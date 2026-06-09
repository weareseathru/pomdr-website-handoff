# Information Architecture and UX Audit

Scope: a full crawl and infrastructure analysis of both POMDR web properties, a
synthesis of current accessibility and ethical-UX research for this audience
(senior adopters and senior people), and a gap map from the static prototype to
the real site, with a prioritized build plan. Crawl date: 2026-06-09.

Tooling note: the URL inventory and navigation analysis below come from the
sitemaps and rendered page content. Pixel-level visual regression and automated
axe-core runs still need the Playwright / Chrome DevTools MCP servers or a local
run; items needing that are flagged "verify in browser."

---

## 1. The two properties (infrastructure)

| Property | Host | Stack | Role |
|----------|------|-------|------|
| Legacy production | `www.peaceofminddogrescue.org` (`www.pomdr.org` 301s here) | Custom HTML/PHP (`adopt.php`, `donateoverview.html`, `POMDRDonation.php`) | The site the public uses TODAY. No XML sitemap. |
| New build | `new.pomdr.org` | WordPress + Divi + ACF (our redesign target) | Where the redesign ships. Native WP sitemaps. |

So there are three layers in play: the legacy PHP site (live now), the new
WordPress/Divi site (`new.pomdr.org`, the migration target our child theme
overlays), and this repo's static prototype (the design lab). The redesign has
to land on the WordPress site, so `new.pomdr.org` is the authoritative IA and
URL set. The legacy site matters mainly for redirect continuity (see
RISK-REGISTER D1).

### new.pomdr.org URL inventory (authoritative)

- 45 pages (full list in section 3)
- 86 dog records at `/pets/{integer-id}/` (IDs 2402 to 3037, confirming the
  integer-ID canonical decision; A3)
- 28 team members at `/team/{slug}/`
- 1 event at `/events/{slug}/`
- Total ~160 indexable URLs

---

## 2. Navigation IA (the canonical menu)

The new.pomdr.org main menu is the canonical IA. Six top-level items, each with
a submenu, plus a prominent Donate button.

| Top level | Submenu | Notes |
|-----------|---------|-------|
| **Adopt** | Adoptable Dogs, Courtesy Listings, Recently Adopted, Adoption Events, Our Adoption Process | Hospice is NOT under Adopt here |
| **Volunteer** | Volunteer Application, Foster Needs, Volunteer Opportunities | Foster lives UNDER Volunteer |
| **Helping Paw** | Financial, Walking/Foster, Resources | |
| **Surrender** | Placing your Dog, Perpetual Care | |
| **About Us** | Our Team, Ways To Give, Benefit Shop, Jobs, Videos, In The Media, Testimonials | Ways To Give and Benefit Shop live here |
| **Donate** (button) | (LGL form) | Utility CTA, always visible |

Logo links home (there is no separate "Home" menu item). Socials: Facebook,
Instagram, TikTok, YouTube, LinkedIn. No breadcrumbs anywhere on the live site.

### Prototype nav vs canonical nav (the deltas to reconcile)

The prototype's `pomdr-layout.js` nav is well-engineered (skip link, real
`<main>` landmark, ARIA dropdowns, mobile menu, Escape handling) but its
STRUCTURE diverges from the canonical menu:

| Prototype nav | Canonical (new.pomdr.org) | Action |
|---------------|---------------------------|--------|
| Top-level: Home, About, Adopt, Foster, Volunteer, Helping Paw, Surrender, Benefit Shop, Contact | Top-level: Adopt, Volunteer, Helping Paw, Surrender, About Us, Donate | Realign to 6 + Donate |
| Foster is top-level | Foster Needs under Volunteer | Move under Volunteer |
| Benefit Shop is top-level | Benefit Shop under About Us | Move under About Us |
| Contact is top-level | No Contact page exists on the live site | Decide: add a Contact page or drop it |
| Hospice under Adopt | Hospice not in the live menu (page exists at /hospice/) | Decide placement |
| About submenu lacks "Ways To Give" | Ways To Give under About Us | Add |
| Helping Paw submenu: Program, Resources | Financial, Walking/Foster, Resources | Expand to match |

Recommendation: adopt the 6-item canonical structure as the spine (it matches
what staff maintain and what users will see post-launch), with two UX-informed
refinements noted in section 4: keep a top-level Donate button, and consider
whether "Surrender" should be softened to "Rehome" or kept (staff term).

---

## 3. Page gap map: prototype vs new.pomdr.org

The prototype already covers the high-traffic core. The gaps are mostly the
"Ways to Give" long tail and a few institutional pages.

### Covered (prototype has a real page)

home, adopt, donate (Ways to Give), videos, volunteer, about, jobs,
benefit-shop, adopted, helping-paw, perpetual-care-program, events, resources
(prototype `resources.html` vs live typo slug `/recources/`), process, media,
surrender, hospice, courtesy-listings, testimonials, volunteer-application,
terms, privacy. Plus 12 dog detail pages and a coming-soon.

