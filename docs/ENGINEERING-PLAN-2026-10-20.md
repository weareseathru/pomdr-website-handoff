# From D to A: the plan for the weak grades

**Written 2026-10-20 for Andrew Z. and Ronnie.** Companion to `ENGINEERING-AUDIT-2026-10-20.md`. Seven areas scored below B. For each: the target grade, the steps in order, who does them, how long they take, and the code or configuration ready to drop in. Everything here is additive to Ronnie's repository and follows its conventions (plain PHP, no dependencies, one folder per admin page, `db.php` as the only door to the database). Items marked **ours** we can do now in our own lane; items marked **Ronnie** touch his build, deploy or data and go to him as ready-made patches.

| Area | Now | Target | Days of work | Owner |
|---|---|---|---|---|
| Data at rest | D | A | 0.5 | Ronnie, Andrew present |
| Continuous integration | F | A | 1 | Ronnie (workflow), ours (render script) |
| Testing | D | B+ | 2 | Ours, then Ronnie for unit tests |
| Observability | C+ | B+ | 1 | Ronnie |
| Delivery headers | B- | A | 0.5 | Ronnie, from our file |
| Authentication | B | A- | 0.5 | Ronnie |
| Repository hygiene | C | B+ | 0.5 | Ronnie (his repo), ours (handoff) |

Total: about six working days, spread over three weeks, none of it blocking the other work.

---

## 1. Data at rest: D to A (this week)

**Why D.** Sixteen gzipped production dumps in `db/backups/` with people, phones, emails, clinic notes and admin password hashes. Git keeps them forever in every clone.

**Steps**

1. Stop writing them to the repository. `bin/backup-db.php` writes to `db/backups/`; change its destination to a local path outside the repo (`/var/backups/pomdr/`) and push each dump to the R2 bucket the photo sync already uses, under `backups/db/YYYY-MM-DD.sql.gz`, with an R2 lifecycle rule keeping 30 daily and 12 monthly.
2. Ignore the folder: add `db/backups/` to `.gitignore`.
3. Remove the files from history. On a fresh clone, once, with every other clone refreshed afterwards:
   ```bash
   git filter-repo --path db/backups --invert-paths
   git push --force origin main
   ```
   Then on the admin box: `git fetch && git reset --hard origin/main` (the box pulls with `--ff-only`, so it must be reset by hand once).
4. Rotate every row in `admin_users` with `bin/create-admin-user.php`, since the hashes were in the dumps.
5. Ask GitHub support to purge the unreachable objects from the server side (they do this on request for sensitive data).

**Ready to use:** the `.gitignore` line, the two commands above, and an R2 lifecycle rule (`backups/db/` prefix, expire after 400 days, keep the first of each month) in the Cloudflare dashboard.

**Grade after:** A. No PII in version control, backups off-box, retention defined.

## 2. Continuous integration: F to A (next week)

**Why F.** Nothing runs on push. The admin box pulls main within five minutes of a commit.

**Steps**

1. Add the workflow below at `.github/workflows/check.yml`. It runs the existing syntax check, then renders every public page against a seeded MySQL service and fails on any PHP error or empty page.
2. Add `bin/seed-ci.php` (ours to write): creates the schema from `db/schema.sql` plus migrations, inserts six dogs, three team rows and two events, so every page has something to render.
3. Turn on branch protection for `main` in GitHub with "require status checks to pass" and no other restriction, so a red check blocks the push but nobody is forced into pull requests.

```yaml
# .github/workflows/check.yml
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
      - run: |
          set -e
          for p in $(php bin/list-pages.php); do
            php build/render-page.php "$p" > /tmp/page.html 2> /tmp/err.txt
            if [ -s /tmp/err.txt ] || [ $(wc -c < /tmp/page.html) -lt 2000 ]; then
              echo "FAIL $p"; cat /tmp/err.txt; exit 1
            fi
          done
```

`bin/list-pages.php` is ten lines that print the same URL list `build.php` uses (its `page_urls()` function already exists; this exposes it on the command line).

**Grade after:** A. Every push lints and renders the whole site before the box can pull it.

## 3. Testing: D to B+ (two days, then ongoing)

**Why D.** One syntax check is the suite.

**Steps**

1. **Browser suites into the repository (ours, one day).** Move the stress harness, the large-text sweep, the per-page audit and the no-script coverage check from the design project into `tests/browser/`, Playwright only, with a `tests/README.md` that says how to run them against the dev server. They become the release checklist, run by hand before a push that touches layout.
2. **Unit tests for the pure functions (ours, one day).** No framework; a `tests/unit/run.php` that calls each function with known inputs and prints pass or fail, the way `bin/check.php` already reports. Start with: `pet_meta_line`, `pet_status_badges`, `pet_temp_foster_open`, `format_event_when`, `event_calendar_url`, `pet_story_paragraphs`, `public_profile_where`. Add `php tests/unit/run.php` to the workflow.
3. **One integration test per importer (Ronnie, later).** Feed each importer a fixture file and assert the row it writes. These are the scripts that can corrupt data, and the ones nobody wants to run by hand.

