# Design council review: the page bodies

Written 2026-09-29 for Andrew, as the brief for redesigning every section page
body in New-Build. It builds on `docs/PAGE-BODIES-PREP-2026-09-25.md` (the
measured inventory) and on `New-Build/Design.md`.

**Status: BUILT and pushed to Ronnie's main on 2026-09-29** (commits `ddadb06`
the kit, `5094a93` the pages, then the review passes `6f94866`, `892d4a4`,
`f23ec14`). New-Build `Design.md` section 4f is the record of what shipped and
how it differs from this brief: the voice walls run as two staggered columns
with long quotes folded verbatim; standalone titles are centred; one rhythm
block sets the spacing; `/become-a-foster/` was added at POMDR's request; the
clinic sits under About Us rather than Get Help. Section 7 (what POMDR must
supply) still stands.

The reviewers, in the spirit of the room: the Stanford d.school (empathy first,
one job per page, prototype and test), IDEO (story, then structure), Whipsaw
(ruthless simplicity, craft, every element earns its place), and a
human-centred accessibility lens for a reader in their seventies on a phone.

---

## 1. Where the site stands

The chrome, the menu, the homepage, the opening bands and every dog page are
designed. The bodies of the section pages are not: 21 of 34 are plain text under
their band, most have no photograph below the band, and the longest page
(testimonials) is 8,874 words in one column with no headings, measuring 35,000
pixels tall. Perpetual Care is 1,185 words of prose and a seven-question FAQ.
Donate is sixteen equal cards. The information is all there; the pages do not
yet carry a reader through it.

The good news is that the hard part exists: the kit (`patterns.php`) already has
cards, steps, two columns, a quote, a numbers block, an FAQ and the closing
strip, all accessible and staff-editable. The new pages built on 2026-09-25
(Get help, Fostering, Why, Clinic, Bauer Center, Contact) show what the kit can
do. This review is about giving every other page the same treatment, and adding
the four or five patterns the kit still lacks.

## 2. The principles the council agreed on

1. **One job per page, stated in the band, answered by the body.** The band
   already asks the question; the body's first screen must start answering it,
   not restate it.
2. **Story before structure.** Lead with a real dog or a real person (photo,
   name, one sentence), then what we do, then how it works, then proof, then
   the ask. Repeat the ask at the end. This is the arc every program page
   follows.
3. **Chunk to the eye.** Nobody reads 1,000 words top to bottom. Break text
   into named sections of 60 to 150 words, one idea each, with a heading a
   scanner can act on. Headings are questions or plain statements, never
   labels ("Overview").
4. **Alternate the rhythm.** Text-left/photo-right, then photo-left/text-right,
   then a full-width tinted band, then white. Never two identical blocks in a
   row. A page should look different at every scroll stop.
5. **Photographs are content, not decoration.** Real POMDR people and dogs,
   with real alt text. Landscape for splits and bands, portrait for a person,
   square for a mosaic. Crop with `object-fit`, never distort. One strong photo
   beats four weak ones.
6. **Measure and size.** Body text 20px at 60 to 70 characters per line; the
   present full-width paragraphs on a 1440 screen run to 95 characters, which
   is why they feel heavy. Nothing under 16px. 48px targets.
7. **One ask per screen, and the same ask at the top and the bottom.** Purple
   for care and people, blue for finding a dog (Design.md section 6).
8. **Progressive disclosure for the long tail.** Reference material (resources,
   FAQs, the fine print) sits behind headings, jump lists or the browser's own
   `details`, so a quick reader passes it and a careful one finds it.
9. **Honest, calm, no dark patterns.** Especially on Donate: equal weight for
   one-time and monthly, no pre-ticked boxes, no urgency, the tax ID plainly
   stated.
10. **Everything works without JavaScript, prints cleanly, and is plain HTML a
    staff member can edit.** No new components that need a script.

## 3. The templates to add to the kit

Six patterns, each plain HTML plus stylesheet, each designed once and reused on
many pages. With these, every page below can be built without inventing
anything page-specific.

