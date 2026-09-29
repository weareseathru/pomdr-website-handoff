# POMDR Site Deployment — Instructions

Updated 2026-09-11 after the first deployment attempt. That attempt taught
us exactly what can go wrong, and these instructions close every gap. The
short version of what happened: the database transferred perfectly, but on
a server that already had an old copy of the theme, two pre-existing theme
files were not overwritten, and the site ran a broken mix of old and new
code. Nothing was lost, and the rules below make a repeat impossible.

You have received a single archive file (`.wpress`) containing the complete
redesigned POMDR website: WordPress database, the divi-child theme
(version 2.0.0), the Divi 5.2.1 parent theme, all plugins, and all media.
Importing it replaces EVERYTHING on the destination site.

## Golden rules

1. **Import onto a FRESH WordPress install.** A destination that never had
   a POMDR theme cannot repeat the old-files problem.
2. **Never merge or hand-copy theme folders** (no FTP upload over an
   existing folder, no file-manager unzip on top). That is what broke the
   first attempt.
3. **Always run the three "after import" steps.** They are not optional.
4. If anything unexpected appears: stop, screenshot, tell Andrew. Do not
   troubleshoot live.

## Before you start

1. **The import wipes the destination.** Every page, post, dog record, and
   setting on the target is replaced. Only proceed on a site whose current
   content is disposable.
2. The destination needs WordPress (any recent version) with the
   **All-in-One WP Migration** plugin PLUS its **Unlimited Extension**
   (paid, servmask.com). The free importer stops at 512MB and this archive
   is roughly **911MB**; without the extension the import refuses the file.
3. Recommended PHP 8.1 or 8.2 on the host.

## Import steps

1. In the destination wp-admin: Plugins > install and activate
   All-in-One WP Migration + the Unlimited Extension.
2. All-in-One WP Migration > Import > drag the `.wpress` file in.
   Confirm the overwrite warning. Wait for the explicit
   **"site has been imported successfully"** screen. If the progress bar
   stalls or errors, screenshot and stop.
3. Log in again (the archive's users replace the site's users; ask Andrew
   for credentials).

## After import, always, in this order

1. **Settings > Permalinks > Save Changes** (change nothing, just Save).
   This wakes up the dog, event, and video page URLs.
2. **Divi > Theme Options > find Static CSS File Generation** (Builder >
   Advanced tab, or Performance) > click **Clear**, then Save. This
   regenerates Divi's cached CSS for the new server address. Skipping it
   leaves broken icon fonts. If you cannot find the button, delete the
   folder `wp-content/et-cache` in the host file manager (it rebuilds
   itself).
3. **Purge caches**: the host's cache button if the panel has one, and
   Cloudflare "Purge Everything" if the domain is behind Cloudflare. Then
   hard-refresh your browser (Cmd+Shift+R or Ctrl+F5).
4. If prompted, re-enter license keys: ACF Pro, Divi (Elegant Themes).

## The sixty-second proof (do this before the 10-minute check)

On the home page, right-click > View Page Source, and use Find (Cmd+F):

| Search for | Expected |
|---|---|
| `pomdr-build` | FOUND, content "2.0.0". This tag only prints when the current theme code runs |
| `pomdr.css` | FOUND (the design stylesheets are loading) |
| `newpomdr-local` | ZERO hits (no leftover local-machine addresses) |
| `maximum-scale` | NOT found (pinch-zoom works for older visitors) |

Also: Appearance > Themes must show **Divi Child version 2.0.0** (the old
broken deploy showed 1.0.2). Logged-in users would see a red warning
banner at the top of every page if the design CSS were not loading; no
banner is a good sign.

## Verify (10 minutes, in this order)

1. Home page: hero photo, three Adopt/Donate/Volunteer cards, six
   adoptable dogs in two rows, icons render as icons (not empty squares).
2. /adopt/ loads (no "critical error") and search, filter, sort work.
3. Any single dog page.
4. /donate/ and submit a **$1 live donation** through the form (this is
   the revenue path; nothing is "done" until this clears).
5. /volunteer-application/ embedded form loads.
6. /events/ and /videos/ both load with content.
7. Top navigation dropdowns and footer links.

If anything fails: tell Andrew, re-import the archive (repeatable), or
restore the host's own backup. One specific error to know: if a theme
upload ever says **"Could not remove the old theme"**, that is a file
ownership problem only the hosting company can fix. Stop and escalate.

## Theme-only updates later (no full import)

When only the theme changes, Andrew sends a `divi-child.zip`. Install via
Appearance > Themes > Add New Theme > Upload Theme > choose the zip >
Install Now > click **"Replace active with uploaded"** on the comparison
screen. The version number on that screen must go UP. Never install the
theme any other way.

## What you are looking at (context)

- Most pages render as native Divi 5 layouts, verified pixel-identical to
  the approved design at four screen widths by an automated harness.
- A few surfaces intentionally still use the theme's PHP templates (dog
  detail pages, and any page not yet cut over); they look and work
  identically and convert later without redeploying.
- Do not update the Divi theme on this site without checking with Andrew:
  the build is pinned and verified against Divi 5.2.1.

Questions: Andrew Z.
