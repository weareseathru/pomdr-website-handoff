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
  README.md                      ← this file
  style.css                      ← child theme header + design token imports
  functions.php                  ← production theme logic; requires inc/enqueue.php
  single-pets.php                ← dog detail page template (overrides Divi)
  screenshot.png                 ← theme screenshot shown in Appearance > Themes
  acf-export-2026-06-09.json     ← authoritative ACF field-group export (reference)
  inc/
    enqueue.php                  ← enqueues the redesign CSS/JS (loaded)
    redirects.php                ← legacy URL handler (NOT loaded; opt-in, see below)
  assets/
    css/
      pomdr-design.css           ← full v2 design system (adapted from the prototype)
    js/
      gallery.js                 ← lightbox from the prototype
      pomdr-nav.js               ← mobile nav supplement
  temp/                          ← reference templates from the production theme
    single-pet.php
    template-pet-profile.php
    template-blog-list.php
```

### What loads, and what does not

`functions.php` requires only `inc/enqueue.php`. Note:

- ACF fields are owned by the ACF Pro plugin and are NOT registered in code.
  `acf-export-2026-06-09.json` is a version-controlled reference export of the
  live field groups (see "ACF field groups" below), not a loaded file.
- `inc/redirects.php` is an opt-in legacy URL handler and is not wired in. The
  Redirection plugin currently manages redirects.

Wire `inc/redirects.php` only after confirming with Andrew. The `temp/`
templates are production references, not active overrides.

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

`acf-export-2026-06-09.json` (theme root) is the authoritative, version
controlled export of the live ACF Pro field groups and CPT registrations,
pulled from the production site on 2026-06-09. It is a reference and disaster
recovery copy, NOT loaded at runtime (the ACF Pro plugin owns the live fields).

To rebuild from scratch, import it via ACF > Tools > Import Field Groups. The
`pets` group is the critical one: `status` is a multi-value **checkbox** with
Title-Case values (`Adoptable`, `Foster Needed`, `Sponsor Needed`,
`Adoption Pending`, `Adopted`, `Hospice`, `Courtesy Listing`), `age` and
`weight` are numbers, `date_adopted` is a date_picker returning `m/d/Y`, breed
is stored as `looks_like`, and the bio is `pet_description`. The templates read
these exact names.

For a later production stage, consider enabling ACF local JSON sync (an
`acf-json/` directory with one file per group) so field changes are
version-controlled automatically. That is deliberately not enabled now to avoid
changing runtime behavior during the prototype stage.

## Do not do these things

- Do not edit the parent Divi theme files directly.
- Do not deactivate ACF Pro (field groups depend on it).
- Do not use the Redirection plugin to create redirects that conflict with
  the legacy URL handler in `inc/redirects.php`.
- Do not publish to production without testing on staging first.
