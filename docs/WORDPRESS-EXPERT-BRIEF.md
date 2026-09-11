# POMDR Website Redesign: Deployment Brief

Prepared 2026-09-10 for an outside WordPress expert helping us deploy.
Contact: Andrew Z. (apzielinski62@gmail.com)

## The project in short

Peace of Mind Dog Rescue (POMDR, pomdr.org) is a nonprofit in Pacific Grove,
CA that rescues senior dogs and helps senior people keep their dogs. We have
rebuilt their dated WordPress site as a modern, accessible redesign. The
build is finished and verified on a local development machine. What we need
now is a clean, reliable deployment to a fresh WordPress test bed, and a
second set of expert eyes on our process. Our first deployment attempt
failed in an instructive way, documented below.

## The stack

| Piece | Detail |
|---|---|
| Platform | WordPress (current core), single site |
| Theme | Divi 5.2.1 parent (pinned, auto-updates off) + custom child theme `divi-child` |
| Custom fields | ACF Pro. Field groups for dogs, events, team |
| Content types | Custom post types: `pets` (~185 dogs), `events`, `videos`, `team` |
| Forms and donations | Little Green Light (LGL) embedded iframes. No form plugins |
| Other plugins | Redirection, All-in-One WP Migration |
| Dev environment | Local (by Flywheel) on macOS. Site: newpomdr-local.local |
| Transfer tool | All-in-One WP Migration (.wpress archive, ~911MB) |
| CDN | The retired test bed sat behind Cloudflare (CSS cached 4 hours) |

## How the theme is built (what you need to know to not be surprised)

The child theme renders pages two ways:

1. **Native Divi 5 pages (29 of them).** Real Divi 5 block content on the
   live page records. A post meta `_pomdr_native` routes these through
   `page-native.php` via a `template_include` filter in `inc/native-gate.php`.
2. **Sidecar templates (the rest).** Hardcoded `page-{slug}.php` templates
   (plus `front-page.php`, `single-pets.php`) that render the design
   directly and ignore post content.

Dynamic content (dog grids, events, video grids) comes from shortcodes and
helper functions defined in `functions.php` and `inc/*.php`. All design CSS
and JS is enqueued by `inc/enqueue.php`.

**The critical detail:** `functions.php` ends with `require_once` calls that
load `inc/enqueue.php`, `inc/chrome.php`, `inc/native-gate.php`,
`inc/videos.php`, and `inc/post-types.php`. It is the loader for the entire
redesign. If an old copy of functions.php executes, the whole design layer
silently disappears while the template files still run. That exact scenario
is what broke our first deploy.

## State of the build (verified locally)

- All 40 public URLs crawl clean: correct headings, no PHP errors, dynamic
  grids populated, forms rendering.
- Pixel-parity harness (Playwright) verifies the pages against the approved
  design at 4 breakpoints.
- Staff editing verified: dogs/events/videos are plain wp-admin edits with
  ACF fields; pages open in the Divi Visual Builder.
- Accessibility is a first-class requirement (older audience): WCAG 2.2 AA,
  16px minimum body text, pinch-zoom restored (we remove Divi's
  maximum-scale viewport lock).
- The current production site (www.pomdr.org) is untouched by all of this.

## Deployment strategy and what went wrong

**The strategy:** export the entire site from Local via All-in-One WP
Migration (wp-admin > Export > File), then import the `.wpress` on the test
bed with the same plugin. One archive carries the database, theme, plugins,
and media. The archive is ~911MB, which is over the free importer's 512MB
cap, so the import side needs the paid Unlimited extension.

**What happened on the first attempt (test bed new.pomdr.org, since
retired):** the database imported perfectly (record timestamps matched local
to the second, URLs rewritten correctly) and every file that was NEW to the
child theme landed byte-identical. But the two files that ALREADY EXISTED in
the old child theme on that server, `functions.php` and `style.css`, kept
their old April versions. Our leading suspicion is file ownership or
permissions preventing overwrite of pre-existing files while new file
creation succeeded.

The result of that half-old, half-new theme:

- Old functions.php ran, so zero design CSS/JS was enqueued, no custom post
  type registration, no shortcodes, no native-page routing.
- Pages rendered the new markup (new template files executed) completely
  unstyled, with the old Divi Theme Builder header exposed.
- Two hard 500s (/adopt/ and /events/): new templates calling helper
  functions that only exist in the new functions.php.
- Every dog/event/video grid empty (shortcodes unregistered).

**A second, independent defect:** Divi writes generated CSS to
`wp-content/et-cache` with protocol-relative URLs that bake in the origin
hostname. Our archive carried cache files pointing at
`//newpomdr-local.local`, which broke icon fonts on the test bed. This lives
in cache FILES that get inlined into the HTML, not in the database (we
verified the DB is clean), so a search-replace cannot fix it. Only clearing
Divi's Static CSS (or deleting et-cache, it rebuilds) fixes it.

A hidden factor that let this go unnoticed: the old and new `style.css` both
declared `Version: 1.0.2`, so nothing in wp-admin looked wrong.

Full forensic record with evidence, HTML snapshots, and a five-seat expert
review: `docs/DEPLOY-FORENSICS-2026-09-01.md` and
`docs/forensics/2026-09-01-newpomdr/` in the repo.

## The corrected protocol (what we plan to do)

**Before export (on Local):**
1. Clear Divi Static CSS (Divi > Theme Options) so the archive ships no
   cache with the local hostname.
2. Bump the child theme version so old and new are distinguishable.
3. Export via All-in-One WP Migration > Export > File.

**Import (new test bed):**
1. Start from a FRESH WordPress install (no pre-existing POMDR theme means
   the file-merge failure is impossible).
2. Install All-in-One WP Migration + Unlimited extension.
3. Import the .wpress, wait for the explicit success screen, log back in
   with the archive's credentials (users are replaced).

**After import, always:**
1. Settings > Permalinks > Save Changes (registers CPT rewrites).
2. Divi > Theme Options > Static CSS > Clear + Save (regenerates for the
   new host).
3. Purge host/CDN caches.

**Rules we now treat as law:**
- Never merge theme folders by hand (FTP or file-manager unzip over an
  existing folder). Theme-only updates go through Appearance > Themes >
  Upload > "Replace active with uploaded".
- "Could not remove the old theme" during that flow means the host must fix
  file ownership on the theme directory. Stop and escalate, do not retry.

**Sixty-second verification (view source on the home page):**
- `pomdr.css` FOUND (the design layer is running)
- `newpomdr-local` ZERO hits (no environment leak)
- `maximum-scale` ABSENT (zoom works for older visitors)
- One `<h1>` per page, six dog cards on home, /adopt/ and /events/ load,
  icons render as icons.

## Where we would value your help

1. Run or supervise the import on the new test bed, especially the file
   permissions/ownership question on the destination.
2. Sanity-check the protocol above; tell us what we are missing.
3. Confirm host requirements: PHP version and limits
   (upload_max_filesize/post_max_size for the 911MB archive if uploaded
   through the browser), and whether the host runs aggressive opcode or
   page caching we should account for.
4. Eventually: the same process against production (www.pomdr.org), with
   rollback planning. Production has live donation flows through LGL
   iframes, which kept working even during the broken deploy, and we intend
   to keep it that way.

## Planned hardening (built, pending application)

Small changes that turn any future partial deploy into a visible, mild
failure instead of a broken site: a build fingerprint constant printed into
the page head, guards at the top of the two templates that 500ed, dropping a
fragile stylesheet dependency on a handle Divi deregisters, and the theme
version bump. These are minutes of work each and will be applied before the
next export.
