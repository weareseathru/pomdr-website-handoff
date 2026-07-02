# Pre-Launch Risk Register

A failure-mode review for the POMDR redesign, framed by the operating reality:
the site has been live about 5 years and serves roughly 1,000 visitors a day,
edited by non-technical staff, with an older-skewing audience. The goal is to
build it right the first time without over-constraining the parts that should
stay loose.

Findings are grounded in the actual code on `feature/divi-integration-v2` and
were verified against the files cited. Line numbers drift as code changes;
re-grep before acting. This is a living document: update the Status column as
items move.

Status legend: `open` (not started) | `in progress` | `done` | `blocked`
(needs a decision or live access) | `wontfix` (deliberately left loose).

---

## Top 7, do these first

Ranked by impact times likelihood divided by effort.

| # | Item | Effort | Status | Notes |
|---|------|--------|--------|-------|
| 1 | Remove per-request `flush_rewrite_rules()` | XS | **done** | functions.php: now flushes on `after_switch_theme` only. Flush permalinks once after deploy. |
| 2 | Resolve the status-vocabulary fork (A1) | S (paper) | **done** | DECIDED 2026-06-09: live Title-Case checkbox is canonical. CLAUDE.md §8 and the ACF export reconciled. Live queries were already correct; unchanged. |
| 3 | Full production crawl + real redirect map (D1) | M | **open** | `redirects.csv` is an admitted stub. Top SEO task. Needs a crawl of www.pomdr.org. |
| 4 | Fix adoption-form prefill + drop hardcoded staging URL (B1) | S | **in progress** | Hardcoded `new.pomdr.org` removed (now `home_url()`). Full `field_21` prefill wiring still needs the confirmed flow. |
| 5 | Decide integer-ID vs slug canonical dog URL (A3) | S (paper) | **done** | DECIDED 2026-06-09: integer `/pets/{ID}/` canonical. `redirects.csv` updated to drop the slug 301s. |
| 6 | Backup + uptime + 404 log (I1, J1) | S | **open** | Config/hosting, not repo code. Converts silent multi-day failures into same-hour alerts. |
| 7 | Reconcile ACF reference to live schema + consistent output escaping (A2, H1) | M | **in progress** | A2 DONE: real ACF export committed (`acf-export-2026-06-09.json`), aspirational `acf-fields.php` removed. Escaping pass on shortcodes (H1) still open. |

---

## A. Dog data integrity (highest-risk area)

### A1. Status vocabulary exists in three incompatible forms  `done`
RESOLVED 2026-06-09. The real ACF export confirms the live `status` field is a
multi-value **checkbox** with Title-Case choices: `Adoptable`, `Foster Needed`,
`Sponsor Needed`, `Adoption Pending`, `Adopted`, `Hospice`, `Courtesy Listing`.
That is now the canonical storage vocabulary (CLAUDE.md §8). The live queries
(`in_array('Adoptable', $status)`, etc. in `functions.php` and
`single-pets.php`) were already correct against it, so no code changed. The
aspirational kebab-case `inc/acf-fields.php` that contradicted it was removed
(see A2). The kebab-case slugs survive only as a CSS/display convention with a
documented mapping. Note: the live set adds `Sponsor Needed` (with a
`sponsored_by` field and a sponsor PDP section), which the prototype's original
7-status model did not have.

### A2. ACF reference file uses different field names than live  `done`
RESOLVED 2026-06-09. Andrew provided the real ACF export; it is committed at
`divi-child-integration/acf-export-2026-06-09.json` (5 field groups + 3 CPT
registrations) as the authoritative, version-controlled copy. The aspirational,
mismatched `inc/acf-fields.php` (which used `age_years`, `weight_lb`,
`foster_date_start/_end` and kebab-case status) was removed. The README and
CLAUDE.md now point at the export and document the real field names.
- Later-stage recommendation: enable ACF local JSON sync (an `acf-json/` dir
  with one file per group) so future field changes are version-controlled
  automatically. Deliberately not enabled now to avoid changing runtime during
  the prototype stage.

