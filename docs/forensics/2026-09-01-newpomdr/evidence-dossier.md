# POMDR Deploy Failure Dossier: new.pomdr.org (2026-09-01)

## Context
Local build (newpomdr-local.local) is a verified-good Divi 5.2.1 child-theme redesign:
29 pages cut over to native Divi 5 blocks at their real URLs (gated by `_pomdr_native`
meta -> page-native.php), remaining pages on hardcoded "sidecar" templates
(page-{slug}.php / front-page.php) that render design markup directly and ignore
post_content. All design CSS/JS is enqueued by `inc/enqueue.php`; site chrome
(header/footer) is injected by `inc/chrome.php` and the old Divi Theme Builder
header/footer is hidden by `pomdr-chrome.css`. Dynamic content (dog grids, events,
videos) renders via shortcodes ([pet_home], [events], [pomdr_videos], etc.) and
helper functions (pom_render_dog_card, pomdr_render_events) all defined in
functions.php + inc/*.php. Repo: /Users/kglocker/Documents/Antigravity/pomdr-website-handoff
(theme at divi-child-integration/). The coworker deployed to the test bed
https://new.pomdr.org via All-in-One WP Migration (free export made by owner;
import side instructions said Unlimited extension required, payload ~700MB).

## Verified evidence (every claim tested 2026-09-01, curl/Playwright)

1. DATABASE IMPORTED PERFECTLY. REST probes: page 54 (home) modified
   2026-08-25T11:27:34 and page 987 (process) modified 2026-08-25T11:14:58,
   IDENTICAL to local to the second. page_on_front=54, siteurl/home rewritten
   to https://new.pomdr.org. Uploads/media load (200s).
2. PARENT THEME OK. Divi 5.2.1 on both sides (style.css headers identical).
   WP core: target 7.0.2, local 7.1 (not causal).
3. CHILD THEME ON TARGET IS A PARTIAL MERGE (the root cause):
   - NEW files are present, current, byte-identical to repo:
     assets/css/pomdr.css (57196B, Last-Modified Aug 25 18:15 GMT, diff=identical),
     native-boost.css (149471B), native-pages.css, native-boost-chrome.css,
     pomdr-chrome.css, native-pages.js, page-native.php, inc/native-gate.php,
     and the sidecar templates (they render).
   - PRE-EXISTING files kept their OLD content: style.css is the April 15 2026
     production file (6693B vs ours 17205B, content-diff confirmed). functions.php
     behaviorally proven old: only the legacy dt_enqueue_styles runs (page enqueues
     ONLY divi-child/style.css?ver=1.0.2), none of our zero-dependency scripts print
     (a11y.js, pomdr-nav.js, native-pages.js absent), Divi's viewport meta with
     maximum-scale=1.0 still present (our removal absent), no chrome injection,
     no fonts preconnect, no Google Fonts enqueue.
4. CONSEQUENCES OBSERVED SITE-WIDE (40-URL crawl):
   - 38 pages render DESIGN MARKUP (new sidecar templates execute) but UNSTYLED
     (no design CSS at all). Old TB header/footer visible; duplicate h1
     "Adopt or Donate" from TB footer.
   - HARD 500s: /adopt/ and /events/ (new templates call functions that exist only
     in NEW functions.php -> PHP fatal: undefined function; /adopt/ emits partial
     HTML then dies).
   - ALL dynamic islands EMPTY: dog-card count = 0 on every page incl. home,
     foster-needs, adopted, courtesy-listings (shortcodes unregistered).
     videos CPT unregistered (inc/videos.php not loaded) -> videos grid empty.
   - Native gate absent: the 29 native D5 records never route to page-native.php.
     Where a sidecar with the same slug exists, WP's template hierarchy serves the
     sidecar (masking); where none exists, page.php + TB template serves the D5
     content (renders, unstyled).
   - TB header band renders raw JSON-escaped script text ("u003cscript...") at the
     top of every page: a LATENT flaw in the TB header layout record (a Code module
     with escaped content). Verified present on LOCAL too but hidden by
     pomdr-chrome.css. Exposed on target because chrome CSS is absent.
   - Icon-font URL leak: Divi cached/dynamic CSS contains protocol-relative
     //newpomdr-local.local/wp-content/themes/Divi/core/admin/fonts/... @font-face
     URLs (FontAwesome/module icons). ai1wm's URL rewrite missed the
     protocol-relative variant. Effect: broken icon fonts for real visitors.
5. Deploy-method inference: a full ai1wm import extracts and REPLACES all files;
   pre-existing files keeping April content is impossible under a completed full
   import. Most consistent: DB restored (fully) + theme files MERGED with
   "skip existing" semantics (file-manager unzip / FTP merge), or a partial
   extraction. The exact coworker action is unknown; the on-disk result is proven.

## Candidate remedies (council: rank, amend, veto)
A. Full re-import of the .wpress with ai1wm Unlimited on target (one-button
   known-good; wipes target DB+wp-content; test bed is disposable).
B. Surgical: DELETE wp-content/themes/divi-child on target entirely, then upload
   a fresh full copy of the theme (11MB zip). DB already correct. Then:
   Settings->Permalinks->Save, clear Divi static CSS, purge host caches/opcache.
C. Post-fix data hygiene: wp search-replace for '//newpomdr-local.local' (and
   scheme variants) + regenerate Divi dynamic CSS to fix icon fonts; re-verify.
D. Hardening our build so this class of failure is visible/impossible next time:
   - POMDR_BUILD constant + wp_head HTML comment + admin-footer stamp
     (staleness detectable in seconds).
   - Bump child theme Version 1.0.2 -> 2.0.0 (visible in admin + ?ver=).
   - function_exists guards (or loader guard) in sidecar templates so partial
     deploys degrade gracefully instead of 500.
   - Ship the theme additionally as a standalone zip in the handoff folder and
     prefer Appearance->Themes->Add New->Upload->"Replace active with uploaded"
     (WP 5.5+ true-replace flow a non-developer can execute).
   - A deploy-verify script (wp eval-file or temporary admin notice) checking:
     build constant, inc/ files loaded, key shortcodes registered, gate-armed
     count = 29, enqueue handles present, and crawling the 40 URLs for
     200 + h1 + marker.
   - Decide fate of the latent TB header/footer templates (garbage band is one
     CSS failure away from public display): fix the record, or remove TB
     header/footer once chrome owns them (risk analysis needed: sidecars vs
     native both suppress TB differently).
   - README-DEPLOY: add "delete theme folder first, never merge" + the checklist.

## Questions for the council
1. Is the partial-merge diagnosis airtight? What alternative explanations survive
   the evidence, and what one probe would falsify them?
2. Remedy ranking A vs B (or both, in what order) for a non-developer coworker;
   exact step-by-step they can follow without CLI access.
3. Which hardening items in D are worth doing BEFORE the redeploy vs after launch?
   Anything missing or wrong-headed?
4. Any Divi-5-specific traps in redeploying (static CSS cache, Design Variables,
   global colors, TB template records, D5 asset regeneration) we have not listed?
5. Design/UX seat: given the test bed will be shown to stakeholders, what is the
   minimum set of visual verifications post-fix (pages + breakpoints) to declare
   the test bed presentable?
