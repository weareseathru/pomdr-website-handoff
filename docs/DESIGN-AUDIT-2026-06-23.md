# POMDR Design Audit, 2026-06-23

Owner: Andrew Z. Author: Claude (implementation partner).
Method: Stanford d.school / IDEO / Whipsaw practice (per CLAUDE.md) plus the
ui-ux-pro-max design-intelligence skill and the sequential-thinking MCP for the
palette-consolidation reasoning.

Status: proposal pending Andrew's sign-off. No site-wide token rewrite has been
applied yet. The only file changed in this pass is
`pomdr-visual-brand-guide.html` (see Section 7).

---

## 1. The decision that triggered this audit

Direction from Andrew (2026-06-23): collapse the site to a **two-color system**.

- **Brand Blue `#008bb0`** carries primary actions and important headlines.
  Blue also means **"available to adopt."**
- **Brand Purple `#632F88`** carries accents and secondary states.
  Purple also means **"foster needed."**
- **White, warm off-white (cream), and ink** are the neutral layer.
- Anything else is a **tint or shade** of those two colors. No other hues.

Two clarifications resolved this session:

1. **Neutrals stay warm.** Cream (`#FAF6F0` / `#F3ECDF`) and the ink grays remain
   the neutral layer, so the "warm, fun" brand feel survives. Cream is treated as
   a warm off-white neutral, not a third brand hue.
2. **Hex value.** Andrew wrote `#632E88`; the canonical purple in CLAUDE.md and
   `tokens.css` is `#632F88` (a one-digit, visually identical difference). We
   standardize on `#632F88`.

This audit also notes a hard constraint that shapes the palette: **white text on
`#008bb0` is only 3.95:1**, which fails the WCAG 2.2 AA 4.5:1 floor that CLAUDE.md
enforces. So `#008bb0` is correct for large headlines (large text needs only
3:1) but button fills must use the next shade down, `#006c8a` (≈4.6:1). This is
already the convention documented in `tokens.css:19-21` (`--blue-text`).

---

## 2. What the site looks like today (the problem)

A scan of every prototype CSS and HTML file found **40+ distinct hex colors**,
far beyond blue and purple. They fall into families:

| Family | Example values | Where used |
| --- | --- | --- |
| Greens | `#1F5A35`, `#2E8B57`, `#1A7A40`, `#E8F5EE` | "Available" dog status (badge + dot + CTA) |
| Oranges | `#C06B00`, `#6B3D00`, `#FFF5E8` | "Foster needed" status, warnings, eyebrows |
| Reds | `#E63946`, `#B03060`, `#8B3A2B` | Occasional paw-trail accent paws |
| Browns / tans / peach | `#F7DDB7`, `#D4896A`, `#8B5A2B`, `#3E2D1F`, `#E8D5C4` | Newsletter band, warm section theming |
| Slate blues | `#2B4A5E`, `#6A8FA0`, `#B8C9D4` | Section theming |
| On-palette | blues `#008bb0`/`#006c8a`/`#004e63`, purples `#632F88` family | Buttons, headings, accents |

The off-palette colors do real jobs (mostly **status differentiation** and
**decorative section theming**), so consolidation is a remapping exercise, not a
simple find-and-replace.

---

## 3. The new palette (single source of truth)

These tokens live in `pomdr-website/project/tokens.css` (and mirror into
`divi-child-integration/assets/css/`). Proposed end state:

### Brand hues

| Token | Value | Role |
| --- | --- | --- |
| `--blue` | `#008bb0` | Brand blue. Large headlines, "available" signal. (Large text only; 3:1.) |
| `--blue-700` (`--blue-text`) | `#006c8a` | **Button fills, small blue text, links.** Clears 4.5:1 on white. |
| `--blue-900` | `#004e63` | Deepest blue. Footers, focus ring, "adopted" state. |
| `--blue-400` | `#26A1BF` | Accent dots, hovers. |
| `--blue-200/100/50` | `#7CB9D0` / `#BCD7E4` / `#E8F2F6` | Tints for status backgrounds, surfaces. |
| `--purple` | `#632F88` | Brand purple. Accents, "foster needed" signal. |
| `--purple-700` | `#4B2268` | Deep purple. "Adoption pending", emphasis. |
| `--purple-400` | `#7F5A9D` | "Sponsor needed" dot, accent. |
| `--purple-200/100/50` | `#A085B7` / `#C9BBD7` / `#F1EBF5` | Tints for status backgrounds, surfaces. |

### Neutrals (kept warm, per Andrew)

| Token | Value | Role |
| --- | --- | --- |
| `--white` | `#ffffff` | Default background. |
| `--cream` / `--cream-2` | `#FAF6F0` / `#F3ECDF` | Warm off-white section backgrounds. |
| `--ink` / `--ink-2` / `--ink-3` | `#16202b` / `#3c4a57` / `#5a6773` | Body text, secondary text, muted. |
| `--line` | `#e5ddd0` | Hairline borders. |

### Tokens to delete

`--orange`, `--orange-50`, `--orange-text`, plus every inline green
(`#1F5A35`, `#2E8B57`, `#1A7A40`, `#E8F5EE`), red, brown, peach, and slate-blue
literal. Replace per Section 4 and 5.

---

## 4. Color migration map (off-palette to replacement)

