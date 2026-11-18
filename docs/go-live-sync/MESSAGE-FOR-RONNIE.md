# Message for Ronnie (paste into a tracker task or an email)

**Title:** Run the go-live data sync: dogs, staff and events to match www.pomdr.org (tested SQL ready)

Ronnie, I'd like the new site's dogs, staff and events to match www.pomdr.org
now, and POMDR has the authority to make these changes. They are all data, so
they need running on production. Nothing has been changed there.

**What is ready.** One tested SQL file, already pushed to your repo:

- `db/sync/2026-11-13-match-live-site.sql` in your repo (one transaction, safe to run twice)
- `db/sync/README.md` beside it (what it does, how it was tested, five things to know first)
- `db/sync/2026-11-13-match-live-site-REPORT.md` (every change in plain words)

**How it was tested.** The 2026-11-15 backup was loaded into a separate local
database and the file was run against it twice. The second run changed nothing.
All pages rendered clean, and every list was compared with the live site by
name.

| | Now | After | Live site |
|---|---|---|---|
| Adoptable dogs | 83, of which 13 are on the live site | 76 | 76 |
| Hospice dogs | 5 | 6 | 6 |
| Courtesy listings | 0 | 10 | 10 |
| Staff | 28 | 27, same people and order | 27 |
| Upcoming events | 0 | 6 | 6 |

**What it changes.**

- Staff: every title, order and bio as the live site has them; unpublishes
  Cortina Whitmore, Emily Termotto, Elizabeth Ramsay and Hannah Wakefield; adds
  Nisha Addleman, Courtney Young and Briana Ryan.
- Events: three upcoming adoption events and three special events (plus the six
  ongoing What's Happening items added on `/special-events/`).
- Dogs: bio, breed, sex, age, weight, sponsors and flags from the live pages for
  67 matched adoptable dogs, 4 recently adopted, 6 hospice, 9 new rows for dogs
  with no `animals` row, 10 courtesy rows, and `is_available = 0` for 72 dogs
  the live site no longer lists.

Every UPDATE names its row by id and name. Nothing is deleted.

**Your call on three things** (detail in the README):

1. 24 dogs get the old site's photo addresses until
   `import-shelterluv-photos.php` covers them.
2. Biggie matches his POMDR row (`id = 1611`, `is_cc = 0`), and Kelpie (`2165`)
   and Zephyr (`300`), both `In Foster Home (regular)` in Shelterluv, have
   `is_cc = 0` cleared and public slugs assigned so all 76 adoptable dogs match
   `www.pomdr.org/adopt.php` 1:1.
3. This is a snapshot. POMDR-18, publishing that follows the source, is still
   the lasting fix.

**To run:** `mysql prod < db/sync/2026-11-13-match-live-site.sql` on the admin box. It prints
a before and after count.

Andrew Z.