### Missing from the prototype (21 pages on new.pomdr.org)

Grouped by type, with a build recommendation:

**Institutional / content (build as real pages):**
- `/about/culture/` (Culture) , `/clinic/` (Boand Vet Clinic),
  `/bauer-center/` (Patricia J. Bauer Center), `/why/` (Why senior dogs),
  `/foster-needs/` (distinct from a general foster page), `/mailing-list/`
  (Mailchimp signup), `/adoption-questionnaire/` (LGL adoption form host page),
  `/donation/` (LGL donation form host page, distinct from the Ways to Give
  overview).

**Ways to Give long tail (recommend a hub + lightweight detail, not 12 pages):**
- `/planned-giving/`, `/legacy/` (legacy donor), `/silver-hearts-fund/`,
  `/sponsorship/`, `/stone-sponsors/`, `/sponsor-an-ad/`, `/tribute-card/`,
  `/dog-tag-donor/`, `/doggie-suite-sponsorship/`, `/wish-list/`,
  `/mural-plaque-sponsorship-donation/`,
  `/garden-river-stone-sponsorship-donation/`,
  `/welcome-room-plaque-sponsorship-donation/`.

The prototype `donate.html` already presents most of these as cards on one
"Ways to Give" page. That is the right pattern for a senior audience: one clear
hub, not a dozen near-identical donation pages. Recommendation: keep the hub,
and only build standalone pages where the live site truly needs a separate
indexed URL (planned-giving, legacy, silver-hearts-fund, wish-list are the
likely keepers; the plaque/stone/suite variants can be sections or anchor
targets on the hub).

### Prototype-only page

- `contact.html` exists in the prototype but there is NO `/contact/` on the
  live site. Decision needed (section 7).

---

## 4. UX design rules for this audience (research-grounded)

POMDR's audience skews older (senior adopters, senior people keeping their
dogs). The following are not generic; they are the rules that matter most for
this audience and are enforceable in CSS and markup. Sources at the end.

### Accessibility floors (WCAG 2.2 AA, tuned for older adults)

1. **Body text minimum 16px; prefer 17 to 18px.** Older eyes lose acuity and
   contrast sensitivity. AUDIT FINDING: the prototype `donate.html` uses 13 to
   14px on `.way-card p`, `.tax`, `.donate-meta`, `.way-link`, and the uppercase
   eyebrow. These are below the floor and must be raised. Sweep all pages for
   sub-16px text.
2. **Contrast at least 4.5:1 for body, 3:1 for large text and UI.** Older users
   lose contrast sensitivity, so do not place light gray text on white. AUDIT
   FINDING: the donate page uses `rgba(255,255,255,.5)` and `.4` text on a dark
   hero, which likely fails 4.5:1. Verify in browser and darken.
3. **Touch and click targets at least 44x44 CSS px** (WCAG 2.2 AAA 2.5.5,
   appropriate here, not just the 24px AA minimum). Larger targets help reduced
   dexterity. Audit buttons, nav links, amount chips, social icons.
4. **No hover-only or time-based interactions.** The prototype nav exposes
   submenus on hover; ensure full keyboard and click access (it does add
   focus handling, good) and that nothing depends on a timed hover.
5. **Visible focus indicators on every focusable element.** Keep the existing
   focus rings; verify they meet 3:1 against the background.
6. **Respect `prefers-reduced-motion`.** Already gated in the prototype; keep it.
7. **Line length 60 to 75 characters, generous line-height (1.5+).** Easier
   tracking for older readers. Constrain prose containers.

### Ease of use for both "quick" and "detailed" users

- **Clear primary actions on every page.** Adopt, Donate, Foster, Volunteer
  should be reachable in one click from anywhere (nav + footer cover this).
- **Progressive disclosure.** Lead with the simple path (one big CTA), let
  detail-seekers scroll for the full story. The dog detail pages already do
  this; apply it to program pages.
- **Breadcrumbs on every page below the top level** (section 5). Quick users
  ignore them; detailed users and screen-reader users rely on them for
  orientation. The live site has none; this is a clear, cheap win.
- **Predictable, consistent navigation.** Same nav and footer everywhere
  (the shared-layout injector already guarantees this).
- **Plain language labels.** "Placing Your Dog" reads more clearly than
  "Surrender" for a distressed owner; consider the friendlier label.

### Ethical UX (no dark patterns), especially around donations

