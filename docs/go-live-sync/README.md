# Go-live data: everything from www.pomdr.org, gathered for one mass update

Prepared 2026-11-13 and refreshed 2026-11-16 at Andrew's request. Nothing on
production has been changed: staff, events and dogs are rows in the production
database, which this machine cannot reach. The update is built, tested and
waiting in Ronnie's repository as `db/sync/2026-11-13-match-live-site.sql`.

## What is here

| File | What it is |
|---|---|
| `MASS-UPDATE-REPORT-2026-11-13.md` | Every change in plain words: each person, each event, each dog |
| `mass-update-2026-11-13.sql` | The update itself. One transaction, safe to run twice |
| `data/live-dogs.json` | All 96 dogs from the live site (2026-11-16): 76 adoptable, 4 recently adopted, 6 hospice, 10 courtesy. Bio, breed, sex, age, weight, sponsors, status and every photo address |
| `data/live-staff.json` | All 27 people on the About page, in order, with title, group, full bio and portrait |
| `data/live-events-and-news.json` | The 3 upcoming adoption events and all 9 What's Happening items, with text, links and images |
| `build_mass_update.py` | Writes the SQL and the report from the three data files and a production backup |
| `MESSAGE-FOR-RONNIE.md` | The request to run it, ready to paste |

The photographs themselves (dog photos, 27 portraits, 9 What's Happening
images) are in `photo-library/live-site-2026-11-13/`, which is kept out
of git.

## How it was tested

Ronnie's 2026-11-15 backup was loaded into a separate local database and the
update run against it twice; the second run changed nothing. Then, on the local
site:

- the adoptable, hospice and courtesy lists were compared with the live site by
  name: 76 of 76, 6 of 6, 10 of 10;
- all 76 adoptable dog pages carry the live site's bio;
- the staff page has the same 27 people in the same order with the same titles,
  and all 27 bios match word for word;
- the six upcoming events (3 adoption events on `/events/`, 3 special events on
  `/special-events/`) and the six ongoing What's Happening items on
  `/special-events/` are listed;
- all rendered pages build with no PHP warnings.

## Biggie, Kelpie and Zephyr

Biggie matches his POMDR record (`id = 1611`, `pom-a-19864`, `is_cc = 0`).
Kelpie (`id = 2165`, `pom-a-20275`) and Zephyr (`id = 300`, `pom-a-2773`) are in
Shelterluv as `In Foster Home (regular)` and on the live adoptable page, so the
update clears `is_cc = 0` and assigns their public slugs so all 76 available
dogs appear 1:1.

## To refresh it

The live site changes daily (it listed 81 adoptable dogs on 2026-11-12 and 76
on 2026-11-13). To rebuild against a newer day, read the live pages again into
`data/`, parse the newest backup, and run `build_mass_update.py`.

## Local preview

http://127.0.0.1:8777/ runs on the copy of production with the update applied.
`includes/config.local.php` points at the local database `pomdr` for that; the
earlier local database was `pomdr_hub`.
