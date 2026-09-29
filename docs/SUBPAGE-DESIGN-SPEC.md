# The sub-pages: what the design is, and how to rebuild it

Date: 2026-09-22. Source: a measured audit of all 39 live sub-pages on
newpomdr-local, read in a browser at 1440 and 390, cross-read against the Divi
child theme's templates and stylesheets.

This is the specification for rebuilding those pages in the standalone repo,
without WordPress or Divi. The homepage is deliberately out of scope and comes
later.

Everything below is measured. Where a number comes from source rather than from
the rendered page it says so, because on this site the two disagree more often
than you would expect.

---

## 1. The single most important finding

**The site is narrower than its own design calls for, and has been all along.**

Divi ships an inline rule setting `.container { width: 80% }`. The POMDR
stylesheets override `max-width` to 1320px but never set `width`, so Divi's
percentage wins on every page.

| Viewport | Intended | Actual container | Content after padding |
|---|---|---|---|
| 1440 | 1320px | **1152px** | 1088px |
| 390 | ~350px | **312px** | **272px** |

On a phone that leaves 118px of a 390px screen as empty margin and only 70% of
the screen carrying content. The 1320px cap never engages below about a 1650px
viewport, so nobody has ever seen the design at its intended width.

**Every measurement in this document was taken on that accidental grid.** The
rebuild should set a real container and accept that proportions will shift
slightly. The recommendation is `width: min(1320px, 100% - 64px)` with a 20px
gutter below 640px.

---

## 2. Two rendering paths, one design

Of the 39 pages, most have been converted to "native Divi": the real markup now
lives inside a Divi Code module in the database, wrapped in five to seven nested
builder divs, with the page's own CSS extracted into `native-pages.css` scoped as
`body.pom-native .pg-<slug>`.

A handful still render from the PHP sidecar templates. **Both paths produce the
same design.** For the rebuild, the PHP template is the readable source of the
markup and `native-pages.css` holds that page's local styles. Everything with an
`et_pb_` prefix, plus the `pom-native`, `pom-wrap` and `pg-*` scoping ladder and
the 149KB of generated CSS that exists only to out-specify Divi, is discarded.

---

## 3. The design system, as it actually renders

### Colour

| Token | Value | Used for |
|---|---|---|
| blue | `#008bb0` | Italic accents, the paw glyph, step numerals |
| blue-700 | `#006c8a` | Every button fill, every link, all small text |
| blue-900 | `#00627d` | Dark bands. Note: `tokens.css` says `#004e63`, but `pomdr-design.css` overrides it and the override is what renders |
| blue-50 / cream | `#e8f2f6` | The tinted band, icon tiles, card fills |
| purple | `#632f88` | Second button, the monthly band, some accents |
| purple-50 | `#f1ebf5` | Purple icon tiles, one CTA band |
| ink / ink-2 / ink-3 | `#16202b` / `#3c4a57` / `#5d6a77` | Body, secondary, tertiary |
| line | `#cfe0e9` | Every hairline and card border |

Four band colours exist site-wide: white, the `#e8f2f6` tint, `#00627d` dark
blue, `#632f88` purple. Bands alternate, always full bleed with the container
inside.

Orange was retired in June 2026 and its tokens now point at purple.

### Type, computed at 1440

| Element | Family | Size | Weight | Line height | Colour |
|---|---|---|---|---|---|
| Body | Source Sans 3 | 24px | 400 | 1.6 | ink |
| `h1.page-headline` | Source Serif 4 | 68px, clamp from 40 | 400 | 1.02 | ink, white on colour |
| `.page-narrative` | Source Serif 4 | 33px, clamp from 25 | 400 | 1.2 | ink-2, `em` italic blue |
| `.page-lead` | Source Sans 3 | 24px | 400 | 1.55 | ink-2, 56ch |
| `.section-title` | Source Serif 4 | 31.68px, clamp from 24 | 400 | 1.15 | ink, `em` italic blue |
| `.section-lead` | Source Sans 3 | 23px | 400 | 1.6 | ink-2, 56ch |
| `.eyebrow` | Source Sans 3 | 36px | 600 | 1.1 | blue-700, uppercase |
| `.cta-strip h2` | Source Serif 4 | 44px, clamp from 26 | 400 | 1.05 | ink |
| Two-column band h2 | Source Serif 4 | 44 to 48px | **300** | 1.05 | varies |
| Button | Source Sans 3 | 25px | 700 | 1.05 | per variant |

