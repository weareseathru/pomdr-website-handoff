# Deployment Guide: shipping the POMDR redesign theme to live WordPress

How to take the redesigned Divi child theme from the Local mirror
(newpomdr-local.local) to the live WordPress site, keeping every custom field,
post, photo, form, and redirect intact.

Owner: Andrew Z. Last updated: 2026-06-29.

---

## 1. The one thing to understand first

**The theme is a presentation layer only.** It draws the pages; it does not own
your data. Everything below is stored in the database and in plugins, NOT in the
theme, so replacing the theme files does not touch any of it:

| Thing | Who owns it | Touched by a theme deploy? |
| --- | --- | --- |
| `pets` / `events` / `team` custom post types | Advanced Custom Fields Pro (DB) | No |
| All ACF fields (status, looks_like, age, weight, event_type, group, etc.) | ACF Pro (DB), schema in `divi-child-integration/acf-export-2026-06-09.json` | No |
| Every dog, event, staff member, page, post | Database | No |
| All uploaded photos / media | `wp-content/uploads` (DB + files) | No |
| URL redirects | Redirection plugin (DB) | No |
| Donation + adoption forms | Little Green Light (external, embedded by URL) | No |
| Menus, site settings, users | Database | No |

The theme adds: the page layouts (templates), the global header/footer (chrome),
the design CSS/JS, the card-rendering shortcodes, and one ACF options page (the
promo banner). That is all that changes.

**So the deploy is low-risk by design: you are updating the look, not the data.**

---

## 2. What is and is not editable after deploy (so staff know)

Per the project's editability model:

- **Staff-editable in wp-admin, no code (daily content):** dogs (Pets), events
  (Events), staff photos/bios (Team), courtesy listings (a Pets status), and the
  promo banner (an ACF options page). Editing a record updates its designed card
  automatically. See `docs/STAFF-CONTENT-GUIDE.md`.
- **Developer-managed (not edited in the Divi builder):** the homepage and the
  redesigned page layouts (they render from `front-page.php` and
  `page-{slug}.php`, which intentionally bypass the Divi builder), plus the
  header, footer, nav, and design system. Changing these is a code change.

This is deliberate: it is what keeps the site consistent and accessible while
still letting staff run the day to day.

---

## 3. Before you deploy (pre-flight checklist)

1. **Full backup of the live site** (files + database). Use the host's backup or
   a plugin (UpdraftPlus). Do not proceed without a restorable backup.
2. **Confirm the Divi parent version** on live matches the mirror. The child
   theme layers on Divi; a very different Divi version can shift things. Note the
   live Divi version and test against it.
3. **Confirm the ACF field names on live match the mirror.** The cards read exact
   field keys (`status`, `looks_like`, `age`, `weight`, `description`, gallery;
   `event_type`, start/end; team `title`, `group`). They match today because the
   mirror was cloned from live, but verify after any live ACF edits. The
   reference schema is `acf-export-2026-06-09.json`.
4. **Stage it first.** Deploy to a staging copy of live (most hosts offer one
   click staging) and walk every page before touching production.
5. **Pick a low-traffic window** and tell the team you are deploying.

---

## 4. Deploying the theme (the actual upload)

The live active theme is already the child theme **`divi-child`** (Theme Name
"Divi Child", Template "Divi"). You are **updating its files in place**, not
switching themes. Three ways, best first:

### Option A - Host staging + push to production (recommended)
1. Deploy the `divi-child-integration/` contents into the staging site's
   `wp-content/themes/divi-child/` (overwrite), via the host's Git deploy or
   SFTP.
2. Verify on staging (Section 6).
3. Use the host's "push staging to production" (files only, not the database, so
   you do not overwrite live content).

### Option B - SFTP/SSH overwrite
1. Connect to the live host with SFTP.
2. Upload everything inside `divi-child-integration/` into
   `wp-content/themes/divi-child/`, overwriting. Keep the folder name `divi-child`
   so it stays the active theme. Do not delete the folder (that would deactivate
   the theme); overwrite files in place.
3. If your host supports it, deploy from this Git repo instead of manual SFTP.

### Option C - WordPress admin upload (smallest sites only)
The admin "Add New Theme > Upload" refuses to overwrite an existing theme, so you
would have to deactivate/delete the current `divi-child` first, which briefly
breaks the live site. Only do this on a site you can take offline, and only after
a backup. Option A or B is safer.

> Do not change the theme folder name or the `Template: Divi` line. The redesign
> is additive on top of the existing child theme.

---

## 5. Right after deploy (required post-steps)

1. **Flush permalinks:** wp-admin > Settings > Permalinks > Save Changes (no edits
   needed). This makes the `page-{slug}.php` templates and any rewrites resolve.
2. **Set the front page:** Settings > Reading > "Your homepage displays: a static
   page" > Homepage = the Home page. `front-page.php` then renders the new
   homepage. (Confirm; the mirror is already set this way.)
3. **Create the promo banner fields:** the theme registers a "Promo Banner"
   options page; create its 8 ACF fields there (names are documented in
   `functions.php`), then it is staff-controllable. The site works without this;
   the banner just stays off until configured.
4. **Clear caches:** any caching/CDN plugin, host cache, and Cloudflare. Old CSS
   is the most common "it looks broken" cause right after deploy.
5. **Walk the verification list (Section 6).**

---

## 6. Verify (every deploy)

- Homepage: hero, sections, the live dog row, footer with the `info@pomdr.org`
  contact link.
- `/adopt/`: stats header, search, filter pills, sort, and the dog grid (cards
  from the CPT). Filter and search work.
- `/about/`: staff render from the Team CPT (Board, Office, Advisory).
- A few static pages: `/foster-needs/`, `/helping-paw/`, `/donate/`,
  `/surrender/`.
- The header (action bar + nav + tagline + clickable logo) and footer appear on
  every page; the old Divi header/footer do not show.
- `/adoption-questionnaire/`: the LGL adoption form loads.
- No horizontal scroll on mobile; headings are dark ink (not Divi purple).

Run an accessibility (axe) and Lighthouse pass on the homepage and a dog page
before announcing.

---

## 7. Rollback

If something is wrong: restore the `wp-content/themes/divi-child/` folder from
your pre-deploy backup (or revert the Git deploy), then flush permalinks and
clear caches. Because the database was never changed, your content is exactly as
it was. This is the safety of a presentation-only deploy.

---

## 8. Known gotchas

- **Divi Customizer heading color.** Divi forces `h1-h6` to purple site-wide; the
  theme neutralizes it (`main h1-h6 { color: inherit }`). If headings look purple
  after deploy, confirm `pomdr-chrome.css` loaded (cache).
- **Field-name drift.** If staff or a migration renamed an ACF field on live, the
  matching card goes blank. Keep field names matching `acf-export-2026-06-09.json`.
- **Page slugs.** The nav/footer/templates use real WP slugs (foster-needs,
  recources, thanks, adoption-questionnaire). If a live page slug differs, fix the
  slug or the link. There is no `contact` page; contact links use `mailto:`.
- **The homepage and redesigned pages are no longer Divi-builder editable.** That
  is intended (developer-managed). The data on them (dogs, events, staff) stays
  editable through the CPTs.
- **mcp-adapter / wp-migrate-db plugins** are dev tools; do not enable
  `mcp-adapter` write access against production.
