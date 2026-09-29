# DESIGN-TOKENS.md

Every design token observed in the handoff design package, traced to its
source. Authored 2026-05-09.

Sources of truth, in priority order:

1. `pomdr-website/project/pomdr.css` (the shared design system file used
   by all 9 page-level HTML files via `<link rel="stylesheet">`).
2. `pomdr-website/project/styles.css` (homepage-specific overlay; mostly
   redundant with `pomdr.css` but introduces hero-specific variables).
3. `pomdr-website/project/data.jsx` (content constants that imply tokens
   not declared in CSS, mostly the warm-tone dog photo palette).

Where the two CSS files declare the same token, `pomdr.css` wins because
every page uses it; `styles.css` only loads on the homepage.

The handoff also contains `Brand Guidelines_december2025.pdf`. That file
could not be parsed locally. Andrew's review is required before this doc
is locked. If the brand guide contradicts a value below, the brand guide
wins and the value updates here in a follow-up edit.

No invented values. Every row cites a source file and line.

---

## 1. Color

### 1.1 Blue scale (primary brand color)

| Token name        | Hex        | Source                          | Use observed |
|-------------------|------------|---------------------------------|--------------|
| `--blue-900`      | `#004e63`  | `pomdr.css:11`, `styles.css:2`  | Footer background |
| `--blue-700`      | `#006c8a`  | `pomdr.css:10`, `styles.css:3`  | Button hover, link hover |
| `--blue` (500)    | `#008bb0`  | `pomdr.css:9`, `styles.css:4`   | Brand primary, logo, primary CTA |
| `--blue-400`      | `#26A1BF`  | `pomdr.css:15`, `styles.css:5`  | Accent, dog photo gradient stop |
| `--blue-200`      | `#7CB9D0`  | `pomdr.css:14`, `styles.css:6`  | Footer link hover, light eyebrow, photo ring |
| `--blue-100`      | `#BCD7E4`  | `pomdr.css:13`, `styles.css:7`  | Avatar shadow ring, tinted background |
| `--blue-50`       | `#E8F2F6`  | `pomdr.css:12`, `styles.css:8`  | Nav link hover surface, programs section background, tag background |

### 1.2 Purple scale (secondary brand color)

| Token name        | Hex        | Source                          | Use observed |
|-------------------|------------|---------------------------------|--------------|
| `--purple` (500)  | `#632F88`  | `pomdr.css:17`, `styles.css:10` | Marquee background, featured tail card, badge.foster |
| `--purple-400`    | `#7F5A9D`  | `pomdr.css:18`, `styles.css:11` | (declared, not used in current CSS) |
| `--purple-200`    | (not declared in `pomdr.css`); `#A085B7` in `styles.css:12` | `styles.css:12` | (declared on home only) |
| `--purple-100`    | `#C9BBD7`  | `pomdr.css:20`, `styles.css:13` | Quote-mark color, btn-white hover |
| `--purple-50`     | `#F1EBF5`  | `pomdr.css:19`, `styles.css:14` | Events section background, mission floating card icon background |

Note: `--purple-200` is declared only in `styles.css` (homepage). For the
block theme, declare it in `theme.json` so it's available everywhere.

### 1.3 Orange (state/accent)

| Token name        | Hex        | Source              | Use observed |
|-------------------|------------|---------------------|--------------|
| `--orange`        | `#C06B00`  | `pomdr.css:22`      | `badge.pending` (status indicator on dog card) |
| `--orange-50`     | `#FFF5E8`  | `pomdr.css:23`      | (declared, no class observed using it) |

These appear only in `pomdr.css` (the shared design system). Treat orange
as a state color for `adoption-pending`. No third primary, no third brand
hue.

### 1.4 Ink (text)

| Token name        | Hex        | Source                          | Use observed |
|-------------------|------------|---------------------------------|--------------|
| `--ink`           | `#16202b`  | `pomdr.css:25`, `styles.css:16` | Primary body text, headings, dark hero/footer-fill |
| `--ink-2`         | `#3c4a57`  | `pomdr.css:26`, `styles.css:17` | Secondary body text, nav link rest state |
| `--ink-3`         | `#6b7885`  | `pomdr.css:27`, `styles.css:18` | Tertiary text (eyebrow muted, breed text, meta) |

