# POMDR Website Redesign: Capabilities and Achievements Report

A plain-language tour of what this project is, what it took to build, the skills
and technology behind it, and how all of it pays forward. Assembled 2026-06-26
from a full walk of the codebase.

---

## 1. What this is, in 30 seconds

A ground-up redesign of the website for **Peace of Mind Dog Rescue (POMDR)**, a
501(c)(3) on California's Central Coast that has paired **senior dogs with senior
people since 2009**. The goal: turn a dated WordPress site into a warm, modern,
**highly accessible** experience that makes it easy to adopt, foster, donate, and
volunteer, while staying **fully editable by non-technical staff** in the
WordPress admin. The redesign layers onto the existing **Divi** theme rather than
replacing it, so the staff-familiar editing surface and the working dog database
are preserved.

---

## 2. The project at a glance (by the numbers)

| Metric | Value |
|---|---|
| Tracked files in the repo | **244** |
| Commits | **69** over ~6 weeks (2026-05-15 to 2026-06-25) |
| Documentation artifacts | **~28 markdown docs** |
| Interactive prototype pages | **31 HTML pages** + a React (JSX) build |
| WordPress shortcodes (theme) | **44** |
| Custom AI content tools (MCP abilities) | **6** |
| Dog detail page logic engine | **566 lines** (rules-driven sections) |
| Responsive verification | measured at **9 widths, 320 to 1920px** |
| MCP servers wired | **9** plus GitHub |

---

## 3. Top skills demonstrated

1. **Full-stack WordPress engineering.** A Divi **child theme** that layers on a
   clean parent, a 2,700-line `functions.php` with 44 shortcodes, ACF custom post
   types (`pets`, `events`, `team`), and a **rules-driven dog detail template**
   (`single-pets.php`) that shows or hides sections based on a dog's status and
   data.
2. **Design systems.** A token-first system (color, type, spacing, radius,
   shadow, motion) with a single source of truth, a disciplined **two-color brand
   palette** (teal + purple plus warm neutrals), and a typographic pairing of
   Source Serif 4 and Source Sans 3.
3. **Accessibility engineering (WCAG 2.2 AA, senior-first).** A dedicated
   accessibility layer with an opt-in **"senior mode,"** 44px+ tap targets,
   visible focus rings, reduced-motion support, 16px+ body text, and
   **scripted contrast auditing** that measures color ratios programmatically
   rather than guessing.
4. **Responsive engineering with proof.** A **measured** Playwright harness that
   checks horizontal overflow, element overlap, CTA visibility, and tap-target
   size across nine viewport widths. Pass/fail, not eyeballing.
5. **AI and agent tooling.** Integration of the **Model Context Protocol (MCP)**:
   a WordPress MCP adapter plus **custom "abilities"** that let an AI assistant
   safely list, read, create, and update dogs and events, all gated by WordPress
   permissions with no delete path.
6. **Local environment and DevOps debugging.** Diagnosed and fixed non-obvious
   infrastructure problems: a migration-corrupted `wp-config.php`, an **nginx
   configuration that was silently stripping the auth header**, and WordPress
   Application Password setup on Local by Flywheel.
7. **IA and UX research.** A full crawl and audit of both the legacy and target
   sites (~160 URLs), a 21-page gap map, breadcrumb scheme, and a human-centered
   design method baked into the workflow.
8. **Brand and content discipline.** A documented voice (no em dashes, senior-
   first framing, no buried calls to action) that is **enforced automatically in
   CI and a pre-commit hook**.
9. **Professional Git and review workflow.** Feature-branch-only, logical
   commits, a real pull request with a thorough description, CODEOWNERS, and a PR
   template. Production was never touched.

---

## 4. Knowledge assets (the documentation moat)

Roughly 28 documents capture not just *what* was built but *why*, which is what
makes the project resilient and easy to hand off:

- **CLAUDE.md** the standing operating rules (brand, stack, quality gates, design
  method).
- **VOICE.md / BRAND-GUIDELINES.md / BRAND-ASSETS.md** the brand and copy system.
- **docs/IA-UX-AUDIT.md** the full information-architecture and UX audit.
- **docs/RISK-REGISTER.md** a pre-launch failure-mode review.
- **docs/BUILD-PLAN.md** a staged execution plan with a shared build contract.
- **STACK.md / MIGRATION-MATRIX.md / REDIRECT-MAP.md / INVENTORY.md** the
  technical decisions, content migration map, and inventory.
- **design-system/MASTER.md** the design-system source of truth.
- **docs/DIVI-BRIDGE-HOWTO.md and docs/DIVI-TRANSLATION-CONTRACT.md** exactly how
  the prototype becomes Divi while staying staff-editable.