**The three-line hero voice is the most consistent thing on the site**: a serif
headline, a serif narrative line with one italic blue phrase, then a sans lead.
It appears on every section page. Keep it exactly.

### Spacing, radius, shadow

Sections are 80px top and bottom, 56px on mobile, though bespoke bands ignore
this and never shrink. Radii are 14, 22, 32 and 44px. Two shadows do all the
work: a barely-there rest shadow and `0 30px 60px -20px rgba(22,32,43,.25)` on
hover.

---

## 4. The component catalogue

Thirty-nine pages reduce to about fifteen components. This is the build list.

1. **PageHeader.** The `#e8f2f6` band, 40px top and 80px bottom. Two variants:
   *split*, a `1.05fr / 1fr` grid at 52px gap with the photo in a 44px-radius
   cover frame at 360px minimum height, stacking at 860px; and *text only*, used
   by every form page. Holds the three-line voice plus a button pair.
2. **Button.** One 52px pill, 999px radius, 25px at weight 700, with a coloured
   drop shadow and an invert-on-hover. **Five classes collapse to three**: a blue
   primary, a purple secondary, and one for dark backgrounds. Today
   `.btn-outline` is not an outline at all, it is solid purple, and
   `.btn-ghost`, `.btn-white`, `.btn-light` and `.btn-band` are four names for
   one thing.
3. **Eyebrow.** Uppercase label preceded by a 40 by 38px masked paw print, in
   blue, purple or white. See the defects: it is currently larger than the title
   it labels, and its colour does not reach the paw.
4. **SectionTitle and SectionLead.** Serif title with an italic accent on the
   last phrase, over a 56ch lead. The house voice device.
5. **Card.** White, one hairline border, 32px radius, 28px padding, lift on
   hover. A small variant at 14 to 22px radius covers list items, quotes and
   accordions. Realised today under nine different class names.
6. **CardGrid.** Three columns at a 20 to 22px gap, two below 900px, one below
   580px. The dog grid uses 28px and different breakpoints; reconcile.
7. **IconChip.** A 44 to 48px rounded square, tinted, holding a stroked 24-box
   SVG at 1.5 to 1.7 stroke width. Teal or purple.
8. **PhotoCard.** A photograph behind a three-stop scrim with white text over
   it, 330px minimum, image scaling 1.05 on hover.
9. **DogCard.** The most reusable thing in the project, and one renderer already
   feeds three pages. A 4:3 photo, a status badge top left, a heart top right,
   a serif name, a meta line reading age, sex, weight and breed, optional tag
   pills, and a bordered "View profile" row pinned to the bottom.
10. **PersonCard and TeamGrid.** A 120px circular photo with a two-letter serif
    initials fallback, a serif name and a role line. Two conflicting skins exist
    today; unify.
11. **CtaStrip.** A tinted full-bleed band, a 44px serif line on the left, a
    button pair on the right, wrapping on narrow. On seven of nine pages in one
    audit slice alone.
12. **TwoColBand.** A tinted band holding a single `1fr 1fr` grid at 48 to 72px
    gap, text one side, photo or list the other.
13. **Steps.** Two patterns that should be one: carded steps with a 64px numeral
    gutter, and hairline steps with an 80px gutter and a top rule. Even the
    heading weight differs between instances.
14. **Accordion.** Native `<details>`, white, hairline, 22px radius, serif
    summary at 26px.
15. **FormEmbed.** A Little Green Light iframe at 760 to 800px maximum width,
    zero border, 14px radius, centred, with a `<noscript>` link and a contact
    footnote below carrying the email and phone.

Two smaller recurring atoms: the **inline arrow link**, a flex row with a `→`
that slides 4px on hover, and the **stat cluster**, a light serif numeral over a
small label, separated by a left hairline.

---

## 5. What is genuinely good and must survive

