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

The repo theme is a faithful superset of the production Divi child theme on
new.pomdr.org: it carries the production `functions.php` and templates
verbatim, then adds the redesign on top through a single additive require of
`inc/enqueue.php`. Nothing in the parent Divi theme is edited.

```
divi-child-integration/
  README.md             ← this file
  style.css             ← child theme header + design token imports
  functions.php         ← production theme logic; requires inc/enqueue.php
  single-pets.php       ← dog detail page template (overrides Divi)
  screenshot.png        ← theme screenshot shown in Appearance > Themes
  inc/
    enqueue.php         ← enqueues the redesign CSS/JS (loaded)
    acf-fields.php      ← ACF field group definitions (NOT loaded; see below)
    redirects.php       ← legacy URL handler (NOT loaded; opt-in, see below)
  assets/
    css/
      pomdr-design.css  ← full v2 design system (adapted from the prototype)
    js/
      gallery.js        ← lightbox from the prototype
      pomdr-nav.js      ← mobile nav supplement
  temp/                 ← reference templates from the production theme
    single-pet.php
    template-pet-profile.php
    template-blog-list.php
```

### What loads, and what does not

`functions.php` requires only `inc/enqueue.php`. Two files in `inc/` are
present for reference and are intentionally **not** wired in:

- `inc/acf-fields.php` would re-register field groups that the ACF Pro plugin
  already owns on the live site. Loading it risks double registration.
- `inc/redirects.php` is an opt-in legacy URL handler. The Redirection plugin
  currently manages redirects.

Wire either only after confirming with Andrew. The `temp/` templates are
production references, not active overrides.

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

Only `single-pets.php` is an active override in this theme today. The other
CPTs render through Divi and the production theme's own logic. Templates
listed as "Divi / production" are not yet redesigned in this repo.

| CPT slug   | Template file       | URL pattern            | Status              |
|------------|---------------------|------------------------|---------------------|
| `pets`     | single-pets.php     | /pets/{id}/            | Override in repo    |
| `pets`     | (Divi / production) | /adopt/ (main listing) | Not yet redesigned  |
| `team`     | (Divi / production) | /team/{slug}/          | Not yet redesigned  |
| `events`   | (Divi / production) | /events/               | Not yet redesigned  |
| `projects` | (Divi builder)      | /projects/{slug}/      | Not yet redesigned  |

## ACF field groups

`inc/acf-fields.php` holds version-controlled field group definitions for
reference and disaster recovery. It is **not loaded** by `functions.php` (the
ACF Pro plugin owns the live field groups). If you ever need to rebuild from
scratch, import via ACF > Tools > Import Field Groups. The `pets` CPT fields
are the most critical and most complete.

## Do not do these things

- Do not edit the parent Divi theme files directly.
- Do not deactivate ACF Pro (field groups depend on it).
- Do not use the Redirection plugin to create redirects that conflict with
  the legacy URL handler in `inc/redirects.php`.
- Do not publish to production without testing on staging first.
