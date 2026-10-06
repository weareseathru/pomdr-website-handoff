# Deploy Forensics: new.pomdr.org, 2026-09-01

The first deployment of the redesign to the test bed (new.pomdr.org) failed
visibly. This document is the complete forensic record, gathered while the
test bed was still live, plus the verified conclusions of a five-seat expert
council (WordPress deployment, Divi 5 internals, PHP architecture, front-end
CSS, design/UX) whose every load-bearing claim was independently re-verified
against the live site, the repo, and the actual export archive. Raw evidence
artifacts live in `docs/forensics/2026-09-01-newpomdr/`.

## Root cause, one paragraph

The export archive was good and the database imported perfectly. The failure
was a PARTIAL FILE MERGE on the target: every file that was NEW to the child
theme landed correctly (byte-identical to the repo), but the two files that
ALREADY EXISTED in the old production child theme, `functions.php` and
`style.css`, kept their old April 15 versions. The old `functions.php` is the
loader for everything in the redesign (the `require_once` block for
inc/enqueue.php, inc/chrome.php, inc/native-gate.php, inc/videos.php,
inc/post-types.php sits at its bottom), so with it stale: zero design CSS/JS
enqueued, no chrome injection, no native gate, no redesign shortcodes, and no
videos CPT. New sidecar templates executed (they were new files) but two of
them call functions that only exist in the new functions.php, producing hard
500s on /adopt/ and /events/. A second, independent defect: Divi's generated
CSS cache (`wp-content/et-cache`) was carried over from local with
protocol-relative `//newpomdr-local.local` font URLs baked in, breaking icon
fonts. That leak is in a cache FILE, not the database (the DB is clean), so
no search-replace can fix it; only clearing Divi's static CSS can.

## The evidence (all independently verified)

