# POMDR Website — UI/UX Fix Spec

> Received from Andrew 2026-07-09, from his meeting with the POMDR leadership
> team. Saved verbatim (including the em dashes of the original; this is an
> external source document, not site copy). The working punch list and status
> tracking live at the bottom in the ADDENDUM, added by the implementation
> session.

**Purpose:** This document is a punch list for Claude Code (running in Antigravity) to work through on the `new.pomdr.org` WordPress/Divi site. Each item is scoped, ordered by dependency, and written so it can be handed to Claude Code as-is or broken into individual prompts.

**Before starting:** Read `CLAUDE.md`, `STACK.md`, `VOICE.md`, and `DESIGN-TOKENS.md` at the repo root first. This doc extends those — if anything here conflicts with `DESIGN-TOKENS.md`, update the token file to match this doc and treat the token file as the source of truth going forward.

---

## 0. Global Design Tokens (do this first — everything else depends on it)

Establish these as CSS custom properties / Divi global colors before touching individual pages, so every subsequent fix references the same tokens instead of hardcoded hex values.

| Token | Value | Usage |
|---|---|---|
| `--color-primary-purple` | `#632E88` (confirm against existing brand var, may be `#632F88`) | Primary button fill, headings |
| `--color-primary-blue` | `#008DAF` | Primary button fill (alt), links, accents |
| `--color-bg-light` | light blue tint in-family with brand blue, e.g. `#EAF6F9` (confirm exact hex with design) | Section backgrounds — **replaces every off-white/tan/beige background site-wide** |
| `--color-button-outline` | `#FFFFFF` | Secondary/outline button border + text — **dark backgrounds only** (see button rule below) |

