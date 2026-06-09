# POMDR Website Redesign

Working repository for the Peace of Mind Dog Rescue (POMDR) website
redesign. Two tracks live here side by side:

- **Track A — Static prototype** under `pomdr-website/project/`. Clickable
  HTML/CSS/JS used as a design lab. Approved patterns get re-implemented
  in the WordPress block theme.
- **Track B — WordPress block theme** (`pomdr-2026/`, lands in Session 3).
  Full Site Editing theme that docks to new.pomdr.org's existing `pets`
  CPT and ACF fields.

The redesign target is documented in [CLAUDE.md](./CLAUDE.md) (project
charter, voice rules, technical stance) and in the plan file at
`~/.claude/plans/lets-start-here-and-synchronous-lightning.md`.

---

## Run the static prototype locally

The prototype is plain HTML/CSS/JS; any static server works.

```bash
cd pomdr-website/project
python -m http.server 8000
```

Open <http://localhost:8000/index.html>. Walk the prototype end-to-end
using [PROTOTYPE-WALKTHROUGH.md](./PROTOTYPE-WALKTHROUGH.md) as the QA
script.

The page currently loads React, ReactDOM, and Babel from CDNs. Offline
work needs Wi-Fi. Vendoring is on the Session 3 punch list.

---

## Voice rules

POMDR has non-negotiable copy rules (see [CLAUDE.md §2](./CLAUDE.md)):

- No em dashes. Anywhere. Use commas, periods, or parens.
- No "Meet [Name]," openers in dog-facing copy.
- No closing rhetorical questions ("Could you be his person?").
- Use `~age` (tilde, no space) for approximate ages.

These are enforced via `scripts/check-voice.sh` and the CI workflow at
`.github/workflows/voice-check.yml`.

### Enable the pre-commit voice hook (optional)

```bash
git config core.hooksPath .githooks
```

The hook runs `scripts/check-voice.sh --staged` before each commit and
fails the commit if a staged file introduces a voice violation. Bypass
in emergencies with `git commit --no-verify`, but expect CI to catch
the same issue.

### Run the voice check ad hoc

```bash
# Scan everything (shows inherited backlog)
bash scripts/check-voice.sh

# Scan only what changed vs main (what CI runs in PRs)
bash scripts/check-voice.sh --diff main

# Scan currently staged files
bash scripts/check-voice.sh --staged
```

---

## Repository layout

```
.
├── CLAUDE.md                    # Project charter and standing rules
├── VOICE.md                     # Voice audit + rules (cites em dashes in tables)
├── DESIGN-TOKENS.md             # Color, type, spacing tokens with source cites
├── INVENTORY.md                 # Existing site URL and nav inventory
├── STACK.md                     # Integration TBDs (forms, donations, email)
├── GAP-ANALYSIS.md              # Data model + page coverage gaps
├── BRAND-ASSETS.md              # Where the heavy brand assets live
├── PROTOTYPE-WALKTHROUGH.md     # QA script for the static prototype
├── WP-SCAFFOLD-NOTES.md         # Session 3 status + WP backend dovetail plan
├── CHANGELOG.md                 # Session-by-session log
├── README.md                    # This file
├── redirects.csv                # Legacy URL preservation map
├── theme.json                   # Draft theme.json for pomdr-2026 WP theme
├── pomdr-website/
│   └── project/                 # Static prototype (Track A)
│       ├── index.html
│       ├── adopt.html
│       ├── dog/                 # 12 dog detail pages + coming-soon.html
│       ├── data.jsx
│       ├── pomdr.css
│       ├── pomdr-layout.js
│       └── uploads/             # Brand PDF, dog photos, design refs
└── pomdr-2026/                  # WordPress block theme (Track B, Session 3)
```

---

## Workflow

1. Branch naming: `feature/`, `fix/`, `chore/`, `content/`, `design/`,
   `a11y/`.
2. One logical change per commit. Plain-language commit messages, no em
   dashes.
3. Open a PR against `main`. The PR template
   ([`.github/PULL_REQUEST_TEMPLATE.md`](./.github/PULL_REQUEST_TEMPLATE.md))
   embeds the voice and accessibility checklists.
4. CI runs the voice check on the diff. Inherited prototype violations
   do not block; new ones do.
5. Andrew reviews and merges.

Never push directly to `main`.

At the end of each session, run the
[end-of-session checklist](./docs/SESSION-CHECKLIST.md) (voice check, commit,
push the feature branch, open/update the PR, log the change) so progress always
lands on GitHub.

> **Branch protection caveat.** The repo is currently private on a free
> GitHub user account, so GitHub will not let us enable classic branch
> protection or repository rulesets on `main` (both gated behind GitHub
> Pro). Until that changes (either Pro upgrade or making the repo
> public), the "no direct push to main" rule is enforced socially via
> [CLAUDE.md](./CLAUDE.md) and reinforced by the PR template and the
> CI voice check. CODEOWNERS still auto-requests reviews.

---

## Quality gates

Every meaningful change should clear:

- Accessibility: axe-core scan, zero new violations. Keyboard walk of
  any new interactive component. `prefers-reduced-motion: reduce`
  honored.
- Performance: Lighthouse mobile. No regression past 3 points on any
  Core Web Vital. Targets: LCP < 2.5s, INP < 200ms, CLS < 0.1.
- Voice: `scripts/check-voice.sh --diff main` returns clean.
- Visual regression: Playwright screenshots of affected pages compared
  to the prior baseline.

---

## Contact

Owner: Andrew Z. (apzielinski62@gmail.com). Implementation partner:
Claude Code (anthropic.com).

POMDR org: <https://www.pomdr.org> · (831) 718-9122 ·
info@peaceofminddogrescue.org · EIN 27-1154816.
