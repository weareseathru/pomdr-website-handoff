# POMDR Redesign: Deployment Roadmap

A staged path from the finished Local mirror to a live launch, with the decisions
and content we need from the team. Written for the stakeholder review.

Owner: Andrew Z. Last updated: 2026-06-29.

---

## Where we are now (done)

The redesign is fully built and verified on the Local WordPress mirror, inside
the existing Divi child theme:

- Global header + footer, homepage, and **27 page templates** ported 1:1 from the
  approved prototype.
- **Daily content stays staff-editable**: dogs, events, and staff render from the
  existing custom post types through shortcodes, so the backend POMDR already
  uses is unchanged.
- Adopt page has working search, category filters, sort, and live counts over the
  editable dog grid.
- Accessibility baseline (senior-readable type, focus states, reduced-motion, a
  "Larger text" toggle), brand-correct colors and copy rules.
- The deploy itself is presentation-only and reversible (see
  `docs/DEPLOYMENT-GUIDE.md`).

---

## Decisions we need from stakeholders (blocks launch)

| # | Decision | Why it matters | Recommendation |
| --- | --- | --- | --- |
| D1 | **Donation flow**: keep the legacy `POMDRDonation.php` links, or move to the confirmed LGL donation form (`62FAoG7Obtf81TYETJMN3Q`)? | The Donate page and "Donate" buttons currently point at the legacy processor (matches the prototype). | Confirm which processor is canonical, then we wire all donate CTAs to one path. |
| D2 | **Adoption form**: confirm the LGL adoption form id (`utzjcNEZaqAcJk3QURlQmw`) and that `field_21` is the dog-name field. | The adoption page now embeds this live LGL form with dog-name prefill. | Verify the id and prefill field with whoever manages LGL. |
| D3 | **`culture` page URL**: top-level `/culture/` (matches prototype) or nested `/about/culture/` (current, sensible IA)? | Cosmetic URL depth only; page renders correctly either way. | Keep nested under About, or flip the page parent in one click if you prefer top-level. |
| D4 | **Real content vs prototype placeholders**: testimonials, the "POMDR Videos" YouTube IDs, the homepage stats (250+, 3,200+, 1,800+), and the impact numbers. | These are prototype sample values and must be real before launch. | Team supplies real quotes, video links, and current numbers (see content checklist). |
| D5 | **Domain / launch target**: which site is "live" (new.pomdr.org vs the primary domain) and the cutover plan. | Determines staging, DNS, and redirect work. | Confirm the target and whether this replaces the current homepage immediately or soft-launches. |
| D6 | **Sponsor-a-dog form**: confirm the destination for the "Sponsor {Name}" CTA (a dedicated LGL sponsor form id, or a route through the donation form with a prefill param). | The dog detail page now shows a sponsor CTA at parity with adopt; it points at the legacy `POMDRSponsorDog.php` as an interim (see RISK-REGISTER B4). | Confirm the target, then update the single `POMDR_SPONSOR_FORM_BASE` constant (or add a `pomdr_sponsor_form_url` filter). |

---

## Content the team provides (the photo + copy checklist)

Everything here is uploaded through wp-admin with no code (see
`docs/STAFF-CONTENT-GUIDE.md`):

- **Dog photos + details** for current dogs (Pets): featured photo, status,
  ~age, sex, weight, breed/looks-like, personality blurb, gallery. The cards and
  the Adopt page update themselves.
- **Staff photos + bios** (Team): a clear headshot, name, role, group (Board /
  Office / Advisory / Clinic / Benefit Shop), short bio.
- **Events** (Events): title, type, dates, details, optional image.
- **Real testimonials** to replace the three sample quotes.
- **YouTube video links** for the "POMDR Videos" row.
- **Current impact numbers** (dogs placed, volunteers, years, counties).
- **Promo banner** copy for any active campaign (optional).

