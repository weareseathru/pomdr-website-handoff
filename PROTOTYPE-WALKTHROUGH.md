# POMDR Static Prototype Walkthrough

Hands-on QA script for the static design lab at
`pomdr-website/project/`. Walk this top to bottom before approving a
design upgrade as "ready to port into the WordPress block theme."

The prototype is meant to be visually faithful and clickable, not
performance-optimized. The bar for "ready to port" is: the pattern
renders correctly, the copy follows VOICE.md, the a11y checks below
pass, and Andrew has eyeballed it.

---

## 0. Before you start

1. Open a terminal at `pomdr-website/project/`.
2. Run a static server. Either of:
   - `python -m http.server 8000`
   - `npx http-server -p 8000 -c-1`
3. Open `http://localhost:8000/index.html` in Chrome.
4. Open DevTools (F12). Keep the Console tab visible; warnings should
   be empty during normal click flow.
5. Optional: install the axe DevTools extension or the
   Chrome DevTools MCP server for the accessibility passes below.

If you are reviewing offline (Wi-Fi off), the React, Babel, and font
files should still load from `project/vendor/` once Task A1 is
finished. Until then, offline mode will fail on the homepage.

---

## 1. Global chrome (every page)

Verify on three pages: `index.html`, `adopt.html`, `dog/clove.html`.

### Header / nav

- [ ] Logo at top-left links to `index.html`.
- [ ] Top-level nav shows: Home, About, Adopt, Foster, Volunteer,
      Helping Paw, Surrender, Get Involved, Contact.
- [ ] Hovering "About," "Adopt," "Volunteer," "Helping Paw,"
      "Surrender," and "Get Involved" reveals a dropdown.
- [ ] Each dropdown item points at an existing page (not `#`).
- [ ] At a 480px-wide viewport the nav collapses to a drawer trigger.
      Drawer opens and closes via keyboard (Tab to trigger, Enter,
      Tab through items, Escape to close).
- [ ] The "Donate" primary CTA in the nav points at `donate.html`.

### Skip link

- [ ] With the page loaded, press Tab once. A blue / purple
      "Skip to main content" pill appears in the top-left.
- [ ] Pressing Enter on the skip link jumps focus past the nav to
      the first heading of the page content.

### Main landmark

- [ ] Open the elements panel and confirm there is exactly one
      `<main id="main">` per page, wrapping the page-header and the
      content sections (not the nav, not the footer).
- [ ] Pages that already declare `<main id="main">` in their HTML
      should NOT have a second one injected by the layout script.

### Footer

- [ ] Brand block shows POMDR identity (no h4 / h5 stragglers; the
      tagline is a plain paragraph and the four column headers are
      `<h2>` so the document outline stays clean).
- [ ] EIN reads `27-1154816`.
- [ ] Phone reads `(831) 718-9122` and is a real `tel:` link.
- [ ] Mailing address: Patricia J. Bauer Center, 615 Forest Ave,
      Pacific Grove, CA.
- [ ] Boand Vet Clinic and Benefit Shop addresses both appear.
- [ ] Privacy and Terms links point at real stubs, not `#`.
- [ ] Hours placeholder is present.
- [ ] No em-dashes anywhere in footer copy.

### Focus rings

- [ ] Tab through any page. Every interactive element shows a visible
      teal / blue outline on focus. No element receives focus silently.

### Reduced motion

- [ ] In DevTools, open Rendering -> "Emulate CSS media feature
      prefers-reduced-motion: reduce."
- [ ] Reload the homepage. Confirm:
      - Hero rotator does NOT auto-advance.
      - Marquee bands do not scroll.
      - Reveal-on-scroll fades resolve to their final state
        immediately.
      - Card hover lift does not animate.
- [ ] Toggle the emulation off; confirm motion returns.

---

## 2. Homepage (`index.html`)

### Hero

- [ ] Five hero slides rotate every six seconds when reduced-motion
      is off.
- [ ] Each slide image has descriptive alt text (not the empty
      string) and is a real dog photo, not a placeholder SVG.
- [ ] The decorative pillar image carries `alt=""` and
      `role="presentation"`.

### Programs strip

