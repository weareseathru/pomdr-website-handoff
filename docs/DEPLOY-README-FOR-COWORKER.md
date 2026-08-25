# POMDR Site Deployment — Instructions

You have received a single archive file (`.wpress`) containing the complete
redesigned POMDR website: WordPress database, the POMDR 2026 child theme,
the Divi 5.2.1 parent theme, all plugins, and all media. Importing it
replaces EVERYTHING on the destination site.

## Before you start

1. **Warning: the import wipes the destination.** Every page, post, dog
   record, and setting on the target site is replaced by this archive's
   contents. Only proceed on a site whose current content is disposable.
2. The destination needs WordPress installed (any recent version) with the
   **All-in-One WP Migration** plugin, PLUS its **Unlimited Extension**
   (paid, servmask.com). The free importer stops at 512MB and this archive
   is roughly 700MB. Without the extension the import will refuse the file.
3. Recommended PHP 8.1 or 8.2 on the host.

## Import steps

1. In the destination wp-admin: Plugins > install and activate
   All-in-One WP Migration + the Unlimited Extension.
2. All-in-One WP Migration > Import > drag the `.wpress` file in.
   Confirm the overwrite warning. Wait for "site imported successfully."
3. Log in again (the archive's users replace the site's users; ask Andrew
   for credentials).
4. Settings > Permalinks > click **Save Changes** (flushes URL rules; do
   this even though nothing looks wrong).
5. If prompted, re-enter license keys: ACF Pro, Divi (Elegant Themes).

## Verify (10 minutes, in this order)

1. Open the home page: hero photo visible, three Adopt/Donate/Volunteer
   cards, six adoptable dogs in two rows.
2. Open /adopt/ and use the search box, filter, and sort.
3. Open any single dog page.
4. Open /donate/ and submit a **$1 live donation** through the form (this
   is the revenue path; nothing is "done" until this clears).
5. Open /volunteer-application/ and confirm the embedded form loads.
6. Open /events/ and /videos/.
7. Click through the top navigation dropdowns and the footer links.

If anything fails, do not troubleshoot live: tell Andrew, re-import the
archive (repeatable), or restore the host's own backup.

## What you are looking at (context)

- Most pages render as native Divi 5 layouts, verified pixel-identical to
  the approved design at four screen widths by an automated harness.
- A few surfaces intentionally still use the theme's PHP templates (dog
  detail pages, and any page not yet cut over); they look and work
  identically and convert later without redeploying.
- Do not update the Divi theme on this site without checking with Andrew:
  the build is pinned and verified against Divi 5.2.1.

Questions: Andrew Z.
