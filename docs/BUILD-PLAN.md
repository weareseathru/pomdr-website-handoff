# Build Plan: Navigation, Infrastructure, and UX

A staged execution plan for the prototype build-out. Each stage is one focused
chunk sized for a full-quality run. It pairs with `docs/IA-UX-AUDIT.md` (the
analysis and the rules) and the decisions recorded there. The design method
(Stanford d.school / IDEO / Whipsaw) and the "never bury a primary action" rule
from CLAUDE.md §4 govern every stage.

Scope of this plan: the static prototype under `pomdr-website/project/`. It is
the design lab; approved patterns then port to the Divi child theme.

---

## The Shared Build Contract

Every page, and every subagent that builds a page, follows this contract. It is
what keeps parallel work consistent. Pass this section verbatim to any subagent.

1. **One job, stated up top.** Each page opens with its single purpose in one
   line. Decide it with "How Might We" (for example, "How might we let a worried
   owner start the Helping Paw application in one tap?").
2. **Primary action is a button, above the prose, never buried.** Use the shared
   `.action-bar` with a `.btn-primary` (min 44x44px, contrast >= 4.5:1). Place
   it above the explanatory text and repeat it at the end. A link inside a
   sentence is never the only path to an action.
3. **Breadcrumbs** are injected by `pomdr-layout.js` from the page's
   `data-page` id. Do not hand-author them; just set the correct `data-page`.
4. **Accessible sizing.** Body and interactive text >= 16px (base is 17px). No
   new sub-16px content text. Labels/eyebrows may be smaller only if decorative
   and paired with sufficient weight and contrast.
5. **Contrast.** Body text >= 4.5:1; large text and UI >= 3:1. No light-gray
   text on light backgrounds, no low-opacity white on pale heroes.
6. **Shared chrome.** Every page is `<body data-page="ID">`, links `pomdr.css`,
   and ends with `<script src="pomdr-layout.js"></script>` (use `../` paths in
   `/dog/`). Nav, breadcrumb, and footer are injected; do not hand-roll them.
7. **Voice rules (CLAUDE.md §2).** No em dashes in copy, no "Meet [Name],"
   openers, no closing rhetorical questions, `~age` with a tilde. No invented
   dog data.
8. **Reduced motion and keyboard.** Honor `prefers-reduced-motion`; every
   interactive element is keyboard reachable with a visible focus ring.
9. **Container and rhythm.** Use `.container`; prose blocks max ~70ch for
   readable line length.
10. **Done means:** states its job, surfaces the action as a button above the
    prose, passes the voice check, is keyboard navigable, and has no new
    sub-16px or low-contrast text. Pixel and axe-core verification is flagged
    for a browser run (no Playwright/Chrome DevTools MCP in this environment).

---

## Parallelization and subagent strategy

The risk in parallel work is two agents editing the same file. We avoid it by
file ownership: shared files change only in sequential stages run by the lead;
subagents only create or edit their own assigned page files.

- **Shared files (lead only, sequential):** `pomdr-layout.js` (nav, breadcrumbs,
  footer), `pomdr.css` (tokens, shared components). Changed in Stage 0 and
  between parallel batches, never by two agents at once.
- **Page files (parallelizable):** each `*.html` page is independent. A subagent
  owns a disjoint set of pages and touches only those files.
- **Dispatch:** in Stages 2 and 3, launch subagents in parallel, each with the
  Shared Build Contract plus its page specs and the exact files it owns. Use
  `run_in_background` for fan-out; integrate and review on completion.
- **Isolation:** if any batch must touch a shared file, that batch runs as a
  single lead task, or agents use `isolation: "worktree"` and the lead merges.
- **Review gate:** the lead reviews every subagent's output against the contract
  (voice check, sizing grep, structure) before it is committed.

---

## Stages (chunks)

### Stage 0, Foundation (lead, sequential). Status: DONE.

The shared spine. Everything else depends on it, so it is done first and not
parallelized.

- 0a. Reconcile `pomdr-layout.js` nav submenus to cover every real page (keep
  the expanded top-level per the decision): add "Ways to Give" under About,
  expand Helping Paw to Financial / Walking-Foster / Resources, add "Foster
  Needs" under Foster. Keep Contact.
- 0b. Breadcrumb system: inject a breadcrumb trail from `data-page`, derived
  from the nav tree with an override map for detail pages (dog, team) and
  footer-only pages (privacy, terms, thank-you). Markup is `<nav
  aria-label="Breadcrumb"><ol>...`, last crumb `aria-current="page"`,
  separators `aria-hidden`.
- 0c. Shared CSS in `pomdr.css`: an accessible `.action-bar` + `.btn-primary` /
  `.btn-secondary` (>= 44px, strong contrast), breadcrumb styles, and a sizing
  pass on shared sub-16px body rules. Establish the page-intro pattern
  (`.page-purpose`).

Acceptance: nav reaches every page, breadcrumbs render on all non-home pages,
the action-bar component exists and is documented, shared CSS has no sub-16px
body text. Commit in logical chunks.

Known Stage 0 follow-through (resolved during each page's refinement, since a
blind global change cannot be visually verified here):
- Only the 5 `.page-hero` pages get the automatic breadcrumb spacing override.
  The other pages clear the fixed nav their own way, so they get a temporary
  extra top gap below the breadcrumb until their first-section top padding is
  tidied in Stage 1/2 (when each page is edited anyway).
- Nav top-level links are 14px (below the 16px interactive floor). Raising them
  may wrap the 9-item expanded nav, so this is a browser-verify item in
  Stage 1, not a blind change.

### Stage 1, Core conversion pages (Tier 1). Status: DONE.

Refine the pages that already exist and carry the most traffic, to the
contract: adopt, the dog detail template and the 12 dog pages, donate (Ways to
Give hub), volunteer, foster, about, helping-paw, process. The lead sets the
pattern on two exemplars (foster, helping-paw, the action-surfacing showcases),
then subagents refine the rest in parallel (one agent per 2-3 pages, distinct
files).

### Stage 2, Missing institutional pages (Tier 2). Status: DONE (LGL iframes deferred to production).

Build the new pages, parallel subagents, distinct files: why, clinic,
bauer-center, about/culture, mailing-list (Mailchimp signup), contact (per
decision), adoption-questionnaire (LGL form host), donation (LGL form host).
Each gets the action-surfacing treatment and breadcrumbs.

### Stage 2.5, Design refresh: paw trail. Status: DONE (needs browser check).

A lean, performant brand touch on the home page: low-opacity paw prints that
reveal on scroll in a meandering path (down the left, across, to the right),
resolving into dog-cutout placeholders near the bottom (swap `#dogSymbol` for
real dog graphics when uploaded). Implementation: one reused SVG `<symbol>` via
`<use>`, a single IntersectionObserver (no scroll handler, no library), and
only opacity/transform animation (GPU composited). Decorative and aria-hidden,
pointer-events none, and `prefers-reduced-motion` renders it static. Tunable via
the paw `top`/`left`/`--rot` inline values and the `.paw.in` opacity. Visual
layering and readability over content need an in-browser check.

### Stage 3, Ways to Give (Tier 3)

Refine the donate hub, then build the standalone pages (planned-giving, legacy,
silver-hearts-fund, wish-list) in parallel. The plaque / stone / suite /
tribute / dog-tag / sponsor-an-ad variants become sections or anchors on the
hub, not separate pages.

### Stage 4, QA and polish (lead, with optional review subagent)

A 404 page, consistent empty states, a full cross-page consistency pass, the
voice check on the whole tree, a sizing/contrast grep sweep, a keyboard walk,
and a verification checklist that flags every item needing a browser run
(axe-core, Lighthouse, visual regression). Update the audit and changelog.

---

## Sequencing and dependencies

- Stage 0 blocks everything (shared components and breadcrumbs).
- Stage 1 and Stage 2 can overlap once Stage 0 lands, since they touch disjoint
  page files; the lead serializes any shared-file change between batches.
- Stage 3 depends on the donate-hub pattern from Stage 1.
- Stage 4 is last.

Progress is tracked per stage in this file and in CHANGELOG.md. Each stage ends
with a commit (or several), a push, and a green voice-check on the PR.