- [ ] Four program cards: Adopt, Foster, Helping Paw, Hospice.
- [ ] Each card has a working "Learn" link.
- [ ] The list bullet arrow uses `var(--blue-700)` (not the lighter
      `--blue`). Contrast against the cream background looks clean.

### Featured dogs

- [ ] Six dog cards appear in the row.
- [ ] Each card photo is a real `<img>` (not an SVG placeholder).
- [ ] Stats line under each name reads exactly
      `~{age} yrs · {sex} · {weight} lb · {breed}` with middle-dot
      separators.
- [ ] Hearts on the favorite icon match the state stored at
      `localStorage["pomdr-likes"]`. Click a heart, then navigate to
      `adopt.html`. The same dog is still favorited.

### Impact section

- [ ] Four headline numbers render (3,200+ rescued, etc.).
- [ ] Each stat pairs with a short story or photo.
- [ ] Two-column layout on desktop, stacked on mobile (resize to
      confirm).

### Newsletter signup

- [ ] Submitting the form (with any email) redirects to
      `thank-you.html?source=newsletter`.
- [ ] On the thank-you page the source query string is honored.

---

## 3. Adopt page (`adopt.html`)

- [ ] Toolbar at top: search field, filter chips, sort dropdown.
- [ ] All dog cards render with real photos. No grey placeholders.
- [ ] Each card shows the same stats-line format as the homepage.
- [ ] Status badge color matches the dog's status. Cycle through the
      filter chips: at least one card appears for each of the seven
      status values.
- [ ] Cards with `campaign_tag` show a small ribbon in the corner of
      the photo. Two campaign values are exercised:
      `forever_starts_here` (LTD) and `helping_paw`.
- [ ] Click a profiled dog. You land on `dog/{slug}.html`.
- [ ] Click an unprofiled dog. You land on
      `dog/coming-soon.html?name={slug}` and the page picks up the
      dog's name from the query string.
- [ ] The "Foster needed" count and "Available" count in the toolbar
      match the rendered card counts.
- [ ] No `href="#"` anywhere on the page.

---

## 4. Dog detail pages (`dog/*.html`)

Walk through each of the twelve profiled dogs. Verify per-dog details
in the matrix below, then on every page confirm the shared checks.

### Per-dog status matrix

| Dog       | Status                  | Campaign banner       |
| --------- | ----------------------- | --------------------- |
| Bixby     | `available`             | (none)                |
| Buddy     | `available`             | `forever_starts_here` |
| Malcolm   | `available`             | (none)                |
| Nana      | `available`             | (none)                |
| Oscar     | `available`             | (none)                |
| Pebble    | `available`             | (none)                |
| Watson    | `available`             | (none)                |
| Yankee C. | `available`             | `forever_starts_here` |
| Honeybee  | `foster_needed`         | (none)                |
| Clove     | `foster_needed_dated`   | (none)                |
| Aragorn   | `adoption_pending`      | (none)                |
| Breeze    | `recently_adopted`      | (none)                |
| Sun Bear  | `hospice`               | `sanctuary`           |
| Mihla     | `courtesy_listing`      | `helping_paw`         |

### Shared checks (apply to all detail pages)

- [ ] `<title>` is `{Name} | Peace of Mind Dog Rescue` with a real
      pipe, no em-dash.
- [ ] Hero image alt text describes the dog visually, not just the
      name.
- [ ] Stats line uses tilde-age formatting and middle-dot separators.
- [ ] Status strip color matches the status. Foster-needed-dated
      shows a date range. Hospice and courtesy listings show the
      sponsorship / inquiry meta line. Recently-adopted shows the
      adopter location.
- [ ] CTAs are status-appropriate:
      - Available dogs: "Apply to Adopt {Name}" + "Ask About {Name}."
      - Foster needed: foster CTA secondary.
      - Adoption pending: contact + browse-other, no "Apply" button
        (the dog is no longer open).
      - Recently adopted: links to other Happy Tails and the
        available roster.
      - Hospice: "Sponsor {Name}'s Care" + "Ask About {Name}."
      - Courtesy listing: "Inquire About {Name}" + "About courtesy
        listings."
- [ ] Photo gallery sits below the hero with three thumbnails. Each
      thumbnail is a `<button>` with `aria-label` "Open photo N of 3."
- [ ] The gallery container has `role="group"` and an
      `aria-label="{Name} photo gallery."`
