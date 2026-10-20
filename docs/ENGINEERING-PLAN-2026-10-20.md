# From D to A: the engineering plan

**Written 2026-10-20 for Andrew Z. and Ronnie.** Companion to `ENGINEERING-AUDIT-2026-10-20.md`. Seven areas scored below B. Each section below opens with the same five-line box (what, why, who, when, done when) so it can be read in a minute, then gives the full detail and the code or configuration to drop in. Everything is additive to Ronnie's repository and follows its conventions: plain PHP, no dependencies, one folder per admin page, `db.php` as the only door to the database. **Ours** means we do it now in our own lane. **Ronnie** means it touches his build, deploy or data and goes to him as a ready-made patch.

## The whole plan on one screen

| # | Area | Now | Target | Effort | Owner | When |
|---|---|---|---|---|---|---|
| 1 | Data at rest | D | A | half a day | Ronnie, Andrew present | This week |
| 2 | Continuous integration | F | A | one day | Ronnie, with our scripts | Next week |
| 3 | Testing | D | B+ | two days | Ours, then Ronnie | Next two weeks |
| 4 | Observability | C+ | B+ | one day | Ronnie | Before the domain moves |
| 5 | Delivery headers | B- | A | half a day | Ronnie, from our file | Before the domain moves |
| 6 | Authentication | B | A- | half a day | Ronnie | Next two weeks |
| 7 | Repository hygiene | C | B+ | half a day each repo | Both | Ongoing |

About six working days over three weeks. Nothing here blocks the site work. Tracker: POMDR-50 (section 1), POMDR-51 (section 4), POMDR-17 (section 5), POMDR-46 (sections 5 and 6 are in the harmony proposal).

---

## 1. Data at rest: D to A

**At a glance**

- **What:** get the sixteen production database dumps out of git, for good.
- **Why:** they hold people's names, addresses, phones, emails, clinic notes and admin password hashes, and git keeps them in every clone forever.
- **Who:** Ronnie, with Andrew present for the one forced push.
- **When:** this week. Half a day.
- **Done when:** `db/backups/` is ignored, history no longer contains it, backups land in R2 with retention, admin passwords are rotated, the repository is under 100 MB.

**The details**

1. **Stop writing to the repository.** `bin/backup-db.php` currently writes to `db/backups/`. Change its destination to a path outside the repo (`/var/backups/pomdr/`) and upload each dump to the R2 bucket the photo sync already uses, under `backups/db/YYYY-MM-DD.sql.gz`. The R2 client code exists in `includes/r2.php`.
2. **Retention.** In the Cloudflare dashboard, an R2 lifecycle rule on the `backups/db/` prefix: delete after 400 days. Keep the first of each month by a second rule or by the backup script naming monthlies differently. Thirty daily and twelve monthly is the target.
3. **Ignore the folder.** Add `db/backups/` to `.gitignore` and commit.
4. **Remove the files from history.** On a fresh clone, once:
   ```bash
   pip install git-filter-repo
   git filter-repo --path db/backups --invert-paths
   git remote add origin git@github.com:ronniereid/pomdr.git
   git push --force origin main
   ```
   Then on the admin box, which pulls with `--ff-only` and will refuse the rewritten history: `git fetch origin && git reset --hard origin/main`. Every other clone (Andrew's `New-Build/`, Ronnie's laptop) does the same once. Andrew is present so nothing is lost by surprise.
5. **Rotate the admin passwords.** Every row in `admin_users` with `bin/create-admin-user.php`, because the hashes were in the dumps. Strong hashes resist cracking, but rotation costs five minutes and removes the question.
6. **Ask GitHub to purge.** GitHub support will remove unreachable objects from their servers on request when sensitive data is involved; a forced push alone leaves them cached for a time.
7. **Check.** `git rev-list --all --objects | grep db/backups` returns nothing; `du -sh .git` is under 100 MB.

## 2. Continuous integration: F to A

**At a glance**

- **What:** every push to main is checked and the whole site rendered before the admin box can pull it.
- **Why:** today nothing runs on push, and the box pulls within five minutes. A broken `db.php` takes the admin, the importers and the build down together, and staff find out first.
- **Who:** Ronnie adds the workflow; we write the seed and page-list scripts.
- **When:** next week. One day.
- **Done when:** a red check on GitHub blocks the push, and a green one means every page rendered without a PHP error.

**The details**

