# Handoff Port Audit: Prototype to Divi (newpomdr-local)

A page-by-page, feature-by-feature audit of the static prototype
(`pomdr-website/project/`) mapped to the WordPress/Divi mirror
(newpomdr-local.local), with the editability model the project follows.

Principle (per the owner, 2026-06): **only daily-update content is staff-editable
in wp-admin** (dogs, events, staff, courtesy listings, promo banner). The base
chrome (nav, action bar, tagline, footer, layout) is developer-managed. Dynamic
content renders through theme **shortcodes** that read the existing **CPTs/ACF**,
so editing a record in wp-admin updates the designed card automatically.

---

## 1. Daily-content systems: CPT to designed card (DONE, verified on the mirror)

| Content | Backend (staff edits) | Shortcode(s) | Renders as | Status |
|---|---|---|---|---|
| **Dogs / adoption posts** | `pets` CPT + ACF (status, sex, looks_like, age, weight, description, gallery, featured image) | `pet_home`, `adopt_a_pet`, `adopt_a_pet_plp`, `adopt_a_pet_fullwidth`, `adopted_pets`, `hospice_care`, `courtesy_listings`, `random_pet` | `.dog-card` (badge, ~age, Senior tag, View profile) via `pom_render_dog_card()` | **Done.** 86 cards verified on `/adopt/` |
| **Events** | `events` CPT + ACF (event_type, start, end, details, image) | `events` | `.event-card` (type eyebrow, title, formatted date range, details) | **Done.** Verified on `/events/` |
| **Staff / team** | `team` CPT + ACF (title=role, group, sort, bio, featured image) | `team_board`, `team_office`, `advisory_council`, `clinic_staff`, `benefit_shop_staff` | `.person-card` (round photo or initials, name, role) via `pom_render_person_card()` / `pomdr_team_grid()` | **Done.** 28 cards verified on `/about/` |

Shared renderers live in `functions.php`: `pom_render_dog_card()`,
`pom_pet_badge()`, `pomdr_dogs_by_status()`, `pom_render_person_card()`,
`pomdr_team_grid()`, `pomdr_event_date()`. Card CSS lives in
`assets/css/pomdr-design.css`.

Remaining in this bucket: `adopted_pets_recent` (a date-window homepage widget,
still old markup), the **promo banner** (system built; fields to be created in
ACF UI), and **media/videos** (see section 3).

---

## 2. Every prototype page mapped

| Prototype page | WP target | Data source | Port path |
|---|---|---|---|
| index.html (home) | front page | mix: `pet_home` (dogs), `events`, static sections | Dogs/events shortcodes done; static sections = Divi Code modules; promo banner = `[promo_banner]` |
| adopt.html | /adopt/ | `pets` CPT | **Done** (cards live) + header/toolbar = Divi/Code module |
| adopted.html | /adopted/ | `pets` (Adopted) | Shortcode done; place `[adopted_pets]` on page |
| courtesy-listings.html | /courtesy-listings/ | `pets` (Courtesy Listing) | Shortcode done; place `[courtesy_listings]` |
| hospice.html | /hospice/ | `pets` (Hospice) | Shortcode done; place `[hospice_care]` |
| events.html | /events/ | `events` CPT | **Done** (cards live) |
| about.html | /about/ | `team` CPT + static mission | **Team done**; mission/locations = Code modules |
| testimonials.html | /testimonials/ | testimonials (CPT or static) | TBD: confirm source; build card |
| media.html | /media/ | press links (likely static) | Static Code module |
| videos.html | /videos/ | YouTube (ACF `youtube_video` on pets, or static) | Confirm source; embed module |
| helping-paw, volunteer, donate, foster, surrender, contact, process, resources, why, culture, jobs, bauer-center, clinic, benefit-shop, perpetual-care-program, privacy, terms, thank-you, mailing-list, adoption-form, volunteer-application | matching pages | **static content** + LGL forms | Port `<main>` into Divi Code module(s) per section; reuse theme CSS; swap any dog/team list for its shortcode |

---

## 3. Open items (developer-managed, sequenced)

1. **Global chrome to Divi** (nav, action bar, tagline, footer). Not staff-edited,
   so a straightforward theme header (Divi Theme Builder global header, or a
   child-theme header). Currently the mirror still shows the old Divi header
   overlapping page titles. See `docs/PORTING-CHECKLIST.md` step 2.
2. **Theme CSS sync.** `assets/css/pomdr-design.css` is an older snapshot of the
   prototype `pomdr.css` (e.g., eyebrow size, nav). The card components are
   current; the global type/nav/button layer needs syncing (checklist step 1).
3. **Static page assembly.** Port each static page's `<main>` into Divi Code
   modules per `docs/DIVI-BRIDGE-HOWTO.md`. Order: Adopt header, Home, Helping
   Paw, Donate, Volunteer, About.
4. **Promo banner fields.** Create the 8 ACF fields on the Promo Banner options
   page (named in `functions.php`), then place `[promo_banner]` below the hero.
5. **Media / videos / testimonials.** Confirm whether each is a CPT or static,
   then build the matching card or embed.
6. **`adopted_pets_recent`** still on old markup; rewire to `pom_render_dog_card`
   with its date window when needed.

---

## 4. How a staff member uses this (no code)

- **Add or edit a dog:** wp-admin to Pets to Add/Edit, fill ACF fields, set the
  featured image, Update. The card on `/adopt/` (and home sample) updates itself.
- **Add an event:** wp-admin to Events, set type/dates/details/image. It appears
  on `/events/`.
- **Add or edit staff:** wp-admin to Team, set role (title), group, photo, bio.
  It appears on `/about/` in the right section.
- **Run a promotion/fundraiser:** wp-admin to Promo Banner, toggle on, write the
  message and button, optional dates. The banner shows below the hero.

_Last updated: 2026-06-27. Owner: Andrew Z._