### 1.5 Surface (backgrounds and dividers)

| Token name        | Hex        | Source                          | Use observed |
|-------------------|------------|---------------------------------|--------------|
| `--cream`         | `#E8F2F6`  | theme token files | Default page background. LEADERSHIP SPEC 2026-07-09: the warm cream family was replaced by the brand light blue (docs/UI-UX-FIX-SPEC-2026-07-09.md); the var names were kept so every consumer updated at once. Was `#FAF6F0`. |
| `--cream-2`       | `#D9E9F0`  | theme token files | Wells, photo frames, placeholder backgrounds. Was `#F3ECDF`. |
| `--white`         | `#ffffff`  | `pomdr.css:31`, `styles.css:22` | Card surface, form surface |
| `--line`          | `#cfe0e9`  | theme token files | Card border, footer divider, dashed dividers (cool hairline; was warm `#e5ddd0`) |

### 1.6 Inline-only colors (used in CSS but not tokenized)

These are referenced by literal hex in the CSS, not via a custom property.
The block theme should tokenize them.

| Hex        | Source            | Use                                       | Proposed token name |
|------------|-------------------|-------------------------------------------|---------------------|
| `#f7ddb7`  | `styles.css:177`, `styles.css:680`, `styles.css:683` | Hero title em accent ("warm cream"), newsletter eyebrow | `--cream-warm` |
| `#4b2268`  | `pomdr.css:461`, `styles.css:203` | btn-purple hover (a darker purple)        | `--purple-800` |
| `#a05800`  | `pomdr.css:468`   | btn-orange hover                          | `--orange-700` |
| `#e63946`  | `pomdr.css:874`   | Active heart color (love red)             | `--love-red` |

### 1.7 Photo-placeholder palette (warm tones)

`data.jsx:3-12` declares 8 dog-photo placeholder gradients. These are not
brand colors per se; they're filler for SVG placeholders that will be
replaced by real photographs. Do not pull these into `theme.json`.

```
purple        : C9BBD7 → 7F5A9D → 632F88
blue          : BCD7E4 → 26A1BF → 008bb0
warm tan      : f7ddb7 → d9a870 → 8b5a2b
sepia         : e8d5c4 → c9a88a → 6b4a2b
olive-brown   : d4c5b0 → 9c8670 → 4a3a28
sage          : cfd9bf → 84966a → 3d4a2b
peach         : f0c8b0 → d4896a → 8b3a2b
slate         : b8c9d4 → 6a8fa0 → 2b4a5e
```

---

## 2. Typography

### 2.1 Font families

| Token name      | Stack                                                                        | Source            |
|-----------------|------------------------------------------------------------------------------|-------------------|
| `--font-sans`   | `'Source Sans 3', -apple-system, BlinkMacSystemFont, system-ui, sans-serif`  | `pomdr.css:33`    |
| `--font-serif`  | `'Source Serif 4', 'Iowan Old Style', Palatino, serif`                       | `pomdr.css:34`    |

Both fonts are loaded from Google Fonts via `<link>` tags in each page's
`<head>`. The block theme should self-host these via `theme.json`'s
`settings.typography.fontFamilies` with `fontFace` source declarations,
not link out to Google Fonts at runtime (privacy + performance).

Source Sans 3 weights observed: 300, 400, 500, 600, 700.
Source Serif 4 weights observed: 300, 400, 500. Italic variants of the
serif are used heavily (hero title em, mission title em, marquee).

### 2.2 Base size and rhythm

| Property        | Value             | Source            |
|-----------------|-------------------|-------------------|
| body font-size  | `17px`            | `pomdr.css:56`, `styles.css:47` |
| body line-height| `1.6`             | `pomdr.css:57`, `styles.css:48` |
| body color      | `var(--ink)`      | `pomdr.css:55`, `styles.css:43` |
| body background | `var(--cream)`    | `pomdr.css:54`, `styles.css:44` |