1. **The workflow.** Save as `.github/workflows/check.yml`:
   ```yaml
   name: check
   on: [push, pull_request]
   jobs:
     check:
       runs-on: ubuntu-latest
       services:
         mysql:
           image: mysql:8.4
           env: { MYSQL_ROOT_PASSWORD: root, MYSQL_DATABASE: pomdr_hub }
           ports: ['3306:3306']
           options: --health-cmd="mysqladmin ping" --health-interval=5s --health-retries=10
       steps:
         - uses: actions/checkout@v4
         - uses: shivammathur/setup-php@v2
           with: { php-version: '8.4', extensions: pdo_mysql, mbstring, gd }
         - run: php bin/check.php
         - run: cp includes/config.local.php.example includes/config.local.php
         - run: php bin/seed-ci.php
         - run: php tests/unit/run.php
         - run: |
             set -e
             for p in $(php bin/list-pages.php); do
               php build/render-page.php "$p" > /tmp/page.html 2> /tmp/err.txt
               if [ -s /tmp/err.txt ] || [ $(wc -c < /tmp/page.html) -lt 2000 ]; then
                 echo "FAIL $p"; cat /tmp/err.txt; exit 1
               fi
             done
   ```
2. **`bin/seed-ci.php` (ours).** Applies `db/schema.sql` and every file in `db/migrations/` in order, then inserts six dogs (two adoptable with photos, one foster-needed with a temporary window, one hospice, one courtesy, one adopted), three team rows across two groups, two events (one past, one future), so every page has something to render. Uses the same `db_run()` helpers; no fixtures framework.
3. **`bin/list-pages.php` (ours).** Ten lines: requires `build/build.php`'s `page_urls()` and prints one URL per line, plus `/pets/<slug>/` for the seeded dogs and `/team/<slug>/` for the seeded people.
4. **Branch protection.** GitHub, Settings, Branches, `main`: require status checks to pass (`check`), nothing else. No pull-request requirement, so the one-branch workflow stays as it is; a red check simply refuses the push.
5. **The example config.** `includes/config.local.php.example` must point at `127.0.0.1`, `root`, `root`, `pomdr_hub`, with every API key blank. The importers are not run in CI, so blank keys are fine.
6. **Check.** Push a commit that breaks one page on purpose; the check goes red within three minutes; revert; green.

## 3. Testing: D to B+

**At a glance**

- **What:** the browser suites move into the repository, a dependency-free unit runner covers the pure functions, and each importer gets one fixture test.
- **Why:** one syntax check is the whole suite today. Our checks exist but live outside the repo where Ronnie cannot run them.
- **Who:** ours for the suites and the unit runner; Ronnie for the importer tests.
- **When:** the next two weeks. Two days of ours.
- **Done when:** `php tests/unit/run.php` runs in CI, `tests/browser/` runs against the dev server with one command, and the release checklist names both.

**The details**

