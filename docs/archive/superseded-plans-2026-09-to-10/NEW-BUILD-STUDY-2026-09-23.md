# New-Build study: the code front to back, and the look we are carrying over

2026-09-23. Written for Andrew and whoever builds the public design next.
Everything below was read in code or measured in a browser today. Screenshots
and the raw measurements are in `.playwright-mcp/study/` (gitignored), made by
`shoot.mjs` and `reveal.mjs` in the same folder.

The direction this serves: leave WordPress and Divi behind, build on the
New-Build repo (`ronniereid/pomdr`, cloned at `New-Build/`), and make the
public site look as close as possible to newpomdr-local, on a backend with none
of WordPress's limits.

---

## 1. New-Build, front to back

### 1.1 What it is

One codebase, two deployments.

| Host | Serves | Built from |
|---|---|---|
| `admin.pomdr.org` | Live PHP 8.4 + MySQL. `/` is the public site rendered live (the staff preview), `/admin/` is "POMDR Hub", the staff app. Behind Cloudflare Access. | `public/`, `admin/` |
| `www.pomdr.org` | Plain static HTML on Cloudflare Pages. No PHP, no database, no credentials. | `build/build.php` renders every `public/*/index.php` plus one page per adoptable pet into a second private repo, `pomdr-public`, hourly at :17 |

Staff edit in Hub, MySQL changes, the preview shows it at once, and the public
site catches up at the next hourly build. Photos live in Cloudflare R2
(`images.pomdr.org`) with masters on the admin box, never in git.

### 1.2 The request, top to bottom

```
public/<section>/index.php
  1. sets $page_title, $page_description, optional $page_intro array
  2. $root = dirname(__DIR__, 2)
  3. require config.php -> db.php -> functions.php (+ images.php when it prints photos)
  4. calls NAMED query helpers only (get_available_pets() ...). No SQL in pages.
  5. require includes/header.php     <head>, action bar, nav card, breadcrumb, <main>
  6. require includes/page-intro.php  the opening band, prints the <h1>
  7. plain HTML markup, every value through e()
  8. require includes/footer.php      footer from site-info.php, closes the page
```

One page is one directory. There is no router, no template engine and no
registry. Adding a page means copying a directory.

### 1.3 The layers

| Layer | Files | Notes |
|---|---|---|
| Config | `includes/config.php`, gitignored `config.local.php` | Local file overrides the database and paths |
| Data | `includes/db.php` (~3,000 lines) | Lazy PDO, `db_all`/`db_one`/`db_run`, then one named function per use. All prepared statements. |
| Helpers | `includes/functions.php` | `e()`, `format_age()`, `pet_statuses()`, `pet_has_status()`, event date formatting |
| Images | `includes/images.php`, `includes/r2.php` | `image_url($key, $width)` is the only way a database photo reaches a page. Stores one 2000px master, makes every other width on demand. |
| Public chrome | `header.php`, `page-intro.php`, `footer.php`, `site-info.php`, `icons.php`, `form-application.php` | Design layer that merged 2026-09-22 |
| Public pages | `public/` 28 pages + `pets/detail.php` + `patterns.php` (dev-only kit showcase) | |
| Style | `public/assets/css/site.css` (2,031 lines), fonts self-hosted | One stylesheet by rule. No inline styles, no `<style>` blocks. |
| Admin (Hub) | `admin/` ~20 pages, `admin.css`, one JS file (search palette) | Ronnie's active area: dashboard, people, clinic, global search |
| Jobs | `bin/` + cron: pull, image sync, build, audit, backup, heartbeat | Every job writes `job_runs`; heartbeat to Better Stack only when all are healthy |
| Sync | LGL (people) and Shelterluv (animals), one-way read-only mirrors | Never write back. Hub owns only events, page wording, photos, publish status. |
| Schema | `db/schema.sql` + numbered `db/migrations/` | Flat: status is boolean columns on `pets` (`is_available`, `is_foster`, `is_hospice`, `is_courtesy`, `is_adopted`, `is_draft`) |

### 1.4 House rules that shape any design work

From the repo's README section 3 and CLAUDE.md. These rules are not ours to
change; Ronnie commits about 30 times a day and this is his codebase.

1. Plain PHP. No framework, Composer, npm, bundler or template engine.
2. One stylesheet for the public site. No inline styles.
3. HTML is written as HTML, not built in PHP strings.
4. Every value goes through `e()`. Every query is a named helper in `db.php`.
5. The build only renders. The static site must equal what the server serves.
6. Links are root-relative. Code says "pet", copy says "dog".
7. Graphics and photos you place in a page by hand go in `public/assets/img/`,
   run through `bin/prepare-page-image.php`. Database photos go through `image_url()`.