Note: HANDOFF.md and the global CLAUDE.md require minimum 16px body text
for the older audience. 17px clears that bar.

### 2.3 Heading scale (clamp-driven, fluid)

| Class / use            | Size                          | Source            |
|------------------------|-------------------------------|-------------------|
| `.display`             | `clamp(48px, 6vw, 88px)`      | `pomdr.css:101`   |
| `.page-title`          | `clamp(44px, 5.5vw, 80px)`    | `pomdr.css:686`   |
| `.hero-title` (home)   | `clamp(42px, 6.2vw, 96px)`    | `styles.css:164`  |
| `.section-title`       | `clamp(34px, 4vw, 54px)` (pomdr.css) / `clamp(36px, 4.2vw, 58px)` (styles.css) | `pomdr.css:111`, `styles.css:312` |
| `.mission-title`       | `clamp(40px, 4.4vw, 64px)`    | `styles.css:489`  |
| `.cta-strip h2`        | `clamp(26px, 3.5vw, 44px)`    | `pomdr.css:630`   |
| `.quote-band blockquote` | `clamp(24px, 3.5vw, 42px)`  | `pomdr.css:642`   |
| `.events-feature h3`   | `44px`                        | `styles.css:665`  |
| `.dog-card .name`      | `26px`                        | `pomdr.css:884`, `styles.css:354` |
| `.program-card h3`     | `30px`                        | `styles.css:526`  |
| `.pillar h3`           | `42px`                        | `styles.css:447`  |

The block theme's `theme.json` should ship a smaller set of fluid sizes
covering display, h1, h2, h3, h4, body-large, body, small, with clamp
values approximating these. Pick the median per role; precise size
matching is less important than clamp behavior.

### 2.4 Tracking / letter-spacing

| Use                       | Value      | Source                       |
|---------------------------|------------|------------------------------|
| Display heading           | `-0.025em` | `pomdr.css:103`              |
| Section title             | `-0.02em`  | `pomdr.css:113`, `styles.css:313` |
| Hero title (home)         | `-0.035em` | `styles.css:166`             |
| Page title                | `-0.03em`  | `pomdr.css:689`              |
| Body                      | (default)  | unset                        |
| Eyebrow / uppercase label | `0.2em`    | `pomdr.css:76`, `styles.css:57` |
| Stat label uppercase      | `0.14em`   | `pomdr.css:595`              |

### 2.5 Eyebrow pattern

A recurring small-caps lead-in pattern. Defined at `pomdr.css:74-97` and
`styles.css:56-62`. Variants: default blue, `.purple`, `.orange`, `.light`
(blue-200 for dark backgrounds). Always uppercase, 12px, `0.2em` letter
spacing, with a 24×1px lead-in line.

---

## 3. Spacing

The CSS uses individual literal values rather than a strict scale token
set. Recurring values, in increasing order:

| Value | Frequency in CSS | Notable use                          |
|-------|------------------|--------------------------------------|
| 4px   | low              | Tab gap                              |
| 6px   | low              | Tag gap, dog card name margin        |
| 8px   | medium           | Filter row gap, button icon gap      |
| 10px  | high             | Logo gap, card list gap              |
| 12px  | high             | Footer link gap                      |
| 14px  | medium           | Common gap                           |
| 16px  | high             | Common padding                       |
| 18px  | medium           | Dog card name margin                 |
| 20px  | medium           | Card padding                         |
| 22px  | low              | Card padding                         |
| 24px  | high             | Card padding, grid gap               |
| 28px  | medium           | Form padding                         |
| 32px  | high             | Container padding, pillar padding    |
| 40px  | high             | Section gap                          |
| 60px  | high             | Marquee gap, section gap             |
| 80px  | high             | Section padding (small)              |
| 100px | medium           | Section padding (medium)             |
| 120px | high             | Section padding (large, observed in `.section`) |
| 140px | low              | Mission padding                      |

**Proposed `theme.json` spacing scale:**

`0`, `4`, `8`, `12`, `16`, `20`, `24`, `32`, `40`, `60`, `80`, `120` (px,
expressed as rem in theme.json). 12 stops covers the observed range
without losing fidelity. Add `spacingScale` for fluid block spacing.

