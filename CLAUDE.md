# CLAUDE.md: POMDR website rebuild

The standing rules for Claude Code (and for anyone) working on the Peace of
Mind Dog Rescue (POMDR) website. Read this at the start of every session.
`START-HERE.md` is the ten-minute orientation; this file is the rules.

Rewritten 2026-11-23. The earlier version described a WordPress and Divi
project that was dropped on 2026-09-23; it is in git history and under the
tag `archive/divi-era-2026-07`.

---

## 1. What this is

POMDR is replacing its website with a new one that is warm, plain, fast and
easy for older people to use. The new site makes it easier to adopt, foster,
donate, volunteer and ask for help, and shows each dog with character.

- **The site** is plain PHP in Ronnie Reid's private repository,
  `github.com/ronniereid/pomdr`, cloned here as `New-Build/` (git-ignored by
  this repository). His `README.md` is the specification and `AGENTS.md` its
  short form. Read both before changing code.
- **This repository** is the design record: plans, audits, staff feedback,
  copy, photo work, the archive of the old site.
- **Test site:** https://pomdr-public.pages.dev/ (rebuilt hourly from
  Ronnie's `main`). The new site is not on www.pomdr.org yet.

Andrew Z. decides design and content. Ronnie owns the repository, the admin
(admin.pomdr.org, "the Hub"), the database, the server and launch. Claude is
the implementation partner, not an autonomous agent.

---

## 2. Brand and copy rules (absolute)

### Copy

- **No em dashes. Ever.** Not in copy, alt text, commit messages or comments
  staff may read. Use commas, periods or parentheses.
- Say **"guardian"**, never "owner". US English in everything a visitor reads.
- Do not open dog copy with "Meet [Name],". Do not close with a question.
- Approximate ages are `~8` (tilde, no space). Never "about", "estimated".
- Concise, warm, personality first. No AI cadence. The current website's own
  wording is the master reference; improve it with heart, do not replace it.
- Staff want a **form filled out, not a phone call**. Forms first.
- When staff supply wording, use it exactly as written and raise questions
  separately.
- Sign-off, when a human author is credited: "Andrew Z."

### Facts

- Never invent a dog's name, age, breed, weight or history, a statistic, a
  price, a staff name or a date. Facts come from the admin database, from
  staff, or from the current website
  (`docs/POMDR-FACTS-FROM-LIVE-SITE-2026-10-26.md`).
- No AI-generated images of dogs or people. Extending a real photograph's
  background is allowed; no pixel of a dog or person may be generated.

### Look

- Two colours with meanings: **blue** (`#008bb0`, buttons `#006c8a`) for
  finding a dog; **purple** (`#632F88`) for the care side and for Donate.
  Warm neutrals otherwise. White backgrounds by default. Propose any new
  colour to Andrew, shown against the palette, before using it.
- Type: Source Sans 3 for text, Source Serif 4 for headings (the web
  stand-ins for Myriad Pro). `New-Build/Brand.md` is the full reference.

### Organisation details that are safe to write

- Peace of Mind Dog Rescue (POMDR). Founded 2009. Senior dogs and senior
  people. Tax ID 27-1154816.
- Patricia J. Bauer Center, 615 Forest Ave, Pacific Grove, CA.
- Veterinary clinic, 1251 10th St, Monterey, CA (private, not open to the
  public).
- Benefit Shop, 229B Grand Ave, Pacific Grove, CA.
- info@pomdr.org, (831) 718-9122.

These live once in `New-Build/includes/site-info.php`. Change them there.

---

## 3. Technical rules

### The stack is fixed

Plain PHP, one stylesheet, no framework, no Composer, no npm, no page
builder, no build step except Ronnie's `build/build.php`. The public site is
static HTML rendered hourly. Do not propose changing any of that.

### Design only, in Ronnie's repository

- **May change:** `public/**` pages, `public/assets/css/site.css`,
  `public/assets/js/`, `public/assets/img/`, `includes/header.php`,
  `includes/footer.php`, `includes/site-info.php`, `includes/page-intro.php`
  and the other public includes, `Design.md`, `Brand.md`, `Components.md`,
  `tests/`.
- **Never change existing lines** under `admin/`, `db/`, `build/`, `bin/`,
  `deploy`, `includes/db.php`, `includes/functions.php`. If the design needs
  something there, add a public twin, work around it in the page, or ask
  Ronnie through a ticket.
