# Native Divi Conversion Plan (council-hardened, v2)

Date: 2026-08-18. Owner: Andrew Z. Status: G0 decided, G1 PASSED, Phase 1
foundation landed (see "Phase 1 progress" below). Next: Divi default
neutralization measured at the pilot, then the generator (Phase 2).

## Phase 1 progress (2026-08-18)

- **CPT self-sufficiency: DONE.** `inc/post-types.php` registers pets,
  events, and team from the theme whenever the ACF database definitions are
  absent (post_type_exists guard at init priority 20, so existing ACF
  records win untouched). Args mirror the live runtime registration.
- **ACF JSON sync: DONE.** The five DB field groups (Pets 14 fields, Events,
  Team, PDP Logic Builder, PDP Sections) are exported to the theme's
  `acf-json/` and load from version control; the save_json filter keeps
  future wp-admin field edits writing back to the folder. The Pets export
  matches the canonical status vocabulary exactly. FLAG for Andrew: the DB
  contains duplicate-key copies of the Pets and Events field groups; the
  duplicates were ignored at export and should be deleted in wp-admin.
- **Global colors seeded and BOUND at the source: DONE, verified by
  read-back.** 11 brand colors (gcid-pomdr-*) stored via
  GlobalData::set_global_colors, and the four Customizer-routed ids
  (gcid-primary-color, gcid-secondary-color, gcid-heading-color,
  gcid-body-color) now carry brand values, which rewrote Divi's own
  accent_color (#008bb0), secondary accent (#632F88), and heading/body text
  (#16202b) options. Divi's default blue-and-gray is gone at the root, which
  is the first and largest piece of default neutralization.
- **Design Variables seeded: DONE, verified by read-back.** Fonts (Source
  Sans 3, Source Serif 4) and numbers (80px/56px section padding, 24/26/20px
  type scale, 22/32px radii) stored via GlobalData::set_global_variables
  under stable gvid-pomdr-* ids the generator will reference. Two API traps
  documented: the getters return stdClass and synthesize extra buckets
  (write payloads must come from the raw option, arrays only), and the
  variables option stores only numbers/strings/images/links/fonts (colors
  live in et_global_data).
- **Remaining Phase 1:** neutralize the rest of Divi's defaults (section/row
  padding, button hover icon and padding shift, Divi Google Fonts loading)
  measured against the pilot page's computed-style diff, evidence-first
  rather than guessed; verify the seeded variables appear in the VB
  variables panel during the pilot's VB smoke.

## G0 decisions (Andrew, 2026-08-18)

1. **Divi-native first, for everything.** Every obstacle gets a genuine
   solve-it-inside-Divi attempt (stock modules, presets, Theme Builder, Loop
   Builder, Divi options) before any custom fallback. The hybrid boundary in
   this plan is now a fallback of last resort per component, not a starting
   assumption. If the native path truly fails for a component, fall back to
   the documented custom approach for that component.
2. Fallback if the conversion itself dies: keep the sidecar theme and ship
   it with the layered deploy (section 8), which alone fixes the original
   deploy failure. Adopted.
3. Design Variables: Option A (bind global colors so pages consume tokens
   natively). Follows from decision 1.
4. Timeline: run at AI pace, compressed from the council's calendar; gates
   stay, waiting does not.

## G1 result (2026-08-18): PASSED

The D4-to-D5 pipeline was ground-truthed on the live local site (test page
3350, draft, slug d5-ground-truth-test). Findings, all now baked into the
validator design:

- Conversion works end to end from wp-cli: requires loading
  `ET_D5_Readiness::includes()` manually (they only load in admin requests)
  and calling `Conversion::initialize_shortcode_framework()` first.
- Output is real D5 blocks: `wp:divi/placeholder` wrapper present, ZERO
  `divi/shortcode-module` fallbacks, parse round-trip byte-stable,
  `_et_pb_divi_4_content` rollback meta stored.
- **Divi's `saveVerification` flag is itself buggy** (it compares saved
  content against `stripslashes()` of never-slashed content, so any page
  containing JSON escapes reports false). Proven by a one-run byte-diff:
  the save cycle is actually lossless. The validator uses its own read-back
  byte-diff, not Divi's flag.
- The converter regenerates custom-attribute UUIDs on every run, so
  cross-run idempotency checks must normalize UUIDs before diffing.
- `module_class` survives as a custom attribute and RENDERS on the front
  end: the test button carries `btn btn-primary` and picks up the design
  system. The cssClass hook strategy works.
