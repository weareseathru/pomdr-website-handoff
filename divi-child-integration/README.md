# POMDR Divi Child Theme Integration

How to copy the POMDR redesign into your existing Divi Child theme.

## What this directory is

This folder contains everything needed to drop the redesign into the existing
WordPress installation at new.pomdr.org, which runs:

- Theme: Divi (parent) + Divi Child (activated)
- Plugins: Advanced Custom Fields Pro, Redirection
- CPTs: Pets, Posts, Team, Events, Media, Pages, Projects

All files here are ready to copy into the Divi Child theme directory. The
static prototype in `pomdr-website/project/` is the visual spec; this
directory is the WordPress implementation layer.

## Architecture overview

```
divi-child-integration/
  README.md                ← this file
  style.css                ← child theme header + design token imports
  functions.php            ← enqueue styles/scripts, register hooks
  single-pets.php          ← dog detail page template (overrides Divi)
  archive-pets.php         ← dog listing grid template (overrides Divi)
  page-whats-happening.php ← What's Happening events page template
  inc/
    acf-fields.php         ← ACF field group definitions (all CPTs)
    redirects.php          ← legacy URL handler (dog.php?id=N)
    enqueue.php            ← script and style enqueue functions
  assets/
    css/
      pomdr-design.css     ← full design system (copy of pomdr.css, adapted)
    js/
      gallery.js           ← lightbox from prototype
      pomdr-nav.js         ← mobile nav supplement
```

## How to deploy

1. Find your Divi Child theme directory in WordPress:
   `wp-content/themes/divi-child/` (or whatever the child is named)

2. Copy these files into that directory, matching the folder structure above.

3. In WordPress Admin > Appearance > Theme File Editor, verify the child
   theme's `style.css` header shows the correct parent theme reference.

4. Run `scripts/check-voice.sh` from this repo root to verify no copy
   violations before pushing.

5. Test on staging (new.pomdr.org) before touching production.

## Which CPTs map to which template

| CPT slug  | Template file         | URL pattern              |
|-----------|-----------------------|--------------------------|
| `pets`    | single-pets.php       | /pets/{id}/              |
| `pets`    | archive-pets.php      | /adopt/ (main listing)   |
| `team`    | single-team.php       | /team/{slug}/            |
| `events`  | archive-events.php    | /events/                 |
| `projects`| (uses Divi builder)   | /projects/{slug}/        |

## ACF field groups

All field groups are version-controlled in `inc/acf-fields.php`. Import them
via ACF > Tools > Import Field Groups if you ever need to rebuild from scratch.
The `pets` CPT fields are the most critical and most complete.

## Do not do these things

- Do not edit the parent Divi theme files directly.
- Do not deactivate ACF Pro (field groups depend on it).
- Do not use the Redirection plugin to create redirects that conflict with
  the legacy URL handler in `inc/redirects.php`.
- Do not publish to production without testing on staging first.
