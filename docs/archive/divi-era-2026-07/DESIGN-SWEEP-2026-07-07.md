# Design Sweep, 2026-07-07

A page-by-page critique of the mirror at desktop (1280) and mobile (390),
applying IDEO human-centered design, Stanford d.school framing, Whipsaw's
ruthless-simplicity craft standard, and 2026 web/UX norms, weighted for the
60+ audience. Produced by a dedicated review pass; items marked APPLIED were
fixed the same day (see the session PR), the rest are the design backlog.

## Top 10 (ranked by user impact)

1. **Desktop nav overflowed its card at 1280 to 1440px.** The tagline squeezed
   the menu rows off the white pill and into the hero. APPLIED: the tagline now
   hides below 1440px (pomdr-chrome.css).
2. **Dog detail template is still the old Divi look.** The most
   conversion-critical page breaks the design system: off-brand type, pill
   CTAs, no status badge, tiny gallery thumbs, ~150px dead space above the H1,
   and an "(est)" age string that violates the ~age voice rule. This is the
   known "single-pets polish" task. BACKLOG (large; the top design priority).
3. **/events/ is a dead end.** No H1 or main landmark (fixed by the unmerged
   heading-outlines PR), nothing on a card is clickable, flyer crops vary
   wildly (fixed by the events-upgrade PR's contain frame), and the category
   label outranks the title. BACKLOG: make cards clickable, reuse the home
   page's date-square treatment.
4. **Invisible Divi remnants.** Ghost H1s and menu modules from the hidden
   Theme Builder layouts. Verified display:none removes them from the real
   accessibility tree; the DOM-level removal ships with the heading-outlines
   PR's Divi filter. NO ACTION here.
5. **Broken images.** /about/ hero (webp source 404ed; APPLIED: dropped the
   broken source so the jpg renders) and /helping-paw/ fund cards (relative
   url() resolved under the page path; APPLIED: theme asset paths).
6. **/videos/ ships literal "video placeholder" boxes.** BACKLOG: embed the
   real YouTube videos (17 exist on the live site) with the existing facade
   pattern, or unlink the page until real.
7. **Sub-44px tap targets on real actions** (surrender application links 24px,
   donate/volunteer card CTAs 24px, adopt chips 38px, hero dots 3px tall).
   APPLIED: a global tap-target floor in a11y.css (card links, chips, tabs at
   44px; hero dots keep the 3px look with a 23px hit area).
8. **Progressive enhancement gaps.** .reveal hid content without JS
   (APPLIED: gated behind html.js, set by a11y.js), and a Divi-stored LGL
   prefill script threw a TypeError on every page (APPLIED: guarded in the DB
   layout; note for production migration, this lives in the Theme Builder
   header layout, post 145 on the mirror).
9. **Dark photo-card walls on /donate/ and /volunteer/.** 15px white text over
   busy photos. APPLIED: 16px body text and a stronger scrim on both.
   BACKLOG: consider grouping the 12 ways-to-give into 3 clusters (Give now,
   Give things, Plan ahead) to cut choice overload.
10. **The Larger Text FAB collided with the hero pause control** on home and
    with bio text on dog pages. APPLIED: home-page offset (bottom 84px). The
    dog-page overlap resolves with the single-pets redesign (item 2).

## Also applied from the sweep

- Benefit Shop hero had no button (the one page violating the
  actions-above-text rule): added Get Directions + Call the Shop.
- Process step 1 said "fill out our application" with no link: added a Start
  the Application button inside the card.

## Design backlog (needs Andrew / larger work)

- Single-pets template redesign to the prototype dog page (top priority).
- Events cards clickable + date-square treatment.
- /videos/ real embeds; /testimonials/ and /media/ real content (both are
  placeholder content presented as real, flagged in the parity audit too).
- Ways-to-give grouping into 3 clusters; consider dropping the hero's third
  ghost CTA on home ("See all dogs") since the card grid is one scroll away.
- Mobile: the sticky utility bar + floating logo pill consume ~140px of a
  390px viewport; consider collapsing one of them on scroll.
- Adopt page: a "Back to top" button after ~30 cards; board grid orphans one
  member (4+1 layout).
- Mailing-list LGL iframe's internal type (13px gray) clashes with the page;
  restyle via LGL's form theme settings if available.

## What is already excellent (do not regress)

The Larger Text toggle (18 to 22px, persistent), 18px body baseline, reduced
motion handling, skip link, labeled card actions, pausable carousel; the
action-first page anatomy sitewide; the adopt browse experience (live counts,
search, chips, tabs, ~age cards with Senior tags); /process/ and
/foster-needs/ as the craft benchmark pages; the copy voice; the two-color
discipline; mobile fundamentals (no horizontal scroll anywhere tested, clean
hamburger, single-column cards).

One capture note: full-page screenshots show beige gaps where lazy images have
not decoded; that is a screenshot artifact, not a site bug. Use viewport-scroll
screenshots for visual baselines.
