# Design Benchmark and Gap Analysis, 2026-08-01

Measured comparison of the POMDR redesign (newpomdr-local.local) against three
best-in-class nonprofit sites Andrew picked as references:

1. worldwildlife.org (WWF)
2. water.org
3. girlswhocode.com (GWC)

Method: every number below was measured in a real browser (Chrome DevTools MCP,
1350px viewport) with computed styles, not eyeballed. Our pages measured the
same way, plus a programmatic WCAG contrast sweep. The goal is not to copy
anyone. We keep our voice, palette, and warmth. The references calibrate the
foundational mechanics: spacing, hierarchy, restraint, and color discipline.

---

## 1. What the references actually do (measured)

| Metric | WWF | water.org | GWC | POMDR (ours) |
|---|---|---|---|---|
| Body text | 16px / 1.4 | 16px / 1.7 | 16-20px | 18px / 1.8 |
| Card / feature title | 22-32px bold | 24-28px | 28px+ bold caps | 21-28px serif |
| H1 / hero display | 54px bold | 48px bold | 60px+ caps | 68px serif |
| Section vertical rhythm | 80px, constant | 40-70px | large, consistent | 40 / 80 / 100 mixed |
| Card padding (boxed cards) | 40px | 30px+ | n/a (split blocks) | 20-24px |
| Primary button height | 56-66px | 40-112px | 44px+ | 42-52px |
| Button text | 16-24px, sentence case | 16px sentence case | 12-16px caps | 17-20px caps + letterspacing |
| Top-level nav items | 3 + search + 2 CTAs | 4 + search + Donate | 4 + 1 CTA | 7 + 2 action-bar + Donate |
| Nav height | 85px | 158px (two-tier) | 105px | 172px (two-tier) |
| Hero | single static message | single static + overlay | color-blocked static | 5-slide auto carousel |

### The three lessons that matter most

**WWF: restraint is the luxury.** Their cards have no borders, no shadows, no
rounded boxes. A bare photo, a bold eyebrow (Video / Blog / News), a 26px bold
title with an inline arrow, quiet body text. Whitespace does all the
separation, and the constant 80px section rhythm makes the whole page feel
calm. Two accent colors total (green, orange) against warm off-white.

**water.org: one blue, used with iron discipline.** Their brand is blue like
ours, and they never put blue text on a blue background. Blue panels always
carry white text. Light sections always carry near-black or deep-navy text.
Blue mid-tones appear only as accents on white. Body copy runs at a 1.7
line-height, which is why the same 16px type feels airier than ours sometimes
does. Text links are underlined with a chevron, every time, so links are never
color-only.

**GWC: one hue family, extreme contrast ends.** Their teal world works because
text is either very dark teal on pale mint or white on deep teal. Nothing sits
in the middle. Big flat type, 50/50 image-text split blocks, four nav items,
total confidence. Their monochrome discipline is the exact template for our
blue world.

---

## 2. The honest gap list, ranked