- **docs/LOCAL-BRIDGE.md / SESSION-CHECKLIST.md** the local environment and
  working rhythm.

---

## 5. Features built

- **Site-wide chrome:** a persistent Adopt / Donate / Volunteer action bar, a
  two-row navigation, the full logo with "Since 2009," and a **beautiful animated
  mobile menu** with on-brand tap effects.
- **Dog cards from real data:** the redesign card pulls live `pets` ACF fields,
  shows status-driven badges (Adoptable, Foster Needed, etc.) and brand-correct
  `~age` formatting, so editing a dog in wp-admin updates the design instantly.
- **Adopt listing** with search, category tabs, filters, and sort.
- **Dog detail pages** driven by a rules engine (sections appear based on status,
  foster dates, sponsorship, hospice, gallery, and video).
- **Homepage** with a photo hero plus a translucent brand-color readability card,
  a "POMDR By the Numbers" stat band, programs, videos, and testimonials.
- **Accessibility toggle and senior mode** available site-wide.
- **AI content tools** to manage dogs and events programmatically when useful.

---

## 6. The stack: what we used to get here

**Platform and data:** WordPress, Divi (child theme), Advanced Custom Fields Pro,
PHP 8.2, MySQL, Local by Flywheel, the WordPress REST API and Application
Passwords.

**Front end:** plain modern CSS (custom properties, logical properties, container
and media queries), progressive-enhancement JavaScript (the core experience works
without JS), Source Serif 4 + Source Sans 3.

**AI and agent toolkit (the MCP fleet):** nine Model Context Protocol servers plus
GitHub:

| Server | What it gave us |
|---|---|
| **Playwright** | Real-browser screenshots, the responsive audit harness, flow testing |
| **Chrome DevTools** | Lighthouse, performance, accessibility passes |
| **WordPress (mcp-adapter + custom abilities)** | Read/write dogs and events as an agent |
| **Filesystem** | Direct theme and content file access |
| **Fetch** | Single-URL content pulls and research |
| **Context7** | Up-to-date WordPress and library documentation |
| **Figma** | Design-token and spec pull-in (when Figma is used) |
| **Sequential Thinking** | Multi-step architectural reasoning |
| **Google Drive** | Brand assets and reference docs |
| **GitHub** | Repository operations and the pull request |

**Design intelligence:** the **ui-ux-pro-max** skill (50+ styles, color systems,
typography pairings, 99 UX guidelines, accessibility rules) used to validate and
shape decisions.

**Quality automation:** a GitHub Actions **voice-check** workflow, a **pre-commit
hook**, CODEOWNERS, and a PR template, all enforcing the brand and code standards
on every change.

**Design method:** Stanford d.school / IDEO / Whipsaw human-centered practice,
applied page by page, with the rule that **every primary action is a real button
above the prose, never buried**.

---

## 7. Standout achievements ("accolades")

- **Unblocked the entire local site with a one-line fix.** A botched migration had
  corrupted the site URL in `wp-config.php`, breaking all 887 media references.
  Root-caused and repaired it.
- **Cracked a non-obvious authentication failure.** Application-password login was
  failing with the right credentials; the cause was nginx not forwarding the
  `Authorization` header to PHP. Diagnosed with a probe and fixed at the server
  config and template level so it survives restarts.
- **Built verifiable responsive QA.** Instead of trusting screenshots, wrote
  scripts that measure overflow, overlap, contrast, and tap targets, and iterated
  until all nine widths passed.
- **Modernized without taking anything away from staff.** Dogs and events stay
  editable in the familiar WordPress admin; the new look renders automatically.
  The Divi translation contract documents exactly how.
- **Kept the work safe.** Everything stayed on a feature branch with a clean PR;
  no production deploy, no database or media changes, no secrets committed.

---

## 8. How this helps going forward

- **Speed and consistency:** the token system and shared components mean new pages
  are fast to build and automatically on-brand.
- **AI-assisted operations:** the MCP toolkit and the custom WordPress abilities
  set up a future where routine content work (new dogs, events, happy tails) can
  be drafted and updated with an assistant, still governed by WordPress
  permissions.
- **Low bus-factor:** the documentation captures decisions and rationale, so a new
  contributor (or a future session) can get productive quickly.
- **Reusable QA:** the measured responsive and contrast harness is a repeatable
  gate for every future change, not a one-time effort.
- **A clean path to production:** the prototype-to-Divi contract means the design
  ports into the live, staff-editable site without a rebuild.

---

_Owner: Andrew Z. Prepared with Claude (Opus 4.8) using the project's MCP toolkit
and the ui-ux-pro-max design skill._
