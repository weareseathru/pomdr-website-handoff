# Start here

For anyone joining the Peace of Mind Dog Rescue (POMDR) website rebuild.
Read this page first; it should take ten minutes. Everything else is linked
from it.

Last checked against the code: 2026-11-23.

## What this project is

POMDR is replacing its website (www.pomdr.org, which today forwards to
www.peaceofminddogrescue.org) with a new one. The new site is written and
most pages are built. It is not live yet. It is being reviewed by staff, page
by page, and corrected.

You can see the current state of the new site at
**https://pomdr-public.pages.dev/**. It rebuilds itself every hour.

## There are two repositories. Know which one you are in.

| | The site | The design record |
|---|---|---|
| Where | `github.com/ronniereid/pomdr` (private) | this repository |
| On Andrew's Mac | the `New-Build/` folder inside this one | `pomdr-website-handoff/` |
| Owner | Ronnie Reid | Andrew Z. |
| Holds | every page, the stylesheet, the admin, the database code | plans, audits, staff feedback, the copy, photo work, the old site's archive |
| Changes the website? | **Yes.** A push to `main` is on the test site within the hour | No |

`New-Build/` is a separate git clone that this repository ignores. A change
to a page is a commit in Ronnie's repository, not in this one.

## How the site works, in five lines

- Plain PHP and one stylesheet. No framework, no build tool, no npm, no page
  builder. A page is a file: `public/<name>/index.php`.
- Shared parts are in `includes/`: the header and menu, the footer, the
  opening band every page starts with, the dog card, the event list.
- All styling is `public/assets/css/site.css`, in numbered sections.
- The public site is **static**: every hour the admin server renders each
  page to HTML and publishes it. Nothing runs when a visitor loads a page.
- Dogs, events, staff and their photographs come from the database, through
  the admin at **admin.pomdr.org** (the "Hub"). Everything else on a page is
  words and markup typed into that page's file.

Ronnie's `README.md` in `New-Build/` is the full specification and is
unusually complete. Read its sections 1 to 5 before changing anything.

## What you can change where

| You want to change | Where it is done |
|---|---|
| A dog's listing, photos, status, whether it is published | admin.pomdr.org, Animals |
| An event | admin.pomdr.org, Events |
| A staff or board member, their portrait | admin.pomdr.org, Staff |
| The words on a page | that page's file, `New-Build/public/<page>/index.php` |
| The menu | the `$main_nav` array at the top of `New-Build/includes/header.php` |
| The footer, phone, addresses, hours | `includes/footer.php` and `includes/site-info.php` |
| The Monterey County Gives banner (on or off) | `site_campaign()` in `includes/site-info.php` |
| How anything looks | `public/assets/css/site.css` |
| A photograph placed in a page | `public/assets/img/`, after running it through `bin/prepare-page-image.php` |

The admin has sections for animals, the clinic, events, people, staff, users,
search and statistics. It has no editor for page wording or layout: those are
code, and they go through git.

## The documents that matter, in reading order

In Ronnie's repository (`New-Build/`):

1. `README.md` the specification. Sections 1 to 5, then 8 (how it deploys).
2. `AGENTS.md` the short list of rules that get broken most often.
3. `Brand.md` colours, type, voice, photographs, accessibility floors.
4. `Design.md` how every public page looks and why, and a change log of
   every push (section 11, newest first).
5. `Components.md` the shared pieces pages are built from, and how to change
   one everywhere at once.

In this repository:

6. `docs/ROUND-5-BRIEF-2026-11-23.md` **the work waiting for you**: the
   latest staff review, turned into a page-by-page work order.
7. `docs/CARIES-FEEDBACK.md` every round of staff feedback and what happened
   to each note. Look here before touching wording staff have commented on.
8. `docs/POMDR-FACTS-FROM-LIVE-SITE-2026-10-26.md` the fact sheet: names,
   numbers, addresses, program details, taken from the current website.
9. `docs/README.md` an index of everything else, and what is archived.
10. `CLAUDE.md` the standing rules, written for an AI assistant but true for
    a person too.

## The rules that have cost us time

These are the ones people (and AI assistants) get wrong. Each has a reason.

**Copy**