- The Visual Builder mounts on the converted page with zero console errors;
  section, row, text, and button appear as editable D5 modules with admin
  labels intact (screenshot verified).
- Theme Builder header/footer sections render identically on sidecar and
  native pages, so baseline parity holds.
- Found and fixed a latent landmine: the DB `siteurl`/`home` options were
  malformed (`https:pomdrsite.local`, no slashes, wrong host), masked in
  browsers by a wp-config override but live in every wp-cli run. Repaired to
  `http://newpomdr-local.local`. All wp-cli conversion runs also pass
  `--url=http://newpomdr-local.local` explicitly.
- Dedicated `vb-smoke` administrator created on local for browser-driven VB
  smoke tests (Andrew's account untouched; its original password hash was
  preserved).

This supersedes the v1 draft. It was stress-tested by a five-seat review
(platform engineer, nonprofit-management PhD, creative executive, delivery
director, and a red-team chair who adjudicated their conflicts). All four
experts returned "sound with amendments." No one recommended killing the
conversion. The amendments below are the load-bearing ones; process that
added ceremony without reducing risk was cut.

---

## 1. Goal (unchanged)

Pages become real Divi 5 modules stored on the page records, pixel-faithful
to the current design, editable by staff in the Visual Builder, deployable to
new.pomdr.org without destroying content there. The current design stays
EXACTLY the same. Freeze point: branch `design-freeze-sidecar-v1`, tag
`v1-sidecar-design`, both on GitHub.

## 2. What changed from the v1 draft (the five big corrections)

1. **The All-in-One full-site import is out as the deploy mechanism.**
   A full DB overwrite of new.pomdr.org destroys every dog, event, and form
   entry staff touched since the export, on every redeploy, forever. This is
   the same failure class as the original deploy disaster, in a worse form.
   Replaced by a layered deploy (section 8). All-in-One is kept only for the
   disposable fresh-local rehearsal.
2. **True scope is 42 renderers, not ~25** (40 page templates plus
   front-page.php plus single-pets.php). Verified against the repo. With the
   scope cuts below, the conversion path is roughly 30 pages.
3. **The pilot expands from one page to three content classes** (static,
   CPT-loop, LGL form) and adds the project's real go/no-go test: one
   non-technical staff member edits a headline and swaps a photo in the
   Visual Builder, unaided, in under 15 minutes. The plan previously tested
   the core premise (staff can drive the VB) last. It now tests it first.
4. **The validator must catch silent conversion failure.** Divi's converter
   hardcodes status "success" and quietly wraps anything it cannot convert in
   `divi/shortcode-module` blocks that render pixel-identical but are NOT
   VB-editable. Pixel diffs and parse round-trips both pass that failure.
   Section 6 adds the checks that catch it.
5. **The Theme Builder dog template (old Phase 5) is deferred past staging.**
   Staff edit dogs through ACF fields, not layout. single-pets.php is a
   working renderer (the stray div bug is verified FIXED as of 2026-08-18).
   Highest-risk, lowest-payoff item; it leaves the launch path.

## 3. Facts verified against the repo and Divi source (2026-08-18)

- single-pets.php stray `</div>` bug: FIXED (both header paths are clean).
  The stale memory note has been corrected.
- Deterministic screenshot baselines need no new code: every animated
  surface (reveal, Ken Burns, hero auto-advance, paw trail, hover slideshow)
  already honors `prefers-reduced-motion`, which Playwright emulates.
  Under reduced motion the hero stays on slide 0 and Ken Burns jumps to its
  end state, so captures are stable.
- Renderer inventory: 40 page-*.php + front-page.php + single-pets.php = 42.
- Divi 5.2.1 converter internals as documented in the session memory
  (convert_single_post pipeline, 3 builder metas, Loop Builder LIKE queries,
  Design Variables storage inside the et_divi options blob).

## 4. Decisions Andrew must make before Phase 1 (G0)

1. **The kill-or-continue fallback, decided NOW, not at failure time.**
   If Phase 1 ground-truthing fails (a hand-made VB page does not round-trip
   byte-stable), the conversion is killed and the fallback is: keep the
   sidecar theme as-is and ship it with the layered deploy from section 8,
   which fixes the original deploy failure on its own. Recommended: adopt
   this fallback in writing.
2. **Design Variables: bind or skip.** Option A: generate D4 markup with
   global color IDs (gcid-*) so converted pages bind Divi global colors and
   staff recoloring propagates. Option B (recommended): do not seed Design
   Variables at all; tokens.css remains the single token authority and brand
   colors are not staff-editable. Decorative seeded variables (seeded but
   unbound) are the worst outcome and are off the table.
