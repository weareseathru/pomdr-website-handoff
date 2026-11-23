# Divi Translation & Editability Contract

How the localhost prototype (`pomdr-website/project/`) becomes the live WordPress
site without losing the ability for any staff member to edit dogs, events, and
content in wp-admin with no code. Read alongside `docs/DIVI-BRIDGE-HOWTO.md`.

Principle: **the redesign is a presentation layer over existing data.** Divi
stays the page-editing surface; the `pets`/`events` CPTs stay the content-editing
surface. Nothing in the redesign changes how staff add or edit a dog.

---

## 1. What maps to what

| Prototype piece | Divi target | Who edits it, and how |
|---|---|---|
| **Design tokens** (`tokens.css`: colors, type scale, `--fs-base`, radius, shadow) | `divi-child-integration/assets/css/pomdr-design.css` `:root` (site-wide) | Developer. One place; cascades everywhere. |
| **Header: action bar + two-row nav + logo** | Divi **Theme Builder global header** (or child-theme header partial), styled by `pomdr-design.css` | Staff edit menu items via **Appearance → Menus**. Action-bar links are a fixed global element. |
| **Buttons, cards, badges, type** | Shared classes already in `pomdr-design.css` | Developer (rare). |
| **Static design sections** (hero, mission, By the Numbers, videos) | Divi **Code module** per section **or** native Divi modules (see §3) | Staff edit in the **Divi Builder** (visual). Code modules = edit text in an HTML box; native modules = full visual editing. |
| **Adoptable dogs / homepage dog sample** | `[adopt_a_pet]` / `[pet_home]` shortcodes → `pom_render_dog_card()` | **Staff edit each dog in wp-admin → Pets** (ACF fields). Page never touched. |
| **Events** | `[events]` shortcode → events CPT | **Staff edit wp-admin → Events** (ACF fields). |
| **Team / testimonials** | `[team_board]` etc. + (testimonials: CPT or repeater) | wp-admin. |

---

## 2. The editability guarantee (dogs, events)

This is already built and verified on the WP local site:

- **Dogs** live in the `pets` CPT with ACF fields (name, age, sex, weight,
  `looks_like`, `status`, bio, photos, foster dates, sponsor). A staff member
  opens **wp-admin → Pets → [dog] → Edit**, changes fields, hits Update. The new
  card design re-renders automatically via `pom_render_dog_card()`. No HTML, no
  Divi, no code.
- **Status drives the badge** (Adoptable, Foster Needed, Adoption Pending, etc.)
  through the ACF checkbox. Change the checkbox, the badge changes.
- **Events** work the same way through the `events` CPT.
- Any role with edit rights (Editor, Admin) can do this. The four named staff
  (Carie, Monica, Allison, Andrew) need no developer.
- The redesign does **not** move dog/event data into Divi or into page content,
  so it cannot break this editing path.

Optional (later): the WordPress MCP tools (`pomdr-list-dogs`, `pomdr-update-dog`,
etc.) let an assistant manage the same data programmatically. That is additive;
staff editing in wp-admin is the primary path.

---

## 3. Module strategy (the one real choice) — recommended: HYBRID

For each static section, Divi offers two ways to hold the markup:

- **Code module** — paste the prototype section HTML verbatim. Pixel-faithful to
  the design. Downside: to change wording, a user edits raw HTML.
- **Native Divi modules** (Text, Blurb, Number Counter, Button, Blog) styled by
  our theme CSS. Downside: the design is approximated to what Divi modules do.
  Upside: any-level staff edit it visually.

**Recommendation — hybrid, decided per section by who edits it and how often:**

| Section | Build as | Why |
|---|---|---|
| Hero, pillars, mission copy | Native Divi (Text/Button) + theme CSS | Staff reword these; keep them visually editable |
| By the Numbers (3 stats) | Native Divi Number Counters **or** ACF options | Numbers change; should not require HTML edits |
| Adoptable dogs, events | Shortcode (already dynamic) | Driven by CPT; zero page editing |
| Video embeds, decorative/complex layout | Code module | Rare edits; faithful design matters more |

This keeps frequently-edited text in visual modules and reserves Code modules for
stable, design-heavy pieces. Split the homepage into **one Code/section module per
section** (not one giant block) so staff can reorder or hide a section in the
Builder without touching the others.

---

## 4. How Phase A and Phase B translate specifically

- **Phase A (global system)** is almost entirely tokens + shared component CSS,
  which already live (or will be mirrored) in `pomdr-design.css`. Porting = sync
  the token/button/card changes into the theme CSS, and build the action bar +
  two-row nav once as a Divi global header. Done once, applies site-wide.
- **Phase B (homepage sections)**: direct titles + smaller narratives are plain
  text → native Divi Text modules. "POMDR By the Numbers" 3-across → three Number
  Counter modules (values editable, or ACF options). Adoptable dogs → the
  existing shortcode. "Hear from our clients" → testimonial source (CPT/repeater).

## 5. Build rule going forward (so nothing has to be re-done)

While building the prototype, each section is built to be portable:
1. Semantic HTML, styled only by tokens/shared classes (no one-off magic values).
2. Any list of real content (dogs, events, team, testimonials) is a **dynamic
   slot** that becomes a shortcode, never hand-keyed data.
3. Frequently-edited copy is isolated so it maps to a native Divi module.
4. No data lives in the design layer; it lives in a CPT/ACF.

_Last updated: 2026-06-24. Owner: Andrew Z._