**Action items:**
- [ ] Audit the site for every instance of tan/beige/off-white section backgrounds. Replace each one with `--color-bg-light` (a light blue tint from the brand's blue family, not a generic gray or off-white). If a given section's existing off-white genuinely needs to stay neutral for contrast reasons, flag it back to Andrew instead of guessing — default assumption is it becomes light blue.
- [ ] Audit all buttons site-wide and consolidate to exactly two variants (see Section 1), applying the light/dark background rule below to choose which variant goes where.
- [ ] Remove any black bars/black background sections (likely Divi row settings with a black background color, or a spacer module with a dark background) — find and replace with `--color-bg-light` or white, whichever fits the section.

---

## 1. Button System (standardize across entire site)

Two button variants only. **Which one to use is decided by the background the button sits on — not by preference or page.**

### The rule
- **Button sits on a dark or busy background** (a photo, an image overlay, a colored hero section, `--color-primary-purple`, `--color-primary-blue`, or anything where a solid purple/blue button would lack contrast or disappear) → use the **white outline button**.
- **Button sits on a light background** (white, `--color-bg-light`, or any other light neutral section) → use the **solid purple or blue button**. Never use the white outline button on a light background — it won't have enough contrast and reads as invisible/broken.

This is a contrast rule, not a style preference — apply it consistently by checking the actual background color behind each button, not by copying whatever variant a nearby button happens to use.

### Primary button (light backgrounds)
- Background: `--color-primary-purple` or `--color-primary-blue` (pick one as the site default; use the other only for deliberate secondary emphasis within the same page, not randomly)
- Text: white, **ALL CAPS**
- Border: none
- Hover: slight darken, or swap to the alternate brand color

### White outline button (dark/photo backgrounds only)
- Background: transparent
- Border: 2px solid white (`--color-button-outline`)
- Text: white, **ALL CAPS**
- Hover: fills white background, text becomes purple or blue
- **Do not use this variant on light backgrounds under any circumstance.**

**Global button text rule:** all button labels site-wide → ALL CAPS (e.g. "ADOPT NOW", "LEARN MORE", "APPLY NOW"). Apply via CSS `text-transform: uppercase` on the button class rather than retyping every label, so it stays consistent automatically.

**Action items:**
- [ ] Find every button component/class in the Divi theme + custom CSS.
- [ ] For each one, check the background it sits on and assign the correct variant per the rule above (don't assume — check each instance, since Divi sections can have inconsistent backgrounds even within one page).
- [ ] Collapse to the two variants above; remove any third/fourth button style found along the way.
- [ ] Apply `text-transform: uppercase` globally to button classes.
- [ ] Remove one-off inline button styling wherever found.

---

## 2. Front Page

- [ ] Add a clear **mission statement** block near the top of the front page (short — 2-3 sentences, matches VOICE.md tone). Confirm exact copy with Carie/Cameron if not already written; if a mission statement already exists in site copy or LGL materials, reuse it rather than writing new.
- [ ] Confirm front-page dog cards are the canonical style — this is the style Section 3 (Adoption Cards) needs to match.

---

## 3. Adoption Page — Dog Cards

- [ ] **Match front-page card style exactly**: same border radius, shadow, spacing, hover state, image treatment.
- [ ] Cards get the standardized button system from Section 1 (likely primary button: "VIEW [NAME]" or "LEARN MORE").
- [ ] **Remove the "Available" filter entirely** from the adoption page filter UI — not just hide it, remove the filter option and any associated logic/query param so it can't be selected. Confirm with Andrew whether "Available" as a default *state* (i.e., only showing available dogs by default) should remain — this item is about removing the *filter control*, not necessarily changing what dogs display by default. Flag this distinction back to Andrew before removing any underlying query logic.
- [ ] **Improve sorting options**: current sort needs expansion. Candidates to confirm: by name (A-Z), by weight (asc/desc), by age (asc/desc), newest listed. Confirm final list before building — don't guess at options not already discussed.

---

## 4. Individual Dog / Application Flow

- [ ] Each dog listing needs a **"Fill Out Application"** button — **no phone number listed** as the primary CTA. Phone contact should not be the default path to applying.
- [ ] Each dog card/page gets **3-5 bullet points only** (not a wall of text) covering the key things a potential adopter needs to know about that dog. Keep this tight — this is a hard cap, not a suggestion. (Note: this may need a "Helping Paw" content field/template — confirm what "Helping Paw" refers to in the current data model before building; if it's an existing ACF field or ShelterLuv field, map to that rather than creating a new one.)

---

## 5. Calendar / Events

- [ ] Calendar should have a **"quick view"** — a lightweight way to see what's happening without a full page load/modal (e.g., hover preview or condensed list view).
- [ ] **Fix calendar bug**: something is currently broken in "what's happening" — needs reproduction steps from Andrew before Claude Code can diagnose. *(Flag: get exact repro steps — what page, what action, what's expected vs. what happens — before attempting a fix.)*
- [ ] **Add "Events" to the main nav bar** — currently not present or not easily found.
- [ ] **Redo event cards** to match the standardized card style (Section 3) and use the standardized button system (Section 1) — event cards should look like siblings of the dog cards, not a different component.

---

## 6. Cross-Browser

- [ ] **Safari renders the site smaller than Chrome.** Likely causes to check first: missing `-webkit-` prefixes, viewport meta tag issues, rem/em vs px inconsistencies, or a Safari-specific zoom/scale bug in a Divi module. Reproduce on Safari (desktop) at standard zoom before making changes, and confirm the fix visually in both browsers side by side.

---

## 7. Final Pass

- [ ] Full **UI review** — click through every page, confirm cards, buttons, and backgrounds are consistent site-wide per Sections 0-1.
- [ ] Full **text/copy review** — confirm no leftover tan backgrounds, no lowercase buttons, no black bars, mission statement reads well, dog bullet points respect the 3-5 cap.

---

## Open Questions to Resolve Before/During Build

These items in the original notes need a decision from Andrew before Claude Code should proceed on that specific piece — everything else can be built straight through:

1. Exact hex for the new light blue background (needs to be light enough to sit behind body text and dark-colored buttons without contrast issues, but visibly blue rather than reading as white).
2. Which color (purple or blue) is the site-wide default primary button color vs. which is used for secondary emphasis.
3. Whether "remove Available filter" also means changing default dog visibility, or just removing the UI control.
4. Final sort field list for the adoption page.
5. What "Helping Paw" refers to in the data model (existing field vs. new).
6. Repro steps for the calendar bug.
7. Mission statement copy (if not already finalized elsewhere).

---

---

# ADDENDUM (implementation session, 2026-07-09)

Status mapping against the current mirror, maintained by the build sessions.

## Already done in recent sessions (before this spec arrived)

- **§2 mission statement**: the homepage has an OUR MISSION section with the
  real mission copy ("Who will care for your dog if you no longer can...").
  Position/copy review still worthwhile against the bosses' intent.
- **§3 card match**: the adopt page and homepage render the SAME component
  (`pom_render_dog_card`), so they match by construction.
- **§4 application button**: every dog page has an "Adopt {Name}" button
  straight to the application with the name prefilled; phone is not the
  primary CTA anywhere in the flow.
- **§5 event cards**: redesigned to the design system (uniform frame, whole
  flyer visible) in the events-upgrade PR.

## Done 2026-07-09 (per Andrew's answers: #E8F2F6, blue primary, caps tuned, chip gone)

- **§0 backgrounds**: the warm cream token family became the brand light blue
  (`--cream` #E8F2F6, `--cream-2` #D9E9F0 for wells, `--line` #cfe0e9 cool
  hairline) in both token files; the whole site flipped at once because every
  page reads the vars. DESIGN-TOKENS.md updated as source of truth.
  **Black bars: none found** in theme code or published Divi content (searched
  both); likely a memory of the old live site. Flag a URL if one reappears.
- **§1 buttons**: two variants sitewide. Solid = blue primary
  (`--blue-700`), purple for deliberate emphasis (`.btn-purple`, and
  `.btn-outline` recast as solid purple since outline-on-light is banned).
  White outline (2px, hover fills white with purple text) =
  `.btn-ghost`/`.btn-light`/`.btn-white`, verified used only on dark bands
  (one violation found on the light newsletter band, converted to solid).
  ALL CAPS via text-transform on every button class, tuned for seniors
  (0.04em letterspacing, labels at 17px or larger).
- **§3 Available bubble removed everywhere**: the adopt-page filter chip is
  deleted (All / Foster Needed / Sponsor Needed / Adoption Pending remain) and
  plain-Adoptable dogs no longer carry a card badge anywhere; special-status
  badges (Foster Needed, Hospice, Adopted...) stay. All adoptable dogs still
  display by default.
- **§5 Events added to the main nav** (secondary row + mobile drawer).

## Still owed by Andrew (the spec's remaining open questions)

- Final sort-field list for the adoption page (§3).
- What "Helping Paw" refers to in the data model for the 3-5 bullet template (§4).
- Calendar bug repro steps (§5 quick view will be designed alongside the fix).
- Mission statement copy sign-off (§2; the current mission block is live).
- Safari sizing repro (§6): needs a side-by-side on Andrew's machine; nothing
  reproducible in Chrome-only tooling here.
