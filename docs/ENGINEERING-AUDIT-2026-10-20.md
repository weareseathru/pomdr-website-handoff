# Engineering audit: the POMDR codebase against current industry standards

**Written 2026-10-20 for Andrew Z.** Scope: Ronnie's repository (`github.com/ronniereid/pomdr`, the code that runs the admin, the importers, the hourly build and the public site) and, briefly, the handoff repository. Every claim below was measured on the code as of main `889e45f` (handoff) and `011906c` (Ronnie) on 2026-10-20; the method is a line-by-line scan plus live checks against the published site. The standard applied is what a senior engineer at a well-run product company would expect of a small production system in 2026: secure by default, observable, tested, reproducible, and simple enough for the next person.

The short version: **this is a well-built small system with one serious problem and three real gaps.** Its architecture is deliberately plain and that is a strength, not a weakness. Its security basics are right. Its weaknesses are the kind that come from one careful person working alone: no automated tests, no CI, sensitive data committed to git, and no defence against a password-guessing script.

---

## 1. What is being judged

| Part | Size | Role |
|---|---|---|
| `includes/` | 13,700 lines, 31 files | The data layer (`db.php`, 5,458 lines, 227 functions), auth, images, the page chrome, the kit partials |
| `admin/` | 9,200 lines, 26 files | The staff back office, behind Cloudflare Access and a login |
| `public/` | 7,400 lines, 41 pages | The public site, rendered to static HTML by the build |
| `bin/` | 6,500 lines, 22 scripts | Importers (Shelterluv, LGL), photo sync to R2, backups, checks |
| `build/` | 5,200 lines | The hourly static build to the public repository and Cloudflare Pages |
| `db/migrations/` | 33 files | Schema, applied by hand |
| Stylesheet and scripts | 6,954 lines CSS, 1,369 lines JS | One stylesheet, five small scripts, no framework |
| Dependencies | **none** | No Composer, no npm, no framework |
| Documentation | 20 top-level Markdown files, 27 numbered rules in README | Unusually complete |

## 2. Scorecard

| Area | Grade | One line |
|---|---|---|
| Architecture | A- | Plain PHP, one data layer, static public output, additive migrations. Deliberately boring, and right for a four-person rescue. |
| Security: input and output | A- | Every query is a prepared statement; output is escaped through one function; eleven of eleven admin forms carry a CSRF token. |
| Security: authentication | B | Password hashing, session regeneration, secure cookies, Cloudflare Access in front. No throttling on the login form. |
| Security: data at rest | **D** | Sixteen full database backups, 285 MB, with people, addresses, phones, emails and clinic records, are committed to a GitHub repository. |
| Security: delivery | B- | Referrer-Policy and nosniff are set. No Content-Security-Policy, no HSTS header from Pages, no long cache on hashed assets. |
| Testing | **D** | One syntax check. No unit tests, no integration tests, no browser tests in the repository. (Ours live in the design project, outside it.) |
| Continuous integration | **F** | None. Nothing runs on push. The server pulls main every five minutes and builds hourly; a bad commit reaches staff within five minutes. |
| Observability | C+ | Slack alerts, a heartbeat, a job-runs table and a daily image audit. No error aggregation, no uptime check on the public site, no alert when the build fails. |
| Code quality | B+ | Consistent style, comments that explain why, functions that do one thing. A few very long functions in the data layer (197 lines) and one 5,458-line file. |
| Performance | B+ | Static HTML at the edge, Brotli, HTTP/2, fonts preloaded, images sized per slot. Still JPEG only, and assets re-validate on every visit. |
| Accessibility | A | Measured: 100 on every page audited, WCAG AA contrast throughout, 16px floor, 44px targets, works without JavaScript, prints. |
| Documentation | A | Twenty documents that say what and why, written for the next person. Better than most funded startups. |
| Repository hygiene | C | 510 commits, clear messages, one branch. But the backups above, and the repository is 584 MB because of them. |

## 3. Where it is strong, and why that matters

**The architecture is the right one.** No framework, no build step for the application, one stylesheet, pages rendered to static files and served from a CDN. This is not old-fashioned; it is what the industry has been returning to for small sites because it removes whole classes of failure: no dependency vulnerabilities (there are no dependencies), no server to attack for the public site (it is files on Cloudflare), no framework upgrade treadmill. A system this small should be exactly this boring.