> All dog and people photos are real (volunteer/staff taken). We do not generate
> AI images of dogs.

---

## Phases

### Phase 0 - Build complete (done)
Mirror built, ported, and self-audited. Two subagent audits (prototype quality +
mirror alignment) on record; fixes folded in.

### Phase 1 - Content + decisions (team, this/next week)
- Resolve D1-D5 above.
- Team loads real dog/staff/event content and photos into the mirror (or directly
  into live's backend, since content is shared data).
- Replace placeholder testimonials, videos, and stats.
- Final copy pass for brand voice (no em dashes, ~age format, primary actions as
  buttons).

### Phase 2 - Staging QA (engineering)
- Deploy the theme to a staging copy of the live site (Deployment Guide, Option A).
- Quality gates (CLAUDE.md):
  - Accessibility: axe-core, zero new violations; screen-reader pass on the
    header, Adopt filters, and a dog profile.
  - Performance: Lighthouse; LCP < 2.5s, INP < 200ms, CLS < 0.1; no Core Web
    Vital regression > 3 points.
  - Visual regression: screenshots of key pages vs the prototype.
  - Cross-browser + real mobile devices (320 to 1920).
- Confirm forms submit end to end (adoption LGL, donation path) and confirmation
  emails fire.
- Confirm redirects (Redirection plugin) cover old URLs.

### Phase 3 - Production launch
- Backup live (files + DB).
- Deploy the theme files (Deployment Guide), flush permalinks, clear caches.
- Set the static front page; create the promo-banner ACF fields.
- Walk the verification list; run axe + Lighthouse on production.
- Announce. Watch analytics, form submissions, and error logs for the first week.

### Phase 4 - Post-launch
- Monitor Core Web Vitals and form conversion.
- Backfill any remaining pages (e.g., individual dog-profile polish).
- Keep the prototype as the design lab; port approved changes through the
  porting checklist so the gap never reopens.

---

## Audit status (2026-06-29)

Two subagent audits were run and their fixes folded in:
- **Prototype review:** no blockers. Fixed the white-on-blue contrast (now
  `--blue-700`, AA), removed residual hardcoded orange and off-palette green/pink,
  and added `:focus-within` so nav dropdowns are keyboard/touch accessible.
- **Mirror alignment:** all 30 pages serve our chrome + templates correctly, no
  Divi-builder leakage, images and content parity good. Two P0s were fixed: the
  dog-profile `/pets/{id}/` URLs (rewrite rules flushed) and the sitewide Contact
  link (now `mailto:info@pomdr.org`, no contact page needed).

## Redirects to configure before launch (Redirection plugin)

Old prototype/legacy paths that should 301 to the canonical WP slugs, in case any
external links, emails (Mailchimp), or bookmarks point at them:
- `/thank-you/` to `/thanks/`
- `/resources/` to `/recources/`
- (the `/foster/` to `/foster-needs/` redirect already exists and works)

## Open engineering follow-ups (non-blocking, tracked)

- **Slug typo:** the resources page slug is `recources` (misspelled). Recommend
  renaming it to `resources` with a redirect from `recources` before launch.
- **Flush permalinks on every deploy** (Settings to Permalinks to Save) so the
  `/pets/{id}/` dog-profile URLs resolve. This is in the deploy runbook; it is the
  most important post-deploy step.
- `clinic` / `benefit-shop`: staff sections were removed to match the prototype;
  the `[clinic_staff]` / `[benefit_shop_staff]` shortcodes still exist if we want
  to surface staff later.
- Individual dog-profile template (`single-pets.php`) polish to the prototype
  dog-page design.
- Dog-list page headers (adopted, hospice, courtesy-listings) can get the same
  template treatment as Adopt if desired.

See `docs/HANDOFF-PORT-AUDIT.md` and `docs/PORTING-CHECKLIST.md` for the full map,
and `docs/DEPLOYMENT-GUIDE.md` for the upload mechanics.
