# POMDR Design System — MASTER (Source of Truth)

Generated 2026-06-23 by reconciling the `ui-ux-pro-max` skill against the live
theme. **Tokens below are pulled from the real theme, not the skill's generic
defaults.** When the skill and this file disagree, this file and CLAUDE.md win.

Authoritative token source: `divi-child-integration/assets/css/pomdr-design.css`
(`:root` block). A11y overrides: `divi-child-integration/assets/css/a11y.css`.

Hierarchical retrieval: before building a page, check
`design-system/pages/<page>.md`. If it exists, its rules override this file. If
not, use this file exclusively.

---

## 1. Pattern (page structure)

- **Pattern:** Trust & Authority + Accessible.
- **Primary action above the fold, always.** Every page surfaces its one
  primary action (adopt, foster, donate, volunteer, Helping Paw, surrender) as
  a real high-contrast button ≥44px, above the explanatory prose, repeated at
  the end. A link inside a sentence is never the only path. (CLAUDE.md §4.)
- **One primary CTA per page.** Secondary actions are visually subordinate.
- **Section flow:** Hero (with primary CTA) → supporting story / proof →
  repeated CTA.

## 2. Color tokens (canonical — two-color + warm neutrals)

| Role | Token | Hex | Notes |
|------|-------|-----|-------|
| Blue (primary / "available") | `--blue` | `#008bb0` | Large UI only; fails 4.5:1 for small text |
| Blue text/link (AA) | `--blue-700` / `--blue-text` | `#006c8a` | 5.98:1 — use for small text, links, button fills |
| Blue deep | `--blue-900` | `#004e63` | |
| Blue tints | `--blue-50/100/200/400` | `#E8F2F6` … `#26A1BF` | backgrounds, borders |
| Purple (secondary / "foster") | `--purple` | `#632F88` | |
| Purple tints | `--purple-400/100/50` | `#7F5A9D` / `#C9BBD7` / `#F1EBF5` | |
| Orange (accent) | `--orange` | `#C06B00` | use `--orange-text #a05800` for small text |
| Ink (body text) | `--ink` | `#16202b` | primary text on light |
| Ink secondary/tertiary | `--ink-2` / `--ink-3` | `#3c4a57` / `#6b7885` | |
| Cream surfaces | `--cream` / `--cream-2` | `#FAF6F0` / `#F3ECDF` | warm backgrounds |
| Line | `--line` | `#e5ddd0` | dividers, borders |
| White | `--white` | `#ffffff` | default background |

Rules: white backgrounds are default; no heavy dark-mode inversion. Never put
small text on raw `--blue` — use `--blue-text`. Functional color always pairs
with an icon or label (never color-only meaning). New colors get proposed
against this palette before use. (CLAUDE.md §2; see
`docs/DESIGN-AUDIT-2026-06-23.md`.)

## 3. Typography

- **Body:** `--font-sans` → `'Source Sans 3', -apple-system, system-ui, sans-serif`.
- **Headings:** `--font-serif` → `'Source Serif 4', 'Iowan Old Style', Palatino, serif`.
- Myriad Pro is the brand font; Source Sans 3 is the documented free web
  fallback (CLAUDE.md §2).
- Base body text `--fs-base: 18px` (senior-readable; never below 16px).
  Line-height `--leading: 1.6`. Max measure `--measure: 70ch`.
- Weight for hierarchy: serif headings, regular body (400), medium labels.

## 4. Effects & motion

- Radius scale: `--radius-sm 14px` / `--radius 22px` / `--radius-lg 32px` / `--radius-xl 44px`.
- Shadow scale: `--shadow-sm` / `--shadow` / `--shadow-lg` / `--shadow-nav`
  (use the scale; no ad-hoc shadow values).
- Easing: `--ease: cubic-bezier(0.2, 0.8, 0.2, 1)`. Micro-interactions
  150–300ms. No hover-only functionality (senior audience). Respect
  `prefers-reduced-motion`. No continuous decorative animation.

## 5. Accessibility (WCAG 2.2 AA minimum, senior-first)

- `--tap-min: 44px` interactive targets (senior mode raises to 48px).
- `--focus-w: 3px` visible focus rings on every interactive element; never
  remove focus outline without a stronger replacement.
- Body contrast ≥4.5:1; large UI glyphs ≥3:1. Verified pairs only.
- Skip-to-content link on nav-heavy pages. Sequential heading hierarchy.
- Form fields: visible labels (not placeholder-only), errors below the field,
  required marked, recovery path in every error.
- No time-based interactions. Support text scaling without layout breakage.

## 6. Avoid (anti-patterns)

- Em dashes anywhere user-facing (CLAUDE.md §2 — absolute).
- "Meet [Name]," openers or question-closers in dog copy.
- Emoji as structural/navigation icons (use SVG: Heroicons/Lucide).
- Small (<16px) body text; gray-on-gray; raw hex in new components (use tokens).
- Hover-only reveals; instant 0ms state changes; layout-shifting press states.
- AI purple/pink gradients; generic blue/green palettes from design tools.

## 7. Pre-delivery checklist

- [ ] Primary action is a button ≥44px above the fold, repeated at the end
- [ ] One primary CTA; secondary actions subordinate
- [ ] Tokens used (no raw hex); palette limited to blue/purple/orange + neutrals
- [ ] Small text/links use `--blue-text`, not `--blue`
- [ ] Body ≥16px (theme default 18px); contrast 4.5:1 verified
- [ ] Visible focus rings; full keyboard nav; skip link present
- [ ] `prefers-reduced-motion` respected; no hover-only functionality
- [ ] No emoji icons; SVG icon set, consistent stroke
- [ ] No em dashes in any user-facing copy
- [ ] Responsive at 375 / 768 / 1024 / 1440; no horizontal scroll
- [ ] axe-core clean (zero new violations) + Lighthouse no regression >3pts