**The data layer is a real boundary.** Every SQL statement lives in `includes/db.php` or runs through its three helpers with placeholders. The twelve places outside that file that build SQL use placeholders too (`WHERE id = ?`). The public pages never query the database directly; they call `get_public_*` functions. That discipline is what makes the design-only rule on our side possible and it is rarer than it should be.

**The three masters are never written to.** Shelterluv, Little Green Light and Instinct are read, mirrored and never updated from here. `Hub.md` says why. A write back that goes wrong would corrupt the records a rescue runs on; the architecture makes it impossible rather than merely discouraged.

**Output escaping is one function, used everywhere that matters.** Of roughly a thousand echoes across the public pages and partials, eight print a variable without `e()`, and each of those is a value the code itself chose (a heading tag name, a class name, a count). None prints user or database text raw.

**Authentication is done properly.** `password_verify` against a hash, `session_regenerate_id` on login, cookies marked HttpOnly, Secure over HTTPS, SameSite Lax, a CSRF token on all eleven admin forms, a deliberately vague login error. And Cloudflare Access in front of the whole admin host, so the login form is the second lock, not the only one.

**Migrations are guarded.** New code checks `animals_has_column()` before using a column, so code can land before a migration is run without taking the admin down. That is a thoughtful answer to a real problem of hand-run migrations.

**The documentation is exceptional.** Twenty documents, 27 numbered rules with reasons, a proposals set that says what is unverified. Most teams of fifty do not have this.

## 4. Where it is weak, and what to do

Ranked by risk to POMDR, not by effort.

### 4.1 Sensitive data is in git (fix this week)

`db/backups/` holds sixteen gzipped full dumps of the production database, 285 MB, one per day. Each contains `people`, `people_addresses`, `people_emails`, `people_phones`, `animals_clinic`, `admin_users` and more: donors, adopters, Helping Paw clients, their contact details, and clinic notes. The repository is private, but git is forever: every clone, every laptop, every future contributor and every GitHub incident now carries these. A backup belongs in a bucket with lifecycle rules, not in version control.

- **Do:** move backups to the R2 bucket (the sync infrastructure exists), add `db/backups/` to `.gitignore`, and rewrite history to remove the sixteen files (`git filter-repo`), then force-push once with every clone refreshed. Rotate the admin users' passwords afterwards, since `admin_users` with hashes was in the dumps.
- **Owner:** Ronnie, with Andrew present for the force-push.
- **Standard:** no PII in version control, ever. This is the one item in this audit a security reviewer would stop on.

### 4.2 No automated tests and no CI (fix this month)

`bin/check.php` runs `php -l` and catches one known footgun. That is the whole test suite. Nothing runs on push; the server pulls main within five minutes. A commit that breaks `db.php` takes the admin, the importers and the build down together, and the first person to know is a staff member.

- **Do:** a GitHub Actions workflow on every push to main that runs `php bin/check.php`, renders every public page with `build/render-page.php` against a seeded SQLite or MySQL service, and fails on any PHP error or empty page. Ten lines of YAML and a seed script. Then: a handful of unit tests for the pure functions (`pet_meta_line`, `pet_status_badges`, `format_event_when`, `event_calendar_url`) and one browser smoke test. Our Playwright suites (stress harness, large-text sweep, page audit) can move under `tests/` as proposed on 2026-10-17.
- **Owner:** Ronnie for the workflow; us for the browser suites.
- **Standard:** nothing reaches main without a green check.

### 4.3 The login form can be brute-forced (fix this month)

`attempt_login()` has no delay, no counter, no lockout. Cloudflare Access is the first lock, which is why this is a B and not a D, but Access can be misconfigured, and a second lock should hold on its own.

- **Do:** per-email and per-IP failure counts in a small table or in the session, a one-second sleep after a failure, a fifteen-minute lockout after ten. Twenty lines.
- **Owner:** Ronnie.

### 4.4 Security headers on the public site (fix before launch)

The published site sends Referrer-Policy and nosniff. It does not send a Content-Security-Policy, Permissions-Policy, X-Frame-Options or HSTS, and every asset is `max-age=0` although each carries a content hash. The public pages have zero inline scripts and one inline style, so a strict CSP is achievable.