**Grade after:** B+. A for unit coverage would need the data layer tested too, which is section 7's job.

## 4. Observability: C+ to B+ (one day)

**Why C+.** Slack hears about intakes and the heartbeat; nobody hears about a failed build, a PHP error, or the public site going dark.

**Steps**

1. **Build failure to Slack.** In `deploy/cron/` the hourly build runs `php build/build.php --push`. Wrap it: on non-zero exit, call the existing `slack_notify()` with the last twenty lines of output. Five lines.
2. **Uptime check.** A free monitor (UptimeRobot, Better Stack or Cloudflare's own health checks) on `https://www.pomdr.org/` and one dog page, every five minutes, alerting Slack and Andrew's phone. Zero code.
3. **Error digest.** A daily cron that greps the PHP error log for the last 24 hours and posts a count and the top three messages to Slack, silent when there are none. Fifteen lines, same pattern as the image audit.
4. **Later:** Sentry's free tier with its PHP SDK is one `require` and one line in `config.php`, and gives stack traces with context. Worth it once the site is live.

**Grade after:** B+.

## 5. Delivery headers: B- to A (half a day)

**Why B-.** No CSP, no HSTS, no framing or permissions policy, assets revalidate on every visit despite content hashes.

**Steps**

1. `build.php` writes a `_headers` file into the output beside `404.html`, through the same `write_if_changed()`:

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

2. The one inline `style=""` attribute in the public pages moves to a class, so `style-src 'self'` holds.
3. Check the policy in report-only mode for one build (`Content-Security-Policy-Report-Only`) and read the console on the home, a dog page, a form page and the videos page, then switch to enforcing.

**Grade after:** A.

## 6. Authentication: B to A- (half a day)

**Why B.** No throttling on `attempt_login()`.

**Steps**

1. A `login_attempts` table (migration 034): `email`, `ip`, `failed_at`. Guarded with `animals_has_column()`'s sibling for tables.
2. In `attempt_login()`: count failures for the email and for the IP in the last fifteen minutes; if either is ten or more, return false without checking the password and log it; on any failure, `usleep(1000000)` and insert a row; on success, delete the email's rows.
3. The login page says "Too many attempts. Try again in fifteen minutes, or ask Andrew to reset your password." for the locked case.
4. Optional, later: TOTP second factor for the three staff with write access. PHP has no dependency-free TOTP in the standard library, so this is the one place a single small vendored file (an RFC 6238 implementation, about 60 lines) is justified.

**Grade after:** A- without TOTP, A with it.

## 7. Repository hygiene and code shape: C to B+ (ongoing)

**Ronnie's repository**

1. Section 1 removes 285 MB and the PII; the repository drops from 584 MB to under 100 MB.
2. `db.php` split by domain as functions are touched: `db-pets.php`, `db-people.php`, `db-events.php`, `db-jobs.php`, `db-core.php`, each `require_once`d from `db.php` so every caller is unchanged.
3. `declare(strict_types=1)` and parameter types added to each file as it is edited, never in a sweep.
4. A `.php-version` file saying `8.4`, so the floor is machine-readable.

**The handoff repository (ours)**

1. The Divi-era theme (`divi-child-integration/`, 169 files), the prototype (`pomdr-website/`), `Divi/`, `wp-mu-plugins/` and the summer screenshots are archived under a tag (`archive/divi-era-2026-07`) and removed from main, as the ten branches were on 2026-10-20.
2. The 20 MB brand PDF and the 10 MB photographs move to the shared drive; the repository keeps the link.
3. What stays: `docs/`, `design-system/`, `scripts/`, the brand guides and `CLAUDE.md`. The repository becomes the documentation it already is in practice.

**Grade after:** B+ for both.

## 8. What we can do today without waiting for anyone

- Write `bin/seed-ci.php`, `bin/list-pages.php` and the workflow file, and hand them to Ronnie as one patch (sections 2 and 3.2).
- Move the browser suites under `tests/browser/` with a README (section 3.1).
- Write the unit test runner and the first seven tests (section 3.2).
- Write the `_headers` file and the report-only step (section 5).
- Archive the Divi-era folders in the handoff repository (section 7).
- File two tracker tasks for Ronnie: the backups out of git with history rewrite (section 1), and the build alert plus uptime check (section 4). The throttle and the headers are already covered by the harmony proposal and POMDR-17.

Say "go" and that list is a day's work, with the patches in Ronnie's conventions and the tests runnable on the local dev server.