---

## 4. Border radius

| Token name        | Value      | Source                          | Use |
|-------------------|------------|---------------------------------|-----|
| `--radius-sm`     | `14px`     | `pomdr.css:36`, `styles.css:27` | Photo card variant (minimal) |
| `--radius`        | `22px`     | `pomdr.css:37`, `styles.css:28` | Card default, dog photo, mobile drawer |
| `--radius-lg`     | `32px`     | `pomdr.css:38`, `styles.css:29` | Dog card outer, program card, pillar |
| `--radius-xl`     | `44px`     | `pomdr.css:39`, `styles.css:30` | Mission visual frame |
| (literal) `8px`   |            | `pomdr.css:341`                 | Dropdown icon |
| (literal) `12px`  |            | `pomdr.css:312`                 | Dropdown link |
| (literal) `14px`  |            | `pomdr.css:855` (badge)         | Same as `--radius-sm` |
| (literal) `18px`  |            | `pomdr.css:520`                 | Program icon container |
| (literal) `20px`  |            | `pomdr.css:269`                 | Dropdown menu, results card |
| (literal) `999px` | full pill  | many                            | Buttons, pills, nav, chips |

The pill (`999px`) is the dominant rounded shape (buttons, tags, badges).
Tokenize as `--radius-pill: 999px`.

---

## 5. Shadow

| Token name        | Value                                                                          | Source            |
|-------------------|--------------------------------------------------------------------------------|-------------------|
| `--shadow-sm`     | `0 2px 8px rgba(22,32,43,.04)` (pomdr) or `0 2px 8px rgba(22,32,43,.04), 0 1px 2px rgba(22,32,43,.03)` (styles) | `pomdr.css:41`, `styles.css:32` |
| `--shadow`        | `0 12px 32px -8px rgba(22,32,43,.12), 0 2px 6px rgba(22,32,43,.04)`            | `pomdr.css:42`, `styles.css:33` |
| `--shadow-lg`     | `0 30px 60px -20px rgba(22,32,43,.25)`                                         | `pomdr.css:43`, `styles.css:34` |
| `--shadow-nav`    | `0 8px 32px -12px rgba(22,32,43,.12)`                                          | `pomdr.css:44`    |

The `pomdr.css` `--shadow-sm` is shorter (one layer) than the `styles.css`
version (two layers). The `pomdr.css` version is the shared default; use it.

Per-component shadows (literal values in CSS):

- nav-cta primary: `0 6px 16px -4px rgba(0,139,176,0.4)` (pomdr.css:364)
- btn-primary:     `0 10px 24px -6px rgba(0,139,176,0.5)` (pomdr.css:452, styles.css:194)
- btn-purple:      `0 10px 24px -6px rgba(99,47,136,0.45)` (pomdr.css:459)
- btn-orange:      `0 10px 24px -6px rgba(192,107,0,0.45)` (pomdr.css:466)
- mission floats:  `var(--shadow-lg)` (styles.css:474)
- nav scrolled:    `0 12px 40px -12px rgba(22,32,43,.18)` (pomdr.css:156)

---

## 6. Motion

| Token name      | Value                                | Source            |
|-----------------|--------------------------------------|-------------------|
| `--ease`        | `cubic-bezier(0.2, 0.8, 0.2, 1)`     | `pomdr.css:46`, `styles.css:36` |

Common durations observed:

- `0.15s` (micro): input focus, dropdown link hover
- `0.18s` (dropdown reveal, `pomdr.css:280`)
- `0.2s`  (small): button color, nav link
- `0.25s` (small/medium): button transform
- `0.3s`  (medium): rotation, color shift
- `0.4s`  (large): card lift
- `0.8s`  (reveal-on-scroll, `pomdr.css:572`)
- `0.9s`  (homepage reveal, `styles.css:759`)
- `1.2s`  (hero slide cross-fade, `styles.css:117`)

Animation choreographies (purely homepage):

