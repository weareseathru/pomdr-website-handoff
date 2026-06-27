# Prototype to Divi: Porting Checklist (the continuous lane)

Purpose: stop the prototype-to-production gap from compounding. Port approved
global patterns into the Divi child theme in small, safe, continuous steps,
**without breaking the staff-editable menu**. Pairs with
`docs/DIVI-TRANSLATION-CONTRACT.md` and `docs/DIVI-BRIDGE-HOWTO.md`.

## Editability scope (clarified 2026-06)

Only the **daily-update content systems** must be staff-editable in wp-admin, no
code:

- Dog adoption posts (`pets` CPT, ACF)
- Events (`events` CPT)
- Staff photos and bios (`team` CPT)
- Courtesy listings (a `pets` status)
- **Promo banner** (announcements / fundraisers) via an ACF Options page

The **base chrome is developer-managed and does NOT need to be staff-editable**:
nav, action bar, tagline, footer, layout. This simplifies the header port: the
nav can be a straightforward theme header (a Divi Theme Builder Menu module, or
even a hardcoded header), without engineering it for low-level staff editing.

---

## Done

- [x] **Design tokens synced** into `assets/css/pomdr-design.css` `:root`:
      `--fs-base` 19px senior-readable, `--fs-lead`, `--fs-small`, orange retired
      to purple. The redesign components in the theme (dog cards) already read
      these, so they update automatically.
- [x] **All daily-content CPTs render the designed cards** from the editable
      backend, verified on the mirror (see `docs/HANDOFF-PORT-AUDIT.md`):
      dogs (8 shortcodes -> `.dog-card`), events (`.event-card`), team
      (5 shortcodes -> `.person-card`). Editing a record in wp-admin updates the
      card automatically. Card CSS is in the theme `pomdr-design.css`.
- [x] **Promo banner system** built in the theme: Promo Banner ACF Options page +
      `[promo_banner]` shortcode + CSS. Staff toggle it on, set label/message/
      button/dates; it renders below the hero (a Code module holding
      `[promo_banner]`) and shows nothing when off. Remaining setup: create the
      8 ACF fields (named in `functions.php`) on the options page in the ACF UI,
      then export to JSON per convention.

- [x] **Global chrome ported to the theme** (developer-managed, not Theme
      Builder): `inc/chrome.php` injects the action bar, two-row nav, centered
      tagline, clickable logo, and mobile drawer on `wp_body_open`;
      `assets/css/pomdr-chrome.css` hides Divi's Theme Builder header and offsets
      content; `assets/js/pomdr-nav.js` runs the drawer. Verified site-wide.
- [x] **Shared CSS synced into the theme.** The prototype `tokens.css` and the
      full `pomdr.css` are now theme assets, enqueued site-wide (tokens to
      pomdr-design to pomdr-shared to chrome to a11y). Every custom template gets
      the exact prototype components (page-header, sections, buttons, step rows,
      cta strips, cards).
- [x] **Homepage built** as `front-page.php`: the prototype hero (rotating) plus
      all sections, the `[pet_home]` dog row (editable), the paw-trail + GSAP
      reveal animations. Hero is full-bleed under the floating chrome. Verified.
- [x] **All static sub-pages ported** as `page-{slug}.php` templates (24 pages:
      foster-needs, volunteer, helping-paw, donate, about, surrender, process,
      recources, why, culture, bauer-center, clinic, benefit-shop,
      perpetual-care-program, privacy, terms, jobs, mailing-list, media, videos,
      testimonials, thanks, adoption-questionnaire, volunteer-application). Each
      is `get_header()` + ported `<main>` + `get_footer()`, links/images rewritten
      to WP paths/theme assets. **about / clinic / benefit-shop** render staff
      from the editable Team CPT via `[team_board]`, `[team_office]`,
      `[advisory_council]`, `[clinic_staff]`, `[benefit_shop_staff]`. Lint clean,
      render-verified; about + helping-paw + foster spot-checked visually.

### Open follow-ups from the sub-page port (need Andrew's call)
- **culture** lives at the nested slug `/about/culture/` (child Page of About);
  `/culture/` 301s there. Template renders correctly. Move to top-level if wanted.
- **clinic / benefit-shop**: the prototype had no staff grid, so the agents
  **added** an "Our team" section with the staff shortcode (so staff stay
  editable). Confirm we want that section on those pages.
- **adoption-questionnaire**: the prototype was a React/Babel mock; ported the LGL
  form placeholder and dropped the React scaffold. Needs the real LGL form embed.
- **contact**: no WP "contact" page exists; in-page contact links now point to
  `mailto:info@pomdr.org`.
- Dog-list pages (adopt, adopted, hospice, courtesy-listings, events) already
  render cards from the CPT; their page *headers* can get the same template
  treatment next.

## Next, in order (each is a small, verifiable step)

### 1. Divi "skin" CSS layer (visible, low-risk, no Theme Builder needed)
Apply the redesign system to Divi's existing elements via CSS only, so the live
site adopts the new look while the menu stays Divi-native.
- [ ] Body and headings: map Divi's type to the token scale (senior-readable
      body, reduced narrative headings, eyebrow treatment).
- [ ] Buttons: skin `.et_pb_button` to the fuller button system (size, fill,
      radius, focus ring).
- [ ] Palette: ensure teal/purple tokens win over Divi defaults; confirm no
      orange remains.
- [ ] Verify on the Local WP site with Playwright (`--ignore-https-errors`),
      run axe-core and a Lighthouse pass.

### 2. Global chrome in Theme Builder (keeps the menu editable)
Build the header once as a **Divi Theme Builder global header**:
- [ ] **Action bar + tagline** as a single **Code module** at the top of the
      header (paste the prototype markup for `.action-bar` and `.nav-tagline`,
      paths pointed at theme assets). This is fixed brand content, safe to hardcode.
- [ ] **Navigation** as a Divi **Menu module** (not hardcoded), so staff edit it
      in Appearance to Menus. Style it to the two-row look via the chrome CSS.
- [ ] **Mobile menu**: use Divi's menu module mobile behavior or the ported
      mobile-drawer CSS; confirm tap targets and the open/close animation.
- [ ] Assign the template to All Pages; verify header height does not cover
      content (set `--nav-h` equivalent spacing).

### 3. Page-by-page content (per DIVI-BRIDGE-HOWTO)
For each approved prototype page, port `<main>` into Divi Code module(s) per
section, swap static lists for the matching shortcode, and reuse the theme CSS.
Order: Adopt, Foster, Donate, Helping Paw, Volunteer, About.

### 4. Standing rule (so debt never balloons again)
When a global pattern is approved in the prototype, port its **CSS + tokens**
into the theme in the same week. Markup that is fixed brand chrome ports as Code
modules; anything menu-driven or staff-edited stays a Divi module.

---

## What stays in the prototype (by design)
The static prototype remains the **design lab**. New ideas are explored there
first in fast HTML, then the approved, stable patterns flow through this
checklist into Divi. The goal is a thin, continuous lane, not a big-bang port.

_Last updated: 2026-06-26. Owner: Andrew Z._
