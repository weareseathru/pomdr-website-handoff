# MIGRATION-MATRIX.md

Page-by-page migration map for **new.pomdr.org**, covering the homepage and
every link in the header and footer navigation (22 pages).

Generated 2026-06-12 by direct HTTP crawl (see Method).

## Method and caveats

- The Playwright MCP is not available in this environment, so this is a
  **server-side HTML crawl** (curl + parsing), not a rendered-browser crawl.
  Divi renders its builder markup server-side, so page structure, headings,
  Divi detection, server-rendered iframes, and `/pets/{id}/` links are captured
  faithfully.
- **Two things a static crawl cannot see**, flagged in the table:
  1. The dog-listing grids on `/adopt/`, `/courtesy-listings/`, `/adopted/`, and
     `/foster-needs/` load pets via **AJAX**. Counts come from the WordPress REST
     API (`/wp-json/wp/v2/pets`, 87 pets), not the HTML.
  2. Most application forms are **LGL embeds injected by JavaScript**, so they do
     not appear in raw HTML. Only server-rendered embeds were detected
     (volunteer-application LGL iframe, videos YouTube embeds). Rows marked
     "(JS-injected)" need a rendered pass to confirm the exact form.
- **Every page is Divi-builder-generated** (`et_pb_pagebuilder_layout` on all 22).
  The "Divi" column counts builder modules in the page body as a complexity signal.

## Heading-outline problems (accessibility)

Divi modules default headings to H1/H2, producing invalid outlines:

- **No H1 in body:** `/events/`, `/volunteer/`, `/volunteer-application/`
- **Multiple H1s:** `/donate/` (18), `/videos/` (18), `/recources/` (9),
  `/helping-paw/` (6), `/about/` (6), `/perpetual-care-program/` (3),
  `/benefit-shop/` (3), `/foster-needs/` (2), `/jobs/` (2)
- **Skipped levels:** `/benefit-shop/` (h1 to h3), `/jobs/` (h2 to h5)
- **Clean (single H1):** home, adopt, courtesy-listings, adopted, process,
  surrender, media, testimonials, terms, privacy

## Page matrix

| # | URL | Live title | Divi | Heading | Forms and destination | Pet-ID links | Repo pattern | Disposition |
|---|-----|-----------|:----:|---------|-----------------------|--------------|--------------|-------------|
| 1 | `/` | POMDR · Helping Senior Dogs & Senior People Since 2009 | 34 | 1 H1 OK | Newsletter + featured dogs (LGL/JS) | 7 uniq | `index.html` | Existing pattern |
| 2 | `/adopt/` | Adoptable Dogs | 10 | 1 H1 OK | LGL adoption questionnaire (JS-injected) | 86 uniq | `adopt.html` | Existing pattern + AJAX pet-grid wiring |
| 3 | `/courtesy-listings/` | Courtesy Listings | 7 | 1 H1 OK | none | AJAX (REST: see note) | `courtesy-listings.html` | Existing pattern + AJAX pet-grid wiring |
| 4 | `/adopted/` | Adopted Pets | 7 | 1 H1 OK | none | AJAX (REST: see note) | `adopted.html` | Existing pattern + AJAX pet-grid wiring |
| 5 | `/foster-needs/` | Foster Needs | 12 | 2x H1 | LGL foster app (JS-injected) | AJAX (REST: see note) | `foster.html` | Existing pattern + pet-grid; FIX 2x H1 |
| 6 | `/process/` | Our Adoption Process | 5 | 1 H1 OK | none | 0 | `process.html` | Existing pattern |
| 7 | `/helping-paw/` | Helping Paw Program | 27 | 6x H1 | LGL Helping Paw app (JS-injected) | 0 | `helping-paw.html` | Existing pattern; FIX 6x H1 |
| 8 | `/surrender/` | Guardian Surrender Program | 7 | 1 H1 OK | LGL surrender app (JS-injected) | 0 | `surrender.html` | Existing pattern |
| 9 | `/perpetual-care-program/` | Perpetual Care Program | 19 | 3x H1 | LGL (JS-injected) | 3 uniq | `perpetual-care-program.html` | Existing pattern; FIX 3x H1 |
| 10 | `/volunteer/` | Volunteer | 44 | NO H1 | LGL (JS-injected) | 0 | `volunteer.html` | Existing pattern; ADD H1 |
| 11 | `/volunteer-application/` | Volunteer Application | 4 | NO H1 | LGL form HsUdXN (server iframe) | 0 | `volunteer-application.html` | Existing pattern; ADD H1 |
| 12 | `/events/` | Event Calendar | 4 | NO H1 | none | 0 | `events.html` | Existing pattern (stub); ADD H1 |
| 13 | `/donate/` | Ways To Donate | 112 | 18x H1 | LGL form 62FAoG7Obtf81TYETJMN3Q (JS-injected) | 0 | `donate.html` | Existing pattern; FIX 18x H1 |
| 14 | `/about/` | About Us | 31 | 6x H1 | none | 3 uniq | `about.html` | Existing pattern; FIX 6x H1 |
| 15 | `/benefit-shop/` | Benefit Shop | 17 | 3x H1 | Divi search form | 0 | `benefit-shop.html` | Existing pattern; FIX 3x H1 + skip |
| 16 | `/jobs/` | Jobs | 23 | 2x H1 | Divi search form | 0 | `jobs.html` | Plain content; FIX 2x H1 + skip |
| 17 | `/videos/` | Videos | 76 | 18x H1 | 15 YouTube embeds | 0 | `videos.html` | Existing pattern; FIX 18x H1 |
| 18 | `/media/` | Media | 12 | 1 H1 OK | none | 0 | `media.html` | Plain content, migrate as-is |
| 19 | `/testimonials/` | Testimonials | 9 | 1 H1 OK | none | 0 | `testimonials.html` | Existing pattern / plain content |
| 20 | `/recources/` | Resources | 34 | 9x H1 | none | 0 | `resources.html` | Existing pattern; 301 slug; FIX 9x H1 |
| 21 | `/terms/` | Terms of Service | 5 | 1 H1 OK | none | 0 | `terms.html` | Plain content, migrate as-is |
| 22 | `/privacy/` | Privacy Policy | 5 | 1 H1 OK | none | 0 | `privacy.html` | Plain content, migrate as-is |

## Mapping summary

- **All 22 live pages already have a corresponding redesign pattern** in
  `pomdr-website/project/` (the static prototype). None are missing a home.
- **Patterns that need building/wiring (not just content):** the **pet-grid** used
  by `/adopt/`, `/courtesy-listings/`, `/adopted/`, `/foster-needs/`. The prototype
  has the card visual; the live AJAX query and status filtering must be wired in the
  Divi child theme against the `pets` CPT.
- **Individual pet profile** (`/pets/{id}/`) maps to the existing
  `divi-child-integration/single-pets.php` template (plus the prototype
  `dog/{slug}.html` visual).
- **Plain content, migrate as-is:** `/media/`, `/jobs/`, `/terms/`, `/privacy/`,
  and largely `/testimonials/`.
- **LGL forms** (adoption questionnaire, foster, surrender, Helping Paw, volunteer,
  donate) are retained embeds. Confirmed GUIDs: volunteer-application `HsUdXN`,
  donate `62FAoG7Obtf81TYETJMN3Q` (STACK.md). Confirm the rest in a rendered pass.