- **Do:** a `_headers` file written by the build: `Content-Security-Policy` allowing self, the two image hosts, `youtube-nocookie.com` frames, `secure.lglforms.com` frames and `stats.imerge.net`; `Strict-Transport-Security` with a year; `X-Frame-Options: DENY`; `Permissions-Policy` denying camera, microphone, geolocation; and `Cache-Control: immutable` for a year on `/assets/*`. Already sketched in the 2026-10-17 proposal (section 2.4).
- **Owner:** Ronnie (the build is his). We will write it as an addition on request.

### 4.5 Observability has holes (fix before launch)

Staff are told about new intakes and exam-needed dogs on Slack, the heartbeat says the box is alive, and the image audit runs daily. Nobody is told when the hourly build fails, when the public site returns errors, or when an importer throws. `display_errors` is off in production, which is right, but errors go only to the PHP log on the box.

- **Do:** a Slack message when `build.php` exits non-zero; an external uptime check on the public home page and one dog page (free tier of any monitor); a weekly digest of PHP errors from the log. Later, Sentry or an equivalent, which has a free tier and a five-line PHP integration.
- **Owner:** Ronnie.

### 4.6 Code shape in the data layer (improve over time)

`db.php` is 5,458 lines and 227 functions. `save_pet()` is 197 lines; `search_pets_where()` 129. These are not bugs, but they are the files a new contributor will fear. No `declare(strict_types=1)` anywhere; no type hints on most parameters.

- **Do:** split `db.php` by domain (`db-pets.php`, `db-people.php`, `db-events.php`, `db-jobs.php`) with the same function names, so nothing calling them changes; add parameter and return types as functions are touched; break `save_pet()` into validate, map, write. No rewrite.
- **Owner:** Ronnie, incrementally.

### 4.7 Smaller items

- **PHP 8.4 required** with no `composer.json` to pin it and no `.php-version`. State the floor in one machine-readable place.
- **Twelve SQL calls outside the data layer** (admin edit, uploads, jobs, five bin scripts). All use placeholders, so this is a boundary leak rather than a hole; move them into `db.php` when those files are next touched.
- **Image uploads** go through `getimagesize`, GD re-encode and EXIF stripping, which is the right pipeline (re-encoding is what defeats polyglot files). Add an explicit size cap and a MIME allow-list at the entry point if one is not already enforced upstream.
- **Umami analytics** loads from a third-party host on every public page. Cookieless and harmless, and the brand rules still ask for a decision on it. That decision is on the partner list.
- **JPEG only.** WebP beside each JPEG saves roughly a third of image bytes; proposed on 2026-10-17 as an addition to `prepare_image()`.
- **The handoff repository** is 868 tracked files and carries the Divi-era theme, prototype uploads (a 20 MB PDF, several 10 MB photographs) and `node_modules` references from the summer. It holds no secrets. It would be lighter with the Divi theme and prototype folders moved to an `archive/` branch tag and the photographs out of git, but it is a documentation repository and the cost is only clone time.

## 5. The honest comparison

Against a Silicon Valley product team's baseline, the system is **ahead** on architecture simplicity, data-layer discipline, escaping, accessibility and documentation, and would pass a design review there without much comment. It is **behind** on the three things such teams automate and never think about again: tests, CI and secret hygiene. Those three are also the three that do not need a bigger team to fix. A day on CI, an afternoon on login throttling, a morning on the backups, and the headers file that is already sketched would move the weak grades to B or better, and the system would then be stronger than most of what ships from well-funded companies, because it has far less in it to go wrong.

## 6. Order of work

1. Backups out of git, history rewritten, admin passwords rotated. **This week. Ronnie and Andrew.**
2. CI on push: syntax check and a full render. **Next week. Ronnie; we supply the render script and seed.**
3. Login throttling. **Next week. Ronnie.**
4. `_headers` with CSP, HSTS, framing, permissions and asset caching. **Before the domain moves. Ronnie, from our sketch.**
5. Build-failure alert and an uptime check. **Before the domain moves. Ronnie.**
6. Browser suites into `tests/`, unit tests for the pure functions. **Ours, any time.**
7. `db.php` split and typing, as touched. **Ongoing.**

Items 1, 3 and 4 are already covered by tickets or proposals in the tracker (POMDR-17 for the headers; the harmony proposal for the build files). Items 2 and 5 are new and should be filed.