### G1. The signature "blue on blue" is real, systemic, and measurable
The pattern: our italic accent words use light-blue tints (--blue-100
#BCD7E4, --blue-200 #7CB9D0) which work on white, but we also place them on
blue bands, where they wash out:

- Home hero card: accent word at 84px, #BCD7E4 on #008BB0 = **2.63:1. Fails
  WCAG AA even for large text.**
- /donate/ header: "saves lives." at 28px italic, #7CB9D0 on #00627D =
  **3.19:1, borderline for large text, fails if text were smaller or normal
  weight.**
- Same construction appears wherever a page-narrative or hero uses an em on a
  colored band.

water.org would render every one of those words white. The italic already
carries the emphasis; the tint is not needed on a colored background.

**Rule to adopt: tinted accents live only on white or near-white backgrounds.
On any colored band, accent words are white (or warm neutral), still italic.**
One CSS rule per band context fixes this site-wide.

### G2. The /donate/ "All Ways to Give" wall is our weakest layout
Fourteen nearly identical dark cards in a 3-column grid: dark photo overlays,
16px text on a narrow measure, plus a second family of purple-gradient cards
that clashes with the photo cards. Every option carries identical visual
weight, which is choice paralysis, and the dark-on-dark treatment is the page
the user's "dark blue colors on blue backgrounds" instinct is pointing at.
The references never do this. water.org ranks giving options: one hero ask,
one monthly ask, then a modest directory. WWF would make these borderless
light cards with big titles.

**Fix: rank the page. Hero ask (one-time), monthly ask, then the long tail as
a light directory (white cards, dark text, small icon, title, one line, arrow
link), killing the dark overlays entirely.**

### G3. Section rhythm is inconsistent
On a single page we mix 40 / 80 / 100px section paddings (fostering measured:
40, 100, 100, 80). WWF holds a constant 80. The mixed rhythm is part of the
"spaced weirdly" feeling: the eye never settles into a beat.

**Fix: pick the beat (80px desktop / 56px mobile), tokenize it
(--section-pad), and apply it everywhere. Delete per-page overrides.**

### G4. Card interior density (fixed in part, finish the job)
Reference boxed cards run 30-40px padding; ours run 20-24px after the July
improvement (was 18-20). Our card titles (21-26px) are proportionally smaller
against photos than WWF's 26-32px on plain white. The dog cards are now close;
program/giving cards on sub-pages still cramp.

**Fix: card padding token (28px min on desktop), card-title floor 22px, and
adopt the WWF-style borderless treatment for editorial cards (stories, news,
programs) where a box adds nothing.**

### G5. Buttons: all-caps + letterspacing at small sizes is working against us
Ours: 42-52px tall, 17-20px, uppercase with 0.04-0.8px letterspacing. WWF: up
to 66px tall, 24px, sentence case. All-caps with letterspacing measurably
reduces reading speed at small sizes, and our audience is seniors. Buttons are
also our primary conversion surface, and sentence case reads warmer, which is
our voice anyway.

**Proposal (bosses' call, brand-adjacent): keep caps for tiny labels
(eyebrows, badges), move buttons to sentence case at 18-20px, min height
52px, and reserve one oversized 60px+ hero CTA per page.**

### G6. Navigation carries more than the references
Top-level count: WWF 3, water.org 4, GWC 4, ours 7 plus a 2-button action bar
plus Donate (10 tap targets in the header). Two-tier headers exist (water.org
is 158px, ours 172px), so height is defensible, but the cognitive load of 7
dropdowns is not what the references do. They push depth into fewer, fatter
menus (Our work / Get involved / About).

**Proposal (IA change, needs Andrew's sign-off, not a quick fix): consolidate
toward Dogs (Adopt, Foster, Surrender), Get Involved (Volunteer, Helping Paw),
What's Happening, About, Donate. Five items, same pages underneath.**

### G7. The hero carousel dilutes the one message
All three references open with a single static statement. We auto-rotate 5
slides, which means most visitors never see slides 3-5, the message resets
mid-read for slow readers (seniors), and motion is the first thing on the
page. Our "no time-based interactions" a11y rule is also strained by this.

**Proposal: one hero, one message, one photo, the three CTAs. Keep the other
slides' photos for section backgrounds elsewhere.**

### G8. Photos are doing editorial work through murk
Reference sites let photography breathe full-bleed and put text beside, not on,
images (or on a solid band below, as WWF's tiger hero does). Our sub-page
program cards put text on heavy dark scrims over small photos, which flattens
118 dogs' worth of genuinely good photography into dark rectangles.

**Fix: prefer text-beside-photo (50/50 split blocks like GWC) or WWF's
solid-band-below-photo pattern on new layouts; reserve text-on-scrim for the
hero and pillars where it already works.**

### G9. Link discipline
water.org underlines every inline text link. Ours are mostly color-only in
body copy (blue on white passes contrast but fails the color-blind and the
squinting). We already fixed the worst cases; make underlines the default for
in-paragraph links site-wide.

---

## 3. What we already do better (keep, and say so to the bosses)

- **Base type is bigger than all three references.** Our 18-19px body beats
  their 16px, correct for a senior audience. After the July comfort pass,
  nothing readable sits below 16px.
- **The senior a11y layer is beyond any reference:** the Larger-Text toggle
  (22px mode), 3px focus rings, 24px+ touch targets, pinch-zoom restored.
  None of the three sites has an equivalent.
- **Dog cards post-redesign are competitive:** clean photo crop (center 30%),
  28px serif names, bottom-anchored CTA, status badges. WWF-grade.
- **Voice.** "Find your new old best friend" beats any reference site's copy.
  The warmth is the moat; the mechanics just need to catch up to it.
- **Home page a11y is a measured 100** (Lighthouse), with real content, real
  events, and honest stats.

---

## 4. Priority order for the pre-review sweep

Quick wins (CSS-only, low risk, do first):
1. G1 accent-on-band rule: white accents on colored bands, site-wide.
2. G3 section-rhythm token: one beat, applied everywhere.
3. G9 underline in-paragraph links.
4. G4 card padding/title floors.

Structural (template edits, verify each page):
5. G2 donate page re-rank (biggest single-page win in the whole audit).
6. G8 photo treatment on sub-page program cards.
7. G5 button case/size system.

Decisions for Andrew / the bosses (do not build until signed off):
8. G7 single hero vs carousel.
9. G6 nav consolidation to 5 items.

Nothing in this list touches the palette, the serif/sans pairing, the paw
motifs, or the voice. It is all mechanics: contrast discipline, one spacing
beat, fewer-but-bigger choices, and letting the photography and copy breathe.

Method note: WWF/water.org/GWC measured 2026-08-01 live; contrast sweep is
computed WCAG ratios on rendered DOM (photo-overlay text excluded where the
scanner cannot see image backgrounds; those cases verified by screenshot).
