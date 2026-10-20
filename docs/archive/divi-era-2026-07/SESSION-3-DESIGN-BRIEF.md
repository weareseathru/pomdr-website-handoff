# Session 3 Design Brief

UX, product, and visual scope for the next session. Loads cold. Pairs
with `~/.claude/plans/lets-start-here-and-synchronous-lightning.md`
(execution order) and `CLAUDE.md` (standing rules).

Foundation locked at commit `c3caddd` on `chore/repo-hygiene`.

---

## 1. UX north star

A senior dog finds a senior person on POMDR's site in three taps:
**home → dog card → adoption inquiry**. Everything else (foster,
volunteer, donate, story) hangs off that spine. The site is editable by
Carie, Monica, and Allison without code, and reads cleanly at 16px on a
60-year-old's laptop without zoom.

Voice is warm, specific, never cute. Brand is teal + purple on white,
photos do the emotional work, type stays out of the way.

---

## 2. Primary user journeys (locked)

### 2.1 Adopter

Entry: organic search or referral. Lands on `/` or `/adopt/`.

| Step | Screen                | Critical surface                                                   |
|------|-----------------------|--------------------------------------------------------------------|
| 1    | `/` hero rotator      | Real dog photo, name, status badge, "See all dogs" anchor          |
| 2    | `/adopt/` grid        | 7-status badge, age, sex, breed, weight, hover lift, real photo    |
| 3    | `/dog/{slug}/` detail | Status strip, 3-up gallery, story long, sticky "Apply" CTA         |
| 4    | LGL inquiry form      | Pre-filled dog name, no extra fields                               |

Failure modes to design against:
- Adopter cannot tell at a glance which dogs are actually available.
  **Fix:** status badge is the second most visually heavy element on
  every card, after the photo.
- "Meet the dog" copy reads generic.
  **Fix:** voice rules enforced at the ACF field instruction layer.
- Mobile sticky CTA covers content.
  **Fix:** sticky bar is 56px max, bottom-anchored, has a close affordance
  the first time it appears.

### 2.2 Foster

Entry: linked from a specific dog with `status=foster-needed` or
`foster-needed-dated`.

Critical surface: a filtered view of `/adopt/` showing only foster-needed
dogs, sortable by date. The detail page replaces "Apply to adopt" with
"Apply to foster" when status flags foster.

### 2.3 Donor

Entry: footer link, story page, or `/donate/`.

Critical surfaces:
- One-time / recurring toggle on the donation form.
- Named-fund picker driven by `campaign_tag` values (Forever Starts
  Here, Helping Paw, Sanctuary, General).
- Post-donation thank-you page that offers Mailchimp opt-in without
  forcing it.

### 2.4 Volunteer

Entry: footer or `/get-involved/` hub.

Critical surface: short application with a specific scoped ask
("transport", "photographer", "event volunteer", "foster") rather than a
single generic form.

### 2.5 Staff (admin)

Entry: WP admin.

Critical surface: a single dog post screen where status, gallery,
campaign tag, and short bio live above the fold. Voice rules surfaced
as ACF instruction text. Bulk-edit for `status` so a Saturday adoption
event closes 4 dogs in one click.

---

## 3. Component design specs (the four bundles, locked)

### 3.1 Status strip (`single-pets.html` and detail prototype)

Position: directly under the dog's name. Width: full content column.
Height: 56px on desktop, 64px on mobile (more vertical breathing room).

