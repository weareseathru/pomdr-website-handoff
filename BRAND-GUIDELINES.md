# POMDR Brand and Experience Guidelines

Peace of Mind Dog Rescue (POMDR). The living guide for how the website looks,
sounds, and behaves. The north star is simple: a warm, calm site that an older
adopter or a senior dog owner in a stressful moment can use easily, find what
they need quickly, and trust. Accessibility and clarity are not a layer we add
at the end. They are the brief.

This document pairs with `DESIGN-TOKENS.md` (the exact values) and `CLAUDE.md`
(the standing project rules). Where they disagree, `CLAUDE.md` wins and this
file is updated.

No em dashes anywhere, including in this document. Use commas, periods, or
parentheses.

---

## 1. Who we design for

We design for the real person, not the org chart:

- An older adopter who wants a calm companion and may find dense, small, or
  cluttered pages tiring.
- A senior dog owner in a hard moment (a move, an illness, a loss) who needs
  help fast and gently.
- A volunteer or donor who wants one clear way to act.

Every page is framed with a "How Might We" and given one clear job. If a page is
trying to do three things, it is three pages.

---

## 2. Accessibility commitments (non-negotiable)

Target: WCAG 2.2 AA, and a little beyond it for an older audience.

- **Body text is at least 16px.** The site base is 18px.
- **Color contrast** is at least 4.5:1 for body text and 3:1 for large text
  (24px or 19px bold). Use the accessible token variants for small text:
  `--blue-text` and `--orange-text`. The lighter `--blue` (`#008bb0`) is for
  fills and large display only, never for small body text on light backgrounds.
- **Text on photos and dark panels is always readable.** Light text on dark,
  dark text on light. When text sits over an image, add a scrim, a gradient, or
  a text shadow so it stays legible over the brightest part of the photo. The
  hero title and the three pillar cards follow this rule.
- **Buttons and calls to action read clearly.** A button is a solid, high
  contrast shape with a clear label, not a faint tint. If it is the primary
  action, it is the most prominent thing in its area.
- **Visible focus.** Every interactive element shows a thick, high contrast
  focus ring (`--focus-w` / `--focus-color`). No element receives focus
  silently.
- **Touch targets** are at least 44px, 48px where space allows.
- **Motion is optional.** All animation (hero rotator, scroll reveals, the paw
  trail) respects `prefers-reduced-motion`. With reduced motion on, content
  appears in its final state with no movement.
- **No hover-only and no time-based traps.** Anything reachable by hover is also
  reachable by tap and keyboard. Nothing important disappears on a timer.
- **Icons are paired with visible text.** An icon alone is never the label.
- **One `<main>`, clean heading order, real landmarks**, descriptive alt text on
  meaningful images, and `alt=""` plus `role="presentation"` on decorative ones.

### The "Larger text" toggle

A single floating control sits in the bottom right of every page. It does one
predictable thing: it makes the text about 4px larger across the whole site, the
navigation included. It does not resize buttons, icons, spacing, or layout, and
turning it off returns everything to normal. The control is labeled with visible
text ("Larger text"), announces its state, and remembers the choice.

Keep it that way. If we ever want a separate spacing or contrast option, it is a
separate, clearly labeled control, not bolted onto this one.

---

## 3. Findability: never bury an action

The legacy site hid applications (foster, Helping Paw, volunteer, surrender)
inside paragraphs. We do the opposite:

- **Surface every primary action as a real button**, high contrast and at least
  44px, placed ABOVE the explanatory text and repeated at the end. A quick user
  can act in one tap. A careful reader can still read the whole story. A link
  inside a sentence is never the only path to an action.
- **One primary action per page.** Secondary actions are visibly secondary.
- **Predictable navigation.** The header carries the main destinations and a
  single Donate call to action. The footer is the full map: Adopt, Get Involved,
  About, plus identity and contact. A person should never wonder where a thing
  lives.
- **Plain, scannable structure.** Short sections, clear headings, generous
  space. White space is a feature, not wasted room.

---

## 4. Usability and craft