- Follow his rules: escape every printed variable with `e()`; root-relative
  links; no inline styles; page images only in `public/assets/img/`, each
  through `php bin/prepare-page-image.php`; say "pet", not "dog", in code.

### Quality floors (older readers)

Body text 16px or more. Contrast 4.5:1 or more for text (3:1 for large
text). Visible focus. Tap targets 44px or more. Nothing hover-only, nothing
timed without a pause. The page works with JavaScript off. No horizontal
scroll at 320px or at 200% text. Targets: LCP under 2.5s, INP under 200ms,
CLS under 0.1.

### The gate, before every push to Ronnie's `main`

`scripts/dev.sh check` runs it. By hand, from `New-Build/`:

1. `php bin/check.php`
2. `php tests/unit/run.php`
3. Render every page: `for u in $(php bin/list-pages.php); do php
   build/render-page.php "$u"; done`. No Warning, Fatal or Deprecated, and no
   page under 2,000 bytes.
4. No em dash in added lines.
5. Structure audit: `git diff --cached origin/main -- admin build bin db
   deploy includes/db.php includes/functions.php | grep -c '^-[^-]'` is 0.
6. A dated line at the top of `Design.md` section 11.
7. Look at the changed pages in a browser at 390px and 1440px.

Then commit, `git pull --rebase origin main`, push `main`. One logical change
per commit, plain-language message. No branches, no pull requests, no force
push. Tell Andrew what was pushed, with the commit id.

### What "done" means

Committed and pushed, the gate passed, a screenshot or the test site shows
it, and Andrew has seen it. Not when Claude believes it works. Say plainly
which checks were run and which were not.

---

## 4. Working style

- **Never rush.** Work step by step, verify before answering, and when
  unsure, check instead of guessing.
- **Design method:** design for the real person (an older adopter, a
  guardian in a hard moment). Give each page one job and one primary action,
  shown as a real button near the top and again at the end. Calm hierarchy,
  generous space, few words. `docs/IA-UX-AUDIT.md` has the detail.
- **Plan before code** for anything larger than a one-file edit: the goal in
  a sentence, files to change, files to read, what makes it done.
- **When a rule fights Andrew's goal,** name the rule, say whether it should
  bend, and propose the cleanest path. Hard limits stay.
- **Track staff feedback.** Every round goes in `docs/CARIES-FEEDBACK.md`
  with the state of each note.
- **Session notes** go in `.claude/session-notes/YYYY-MM-DD.md` when a
  session is long.

---

## 5. Hard limits

- Do not publish to production, and do not write to the production database,
  without Andrew's typed confirmation. This machine has no production
  database access; SQL for production goes to Ronnie as a file.
- Do not commit secrets. `New-Build/includes/config.local.php` and any
  certificate stay on the machine. Never print their contents, a token, or a
  shell configuration file.
- Do not add tracking, ads or third-party widgets without approval.
- Do not suggest an AI chatbot for adoption questions. Those go to people.
- The Photos library on Andrew's Mac is read-only.
- Do not pull from or push to any copy of Ronnie's repository made before
  2026-11-19 (history was rewritten to remove backups).

---

## 6. Names and terms

- **Helping Paw:** support so senior people can keep their dogs (walking,
  rides, help with vet bills, food, short-term foster).
- **Perpetual Care:** a pre-arrangement for POMDR to take a dog in if its
  guardian no longer can.
- **Courtesy listing:** a dog in someone else's care, listed as a favour.
  Not POMDR's dog; never shown as one (not on the home page).
- **Hospice:** a dog that lives out its life in POMDR care; not adoptable.
- **Foster Needs:** dogs waiting for a foster home.
- **Happy tail:** a celebrated adoption.
- **The Hub:** Ronnie's admin and database at admin.pomdr.org, which mirrors
  Shelterluv (animals) and Little Green Light (people) and never writes back
  to them.

Dog statuses, how they are stored and shown, are in `New-Build/Animals.md`
and `New-Build/Design.md` section 4c. Dog data comes from Shelterluv through
the Hub; a dog appears on the site when staff tick it as available.

---

## 7. When this file is wrong

Say so in the reply, propose the change, and wait for Andrew before acting
on the unclear case. This file is the source of truth, not infallible.

---

_Last updated: 2026-11-23. Owner: Andrew Z._
