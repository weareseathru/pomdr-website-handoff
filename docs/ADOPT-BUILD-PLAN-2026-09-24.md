# Adopt system build plan, v2: on Shelterluv and the Hub

2026-09-24. Supersedes `ADOPT-BUILD-PLAN-2026-09-23.md`, whose branch
(`design/adopt-and-look`) was built on the WordPress-era data model and is kept
only as a parts bin. New branch: `design/shelterluv-adopt`, cut from
`origin/main` at `4e7ad53`.

## Goal

The same adopt experience as v1 (finder, cards, dog page, adopted wall), with
every dog fact read from Shelterluv or owned by the Hub, and nothing from
WordPress or Divi.

## Ground rules (Andrew, 2026-09-24)

- **Shelterluv is pull only.** Nothing here writes to it, ever.
- **No WordPress or Divi values, names or conventions** in code.
- **One profile per dog.** Temporary foster is a switch plus dates on that one
  profile, not a second record.
- **Adoption form rebuild on Shelterluv is pinned.** No more work on the LGL
  `field_21` prefill.
- Old dog URLs may lapse.

## How a dog fact reaches a page

```
Shelterluv API --(bin/sync-shelterluv.php, hourly, read only)--> shelterluv_animals
                                                                      |
   Hub-owned columns on animals (name_public, is_available,           |  joined on
   description, the new foster/sponsor/pending switches) -------------+  pom-a-<ID>
                                                                      v
                                        one read helper in includes/db.php
                                                                      v
                                             pet-facets.php -> cards, pages
```

Shelterluv fields are read from the mirror through **one helper**. `Animals.md`
section 5 plans `shelterluv_*` columns on `animals`, filled by an n8n upsert that
is not built yet. When it lands, only that helper changes. Fields read, and only
these: `Sex`, `Breed`, `DOBUnixTime`, `CurrentWeightPounds`, `Status`,
`InFoster`, and `Attributes` **where Shelterluv's own `Publish` is Yes**.

## Statuses

| Public badge | Comes from |
|---|---|
| Foster needed | Hub checkbox, or Shelterluv attribute `Needs Foster` (published), or status `Awaiting Foster` |
| Temporary foster needed, Oct 1 to Oct 14 | Hub switch plus start and end dates; hidden once the end date has passed |
| Sponsor needed | Hub checkbox |
| Adoption pending | Hub checkbox |
| Hospice care | Shelterluv status `Hospice`, or the existing `is_hospice` |
| Courtesy listing | the existing Hub `is_courtesy` |

New Hub columns in one migration (numbered after Ronnie's 023):
`is_foster_needed`, `is_temp_foster_needed`, `is_sponsor_needed`,
`is_adoption_pending`. The temp window reuses `foster_start_date` and
`foster_end_date`, already editable in `admin/animal-edit.php`.

## Traits for matching

Shelterluv's published attributes: Good with dogs, Good with cats, Good with
kids. They become card chips, finder filters and search words. A dog without
the attribute is not claimed to be bad with anything; the filter only finds
the dogs Shelterluv says yes for.

## Privacy

Everything public prints `name_public`, and nothing publishes without it,
including the adopted wall. `Medical`, `Behavior` and every other unpublished
attribute never leave the database.

## Order of work

1. Carry the clean v1 commits: forms and section kit, type scale and dark
   footer, breadcrumb, band accent contrast.
2. Migration and statuses; one public baseline and one "has a page" rule.
3. The Shelterluv read helper and facets.
4. Card, list, homepage.
5. Finder, tabs, hospice.
6. Adopted wall on `name_public`.
7. Dog page.
8. Sponsor page without prefill.
9. Design.md.

## Local data

A placeholder, not a source. `pomdr_hub` in Local's MySQL, loaded from the
2026-09-24 prod backup's animal tables only (no people, logins, clinic notes,
or foster people in the Shelterluv payload), then Ronnie's own
`bin/merge-matched-animals.php` replayed so it matches prod after the merge.

## Outcome (same day)

Built in the order above: 13 commits on `design/shelterluv-adopt`, local, not
pushed. Verified: all 117 published addresses render clean through the build's
own renderer; 19 finder tests and 13 admin-editor tests pass in a browser;
Lighthouse accessibility, best practices and SEO all 100 on `/adopt/` and a dog
page. Findings for staff are in Design.md section 9.
