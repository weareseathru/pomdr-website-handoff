# Pre-Launch Audit, 2026-07-07

A consolidated sweep run before the migration to production (www.pomdr.org).
Three parallel reviews: a security review, a breakage and URL-flexibility
review, and a content-parity scan against the current live site. This file is
the running punch list. Items fixed in the same session are marked FIXED with
the file touched; everything else is a launch to-do.

The mirror is https://newpomdr-local.local. Production will be www.pomdr.org.
Every URL-based thing must survive that hostname change.

---

## A. Security

Full defensive review of the theme, the MU plugins, secrets, and CI.

| # | Severity | Item | Status |
|---|----------|------|--------|
| S1 | Medium | `[foster_a_pet]` shortcode echoed the dog title, permalink, and ACF fields (looks_like, sex, age, weight) unescaped: a stored-XSS path (a crafted dog name breaks out of the img `alt` attribute). | **FIXED** functions.php: `esc_url`/`esc_attr`/`esc_html` added; sex routed through `pom_acf_sex_display()`. |
| S2 | Low | `single-pets.php` echoes the `youtube_video` oEmbed field raw. Safe as an oEmbed field, but an editor can iframe an arbitrary URL. | Open. Wrap in `wp_kses` allowing only a YouTube iframe, or validate the host. (Left raw by an earlier decision; revisit at launch.) |
| S3 | Low | `divi-child-integration/temp/` ships unfinished scratch templates (one literally echoes `aaa`; one uses `urlencode` in an href). Dead weight, and `template-blog-list.php` is registered as a selectable page template. | Open. Delete `temp/` before launch; confirm no page uses "Custom Blog List". |
| S4 | Info | Production hardening not yet set: `DISALLOW_FILE_EDIT`, XML-RPC, REST user enumeration (`/wp-json/wp/v2/users`, `?author=1`), login rate-limiting/2FA. The MCP config uses `NODE_TLS_REJECT_UNAUTHORIZED=0` (fine for the self-signed `.local` cert, never carry to production). | Open. Set in the production `wp-config` / host / security plugin. |

**Verified clean:** no committed QA/auto-login backdoor (grepped working tree + full history); Local mu-plugins match the repo with no leftover `zz-*` fixtures; the `pomdr_editor` role has no privilege-escalation path; MCP abilities are capability-gated and input-sanitized with no delete ability; no SQL string concatenation anywhere; JS `innerHTML` writes are all static/developer-authored, no user input; no unsafe file/remote/exec sinks; no committed credentials; CI workflows use least-privilege `contents: read`.

**Owner action (not a repo item):** rotate the `andrew@pomdr.org` admin app password (recorded in a local, git-ignored session note as plaintext in `~/.zshrc`), matching the earlier GitHub PAT rotation.

---

## B. Migration blockers (must fix before / during the hostname switch)

| # | Item | Status |
|---|------|--------|
| MB1 | Hero rotator injected `adopt.html` / `foster.html` CTAs (404 on WordPress) after the first slide change. | **FIXED** assets/js/pomdr-home.js: now `/adopt/` and `/foster/`. |
| MB2 | ~30 primary CTAs (donation, surrender, Helping Paw, perpetual care applications) point at the legacy `peaceofminddogrescue.org` PHP server. If that host is retired, they die. | Decision needed: keep the legacy host up, or swap to the confirmed LGL forms (see parity section for the real form IDs). |
| MB3 | Newsletter form (`front-page.php`) previews success but discards the email (no Mailchimp backend, risk B2). | Open. Wire the Mailchimp/LGL embed (live newsletter is LGL `gTxA6GdJUmSjS2J4hXvcKg`). |
| MB4 | Fancybox loads from an unpinned jsdelivr CDN (`version: null`). A breaking release or a CDN outage breaks dog-gallery lightboxes. | Open. Vendor and pin it (GSAP is already vendored under `assets/vendor/`). |
| MB5 | CPTs (`pets`, `events`, `team`) and ACF groups are registered by plugins/DB, not the theme. Nav targets `/foster/` and `/events/` must exist as WP pages. | Open. The migration must copy the plugin set + pages, not just the theme, or grids render empty and dog URLs 404. |
| MB6 | The `/pets/{id}/` rewrite rule flushes only on `after_switch_theme`, which will not fire on production (theme already active). | Open. Add one manual Settings > Permalinks > Save (or `wp rewrite flush`) to the migration runbook. |
| MB7 | `single-pets.php` builds the Adopt link with `site_url()` (breaks under a subdirectory install). | Open. Use `home_url()` like the other 14 call sites. |
| MB8 | Migrated Divi content referenced a stale `https:pomdrsite.local/...` host (malformed scheme), 404ing images on every page. | **FIXED on the mirror** via serialization-safe search-replace to `/wp-content/...`. Run the equivalent on production. (See the smoke-suite PR.) |

