# Round 5 brief: the full-site review, as a work order for every page

Written 2026-11-23 for whoever picks this up. Read `START-HERE.md` first.

## 1. What happened

A senior staff reviewer and her husband went through the whole new site and
sent a written review of three things: the top banners and menus, the home
page, and the footer. It is the most thorough review the site has had. The
review in her own words, note by note, is in `docs/CARIES-FEEDBACK.md` under
Round 5.

Every note was built and pushed to Ronnie's `main` in three commits, then
**taken back out the same day at Andrew's request**. The site today is as it
was before the round.

| Commit | What it held | State |
|---|---|---|
| `7461df8` | Top of every page: bands, logo, Donate, menus, Title Case, a new Upcoming Events page | reverted |
| `9673938` | Home page, and the videos page in newest-first order | reverted |
| `509d10c` | Footer | reverted |
| `e5660c0` | The revert of all three | on `main` |

The three commits are a worked reference. `git show 7461df8` (and the other
two) shows exactly how each note was answered, with comments explaining why.
`git revert e5660c0` would bring all of it back at once. **Do not do that
without Andrew.** He has not said which parts he wants kept, changed or
dropped, and that is the first thing to settle (section 6).

The review covered three areas, but the same faults exist on the other 38
pages. The job is to carry the review's intent across the whole site, not
only to the pages she happened to name.

## 2. The rules the review implies, for every page

Each rule is a note from the review, stated generally.

| # | Rule | From her note |
|---|---|---|
| R1 | **Fewer words.** A section says one thing. Cut explanations that repeat what a photo, a title or the next page already says. | "Way too wordy/busy overall"; mission cut to one sentence; newsletter cut to one line |
| R2 | **One section-title style.** One colour, one alignment, one line. No label over a second title. | "Section titles should all use consistent colors and formatting" |
| R3 | **Title Case** for menu items, and so for the page titles they name. | "They should all be in title case" |
| R4 | **One way to each place, under one name.** No two buttons to the same page with different wording. | "All of the buttons are also included elsewhere, sometimes with different wording. This makes navigation confusing." |
| R5 | **A photo and a short line beats a block of text.** Cards for programs get a photograph. | "Too many words and nothing to catch the eye" |
| R6 | **Every group of cards has a title** saying what the group is. | Asked for titles over the two help sections |
| R7 | **Dated things are newest first**, everywhere they appear. | Videos |
| R8 | **Numbers must mean what they say**, with no filler. | "Your gifts have not created 1800 volunteers"; "17+ years doesn't seem useful" |
| R9 | **A "see all" link is a normal button**, the same on every page. | See All Videos |
| R10 | **Nothing can be mistaken for the whole.** A partial list says it is partial or is not shown. | Six adoptable dogs read as all of them |
| R11 | **Behaves the same every time.** A control does one thing on every screen. | Menus |
| R12 | **The organisation's name is always on screen**, on every device. | Phone logo |
| R13 | **Few shapes.** Neighbouring elements share one corner style. | Three banner styles |
| R14 | **Don't repeat the menu** elsewhere on the page. | Footer link lists |

## 3. The areas she reviewed, and what was built

Built and reverted; the commits show how.

**Top of every page** (`7461df8`). Tagline band slim, with a paw, shown on
phones without "since 2009". Monterey County Gives as a square purple band on
every page. Full logo with the name on phones; Donate at the right beside the
menu button. A menu name opens its list and never navigates; each section's
own page is the first item in its list; all labels Title Case. New page
`/upcoming-events/`.

**Home page** (`9673938`). Slideshow of photos with one short phrase each and
no buttons. One title style (paw, capitals, centered, blue). Mission in one
sentence. The three programs as photo cards with the current website's words.
Adoptable dogs removed. The three newest videos; `/videos/` put in the live
site's order and the 2026 film "This is Our Message to You" added (it is
still missing from the site today). Numbers without the subtitle or the years
figure. "Next up" on the left; "See all events" button. Newsletter reduced to
a title, one line, one button.

**Footer** (`509d10c`). Link lists and Donate button removed. "501(c)(3)"
said once. The Bauer Center photograph added.

## 4. Every page, measured

