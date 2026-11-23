# Bridging the redesign into Divi (homepage proof)

This is the first step of putting the new design onto the WordPress (Divi)
site while keeping every Divi system: the `pets` CPT, ACF, the shortcodes in
`functions.php`, LGL forms, and Redirection. It follows the chosen approach:
**Divi Builder layouts using Code modules plus a theme stylesheet.**

## Why this approach (the diagnosis in one line)

The redesign existed only as a static prototype. The Divi pages render from
Divi Builder content in the database (the old design), and the old
`pomdr-design.css` targeted prototype class names, not Divi markup, so it
barely applied. CSS alone cannot create the new layouts; the sections have to
live in the page. So we put each redesigned section into the page as a Divi
**Code module**, and ship the styling/behavior as theme assets.

## What shipped in the theme (already live via the symlink)

| File | Role |
|------|------|
| `assets/css/pomdr-home.css` | Homepage section styles, scoped under `.pomdr-home` so they never fight Divi's header/footer. Tokens come from `pomdr-design.css`. |
| `assets/js/pomdr-home.js` | Homepage behavior: hero carousel, video facade, favorite hearts. Absent elements no-op. |
| `assets/images/` | The 37 homepage images (WebP + fallbacks), copied so paths resolve on the WP site. |
| `layouts/pomdr-home.code.html` | The homepage markup (hero through newsletter), wrapped in `.pomdr-home`, image paths already pointed at the theme. Paste this into one Divi Code module. |
| `inc/enqueue.php` | Loads `pomdr-home.css` / `.js` on the front page only. |

The accessibility layer (`a11y.css` / `a11y.js`, the floating toggle and senior
mode) is already enqueued site-wide from the previous step.

## Assemble the homepage in Divi (about 5 minutes)

1. Start the Local site, open the **Home** page, **Edit with Divi Builder**.
2. Optional but cleanest: remove the existing (old) sections on the Home page,
   or build a new page and set it as the front page.
3. Add one **Regular Section** with a single full-width column, then add a
   **Code** module to it.
4. Open `divi-child-integration/layouts/pomdr-home.code.html`, copy all of it,
   and paste it into the Code module's content box. Save.
5. View the page. The hero, pillar cards, "Who will care", dog sample, video,
   impact, happy tails, events, and newsletter render with the new look, and
   the accessibility toggle is bottom-right.

For staff editability later, the single Code module can be split into one Code
module **per section** (each `<section>...</section>` from the file), so each
block can be reordered or hidden in the Builder without touching the others.

## Known integration items to tune live (Local must be running)

These need eyes on the running site; I could not verify them with Local
stopped:

- **Header overlap.** The hero is full-bleed and Divi's header is fixed. You may
  want the section set to no top padding, or the page assigned Divi's
  "Blank / no sidebar" template so the hero sits under the transparent header.
- **The decorative paw trail** is intentionally omitted from this pass (it was
  positioned over the whole prototype page and conflicts with Divi's flow). It
  can return later as its own enhancement.
- **Dynamic dogs.** The dog sample is currently the static six from the
  prototype. To show real dogs in the new card design, the existing
  `pet_home_shortcode` in `functions.php` needs to output the new
  `.dog-card` markup (right now it outputs the old layout). That is the next
  wiring task, kept separate so the visual proof is not blocked.

## Rolling out the other pages

Each page follows the same pattern once the homepage is approved:
1. Take the prototype page's `<main>` markup, rewrite image paths to
   `/wp-content/themes/divi-child/assets/images/...`, wrap in `.pomdr-home`.
2. Drop its styles into a page-scoped stylesheet (or reuse shared component
   styles), enqueue on that page.
3. Paste into a Code module on the matching WordPress page.
4. Swap static lists for the matching shortcode where dynamic data exists
   (adoptables, events, team).

Adopt, donate, foster, helping-paw, and about are the highest-value next pages.