Worth stating plainly, because the defect list below is long and the design
itself is strong.

- The three-line hero voice, and the italic accent phrase. It is distinctive and
  it is used consistently.
- The band rhythm: alternating white and tint, with occasional colour, full
  bleed, container inside.
- The serif and sans pairing, and the choice to set headings at regular weight
  and let size carry the hierarchy.
- The rule that accents go white on coloured bands rather than staying blue,
  which is what keeps them readable.
- The white card directory on the giving page, which deliberately replaced an
  earlier photo-card version and reads as a calm index.
- The 44px-radius media frame in the split header.
- One primary action and one secondary, as a pair, repeated at the foot.

---

## 6. Defects measured, not to be reproduced

These were all verified in a browser. They are listed so the rebuild does not
faithfully reproduce them.

**Severe, user-blocking**

1. **The Donate Now button is off the screen on a phone.** At 390px it renders
   from x=332 to x=513 in a 390px viewport, inside a hero with `overflow:
   hidden`. The primary giving action on the main giving page cannot be tapped.
   A `$600` amount chip is lost the same way. Cause: a number input with its
   default intrinsic width and no `min-width: 0`.
2. **The giving widget has no form and no fallback.** It is JavaScript only, with
   no `<form>` and no `<noscript>`. With scripting off the entire ask is dead.
3. **The hero overflows the viewport on five pages at 390px**, because the split
   grid's minimum is set by the button row's width. Measured at up to 44px past
   the viewport edge, hidden only by `overflow-x: hidden` on the body.
4. **A hero image is a 404.** The Perpetual Care page offers a `.webp` that does
   not exist; the browser picks it, gets 210KB of HTML error page, and renders
   nothing. The working `.jpg` beside it is never reached.

**Visible and wrong**

5. The eyebrow is 36px, larger than the 31.68px title it labels. Site-wide.
6. The About page's h1 is 41.76px while every other page's is 68px. The
   reference page has the smallest title on the site.
7. A white eyebrow sits on a light tinted band on one page at roughly 1.3:1.
   Invisible.
8. The eyebrow's colour is set inline and cannot reach its `::before`, so the
   paw renders blue beside purple text in at least two places.
9. Every page ships five `<h1>` elements, because the footer headings are `<h1>`.
10. The Perpetual Care card on the giving hub links to the surrender page.
11. Two giving cards link to addresses that only exist via redirects.
12. The Adopted Dogs tab prints a count of 64 and renders 24.
13. The heart button on dog cards does nothing outside the homepage.
14. The Why page renders its testimonials after the closing call to action.
15. The About timeline is out of chronological order.
16. A build note is published on the public About page, telling readers the files
    move into the media library at launch.

**Structural**

17. Two nested `<main>` elements on at least two pages.
18. The testimonials page is a single unbroken 34,505px scroll on desktop and
    130,922px on mobile, with no filter, search or pagination.
19. Several grids overflow the 272px mobile column, hidden by `overflow-x`.
20. Images are badly sized throughout: a 150 by 237 original blown up to 505 by
    798, a 200 by 150 rendered at 528 by 396, a 200 by 289 upscaled 2.5 times,
    dog cards served at 328px into a 342px slot.
21. The phone number is written two ways, and is plain text rather than a link on
    both questionnaire pages.
22. Bespoke section paddings ignore the 80px rhythm and never shrink on mobile.
23. The venue is called both Bauer Center and Boand Clinic.
24. The clinic publishes no opening hours; one shop email is not a link; two of
    three locations have no map link while the third makes it the primary action.

---

## 7. How to rebuild it

The order is chosen so that each step is verifiable before the next depends on
it.

1. **The shared layer first.** Container, band, the three-line header, buttons,
   eyebrow, section title, card, card grid. These are already partly built in the
   standalone repo's chrome work; extend rather than restart.
2. **The form pages next**, because there are six of them and they are one
   component with different payloads. Highest count, lowest risk.
3. **The simple content pages**: culture, jobs, the three locations, the legal
   pages. They exercise the card, the two-column band and the contact trio.