Measured on 2026-11-23 from the rendered pages (words are those inside the
page body, not the header or footer). "Titles" counts the serif section
titles by alignment. Dog pages and staff pages are generated from one
template each and are not listed.

| Page | Words | Titles (centered / left) | Buttons | What round 5 implies here |
|---|---|---|---|---|
| `/` | 774 | 0 / 7 | 13 | 6 title(s) with a blue italic accent (R2); 13 buttons: check for the same destination under two wordings (R4); everything in round 5; see section 3 |
| `/404/` | 124 | 1 / 0 | 2 | 1 title(s) with a blue italic accent (R2); icon-and-text cards: photo cards instead? (R5) |
| `/about/` | 6,332 | 10 / 2 | 3 | titles mix centered and left (R2); 7 title(s) with a blue italic accent (R2); long: cut words (R1); very long; numbers band (R8); 42 headings |
| `/adopt/` | 1,527 | 0 / 0 | 2 | chrome only |
| `/adopted/` | 4,785 | 0 / 0 | 2 | numbers shown here too (R8) |
| `/adoption-questionnaire/` | 215 | 0 / 0 | 0 | chrome only |
| `/become-a-foster/` | 281 | 1 / 0 | 2 | 1 title(s) with a blue italic accent (R2) |
| `/benefit-shop/` | 316 | 0 / 1 | 3 | 1 title(s) with a blue italic accent (R2) |
| `/clinic/` | 413 | 2 / 0 | 4 | 2 title(s) with a blue italic accent (R2) |
| `/contact/` | 165 | 3 / 0 | 2 | 3 title(s) with a blue italic accent (R2) |
| `/courtesy-listings/` | 142 | 0 / 0 | 0 | chrome only |
| `/donate/` | 812 | 2 / 1 | 4 | titles mix centered and left (R2); 3 title(s) with a blue italic accent (R2); numbers shown here too (R8) |
| `/events/` | 86 | 0 / 1 | 3 | 1 title(s) with a blue italic accent (R2) |
| `/foster-needs/` | 848 | 1 / 1 | 5 | titles mix centered and left (R2); 2 title(s) with a blue italic accent (R2); 5 buttons: check for the same destination under two wordings (R4) |
| `/fostering/` | 1,882 | 3 / 1 | 6 | titles mix centered and left (R2); 4 title(s) with a blue italic accent (R2); long: cut words (R1); 6 buttons: check for the same destination under two wordings (R4) |
| `/get-help/` | 221 | 0 / 0 | 4 | icon-and-text cards: photo cards instead? (R5) |
| `/helping-paw-application/` | 192 | 0 / 0 | 1 | chrome only |
| `/helping-paw-application/es/` | 197 | 0 / 0 | 1 | chrome only |
| `/helping-paw-volunteer-application/` | 208 | 0 / 0 | 1 | chrome only |
| `/helping-paw/` | 504 | 2 / 1 | 5 | titles mix centered and left (R2); 3 title(s) with a blue italic accent (R2); 5 buttons: check for the same destination under two wordings (R4) |
| `/hospice/` | 559 | 0 / 1 | 5 | 1 title(s) with a blue italic accent (R2); 5 buttons: check for the same destination under two wordings (R4) |
| `/intake-questionnaire/` | 225 | 0 / 0 | 0 | chrome only |
| `/jobs/` | 1,198 | 2 / 1 | 3 | titles mix centered and left (R2); 3 title(s) with a blue italic accent (R2); long: cut words (R1) |
| `/mailing-list/` | 94 | 0 / 0 | 0 | chrome only |
| `/media/` | 617 | 0 / 0 | 2 | chrome only |
| `/perpetual-care-program/` | 916 | 2 / 2 | 5 | titles mix centered and left (R2); 4 title(s) with a blue italic accent (R2); long: cut words (R1); 5 buttons: check for the same destination under two wordings (R4) |
| `/privacy/` | 11 | 0 / 0 | 0 | chrome only |
| `/process/` | 294 | 1 / 1 | 2 | titles mix centered and left (R2); 2 title(s) with a blue italic accent (R2) |
| `/resources/` | 1,531 | 0 / 0 | 2 | chrome only |
| `/special-events/` | 511 | 1 / 1 | 3 | titles mix centered and left (R2); 2 title(s) with a blue italic accent (R2) |
| `/sponsor-a-dog/` | 121 | 0 / 0 | 1 | chrome only |
| `/surrender/` | 586 | 1 / 2 | 3 | titles mix centered and left (R2); 3 title(s) with a blue italic accent (R2) |
| `/team/` | 5,480 | 5 / 0 | 2 | chrome only |
| `/terms/` | 13 | 0 / 0 | 0 | chrome only |
| `/testimonials/` | 9,133 | 5 / 0 | 2 | chrome only |
| `/videos/` | 1,302 | 1 / 0 | 2 | 1 title(s) with a blue italic accent (R2); order is not newest first; one film missing (R7) |
| `/volunteer-application/` | 209 | 0 / 0 | 1 | chrome only |
| `/volunteer/` | 445 | 1 / 1 | 5 | titles mix centered and left (R2); 2 title(s) with a blue italic accent (R2); 5 buttons: check for the same destination under two wordings (R4) |
| `/why/` | 923 | 4 / 1 | 4 | titles mix centered and left (R2); 5 title(s) with a blue italic accent (R2); long: cut words (R1) |