### A3. Integer-ID vs slug URL contradiction  `done`
RESOLVED 2026-06-09. DECIDED: integer `/pets/{ID}/` is canonical (already in
code via the rewrite rule and the `post_type_link` filter, already indexed for
years, and rename-safe). `redirects.csv` was updated to remove the
`/pets/{id}/` to `/pets/{slug}/` 301 rows (they would have redirected away from
the canonical URL) and to point the legacy `dog.php?id=N` redirect at
`/pets/{N}/`. Caveat carried in the CSV: confirm the legacy id equals the new
post ID before enabling the `dog.php` handler; if not, a `legacy_id` lookup is
needed.

### A4. `flush_rewrite_rules()` on every request  `done`
Was called inside an unguarded `init` hook (functions.php ~1633), a DB write on
every page load. Replaced with an `after_switch_theme` flush.
- Deploy note: on an already-active theme, flush once after deploy
  (Settings > Permalinks > Save, or `wp rewrite flush`).

### A5. "Recently adopted" date parsing  `mostly resolved`
The real ACF export shows `date_adopted` is already a **date_picker** with
`return_format` `m/d/Y`, which matches the parser
(`DateTime::createFromFormat('m/d/Y', ...)`, functions.php ~1110). So the
free-text risk is much lower than first feared: the picker enforces the format.
- Residual risk: legacy values entered before the field became a picker, or
  any code path using `strtotime`, could still mis-parse. Low.
- Get ahead of it (optional): make the parser tolerant as a belt-and-suspenders
  measure. Not a launch blocker.

---

## B. Forms, donation, Mailchimp (unconfirmed integrations)

### B1. Adoption-inquiry CTA points at three different targets  `in progress`
`single-pets.php` passes `dogname` to an internal `/adoption-questionnaire/`
page; `inc/enqueue.php` localized a hardcoded `new.pomdr.org` staging URL; the
confirmed working form (STACK.md) is the LGL iframe `utzjcNEZaqAcJk3QURlQmw`
prefilled by `field_21`. These are not wired together, so prefill likely never
reaches LGL.
- Done this session: removed the hardcoded staging host (now `home_url()`), so
  no staging URL leaks onto production.
- Still owed: confirm the one real flow with Andrew, then make `single-pets.php`,
  `enqueue.php`, and the `/adoption-questionnaire/` page agree (the page must
  embed the LGL iframe and map the incoming param to `field_21`).
- Do not over-engineer: keep the LGL iframe for launch. Do not migrate to a
  native forms plugin now (new spam surface, new staff training, no launch
  benefit).

### B2. Mailchimp signup has no backend  `open`
The homepage newsletter input is presentation-only (STACK.md §4). Email is
load-bearing (site content feeds Mailchimp and Metricool). A dead form silently
loses subscribers.
- Get ahead of it: confirm audience ID and opt-in mode, then use Mailchimp's
  hosted embed form (no API key on the server, no PII handling).

