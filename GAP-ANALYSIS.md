# GAP-ANALYSIS.md

A two-axis cross-reference: where the handoff design meets the HANDOFF.md
spec, and where it does not. Authored 2026-05-09. Each gap row carries a
proposed resolution so Session 2 can pick up without re-deriving intent.

Source evidence:

- HANDOFF.md `pomdr_dog` data model (17 fields plus override layer).
- INVENTORY.md preserved-URL table (19 spec nav items plus extras).
- Design package: nine page-level HTMLs at `pomdr-website/project/`,
  twelve dog profile HTMLs at `pomdr-website/project/dog/`, content
  constants in `pomdr-website/project/data.jsx`.

Status legend used throughout:

- **covered**: design has a surface for this; theme can reuse the visual
  contract directly.
- **partial**: design has a surface but it omits required fields, breaks
  voice rules, or misrepresents the field. Specific fix required.
- **missing**: no design surface exists. Pattern must be designed in
  Session 2 or later, before the theme can ship.
- **n/a**: field is editor-only or backend-only; surface is the WP admin,
  not the public site.

---

## 1. Data model coverage (`pomdr_dog`)

The HANDOFF data model defines every field the dog Custom Post Type carries.
This table walks each field against what the design surfaces on listing
cards and detail pages.

Listing surfaces audited: `index.html` (homepage rotating cards),
`adopt.html` (grid + filter toolbar). Detail surface audited: `dog/pebble.html`
as representative; spot-checks against `dog/aragorn.html`, `dog/sun-bear.html`,
`dog/watson.html` confirm the same skeleton.

| Field           | Listing card    | Detail page      | Status   | Resolution |
|-----------------|-----------------|------------------|----------|------------|
| `name`          | yes (`.dog-name`) | yes (`<h1>`)     | covered  | Map to post title. |
| `slug`          | yes (link href) | yes (URL path)   | covered  | WP post slug. Detail URL becomes `/pets/{slug}/`. |
| `legacy_id`     | no              | no               | missing  | Required for `/pets/{int}/` and `/dog.php?id=N` redirects. Add as ACF field. Render nowhere on the public site; used only by redirect rules. |
| `breed`         | yes (stats line)| yes (vitals line `~12 years · Female · 11 lb · Long-haired Dachshund`) | covered | Map directly. Stats line uses middle-dot separator already. |
| `sex`           | partial         | yes              | partial  | Listing card omits sex on some variants (homepage rotator shows age + city only). Add sex to the canonical card stats line for consistency, since adopters filter on it. |
| `age_years`     | yes             | yes              | partial  | Detail page shows `~12 years`; design's `data.jsx` shows bare `11 yrs` and a `Meet Pebble · 11 yrs ...` violation. Voice: numeric value with `~` only when approximate, abbrev `yrs` on cards, `years` on detail. Conflict: detail page says 12, `data.jsx` says 11. Source-of-truth resolves on data sync, not in design. |
| `weight_lb`     | no              | yes (vitals line)| partial  | Listing card does not show weight; detail shows `11 lb`. Add weight to card stats line behind a small-screen breakpoint (drop on `xs`). |
| `status`        | partial         | partial          | partial  | Cards show one badge slot (`badge.foster`, `badge.pending` exist in `pomdr.css` lines 894 to 920) but the design only renders two of seven status values. Detail page has no status surface at all. Resolution: extend badge vocabulary to all seven values (`available`, `foster_needed`, `foster_needed_dated`, `adoption_pending`, `recently_adopted`, `hospice`, `courtesy_listing`). Detail hero gains a status strip beneath the eyebrow. |
| `foster_dates` | no              | no               | missing  | Required when `status = foster_needed_dated`. Pattern: render as small italic line under the badge ("Needs foster May 14 to June 2"). Block pattern in Session 2. |
| `campaign_tag`  | no              | no               | missing  | Required for "Forever Starts Here" / LTD surfacing. Add a ribbon block (top-right corner of card; banner above detail hero). Visual style not yet designed. |
| `intake_date`   | no              | no               | missing  | Drives "long-term" calculation that toggles `campaign_tag`. Editor-only field, not directly rendered, but powers a derived field. |
| `category`      | partial         | no               | partial  | Listing pages exist (`/adopt/`, `/courtesy-listings/`) but the design has no `/hospice/` listing page (INVENTORY.md section 3 flags this). Each dog must carry one of `adoptable`, `courtesy`, `hospice`. |
| `featured_photo`| yes             | yes              | covered  | Map to post featured image. Cards use `.media-portrait`; detail uses 3:2 hero. |
| `gallery`       | no              | no               | missing  | Spec calls for a gallery; design's detail page renders one photo only. Pattern needed: gallery block (grid or carousel) with caption support and accessible focus order. |
| `story_short`   | yes             | n/a              | covered  | Card excerpt, 60 to 120 words per VOICE.md. |
| `story_long`    | partial         | partial          | partial  | Detail page renders a single bio paragraph (~5 sentences, ~110 words) inside `.page-lead`. Spec calls for 200 to 400 words. Resolution: replace the single paragraph with a multi-paragraph block; wrap in `.prose` constraint for line-length control. |
| `override_*`    | n/a             | n/a              | n/a      | Editor-only. Override values land in normal fields when set; no separate public surface. |