**Verified sound:** no hardcoded `newpomdr-local.local` / `new.pomdr.org` / `localhost` in runtime PHP/JS/CSS (only in docs). CSS is fully self-contained (only `data:` URIs, no font CDNs). All chrome/footer/permalink URLs use `home_url()` / `get_permalink()` / `get_stylesheet_directory_uri()`. GSAP + ScrollTrigger are vendored locally. `php -l` clean on all theme PHP; no PHP 8-only syntax.

---

## C. Breakage risks (things that could silently misrender)

| # | Item | Status |
|---|------|--------|
| R1 | Mobile nav opened then instantly closed on the homepage (the toggle was bound twice: pomdr-home.js + pomdr-nav.js). | **FIXED** assets/js/pomdr-home.js: removed the duplicate binding. |
| R2 | Events grid printed a bogus end date equal to "today" (a bare `event_end` time run through the date parser). | **FIXED** functions.php: `event_end` treated as a time and appended, not date-parsed. |
| R3 | `[foster_a_pet]` printed the literal "Array" for sex (a checkbox array). | **FIXED** functions.php: routed through `pom_acf_sex_display()`. |
| R2b | `events_shortcode` filtered to only `event_type = Special Event` and never hid past events, so real events could vanish and old ones lingered. | **FIXED** functions.php: upcoming-only, any type, soonest first, query bounded to 50. |
| R4 | "Custom Blog List" page template `template_include`s a file that only exists in `temp/`. | Open. Remove the registration or move the file. |
| R5 | Many `get_field()` calls are unguarded, so deactivating ACF Pro fatals the front end. | Open. Add an early bail/stub when ACF is inactive. |
| R6 | Team grid orders by string not number (`meta_value` vs `meta_value_num`) and drops members with no `sort`; dogs with no `feature` meta are invisible to `[pet_home]`; MCP-created dogs never set `feature`. | Open. Use `meta_value_num` + order in a second pass; drop the `meta_key` JOIN. |
| R7 | Adopt-button prefill uses three different mechanisms (`dogname`, `field_21`, a localized base) that never line up, so prefill never reaches LGL (risk B1). | Open. Rename to the one confirmed param (`field_21`). |
| R8 | `[acf_if]` registered twice; the second silently wins. | Open. Remove one. |
| R10 | Several `posts_per_page => -1` queries will grow forever (`[adopted_pets]`, events). | Partly addressed (events now capped at 50). Cap `[adopted_pets]` or paginate. |
| R15 | Homepage hardcoded a permanent promo banner and four invented events instead of the ACF/CPT-driven versions. | **FIXED** front-page.php: events now render from the CPT (real, upcoming, soonest-first). The static promo banner remains; swap for `[promo_banner]` when the ACF options are set. |