| Old (family) | Old job | New token |
| --- | --- | --- |
| Greens (`#E8F5EE` bg, `#1F5A35` text, `#2E8B57` dot) | "Available" status | `--blue-50` bg, `--blue-900` text, `--blue` dot |
| Oranges (`--orange`, `#6B3D00`, `--orange-50`) | "Foster needed" status, warnings | `--purple` / `--purple-50` / `--purple-700` text |
| Peach / tan (`#F7DDB7`, `#E8D5C4`, `#D4896A`) | Newsletter band, warm theming | `--cream-2` surface; accent text in `--blue-200` or `--purple-100` |
| Browns (`#8B5A2B`, `#3E2D1F`, `#9C8670`) | Earthy section theming | `--ink` / `--ink-2` on `--cream` |
| Slate blues (`#2B4A5E`, `#6A8FA0`, `#B8C9D4`) | Section theming | `--blue-900` / `--blue-400` / `--blue-100` |
| Reds (`#E63946`, `#B03060`) | Paw-trail accent paws | `--purple` and `--blue` (alternate the two for variety) |

Note on the newsletter band (`styles.css:591-594`): peach text `#f7ddb7` sits on
a dark background; verify its replacement (`--blue-200` or `--purple-100`) still
clears 4.5:1 against that specific background before shipping.

---

## 5. Status system redesign (blue = available, purple = foster)

The 7 ACF statuses must stay distinguishable with only two hues. The
`.dog-status` component already renders a **colored dot + a text label/CTA**, so
**color is never the only signal** (WCAG `color-not-only` satisfied). That lets
several statuses share a hue family, differentiated by tint/shade + the label.

| Status (stored value) | Hue | Background | Dot / accent | Text |
| --- | --- | --- | --- | --- |
| `Adoptable` (available) | **Blue** | `--blue-50` | `--blue` | `--blue-900` |
| `Foster Needed` | **Purple** | `--purple-50` | `--purple` | `--purple-700` |
| `Sponsor Needed` | Purple (light) | `--purple-50` | `--purple-400` | `--purple-700` |
| `Adoption Pending` | Purple (deep) | `--purple-50` | `--purple-700` | `--purple-700` |
| `Adopted` (happy tail) | Blue (deep) | `--blue-50` | `--blue-900` | `--blue-900` |
| `Hospice` | Purple (muted) | `--purple-50` | `--purple-200` | `--purple-700` |
| `Courtesy Listing` | Neutral | `--cream-2` | `--ink-3` | `--ink` |

Because available/adopted both read blue and four states read purple, the **dot,
the label text, and (recommended) a small Lucide icon per status** carry the real
differentiation. Confirm each text-on-tint pair clears 4.5:1 (the purple-700 on
purple-50 and blue-900 on blue-50 pairs do; spot-check the dots at 3:1).

Files to touch: `pomdr.css:876-885` (badges) and `pomdr.css:1159-1183`
(dog-status strip), plus the `foster_needed`/`foster_needed_dated` dot rules at
`pomdr.css:1165-1168`.

---

## 6. Contrast findings (WCAG 2.2 AA, computed)

| Pair | Ratio | Verdict | Action |
| --- | --- | --- | --- |
| White on `#008bb0` (brand blue) | 3.95:1 | Fails normal text; passes large (3:1) | Buttons + small text use `#006c8a`; keep `#008bb0` for headings only |
| White on `#006c8a` (blue-700) | ≈4.6:1 | Passes AA | Use for all button fills |
| White on `#C06B00` (orange) | 3.93:1 | Fails | Removed entirely |
| `#008bb0` eyebrow at 14px | ≈3.3:1 | Fails | Use `--blue-700` and 15px+ (fixed in brand guide) |

The prototype's existing baseline is otherwise strong: reduced-motion blocks in
`a11y.css`, `pomdr.css`, `paw-trail.css`; 15 responsive `@media` queries in
`pomdr.css`; `:focus-visible` rules; and a `--tap-min: 44px` token. No regressions
expected from the palette change on those fronts.

---

## 7. Brand guide updates already applied

`pomdr-visual-brand-guide.html` was brought onto the new system this session:

- Removed all orange (token, badge, obstacle cards, "don't" rule cards, swatch).
- Repurposed those to purple; added a "Blue 700 (Buttons)" swatch.
- Button fills now `--blue-700` (AA compliant); eyebrow now `--blue-700` at 15px.
- Removed 3 em dashes (CLAUDE.md hard rule), lines 455 and 482.
- Added a mobile breakpoint (collapses the 2-column grids at ≤768px) and a
  `prefers-reduced-motion` block (neither existed before).
- Made the tabs an accessible tab pattern (`role="tablist"/"tab"/"tabpanel"`,
  `aria-selected`, `aria-controls`) and fixed the deprecated global `event`.
- Added a palette explainer paragraph to the Color System section.

Verified: 0 em dashes, 0 orange references, no off-palette hex remaining.

---

## 8. Implementation plan (when approved)

Token-first, so one change cascades:

1. **`tokens.css`**: delete orange tokens; confirm blue/purple ramps complete.
2. **`pomdr.css`**: remap badge + dog-status rules (Sections 4-5); replace inline
   green/orange literals with tokens.
3. **`styles.css`**: replace peach/brown/slate section theming with cream + token
   accents; fix the newsletter band contrast.
4. **`paw-trail.css`**: recolor accent paws to alternate blue/purple.
5. **`divi-child-integration/assets/css/`**: mirror token changes so the live
   Divi child theme matches.
6. **Quality gates** (CLAUDE.md): axe-core scan, Lighthouse (no CWV regression),
   Playwright visual diff of affected pages, contrast re-check on every new pair.

Estimated blast radius: 5 CSS files drive ~40 HTML pages, so the per-page HTML
should need little or no change once tokens and component rules update.

---

## 9. Open decisions for Andrew

1. **Confirm the status hue assignments in Section 5** (especially
   available-vs-adopted both being blue, and four purple variants). The labels +
   dots disambiguate, but it is a UX trade worth your eye.
2. **Paw-trail variety**: alternate blue/purple acceptable, or single-hue?
3. **Go-ahead to apply the token rewrite** across the 5 CSS files on a
   `design/two-color-palette` branch for review.
