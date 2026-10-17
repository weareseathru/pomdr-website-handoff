# Adopt system build plan

2026-09-23. Branch `design/adopt-and-look` in `New-Build/` (local, not pushed).
Decisions behind it: `docs/NEW-BUILD-STUDY-2026-09-23.md` section 4, answered
by Andrew the same day.

## Goal

Let a visitor find the right dog fast: a filterable, searchable, sortable
adopt page with a "help me choose" matcher, real pages for every listed dog
that is not adopted, a thumbnail wall for adopted dogs, and a dog page that
puts Adopt and Sponsor above the story.

## What the live site does today (www.pomdr.org, crawled 2026-09-23)

- `adopt.php`: no search, filter or sort. Four status pages linked as tabs
  (Adoptable, Other Adoptable, Hospice, Adopted). Whole-card links, "Looks like:".
- `adopted.php`: 3,613 thumbnails with names and **no links**. Kept as is in spirit.
- `dog.php?id=N`: photo gallery with CSS radio-button thumbnails (no JS),
  "Looks like", age with "(estimated)", Adopt and Sponsor buttons that pass
  `?dogname=` to the forms.

## Commits, in order

| # | Change | Files |
|---|---|---|
| 1 | Two new statuses, and public queries that can never show a draft, an inactive record or a Community Care animal. One rule for "has a public page". | `db/migrations/016-sponsor-and-pending.sql`, `db/schema.sql`, `includes/functions.php`, `includes/db.php`, `build/build.php`, `public/pets/detail.php` |
| 2 | Derived facets (size, age band, senior, sex, statuses, tags) in one helper, and the new dog card as a component | `includes/pet-facets.php`, `includes/pet-card.php`, `includes/pet-list.php`, `site.css` |
| 3 | Adopt page: count band, status tabs, search, filter chips, sort, matcher. Works with no JS as a full grid. | `public/adopt/index.php`, `public/assets/js/adopt.js`, `includes/header.php` (optional `$page_scripts`), `site.css` |
| 4 | Adopted wall: thumbnails, no links, find-your-dog search | `public/adopted/index.php`, `includes/pet-thumb.php`, `site.css` |
| 5 | Dog page: status, `~age` meta, Adopt + Sponsor above the story, no-JS gallery, sanitised bio | `public/pets/detail.php`, `includes/functions.php`, `site.css` |
| 6 | Adoption form prefill from `?dogname=` into LGL `field_21` | `public/assets/js/form-prefill.js`, `public/adoption-questionnaire/index.php`, new `public/sponsor-a-dog/` |
| 7 | Hospice page (missing), status tabs on every list page | `public/hospice/index.php`, `includes/pet-status-tabs.php` |
| 8 | Design.md sections for all of the above | `Design.md` |

Read, not changed: `README.md`, `Sources.md`, `includes/images.php`,
`admin/pet-edit.php` (statuses appear there automatically from `pet_statuses()`).

## Acceptance

- Every listed dog that is not adopted has a page; no card links to a 404.
  Adopted dogs have no page and no link.
- With JavaScript off, /adopt/ shows every dog and every card works.
- With JavaScript on: search by name, breed or words in the story; filter by
  size, age, sex and status; sort; the count is announced; the URL carries the
  state so a filtered view can be shared and Back works.
- `~13 yrs` on cards and pages, never "13 years old".
- Adopt button on a dog page opens the questionnaire with the name prefilled.
- 390 and 1440: no horizontal scroll, targets at 44/48px, nothing under 16px
  (the recorded action bar exception aside), axe clean, `php bin/check.php` clean.
- No invented dog data. Traits beyond what the database holds are not shown.

## Found on the way, for Ronnie

1. `first_acf_value()` keeps only the first value of the WordPress status
   checkbox, so a dog that is Adoptable and Foster Needed imports as Adoptable only.
2. `map_status()` expects `foster` and `courtesy`; the real stored values are
   `Foster Needed`, `Courtesy Listing` and `Adoption Pending`. All of them
   currently import as **draft** and would vanish from the public site.
3. `get_pets_by_status()` does not exclude drafts, inactive records or
   Community Care animals (fixed in commit 1).

## Outcome (same day)

Built as planned, 11 commits on `design/adopt-and-look`, not pushed. Partials
sit flat in `includes/` beside `page-intro.php`, matching the repo, rather than
in a `components/` folder. Also fixed on the way: the breadcrumb had no CSS at
all, and the band's accent phrase failed contrast on every banded page.
Verified: all 150 published addresses render clean through the build's own
renderer; Lighthouse accessibility 100 on `/adopt/` and a dog page.
