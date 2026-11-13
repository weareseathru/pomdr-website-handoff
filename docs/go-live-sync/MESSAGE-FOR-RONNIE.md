# Message for Ronnie (paste into a tracker task or an email)

**Title:** Run the go-live data sync: dogs, staff and events to match www.pomdr.org (tested SQL ready)

Ronnie, I'd like the new site's dogs, staff and events to match www.pomdr.org
now, and POMDR has the authority to make these changes. They are all data, so
they need running on production. Nothing has been changed there.

**What is ready.** One tested SQL file, already pushed to your repo:

- `db/sync/2026-11-13-match-live-site.sql` in your repo (one transaction, safe to run twice)
- `db/sync/README.md` beside it (what it does, how it was tested, five things to know first)
- `db/sync/2026-11-13-match-live-site-REPORT.md` (every change in plain words)

**How it was tested.** The 2026-11-12 backup was loaded into a separate local
database and the file was run against it twice. The second run changed nothing.
All 149 pages rendered clean, and every list was compared with the live site by
name.

| | Now | After | Live site |
|---|---|---|---|
| Adoptable dogs | 83, of which 13 are on the live site | 68 | 71 |
| Hospice dogs | 5 | 6 | 6 |
| Courtesy listings | 0 | 9 | 9 |
| Staff | 28 | 27, same people and order | 27 |
| Upcoming events | 0 | 7 | 7 |

**What it changes.**

- Staff: every title, order and bio as the live site has them; unpublishes
  Cortina Whitmore, Emily Termotto, Elizabeth Ramsay and Hannah Wakefield; adds
  Nisha Addleman, Courtney Young and Briana Ryan.
- Events: four adoption events and three special events.
- Dogs: bio, breed, sex, age, weight, sponsors and flags from the live pages for
  63 matched dogs (matched on Shelterluv number), 5 recently adopted, 6 hospice,
  5 new rows for dogs with no `animals` row, 9 courtesy rows, and
  `is_available = 0` for 72 dogs the live site no longer lists.

Every UPDATE names its row by id and name. Nothing is deleted.

**Your call on three things** (detail in the README):

1. 23 dogs get the old site's photo addresses until
   `import-shelterluv-photos.php` covers them.
2. Kelpie, Zephyr and Biggie are flagged `is_cc = 1` and are left alone.
3. This is a snapshot. POMDR-18, publishing that follows the source, is still
   the lasting fix.

**To run:** `mysql prod < db/sync/2026-11-13-match-live-site.sql` on the admin box. It prints
a before and after count.

Andrew Z.