- `kenburns`: 10s default, 7s rich, 16s subtle (`styles.css:127`, motion variants `:753-756`)
- `marquee`: 40s default, 22s rich, 70s subtle (`styles.css:290`, motion variants `:753-756`)
- `fadeUp`: staggered 0.2/0.35/0.5/0.65/0.8s (`styles.css:155-214`)
- `fill`: 6s linear progress bar (`styles.css:245`)

All motion respects `prefers-reduced-motion` requirements per HANDOFF.md
accessibility budget. The current CSS does not gate animations on the
media query. The block theme must add `@media (prefers-reduced-motion:
reduce)` overrides.

---

## 7. Layout

| Property              | Value      | Source                    |
|-----------------------|------------|---------------------------|
| Container max-width   | `1320px`   | `pomdr.css:68`, `styles.css:64` |
| Container padding x   | `32px`     | `pomdr.css:68`, `styles.css:64` |
| Container padding x (mobile) | `20px` | `pomdr.css:69`, `styles.css:781` |
| Section padding y     | `100px` (pomdr) / `120px` (styles) | `pomdr.css:123`, `styles.css:306` |
| Section padding y small | `60px` (pomdr) / `80px` (styles) | `pomdr.css:124`, `styles.css:307` |

Breakpoints observed:

| Breakpoint       | Trigger                                |
|------------------|----------------------------------------|
| `1024px`         | Pillars/programs grids collapse, nav links hide on home (`styles.css:763`) |
| `1000px`         | Adopt page dogs grid → 2 columns (`pomdr.css:823`) |
| `960px`          | Nav links collapse to drawer (`pomdr.css:423`) |
| `900px`          | Footer collapses to 2 columns (`pomdr.css:993`) |
| `800px`          | Adopt policy block stacks (`pomdr.css:953`) |
| `700px`          | Page header padding reduces (`pomdr.css:711`) |
| `640px`          | Dogs grid → 1-2 cols, hero meta hides (`styles.css:773`, `pomdr.css:824`) |
| `580px`          | Footer 1 column, display font shrinks (`pomdr.css:997`) |
| `540px`          | Nav-cta hides (`pomdr.css:427`) |
| `480px`          | Logo srcset switches (`web-capture` evidence) |

Standardize to a smaller set in `theme.json`: `640`, `782`, `1024`, `1200`,
`1320` (use 1200 as the "wide" content width and 1320 as the page max).

---

## 8. Tokens proposed for `theme.json` but not in current CSS

These are additions necessitated by the block theme architecture, not by
visual change:

- A standardized custom property for `--love-red` (active heart) at
  `#e63946`, since the current CSS uses the literal hex inline.
- A standardized custom property for the warm cream accent
  (`--cream-warm: #f7ddb7`).
- Status semantic tokens for the dog status field (foreground + background
  pair per status):
  - `--status-available`: blue family
  - `--status-foster-needed`: purple family
  - `--status-foster-needed-dated`: purple family with date emphasis
  - `--status-adoption-pending`: orange family (already in pomdr.css as `badge.pending`)
  - `--status-recently-adopted`: ink-3 (muted)
  - `--status-hospice`: ink-2 with cream background
  - `--status-courtesy-listing`: ink-3 with cream-2 background

These map to the dog CPT's `status` field. Encode them in `theme.json`'s
`settings.custom` so blocks can pull them by token name.

---

## 9. Items pending review

1. Confirm Source Sans 3 and Source Serif 4 are the brand fonts per the
   `Brand Guidelines_december2025.pdf`. The global CLAUDE.md cites Myriad
   Pro as the brand font with free fallbacks like Source Sans 3 acceptable.
   The current design uses Source Sans 3 and Source Serif 4 directly, so
   either the brand guide endorses them, or the design substituted on
   pragmatic grounds.
2. Confirm the orange (`#C06B00`) is the canonical "pending" state color
   versus a third brand accent.
3. Confirm hover-state values for `btn-purple` (`#4b2268`) and `btn-orange`
   (`#a05800`) which are inline rather than tokenized.
4. Confirm that the homepage's `--purple-200` (`#A085B7`) should be
   declared globally, or whether it's homepage-only by intent.