| Template | What it is | Rules |
|---|---|---|
| **Story split** | A landscape photo beside 2 to 4 short paragraphs and an optional button; alternates sides down the page | 1200px photo, 4:3 or 3:2; text column 60ch; on a phone the photo comes first |
| **Featured voice** | One large portrait or photo, a pull quote in the serif, a name and role line | One per page at most, near the top; the quote is 25 to 45 words |
| **Voice wall** | Quotes in a two- or three-column masonry-like grid, grouped under headings with a jump list; each quote is a card with name and role; longer ones fold behind `details` | Groups of 6 to 12 visible; the rest behind "Show more" (no script: `details`) |
| **Numbers band** | 3 or 4 figures with a one-line label each, on a tint | Real figures only; the same three everywhere (dogs rehomed, volunteers, years) |
| **Timeline** | Years down the left, a sentence and optional photo each | 4 to 8 entries; used on About and the Bauer Center |
| **Directory** | Grouped lists with a sticky jump list on wide screens; each entry is name, one line, link | For Resources, In the media, Videos; entries are `dl` or cards, never bare paragraphs |
| **Photo mosaic** | A grid of mixed square and landscape photos with captions | 6 to 9 photos; used for the volunteer parties, the shop, happy tails |
| **Photo steps** | The existing `steps`, each with a small photo | For Process, Surrender, Perpetual Care enrolment |

Existing and kept: the opening band, cards, steps, two columns, the quote, the
FAQ (`details`), the closing strip, the numbers block.

**Rhythm rule:** white, tint, white, colour, white. At most one deep colour band
per page, and it is the closing ask.

## 4. Page by page

Each entry: who arrives and in what state; the one job; what the page is today;
the prescription, as a sequence of templates; what it needs from POMDR.

### Adopt group

**/adopt/ (Adoptable Dogs).** Designed already (the finder). Leave.

**/process/ (How adopting works).** Arrives: someone who has found a dog and
is nervous about the next step. Job: show it is simple. Today: 275 words, no
headings, no photos. Prescription: *Photo steps* (apply, meet, home), then a
short *Story split* on what the fee covers and the lifetime commitment, then
the *FAQ* (spam-folder note, timing), then the closing strip "Ready? Meet the
dogs". Needs: three photos (a form on a table, a meet-and-greet, a dog arriving
home).

**/why/ (Why a senior dog).** Built 2026-09-25 on the kit. Add a *Featured
voice* at the top (one adopter with a photo) when POMDR supplies it.

**/courtesy-listings/ (Other adoptable dogs).** 59 words over the dog grid.
Fine as a list page. Add one sentence under the band saying who to contact,
and keep it a list.

**/hospice/ (Hospice care).** 122 words over the dog grid. Add a *Story split*
above the grid: what hospice means at POMDR, in the words of a foster, with
the Sponsor ask. Purple.

**/adopted/ (Happy tails).** The wall is designed. Add a *Numbers band* above
it (dogs rehomed since 2009) so the wall has a headline.

### Foster group

**/fostering/.** Built 2026-09-25: band, two paragraphs, twelve foster
parents, purple closing strip. Upgrade the twelve to the *Voice wall* template
so the page is 40% shorter and the reader chooses whom to read. Add one *Story
split* on "what we cover, what you give" with a photo of a foster home.

**/foster-needs/ (Dogs needing a foster home).** 403 words of prose above the
list of dogs. Cut to one *Story split* (why these dogs are waiting) with the
apply button, then the dogs. The prose about what fostering is belongs on
/fostering/, and should not be repeated.

### Get help group

**/get-help/.** Built 2026-09-25 as five cards plus the phone. Keep.

**/helping-paw/.** Arrives: a worried owner, often older, often on a phone,
sometimes their adult child. Job: tell them in one screen that help exists and
how to ask. Today: 414 words, definition list, no photos below the band; a
purple closing strip added 2026-09-29. Prescription: *Story split* (a Helping
Paw client and their dog, what we did), then three *cards* for the three kinds
of help (food and supplies, vet costs, walking and temporary foster), then the
eligibility and limits as an *FAQ*, then the strip. Spanish link kept in the
band. Needs: one real client story with a photo (with consent), and photos of a
dog walk and a food delivery.

