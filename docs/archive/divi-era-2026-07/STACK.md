# STACK.md

The confirmed integration stack for the new POMDR block theme. Authored
2026-05-09. Status of each row reflects what the design package and the
DOM captures show, not what production may currently use. Andrew confirms
each row before integration code is written.

The HANDOFF.md spec calls this file out as the gating doc for integration
architecture: "Do not assume." Until a row's status is `confirmed`, design
no integration around it.

---

## 1. Source-of-truth dog records

| Field           | Value                                                  |
|-----------------|--------------------------------------------------------|
| System          | (TBD) ShelterLuv? PetPoint? Custom WordPress CPT?      |
| Confirmation    | TBD                                                    |
| Sync direction  | TBD                                                    |
| Photo handling  | TBD                                                    |
| Override layer  | WordPress side wins per HANDOFF.md, when set           |

Evidence in captures: dog detail URLs are `/pets/{integer-id}/` with IDs
in the ~2800 to ~3100 range. The site appears to use a WordPress custom
post type (post type slug: `pets`), backed by ACF (custom field grids:
`custom-acf-posts-grid-4`). No ShelterLuv-format IDs observed in markup.

**Decision needed from Andrew:**

1. Does POMDR use ShelterLuv? If yes, what's the access pattern (API key,
   widget, embed)?
2. If not, is the WordPress CPT the master, or is there an external
   spreadsheet that staff edit and then import?
3. If ShelterLuv is in use, which adapter pattern does the existing site
   currently use? "Custom plugin," "iframe widget," "scheduled sync,"
   etc.

If sync is required, HANDOFF.md commits us to **scheduled sync (every 15
to 60 minutes), not iframe and not live API**. Photos download to the WP
media library on sync. Override layer on the WordPress side wins over the
source data when set.

---

## 2. Donation processing

| Field           | Value                                                          |
|-----------------|----------------------------------------------------------------|
| System          | LGL (Little Green Light). CONFIRMED 2026-06-09.                |
| Confirmation    | Donation form is LGL form `62FAoG7Obtf81TYETJMN3Q`, embedded   |
|                 | as an iframe on the donation page.                             |
| Form pattern    | iframe embed (donation); query-param prefill for inquiry forms |

**CONFIRMED 2026-06-09 (donation form).** The donation page uses an LGL iframe.
Production implementation (deferred to the production stage, not applied to the
local prototype): on the `/donation/` page, replace the plain donation URL text
with this embed, inside a centered container (`max-width: 900px`, horizontal
auto margins):

```html
<div style="max-width:900px;margin:0 auto;">
  <iframe src="https://secure.lglforms.com/form_engine/s/62FAoG7Obtf81TYETJMN3Q"
          width="100%" height="800" frameborder="0"></iframe>
  <script src="https://secure.lglforms.com/form_engine/s/tfs_iframe.js"></script>
</div>
```

This is the same LGL pattern as the adoption inquiry form (see section 3),
which uses a different form id and a `field_21` prefill. The donation form does
not take a dog-name prefill.

Legacy note: the prototype `donate.html` still links its widget to the old
`POMDRDonation.php` processor and several `POMDRDonation.php?fund=...` funds.
Those fund-specific links were not part of this confirmation; confirm whether
each fund moves to LGL or stays on the legacy processor before production.

Evidence: a `<script>` block in the captured DOM at `new.pomdr.org` builds
an LGL iframe URL on page load and inserts it into `#adoption-iframe`.
Form id appears to be `utzjcNEZaqAcJk3QURlQmw`. Prefill happens via query
param `field_21` (the dog name pulled from the page URL's `dogname`
parameter).

```javascript
let iframeUrl = 'https://secure.lglforms.com/form_engine/s/utzjcNEZaqAcJk3QURlQmw';
if (petname) {
  iframeUrl += '?field_21=' + encodeURIComponent(petname);
}
```

This is an **adoption inquiry form**, not a donation form. The donation
processor is unconfirmed.

**Decision needed:**

1. Is donation flow LGL-backed (likely, given LGL is in use), Stripe-backed,
   GiveButter, or something else?
2. HANDOFF.md says: "No third-party iframe on the donation page. The
   donation form is ours." If LGL is the processor, we need either
   (a) an LGL API integration that lets us host the form ourselves, or
   (b) explicit acceptance that the donation page is an exception.
3. Are there any existing recurring-donation subscriptions that must
   survive cutover? If yes, the processor cannot change.

---

## 3. Forms (non-donation)

| Form                  | Likely backend     | Confirmation | Notes                                           |
|-----------------------|-------------------|--------------|-------------------------------------------------|
| Adoption inquiry      | LGL Forms         | confirmed    | Form id `utzjcNEZaqAcJk3QURlQmw`. Prefill via `field_21`. |
| Foster application    | LGL Forms         | likely       | Pattern matches but not directly captured.      |
| Volunteer application | LGL Forms         | likely       | Captured nav has `/volunteer-application/` URL. Confirm form id. |
| Surrender / placing   | LGL Forms         | likely       | Confirm form id.                                |
| Contact               | TBD               | TBD          | The design has a `contact.html`, the live site has no `/contact/` URL. New page. |