- **No em dashes, anywhere.** Use a comma, a period or parentheses.
- Say **"guardian"**, never "owner". US English.
- Dogs' ages that are approximate are written `~8`, not "about 8".
- Never invent a fact about POMDR or a dog: no numbers, names, ages or
  histories that did not come from staff, the admin or the current website.
- No AI-generated pictures of dogs or people. Every photograph is real.
- Staff want people to **fill out a form, not call**. A form button comes
  first; the phone is the fallback.

**Design**

- Two colours and what they mean: **blue** for finding a dog, **purple** for
  the care side (help for guardians, Donate). Plus warm neutrals.
- The primary action on a page is a real button near the top, never only a
  link inside a sentence.
- Body text 16px or larger, contrast 4.5:1 or better, tap targets 44px or
  larger, nothing that works only on hover. The readers are often in their
  seventies.
- The page must work with JavaScript switched off.

**Ronnie's repository**

- **Design only.** Add to and change the public pages, `includes/header.php`,
  `includes/footer.php`, `includes/site-info.php`, the stylesheet and the
  page images. **Never change existing lines** under `admin/`, `db/`,
  `build/`, `bin/`, `deploy`, `includes/db.php` or `includes/functions.php`.
  If a design needs something from those, ask Ronnie or work around it in
  the page.
- One branch, `main`. No pull requests. Check, then push.
- Nothing secret is committed. `includes/config.local.php` stays on the
  machine.
- Never write to the production database or publish to production without
  Andrew saying so in writing.

## Before every push to the site

From `New-Build/`, or run `scripts/dev.sh check` from this repository, which
does all of it:

1. `php bin/check.php` syntax, and a known trap with `?>` in comments.
2. `php tests/unit/run.php` 24 small checks.
3. Render every page (`bin/list-pages.php` and `build/render-page.php`);
   none may print a warning or come out nearly empty.
4. No em dash in the lines you added.
5. Nothing changed under the folders listed above as Ronnie's.
6. One line added to `Design.md` section 11 saying what changed and why.
7. Look at the page in a browser at phone width (390px) and desktop width.

Then commit, `git pull --rebase origin main`, and push. The test site shows
it after the next hourly build (the server pulls every five minutes between
6am and 11pm Pacific).

## Running the site on your own machine

Ronnie's README section 2 is the authority. In short: PHP 8.2 or newer,
`includes/config.local.php` (ask Ronnie for the values; never commit it), and

```bash
php -S 127.0.0.1:8777 bin/dev-server.php
```

then open http://127.0.0.1:8777/. `scripts/dev.sh up` in this repository
starts it for you and says what is missing.

`http://127.0.0.1:8777/patterns.php` shows every shared component on one
screen. It is never published.

## Who decides what

| Person | Decides |
|---|---|
| **Andrew Z.** | Everything about design and content, and what goes to Ronnie's `main`. He is the one you answer to. |
| **Ronnie Reid** | The repository, the admin (Hub), the database, the server, deployment, launch day. |
| **Carie** | Wording. She sends rounds of corrections and supplies photographs and phrases. |
| **Senior staff reviewer** | Sent the full-site review of 2026-11-23 (round 5). Exacting about consistency. |

Ronnie tracks work as tickets at https://projects.imerge.com (he issues the
access token). Tickets are named POMDR-1, POMDR-2 and so on.

## Where things stand (2026-11-23)

- Rounds 1 to 4 of staff feedback are built and on the test site.
- **Round 5**, the largest review, was built, pushed, and then taken back out
  the same day at Andrew's request. The work is in the history of Ronnie's
  repository (commits `7461df8`, `9673938`, `509d10c`; reverted by `e5660c0`)
  as a reference. The brief in `docs/` says what it asked for and what to do
  with it across every page. **Do not restore it wholesale without Andrew.**
- Open questions are listed at the end of that brief.
- Monterey County Gives is switched on. It must be switched off after
  December 31 (`site_campaign()`, set `'on'` to `false`).

## What is old and can be ignored

Until September 2026 the plan was a WordPress site on the Divi theme. That
was dropped. `Divi/`, `design-system/`, `responsive-review/`, `scripts/native/`
and everything under `docs/archive/` belong to that plan or to plans that
were replaced. They are kept for the record. Nothing in them runs.