4. **The listing pages**: events, news, videos, media, resources, testimonials.
   Here the item template matters more than the page, and testimonials needs a
   real answer rather than a port.
5. **The funnel pages**: process, why, fostering, foster needs, surrender,
   volunteer. Most bands, most bespoke CSS, most value.
6. **The giving hub**, which is 17 cards and a widget that needs rebuilding as a
   real form rather than ported.
7. **The adopt page** last of the sub-pages, because it carries the dog card, the
   filters, the tabs and the sort, and it is the one page where behaviour matters
   more than layout.

The homepage comes after all of it.

At each step: build locally, confirm in a browser at 1440 and 390, check it
against this document, then show it before it goes near the repository.

---

## 8. The design-thinking pass, and where this audit fell short

Sections 1 to 7 are a measurement. Measurement is not design, and a faithful
port of a measured inventory would rebuild this site's problems in cleaner code.
Run against the practice this project says it follows, the audit has four gaps.

### Gap 1: there is no person in it

Empathy is step one and this document skipped to inventory. It records that an
eyebrow is 36px without once asking who is reading it.

Who actually arrives on these pages:

| Page | Who arrives, and in what state |
|---|---|
| Surrender | Someone whose health is failing, or whose circumstances collapsed, about to give up a dog they love. Ashamed, often crying. |
| Helping Paw | Someone who cannot pay a vet bill. Proud, and asking for help is hard. |
| Perpetual Care | Someone confronting their own death and what happens to their dog after it. |
| Adopt, Process, Why | Someone browsing, possibly for months. Low urgency, high emotion. |
| Volunteer, Fostering | Someone with time to give, unsure what is being asked of them. |
| Donate | Someone already convinced, who wants it to take thirty seconds. |

Three of those six are people in a bad moment. That should drive the design, and
in the audit it drove nothing.

**What it changes.** On the distress pages the phone number is the primary
action, not the form. Plain language beats brand voice. The first thing on the
page should be reassurance, not a process. The current surrender page opens with
a serif pun, and its tone is a decision worth revisiting rather than porting.

### Gap 2: no page states its job

Every page was catalogued as a list of sections, and not one was asked what it is
for. Without that, section order is arbitrary and nothing can be cut, because
nothing can be shown not to serve the job.

Before rebuilding each page, write one line: *this page exists so that a
[person] can [do one thing]*. If a section does not serve that line, it goes. The
audit already found one page that fails this on its face: Why renders its
testimonials after the closing call to action, which means the proof arrives
after the ask.

### Gap 3: reduction was not applied

The audit found fifteen components and treated that as the answer. It is the
starting point. Reduce, then reduce again:

- **The eyebrow should probably go.** A 36px uppercase label sitting above a
  31.68px title is louder than the thing it labels, and it tells the reader
  nothing the title does not. It is decoration wearing the costume of structure.
  At minimum it drops well below the title. My recommendation is to delete it and
  let the titles carry the sections.
- **Seventeen giving cards is a wall, not a directory.** Somebody who wants to
  give has to read seventeen options to find theirs. They need grouping, or
  triage, or a default path with the rest behind a heading.
- **The three-line hero has one line too many on some pages.** The headline and
  the narrative sometimes say the same thing twice, once plainly and once as a
  pun. The narrative earns its place only when it adds meaning.
- **Two step patterns become one.** Nine card class names become one. Five button
  classes become two plus a variant for dark backgrounds.
- **Testimonials needs an answer, not a port.** A 34,505px unbroken scroll is not
  a design.

### Gap 4: nothing has been tested with a person

The practice says prototype and test, and every judgement in this document is
mine or measured. Nobody over 65 has been watched using any of it.

The cheapest useful version: once three or four pages are rebuilt, watch two
POMDR volunteers in their seventies try to start a Helping Paw application and
find the Benefit Shop's opening hours. That will teach more than the rest of this
document.

### What this changes about the plan

The build order in section 7 stands. Two things are added to every step:

1. Write the page's job in one line before writing any markup, and cut what does
   not serve it.
2. On the three distress pages, the phone number is the primary action and the
   reassurance comes before the process.

And one thing is removed from the plan: the eyebrow, pending your call.