**/surrender/ (Help placing your dog).** Arrives: the worst day. Job: calm
them, and get them to the phone or the form. Today: 416 words, one heading.
Prescription: keep the band short; then *Photo steps* (call or fill in the
form, we talk, we meet the dog, we find the home), then a *Story split* on what
happens to the dog with us (foster home, vet care, lifetime commitment), then
the Perpetual Care link as a card, then the strip with the phone number large.
Copy: second person, short sentences, no "surrender" in the visible text where
"placing" will do. Purple.

**/perpetual-care-program/.** Arrives: a planner, often 70+, with an attorney
in mind. Job: explain the promise and the enrolment. Today: 1,185 words, seven
FAQ headings as plain text. Prescription: a *Story split* stating the promise
in three sentences; *Photo steps* for enrolment (talk to us, the pet profile,
the pet trust with your attorney); a *two-column* block with the NPR story on
one side; then the seven questions as the kit's *FAQ* so each opens on demand;
then the strip ("Ask us about Perpetual Care", phone). Copy: replace every "he
or she" with "your dog" or "they". Needs: one photo of an enrolled dog at
home.

**/clinic/, /resources/.** Clinic was built 2026-09-25. Resources is 1,478
words in eight sections: make it the first use of the *Directory* template with
a jump list, and give each organisation a one-line description and a link,
nothing more.

### Volunteer group

**/volunteer/.** 808 words over thirteen headings, seven party photos at the
bottom. Prescription: a *Numbers band* (1,500 volunteers), then the roles as
*cards* in a grid (six to eight roles, one line each, "Apply" on every card),
then a *Story split* with one volunteer's words, then the party photos as a
*Photo mosaic* with captions, then the strip. Youth section removed
2026-09-29.

**/volunteer-application/.** A form page; the *Photo steps* above the frame
are enough. Leave.

**/jobs/ (Work with us).** 1,122 words, three headings. Prescription: openings
as *cards* (title, hours, one line, "Apply"), then a *Story split* on what it is
like to work here, then the former-employee quotes (owed since the July audit)
as a small *Voice wall*.

### Events group

**/events/.** Two lists since 2026-09-25. Give each event a card with the date
as a large block on the left (the homepage already draws this), and put a
*Photo mosaic* of past events below so the page is not empty in a quiet month.

**/mailing-list/.** 63 words and a form. Add one line on what arrives and how
often, and a sample-issue image if POMDR has one. Otherwise leave.

### About group

**/about/ (Our story).** 659 words, 28 team portraits, six headings, reports
and 990s. Prescription: a *Story split* for the founding, then a *Timeline*
(2009 founded, 2011 the Bauer house, 2019 the clinic, and the milestones POMDR
chooses), then the *Numbers band*, then the team grid as it is, then the
policies (food, DEI, vision) as an *FAQ* so they stay but do not dominate,
then reports and 990s as a short *Directory*.

**/testimonials/ (What people say).** The largest single improvement on the
site. Prescription: a *Featured voice* at the top (one photo, one quote), then
the *Voice wall* grouped into Adopters, Fosters, Helping Paw clients and
Volunteers with a jump list, six to nine visible per group and the rest behind
"Show more". The page drops from 35,000 pixels to about 6,000 while keeping
every word. Needs: staff to star ten quotes for featuring, and to tag each
quote's group where the source does not say.

**/media/ (In the media).** 541 words, no headings. *Directory* template:
outlet, date, headline, link, newest first, with a jump list by year.

**/videos/.** 1,199 words and embeds. Keep the embeds; put a one-line
description and a poster under each, in a two-column grid, with the featured
one large at the top (the homepage already does this in miniature).

**/bauer-center/, /contact/, /benefit-shop/.** The first two were built
2026-09-25. Benefit Shop: 227 words; add a *Photo mosaic* of the shop and turn
the two lists (we take, we cannot use) into a *two-column* block with icons.

### Give group

**/donate/.** Arrives: someone ready to give, or checking where money goes.
Job: make the main gift easy, then show the other ways without burying the
first. Today: the band's "Donate now", five amount chips that do nothing yet
(ticket POMDR-9), and sixteen equal cards. Prescription:

1. The band with one button.
2. A *Numbers band* that says where the money goes (clinic cases, dogs in
   care, cost of a dental) with real figures from POMDR.