3. **Confirm the scope cuts:** TB dog template deferred past staging; the 7
   donation/thank-you LGL pages (donation, annual-donation, monthly-donation,
   dog-tag-donor-donation, doggie-suite-sponsorship-donation, thank-you,
   thanks) stay as sidecar PHP permanently (they are iframe wrappers nobody
   restyles); dog card grids keep the single existing shortcode renderer
   everywhere (no Loop Builder cards for now, so the site's most important
   component cannot fork into two drifting implementations).

## 5. Phases

**Phase 0, safety (partially done).**
- Git freeze branch + tag: DONE, pushed.
- Divi pinned at exactly 5.2.1: disable auto-updates on local AND
  new.pomdr.org. Written rule: Divi updates are applied by Andrew only,
  local first, with the Phase 2 validator re-run before the update touches
  staging. A mid-window point release rewrites converted JSON (D5-to-D5
  migrations) and invalidates the generator.
- `wp db export` snapshot before ANY database write. BLOCKED until the
  Local site is started (manual Start click in the Local app).
- All-in-One export as secondary restore point.

**Phase 1, foundation.**
- Ground-truth the D5 format: hand-build a small page in the VB, dump its
  post_content and metas, confirm our understanding (block comments, attr
  paths, 3 metas). This is the kill-or-continue moment (G1).
- Neutralize Divi defaults BEFORE the generator emits one shortcode: zero
  default section/row padding via global preset, strip the button preset's
  hover icon and padding shift, set Customizer body/heading fonts to
  inherit, disable Divi's own Google Fonts loading. Then assert on a test
  page that computed body font family, size, and line-height match the
  sidecar rendering exactly. Skipping this turns the batch into 30 pages of
  CSS whack-a-mole.
- Code-register the CPTs (pets, events, team) in the child theme and turn on
  ACF JSON sync NOW, not at deploy time. The last deploy died precisely
  because ACF CPT definitions lived only in the database. Every week of
  development from here exercises the real deployable configuration.

**Phase 2, generator + validator.**
- Dev script (scripts/, not theme code) maps each sidecar template's content
  model to D4 shortcodes with our CSS classes, then converts via
  `convert_single_post`. Every wp-cli invocation runs with
  `--user=<admin with unfiltered_html>` (kses otherwise strips the entire
  block markup while the status still reads success). The script reads back
  what it wrote and diffs; it never trusts the return status.
- Wrap the generator in a pre-conversion dump: post_content plus ALL
  `_et_pb_*` postmeta per page, saved to scripts/backups/. Revisions alone
  cannot revert the builder meta flags.
- The per-page validator is section 6. No page passes without all of it.

**Phase 3, pilot: three content classes.**
- One static page (culture), one CPT-loop page (videos or news), one LGL
  form page (volunteer-application).
- Each must pass the full section 6 validator, plus a cross-site Divi
  Portability import test (export from local, import into the disposable
  rehearsal site) to prove converted JSON survives crossing environments.
- Staff usability gate: one non-technical staff member (Carie or Monica)
  performs a scripted VB task on the pilot page in a screen share: swap a
  photo, change a headline, reorder two sections, publish, undo. Pass:
  completed unaided in under 15 minutes with no design damage. Fail:
  redesign the editing surface or lock more down before any batch work.
- STOP: Andrew approval (G2).

**Phase 3.5, content-model changes + migration rehearsal (moved BEFORE batch).**
- Everything that changes what a page contains happens before that page is
  frozen into a validated native layout (otherwise each one is converted
  twice): forms-page repeater, stats options field, promo banner wiring,
  adopted-wall merge, News/Jobs/Testimonials/Media CPT conversions, and the
  hero rotator's CONTENT (slides, images, captions) into an options page so
  the most-requested edit stops requiring a developer.
- Named Promotion system (options page: promotion name + on/off; per-dog
  checkbox; title banner on the card; seed from aged_to_perfection).
- Run the fresh-local All-in-One rehearsal now, so the migration pipeline is
  proven before 30 pages depend on it.

**Phase 4, batch conversion.**
- Remaining static pages one at a time through the same generator +
  validator loop. Retire each page-{slug}.php ONLY after its native page
  passes everything. Complex pages (donate, helping-paw, surrender) last.
- Realistic pace for a solo developer: 2 to 3 validated pages per session.
- STOP before the final sidecar retirements (G3).

**Phase 5, deployment** (section 8). STOP before staging deploy (G4),
conditional on the upgrade rehearsal passing.

