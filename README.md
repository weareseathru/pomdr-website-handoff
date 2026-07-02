# POMDR Website Redesign

Working repository for the Peace of Mind Dog Rescue (POMDR) website redesign.
The redesign is a **Divi child theme** (`divi-child-integration/`, theme name
"POMDR 2026") that overlays the existing WordPress and Divi install on
new.pomdr.org. It docks to that site's real content model (the `pets` custom
post type with ACF fields, plus Team and Events CPTs) and its existing form and
redirect setup, so non-technical staff keep editing the site in wp-admin with no
code. Redesigned pages are child-theme templates (`front-page.php`,
`page-{slug}.php`, `single-pets.php`) plus a global CSS and JS layer; everything
else inherits the new look through CSS without touching the Divi parent.

The project charter, voice rules, architecture facts, settled decisions, and
open decisions live in [CLAUDE.md](./CLAUDE.md). Read it first.

---

## Architecture at a glance

- **Theme:** `divi-child-integration/` ("POMDR 2026"), a Divi **child** theme.
  Never edit the Divi parent.
- **Content model:** `pets` CPT plus ACF (schema of record:
  `divi-child-integration/acf-export-2026-06-09.json`), plus Team and Events
  CPTs. Dogs, staff, and events render through shortcodes so staff manage them in
  wp-admin.
- **Dog URLs:** integer `/pets/{ID}/` is canonical (see CLAUDE.md).
- **Forms:** LGL (Little Green Light) embeds. Adoption inquiry
  `utzjcNEZaqAcJk3QURlQmw` (prefill `field_21`); donation
  `62FAoG7Obtf81TYETJMN3Q`.
- **MU plugin:** `wp-mu-plugins/pomdr-mcp-abilities.php` (mirrored into the live
  install's `mu-plugins/`) registers WordPress Abilities exposed as MCP tools, so
  dogs and events can be managed through the WordPress MCP. No delete abilities;
  writes require `edit_posts`.
- **Historical design reference:** the static prototype under
  `pomdr-website/project/` was the original clickable design lab. It is kept for
  reference only and is not an active track; new design work happens in the Divi
  theme on the Local mirror.

> The 2026-06-06 decision retired the earlier plan for a clean Full Site Editing
> block theme (`pomdr-2026/`). See [CLAUDE.md](./CLAUDE.md) and
> [STACK.md](./STACK.md) section 7. The block-theme notes in
> [WP-SCAFFOLD-NOTES.md](./WP-SCAFFOLD-NOTES.md) are historical record only.

---

## How to run (Local WordPress mirror)

Development happens on a Local (localwp.com) WordPress mirror, not a static
server.

1. Install Local (localwp.com) and open the POMDR site (host:
   `newpomdr-local.local`). The full wiring, including the DB import and the
   theme symlink, is in [docs/LOCAL-BRIDGE.md](./docs/LOCAL-BRIDGE.md).
2. The active child theme `divi-child` is a **symlink** to this repo's
   `divi-child-integration/`, so edits to theme files are live on the mirror
   immediately (no build step).
3. Browse the mirror at <http://newpomdr-local.local> (use `http`; the local
   certificate is self-signed).
4. Edit dogs, staff, and events in wp-admin; the templates read the CPTs, so the
   pages update themselves.
5. Lint PHP before committing with Local's bundled PHP (`php -l`); the exact
   binary path is recorded in [docs/LOCAL-BRIDGE.md](./docs/LOCAL-BRIDGE.md).

To port an approved design into a page, see
[docs/DIVI-BRIDGE-HOWTO.md](./docs/DIVI-BRIDGE-HOWTO.md) and
[docs/PORTING-CHECKLIST.md](./docs/PORTING-CHECKLIST.md).

---

## Voice rules

POMDR has non-negotiable copy rules (full text in [CLAUDE.md](./CLAUDE.md)):

- No em dashes. Anywhere. Use commas, periods, or parentheses.
- No "Meet [Name]," openers in dog-facing copy.
- No closing rhetorical questions.
- Use `~age` (tilde, no space) for approximate ages.

Enforced by `scripts/check-voice.sh` and the CI workflow
`.github/workflows/voice-check.yml`. Run `bash scripts/check-voice.sh --staged`
before every commit. Optional pre-commit hook: `git config core.hooksPath
.githooks`.

---

## Documentation index (`/docs`)

