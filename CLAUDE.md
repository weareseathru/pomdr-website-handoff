# CLAUDE.md — POMDR WordPress Redesign

This file sets the standing rules for Claude Code (running in Antigravity) when
working on the Peace of Mind Dog Rescue (POMDR) website redesign. Read this
file at the start of every session. Re-read when switching major tasks.

---

## 1. Project goal

Redesign the POMDR public website (currently at https://www.pomdr.org) from an
acceptable-but-dated WordPress site into a warm, fun, modern, highly
accessible site that:

- Makes it easier for people to adopt, foster, donate, and volunteer.
- Surfaces individual dogs and their personalities with character.
- Is fully editable by non-technical staff (Carie, Monica, Allison, Andrew) in
  the WordPress admin — no code changes needed for routine content updates.
- Is stable, scalable, performant, and accessible (WCAG 2.2 AA minimum).
- Preserves POMDR's brand voice and visual identity.

Andrew Z. is the sole developer-in-the-loop. Claude is the implementation
partner, not an autonomous agent — always confirm destructive or deployment
actions.

---

## 2. Non-negotiable brand and copy rules

These are absolute. Every piece of generated content must conform.

### Copy style

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

### Brand colors

- Teal primary: `#0099A8`, with accent variants `#008DAF` and `#008BB0`.
- Purple primary: `#5B2C6F`, with accent variant `#632F88`.
- White backgrounds are the default. Avoid heavy dark-mode inversions.
- When adding new colors (e.g. for state indicators or tags), propose them and
  show them against the primary palette before using.

### Typography

- Myriad Pro is the brand font. On web, fall back to a free, widely-licensed
  alternative (Source Sans 3 or Open Sans are acceptable defaults) served via
  a reputable font host. Document the decision in the theme's design tokens
  file.

### Org info that's safe to hardcode

- Name: Peace of Mind Dog Rescue (POMDR)
- Tax ID: 27-1154816
- Main address: Patricia J. Bauer Center, 615 Forest Ave, Pacific Grove, CA
- Vet clinic: 1251 10th St, Monterey, CA
- Benefit Shop: 223 Grand Ave, Pacific Grove, CA
- General contact: info@peaceofminddogrescue.org, (831) 718-9122
- Founded 2009. Focus: senior dogs and senior people.

---

## 3. Technical stance

### Stack decisions

- **WordPress**, block theme path preferred (Full Site Editing) so staff can
  edit every area of the site in the Site Editor without touching code.
- Child theme with a clearly named slug (e.g. `pomdr-2026`) on top of a clean
  parent theme. Never edit the parent theme directly.
- PHP version target: whatever the current POMDR host supports at or above the
  WordPress recommended minimum. Verify before writing PHP.
- Custom post types for dogs already exist (or need to be standardized) — read
  before proposing changes.
- No heavy page builders (Elementor, Divi, etc.) unless explicitly approved.
  They slow the site, complicate accessibility, and make AI-assisted editing
  harder. Gutenberg + custom blocks is the default.
- CSS: plain modern CSS (custom properties, logical properties, container
  queries) over any framework unless a compelling reason surfaces. If we
  adopt Tailwind, do it intentionally via a WordPress-compatible integration,
  not by bolting it on.
- No unnecessary JavaScript. Progressive enhancement. The core experience
  must work without JS.

### Quality gates (enforced on every change)

Before proposing a change is "done," Claude runs or asks Andrew to run:

1. **Accessibility**: axe-core scan via Chrome DevTools MCP. Zero new
   violations. Screen reader pass on new interactive components.
2. **Performance**: Lighthouse. No regression beyond 3 points on any Core Web
   Vital score. Target: LCP < 2.5s, INP < 200ms, CLS < 0.1.
3. **Visual regression**: Playwright screenshots of affected pages, compared
   to prior baseline. Any diff is flagged for review.
4. **Code style**: WordPress coding standards for PHP. Prettier defaults for
   JS/CSS.
5. **Accessibility specifically for older audiences**: minimum body text 16px,
   color contrast ratio ≥ 4.5:1 for body, focus indicators clearly visible,
   no time-based interactions, no hover-only functionality.

### What "done" means

A task is done when (a) code is committed to a feature branch, (b) quality
gates pass, (c) a preview URL or screenshot set is available, and (d) Andrew
has confirmed. Not when Claude believes it works.

---

## 4. Working style

### Tone and pace

Andrew has a standing rule: **never rush or be careless**. Always work
through problems step by step, show your work, and verify before responding.
This applies to code reasoning too — when unsure, pause and check, don't
guess.

### Planning before coding

For any task larger than a typo or single-file edit:

1. State the goal in one sentence.
2. List the files that will change.
3. List the files that will be read but not changed.
4. Note the acceptance criteria (what makes this done).
5. Only then write code.

Use `sequentialthinking` MCP for multi-step architectural decisions
(information architecture, custom block structure, new templates).

### Context management

- When context gets long, summarize state into a file at
  `.claude/session-notes/YYYY-MM-DD.md` and reference it rather than letting
  the window fill.
- If Claude is getting something wrong repeatedly, Andrew's instruction is:
  rewrite the instructions file and show where the wrong choice was made.
  Don't patch around the misunderstanding — fix the source.
- Prefer reading the repo and this CLAUDE.md fresh at the start of a long
  session over relying on cached assumptions.

### Commits and branches

- One logical change per commit. Clear, descriptive commit messages written
  in plain language (remember: no em dashes).
- Branch naming: `feature/`, `fix/`, `content/`, `design/`, `a11y/` prefixes.
- Never push directly to `main`. Always through a PR with a clear before/after.
- Tag Andrew for review on anything touching the public-facing site.

### When in doubt, don't

If a request could affect production data (live posts, media, users,
settings), stop and confirm. A staging site is the required default for all
WordPress MCP write operations. Never issue a destructive operation (delete,
bulk update, user modification) without a typed confirmation from Andrew.

---

## 5. MCP toolkit and how to use each

### Required servers for this project

| Server              | Used for                                                       |
| ------------------- | -------------------------------------------------------------- |
| Playwright          | Full-site crawl, screenshots, visual regression, flow testing |
| Chrome DevTools MCP | Live debugging, Lighthouse, axe-core, Core Web Vitals         |
| Filesystem          | Read/write theme files, content exports, asset organization  |
| Fetch               | Single-URL content pulls, competitor research                |
| Context7            | Up-to-date docs for WordPress, Gutenberg, CSS, libraries      |
| Sequential Thinking | Multi-step architectural reasoning                           |
| GitHub MCP          | Repo operations, PRs, issues                                 |
| Figma MCP           | Pull design tokens and specs into code (when Figma is used)  |
| WordPress MCP       | Read/write to staging WP via `mcp-adapter` plugin            |
| Google Drive        | Brand assets, photos, reference docs                         |

### Tool selection rules

- **"Open the site" / "see the site"** → Playwright or Chrome DevTools MCP,
  not Fetch. Fetch gives you markdown, not rendered reality.
- **"Check if this is accessible"** → Chrome DevTools MCP with axe-core. Not
  your own guess.
- **"What does this WordPress API accept?"** → Context7, not memory. WP's API
  surface changes.
- **"Change this content on the live site"** → WordPress MCP, staging only,
  with Andrew's confirmation.
- **"Look up a single URL I gave you"** → Fetch.
- **"Plan the new information architecture"** → Sequential Thinking.

---

## 6. Content inventory: things Claude should know exist

- ~95 adoptable or recently adopted dogs (roster lives in a spreadsheet and
  in WP; use WP as source of truth).
- Recurring content types: adoption post, foster post, happy tail, memorial,
  event recap, Herald print ad.
- Established campaigns: "Forever Starts Here" for long-term dogs.
- Email infrastructure: Mailchimp, HTML templates, images hosted at
  mcusercontent.com. Do not break existing email flow; site content feeds
  into emails.
- Social scheduling: Metricool. Site content also feeds social, so writing
  for the site means writing for downstream reuse.
- Existing 2025 annual impact report scaffold lives in a separate project
  archive. The new site should have a clear, linkable home for annual
  reports.

### Herald print ad format (if asked to generate one)

```
**Name** (bold)
breed | sex | age | weight

Peace of Mind Dog Rescue

www.POMDR.org
```

Separate entries with a blank line. No em dashes.

---

## 7. Things Claude should not do

- Do not use em dashes. (This is worth repeating.)
- Do not invent dog names, ages, breeds, weights, or histories. All dog data
  comes from WordPress or a staff-provided source.
- Do not generate photorealistic AI images of dogs. All dog photos are real,
  taken by volunteers or staff.
- Do not propose switching away from WordPress. The platform is fixed.
- Do not propose adding a page builder plugin without explicit approval.
- Do not publish to production.
- Do not grant the WordPress MCP write access to a production database.
- Do not add tracking, ads, or third-party widgets without approval.
- Do not suggest AI chatbots on the site for adoption questions. Those go to
  humans.

---

## 8. Definitions and naming

- **POMDR**: Peace of Mind Dog Rescue.
- **Helping Paw**: POMDR's support-in-place program for senior people keeping
  their senior dogs.
- **Happy tail**: a post celebrating a successful adoption.
- **Long-term dog / LTD**: a dog whose time in the rescue has exceeded the
  norm. These get special campaign attention.
- **Foster-needed**: a dog currently without a foster placement.
- **Sanctuary dog**: a dog whose medical or behavioral needs mean they live
  out their days in POMDR care rather than being adopted out.

### Dog status vocabulary

The 7 status values used in the prototype and the WordPress block theme. All
slugs use kebab-case (lowercase, hyphen separators) to match `theme.json`,
CSS class names, and URL conventions. Storage in WordPress ACF and meta
queries uses the same kebab-case slugs.

| Slug                  | Label              | Notes                                       |
| --------------------- | ------------------ | ------------------------------------------- |
| `available`           | Available          | Adoptable, ready to meet                    |
| `foster-needed`       | Foster needed      | No date range                               |
| `foster-needed-dated` | Foster needed      | With date range (vacation foster, etc.)     |
| `adoption-pending`    | Adoption pending   | Application in final stages                 |
| `recently-adopted`    | Recently adopted   | Drives the "happy tail" treatment           |
| `hospice`             | Hospice            | Sanctuary care, not adoptable               |
| `courtesy-listing`    | Courtesy listing   | Listed for another rescue or owner          |

---

## 9. When this file is wrong

If Claude encounters a situation this file doesn't cover, or covers
incorrectly, the move is:

1. Flag it in the response.
2. Propose the update to CLAUDE.md.
3. Wait for Andrew's decision before acting on the ambiguous case.

This file is a living document. Treat it as source of truth, but not as
infallible.

---

_Last updated: 2026-05-18. Owner: Andrew Z._