### Surfaces missing from the design entirely

These are spec-required but have zero design representation. Each becomes a
to-design item for Session 2 (visual) and Session 3 (block authoring):

1. **Gallery block.** Multi-photo display on the dog detail page. Suggested
   pattern: 3-up grid below the hero with click-to-lightbox, or a swipe
   carousel using `@wordpress/interactivity`.
2. **Status strip.** A horizontal rule under the dog's name on the detail
   page that surfaces status (`Available`, `Foster needed`, `Adoption
   pending`, etc.) with an icon, an optional date range, and (when
   relevant) a CTA scoped to that status.
3. **Campaign ribbon.** "Forever Starts Here" / LTD treatment. Both card
   and detail variants. Visual TBD.
4. **Foster dates surface.** Small italic line under the status strip when
   `status = foster_needed_dated`.
5. **Hospice listing page.** Sister page to `/adopt/` and
   `/courtesy-listings/`. Same grid template, different filter scope.
6. **Adoption inquiry CTA wired to LGL.** Detail page currently links to a
   PHP form (`https://www.peaceofminddogrescue.org/POMDRAdoptionApplication.php`),
   not the LGL form id confirmed in STACK.md
   (`utzjcNEZaqAcJk3QURlQmw`). The CTA must build the LGL URL with
   `field_21` prefilled to the dog's name.

---

## 2. Page coverage (preserved URLs vs. designed pages)

Walks every preserved URL from INVENTORY.md against the nine page-level
HTMLs in the handoff package. Marks where each URL has design support, where
new design work is needed, and where copy needs to be written for the first
time.

### 2.1 Top-level URLs

| Preserved URL                | Design source                | Status   | Resolution |
|------------------------------|------------------------------|----------|------------|
| `/`                          | `index.html`                 | covered  | Translate React+Babel homepage to block patterns. Hero rotator becomes `@wordpress/interactivity` block. |
| `/about/`                    | `about.html`                 | covered  | Translate. `/about/#team` and `/about/#locations` are anchor targets within. |
| `/adopt/`                    | `adopt.html`                 | covered  | Filter toolbar is React; reimplement in interactivity API. |
| `/courtesy-listings/`        | (none directly)              | partial  | Reuse `adopt.html` template with `category=courtesy` query. No bespoke design needed. |
| `/adopted/`                  | (none)                       | missing  | Historical archive page. Reuse adopt grid template with `status=recently_adopted`. Confirm in Session 2 whether design changes are wanted (greyscale photos, year-grouped headings, etc.). |
| `/events/`                   | (none directly)              | missing  | Spec calls for an events listing. No designed page. New pattern in Session 2. |
| `/process/`                  | (covered inside `adopt.html`)| partial  | The design folds the adoption process into `adopt.html`. The live site has it on its own URL. Resolution: lift the "process" section into a standalone page template that also renders inline on `/adopt/`. |
| `/volunteer/`                | `volunteer.html`             | covered  | Translate. |
| `/volunteer-application/`    | (none)                       | missing  | LGL form embed page. Stub this in Session 2; the form id needs Andrew's confirmation per STACK.md. |
| `/foster-needs/`             | `foster.html` (48 lines)     | partial  | Design file is a stub. Treat as new page; only the shell layout is reusable. |
| `/helping-paw/`              | `helping-paw.html`           | covered  | Translate. The two captured anchors (Financial, Walking/Foster) live on this page. |
| `/recources/` → `/resources/`| (none)                       | missing  | The misspelled URL becomes a 301 source. The canonical `/resources/` page has no design. Author content list and a simple resource-card block in Session 2. |
| `/surrender/`                | `surrender.html`             | covered  | Translate. The page should be titled "Placing Your Dog" per HANDOFF.md item 10. |
| `/perpetual-care-program/`   | (none)                       | missing  | Spec calls it "Lifetime Care," live URL says "perpetual-care-program." Andrew picks the canonical name; design is new. |
| `/donate/`                   | `donate.html`                | partial  | The design renders a donate page but does not host the donation form itself. HANDOFF.md says "no third-party iframe on the donation page." STACK.md flags this for confirmation. Resolution: design the in-page donation form in Session 2 once the processor is confirmed. |
| `/benefit-shop/`             | (none)                       | missing  | New page. Address per global CLAUDE.md is 223 Grand Ave, Pacific Grove. Author content in Session 2. |
| `/jobs/`                     | (none)                       | missing  | New page. Likely a simple list of openings; volume is low. |
| `/videos/`                   | (none)                       | missing  | Possibly merges into `/media/`. Decision needed (INVENTORY.md section 2). |
| `/media/`                    | (none)                       | missing  | "In the Media" page. No designed equivalent. |
| `/testimonials/`             | (partial inside `index.html`)| partial  | Homepage has a "Happy Tails" carousel; standalone `/testimonials/` page has no design. Reuse the carousel block on a paginated archive template. |
| `/contact/`                  | `contact.html` (58 lines)    | partial  | Live site has no `/contact/` URL; this is a new page in the redesign. The 58-line design file is enough to get started. Confirm the contact form's backend (LGL? native?) per STACK.md. |
| Footer links: `/privacy/`, `/terms/` | (none)               | missing  | The design's "Privacy" link points at `about.html` (broken). `/terms/` is not represented at all. Both need real pages. |

