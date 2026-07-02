# CLAUDE.md: POMDR WordPress Redesign

Orientation and standing rules for Claude Code on the Peace of Mind Dog Rescue
(POMDR) website redesign. Read this file at the start of every session. It is the
source of truth. When it is wrong or silent on something, flag it and propose the
fix; do not act on the ambiguity. Deeper detail lives in the docs referenced in
section 8; keep this file dense.

---

## 1. Project summary

POMDR is a Pacific Grove, California nonprofit (founded 2009, EIN 27-1154816)
serving senior dogs and senior people. This project redesigns its public website
into a warm, modern, highly accessible site (WCAG 2.2 AA minimum) that makes it
easier to adopt, foster, donate, and volunteer, surfaces individual dogs with
character, and stays fully editable by non-technical staff in wp-admin. The
redesign ships as a Divi child theme overlaid on the existing WordPress install.
It does not replace WordPress or Divi, and it preserves the working content
model, forms, and redirects. Andrew Z. is the sole developer in the loop; Claude
is the implementation partner, not an autonomous agent.

---

## 2. Session protocol

<!--
  PENDING (2026-07-02): Andrew to paste the canonical Session protocol block
  here, verbatim. This placeholder is intentional. Replace it with the block as
  provided; do not paraphrase, summarize, or reformat it.
-->

_Awaiting the Session protocol block from Andrew. Until it is embedded here,
follow sections 3 through 7 and confirm process expectations before any
irreversible or outward-facing action._

---

## 3. Voice rules (absolute, machine-enforced)

These are absolute. Every piece of generated content must conform.

- **No em dashes. Ever.** Not in copy, not in alt text, not in commit messages
  visible to staff, not in template comments shown in the admin. Use commas,
  periods, or parentheses instead.
- Do not lead with "Meet [Name]," in any dog-facing copy.
- Do not close with a question ("Could you be his person?" style endings are
  out).
- Use "~age" (tilde, no space) when age is approximate. Never "estimated,"
  "about," or "approximately."
- Concise, personality-forward, warm. Natural emoji use is fine when it fits
  the voice. No emoji stuffing.
- Professional sign-off is "Andrew Z." when a human author is credited.

Enforcement: run `bash scripts/check-voice.sh --staged` before every commit; CI
runs the same check on the PR diff. Inherited prototype violations do not block;
new ones do.

---

## 4. Architecture facts

- **Platform:** WordPress with the Divi parent theme, on new.pomdr.org (staging)
  and www.pomdr.org (production). PHP 8.2. Never edit the Divi parent.
- **Redesign theme:** a Divi **child** theme at `divi-child-integration/` (theme
  name "POMDR 2026"). Redesigned pages are child-theme templates
  (`front-page.php`, `page-{slug}.php`, `single-pets.php`) plus a global CSS and
  JS layer (`assets/css`, `assets/js`). Every other page inherits the look
  through CSS.
- **Content model:** `pets` CPT plus ACF is the source of truth for dogs; Team
  and Events CPTs cover staff and events. Everything renders through shortcodes,
  so staff edit content in wp-admin with no code. Authoritative ACF schema:
  `divi-child-integration/acf-export-2026-06-09.json`.
- **Dog status:** the ACF `status` field is a multi-value **checkbox** storing
  **Title Case** strings: `Adoptable`, `Foster Needed`, `Sponsor Needed`,
  `Adoption Pending`, `Adopted`, `Hospice`, `Courtesy Listing`. A dog can hold
  more than one. Queries use `in_array('Adoptable', $status)` on the Title Case
  values. Kebab-case slugs are a display and CSS convention only.
- **Dog URLs:** integer `/pets/{ID}/` is canonical (indexed for years,
  rename-safe).
- **Forms:** LGL (Little Green Light) embeds. Adoption inquiry form
  `utzjcNEZaqAcJk3QURlQmw`, prefilled by `field_21`; donation form
  `62FAoG7Obtf81TYETJMN3Q`.