3. The main ask as a *two-column* block: give once / give monthly, equal
   weight, each with its own LGL form link (POMDR-9), and the tax ID beside
   them.
4. "Other ways to give" grouped, not sixteen equal cards: **Give for a dog**
   (Sponsor a dog, Silver Hearts, Helping Paw fund), **Give in memory or in
   honor** (tribute, plaques and stones, dog tags), **Give for the long term**
   (legacy, planned giving, Perpetual Care, annual gift), **Give things**
   (wish list, stock, the Benefit Shop), **Companies** (corporate, sponsor an
   ad, sponsor a suite). Each group is a heading and three or four cards.
5. A purple closing strip, once the main form's address exists.

Copy: cut each card to one sentence; the details live on the linked page or
form. Ethics: no pre-selected monthly, no urgency, no confirm-shaming.

**/sponsor-a-dog/.** A form page. Add the *Featured voice* of a sponsor if
POMDR has one; otherwise leave.

### Forms and legal

**/adoption-questionnaire/, /intake-questionnaire/, /helping-paw-application/
(and es/), /volunteer-application/.** Designed with the form kit. Leave, except
the Helping Paw application, which should say in one line what happens after
you apply and how long it takes.

**/terms/, /privacy/.** Placeholders of about ten words. Need POMDR's real
text; no design work until then.

## 5. Copy: the rules for the rewrite

- Second person, present tense, short sentences. "You give the love" not "Love
  is provided by the foster".
- Every section heading is a statement or a question a reader would ask.
- US spelling (Ronnie's rule, 2026-09-27). No em dashes. Sentence case.
- "~13 yrs", "6 lb", "dog" in copy, "pet" in code.
- No "he or she": "your dog", "they".
- Quotes verbatim, always. Names and roles as the person gave them.
- Every ask is a button, above the text and again at the end, never only a
  link in a sentence.
- Nothing invented: no numbers, stories, names or photos that POMDR has not
  supplied. Where a page needs one and there is none, the template waits.

## 6. Order of work

Grouped so each session ships whole pages, and the templates arrive when the
first page needs them.

1. **Kit first:** Story split, Voice wall, Numbers band, Directory (the four
   most pages need), added to `patterns.php` and `site.css`, tested at 390 and
   1440 and with a screen reader. One session.
2. **Get help pages:** Helping Paw, Help placing your dog, Perpetual Care.
   Highest stakes, shortest pages. One session.
3. **Process and Volunteer.** One session.
4. **Testimonials and About** (Featured voice, Timeline, Photo mosaic added
   here). One session.
5. **Donate** (with POMDR-9's form addresses). One session.
6. **Directories:** Resources, Media, Videos, Jobs. One session.
7. **The rest:** Benefit Shop, Events, Hospice, Foster needs, Happy tails,
   Sponsor. One session.

Each session ends with the quality gates: lint, all pages rendered, the test
suites, Lighthouse on the changed pages, and a 390/1440 screenshot set for
review.

## 7. What we need from POMDR, by page

- **Photos:** a Helping Paw client (with consent), a dog walk, a food
  delivery, a foster home, a meet-and-greet, a dog arriving home, an enrolled
  Perpetual Care dog, staff at work in the clinic, the shop interior, past
  events. The WordPress library (about 985 originals) is the fallback for
  most.
- **Stories:** one client story each for Helping Paw and Perpetual Care; one
  sponsor's words; the two missing foster quotes.
- **Testimonials:** star ten; tag each by group.
- **Figures for Donate:** what a dental costs, clinic cases a year, dogs in
  care today.
- **Text:** Culture, News, Terms, Privacy, and the former-employee quotes for
  Jobs.
- **Confirmations:** opening hours (ticket POMDR-11), the "Darla with Tori"
  photo.

## 8. How we will know it worked

- Every section page has at least one photograph below the band and no block
  of text over 150 words without a heading.
- Testimonials under 7,000 pixels tall at 1440 with every quote still
  reachable.
- The line length of body text between 55 and 75 characters on every page at
  1440.
- Lighthouse accessibility 100 on every changed page; all suites green.
- Five older readers can find and start the application on each program page
  in one tap in a hallway test.