| Doc | Purpose |
|-----|---------|
| [BUILD-PLAN.md](./docs/BUILD-PLAN.md) | Staged build plan for navigation, infrastructure, and UX foundations. |
| [DEPLOYMENT-GUIDE.md](./docs/DEPLOYMENT-GUIDE.md) | Mechanics of shipping the theme to live WordPress (upload, activate, flush). |
| [DEPLOYMENT-ROADMAP.md](./docs/DEPLOYMENT-ROADMAP.md) | Phased path from the Local mirror to launch, with the D1 to D5 stakeholder decisions. |
| [DESIGN-AUDIT-2026-06-23.md](./docs/DESIGN-AUDIT-2026-06-23.md) | Design audit that set the two-color palette direction. |
| [DIVI-BRIDGE-HOWTO.md](./docs/DIVI-BRIDGE-HOWTO.md) | How to bridge a redesigned page into Divi (homepage proof). |
| [DIVI-TRANSLATION-CONTRACT.md](./docs/DIVI-TRANSLATION-CONTRACT.md) | Rules for keeping redesigned pages editable inside Divi. |
| [HANDOFF-PORT-AUDIT.md](./docs/HANDOFF-PORT-AUDIT.md) | Page-by-page audit mapping the prototype to the Divi mirror. |
| [IA-UX-AUDIT.md](./docs/IA-UX-AUDIT.md) | Information architecture and UX audit, page-by-page build plan, and design method. |
| [LOCAL-BRIDGE.md](./docs/LOCAL-BRIDGE.md) | How the git repo, the Local WP install, and new.pomdr.org wire together. |
| [PORTING-CHECKLIST.md](./docs/PORTING-CHECKLIST.md) | The continuous checklist for porting an approved change into the Divi theme. |
| [PROJECT-CAPABILITIES-REPORT.md](./docs/PROJECT-CAPABILITIES-REPORT.md) | Summary of what the redesign delivers. |
| [RISK-REGISTER.md](./docs/RISK-REGISTER.md) | Pre-launch failure-mode review; open, in-progress, and done items with status. |
| [SESSION-CHECKLIST.md](./docs/SESSION-CHECKLIST.md) | End-of-session checklist (voice check, commit, push, PR, log). |
| [STAFF-CONTENT-GUIDE.md](./docs/STAFF-CONTENT-GUIDE.md) | How staff edit dogs, staff, and events in wp-admin with no code. |

---

## Repository layout

```
.
├── CLAUDE.md                    # Orientation + standing rules (read first)
├── README.md                    # This file
├── STACK.md                     # Integration stack + decisions log
├── MIGRATION-MATRIX.md          # Prototype-to-WP page and URL mapping
├── design-system/               # Design system source of truth (MASTER.md + pages)
├── DESIGN-TOKENS.md             # Color, type, spacing tokens with source cites
├── VOICE.md                     # Voice audit + rules
├── CHANGELOG.md                 # Session-by-session log
├── redirects.csv                # Legacy URL preservation map (stub; needs a prod crawl)
├── docs/                        # See the documentation index above
├── scripts/check-voice.sh       # Voice-rule enforcement (CI + pre-commit)
├── wp-mu-plugins/               # MU plugin mirror (pomdr-mcp-abilities.php)
├── divi-child-integration/      # The WordPress Divi child theme ("POMDR 2026")
│   ├── style.css, functions.php, front-page.php, page-{slug}.php, single-pets.php
│   ├── acf-export-2026-06-09.json   # Authoritative ACF field-group export
│   ├── inc/                         # chrome, enqueue, redirects
│   └── assets/                      # Redesign CSS + JS (design system, gallery, nav)
└── pomdr-website/project/       # Static prototype (historical design reference only)
```

Other root files (BRAND-ASSETS.md, GAP-ANALYSIS.md, INVENTORY.md,
PROTOTYPE-WALKTHROUGH.md, SESSION-3-DESIGN-BRIEF.md, WP-SCAFFOLD-NOTES.md,
theme.json) are background or historical references.

---

## Workflow

1. Branch naming: `feature/`, `fix/`, `chore/`, `content/`, `design/`, `a11y/`.
2. One logical change per commit. Plain-language commit messages, no em dashes.
3. Open a PR against `main`. The PR template
   ([`.github/PULL_REQUEST_TEMPLATE.md`](./.github/PULL_REQUEST_TEMPLATE.md))
   embeds the voice and accessibility checklists. Use the GitHub web UI, the `gh`
   CLI if installed, or the GitHub MCP server. Never push directly to `main`.
4. CI runs the voice check on the diff. Inherited prototype violations do not
   block; new ones do.
5. Andrew reviews and merges.

End each session with the
[end-of-session checklist](./docs/SESSION-CHECKLIST.md) (voice check, commit,
push the branch, open or update the PR, log the change) so progress always lands
on GitHub.

> **Branch protection caveat.** The repo is private on a free GitHub user
> account, so classic branch protection and rulesets on `main` are not available
> (both are gated behind GitHub Pro). Until that changes, the "no direct push to
> main" rule is enforced socially via [CLAUDE.md](./CLAUDE.md) and reinforced by
> the PR template and the CI voice check.

---

## Quality gates

Every meaningful change should clear:

- Accessibility: axe-core scan, zero new violations. Keyboard walk of any new
  interactive component. `prefers-reduced-motion: reduce` honored.
- Performance: Lighthouse mobile. No regression past 3 points on any Core Web
  Vital. Targets: LCP < 2.5s, INP < 200ms, CLS < 0.1.
- Voice: `scripts/check-voice.sh --diff main` returns clean.
- Visual regression: Playwright or Chrome DevTools screenshots of affected pages
  compared to the prior baseline.

---

## Contact

Owner: Andrew Z. (apzielinski62@gmail.com). Implementation partner: Claude Code
(anthropic.com).

POMDR org: <https://www.pomdr.org> · (831) 718-9122 · info@pomdr.org ·
EIN 27-1154816.