| Fact | Proof |
|---|---|
| DB imported perfectly | REST: page 54 and 987 modified_gmt identical to local to the second (2026-08-25T18:27:34 / 18:14:58); DB content clean of local host (0 hits across all 100 pages) |
| Archive was good | The actual .wpress (911MB, `newpomdr-local-local-20260825-121945-wyjhpxo0bglf.wpress`) parsed: contains new style.css 17205B, functions.php 82776B, all inc/*.php |
| New files landed | ~30 probed theme files all Last-Modified Aug 25 18:15:50 GMT, spot diffs byte-identical to repo (pomdr.css, tokens.css, native-boost.css...) |
| Old files kept | target style.css: 6693B, Last-Modified Apr 15 2026, content-diff proves it is the old production file. functions.php proven old behaviorally: only `style.css?ver=1.0.2` enqueued, Divi zoom-lock viewport meta still present, no zero-dependency scripts print |
| Not opcache, not CDN | style.css is static (no PHP) and was fetched cache-busted at origin (cf-cache-status: MISS): old ON DISK |
| Not a second theme folder | The same active /themes/divi-child/ path serves both the old style.css and the new pomdr.css |
| 500 mechanism | page-adopt.php:237/277 call `pom_render_dog_card()`, page-events.php call `pomdr_collect_events()`/`pomdr_render_events()`, all defined only in new functions.php. /adopt/ emits 135KB of HTML then dies at `<div class="dogs-grid">` |
| Icon-font leak carrier | `wp-content/et-cache/54/et-divi-dynamic-tb-145-tb-307-54-critical.css` (copy saved in forensics folder), inlined server-side as `<style id="divi-dynamic-critical-inline-css">`; the three LINKED et-cache files are clean; the DB is clean |
| Garbage text band | The old Theme Builder header (layouts tb-145/tb-307) contains a Code module holding the legacy LGL adoption prefill script, double-mangled (backslash-stripped `u003c` escapes, unrecoverable by decoding). Present on LOCAL too, hidden there by pomdr-chrome.css lines 9-10 |
| Cloudflare fronts the site | cf-cache-status/cf-ray headers, CSS max-age 14400 (4h staleness window) |
| Target environment | WordPress 7.0.2 (local: 7.1), ai1wm 7.110 (readme touched Sep 1 21:28 GMT, someone active on the server that day), Unlimited extension NOT present Sep 1 (404), ACF Pro readme Aug 20 (archive era), REST namespaces: redirection, ai1wm, divi, wp/v2 |
| mtimes are source mtimes | uploads carry June 2026 Last-Modified; theme files Aug 25 (the local theme-copy date). So Last-Modified proves which archive a file came FROM, not when it was written; content diffs are the real proof |

Full 40-URL crawl results: `docs/forensics/2026-09-01-newpomdr/target-crawl-40urls.txt`
(38 pages rendered unstyled with dead dog/video/event islands, 2 hard 500s,
zero dog-cards site-wide, duplicate h1s from the exposed TB chrome).

## What was NEVER broken (for stakeholder comms)

Donations were never down: /donation/ and /donate/ returned 200 with the LGL
iframe rendering even in the broken state. All content, dog records, photos,
and settings transferred correctly. Nothing was lost at any point.

## Why the failure was invisible for a week

Old and new style.css both declare `Version: 1.0.2`, so the admin Themes
screen, the theme-upload comparison table, and the `?ver=1.0.2` cache-buster
all showed nothing wrong. This is why the version bump to 2.0.0 is a
precondition of the next deploy, not cosmetics.

## Council verdicts (5 seats, all "sound-with-amendments")

Unanimous remedy ranking, with seat corrections folded in:

1. Surgical theme replacement beats re-import. The DB is already right; only
   the theme folder is wrong; re-import needs the paid extension, re-uploads
   911MB, and re-poisons et-cache anyway. If overwrite-of-existing failed
   once (file ownership is the leading suspect), a re-import can fail the
   same way; delete-then-install only needs directory write permission.
2. The Divi static CSS clear (or deleting wp-content/et-cache, it rebuilds)
   is MANDATORY on every remedy path including a perfect re-import.
3. The redeploy zip has two traps: its root folder must be named exactly
   `divi-child` (repo folder is divi-child-integration; wrong name installs
   a silent second theme and the fix no-ops) and style.css must be bumped
   to 2.0.0 first.
4. Cloudflare must be purged (or busted via the new ?ver) after the fix, or
   verification can lie for up to 4 hours.
5. "Could not remove the old theme" during the replace flow is diagnostic
   gold: it confirms foreign file ownership, and the escalation is host
   support fixing ownership on wp-content/themes/divi-child.
6. Remove, do not repair, the old TB header/footer templates (after a TB
   portability JSON export): the band's script is unrecoverable and
   duplicates prefill logic the new layer owns. pomdr-chrome.css already
   hides the classic Divi chrome that replaces deleted TB layouts, so the
   removal is low risk and turns a garbage-band failure mode into a benign
   default-nav failure mode.
7. Code hardening (each implicated in this incident): guards at the TOP of
   page-adopt.php and page-events.php (before get_header, half the page is
   already sent by line 237); drop the dangling `divi-style` dependency in
   inc/enqueue.php:34 (Divi deregisters that handle at priority 99999998 and
   force-rewrites all child deps at 99999999, so the chain survives by
   accident; if Divi ever changes, ALL design CSS dies silently); align dep
   arrays with call order (Divi currently discards deps, order = call order);
   POMDR_BUILD constant + wp_head marker; unify file_exists guards around
   filemtime calls.

Seat disagreement resolved by the harvest: the mtime fingerprint proves the
target's new files came from the Aug 25 archive but not which tool wrote
them (mtimes are source-preserved, see uploads). The Unlimited extension was
absent on Sep 1 but could have been installed and removed. The mechanism
label matters less than the on-disk result, which is proven, and the remedy
(delete-then-replace, never merge) is robust under every candidate mechanism.

Full seat reports: `docs/forensics/2026-09-01-newpomdr/council-seat-reports.json`.

## THE PROTOCOL: future exports and imports that show up perfectly

### Before every export (local, 10 minutes)

1. Theme on the Local site is a REAL COPY of the repo theme (never a symlink;
   ai1wm skips symlinks). Verify: `rsync -rcn --delete repo-theme/ site-theme/`
   prints nothing.
2. Bump the child theme Version in style.css if ANY theme file changed since
   the last deploy. Old and new must never share a version string.
3. Clear Divi's static CSS on LOCAL (Divi > Theme Options > Builder >
   Advanced > Static CSS > Clear, or delete wp-content/et-cache) so the
   archive never ships cache files with the local hostname baked in.
4. Confirm the build canaries in local view-source: the pomdr stylesheet
   links present, viewport meta WITHOUT maximum-scale.
5. Export via wp-admin > All-in-One WP Migration > Export > File.
   Note the archive size (this one was 911MB; free import cap is 512MB).

### Import (target)

Preferred when importing the whole site: ai1wm Import with the Unlimited
extension, run to the explicit "imported successfully" screen, then log in
again (users are replaced).

When only the THEME needs updating (this incident's fix): wp-admin >
Appearance > Themes > Add New > Upload Theme > zip whose root folder is
exactly `divi-child` > Install Now > "Replace active with uploaded".
NEVER merge theme folders via FTP or a file manager. If WordPress says
"Could not remove the old theme", stop and have the host fix file ownership
on the theme directory; that ownership problem is what broke this deploy.

### After every import, in order, non-negotiable

1. Settings > Permalinks > Save Changes (registers pets/videos/events
   rewrites; without it CPT URLs 404).
2. Divi > Theme Options > Builder > Advanced > Static CSS > Clear + Save
   (or delete wp-content/et-cache). Fixes the baked-hostname icon fonts and
   regenerates critical CSS for the new host.
3. Purge Cloudflare (Caching > Configuration > Purge Everything) or wait out
   max-age 14400; purge host cache / restart PHP if the panel offers it.
4. Run the verification below before anyone is shown the site.

### Ten-second canaries (view-source on the home page)

- `pomdr-tokens-css` or `pomdr.css` present  -> new functions.php is running
- `maximum-scale` ABSENT                     -> our viewport fix is live
- `newpomdr-local` ABSENT (0 hits)           -> no environment leak
- `u003cscript` ABSENT                       -> no exposed TB garbage band
- exactly one `<h1` per page                 -> no double chrome

### Full verification (Andrew, ~30 minutes)

- Crawl the 40-URL list (docs/forensics/.../target-crawl-40urls.txt has the
  list): every URL 200, h1 present, canaries green.
- Dog cards populated on home (6), /adopt/ (~140), /foster-needs/, /adopted/.
- /events/ two sections, /videos/ 18-item grid, LGL iframes on all 5 forms.
- Icon glyphs render (no tofu boxes); hero arrows; adopt search filters.
- 12 priority pages at 390 / 768 / 1440 and desktop 200 percent zoom with no
  horizontal scroll; pinch-zoom works on a real phone (senior-critical).
- The ?petname= prefill reaches field_21 in the adoption questionnaire iframe.
- axe scan on home, adopt, donation, one dog page: zero new violations vs
  local baseline.

## Hardening backlog (decided items live in the repo when applied)

Pre-redeploy (minutes each, all implicated in this incident): version bump,
zip build rule, top-of-file guards in page-adopt.php and page-events.php,
POMDR_BUILD + wp_head marker, drop the divi-style dep, README-DEPLOY rewrite
with this protocol. Post-launch: TB header/footer removal (with JSON backup),
dep-array alignment, filemtime guard helper, deploy-verify crawler script.

Owner decisions pending: purchase/confirm ai1wm Unlimited for full-site
imports; TB template removal timing; who owns the Cloudflare purge.