**Decision needed:**

1. List every LGL form id in use, the URL each is embedded on, and the
   prefill fields each one uses.
2. Should the new theme keep LGL iframes, or move to a WP-native forms
   plugin (Gravity Forms, WPForms, Fluent Forms, native block forms)
   with LGL as a webhook target?

The HANDOFF.md commitment is clear: every inquiry form posts back to the
source-of-truth animal record with the dog's `legacy_id` and current
`name` attached. The current LGL prefill pattern does this for
`adoption_inquiry`. Other forms must follow the same pattern.

---

## 4. Mailing list

| Field           | Value                                              |
|-----------------|----------------------------------------------------|
| Provider        | Mailchimp                                          |
| Confirmation    | Mailchimp use confirmed by global CLAUDE.md (email infrastructure note) |
| Integration     | TBD: API or hosted form?                           |
| Asset hosting   | mcusercontent.com (per global CLAUDE.md)           |

The global `CLAUDE.md` says: "Email infrastructure: Mailchimp, HTML
templates, images hosted at mcusercontent.com. Do not break existing
email flow; site content feeds into emails."

The DOM captures don't show a Mailchimp form on the homepage. The design
homepage has a newsletter input but it's React-only with no backend. The
new theme needs a real signup that posts to Mailchimp.

**Decision needed:**

1. Mailchimp API key access (newsletter signup via JS) or hosted form
   embed (Mailchimp's own form on a Mailchimp URL)?
2. Audience id (the Mailchimp list to subscribe to).
3. Is there a double-opt-in flow today, or single-opt-in?
4. Are tags or merge fields required at signup (e.g. interest in
   adoption vs. fundraising vs. events)?

---

## 5. WordPress base

| Field                 | Value                                |
|-----------------------|--------------------------------------|
| Platform              | WordPress (confirmed)                |
| Current theme         | Divi (confirmed via `et_pb_*` markup)|
| Target theme          | Divi **child theme** "POMDR 2026" (see §7, 2026-06-06) |
| FSE                   | No. Divi parent retained; child theme overlay |
| PHP minimum           | PHP 8.2 (Local mirror runs php-8.2.29) |
| WP version            | TBD                                  |
| Hosting               | TBD                                  |
| Staging URL           | https://new.pomdr.org/ (captured)    |
| Production URL        | https://www.pomdr.org/               |

**Decision needed:**

1. Hosting provider and the host's PHP / WP version constraints.
2. Whether the existing WP database carries forward (the dog post type
   and integer IDs survive) or whether we start clean and migrate
   content via WP CLI.
3. Whether `wp-content/uploads/2025/...` and `wp-content/uploads/2026/...`
   media paths must be preserved, or whether photos can be re-uploaded to
   the new theme.

---

## 6. Other plugins to evaluate

These plugins are referenced in HANDOFF.md or implied by spec needs.
None are confirmed in production yet.

| Plugin                            | Purpose                                        | Status |
|-----------------------------------|------------------------------------------------|--------|
| `@wordpress/create-block-theme`   | Scaffold the block theme                       | tooling, not a runtime plugin |
| Equalize Digital Accessibility Checker | Block publish on critical a11y violations | required by HANDOFF.md |
| Redirection (or Yoast Premium)    | Manage the redirects.csv at runtime            | TBD: which plugin |
| ACF (Advanced Custom Fields)      | Already in use on Divi site for custom fields  | Likely retain. Confirm. |
| Page builder plugin               | Banned by HANDOFF.md unless explicitly approved| n/a |

---

## 7. Decisions log

Each decision becomes one row here with date, decision, and one-sentence
rationale, so future maintainers know why something is the way it is.

| Date       | Decision                                              | Rationale |
|------------|-------------------------------------------------------|-----------|
| 2026-06-06 | Keep Divi. Ship the redesign as a **Divi child theme** (`divi-child-integration/`, "POMDR 2026"), not the FSE block theme `pomdr-2026`. | Preserve the existing Divi base build, staff editing surface, ACF `pets` CPT, LGL forms, and Redirection rather than rebuilding. Supersedes the "Target theme = custom block theme" row in §5 and the block-theme plan in WP-SCAFFOLD-NOTES.md. Approved in session; pending Andrew's PR sign-off. |
| 2026-06-09 | **Status canonical = live ACF `status` checkbox** (multi-value, Title Case). | The live data is the source of truth; the kebab-case "7 status" model is now display-only. Reconciled in CLAUDE.md §8 and the ACF export. |
| 2026-06-09 | **Dog URL canonical = integer `/pets/{ID}/`** (not slug). | Already in code and indexed for years; rename-safe. `redirects.csv` updated to stop asserting slug canonicalization. |
| 2026-06-09 | **Donation form = LGL `62FAoG7Obtf81TYETJMN3Q`** (iframe). | Confirmed by Andrew. Embed recorded in §2 for the production stage. |
| 2026-06-09 | **ACF schema version-controlled** via `divi-child-integration/acf-export-2026-06-09.json` (real export). | Replaces the aspirational, mismatched `inc/acf-fields.php`, which was removed. |