### B3. Donation processor  `done (impl deferred)`
RESOLVED 2026-06-09. CONFIRMED: donations use LGL form
`62FAoG7Obtf81TYETJMN3Q`, embedded as an iframe on the donation page. The exact
embed snippet (centered, max-width 900px, with the LGL resize script) is
recorded in STACK.md §2 for the production stage. Per Andrew, this is a local
prototype; the iframe goes onto the production `/donation/` page in a later
stage, not now.
- Still open: the prototype `donate.html` and several `POMDRDonation.php?fund=`
  links (Max's, Silver Hearts, etc.) were not part of this confirmation.
  Confirm whether each fund-specific donation moves to LGL or stays on the
  legacy processor before production.

### B4. Sponsor-a-dog CTA destination unconfirmed  `open`
The dog detail page now renders a "Sponsor {Name}" CTA at parity with the adopt
CTA (added on `feat/sponsor-dog-cta`). Its target is not yet confirmed: it may be
a dedicated LGL sponsor form or a route through the donation form. The button is
built behind a single filterable config point, `pomdr_sponsor_form_url()` in
`functions.php`, whose base URL is the constant `POMDR_SPONSOR_FORM_BASE`. As an
interim for launch parity, that constant points at the current legacy processor
`POMDRSponsorDog.php?dogname={Name}` (the same destination the live legacy detail
pages use), and it carries a `TODO(HUMAN): confirm LGL sponsor form id` marker.
- Decision owner: Andrew (see roadmap D6).
- Get ahead of it: confirm the sponsor form id (or the donation-form route and
  its prefill param), then update the one constant (or add a
  `pomdr_sponsor_form_url` filter). No template change needed.
- Do not over-engineer: keep the single config point; do not scatter sponsor
  URLs across templates.

---

## C. CSS-overlay-on-Divi approach

### C1. `pomdr-design.css` was authored against the prototype DOM, not Divi  `open`
Per `docs/LOCAL-BRIDGE.md`, the design system was ported from the static
prototype's semantic markup; the fit to Divi's `et_pb_*` and
`custom-acf-posts-grid` classes still needs iteration. Tokens apply globally,
but component styles keyed to prototype classes will not land on Divi output.
Only `single-pets.php` is an active override, so every other page renders as
token-restyled Divi.
- Get ahead of it: use the Local WP bridge to map the design onto real Divi
  classes for the pages that matter (home, `/adopt/`, dog detail). Make the
  non-redesigned pages an explicit, documented launch scope.
- Do not over-engineer: do not pixel-match every Divi module. Tokens everywhere
  plus bespoke treatment on 3 to 4 key pages is the right 80/20.

### C2. Heavy `!important` will collide with Divi and the customizer  `open`
`style.css` uses `!important` on several selectors. Divi and its customizer
inject competing styles. Staff edits in the customizer may silently win or lose.
- Get ahead of it: keep `!important` only where Divi forces it, document which
  overrides are intentional, and verify customizer behavior on the Local mirror.

---

## D. SEO and redirect/URL continuity

### D1. `redirects.csv` is an explicit stub  `open`
The file itself says a full crawl of www.pomdr.org is required before launch.
A 5-year-old site at 1,000/day has hundreds of indexed URLs (old posts, happy
tails, memorials, events). Launching without the real map means a wave of 404s
and lost rankings.
- Likelihood x Impact: High x High. The classic redesign-kills-SEO failure.
- Get ahead of it: crawl production (Playwright or Screaming Frog), diff against
  the new URL set, load the real map into the Redirection plugin, and use Search
  Console top-pages to prioritize URLs that actually earn traffic.

### D2. `inc/redirects.php` overlaps the Redirection plugin  `open`
The dormant `redirects.php` handler (a `/recources` typo redirect and a
`dog.php?id=N` to `/pets/{ID}/` handler) would double-redirect or loop if active
alongside the Redirection plugin, and its target reinforces the A3 conflict.
- Get ahead of it: choose ONE mechanism. Prefer the Redirection plugin
  (staff-manageable, logged, no deploy). Keep `redirects.php` only for the
  `dog.php?id=N` pattern if the plugin handles it awkwardly, aligned with the A3
  decision.
- Do not over-engineer: redirects in code need a developer and a deploy for
  every change; the plugin lets staff fix a broken link themselves.

---

## E. Accessibility for an older audience

### E1. Inline font-size overrides fight the 16px minimum  `open`
`pet_age_sex_weight` (functions.php ~50) hardcodes inline styles on vitals.
Inline styles bypass the 17px design base and can render below-minimum text for
the exact audience that needs it largest.
- Get ahead of it: move these into `pomdr-design.css` classes; run the axe-core
  and manual font-size pass on dog detail and listing pages.

### E2. Lightbox is good but lacks a focus trap  `open`
`gallery.js` is well built (progressive enhancement, focus restore, Escape and
backdrop close, `aria-modal`), but Tab can leave the dialog to the page behind.
Keep status as text-plus-color, never color alone.
- Get ahead of it: add a small focus trap (one keydown handler). Do not
  gold-plate it.

### E3. Accessibility-checker plugin unconfirmed  `open`
STACK.md §6 lists Equalize Digital Accessibility Checker as required but
unconfirmed. Install on staging as an editor-side guardrail.

---

## F. Non-technical staff editing workflow

### F1. The single-pets Logic Builder is a foot-gun  `open`
`single-pets.php` implements a full per-dog rule engine (`pom_evaluate_rule`,
`pdp_logic`, AND/OR groups, operators). A mis-set rule silently hides a dog's
bio or photos. The code does fall back to sensible defaults when no logic is
set, which is good.
- Get ahead of it: hide the Logic Builder from non-admin roles; document 2 to 3
  locked patterns (hospice, foster-dated) instead of free-form logic.
- Do not over-engineer: do not extend this engine. It already does more than the
  team needs. Lean on the defaults.

### F2. Org identity is hardcoded in many places  `open`
EIN, addresses, phone, and email are hardcoded across templates and JS
(enqueue.php). Changing a phone number should not need a developer.
- Get ahead of it: put org identity in one place (ACF options page) and pull it
  everywhere. One-time setup, permanent staff autonomy.

---

## G. WordPress/Divi/plugin fragility over years

### G1. Divi major updates can break the overlay  `open`
The redesign depends on `et_pb_*` markup, `#main-header`, `#main-content`, and
Divi enqueue ordering. Divi ships frequent, sometimes structural updates (Divi 5
changes the DOM).
- Get ahead of it: pin Divi to a known-good version, test updates on the Local
  mirror before production, keep `pomdr-nav.js`'s defensive header fallback,
  budget for a Divi 5 compatibility pass.
- Do not over-engineer: do not build a version-agnostic shim. Pin, test, update
  deliberately.

### G2. `style.css` still identifies as "Divi Child / Elegant Themes"  `open`
The header reads Theme Name "Divi Child", Author "Elegant Themes", Version
1.0.2, so there is no clear version signal for what is deployed.
- Get ahead of it: bump the version on each deploy. Minor housekeeping.

---

## H. Security and spam

### H1. Inconsistent output escaping in shortcodes  `open`
Many shortcodes escape correctly, but several echo unescaped image src, alt,
title, bio, and description (functions.php 490, 600, 707, 792, 1033, 1511, 1577).
Inputs are mostly staff-authored, but a compromised editor account becomes
stored XSS.
- Get ahead of it: apply `esc_url`, `esc_attr`, `esc_html`, `wp_kses_post`
  consistently, prioritizing fields that can hold HTML (`bio`, `pet_description`).
  Mechanical and low-risk.
- Do not over-engineer: no security framework needed; the core escaping
  functions are the WordPress-standard answer.

### H2. Form and contact spam  `open`
Public forms at this traffic attract bots. LGL handles its own anti-spam. A new
native `/contact/` form would be a target.
- Get ahead of it: keep inquiry on LGL where possible; if a native form is
  added, use a honeypot, not a CAPTCHA.
- Do not over-engineer: avoid reCAPTCHA image grids for a senior audience (they
  are time-pressured and violate the accessibility rules). A honeypot is
  invisible, accessible, and sufficient.

---

## I. Backup, disaster recovery, rollback

### I1. DR posture is largely TBD  `open`
Content (95 dogs, ACF values, media) lives only in the database, pulled down via
WP Migrate DB. There is no stated backup cadence, no rollback runbook, and the
repo is private on a free GitHub account (no branch protection). A bad deploy or
DB corruption has no defined recovery path.
- Get ahead of it: confirm the host's automated backup cadence and retention,
  add an independent scheduled backup (UpdraftPlus or host snapshots) for DB plus
  uploads, and write a one-page rollback runbook (the symlink/backup pattern in
  LOCAL-BRIDGE.md is a good start).
- Decision owner: Andrew (hosting).

### I2. Manual deploys  `open`
Deploy is manual SFTP or git pull (LOCAL-BRIDGE.md). Manual deploys at the worst
moment are where mistakes happen.
- Get ahead of it: document the exact deploy steps as a checklist.
- Do not over-engineer: no CI/CD pipeline for a single-developer nonprofit; a
  written, tested checklist is the right control.

---

## J. Content lifecycle, observability, browser matrix

### J1. No observability  `open`
No uptime monitoring, error logging, or 404 report. The A1/A2 failures show as
empty grids, not HTTP errors, so they would go unnoticed.
- Get ahead of it: add a free uptime monitor (UptimeRobot) on the homepage and
  `/adopt/`, enable the Redirection plugin's 404 log, and add one synthetic check
  that `/adopt/` renders at least one dog card.
- Do not over-engineer: no APM. A ping monitor plus the 404 log plus one content
  assertion is right-sized.

### J2. Content lifecycle accumulation  `open`
The events shortcode (functions.php ~1411) does not filter out past events, so
old events linger. No stated lifecycle for adopted dogs or memorials.
- Get ahead of it: add a date filter to the events query (only upcoming).
  Document the dog lifecycle (adopted becomes happy tail; memorial is a status).

### J3. Browser/device matrix unstated  `open`
Older users skew to older devices, Safari, and high browser zoom. Most CSS
features used are well supported, but reflow at 200% zoom needs verification.
- Get ahead of it: test at 200% zoom and on a real older iPad/iPhone, not just
  responsive emulation.

---

## Decisions from Andrew

Resolved 2026-06-09:

1. ~~Status vocabulary (A1)~~ DONE. Live Title-Case checkbox is canonical.
2. ~~Dog URL canonical (A3)~~ DONE. Integer `/pets/{ID}/` canonical.
3. ~~Donation processor (B3)~~ DONE. LGL form `62FAoG7Obtf81TYETJMN3Q`.
4. ~~Real ACF export (A2)~~ DONE. Committed as `acf-export-2026-06-09.json`.

Still open:

5. **Hosting and backups (I1).** Host, PHP/WP versions, and current backup
   cadence, so DR can be specified. (Deferred: this is a local prototype today;
   revisit at the production-deploy stage.)
6. **Fund-specific donations (B3).** Do the `POMDRDonation.php?fund=` funds
   (Max's Helping Paws, Silver Hearts, Helping Paw, tributes) move to LGL or
   stay on the legacy processor?
7. **Canonical donation path.** Is it `/donate/` or `/donation/`? The prototype
   and redirects use `/donate/`; the LGL embed instruction said `/donation/`.

---

## Judgment

The trajectory is sound and the strategy was right. Keeping Divi over a clean
FSE rebuild fits a non-technical four-person staff: it preserves their editing
surface, the working ACF `pets` data, and the LGL/Redirection setup, and avoids
a risky full migration. The design system, progressive-enhancement JS, voice
rules with CI, the Local WP bridge, and the deliberate "what loads and what does
not" discipline all show real care.

The exposure is not the strategy but a handful of unreconciled contradictions
sitting in the code: the status vocabulary in three forms, the dog URL integer
in code but slug in the redirect map, the ACF reference using different field
names, and the adoption-form prefill wired three ways. Each fails silently and
several touch the core adopt flow, so they must close before launch. The main
thing to guard against is the opposite of the stated fear: not under-building,
but over-building the parts that should stay loose (the Logic Builder, redirects
in code, slug URLs, heavy CAPTCHAs), which would slow the staff workflow and sap
the warm, low-friction feel that is the whole point.
