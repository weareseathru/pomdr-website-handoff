# Dog listing check, 2026-11-12

Drafted as a comment for POMDR-18 ("Make sure the right animals are marked
available"). Not posted: the agent's tracker write was blocked, so Andrew posts
it or approves the post.

How it was measured: dog names read from the preview's /adopt/ and /hospice/
and from the live site's adopt.php; `animals` and `shelterluv_animals` read
from `db/backups/pomdr-20261111.sql.gz` in Ronnie's repo; joined on the
Shelterluv number (`pom-a-<n>`). Read-only throughout.

---

**Check run 2026-11-12 (for Andrew, during the photo pass).** The dogs the
public site publishes have drifted a long way from the dogs POMDR has in care.
The Shelterluv sync itself is healthy (last fetch 2026-11-12 06:30); the gap is
the `is_available` tick, which nothing keeps current.

| | Count |
|---|---|
| Dogs the new site publishes (`is_available = 1`) | 88 |
| ...whose Shelterluv status is Healthy In Home (adopted) | 59 |
| ...Deceased | 6 |
| ...Transferred Out | 4 |
| ...No Rescue Groups (from foster) | 2 |
| ...no Shelterluv link | 1 |
| ...still in care (In Foster Home, Hospice) | 16 |
| Dogs in care in Shelterluv (In Foster Home regular or temp, Awaiting Foster, Hospice, Pending Acceptance) | 89 |
| ...that the new site publishes | 16 |
| ...that have no `animals` row at all | 15 |
| Dogs on the live site today (adopt.php) | 81 |
| ...also on the new site | 13 |

So at launch the Adopt page would show about 70 dogs who are no longer
available and hide about 70 who are.

Examples on the live site and missing from the new one: Pumpkin, Grim,
Tofurky, Licorice, Gabby, Evie, Barbara Walters, Bagel Boss, Cream Cheese,
Thistle.

Examples published on the new site but not on the live site: Gingerbread,
Sugar Cookie, Root Beer, Tootsie Roll, Adele, Ahi Tuna.

In care in Shelterluv with no `animals` row: Ashanti, Dr. Pepper, Jason, Kenji,
Lacey, Tulle, Zero, Abby, Allee, Manny, Nico, Toto, Skye (26-994), 26-1010,
26-953.

**Question for Ronnie:** should publishing follow Shelterluv's status (with the
tick as an override, the way `sync-adopted` already moves a dog to Adopted), or
stay a hand tick that staff keep up? Until launch staff are still maintaining
the old site, so a hand tick will keep drifting. Nothing was changed; this is
read-only evidence. The public pages need no change either way: they print
whatever `is_available` says.

## What it means for the photo pass

- Part 3 of the photo packet (88 dogs) is mostly dogs who are no longer
  available. Hold the dog photo decisions until the list is right, then rebuild
  that part.
- Shelterluv serves photographs at 1,028 px at most (header of
  `bin/prefer-shelterluv-photos.php`), so uploading larger originals there will
  not raise the ceiling. 1,028 px is sharp for the 532 px a main photo fills.
- 84 of the 89 in-care dogs already have four or more photos in Shelterluv, so
  once the right dogs publish, most dog photos need no work.
