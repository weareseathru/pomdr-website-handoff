# Go-live data: everything from www.pomdr.org, gathered for one mass update

Prepared 2026-11-13 at Andrew's request. Nothing on production has been
changed: staff, events and dogs are rows in the production database, which this
machine cannot reach. The update is built, tested and waiting in Ronnie's
repository as `db/sync/2026-11-13-match-live-site.sql`.

## What is here

| File | What it is |
|---|---|
| `MASS-UPDATE-REPORT-2026-11-13.md` | Every change in plain words: each person, each event, each dog |
| `mass-update-2026-11-13.sql` | The update itself. One transaction, safe to run twice |
| `data/live-dogs.json` | All 91 dogs on the live site: 76 adoptable, 6 hospice, 9 courtesy. Bio, breed, sex, age, weight, sponsors, status and every photo address |
| `data/live-staff.json` | All 27 people on the About page, in order, with title, group, full bio and portrait |
| `data/live-events-and-news.json` | The 4 adoption events and all 9 What's Happening items, with text, links and images |
| `build_mass_update.py` | Writes the SQL and the report from the three data files and a production backup |
| `MESSAGE-FOR-RONNIE.md` | The request to run it, ready to paste |

The photographs themselves (353 dog photos, 27 portraits, 9 What's Happening
images, 80 MB) are in `photo-library/live-site-2026-11-13/`, which is kept out
of git.

## How it was tested

Ronnie's 2026-11-12 backup was loaded into a separate local database and the
update run against it twice; the second run changed nothing. Then, on the local
site:

- the adoptable, hospice and courtesy lists were compared with the live site by
  name: 68 of 71, 6 of 6, 9 of 9;
- all 68 adoptable dog pages carry the live site's bio;
- the staff page has the same 27 people in the same order with the same titles,
  and all 27 bios match word for word;
- the seven events are listed;
- all 149 pages render with no PHP warnings.

## The three dogs it does not publish

Kelpie, Zephyr (was Sonny) and Biggie are on the live adoptable page. Their
records in the new system are flagged community care, which hides a dog from
every public page. Clearing that flag is a staff decision, so the update leaves
them alone.

## To refresh it

The live site changes daily (it listed 81 adoptable dogs on 2026-11-12 and 76
on 2026-11-13). To rebuild against a newer day, read the live pages again into
`data/`, parse the newest backup, and run `build_mass_update.py`.

## Local preview

http://127.0.0.1:8777/ runs on the copy of production with the update applied.
`includes/config.local.php` points at the local database `pomdr` for that; the
earlier local database was `pomdr_hub`.
