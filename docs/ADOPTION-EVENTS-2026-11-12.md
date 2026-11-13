# Adoption events: getting the live site's events onto the new site

For Andrew to pass to Ronnie (or post on the tracker). Nothing on production
was changed.

## What is wrong

The new site's Adoption events page says "No adoption events are scheduled".
The live site lists four. The new site's events came from one run of the
importer on 2026-09-18 and nothing has been added since, so every event in the
table is now in the past.

## The fix that already exists

`build/migrate/import-archive-events.php` copies events from the old site's
database (`archive.adoptionevent`, refreshed nightly) into `events`. Its own
header says re-running it before the cutover is safe and expected, and that
everything it adds arrives as a draft.

On the admin box:

    sudo -u www-data php build/migrate/import-archive-events.php            # shows what would change
    sudo -u www-data php build/migrate/import-archive-events.php --write    # writes it

Then, in the admin, publish the new drafts under Events. They appear on the
public site at the next hourly build.

Until launch this needs repeating whenever staff add events to the old site,
because the importer is not scheduled (on purpose: after the cutover it would
overwrite staff corrections).

## The four events the live site lists today (2026-11-12)

| When | Where | Address | Note on the live site |
|---|---|---|---|
| Sat Nov 14, 11:00am to 1:30pm | Patagonia Outlet | 415 River Street, Suite C, Santa Cruz | POMDR information table and adoptable dogs. |
| Sun Nov 22, 11:00am to 1:30pm | Pet Pals | 3660 Soquel Drive, Soquel | POMDR information table and adoptable dogs. |
| Sun Nov 22, 3:00pm to 6:00pm | Cypress Inn Yappy Hour | Lincoln St and 7th Ave, Carmel-by-the-Sea | Drinks, snacks, and a relaxed afternoon with your pup. Dogs welcome! |
| Sun Dec 13, 3:00pm to 6:00pm | Cypress Inn Yappy Hour | Lincoln St and 7th Ave, Carmel-by-the-Sea | Drinks, snacks, and a relaxed afternoon with your pup. Dogs welcome! |

The live page prints no year; these weekdays match 2026. The live site spells
the Carmel street "Lincol St", which looks like a typo for Lincoln.

## What was done locally

The four were added to the local test database only, as published adoption
events, so the local preview (http://127.0.0.1:8777/events/) shows the page
full. The script refuses any database that is not on this machine.