**Data assumptions a staff editor can break (hardening backlog):** dog with no photo (empty box), empty status array, non-numeric age, missing `looks_like`/`age`/`weight`/`sex` hides the whole vitals line, two dogs with the same name share one favorite/prefill, `date_adopted` unset hides recent-adopted, foster date range across a year reads backwards, event with no `event_start` is dropped. The editor-hardening branch (required photo/name/weight/age validation) covers several of these; merge it.

---

## D. Content parity vs the live site (top gaps)

The mirror's structure and design are strong; the gaps are missing content and
broken form links. Ranked:

1. **Events: FIXED this session.** All 11 real events (6 adoption + 5 special: Wishbone, Earthwise, Pet Pals, Concours for a Cause, Scotts Valley Festival, Carmel Plaza Concert, Paws and Prosecco, Cork and Fork, For the Love of Dogs, Free Pet Trust Seminar) were entered into the Events CPT verbatim from the live site, with 5 flyer images. The homepage and `/events/` now show them.
2. **Broken/missing application forms.** `/surrender/` and `/helping-paw/` buttons point at legacy URLs that 404 even on live; `/volunteer-application/` embeds no form and cites the wrong ID. Real live LGL IDs to wire: intake/surrender `qaEwtEwFynxBALn0rRh7AA`, volunteer `HsUdXNpwCEoU8Kc9e_H6Hw`, sponsor-a-dog `k1_dO7VqC3nd7fsh5tFJCA`, Helping Paw finance `POMDRHelpingPawFinanceApplication.php` (+ Spanish), newsletter `gTxA6GdJUmSjS2J4hXvcKg`. Adoption `utzjcNEZaqAcJk3QURlQmw` and donation `62FAoG7Obtf81TYETJMN3Q` are already correct.
3. **`/adopted/` renders empty** ("No dogs to show") though the counter claims 12; adoptable data is stale (0 Foster Needed, none of the current live roster). Foster-needed roster of ~15 dogs with bios is missing.
4. **Donor recognition wiped:** Keystone Donors / sponsor category lists (`thankyou.html`) and tribute donor listings have no mirror home.
5. **Max's Helping Paws Fund** page missing (reduced to a tile).
6. **Jobs page factually wrong:** says no openings; live is hiring a Relief/Sunday Veterinarian ($800-$1100/day, starting May 2026, apply carie@pomdr.org).
7. **About page** missing Clinic Staff, Benefit Shop Staff, Impact Reports and 990 downloads; staff rosters diverge from live (confirm which is current).
8. **Placeholder content presented as real:** testimonials (3 invented quotes), media (3 invented items vs ~30 real), videos (3 "video placeholder" boxes vs 17 real), culture values rewritten. The invented testimonials/media conflict with the no-fabrication rule and must be replaced with real content or clearly removed.
9. **Donate page** leaks to the old `.html` site where local pages exist, and omits several give-methods (Dog Tag Donor, Doggie Suite, Plaque/Stone, Corporate, Annual).
10. **Perpetual Care FAQ** page missing; smaller copy drops: adoption fees ($155 to $305) and the follow-up policy on `/process/`, Spanish paragraphs on `/surrender/` and the Spanish Helping Paw button, NPR link on perpetual care.
11. **Terms and Privacy** are self-flagged placeholders (fine pre-launch, blocking at launch, pending counsel).

Pages verified at good parity: courtesy listings, hospice, bauer-center, clinic, benefit-shop, resources, fostering, wishlist, planned-giving, legacy, sponsorship tiers, dog-tag/doggie-suite sponsor pages.

---

## What was fixed in this session (2026-07-07)

Escaping XSS (S1/R3), homepage CTA 404s (MB1), mobile-nav double-bind (R1),
events end-date + type/upcoming filtering (R2/R2b), homepage hardcoded events
replaced with the real CPT (R15), all 11 real events populated (D1), the paw
watercolor animation un-occluded on home, the "Larger text" feature made
meaningfully larger, footer text enlarged, and a marginal event-card contrast
fix. See the session PR.