**Phase 6, post-staging (deferred scope).**
- Theme Builder dog template: build the layout, assign via display condition
  to ONE test pet, validate there, then flip to all pets in one reversible
  step. Rollback is unassigning the template, instant, never git revert.
  Screenshot a stratified sample (one dog per status value, multi-status
  combos, no-photo dog, gallery+video dog, longest description), not all 185.
- Loop Builder experiments, remaining pure-CPT admin fields (courtesy
  contacts, highlights) if not already landed.

## 6. The per-page validator (the gate that makes every other gate real)

A converted page passes only when ALL of the following hold:

1. **No silent fallback:** converted post_content contains zero
   `divi/shortcode-module` occurrences, and the converter's
   `saveVerification === true`. Log `_et_pb_divi_5_conversion_status` per
   page into the batch report.
2. **Parse round-trip:** parse_blocks() and Divi BlockParser round-trip
   byte-stable.
3. **VB smoke + save idempotency:** Playwright logs in as admin, opens
   `?et_fb=1`, asserts the builder mounts with the expected module count and
   zero console errors, performs a no-op save, asserts post_content is
   byte-identical after. Then one trivial text edit, save, and re-run: parser
   round-trip, a class census (every design-system CSS class present before
   the save is present after), and the pixel diff. Staff saves are the
   permanent operating condition, not the conversion; this catches
   normalization bombs before they detonate on a staff member's first edit.
4. **Pixel diff, numerically defined:** under 0.5% pixel delta at 390, 768,
   1440, plus Divi's own column-stack boundaries at plus and minus 1px.
   Masked regions enumerated per page. Any layout shift is an automatic
   fail. Captured under emulated prefers-reduced-motion (deterministic for
   free, verified). "Pixel-identical" is retired as a phrase; Divi's wrapper
   DOM will never be byte-identical to sidecar markup and does not need to be.
5. **Computed-style diff (the typography gate):** a script walks matched
   selectors (h1-h4, p, every .btn variant, section wrappers) on sidecar vs
   native and diffs computed font-size, line-height, letter-spacing,
   font-weight, margins, paddings, colors. Tolerance: zero for typography,
   1px for box metrics. Image diffing blurs away sub-pixel typographic
   betrayal; this catches it deterministically.
6. **Accessibility:** axe-core scan, zero new violations vs the sidecar
   render, plus a keyboard-tab traversal asserting a visible focus outline at
   every stop. Pixel-identical DOM can still scramble heading hierarchy,
   ARIA, and focus order; the diff cannot see that.
7. **Senior mode:** the same pixel sweep and tab traversal with
   `html[data-a11y="senior"]` set (zoom 1.15) at 1440 and 390. Zoom does not
   retrigger media queries and skews layout-measuring JS; run this on the
   pilot FIRST so the interaction is discovered on one page, not thirty.
8. **Performance:** Lighthouse sidecar vs native, no score regression beyond
   3 points on any Core Web Vital (this is already mandated by CLAUDE.md and
   was missing from the v1 loop). Native pages enqueue Divi front-end CSS/JS
   the sidecars never loaded.
9. **Pinned fixtures for CPT-loop pages:** diffs of adopt, news, events,
   videos, foster-needs run against a frozen fixture set of pets/posts so
   both renders see identical content; live data makes those diffs
   nondeterministic by design.

## 7. Rollback (repaired; v1's was broken as written)

- A bare WP revision restore reverts post_content but NOT `_et_pb_use_divi_5`,
  which leaves D4 shortcodes parsed as D5: a broken page. Never use it alone.
- Per-page rollback = Divi's own d5-readiness Rollback (restores
  `_et_pb_divi_4_content` AND flips the meta), or our script restoring the
  Phase 2 pre-conversion dump (post_content + all `_et_pb_*` metas)
  atomically.
- Sidecar retirement remains the LAST step per page, so every page has a
  working renderer at every moment.
- Full `wp db export` before Phase 3.5 (schema-level changes) and before any
  Theme Builder write in Phase 6; both were unsnapshotted in v1.
- Theme code: the freeze branch/tag restores everything instantly.

## 8. Deployment: layered, repeatable, content-safe

Replaces the All-in-One full import. Content rows on the receiving site
(pets, events, team, form entries, users) are never touched.

1. Theme code: git pull of the child theme on new.pomdr.org.
2. Page layouts: Divi Portability export/import, or WXR of just the ~30
   converted page records (+ any Theme Builder layouts later).
3. CPTs and fields: in-theme registration + ACF JSON sync (landed in
   Phase 1, so this is a no-op by deploy day).