- **Honest, opt-in giving.** No pre-checked recurring-donation boxes, no
  pre-selected add-ons, no guilt-trip confirm-shaming ("No, I don't love
  dogs"). The donate widget must make declining or choosing a smaller amount
  as easy as the suggested amount.
- **Transparent costs and destinations.** State where money goes (the fund
  cards already do this) and show the tax-deductible status plainly.
- **No fake urgency or countdowns.** Time pressure both manipulates and
  fails the "no time-based interaction" accessibility rule. Avoid it.
- **Accessible verification.** If spam protection is added to forms, use a
  honeypot, never a timed image CAPTCHA (excludes this audience). See
  RISK-REGISTER H2.
- **Equal visual weight for choices.** A "decline" or "one-time" option must be
  as visible as "donate monthly."

---

## 5. Breadcrumbs (currently absent everywhere)

Proposed scheme, matching the canonical IA. Breadcrumbs appear on every page
below the top level, directly under the nav, as an ordered list with
`aria-label="Breadcrumb"` and `aria-current="page"` on the last item.

```
Home / Adopt / Pebble
Home / Adopt / Adoption Events
Home / About Us / Our Team
Home / About Us / Ways to Give / Planned Giving
Home / Volunteer / Foster Needs
```

Rules: the trail mirrors the menu hierarchy, not the URL path; the current page
is plain text (not a link) with `aria-current="page"`; the separator is
decorative (`aria-hidden`); home is always the first crumb. Dog and team detail
pages use the parent section as the second crumb (Adopt, About Us).

---

## 6. Prioritized build plan

Tiers by traffic and conversion impact. Each page must clear the section 4
floors (16px+ text, 4.5:1 contrast, 44px targets, breadcrumb, keyboard).

**Tier 0, infrastructure (touches every page):**
1. Reconcile `pomdr-layout.js` SUBMENUS to cover every real page (keep the
   expanded top-level per decision 1): add Ways to Give under About, expand
   Helping Paw to Financial / Walking-Foster / Resources, add Foster Needs.
2. Add breadcrumbs to the shared layout (data-driven from the nav map).
3. Global sizing/contrast sweep: raise all sub-16px text, fix low-contrast
   grays, verify 44px targets.

**Tier 1, core conversion pages (already exist, refine to spec):**
adopt, dog detail, donate (Ways to Give hub), volunteer, foster-needs, about,
helping-paw, process.

**Tier 2, build the missing institutional pages:**
why, clinic, bauer-center, about/culture, mailing-list, adoption-questionnaire
(LGL host), donation (LGL host).

**Tier 3, Ways to Give long tail (hub + selective detail):**
planned-giving, legacy, silver-hearts-fund, wish-list as standalone; the
plaque/stone/suite/tribute/dog-tag variants as sections or anchors on the hub
unless a separate indexed URL is required.

**Tier 4, polish:** Contact decision, 404 page, search (optional), consistent
empty states.

---

## 7. Decisions (resolved 2026-06-09)

1. **Nav IA: keep the prototype's expanded top-level nav.** DECIDED. Retain the
   prototype's top-level set (Home, About, Adopt, Foster, Volunteer, Helping
   Paw, Surrender, Benefit Shop, Contact) plus the Donate button. The Tier 0
   nav work is therefore NOT a collapse to 6 items; it is reconciling the
   SUBMENUS so every real new.pomdr.org page is reachable. Specifically:
   - About submenu: add "Ways to Give" (the donate hub).
   - Helping Paw submenu: expand to Financial, Walking/Foster, Resources.
   - Volunteer or Foster submenu: add "Foster Needs" (distinct from the general
     foster page).
   - Keep Hospice under Adopt (prototype already does; see item 3).
2. **Contact page: add it.** DECIDED. Build a light Contact page (phone, email,
   the three addresses, hours, short message form). Keep Contact in the nav and
   footer. This improves on the live site, which has footer contact only.
3. **Ways to Give: hub plus selective standalone.** DECIDED. Keep the one Ways
   to Give hub (`donate.html`). Build standalone pages only for
   planned-giving, legacy (legacy donor), silver-hearts-fund, and wish-list.
   The plaque / stone / suite / tribute / dog-tag / sponsor-an-ad variants
   become sections or anchor targets on the hub, not separate pages.
4. **Hospice placement.** Keep under Adopt (prototype convention). Minor; can
   revisit.
5. **"Surrender" label.** Open, minor. Keep "Surrender" as the top-level label
   for now with "Placing Your Dog" as the submenu item (prototype already does
   this), revisit if staff prefer the gentler term.

---

## Sources

- W3C WAI, Developing Websites for Older People (WCAG and older users):
  https://www.w3.org/WAI/older-users/developing/
- W3C, WCAG 2.2: https://www.w3.org/TR/WCAG22/
- Designing for the Elderly considering WCAG:
  https://www.hurix.com/blogs/creating-an-accessibility-design-for-seniors-considering-wcag-guidelines/
- Button accessibility (target size guidance):
  https://beaccessible.com/post/button-accessibility/
- Ethical UX principles:
  https://uxcel.com/blog/ethical-principles-for-creating-responsible-and-user-focused-ux-design-582
- Dark patterns to avoid:
  https://uxplaybook.org/articles/ux-dark-patterns-and-ethical-design
