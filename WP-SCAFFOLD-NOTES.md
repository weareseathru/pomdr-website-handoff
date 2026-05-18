# POMDR WP Block Theme: Scaffold Notes (Track B)

Status: decisions locked (session 2). Ready to execute steps 3 through 8
when Andrew is ready to stand up the local WP environment. The static
prototype (Track A) is demo-ready first. Dog data will be populated from
new.pomdr.org in a later session once the static demo is approved.

This document is the single source of intent for Track B. It is
designed so any future session can pick up Track B by reading this and
the Session 1 deliverables (`theme.json`, `DESIGN-TOKENS.md`,
`GAP-ANALYSIS.md`, `STACK.md`, `INVENTORY.md`, `VOICE.md`).

---

## 1. Prerequisite decisions (blocking)

Six decisions block the install. Each has a recommended answer; Andrew
confirms each before the scaffold runs. Answers land here, not in
chat, so the record survives.

All six decisions are now locked per Andrew's session 2 direction.

| # | Question | LOCKED answer | Notes |
|---|----------|---------------|-------|
| 1 | Local WP runner | wp-now | Node-based, fast. Fallback: Local by Flywheel if PHP not on PATH. |
| 2 | CPT post type slug | `pets` | Matches new.pomdr.org. HANDOFF.md's `pomdr_dog` is reconciled to `pets`. |
| 3 | Field framework | ACF Pro | Reuse live site's ACF setup. Version-controlled in PHP. |
| 4 | WP MCP / live data | Deferred | Dog data loads from new.pomdr.org in a later session. Scaffold uses seed data matching the static prototype. |
| 5 | Age-conflict canonical | Deferred | Resolves at import time. Static prototype values are canonical for the demo. |
| 6 | STACK.md integrations | Deferred | Donation processor, form plugin, mailing list deferred. Scaffold proceeds without them. |

---

## 2. Working directory and tooling

```
POMDR Website-handoff/
  pomdr-website/project/   (Track A: static prototype, design lab)
  pomdr-2026/              (Track B: block theme, will be created)
  pets-snapshot.json       (read-only snapshot from new.pomdr.org)
```

Tools required on PATH:

- Node 18 or later (for wp-now and `@wordpress/scripts`).
- npm (ships with Node).
- PHP 8.1 or later (wp-now bundles its own; verify the bundled binary
  matches the live host's version).
- Git, only when Andrew approves a branch strategy.

---

## 3. Local WordPress site

Goal: get a fresh WP install at `http://localhost:8881/` pointing at
`pomdr-2026/` so theme work has an immediate preview.

```sh
cd "POMDR Website-handoff/"
mkdir pomdr-2026
cd pomdr-2026
# wp-now picks up the theme from the current directory.
npx @wp-now/wp-now@latest start --path=. --port=8881 --wp=latest --php=8.2
```

Verification:

- The browser at `http://localhost:8881/wp-admin/` reaches a fresh WP
  admin with default credentials (`admin` / `password`).
- `npx @wp-now/wp-now status` returns the running site.

If wp-now refuses to start (PHP not on PATH, port collision, etc.),
fall back to Local by Flywheel: install Local, create a new site, point
its Sites directory at `POMDR Website-handoff/pomdr-2026-local/`,
symlink `wp-content/themes/pomdr-2026/` to `pomdr-2026/`.

---

## 4. Block theme scaffold

```sh
cd "POMDR Website-handoff/pomdr-2026"
npx @wordpress/create-block-theme@latest --name "POMDR 2026" --slug pomdr-2026
```

Replace the generated `theme.json` with the Session 1 draft at
`POMDR Website-handoff/theme.json`. Validate against the WP schema:

```sh
npx @wordpress/scripts validate-theme-json theme.json
```

Activate the theme via `wp-cli`:

```sh
wp theme activate pomdr-2026
```

Confirm the theme loads at `http://localhost:8881/` with the default
WP "Hello world" post visible against POMDR tokens.

### Folder layout (target)

```
pomdr-2026/
  style.css              (theme header only; CSS lives in theme.json)
  theme.json             (Session 1 draft, validated)
  functions.php          (entrypoint; loads inc/*.php)
  inc/
    cpt-pets.php         (registers the `pets` CPT and rewrite rules)
    acf-fields.php       (ACF field groups, version controlled)
    taxonomies.php       (dog-category taxonomy)
    block-patterns.php   (registers pattern files in patterns/)
    redirects.php        (handles `/dog.php?id=N` legacy redirects)
  parts/
    header.html
    footer.html
  templates/
    index.html
    front-page.html
    single-pets.html
    archive-pets.html
    taxonomy-dog-category.html
    page.html
    404.html
  patterns/
    dog-status-strip.php
    dog-gallery.php
    dog-campaign-banner.php
    dog-detail-hero.php
    dog-card.php
    homepage-hero.php
    homepage-impact.php
    homepage-programs.php
    cta-strip.php
    site-footer.php
  assets/
    fonts/               (vendored Source Sans 3 + Source Serif 4)
    css/                 (block-specific overrides only)
    img/
  bin/
    import-pets.php      (wp eval-file target; reads pets-snapshot.json)
  package.json
  .gitignore
```

---

## 5. `pets` CPT and ACF fields

### CPT registration (`inc/cpt-pets.php`)

```php
add_action( 'init', function () {
    register_post_type( 'pets', [
        'labels' => [
            'name'          => 'Dogs',
            'singular_name' => 'Dog',
            'add_new_item'  => 'Add a Dog',
        ],
        'public'       => true,
        'has_archive'  => true,
        'rewrite'      => [ 'slug' => 'pets' ],
        'menu_icon'    => 'dashicons-pets',
        'supports'     => [ 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ],
        'show_in_rest' => true,
    ] );

    register_taxonomy( 'dog-category', 'pets', [
        'labels'       => [ 'name' => 'Categories' ],
        'public'       => true,
        'hierarchical' => true,
        'rewrite'      => [ 'slug' => 'dogs' ],
        'show_in_rest' => true,
    ] );
}, 0 );
```

### Field map

ACF field keys map directly to the HANDOFF data model. Names normalized
to `dog_*` to avoid collisions with WP core meta. `pets` slug is locked
for backend continuity; field names remain HANDOFF-aligned.

| HANDOFF field    | ACF key            | Type        | Notes |
|------------------|--------------------|-------------|-------|
| `name`           | (post title)       | -           | Use post title, not a custom field. |
| `slug`           | (post slug)        | -           | WP post slug. |
| `legacy_id`      | `dog_legacy_id`    | number      | Powers `/pets/{int}/` and `/dog.php?id=N` redirects. |
| `breed`          | `dog_breed`        | text        |  |
| `sex`            | `dog_sex`          | select      | Male, Female. |
| `age_years`      | `dog_age_years`    | number      | Decimal supported for puppies. |
| `weight_lb`      | `dog_weight_lb`    | number      |  |
| `status`         | `dog_status`       | select      | `available`, `foster_needed`, `foster_needed_dated`, `adoption_pending`, `recently_adopted`, `hospice`, `courtesy_listing`. |
| `foster_dates`   | `dog_foster_dates` | date-range  | Conditional: visible only when status is `foster_needed_dated`. |
| `campaign_tag`   | `dog_campaign_tag` | select      | `forever_starts_here`, `helping_paw`, `sanctuary`, or empty. |
| `intake_date`    | `dog_intake_date`  | date picker |  |
| `category`       | `dog-category`     | taxonomy    | Backed by the `dog-category` taxonomy, not a custom field. Terms: `adoptable`, `courtesy`, `hospice`. |
| `featured_photo` | (post thumbnail)   | -           | Use WP featured image. |
| `gallery`        | `dog_gallery`      | gallery     | ACF Pro gallery field. Three to six images suggested. |
| `story_short`    | (post excerpt)     | -           | Map to WP excerpt for compatibility with theme excerpt blocks. |
| `story_long`     | (post content)     | -           | Map to post content. Allows Gutenberg blocks in the bio. |
| `override_*`     | `dog_override_*`   | each type   | One override per overridable field. Wins when set. Editor-only. |

### Conditional logic

- `dog_foster_dates` shows only when `dog_status == foster_needed_dated`.
- `dog_campaign_tag = sanctuary` is suggested only when
  `dog-category == hospice` (soft suggestion via field-group instruction
  text, not hard validation).

---

## 6. Template parts and templates

### `parts/header.html`

Locked pattern. Block markup mirrors `pomdr-website/project/pomdr-layout.js`
output: skip link, primary nav (rendered from `core/navigation`), donate
CTA. Footer is its own template part.

### `parts/footer.html`

Single source for POMDR identity. Pulls from theme options (or a
single-instance "Site Identity" CPT) so EIN, addresses, hours, and
social links edit in one place. Footer column headers as `<h2>` for
landmark coherence.

### `templates/single-pets.html`

Mirrors the static prototype's dog detail page exactly:

1. Hero region: name, vitals line, status strip, lead paragraph, CTAs.
2. Gallery region: three to six thumbnails, click-to-lightbox via
   `@wordpress/interactivity`.
3. Optional campaign banner above the hero when `dog_campaign_tag` is
   set.
4. Bottom CTA strip with status-appropriate phrasing.

Block bindings (`@wordpress/block-bindings`):

- `core/heading` (h1) binds to post title.
- Vitals line is a custom dynamic block reading `dog_age_years`,
  `dog_sex`, `dog_weight_lb`, `dog_breed`.
- Status strip is a custom dynamic block keyed off `dog_status`.
- Gallery is a custom block reading `dog_gallery`.

### `templates/archive-pets.html`

Reuses the static prototype's adopt grid. Block-pattern level filter
toolbar (search, category chips, sort) wired to URL query strings via
`@wordpress/interactivity`.

### `templates/taxonomy-dog-category.html`

Same archive template, scoped by taxonomy term. Powers `/dogs/adoptable/`,
`/dogs/courtesy/`, and `/dogs/hospice/` from one template.

### `templates/front-page.html`

Mirrors the static prototype's `index.html`: hero rotator, programs,
featured dogs, impact, happy tails, newsletter.

### `templates/page.html`

Generic page template used by the 17 stubs from Track A. Replaces those
HTML files in the WP world.

---

## 7. Data dovetail with new.pomdr.org

### 7.1 Read-only snapshot

WP MCP runs against new.pomdr.org with read scope. Capture target:

- All `pets` CPT entries: post object + every ACF field value + featured
  image URL + gallery image URLs.
- All `dog-category` taxonomy terms.
- All ACF field group definitions on `pets`.
- All media library entries referenced by `pets` posts (URLs only;
  files download in step 7.2).

Output: `POMDR Website-handoff/pets-snapshot.json`. Pretty-printed,
sorted by post ID, frozen reference.

### 7.2 Local import

`bin/import-pets.php` reads `pets-snapshot.json` and:

1. Creates a `pets` post on the local WP for each snapshot entry,
   preserving the post slug.
2. Sets the post title, content, excerpt, status, and date from the
   snapshot.
3. Sets all ACF field values via `update_field()`.
4. Downloads referenced media into the local WP media library and
   re-points featured image + gallery field values at the new
   attachment IDs.
5. Assigns `dog-category` taxonomy terms.

Run via:

```sh
wp eval-file bin/import-pets.php
```

Idempotent: re-running updates existing posts, does not duplicate.

### 7.3 Cutover strategy

This file's scope ends at the local-import step. Production cutover
(replacing new.pomdr.org's active theme) is a separate session and
requires:

- Approved theme on local, walked top-to-bottom against
  `PROTOTYPE-WALKTHROUGH.md`.
- Backup of new.pomdr.org's database + uploads.
- A staging site at staging.pomdr.org with the new theme.
- 30-day side-by-side review window.
- Andrew's explicit greenlight.

---

## 8. Verification (Track B)

When the steps in sections 3 through 7 are complete, run these checks
before considering the scaffold landed:

- [ ] Local WP loads at the chosen port with `pomdr-2026` active.
- [ ] `theme.json` validates against the WP schema with no warnings.
- [ ] `pets` CPT visible in WP admin sidebar. "Add new Dog" opens an
      ACF field grid matching the table in section 5.
- [ ] `dog-category` taxonomy visible, three default terms seeded.
- [ ] `wp eval-file bin/import-pets.php` against a hand-edited
      three-dog snapshot creates three local posts with all fields
      populated.
- [ ] `single-pets.html` template renders one of the imported dogs
      with hero, status strip, gallery, and status-appropriate CTAs.
- [ ] `archive-pets.html` lists the imported dogs.
- [ ] Lighthouse mobile score on `single-pets.html` reaches 90+ on
      performance and 100 on accessibility.
- [ ] axe-core reports zero serious or critical violations on the
      three templates above.
- [ ] `prefers-reduced-motion: reduce` disables every animation.

---

## 9. Out of scope (for this scaffold step)

- Production deploy.
- Activating the theme on new.pomdr.org or any staging URL.
- Full migration of all live `pets` posts (only a three-dog snapshot
  for the scaffold; full migration is a later session).
- LGL form replacement.
- Donation processor integration.
- Mailchimp wiring.
- Image optimization pipeline.
- Search (will be Algolia or native; deferred).

---

## 10. Cross-track sync

Any change in Track A's design that should propagate to Track B lands
first in the relevant Session 1 deliverable, then both tracks
re-implement:

| Change type            | Update first                | Then update |
|------------------------|-----------------------------|-------------|
| Color, type, spacing   | `DESIGN-TOKENS.md` + `theme.json` | Track A inline CSS + Track A `pomdr.css` |
| Status vocabulary      | `GAP-ANALYSIS.md`           | Track A `data.jsx` + Track B `cpt-pets.php` |
| Copy / voice rules     | `VOICE.md`                  | Track A `data.jsx` + Track B pattern placeholders |
| Nav structure          | `INVENTORY.md`              | Track A `pomdr-layout.js` + Track B `parts/header.html` |
| Integration decisions  | `STACK.md`                  | Track B integration code |

This file (`WP-SCAFFOLD-NOTES.md`) is updated at the start of every
Track B session as decisions resolve.

Owner: Andrew Z.
