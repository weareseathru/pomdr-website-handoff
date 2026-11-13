# Go-live data sync: dogs, staff and events matching www.pomdr.org

Prepared 2026-11-13 at Andrew's request. Andrew has said POMDR has the
authority to make these changes. Nothing on production has been changed: this
machine has no connection to the production database, so the change set has to
be run by someone who does.

## What is here

- `prod-sync-2026-11-13.sql`: one transaction, about 500 statements.
- `build_sync_sql.py`: the script that wrote it, from the live public pages and
  the production backup `db/backups/pomdr-20261112.sql.gz`.

## How it was tested

The 2026-11-12 backup was loaded into a separate local database and the file
was run against it twice. The second run changed nothing. All 149 pages then
rendered with no PHP warnings, and the local lists were compared with the live
site name by name.

| | Before | After | Live site |
|---|---|---|---|
| Adoptable dogs | 83 (13 of them on the live site) | 68 | 71 |
| Hospice dogs | 5 | 6 | 6 |
| Courtesy listings | 0 | 9 | 9 |
| Staff | 28 | 27, same people, same order | 27 |
| Upcoming adoption events | 0 | 4 | 4 |
| Upcoming special events | 0 | 3 | 3 |

The three adoptable dogs it does not publish are listed under "Needs a person".

## To run it

On the admin box, against production:

    mysql prod < prod-sync-2026-11-13.sql

It prints a before and an after count. The public site picks the changes up at
the next hourly build.

## What it does

**Staff.** Unpublishes Cortina Whitmore and Emily Termotto (Andrew's request)
and Elizabeth Ramsay and Hannah Wakefield (also gone from the live About page).
Adds Nisha Addleman, Courtney Young and Briana Ryan, who are on the live page
and not in `team`. Updates three titles to the live site's wording: Carmelita
Garcia, Benefit Shop Manager; Cameron Donegan, Development Director; Susan
Ferriby, Secretary/Treasurer. Office staff take the live site's order. Rows are
unpublished, never deleted.

**Events.** Adds the four adoption events and three special events the live
site lists, published. If `build/migrate/import-archive-events.php --write` is
run instead, skip the four adoption events in section 2 of the file, or they
will be there twice.

**Dogs.**

- 63 dogs on adopt.php that already have a row: public name, bio, breed, sex,
  age, weight, sponsors and status flags as the live site has them, published.
  Matched by Shelterluv number where the dog has one.
- 9 dogs the live site marks "Recently Adopted": marked adopted, with a bio, so
  they show under Adopted.
- 6 hospice dogs: flagged hospice and published.
- 6 dogs on adopt.php with no `animals` row: a new row each, carrying the
  Shelterluv number where Shelterluv has the dog (Jason, Zero, Kenji, Tulle,
  Dr. Pepper, Ashanti).
- 9 courtesy listings: a new row each, flagged courtesy. These are other
  people's dogs and their write-ups carry the owner's contact details, exactly
  as the live page does.
- 72 dogs published today that the live site no longer lists: `is_available`
  set to 0 and nothing else.

Every UPDATE names its row by id and by name, so a row that has changed since
the backup is skipped.

## Things to know before running it

1. **Photos for 24 dogs point at the old site.** Those dogs have no photographs
   in `animals_photos`, so the file stores the live site's own image addresses
   (`https://www.peaceofminddogrescue.org/images/dogs/...`). `image_url()` hands
   full addresses back untouched, so they show at once. They are 600px and will
   break when the old site is switched off. `bin/import-shelterluv-photos.php`
   for those dogs, then `bin/prefer-shelterluv-photos.php --write`, replaces
   them with Shelterluv's.
2. **Needs a person: three dogs.** Kelpie, Zephyr (was Sonny) and Biggie are on
   the live adopt page, and their rows here (2165 Kelpie Van Wagner, 300 Zephyr
   Bendel, 1963 Koa Gonzales) are flagged community care (`is_cc = 1`), which
   keeps a dog off every public page. The file leaves them alone. If the flag is
   stale, clearing it in the admin and publishing the dog finishes the job.
3. **Adoption dates.** A recently adopted dog with no `adopted_date` gets
   today's date, so it sorts with the newest. The real dates are in Shelterluv.
4. **Dogs taken off the Adopt page are not marked adopted.** 59 of them are
   "Healthy In Home" in Shelterluv. Whether `sync-adopted` should pick them up
   is a question for Ronnie; this file only clears the published switch.
5. **It is a snapshot.** The live site changes daily. The lasting fix is the
   one in `Import.md` and POMDR-18: publishing that follows the source, not a
   one-off file. Until then this needs repeating, by re-running
   `build_sync_sql.py` against a fresh backup.

## Local preview

The local preview (http://127.0.0.1:8777/) now runs on the copy of production
with this file applied, so it shows what production will look like.
`includes/config.local.php` points at the local database `pomdr` for that;
the earlier local database was `pomdr_hub`.