### 2.2 Dog detail URLs

The handoff package ships twelve designed dog profile pages at
`pomdr-website/project/dog/`:

`pebble.html`, `aragorn.html`, `bixby.html`, `breeze.html`, `clove.html`,
`honeybee.html`, `malcolm.html`, `mihla.html`, `nana.html`, `oscar.html`,
`sun-bear.html`, `watson.html`.

These are not URL-bound. They demonstrate one detail-page template, parameterized
by content. Coverage of the template against `pomdr_dog`:

| Detail surface        | Designed?                | Status   | Resolution |
|-----------------------|--------------------------|----------|------------|
| Hero (name + portrait + vitals + lead) | yes (12 of 12)| covered  | Translate to a block pattern. Photo aspect ratio fixed at 3:2. |
| Vitals line           | yes (`~12 years · Female · 11 lb · Long-haired Dachshund`) | covered | Middle-dot separator. Drop placeholder values cleanly when missing. |
| Apply CTA             | partial                  | partial  | Designed as a button to the legacy PHP application. Theme wires it to the LGL form id with `field_21` prefill. |
| Foster CTA            | yes                      | covered  | Designed as a secondary outline button. |
| Status strip          | no                       | missing  | See Section 1 surface #2. |
| Foster dates surface  | no                       | missing  | See Section 1 surface #4. |
| Campaign ribbon       | no                       | missing  | See Section 1 surface #3. |
| Story long            | partial                  | partial  | Single paragraph. Replace with multi-paragraph block; preserve voice rules. |
| Gallery               | no                       | missing  | See Section 1 surface #1. |
| Related dogs / "Meet more senior dogs" CTA strip | yes  | covered  | The strip uses "Meet more" as a category invitation, not "Meet [Name]." Acceptable per VOICE.md. |
| "Back to all dogs" link | yes                    | covered  | Renders top-left of detail hero. |

### 2.3 Pages without redirect concerns

A small number of URLs in `redirects.csv` are direct-passthrough rows
(`/wp-admin/*` etc.) that do not need design support. They are out of scope
for this analysis.

---

## 3. Voice rule violations to fix in seed content

This is a separate axis from data and pages. VOICE.md section 5 lists each
violation in the design's React data file with its proposed fix. The block
theme's seed content imports from that table, not from `data.jsx` raw.

Quick recap (full table in VOICE.md):

- `data.jsx` lines 54, 61, 69: "Meet [Name]" hero tags.
- `data.jsx` lines 59, 67, 94, 102, 120: em dashes in body copy.
- `data.jsx` line 86: "furever home." Brand guide reconciliation pending.
- `dog/pebble.html` line 6: `<title>Pebble — Peace of Mind Dog Rescue</title>`
  contains an em dash. Fix to a comma or a pipe in the theme template.
- `dog/pebble.html` line 27: external CTA URL points at the legacy PHP
  application, bypassing LGL prefill. Theme replaces with LGL.

---

## 4. Editor-experience gaps

The design demonstrates the public-facing surface only. The block theme has
to layer editor experience on top:

1. **Locked pattern shells.** Dog detail, listing card, footer identity,
   header nav: each locked to its own structure. Editors edit text and
   images, not block structure.
2. **Placeholder text quoting voice rules.** Per VOICE.md, every textarea
   placeholder in dog content patterns embeds the voice rule snippet.
3. **Reference videos.** INVENTORY.md section 7 calls for a three-minute
   video alongside each editor workflow. Recording deferred to post-theme
   ship.
4. **Site Identity surface.** Footer pulls from theme settings (or a
   single-instance "Site Identity" CPT) so the EIN, addresses, hours,
   social links, and legal links update in one place. The current design
   hardcodes them.
5. **Override layer for `pomdr_dog`.** Editor UX TBD (separate metabox?
   inline ACF group?). Design has no surface for this; it's editor-only.

---

## 5. Open questions blocking Session 2

A condensed punchlist. Each row needs Andrew's call before the block theme
scaffold runs.

1. Hospice listing page: confirm URL slug (`/hospice/` vs. another) and
   whether the listing is publicly browsable.
2. Lifetime Care vs. Perpetual Care canonical name.
3. Videos: stay separate at `/videos/` or merge into `/media/`.
4. The four spec-only nav items (What's Happening, Tribute Donations, Thank
   You, Mailing List): keep, retire, or convert to footer/anchor links.
5. STACK.md decisions: source-of-truth dog records, donation processor,
   form plugin, mailing list integration.
6. Whether `/dog.php?id=N` still resolves on production today.
7. Privacy and Terms: do existing legal pages exist somewhere off-site, or
   do we author new ones?
8. Adoption application: keep the LGL form, switch to native WP forms with
   LGL webhook, or other?