- **Ruthless simplicity.** Every element earns its place. Calm hierarchy,
  generous spacing, beauty in service of clarity.
- **Progressive enhancement.** The core experience works without JavaScript.
  Enhancements (carousels, reveals, the paw trail) are decoration on top of
  content that already stands on its own.
- **Performance is part of usability.** Images are optimized (WebP with
  fallbacks), motion is GPU friendly (transform, opacity, filter only), and we
  hold a 60fps bar. Targets: LCP under 2.5s, INP under 200ms, CLS under 0.1.
- **Consistency.** Reuse the shared components and tokens. A card, a button, a
  badge looks and behaves the same everywhere.

---

## 5. Voice and copy

Warm, concise, personality forward. We talk like a kind neighbor, not a
brochure.

- **No em dashes, ever.** Commas, periods, or parentheses.
- Do not open dog copy with "Meet [Name],".
- Do not close with a rhetorical question ("Could you be his person?").
- Use `~age` (tilde, no space) for approximate ages. Never "about,"
  "estimated," or "approximately."
- Do not promise outcomes ("she will love you," "guaranteed forever"). Suggest,
  do not guarantee.
- Natural emoji is fine when it fits. No emoji stuffing.
- Human sign off is "Andrew Z." when an author is credited.

The voice rules are enforced by `scripts/check-voice.sh` (run it `--all` or
`--staged`). It checks user-facing copy, not code comments.

---

## 6. Color

Brand palette (full values in `DESIGN-TOKENS.md`):

- **Teal / blue** primary: `--blue` `#008bb0`, with `--blue-700` `#006c8a` and
  `--blue-900` `#004e63` for text, hovers, and dark panels. Pastels
  (`--blue-50` through `--blue-200`) for tints and surfaces.
- **Purple** secondary: `--purple` `#632F88`, with `--purple-400` and the
  `--purple-50` to `--purple-200` pastels.
- **Orange** is a state and accent color (`--orange` `#C06B00`, `--orange-text`
  `#a05800` for small text), not a third brand hue.
- **Cream and white** are the default backgrounds. Avoid heavy dark-mode
  inversions.

Contrast first. Small text uses the `-text` token variants. New colors (for
states or tags) are proposed against the palette before use.

---

## 7. Typography

- Brand font is Myriad Pro. On the web we use **Source Sans 3** (sans) and
  **Source Serif 4** (serif) as the licensed, widely available stand ins.
- Body text 18px minimum, line height around 1.6, readable line length capped
  near 70 characters for prose.
- Headings use fluid `clamp()` sizes so they scale smoothly without jumping at
  breakpoints. The serif italic is the warm accent voice (hero title, section
  titles, pull quotes).

---

## 8. Motion and the paw trail

Motion is gentle and earns its place. The decorative watercolor paw trail on the
home page is the house style for motion graphics: it is purely decorative
(behind content, `aria-hidden`, `pointer-events:none`), it never affects layout
or scroll length, it animates only transform, opacity, and filter, and it
renders as a static finished trail under reduced motion. Any future motion piece
follows the same contract.

---

## 9. Organizational facts (safe to use as is)

- Name: Peace of Mind Dog Rescue (POMDR). Founded October 2009.
- Tax ID / EIN: 27-1154816 (501(c)(3) nonprofit).
- Main address: Patricia J. Bauer Center, 615 Forest Ave, Pacific Grove, CA
  93950.
- Vet clinic: Boand Veterinary Clinic, 1251 10th St, Monterey, CA.
- Benefit Shop: 223 Grand Ave, Pacific Grove, CA.
- Contact: info@pomdr.org, (831) 718-9122.
- Focus: senior dogs and senior people on California's Central Coast.

---

## 10. The short version

If you remember five things:

1. Make it easy to read (size, contrast, light on dark, dark on light).
2. Make the main action an obvious button, never buried in text.
3. One clear job per page.
4. Motion and decoration are optional and never get in the way.
5. Warm, plain words. No em dashes.

_Owner: Andrew Z. Living document. Update it when reality changes._