- [ ] Clicking a thumbnail opens a lightbox (when wired) and traps
      focus until Escape.
- [ ] Bottom CTA strip phrasing matches the dog's status, not a
      generic "Meet more senior dogs." Examples:
      - `foster_needed` -> "More dogs needing a foster."
      - `hospice` -> "Support our sanctuary dogs."
      - `recently_adopted` -> "More happy tails."
      - `courtesy_listing` -> "More courtesy listings."
      - `adoption_pending` -> "Meet more dogs ready to adopt."
- [ ] No `href="#"` on any link.

---

## 5. Forms and integrations (stubbed)

- [ ] Contact form on `contact.html` submits and redirects to
      `thank-you.html?source=contact`.
- [ ] Newsletter form on the homepage and on `support.html` (when it
      lands) redirect with `?source=newsletter`.
- [ ] Donate page surfaces the four giving paths (one-time, monthly,
      planned, in-kind) and links to the placeholder donation
      processor stub. The processor itself is out of scope.
- [ ] Foster, volunteer, and surrender pages link to their respective
      application URLs (the LGL forms in the live site). No real
      submission happens from the prototype.

---

## 6. Voice audit

- [ ] No em-dashes anywhere visible. Use Find-in-page for the
      Unicode codepoint at U+2014 (it looks like a long horizontal
      dash, wider than a hyphen). Ignore matches inside source-code
      comments cited by VOICE.md as exceptions.
- [ ] No "Meet {Name}," sentence openings on any dog page.
- [ ] No dog page closes with a rhetorical question.
- [ ] Every "approximately N years" reads as `~N years`.
- [ ] Voice does not promise outcomes ("she will love you," "guaranteed
      forever"). Suggested language only.

---

## 7. Accessibility audit (axe-core + manual)

Run axe-core on:

- `index.html`
- `adopt.html`
- `dog/clove.html` (foster_needed_dated, exercises status strip + date
  range)
- `dog/sun-bear.html` (hospice, exercises sanctuary banner)
- `dog/mihla.html` (courtesy_listing, exercises helping-paw banner)

Acceptance:

- [ ] Zero critical or serious violations on each page.
- [ ] Heading order makes sense: single `<h1>` per page; the document
      outline reads cleanly when previewed via the headings-only view
      in axe.
- [ ] Every image either has descriptive alt text or `alt=""` +
      `role="presentation"` (decorative only).
- [ ] Color-contrast pass: body text on cream and white passes 4.5:1.
      Large display text (>=24px or >=19px bold) passes 3:1.
- [ ] Form labels are programmatically associated with their inputs.
- [ ] Buttons that look like links and links that look like buttons
      use the correct underlying element.

---

## 8. Mobile / responsive

Set the viewport to:

- 480px (small phone)
- 768px (tablet)
- 1024px (small desktop)

At each width on `index.html`, `adopt.html`, and `dog/clove.html`:

- [ ] No horizontal scroll.
- [ ] Hero text is legible (>=16px body).
- [ ] Dog card grid drops to two columns at 768px and one column at
      480px.
- [ ] Dog detail hero stacks vertically below 900px.
- [ ] Gallery thumbnails reflow without cropping.
- [ ] CTAs remain inside the viewport (no overflow).

---

## 9. Known throw-away pieces

These items are scoped to be replaced when the WordPress block theme
is ready in Track B. They do NOT need to be polished further on the
static side:

- The 17 stub HTMLs (privacy, terms, events, resources, benefit-shop,
  jobs, media, testimonials, videos, process, hospice,
  courtesy-listings, adopted, volunteer-application,
  perpetual-care-program, thank-you, dog/coming-soon).
- Hardcoded dog data in `data.jsx` (will be replaced by the WP
  `pets` CPT and ACF fields).
- The vanilla-JS lightbox shim (will be reimplemented with
  `@wordpress/interactivity` once the WP theme exists).

Anything in this list still needs the voice and visual checks above;
it does not need additional engineering polish.

---

## 10. Sign-off

When every checkbox above passes, the static prototype is approved
for "design lock." After sign-off, the same patterns are reimplemented
as Gutenberg blocks in `pomdr-2026/` and re-verified against this same
walkthrough on the WordPress side.

Author: Andrew Z.