- **MU plugin:** `wp-mu-plugins/pomdr-mcp-abilities.php` (mirrored into the live
  install's `mu-plugins/`) registers WordPress Abilities exposed as MCP tools:
  `pomdr-list-dogs`, `pomdr-get-dog`, `pomdr-create-dog`, `pomdr-update-dog`,
  `pomdr-list-events`. No delete abilities; writes require `edit_posts`.
- **Local dev:** a Local by Flywheel mirror at `newpomdr-local.local`. The active
  child theme `divi-child` is a symlink to `divi-child-integration/`, so theme
  edits are live on the mirror. Design system source of truth:
  `design-system/MASTER.md` and the `:root` tokens in
  `divi-child-integration/assets/css/pomdr-design.css`.
- **Historical reference:** the static prototype under `pomdr-website/project/`
  is a design reference only, not an active track.

---

## 5. Settled decisions (do not revisit)

Extracted from STACK.md section 7 and the risk-register `done` items. Do not
reopen or re-litigate these; if a task seems to require changing one, stop and
ask Andrew.

| Decision | Source |
|----------|--------|
| Keep Divi. Ship the redesign as a **Divi child theme** (`divi-child-integration/`, "POMDR 2026"), not the FSE block theme `pomdr-2026`. | STACK.md section 7 (2026-06-06) |
| **Status canonical = live ACF `status` checkbox** (multi-value, Title Case). Kebab-case model is display-only. | STACK.md section 7 (2026-06-09); RISK A1 (done) |
| **Dog URL canonical = integer `/pets/{ID}/`** (not slug). `redirects.csv` updated to stop asserting slug canonicalization. | STACK.md section 7 (2026-06-09); RISK A3 (done) |
| **Donation form = LGL `62FAoG7Obtf81TYETJMN3Q`** (iframe embed on the donation page). | STACK.md section 7 and section 2 (2026-06-09); RISK B3 (done, impl deferred) |
| **ACF schema version-controlled** via `divi-child-integration/acf-export-2026-06-09.json` (real export). The aspirational `inc/acf-fields.php` was removed. | STACK.md section 7 (2026-06-09); RISK A2 (done) |
| **`flush_rewrite_rules()` removed from per-request**; now flushes on `after_switch_theme` only. Flush permalinks once after each deploy. | RISK A4 (done) |
| **Adoption inquiry form = LGL `utzjcNEZaqAcJk3QURlQmw`**, prefill via `field_21`. | STACK.md section 3 (confirmed) |

---

## 6. Current open decisions (HUMAN-gated)

These mirror roadmap items D1 to D5 (`docs/DEPLOYMENT-ROADMAP.md`). They are
**human decisions. Claude must not make or assume them.** Surface them and wait
for Andrew.

| # | Decision | Status |
|---|----------|--------|
| D1 | Donation flow: keep the legacy `POMDRDonation.php` links, or move all donate CTAs to the confirmed LGL donation form. | Open (human) |
| D2 | Adoption form: confirm the LGL adoption form id (`utzjcNEZaqAcJk3QURlQmw`) and that `field_21` is the dog-name field, then finish the prefill wiring. | Open (human); wiring in progress (RISK B1) |
| D3 | `culture` page URL: top-level `/culture/` or nested `/about/culture/`. | Open (human) |
| D4 | Real content vs prototype placeholders: testimonials, POMDR Videos YouTube ids, homepage stats, impact numbers. | Open (human) |
| D5 | Domain and launch target: which site is live (new.pomdr.org vs the primary domain) and the cutover plan. | Open (human) |

---

## 7. Off-limits (hard guardrails)

- **No production deploy or publish.** The Local mirror (or an explicit staging
  copy) is the only write target. Never give the WordPress MCP write access to a
  production database.
- **Confirm destructive or outward-facing actions** (deletes, bulk updates,
  deploys, anything published externally) with a typed confirmation from Andrew.
- **Never push directly to `main`.** Always through a PR with a clear
  before/after.
- **No page builders beyond Divi**, and do not propose switching away from
  WordPress.
- **Do not invent dog data** (names, ages, breeds, weights, histories); it comes
  from WordPress or a staff source. **Do not generate AI images of dogs**; all
  photos are real.
- **No tracking, ads, third-party widgets, or AI chatbots** without approval.
  Adoption questions go to humans, not bots.

---

## 8. Reference pointers

Cut detail lives here so this file stays dense.

- Architecture and how to run: `README.md`, `docs/LOCAL-BRIDGE.md`,
  `docs/DIVI-BRIDGE-HOWTO.md`.
- Stack and decisions log: `STACK.md`.
- Pre-launch risks (open, in progress, done): `docs/RISK-REGISTER.md`.
- Launch plan and mechanics: `docs/DEPLOYMENT-ROADMAP.md`,
  `docs/DEPLOYMENT-GUIDE.md`.
- Design system and tokens: `design-system/MASTER.md`, `DESIGN-TOKENS.md`,
  `divi-child-integration/assets/css/pomdr-design.css`.
- Staff editing workflow: `docs/STAFF-CONTENT-GUIDE.md`.
- Information architecture and UX method: `docs/IA-UX-AUDIT.md`.
- Org info safe to hardcode: Peace of Mind Dog Rescue; EIN 27-1154816; Patricia
  J. Bauer Center, 615 Forest Ave, Pacific Grove, CA; info@pomdr.org;
  (831) 718-9122; founded 2009.

---

_Owner: Andrew Z. Structure last reworked 2026-07-02. When this file is wrong,
flag it, propose the update, and wait for Andrew's decision._