1. **Browser suites into `tests/browser/` (ours).** The stress harness (ten persona journeys), the large-text sweep (320 to 1280px at 150 and 200 percent), the per-page audit (titles, headings, alt text, targets, overflow), the no-script coverage check and the logo and form checks. Playwright only, no build step, a `package.json` in `tests/browser/` only (the repository's no-dependency rule is about the application; the tests are not shipped). A `tests/README.md` says: start the dev server, `npm ci` in `tests/browser/`, `node stress.mjs 3 100`, and what each number means.
2. **Unit runner (ours).** `tests/unit/run.php`: a list of cases, each a function, its arguments and the expected result; prints one line per case and exits non-zero on any failure, the way `bin/check.php` reports. First cases: `pet_meta_line`, `pet_status_badges`, `pet_temp_foster_open`, `pet_temp_foster_dates`, `format_event_when`, `event_calendar_url`, `pet_story_paragraphs`, `public_profile_where`. These are the functions whose wrong answer shows on a public page.
3. **Importer fixture tests (Ronnie).** For `sync-shelterluv.php`, `import-lgl.php` and `sync-connections.php`: a small JSON fixture per importer, run against the CI database, then assert the rows written. These scripts are the ones that can corrupt data and the ones nobody wants to run by hand to check.
4. **Release checklist.** `tests/README.md` ends with the list run before any push that touches layout or data: check, unit, render, the three journeys the change touches, the large-text sweep.
5. **Check.** CI runs the unit tests; the browser suites run locally in under ten minutes.

## 4. Observability: C+ to B+

**At a glance**

- **What:** someone is told within minutes when the build fails, when PHP errors pile up, or when the public site stops answering.
- **Why:** today Slack hears about intakes and the heartbeat, and nothing else. A failed hourly build leaves the public site stale with no signal.
- **Who:** Ronnie. Tracker POMDR-51.
- **When:** before the domain moves. One day.
- **Done when:** a deliberately broken build posts to Slack within the hour, the uptime monitor alerts on a 500, and the daily digest posts when the log is not empty.

**The details**

1. **Build failure to Slack.** The hourly cron runs `php build/build.php --push`. Wrap it so a non-zero exit posts the last twenty lines through the existing `slack_notify()` in `includes/slack.php`, to the channel the intake alerts use. Five lines in the cron script or in `build.php`'s exit path.
2. **Uptime.** A free external monitor (Cloudflare Health Checks, UptimeRobot or Better Stack) on `https://www.pomdr.org/` and one dog page, every five minutes, alerting Slack and Andrew's phone. Two checks and no code. The dog page matters because the home page can be fine while the database-driven pages are stale.
3. **Error digest.** A daily cron, same pattern as `bin/audit-images.php`: count the last 24 hours of lines in the PHP error log, post the count and the three most common messages to Slack, say nothing when the count is zero. Fifteen lines.
4. **Build staleness.** The build writes a timestamp into the output (a comment in `index.html` or a `/build.txt`); the uptime monitor can check that the public home page's build time is under two hours old. Optional, but it catches the case where the build runs and silently publishes nothing.
5. **Later, once live:** Sentry's free tier. Its PHP SDK is one vendored file and one line in `config.php`, and gives stack traces with the request that caused them. The one place a third-party service earns its keep.

## 5. Delivery headers: B- to A

**At a glance**

- **What:** the public site sends a strict Content-Security-Policy, HSTS, framing and permissions policies, and caches hashed assets for a year.
- **Why:** today only Referrer-Policy and nosniff are set, and every asset revalidates on every visit despite carrying a content hash. The public pages have zero inline scripts, so a strict policy is achievable without rewriting anything.
- **Who:** Ronnie, since the build writes the file. We supply it. Tracker POMDR-17.
- **When:** before the domain moves. Half a day.
- **Done when:** `curl -I https://www.pomdr.org/` shows all six headers, the browser console is clean on the home, a dog page, a form page and the videos page, and repeat visits fetch no assets.

**The details**

1. **The file.** `build.php` writes `_headers` into the output beside `404.html`, through `write_if_changed()` so the output stays byte-stable:
   ```
   /*
     Content-Security-Policy: default-src 'self'; img-src 'self' https://images.pomdr.org https://www.pomdr.org https://www.peaceofminddogrescue.org data:; script-src 'self' https://stats.imerge.net; style-src 'self'; font-src 'self'; frame-src https://www.youtube-nocookie.com https://secure.lglforms.com; connect-src 'self' https://stats.imerge.net; frame-ancestors 'none'; base-uri 'self'; form-action 'self' https://secure.lglforms.com
     Strict-Transport-Security: max-age=31536000; includeSubDomains
     X-Frame-Options: DENY
     Referrer-Policy: strict-origin-when-cross-origin
     Permissions-Policy: camera=(), microphone=(), geolocation=()
     X-Content-Type-Options: nosniff

   /assets/*
     Cache-Control: public, max-age=31536000, immutable
   ```
2. **The one inline style.** There is a single `style=""` attribute in the public pages; move it to a class so `style-src 'self'` holds. (Ours; a five-minute change.)
3. **Report first.** For one build, use `Content-Security-Policy-Report-Only` with the same value and read the console on four pages. Anything blocked is a host missing from the list (the old photo hosts, until POMDR-6 moves the pictures to R2). Then switch to enforcing.
4. **HSTS preload.** Once the domain has served HSTS for a month without trouble, add `; preload` and submit to the browser preload list. Optional.
5. **Check.** `curl -I` on the live site; a browser console with no CSP reports; the Network panel showing `(memory cache)` for assets on a second visit.

## 6. Authentication: B to A-

**At a glance**

- **What:** the admin login slows down and locks out after repeated failures.
- **Why:** `attempt_login()` has no delay, counter or lockout. Cloudflare Access is the first lock; the second lock should hold on its own.
- **Who:** Ronnie. In the harmony proposal (POMDR-46).
- **When:** the next two weeks. Half a day.
- **Done when:** ten wrong passwords in fifteen minutes lock the email and the IP for fifteen minutes, each failure costs a second, and the login page says so plainly.

**The details**

1. **Migration 034.** A `login_attempts` table: `id`, `email`, `ip`, `failed_at`. Guarded in code the way `animals_has_column()` guards columns, so the code can land before the migration runs.
2. **In `attempt_login()`:**
   - Count rows for this email, and separately for this IP, with `failed_at` in the last fifteen minutes.
   - If either count is ten or more: `usleep(1000000)`, return false without checking the password, and log the lockout.
   - On a wrong password: insert a row, `usleep(1000000)`, return false.
   - On success: delete the email's rows, then the existing `session_regenerate_id(true)`.
   - A nightly cron deletes rows older than a day.
3. **The login page.** For the locked case: "Too many attempts. Try again in fifteen minutes, or ask Andrew to reset your password." Still vague about whether the address exists.
4. **Optional, later: a second factor.** TOTP (the six-digit app code) for the staff with write access. PHP's standard library has no TOTP, so this is the one place a single vendored file (an RFC 6238 implementation, about sixty lines, reviewed) is justified. Codes are stored as a secret per admin user; recovery is Andrew resetting it.
5. **Check.** Eleven wrong passwords from one machine: the eleventh returns the lockout message without touching the database's password check; the right password works again fifteen minutes later.

## 7. Repository hygiene and code shape: C to B+

**At a glance**

- **What:** Ronnie's repository drops the dumps and splits its 5,458-line data layer by domain as it is touched; the handoff repository archives the Divi-era theme and prototype and moves large binaries out.
- **Why:** the data layer is the file a newcomer will fear; the handoff repository carries 868 files, of which a few hundred are from a design direction abandoned in September.
- **Who:** Ronnie for his; ours for the handoff.
- **When:** ongoing; the handoff archive is half a day.
- **Done when:** `db.php` is a thin file requiring five domain files; the handoff repository is `docs/`, `design-system/`, `scripts/`, the brand guides and `CLAUDE.md`.

**The details**

**Ronnie's repository**

1. Section 1 removes 285 MB and the PII; the repository goes from 584 MB to under 100 MB.
2. **Split `db.php` by domain** as functions are touched, never in a sweep: `db-core.php` (connection, the three helpers, `animals_has_column()`), `db-pets.php`, `db-people.php`, `db-events.php`, `db-jobs.php`. `db.php` becomes five `require_once` lines, so every caller stays the same. The longest functions (`save_pet()` at 197 lines, `search_pets_where()` at 129) split into validate, map, write when next edited.
3. **Types.** `declare(strict_types=1)` at the top of each file as it is edited, and parameter and return types on the functions touched. Never a sweep; a sweep is where regressions hide.
4. **The floor.** A `.php-version` file containing `8.4`, so the PHP requirement is machine-readable as well as in the README.
5. **The twelve SQL calls outside `db.php`** (admin edit, uploads, jobs, five bin scripts) move into `db.php` when those files are next touched. All already use placeholders; this is a boundary tidy, not a hole.

**The handoff repository (ours)**

1. Tag the current main as `archive/divi-era-2026-07`, then remove `divi-child-integration/` (169 files), `pomdr-website/`, `Divi/`, `wp-mu-plugins/`, `theme.json`, `responsive-review/`, the summer screenshots and the `node_modules` remnants from main. Everything stays reachable under the tag, as the ten branches do.
2. Move the 20 MB brand PDF and the 10 MB photographs under `pomdr-website/project/uploads/` to the shared drive; the brand guide keeps a link.
3. What stays: `docs/`, `design-system/`, `scripts/`, `BRAND-GUIDELINES.md`, `VOICE.md`, `DESIGN-TOKENS.md`, `CLAUDE.md`, `README.md`. The README's first paragraph says the repository is now documentation and the archive tag holds the Divi era.

## 7b. Reproducible schema (found while building section 2)

**At a glance**

- **What:** `db/schema.sql` becomes the whole current structure, regenerated from production whenever a migration lands.
- **Why:** today it holds six tables, and the migrations cannot be replayed on an empty server (nine refuse; `animals_clinic` and `edit_locks` are never created). Nobody can stand up a copy from the code alone.
- **Who:** Ronnie. Fifteen minutes each time, or one line in the deploy script after a migration.
- **When:** with the first migration after 033.
- **Done when:** `mysql < db/schema.sql` on an empty server gives the same 26 tables as production, and CI loads it instead of `tests/ci/schema.sql`.

**The details:** `mysqldump --no-data --skip-comments --compact --skip-triggers pomdr > db/schema.sql`, with a header saying migrations are history and this file is the present. CI already works around it with the snapshot under `tests/ci/`, which carries the same command at its top.

## 8. What we can do today without waiting for anyone

- Write `bin/seed-ci.php`, `bin/list-pages.php` and the workflow file, and hand them to Ronnie as one patch (section 2).
- Move the browser suites under `tests/browser/` with the README, and write the unit runner with its first eight cases (section 3).
- Write the `_headers` file and move the one inline style to a class (section 5).
- Archive the Divi-era folders in the handoff repository (section 7).

That list is one day. Sections 1, 4 and 6 are Ronnie's, and are filed: POMDR-50, POMDR-51, and the harmony proposal.