| Status               | Icon glyph | Color (token)        | CTA                          |
|----------------------|------------|----------------------|------------------------------|
| `available`          | paw        | `--teal` (#0099A8)   | "Apply to adopt"             |
| `foster-needed`      | home       | `--purple` (#5B2C6F) | "Apply to foster"            |
| `foster-needed-dated`| calendar   | `--purple`           | "Apply to foster [dates]"    |
| `adoption-pending`   | hourglass  | `--orange` (#C06B00) | "Application in progress"    |
| `recently-adopted`   | heart      | `--teal-700`         | "See more like {name}"       |
| `hospice`            | candle     | `--ink-3` (#5B5147)  | "Support sanctuary care"     |
| `courtesy-listing`   | flag       | `--blue-700`         | "Contact listing party"      |

Foster-dated variant renders a small italic meta line ("Needs foster
May 14 to June 2") below the strip, never inside it.

### 3.2 Gallery

3-up grid below hero on detail. Click opens lightbox with keyboard nav,
focus trap, ESC close, and `prefers-reduced-motion` honored. 3 to 6
photos per dog. Captions optional. No autoplay. The prototype's
vanilla-JS lightbox ports 1:1 to `@wordpress/interactivity` in Session 3.

### 3.3 Campaign ribbon

Two variants:

- **Card variant**: diagonal corner ribbon, top-right, 80px wide. Renders
  only when `campaign_tag` is set.
- **Detail variant**: full-width banner above the hero, 48px tall, with
  ribbon glyph and one-line copy.

Color: teal-to-purple gradient with white text. No drop shadow heavier
than `0 2px 8px rgba(0,0,0,.12)`.

Copy patterns (fixed):
- `forever-starts-here`: "Forever Starts Here. Long-term resident."
- `forever-starts-here` (card-tight variant): "Forever Starts Here"
  alone, with the qualifier moved to a separate meta line.
- `helping-paw`: "Helping Paw program dog"
- `sanctuary`: "POMDR Sanctuary dog"

### 3.4 Card (homepage and `/adopt/`)

Locked spec:

```
[ real photo, 3:2 aspect, lazy-loaded ]
[ status badge, top-left absolute ]
[ campaign ribbon, top-right absolute, conditional ]

Name (24px, var(--font-serif), 500)
~{age} · {sex} · {weight} · {breed}   (14px, var(--ink-3))
{story_short, 60 to 120 words}        (16px, var(--ink-1))

[ favorite heart, bottom-right, syncs via localStorage ]
```

Hover: 2px lift, 200ms ease. Disabled under `prefers-reduced-motion`.

---

## 4. Information architecture (locked)

Top-level nav (5 items, no megamenu):

1. **Adopt** → `/adopt/` (default list)
2. **Foster** → `/foster/` (program info + filtered foster-needed grid)
3. **Donate** → `/donate/` (GiveWP form)
4. **Get Involved** → `/get-involved/` (volunteer, jobs, events)
5. **About** → `/about/` (mission, team, contact, hospice, courtesy)

Footer carries everything else (Privacy, Terms, Annual Report, Herald,
Benefit Shop, Vet Clinic, EIN, hours). The 17 stub pages from Track A
become real pages or move to footer-only.

The `/adopted/` archive lives off the Adopt page as a sibling tab, not a
top-level nav item.

---

## 5. Accessibility design system (commitments)

These are design-time constraints, not test-time fixes.

- **Body text floor: 16px.** No 14px paragraphs. 14px reserved for meta
  lines (stats, captions, timestamps).
- **Contrast floor: 4.5:1 for body, 3:1 for large text.** Audit any new
  color against `--paper`, `--paper-2`, and `--ink-1` before approving.
- **Focus rings: `2px solid var(--blue)` with `2px` outset, on every
  interactive element.** No exceptions.
- **Touch targets: 44x44 minimum.** Status strip CTAs, sticky bar,
  favorite hearts, lightbox controls.
- **Motion: every animation is gated by `prefers-reduced-motion`.** Hero
  rotator, kenburns, marquee, hover lifts, ribbon shimmer.
- **Keyboard order: skip link, header, main, footer.** Lightbox traps
  focus, ESC returns it to the trigger.
- **Form labels: every input has a visible `<label for>`.** Newsletter
  and contact were fixed at commit `c3caddd`. Audit donation and foster
  forms as they ship.

---

## 6. Content design (editor experience)

The site is only as good as what staff can ship in 10 minutes. Decisions:

- **ACF field instructions encode VOICE.md.** `story_short` says "Two
  sentences max. No 'Meet [Name]' openers. No closing questions." in
  the admin UI.
- **Status is a dropdown, not free text.** Seven values, kebab-case
  slugs, human-readable labels.
- **Age is integer only.** "Whole numbers. Approximate ages are fine.
  The site adds the tilde automatically."
- **Featured photo is required to publish.** Prevents shipping a card
  with a placeholder.
- **Shelterluv-owned fields are visually locked in admin** via an
  `acf/update_value` hook once sync is live. Staff see the value, can't
  edit it, see a small "Synced from Shelterluv" badge.

---

## 7. Visual hierarchy (brand application)

Three layers, top to bottom on every page:

1. **Photo layer.** Real dog or staff photo, full bleed where possible,
   no SVG placeholders past Session 2.
2. **Type layer.** Source Sans 3 (web) standing in for Myriad Pro.
   Serif optional for h1/h2 only (kept from current prototype).
3. **Token layer.** Teal as the primary action color, purple as the
   secondary/program color, orange reserved for `adoption-pending`,
   white the canvas. No gray buttons.

Anti-patterns to avoid (called out so they don't reappear):

- Dark-mode-style inversions on hero sections.
- Background images behind text without a contrast scrim.
- Gradient buttons. Solid teal, solid purple, or outlined only.
- Stock photography. Every dog photo is real.

---

## 8. Open design questions (Andrew decides before Session 3 codes)

1. **CPT slug**: confirm `pets` (matches new.pomdr.org) vs. `pomdr_dog`.
   Default recommendation: `pets`. Affects URL shape: `/pets/{slug}/`
   vs `/dog/{slug}/`. URL preference may override slug preference.
2. **Donation processor**: GiveWP confirmed? If yes, named-fund mapping
   to `campaign_tag` is straightforward. If not, pick before designing
   the donate page.
3. **Hospice listing page**: live in nav under About, or buried as a
   footer link? Affects how visible sanctuary dogs are.
4. **Mobile sticky CTA**: persistent on every detail page, or only when
   user scrolls past the hero? Default recommendation: appear after
   400px scroll.
5. **Favorites**: localStorage-only (current prototype) or tied to a WP
   user account? Default recommendation: localStorage stays, no login.
6. **Annual report home**: `/annual-report/` linkable hub vs. PDF-only
   download? Default recommendation: hub page with PDF download.
7. **Herald print ad generator**: live on the site for staff, or
   manual-only? Default: manual for Session 3; revisit later.

---

## 9. Out of scope for Session 3

Locked exclusions so scope doesn't drift:

- Push to production (new.pomdr.org goes live last).
- Shelterluv sync adapter (designed in Session 3, built in Session 4).
- AI-assisted bio drafting in admin (deferred indefinitely).
- Search across dogs by trait (deferred to Session 5; the 95-dog
  inventory is small enough to scroll).
- Multi-language. POMDR serves the Monterey peninsula in English.
- Comments on dog posts.
- A blog. Stories live as posts in a dedicated CPT (`pomdr_story`)
  rather than a date-ordered blog.
- Custom email templates beyond Mailchimp's existing infrastructure.

---

## 10. Definition of "Session 3 done"

1. `pomdr-2026` block theme activates on local WP, renders the homepage,
   `/adopt/` archive, and one dog detail page using imported data.
2. The four design upgrade bundles (status strip, gallery, ribbon, card)
   exist as Gutenberg block patterns and visually match the static
   prototype within 5% pixel difference on the same dog.
3. ACF fields cover all 7 status values plus `story_short`,
   `campaign_tag`, and `featured_photo_id`. Field instructions encode
   VOICE.md.
4. Voice CI stays green on `--diff main`.
5. Lighthouse mobile on `single-pets.html`: perf >= 90, a11y == 100.
6. Andrew has clicked through one dog end to end and approved the
   visual feel.

If items 1 through 6 are true, the session ships and Session 4 picks up
the Shelterluv adapter and full 95-dog migration.

---

_Last updated: 2026-05-18. Owner: Andrew Z. Pairs with CLAUDE.md._
