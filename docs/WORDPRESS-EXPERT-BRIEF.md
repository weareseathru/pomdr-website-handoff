# POMDR Website Redesign: Deployment Brief

Prepared September 10, 2026, updated September 11 with the fixes applied.
For the WordPress expert helping us deploy.
Contact: Andrew Z. (apzielinski62@gmail.com). Live site (untouched by all
of this): https://www.pomdr.org

## The project in short

Peace of Mind Dog Rescue (POMDR) is a nonprofit in Pacific Grove, CA that
rescues senior dogs and helps senior people keep their dogs. We rebuilt
their dated WordPress site as a modern, accessible redesign. The build is
finished and verified on a local development machine. We now need a clean
deployment to a fresh WordPress test bed, and a second set of expert eyes
on our process. Our first attempt failed in an instructive way; the cause
is fully diagnosed and the fixes are applied (see the progress log at the
end).

## How we work: tools and methods

Plain summary of the workshop, so nothing surprises you:

- **Development happens locally**, in Local (by Flywheel) on a Mac. The
  live site is never touched.
- **Everything is in Git** on GitHub, on feature branches, one logical
  change per commit. The theme in the repo is the source of truth and is
  synced to the local WordPress install.
- **The design layer is plain modern CSS** (custom properties for colors,
  type, spacing) on top of Divi. No CSS frameworks, no extra page
  builders, minimal JavaScript, progressive enhancement throughout.
- **Every page is machine-verified before we call it done**: automated
  browser screenshots (Playwright) compared against the approved design
  at four screen sizes, full-site crawls checking every URL for errors,
  and accessibility scans. The audience skews older, so we hold to WCAG
  2.2 AA, 16px minimum text, and working pinch-zoom.
- **Staff editability is a requirement, not a feature**: dogs, events,
  and videos are ordinary wp-admin edits with ACF fields; page content
  opens in the Divi Visual Builder.

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
| CDN | The retired test bed sat behind Cloudflare (CSS cached 4 hours) |

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

**What happened:** the database imported perfectly and every file NEW to
the child theme landed byte-identical. But the two files that already
existed in the old child theme on that server (`functions.php`,
`style.css`) kept their old versions. Leading suspicion: file ownership
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
evidence in `docs/forensics/2026-09-01-newpomdr/`.

## The corrected protocol

**Before export (on the dev machine):**
1. Clear Divi Static CSS so the archive ships no cache with the local
   hostname.
2. Confirm the theme version was bumped if theme files changed.
3. Export via All-in-One WP Migration > Export > File.

**Import (new test bed):**
1. Start from a FRESH WordPress install. With no pre-existing POMDR theme
   the file-merge failure cannot happen.
2. Install All-in-One WP Migration + Unlimited extension.
3. Import, wait for the explicit success screen, log back in with the
   archive's credentials (users are replaced).

**After import, always:**
1. Settings > Permalinks > Save Changes.
2. Divi > Theme Options > Static CSS > Clear + Save.
3. Purge host/CDN caches.

**Law:** never merge theme folders by hand. Theme-only updates go through
Appearance > Themes > Upload > "Replace active with uploaded" (zip root
folder named exactly `divi-child`). "Could not remove the old theme"
means the host must fix file ownership; stop and escalate.

## Sixty-second verification (view source on the home page)

- `pomdr-build` FOUND, content `2.0.0` (the whole design layer is
  running; this meta tag only prints when the current code executes)
- `pomdr.css` FOUND
- `newpomdr-local` ZERO hits
- `maximum-scale` ABSENT
- By eye: one visible h1, six dog cards on home, /adopt/ and /events/
  load, icons render as icons.

## Progress log: fixes applied 2026-09-11

Every root cause and fragility from the failed deploy now has a fix in
the theme, verified live on the local site:

1. **Theme version bumped 1.0.2 to 2.0.0** (style.css). Old and new can
   never be confused again, in wp-admin or in cached asset URLs.
2. **Build fingerprint added.** `functions.php` defines `POMDR_BUILD` and
   the enqueue layer prints `<meta name="pomdr-build" content="2.0.0">`
   into every page head. A stale functions.php cannot print it, so
   staleness is a ten-second view-source check.
3. **Deploy guards on the two templates that 500ed.** page-adopt.php and
   page-events.php now check for their helper functions BEFORE rendering
   and degrade to a calm "this page is being updated" notice with our
   phone number instead of a fatal error.
4. **Fragile stylesheet dependency removed.** The design CSS chain
   depended on a Divi handle that Divi itself deregisters late in every
   request; it only worked because a second Divi pass rewrites the
   dependencies. One Divi update could have silently removed ALL design
   CSS. The chain now depends only on our own handles, and one
   wrong-direction dependency (which could have flipped the CSS cascade
   order) was corrected.
5. **Warning-proof asset versioning.** All cache-buster timestamps now go
   through a helper that tolerates missing files, so even a partial
   deploy renders without PHP warnings.
6. **Local Divi cache cleared** and the clear-before-export /
   clear-after-import rule made standing policy.

Verified after the changes: build meta 2.0.0 on every page, full 17-asset
design chain loading, 12 dog cards on home, /adopt/ /events/ /videos/ and
native pages all healthy, zero PHP warnings. A fresh export made from
this state is what you will receive.

Still open by choice (post-deploy): removing the old Divi Theme Builder
header/footer records (they contain a mangled legacy script, currently
hidden by our CSS; removal wants a Theme Builder backup first).

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