8. The public site today ships **zero** `<script>` tags.

### 1.5 Upstream activity since last sync

16 commits on 2026-09-22 and 09-23, all Hub-side (global search, a real
dashboard, scheduled LGL sync, people tiles). Nothing in `public/`. Our six
form pages and the section kit are still staged and uncommitted locally.

---

## 2. The look we are carrying over (newpomdr-local)

### 2.1 Where that look actually lives

Most of what makes newpomdr-local look the way it does is **our own code**, in
`divi-child-integration/`, not Divi's: about 9,800 lines of CSS across seven
files (`pomdr.css`, `pomdr-design.css`, `pomdr-home.css`, `pomdr-chrome.css`,
`native-boost*.css`, `tokens.css`) and 1,200 lines of JS. So carrying the look
over is **a port of our own design**, not reverse-engineering a page builder.
Divi mostly contributes weight.

### 2.2 Measured, same pages, 1440px

| | newpomdr-local (Divi) | New-Build |
|---|---|---|
| Scripts per page | 24 to 28 | **0** |
| Stylesheets per page | 19 to 22 | **1** |
| Load to network idle (local) | ~1,400 ms | ~520 ms |
| Images with no width/height | 4 to 14 per page | 0 |
| Body text | Source Sans 3, 24px | Source Sans 3, 19px |
| Page title (h1) | Source Serif 4, 68 to 84px (home hero 89px sans 300) | Source Serif 4, 46px |
| Section heading (h2) | Serif, 32 to 54px | Serif, 28px |
| Footer | Deep teal band, white text | Light blue `#e8f2f6`, dark text |
| Content visible without JS | **No.** Home sections are opacity 0 until a GSAP scroll reveal runs; a full-page capture shows blank bands | Yes |

### 2.3 The component vocabulary (homepage, adopt, dog page)

What a visitor sees on newpomdr-local, top to bottom, and whether New-Build has it.

| Component | On Divi site | New-Build today |
|---|---|---|
| Action bar + floating nav card + Donate pill | Yes | **Yes, matches** |
| Hero: full-bleed photo, frosted teal panel, big headline with italic serif accent, 3 pill buttons, 5-slide carousel with pause | Yes | No hero. Plain h1 + 2 buttons |
| Promo strip (purple, eyebrow chip, dismiss) | Yes | No |
| Photo tiles (Adopt / Donate / Volunteer, text on photo with gradient) | Yes | No |
| Section eyebrow: paw icon + UPPERCASE tracked label, serif title with italic blue accent phrase | Yes, everywhere | Accent exists in the band only; no eyebrow |
| Program cards: icon chip, serif title, text, dashed arrow list, "Explore" link | Yes | Card + icon-chip + arrow-link exist in the uncommitted kit |
| Dog card: photo, status pill (purple FOSTER NEEDED), heart, serif name, `~13 yrs · Male · 13 lb · breed`, Senior chip, "View profile" row | Yes | Text-only card, "13 years old", no photo, no status |
| Video tiles with play button + "See all" outline tile | Yes | No (videos page exists) |
| Stats: big light serif numbers with superscript + | Yes | `.stat` in kit |
| Testimonial cards: quote mark, italic serif, blue rule, avatar initial, name + role | Yes | `.quote` in kit, simpler |
| Events: date chip (OCT 17) list + featured purple gradient card | Yes | Plain list |
| Newsletter band + card | Yes | Mailing-list page only |
| Adopt page: intro band with live counts, search, status filter chips, sort, tabs with counts (Ours 104 / Other 12 / Hospice 1 / Adopted 64) | Yes, JS-driven | Band + grid only |
| Dog page: back link, status pill, huge serif name, meta line, Adopt + Sponsor pills, rounded photo, bio card | Yes | Name, two lines of text, back link |
| "Larger text" floating toggle | Yes | No |
| Dark teal footer | Yes | Light footer (deliberate, Design.md) |

### 2.4 What not to carry over

Found on newpomdr-local and worth leaving behind:

- **Content hidden until JS runs** (scroll reveals). Fragile, and hostile to slow connections and older readers.
- **Hero carousel.** Five rotating slides is a time-based interaction our own quality gate forbids; only slide one gets seen.
- **Status packed into the dog's name.** "Mihla – Aged to Perfection – 8/24-8/31 only" is a title field doing the job of a status and foster dates. It then leaks into "Adopt Mihla – Aged to Perfection – 8/24-8/31 only". Fix in data, not in CSS.
- **Centred body text** in the dog bio card. Hard to read at length.
- **Leftover cream band** behind the adopt search (the pre-July palette).
- **A hardcoded old email** (`foster@peaceofminddogrescue.org`) inside a dog bio.

---

## 3. How to build it: recommendation

**Keep New-Build's stack exactly as it is, and port our design onto it as a
component layer.** Plain PHP partials, one stylesheet, static HTML. This is the
"free of WordPress" backend already, and it measures 3x faster with zero
scripts. Adding React, Tailwind or a bundler would break the repo's rules and
put us at odds with Ronnie's daily commits.

### 3.1 Components as partials, the way `page-intro.php` already works

A component is `includes/components/<name>.php`, fed an array, printing plain
HTML with every value escaped. Pages call it, never copy it.

```php
$pet_card = ['pet' => $pet, 'show_status' => true];
require $root . '/includes/components/pet-card.php';
```

First set, in order of value: `pet-card`, `section-head` (eyebrow + accent
title), `hero`, `photo-tile`, `program-card`, `testimonial`, `event-row` +
`event-feature`, `video-tile`, `newsletter`. Then the adopt page and the dog page.

### 3.2 CSS, still one file, organised with modern CSS

- `@layer tokens, base, chrome, components, pages;` at the top of `site.css`, so
  order stops mattering and a page rule can never be beaten by specificity.
- Port the token values from `divi-child-integration/assets/css/tokens.css`
  into the existing `:root`. The colours already match; the type scale is the gap.
- Container queries on cards, so a card reflows by its column, not the window.
- `:has()` and `:focus-within` for state that would otherwise need JS.

### 3.3 Interactions, without shipping a framework

| Divi feature | New-Build approach |
|---|---|
| Hero carousel | One hero photo, chosen per page (or rotated per build, never per second) |
| Scroll reveals | None. Content is visible on arrival. A reduced-motion-safe CSS entrance at most. |
| Adopt filters + tabs | Real static pages per status (`/adopt/`, `/foster-needs/`, `/hospice/`, `/adopted/`) as tabs that are links, so they work with no JS and are shareable. Optional: one small progressive-enhancement script for instant search. Needs sign-off, since it would be the site's first script. |
| Status pills, counts | Printed at build time from the database |
| Larger text toggle | Drop it; browser zoom does this, and the base is already 19px. Or a CSS-only version if staff want it. |
| Favourites heart | Drop it. It saves to one browser only and nobody asked for it. |

### 3.4 Testing rig (ready now)

- `.playwright-mcp/study/shoot.mjs`: both sites, eight templates, 1440 + 390,
  screenshots and computed type/colour per page. Rerun after each component for
  a side-by-side parity check.
- `reveal.mjs`: full-page capture that triggers the Divi reveals first.
- Chrome DevTools MCP for Lighthouse and accessibility audits, Playwright MCP for flows.
- `php bin/check.php` before any commit into New-Build.

---

## 4. Decisions for Andrew

1. **Match Divi's type scale (24px body, 68px titles) or keep New-Build's
   19px/46px?** Design.md chose 19px on purpose ("24px read as shouted").
   "Look like the old site" says 24. A middle option: 20px body with the larger
   display titles, which gets most of the Divi drama back.
2. **Dark teal footer (Divi) or light footer (New-Build today)?**
3. **Allow the site's first JavaScript** for instant adopt search, as a
   progressive enhancement over working static pages? Yes or no.
4. **Adopted dogs keep a page?** Still unresolved, still blocks the 4,594-URL
   redirect plan. The adopt page's "Adopted Dogs 64" tab depends on it too.
5. **Where do we build?** On a `design/` branch in New-Build and PR to Ronnie,
   as with the chrome work. The alternative is to prototype in this repo first
   and port it. Branching in New-Build is recommended: one codebase, no second
   copy to keep in sync.

## 5. One setup step before building

The local New-Build database has 9 invented dogs with no photos, so every
dog-facing component will look empty in comparison. `db/migration-source/pets.json`
is a real snapshot from new.pomdr.org and `build/migrate/import-pets.php` reads
it. Running it against the **local** database only (`pomdr` on
127.0.0.1:10004) would give us real names, bios and photo URLs to design
against. It touches nothing shared, but it replaces the seed data, so confirm first.
