# INVENTORY.md

The Inviolable Inventory. URLs, pages, integrations, and editor workflows
that must survive the redesign. Authored 2026-05-09.

Source evidence:

- Two DOM captures of the staging site `https://new.pomdr.org/` taken
  2026-04-17 (`pomdr-website/project/uploads/web-capture-*.json`).
- Spec text in HANDOFF.md (the 19-item nav list, 3 dog categories, footer
  identity block).
- Voice rules in the global `CLAUDE.md` at the Antigravity working dir.
- The handoff design package (9 page-level HTML files plus 12 dog profiles).

This file is normative. If a row needs to change, change it here in writing
before code touches it.

---

## 1. Site context

- Production hostname: `www.pomdr.org`
- Staging hostname captured: `new.pomdr.org` (Divi block-builder WordPress)
- Theme observed in DOM captures: Divi (`et_pb_*` class soup)
- Dog detail URL pattern observed: `/pets/{id}/` (e.g. `/pets/3037/`,
  `/pets/2865/`, `/pets/2860/`)

**Open question for Andrew:** HANDOFF.md calls for redirecting
`/dog.php?id=N` for IDs 3000 to 5000. The captured nav uses `/pets/{id}/`,
not `/dog.php?id=N`. Three possibilities: (a) `dog.php` is from a pre-Divi
generation of the site that already 301s to `/pets/{id}/`, (b) it's a
parallel route that still resolves, (c) the spec is stale. STACK.md flags
this for confirmation. The block theme must support whichever pattern is
authoritative on production at cutover.

---

## 2. Top-level navigation

HANDOFF.md commits us to **19 top-level items**. The captured staging nav
uses a 6-section structure with sub-menus, totaling 27 distinct links. The
table below maps the spec's 19 against what the captures show.

| #  | HANDOFF nav item       | Captured live URL              | Captured nav location         | Status     | Notes |
|----|------------------------|--------------------------------|-------------------------------|------------|-------|
| 1  | Home                   | `/`                            | logo                          | confirmed  | |
| 2  | About Us               | `/about/`                      | top-level: About Us           | confirmed  | |
| 3  | Adoptable Dogs         | `/adopt/`                      | top-level: Adopt              | confirmed  | |
| 4  | Ways to Give           | `/donate/`                     | sub of About Us, also "Donate" CTA | confirmed | Two paths to same URL |
| 5  | Volunteer              | `/volunteer/`                  | top-level: Volunteer          | confirmed  | |
| 6  | Foster Needs           | `/foster-needs/`               | sub of Volunteer              | confirmed  | |
| 7  | Benefit Shop           | `/benefit-shop/`               | sub of About Us               | confirmed  | |
| 8  | What's Happening       | (not in capture)               | (missing)                     | gap        | Possibly retired or footer-only. Needs Andrew confirmation. |
| 9  | Adoption Events        | `/events/`                     | sub of Adopt                  | confirmed  | |
| 10 | Placing Your Dog       | `/surrender/`                  | top-level: Surrender, sub: "Placing your Dog" | confirmed | Spec name and URL slug differ |
| 11 | Lifetime Care          | `/perpetual-care-program/`     | sub of Surrender              | confirmed  | Spec calls it "Lifetime Care," live page is "Perpetual Care" |
| 12 | Helping Paw            | `/helping-paw/`                | top-level: Helping Paw        | confirmed  | |
| 13 | Resources              | `/recources/`                  | sub of Helping Paw            | confirmed  | URL slug is misspelled ("recources"). Preserve as 301 source, fix as 200 target. |
| 14 | In the Media           | `/media/`                      | sub of About Us               | confirmed  | |
| 15 | Tribute Donations      | (not in capture)               | (missing)                     | gap        | Likely a sub-page of `/donate/` or external LGL link |
| 16 | Thank You              | (not in capture)               | (missing)                     | gap        | Possibly a post-form thank-you page, not a nav item |
| 17 | Testimonials           | `/testimonials/`               | sub of About Us               | confirmed  | |
| 18 | Mailing List           | (not in capture)               | (missing)                     | gap        | Likely a footer signup, not a nav item; Mailchimp confirmation needed |
| 19 | Job Openings           | `/jobs/`                       | sub of About Us               | confirmed  | Live label is "Jobs" |

Additional captured nav items not in the HANDOFF 19:

| Captured item              | URL                          | Captured location              | Disposition |
|----------------------------|------------------------------|--------------------------------|-------------|
| Adopt (parent)             | `/adopt/`                    | top-level                      | Same target as "Adoptable Dogs"; the parent doubles as a child. |
| Courtesy Listings          | `/courtesy-listings/`        | sub of Adopt                   | One of the 3 dog categories (see section 3). Preserve. |
| Recently Adopted           | `/adopted/`                  | sub of Adopt                   | Adopted historical archive. Preserve. |
| Our Adoption Process       | `/process/`                  | sub of Adopt                   | Preserve. |
| Volunteer Application      | `/volunteer-application/`    | sub of Volunteer               | Preserve (LGL form likely). |
| Volunteer Opportunities    | `/volunteer/`                | sub of Volunteer               | Same target as parent. |
| Helping Paw Financial      | `/helping-paw/`              | sub of Helping Paw             | Anchor sub-section, not its own URL. |
| Helping Paw Walking/Foster | `/helping-paw/`              | sub of Helping Paw             | Anchor sub-section. |
| Perpetual Care             | `/perpetual-care-program/`   | sub of Surrender               | Same as spec item 11. |
| Our Team                   | `/about/`                    | sub of About Us                | Anchor on About page. |
| Videos                     | `/videos/`                   | sub of About Us                | Preserve. |

**Decisions needed from Andrew before Session 2:**

1. The four spec items not present in the captured nav (What's Happening,
   Tribute Donations, Thank You, Mailing List): keep, retire, or redefine
   as footer/page-anchor links?
2. "Lifetime Care" vs. "Perpetual Care": which is the canonical label?
3. Should "Videos" promote to top-level, stay as About sub, or merge
   into `/media/`?

---

## 3. Dog categories and listings

HANDOFF.md says **three dog-listing categories**: Adoptable, Courtesy
Listings, Hospice. Captured live site shows:

| Category           | URL                     | Status              | Notes |
|--------------------|-------------------------|---------------------|-------|
| Adoptable          | `/adopt/`               | confirmed           | Default listing. |
| Courtesy Listings  | `/courtesy-listings/`   | confirmed           | Dogs not in POMDR's care, listed as a courtesy. |
| Hospice            | (not in capture)        | gap                 | Spec requires it. Confirm with Andrew whether a `/hospice/` page exists or needs creating. |
| Recently Adopted   | `/adopted/`             | confirmed (separate) | Historical archive, separate from the 3 active categories. |

The block theme's dog Custom Post Type field `category` (controlled
vocabulary: `adoptable`, `courtesy`, `hospice`) drives which listing page
each dog appears on. The status field (`available`, `foster-needed`,
`foster-needed-dated`, `adoption-pending`, `recently-adopted`,
`hospice`, `courtesy-listing`) drives badges and filters within a listing.

---

## 4. Footer identity block

HANDOFF.md mandates the footer carry: phone, email, mailing address, three
physical locations (Bauer Center, Boand Vet Clinic, Benefit Shop), 501(c)(3)
status, Tax ID 27-1154816, hours, social links, Terms & Privacy.

Audit of the design's footer (`pomdr-website/project/pomdr-layout.js` lines
57 to 120):

| Required item         | In design? | Notes |
|-----------------------|------------|-------|
| Phone                 | yes        | Display "(831) 718-9122" and `tel:+18317189122` both correct in `pomdr-layout.js:169`. Fixed in Session 2 footer rewrite. |
| Email                 | yes        | `info@peaceofminddogrescue.org`. Matches global CLAUDE.md. |
| Mailing address       | yes (Bauer only) | "615 Forest Ave, Pacific Grove, CA 93950". |
| Bauer Center          | yes        | (same as mailing address) |
| Boand Vet Clinic      | no         | "1251 10th St, Monterey, CA" per global CLAUDE.md. Not in design footer. |
| Benefit Shop          | no         | "223 Grand Ave, Pacific Grove, CA" per global CLAUDE.md. Not in design footer. Listed in nav, not footer. |
| 501(c)(3) status      | yes        | "501(c)(3) nonprofit." |
| Tax ID                | yes        | Footer now reads `EIN 27-1154816` in `pomdr-layout.js:235`, matching the authoritative value in CLAUDE.md. Fixed in Session 2 footer rewrite. |
| Hours                 | no         | Not present. |
| Social links          | yes        | Facebook, Instagram, YouTube. URLs match `peaceofminddogrescue` handle. |
| Terms                 | no         | Design has "Privacy" and "Contact" only, no "Terms" link. |
| Privacy               | yes        | Links to `about.html`, which is wrong. Needs a real `/privacy/` page. |