4. Uploads: rsync of wp-content/uploads.
5. Belt and braces: All-in-One export OF THE TARGET taken immediately before
   every deploy, as the restore point.
6. Post-deploy, in order: purge et-cache/Divi static CSS and regenerate,
   confirm identical Divi version (5.2.1 pinned), permalink flush, then the
   smoke suite: a live $1 donation through the LGL form FIRST, all three
   form-prefill paths, adopt filter/sort, one dog page per status value,
   Mailchimp template render check (mcusercontent.com images resolve),
   redirect spot-checks, full-site 404 crawl, and the stratified screenshot
   sample re-run against staging. Nobody announces the site is back until
   the donation test clears.

**Cross-environment portability (the risk all four experts missed, caught by
the red team):** converted D5 JSON bakes in local absolute URLs and integer
attachment IDs. `wp search-replace` does not reliably rewrite URLs inside
block-comment JSON (JSON-escaped forms) and cannot fix attachment IDs at all,
so images silently 404 or bind to wrong media rows. Required: media imported
to the target FIRST with an ID map (or modules referencing URLs only), a
JSON-aware URL rewrite step in the deploy script, and the post-deploy crawl
asserting zero local-host references. The Phase 3 cross-site Portability test
exists to prove this before the batch runs.

**Upgrade rehearsal (before G4):** clone the converted site, update Divi
5.2.1 to the then-current release, re-run the VB smoke and the stratified
screenshot sample. new.pomdr.org WILL update Divi eventually; this must not
be discovered in production.

## 9. Governance, training, and staff reality

- Roles at deploy: staff = Editor, Andrew = sole Administrator. Divi Role
  Editor for Editors disables: Theme Builder, Theme Options, custom CSS
  fields, Portability import/export, and structural changes (section/row
  add/delete). On-system choices become the easy path via global presets.
- **Editability map** ships with the conversion: one page per template
  stating which regions are VB-editable, which are options/CPT-driven, and
  which require Andrew (hero rotator internals, chrome, search UI). Staff
  must be told explicitly what they can and cannot edit, or "the Visual
  Builder works now" becomes a trust problem the first time someone hits
  the hero.
- One 60-minute hands-on session per staff member, plus a permanent sandbox
  page they are allowed to break.
- The Phase 2 diff tooling is kept runnable on demand as a drift audit
  against the post-launch baseline (scheduled monitoring was judged
  process theater; the Role Editor lockdown is the actual control).

## 10. Risks accepted consciously, in writing

- D4-to-D5 converter fidelity on edge attributes: tolerable because sidecar
  retirement is last per page and rollback is repaired (section 7).
- Screenshot flake on animated elements: mitigated by reduced-motion
  captures and enumerated masks; residual flake accepted.
- The hybrid boundary stays where it is (hero rotator internals, adopt
  filter UI, chrome nav/footer, dog gallery strip, paw trail). The council's
  creative seat reviewed the boundary and endorsed it: do not move it inward.
- `convert_single_post` is a migration-era internal Divi class that Elegant
  Themes can rename in any point release: accepted ONLY under the 5.2.1 pin
  with auto-updates disabled (Phase 0).
- LIKE-on-serialized status queries at 185 rows: trivial perf; if Loop
  Builder is ever used, quote-wrap the meta value ("Adoptable" including
  quotes) to prevent substring cross-matches.

## 11. Honest timeline (solo developer, session-based)

- Foundation + generator + validator: 3 to 4 weeks (the validator is a
  project in itself).
- Expanded pilot + staff test + approval latency: 1 to 2 weeks.
- Batch (~27 remaining pages at 2 to 3 per session): 4 to 6 weeks.
- Phase 3.5 content-model slice: 2 to 3 weeks (overlaps batch partially).
- Rehearsals + staging deploy: 1 to 2 weeks.
- **Roughly 3 to 4.5 months to staging** with the scope cuts; 5 to 6 months
  if the dog Theme Builder template and full Phase 6 stay on the launch path
  (they should not).

## 12. Production cutover (out of scope here, must exist before launch)

This plan ends at new.pomdr.org. The revenue site is pomdr.org. Before any
public cutover a separate runbook must cover: DNS/hosting switch, the
redirect map (redirects.csv is still a stub), Mailchimp templates, Metricool
social links, and printed materials carrying URLs. Flagged, not planned here.

---

_Council: 4 expert seats + red-team chair, run 2026-08-18. Full transcripts
in the session workflow journal. Prior draft: session scratchpad
master-plan.md._
