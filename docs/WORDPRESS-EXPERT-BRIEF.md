# POMDR Website Redesign: Deployment Brief

Prepared September 10, 2026, updated September 11 with all pre-deploy
fixes applied. From Andrew Z. at POMDR (apzielinski62@gmail.com).

This brief covers where the redesign stands, the stack and methods behind
it, what went wrong on our first deployment attempt, the fixes applied
since, the corrected deployment process, and where we could use your help.

## Where things stand

Our test bed lives at **https://new.pomdr.org**. It carries the site's
working foundation (the Divi base build, the pets post type and ACF
fields, the LGL forms), and that foundation is what my design process
started from: my local development copy was cloned from it, and the
redesign layers a new child theme on top of everything that already
works there.

The redesign is now finished and verified on my local machine: the whole
public site rebuilt for accessibility and easier adopting, fostering, and
donating, with every page machine-checked against the approved design.
The live site (www.pomdr.org) is untouched. **new.pomdr.org is where we
are trying to deploy the new child theme.** Our first attempt to move the
build onto it failed in an instructive way. We diagnosed it completely,
fixed everything fixable in code, and wrote the corrected process below.
What we need now is a clean deployment to the test bed with you looking
over our shoulder, especially on the hosting side.

## How we have been working

So you know what you are walking into:

- **Development is local-first**, in Local (by Flywheel) on a Mac. The
  live site is never touched.
- **Everything is in Git** on GitHub, feature branches, one logical
  change per commit. The theme in the repo is the source of truth and is
  synced to the local WordPress install.
- **The design layer is plain modern CSS** (custom properties for colors,
  type, spacing) on top of Divi. No CSS frameworks, no extra page
  builders, minimal JavaScript, progressive enhancement throughout.
- **Every page is machine-verified before we call it done**: automated
  browser screenshots (Playwright) compared against the approved design
  at four screen sizes, full-site crawls, and accessibility scans. Our
  audience skews older, so we hold to WCAG 2.2 AA, 16px minimum text, and
  working pinch-zoom.
- **Staff editability is a requirement**: dogs, events, and videos are
  ordinary wp-admin edits with ACF fields; page content opens in the Divi
  Visual Builder.

## The stack

| Piece | Detail |
|---|---|
| Platform | WordPress (current core), single site |
| Theme | Divi 5.2.1 parent (pinned, auto-updates off) + custom child theme `divi-child`, version 2.0.0 |
| Custom fields | ACF Pro (field groups for dogs, events, team) |
| Content types | Custom post types: `pets` (~185 dogs), `events`, `videos`, `team` |
| Forms and donations | Little Green Light (LGL) embedded iframes, no form plugins |
| Other plugins | Redirection, All-in-One WP Migration |
| Transfer | All-in-One WP Migration `.wpress` archive, about 911MB |
| Test bed | https://new.pomdr.org, behind Cloudflare (CSS cached 4 hours) |

## How the theme renders (one minute)

Two families of pages:

1. **Native Divi 5 pages (29).** Real Divi 5 block content on the page
   records, routed through `page-native.php` by a post meta
   (`_pomdr_native`).
2. **Template pages (the rest).** Hardcoded `page-{slug}.php` files that
   render the design directly.

Dog grids, events, and video grids come from shortcodes and helpers in
`functions.php` and `inc/*.php`. All design CSS/JS is enqueued by
`inc/enqueue.php`.

The one critical fact: `functions.php` loads everything else. If an old
copy of it runs, the entire design layer silently disappears while the
template files still execute. That is exactly what our first deploy did.

## Deployment strategy, and what went wrong

**Strategy:** export the whole site from Local with All-in-One WP
Migration (Export to File), import the `.wpress` on the test bed with the
same plugin. One archive carries database, theme, plugins, media. At
911MB it needs the paid Unlimited extension on the import side (free
importer caps at 512MB).

**What happened on new.pomdr.org:** the database imported perfectly and
every file NEW to the child theme landed byte-identical. But the two
files that already existed in the old child theme on that server
(`functions.php`, `style.css`) kept their old versions. Leading suspicion: file ownership
or permissions blocked overwriting pre-existing files while creating new
ones succeeded. Result: unstyled pages, two 500s (/adopt/ and /events/,
new templates calling helpers the old functions.php lacks), empty dog
grids, the old header exposed.

**Second, independent defect:** Divi writes generated CSS to
`wp-content/et-cache` with the origin hostname baked in. Our archive
carried cache pointing at the local dev machine, which broke icon fonts.
This lives in cache files (the database was verified clean), so
search-replace cannot fix it; only clearing Divi's Static CSS can.

**Why nobody noticed for a week:** old and new `style.css` both said
version 1.0.2. Everything in wp-admin looked normal.

Full forensic record: `docs/DEPLOY-FORENSICS-2026-09-01.md` plus raw
evidence in `docs/forensics/2026-09-01-newpomdr/` (repo access on
request).

## The corrected protocol