The footer must be implemented as a single locked block pattern that pulls
from theme settings (or a small "Site Identity" CPT) so staff can edit
identity once and have it propagate everywhere.

---

## 5. Designed pages in handoff package

Nine page-level HTML files at `pomdr-website/project/`:

| File              | Likely target URL          | Notes |
|-------------------|----------------------------|-------|
| `index.html`      | `/`                        | Homepage, 1,701 lines, React+Babel. The richest design surface. |
| `adopt.html`      | `/adopt/`                  | Adoptable dogs grid + filter toolbar + adoption process. |
| `about.html`      | `/about/`                  | Our story, team. Anchor for `/about/#team`, `/about/#locations`. |
| `donate.html`     | `/donate/`                 | Ways to give. |
| `volunteer.html`  | `/volunteer/`              | Volunteer opportunities. |
| `helping-paw.html`| `/helping-paw/`            | Walking brigade, financial aid. |
| `surrender.html`  | `/surrender/`              | Placing your dog. |
| `foster.html`     | `/foster-needs/`           | Tiny (48 lines). Likely a stub. |
| `contact.html`    | `/contact/` (new)          | Tiny (58 lines). The live nav has no /contact/, so this is a new page. |

Twelve dog profile HTML files at `pomdr-website/project/dog/`:

`pebble.html`, `aragorn.html`, `bixby.html`, `breeze.html`, `clove.html`,
`honeybee.html`, `malcolm.html`, `mihla.html`, `nana.html`, `oscar.html`,
`sun-bear.html`, `watson.html`.

These map to the dog detail template, not to a fixed URL. In the new theme
each dog gets a `pomdr_dog` post with slug `{slug}` and listing URL
`/pets/{slug}/` (or whatever production decides).

---

## 6. Confirmed integrations

Evidence is in the DOM captures.

- **LGL Forms (Little Green Light)** is in use for form embeds. Specific
  evidence: `https://secure.lglforms.com/form_engine/s/utzjcNEZaqAcJk3QURlQmw`
  is loaded as an iframe with prefill via query parameter `field_21`
  (the dog name). Adoption inquiries flow through this. Donations likely
  do too.
- **Divi page builder** with custom ACF posts grid (`custom-acf-posts-grid-4`)
  drives the homepage adoptable list. Migration off Divi is part of the
  redesign scope.
- **WordPress** with custom post type for pets (URL slug `pets`, integer IDs
  in the 2800 to 3100 range observed).

These observations seed `STACK.md` rather than substituting for it.

---

## 7. Editor workflows to preserve

These are workflows staff perform regularly. The block theme must keep them
fast and unambiguous.

1. **Add a new dog.** Create a `pomdr_dog` post, fill required fields, set
   category and status, upload featured photo + gallery, publish. Review
   placeholder text enforces voice rules.
2. **Move a dog from `available` to `adoption-pending`.** Single status
   field flip. The card re-skins automatically.
3. **Mark a dog adopted.** Status flip to `recently-adopted`. The dog
   moves out of category listings and into the historical archive.
4. **Post a happy tail.** A separate CPT (`pomdr_happy_tail`) or a tag on
   the dog post. Decide in Session 2.
5. **Schedule an event.** Event CPT or block on `/events/`.
6. **Send a tribute donation acknowledgment.** This is downstream of LGL,
   not a WordPress workflow per se. STACK.md flags integration shape.
7. **Generate a Herald print ad.** Format is in VOICE.md. Manual export,
   not automated.

Each of these gets a roughly three-minute reference video embedded next to
the relevant pattern in the editor. Recording happens after the patterns
ship.

---

## 8. Out of scope for inventory

Items intentionally NOT preserved or migrated:

- Divi shortcodes, Divi-specific blocks, Divi page builder. Replaced by
  Gutenberg + custom blocks + locked patterns.
- The CSS variables and selectors carrying `et_pb_*` prefixes.
- Whatever the underlying Divi theme is. The new theme is the source of
  truth for layout.
- The misspelled URL slug `/recources/` is preserved as a 301 source only;
  the canonical URL becomes `/resources/`.