Reading the table:

- **Twelve pages mix centered and left-aligned titles** (R2): About, Donate,
  Foster Needs, Fostering, Helping Paw, Jobs, Perpetual Care, Process, Special
  Events, Surrender, Volunteer, Why. Pick one alignment for the site.
- **Twenty-one pages use a blue italic word inside the title.** The review did
  not name it, but it is a second colour in a title. Decide once: keep it
  everywhere or drop it everywhere.
- **The longest pages** that are prose, not lists: About (6,300 words),
  Fostering (1,900), Jobs (1,200), Why, Perpetual Care. Start R1 there.
- **The same three figures** (4,500+ dogs, 1,800+ volunteers, years) appear on
  Home, About, Donate and Adopted. Whatever is decided for R8 applies to all
  four.

## 5. How to do it without breaking things

1. **Settle the decisions first** (section 6). Most of this round is taste,
   and taste was the reason it was reverted.
2. **Change the shared piece, not each page.** `New-Build/Components.md`
   lists every shared component and the one place that changes it everywhere.
   A title style is one rule in the stylesheet; a menu label is one array.
3. **One rule at a time, across all pages,** not one page at a time. A push
   that applies R2 to every page can be looked at and judged as a whole.
4. **Show before pushing.** Put before-and-after screenshots at phone and
   desktop width in front of Andrew for anything a visitor would notice.
5. **Run the gate** (`scripts/dev.sh check`), add a line to `Design.md`
   section 11, and log the outcome against each note in
   `docs/CARIES-FEEDBACK.md`.

Words on a page are typed into that page's file. Staff-supplied wording goes
in exactly as written.

## 6. Open questions, in the order they block work

| # | Question | Who answers |
|---|---|---|
| 1 | Which parts of round 5 does Andrew want back as built, which changed, which dropped? | Andrew |
| 2 | The review asks for the menu's "Become a Foster" to go to the Volunteer Application because the new page "has significant problems". Andrew finds the page harmless and wants to know what the problems are before changing it. Eleven other links go to that page. | Andrew asks the reviewer |
| 3 | Slideshow phrases and photographs. The reviewers offered a draft list of phrases for Carie to refine. | Carie |
| 4 | Titles and photographs for the two help sections ("How you can help", "How POMDR can help you" were her suggestions). | Carie |
| 5 | The number of people Helping Paw has helped, for By the Numbers. No such figure exists in the repository, the database or the current website. It must come from POMDR. | Staff |
| 6 | Section titles: blue or purple? She accepts either, consistently. | Andrew |
| 7 | Should a menu open on hover as well as on a click? The reverted build made it click-only so a mouse and a finger behave the same. | Andrew |

## 7. Smaller things found along the way

- The home page showed a courtesy-listing dog first among its six. Fixed
  2026-11-23 (`24d319d`); it is not part of the revert.
- On a section's front page the "More under" list includes the page itself
  (Adopt, Volunteer, Events). The fix is in `7461df8`, reverted with it.
- `Design.md` still describes a top strip holding a phone number and a
  footer that is a site map of four lists. The strip has held only the
  tagline since October; the footer lists are what the review asks to remove.
- The Monterey County Gives banner must be switched off after December 31.
