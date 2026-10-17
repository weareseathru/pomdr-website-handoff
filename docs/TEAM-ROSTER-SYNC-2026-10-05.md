# Team roster: www.pomdr.org is the roster of record

Written 2026-10-05 for Andrew. The live site (peaceofminddogrescue.org,
About Us and its 27 person.php pages) was read today and compared with the
`team` table New-Build's /team/ and /about/ print. Andrew's instruction: the
new site shows only the people on the live site, with their live bios, and
pressing a face opens a card with the bio, role and group.

The local test database (newpomdr-local, `pomdr_hub`) has been brought to the
live roster with `docs/team-roster-live-2026-10-05.sql`. The production table
belongs to Ronnie's admin (`/admin/staff.php`); either run the same SQL there
or make the changes below by hand. Every bio in the SQL is the live text,
verbatim, with its paragraph breaks.

## What differs between the live site and the staff table

**On the live site, not in the table (added, Office Staff, in live order):**

| Name | Role | Portrait used |
|---|---|---|
| Nisha Addleman | Behavior Coordinator | live site, images/people/aboutus/NishaAddleman.jpg |
| Courtney Young | Adoptions Coordinator | live site, CourtneyYoung.jpg |
| Briana Ryan | Volunteer Coordinator | live site, BrianaRyan.jpg |

Their `photo_url` points at the live site's full-size portrait (the only copy
we have). It works today; when the live site goes dark those three need their
portraits uploaded through the admin like everyone else.

**In the table, not on the live site (set to Draft locally; delete on
production, Andrew 2026-09-29 for Cortina):**

Cortina Whitmore (Benefit Shop), Emily Termotto, Elizabeth Ramsay, Hannah
Wakefield (all Office Staff).

**Role wording, live wins:**

| Person | Table said | Live says |
|---|---|---|
| Susan Ferriby | Treasurer | Secretary/Treasurer |
| Cameron Donegan | Interim Development Director | Development Director |
| Carmelita Garcia | Benefit Shop Sales Associate, part-time | Benefit Shop Manager |

**Bios.** All 27 replaced with the live text. Twenty-two differed only in
whitespace. Five had real updates on the live site: Andrew Zielinski (Pepper
added), Caitlin Lupien (the current pack), Cameron Donegan (the permanent
move), Dr. Erin Trannel (Ozzy added), Monica Rua (Frolic added).

**Order.** Office Staff now follows the live page (Carie, Cameron, Allison,
Caitlin, Andrew, Shanel, Alejandra, Nisha, Courtney, Briana). The other groups
already matched.

## Not changed

Group names and membership for Board, Advisory Council, Clinic Staff and
Benefit Shop Staff match the live site. Slugs are unchanged, so the earlier
WordPress /team/<name>/ addresses still hold. No email addresses are shown.