**Before export (on the dev machine):**
1. Clear Divi Static CSS so the archive ships no cache with the local
   hostname.
2. Confirm the theme version was bumped if theme files changed.
3. Export via All-in-One WP Migration > Export > File.

**Import (on the test bed):**
1. Neutralize the file-merge failure first. Cleanest is a fresh WordPress
   install; when deploying onto new.pomdr.org as it stands, instead
   delete the old child theme before importing: Appearance > Themes >
   activate the Divi parent temporarily > delete "Divi Child" entirely.
   With no pre-existing `divi-child` folder, every theme file is created
   fresh (file creation worked fine last time; only overwriting failed).
2. Install All-in-One WP Migration + Unlimited extension.
3. Import, wait for the explicit success screen, log back in with the
   archive's credentials (users are replaced).

**After import, always:**
1. Settings > Permalinks > Save Changes.
2. Divi > Theme Options > Static CSS > Clear + Save.
3. Purge host/CDN caches.

**Law:** never merge theme folders by hand. Theme-only updates go through
Appearance > Themes > Upload > "Replace active with uploaded" (zip root
folder named exactly `divi-child`; a versioned zip is staged and ready).
"Could not remove the old theme" means the host must fix file ownership;
stop and escalate.

## Sixty-second verification (view source on the home page)

- `pomdr-build` FOUND, content `2.0.0` (the whole design layer is
  running; this meta only prints when the current code executes)
- `pomdr.css` FOUND
- `newpomdr-local` ZERO hits
- `maximum-scale` ABSENT
- By eye: one visible h1, six dog cards on home, /adopt/ and /events/
  load, icons render as icons.

There is also a scripted version: `scripts/deploy-verify.sh <site-url>`
crawls all 39 public pages and checks every canary plus populated dog and
video grids, and exits nonzero on any failure.

## Progress log: pre-deploy fixes, all applied and verified 2026-09-11

Everything we identified as fixable before redeploying is now done, in
the theme, verified live on the local site:

1. **Theme version bumped 1.0.2 to 2.0.0** (style.css). Old and new can
   never be confused again, in wp-admin or in cached asset URLs.
2. **Build fingerprint.** `functions.php` defines `POMDR_BUILD` and the
   enqueue layer prints `<meta name="pomdr-build" content="2.0.0">` into
   every page head. A stale functions.php cannot print it.
3. **Deploy guards on the two templates that 500ed.** page-adopt.php and
   page-events.php check for their helper functions BEFORE rendering and
   degrade to a calm "this page is being updated" notice with our phone
   number instead of a fatal error.
4. **Fragile stylesheet dependency removed.** The design CSS chain
   depended on a handle Divi itself deregisters late in every request;
   one Divi update could have silently removed ALL design CSS. The chain
   now depends only on our own handles, and one wrong-direction
   dependency that could have flipped the CSS cascade order was
   corrected.
5. **Warning-proof asset versioning.** All cache-buster timestamps go
   through a helper that tolerates missing files, so even a partial
   deploy renders without PHP warnings.
6. **Staff-visible design-layer canary.** The design tokens stylesheet
   sets a `--pom-build` custom property and a small always-on script
   checks it: if the design CSS ever fails to load, logged-in users see a
   red banner at the top of every page and the console logs the cause.
   Visitors never see it.
7. **Deploy README rewritten** (`docs/DEPLOY-README-FOR-COWORKER.md`)
   with the golden rules, the corrected sizes, the three mandatory
   after-import steps, the sixty-second proof, and the escalation path.
8. **Automated verification script** (`scripts/deploy-verify.sh`) that
   crawls all 39 pages of any deploy target and checks every canary;
   it passes green against the local build.
9. **Theme zip staged** with the correctly named root folder
   (`divi-child-2.0.0.zip`, on the deploy kit folder with the README),
   for the theme-only update path.
10. **Local Divi cache cleared**, and clear-before-export /
    clear-after-import made standing policy.

Verified after all changes: build meta 2.0.0 on every page, full 17-asset
design chain loading, 12 dog cards on home, /adopt/ /events/ /videos/ and
native pages all healthy, zero PHP warnings, and the crawl script passes
all 39 pages. A fresh export made from this state is what you will
receive.

Deliberately left for after the redeploy: removing the old Divi Theme
Builder header/footer records (they contain a mangled legacy script,
currently hidden by our CSS; removal wants a Theme Builder backup first,
and our CSS also hides Divi's fallback chrome so the removal is low
risk).

## Where we would value your help

1. Run or supervise the import on the new test bed, especially file
   permissions and ownership on the destination.
2. Sanity-check the protocol above; tell us what we are missing.
3. Confirm host requirements: PHP version and limits (upload and post
   size for a 911MB browser upload), opcode/page caching behavior.
4. Eventually, the same process against production, with rollback
   planning. Production has live donation flows through LGL iframes,
   which kept working even during the broken deploy, and we intend to
   keep it that way.
