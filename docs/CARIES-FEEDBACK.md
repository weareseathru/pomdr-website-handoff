# Carie's feedback

The running list of wording and content changes Carie (POMDR staff) sends
Andrew for the new site, with the state of each one. Every round she sends
gets added here, newest round at the bottom of the rounds list, and the
status column is kept current. This file is the place to look before
touching copy she has commented on.

Statuses: **Live** (on Ronnie's main and on the preview), **Built** (pushed,
waiting for the hourly build), **Open** (not yet done), **Talk** (needs her
answer first), **Declined** (with the reason).

Preview: https://pomdr-public.pages.dev/

## How she reads the site (from her notes)

- "So much is worded in a bizarre way or is just flat out incorrect." Expect
  more rounds; treat her list as a sample, not the whole.
- "We really try to get people to fill out a form. Calling us is the last
  thing we want them to do." Forms first, phone as the fallback. This
  reverses the phone-first care pages we built.
- Direct and practical. She gives the replacement wording herself, so apply
  it as written and raise questions separately rather than editing her text.

## Round 1, sent 2026-10-21, applied 2026-10-22 (commit 97e0895)

| # | Where | Her change | Status | Notes |
|---|-------|-----------|--------|-------|
| 1 | Blue strip at the top of every page | Remove the phone pill and the Foster pill; put a purple Donate pill there | Live | On phones this Donate now sits right above the header card's Donate. Raise with her: drop one, or give the strip pill a different job. |
| 2 | Home hero, slide 4 title | "Be the reason a gray muzzle finds **a** home." | Live | |
| 3 | Home hero, slide 1 lead | "...ready to love again. **Meet our** gray-muzzled companions waiting for their next chapter." | Live | |
| 4 | Helping Paw application band | Remove the lead paragraph ("Helping Paw pays towards... will not count against you.") | Live | Removed in English and Spanish. |
| 5 | Every form intro | "This takes N minutes." (drop "You can stop part way through...") and "What we need from you:" | Live | Shared string, so it changed on all five form pages, English and Spanish. |
| 6 | Every form intro, and Helping Paw | "If you have trouble filling out this form, call (831) 718-9122 and a volunteer will call you back to help you fill it out."; remove "We do not ask for tax returns or bank statements." | Live | Shared string, both languages. |
| 7 | Adopt page lead | "...could no longer keep them. The fastest way to get information about a dog and set up a meet and greet is to fill out an adoption questionnaire." | Live | |

## Round 2, the fine-tooth comb, 2026-10-26

Carie's note: the site "needs a fine tooth comb", with "all of our dogs are
seniors" as the example of a wrong statement. Andrew settled the live site's
nine self-contradictions (1,800 volunteers; about 80 dogs in care; Helping Paw
$750 and up to $2,750; ads $150; shop 11 to 5, closed Sunday and Monday; Bauer
11 to 4; all dogs live in foster homes; fosters buy food, POMDR shares donated
food when it has it and provides supplies; keep the 4,500+ count; follow the
live site's senior-dog wording). Every sentence on the new site was then read
against `docs/POMDR-FACTS-FROM-LIVE-SITE-2026-10-26.md`. Full list, with the
proposed wording for each open item: `docs/FINE-TOOTH-COMB-2026-10-26.md`.

| # | Where | Change | Status |
|---|-------|--------|--------|
| 1 | Home, About, Donate, Volunteer, Team, Happy tails | Volunteers 1,500 to 1,800 | Built |
| 2 | Same pages | "Senior dogs adopted" to "Dogs adopted" beside 4,500+ | Built |
| 3 | Helping Paw page (3 places), application EN and ES | $500 cap to $750 and up to $2,750; eligibility to three counties and financial need; routine care not covered; flea pick-up; spay/neuter rule; dog and cat food; "give you" not "bring you" | Built |
| 4 | Benefit Shop | Hours 11 to 5, closed Sunday and Monday; shop phone added | Built |
| 5 | Donate | Ad sponsorship $120 to $150 | Built |
| 6 | Volunteer application | "We pay for the food" to "you provide the food" | Built |
| 7 | Fostering | Clinic-suite FAQ removed | Built |
| 8 | About | Staff 27 to 19; Helping Paw paragraph used the wrong live sentence; "adoption center" to "headquarters" | Built |
| 9 | About and Donate promise block | Dental line matches the live site | Built |
| 10 | Hospice | Not "Perpetual Care", not "behavioral"; live definition | Built |
| 11 | Get help, Resources | Resources described as they are; Clark Ruggiero Law Office | Built |
| 12 | How adopting works, questionnaire | "About a week" removed; reply by email; meet and greet via foster; annual follow-up calls | Built |
| 13 | Perpetual Care | "arrange to meet", eligibility factors; "you can also remember POMDR" | Built |
| 14 | Help placing your dog | Form first; a call delays processing | Built |
| 15 | Bauer Center | Headquarters, dogs in foster homes, front desk and B&E shifts | Built |
| 16 | Work with us | About twenty staff; live posting's hours | Built |
| 16a | Clinic page, footer, Contact | Private clinic that sees Helping Paw clients; no public appointments; buttons go to Helping Paw | Built |
| 16b | About, Work with us | Staff "more than thirty" | Built |
| 16c | Team page | Remove Cortina Whitmore, Elizabeth Ramsay, Emily Termotto, Hannah Wakefield; add current staff not listed | Open: production data, Ronnie runs the roster SQL (POMDR-32) |
| 17 to 47 | 31 items across home, About, Clinic, Contact, Donate, Why, Surrender, Perpetual Care, mailing list, applications, media | Grand phrasing, invented detail, or promises not on the live site; each with proposed wording in the comb document, Part B | Talk |

## Round 3, the page-by-page meeting, 2026-10-26 (1.5 hours, every page)

The full item list with before and after wording is
`docs/CARIE-ROUND-3-PLAN-2026-10-26.md` (67 items). Status:

| Group | Items | Status |
|---|---|---|
| Site-wide (guardian, menu, Donate, MC Gives switch, Job opportunities, mailing list under About, Bauer page removed, no "Prefer to talk") | A1 to A9 | Built |
| Home and Adopt (hero cards, slide copy, events above testimonials, purple videos tile, two buttons on Adoptable Dogs, How adopting works back to the dogs, "most" live in foster homes, Adopt button lands on the form) | B1 to B6, B8 to B13 | Built |
| Search buckets | B7 | Parked, Carie marked IGNORE |
| Hospice and Why | C1, C2, C4 to C6 | Built |
| Hospice call button | C3 | She meant the hospice dog's page: call link and fact squares removed; cards now story-first, one to a row (built 2026-10-26, second push) |
| Foster | D1 to D4, D6 | Built |
| Foster reason line per dog | D5 | Filed for Ronnie as POMDR-65 (for-ronnie) |
| Get Help (Helping Paw rebuild, volunteer assistance application, no call buttons, Perpetual Care, Resources) | E1 to E18 | Built |
| Volunteer | F1 to F4 | Built |
| Events, About, Benefit Shop, Clinic, Contact | G1 to G8 | Built |

Andrew's decisions on the seven open questions (2026-10-26): $3,000 major
medical; 229B Grand Avenue now; MC Gives switch built but off until the URL
and dates arrive; mailing list under About Us; "most of our dogs live in
foster homes"; keep the vet photo until the photo pass (Andrew will redo
many photos next); "Prefer to talk" off all five forms.

Still to settle with Carie: C3 above; the 31 wording items from round 2
(Part B of the comb document) that this meeting did not reach.

Verification pass, 2026-11-02 (Andrew's 67-line recap of the round, checked
item by item against the rendered pages): 63 confirmed in place. The hero
card was in the code at 60% but still read as a solid panel, so it went to
42% with a lighter blur and a deeper text shadow (9a92171). Not ours: the
vet-staff photo (Andrew's photo pass) and the per-dog foster reason
(POMDR-65, Ronnie). Left as is: three uses of "owner" that are other
people's words (Old Dog Haven's own description on Resources, a foster's
quote on Fostering, two news headlines on Media). The MC Gives banner is
built and off, as decided.

## Round 4, the 2026-11-18 meeting (commit f456ee3)

| Her note | What was built |
|---|---|
| "Dogs needing a foster home" becomes Foster Needs | Title changed in the menu, footer, page band and the Fostering page button |
| Foster cards like the hospice cards, the reason on the right, click through to the dog's page | Sideways cards, one to a row: photo left; right side has a purple line saying what kind of home the dog needs (short stay with dates, or until adopted), then the story opening; the whole card opens the dog's page with the bio. A staff-written reason per dog still needs POMDR-65 (Ronnie) |
| Remove "Who we are" / "Visit us" under About Us; put Job opportunities there | Headings gone, two plain columns; Job opportunities moved from Volunteer to About Us |
| Volunteer opportunities, that page, or Volunteer Application | Volunteer menu lists Volunteer opportunities (/volunteer/, band title renamed to match) and Volunteer application |
| Follow-up (Andrew): the card should show the live site's "why they need a foster" paragraph, not the bio; the dog page shows it below the bio | Built 4462cd0: migration 042 adds `foster_reason`, db/sync/2026-11-18-foster-reasons.sql fills 8 dogs from the live page. On production only after Ronnie runs both files; staff edit box in the admin still POMDR-65 |

## Round 5, the 2026-11-23 full-site review (commits 7461df8, 9673938, 509d10c)

**REVERTED 2026-11-23 (e5660c0), at Andrew's request, for now.** Everything
in this round was built and pushed, then taken back out the same day; the
site is as it was before. "Built" below means built and then reverted. The
work is in the history of Ronnie's repo under the three commits above and
comes back by reverting e5660c0. Do not rebuild it or restore it until Andrew
says so.

Not from Carie: a senior staff reviewer and her husband went through the whole
site and sent a written review of the top banners, the home page and the
footer. Their notes were right, and several of the faults were ours: changes
we had made and left half finished. Everything below is on Ronnie's main.
The approved plan is `~/.claude/plans/hazy-knitting-moon.md`.

### Top banners

| Their note | What was built | Status |
|---|---|---|
| Tagline missing on small screens (a blank band); fine to drop "since 2009" there | The band is sized by its words; phones show "Helping senior dogs and senior people" | Built |
| Tagline looks dull; add a paw or a more interesting font | Paw first, set in the heading serif | Built |
| Uneven space above and below; band too tall | Equal padding, about 37px tall (was 56px, sized for buttons that had gone) | Built |
| Phones never show the name "Peace of Mind Dog Rescue" | The full logo with the name at every width. Andrew kept the floating card; below 360px the name takes its own row | Built |
| Donate belongs on the right, before the menu button | Done | Built |
| "Become a foster" should go to the Volunteer Application | **Not changed.** Andrew wants to ask why the page is a problem first | **Pinned** |
| Submenu labels inconsistently capitalised; use Title Case | All Title Case, and the pages' own titles follow | Built |
| Top-level menus behave inconsistently (sometimes open, sometimes navigate) | One rule: a name opens its list and never leaves the page, by click or tap, on every screen | Built |
| The page a name goes to is sometimes in the submenu, sometimes not | Each section's own page is the first item in its list (Fostering, Get Help Overview, Our Story added) | Built |
| MC Gives banner only on Home and Donate; should be on every page | A purple band under the tagline on every page | Built |
| Three corner styles in a small area | Two square bands and the rounded card; the hanging tab is gone | Built |

### Home page

| Their note | What was built | Status |
|---|---|---|
| Slideshow text covers the dogs; on phones the words and buttons sit under the photo; buttons duplicate the page | Each slide is a photo and one short phrase, no buttons, on phones too | Built |
| Carie will provide wording and photos | Four phrases from their list stand in on the current photos | **Waiting on Carie** |
| Section titles: one colour, one alignment | One title style everywhere: paw, capitals, centred, blue, no subtitle | Built (blue chosen; confirm) |
| Mission section too wordy | POMDR's one sentence only | Built |
| Titles for the Adopt/Donate/Volunteer and the three-programme sections | "How You Can Help" and "How POMDR Can Help You" stand in | **Waiting on Carie** |
| Programmes: a photo and the current website's short wording | Photo cards with the current site's photos and sentences | Built; **photos waiting on Carie** |
| Adoptable dogs should not be on the home page | Removed | Built |
| Videos title just "Watch POMDR Videos" | Done | Built |
| Home videos are not the most recent | The three newest, newest first. /videos/ now follows the live site's order, and gains the 2026 film "This is Our Message to You", which we were missing | Built |
| "See all videos" should look like a normal button | An outline button under the row | Built |
| Drop "What your gifts have done" | Done | Built |
| The 17+ years figure is not useful | Removed | Built |
| Add the number of people helped by Helping Paw | No such figure exists anywhere we can read; it must come from POMDR | **Waiting on staff** |
| Events title just "Upcoming Events" | Done | Built |
| "Next Up" should be on the left | Done | Built |
| Remove "All Adoption Events" | Done | Built |
| A button to all upcoming events, styled like See all videos | New page /upcoming-events/ (every event of both kinds), and the button | Built |
| "Testimonials" title does not match | Same paw title as the rest | Built |
| Stay in the Loop too wordy | The title, the one line and the button | Built |

### Footer

| Their note | What was built | Status |
|---|---|---|
| The link lists repeat the menu; remove them | Removed | Built |
| Remove the Donate button | Removed | Built |
| 501(c)(3) said twice | Said once, with the Tax ID | Built |
| A photo, as on the current website | The Bauer Center photograph | Built |

### Found on the way, in the same vein

- The "More under" list on a section's front page listed the page itself.
- The stress test still drove the old arrow button.
- Design.md described a strip with a phone number and a footer site map,
  neither of which existed any more.

## Open with Carie (to settle in the next conversation)

| # | Question | Why it matters | Status |
|---|----------|----------------|--------|
| A | Two Donate buttons stacked on phones (strip and header card) | It is the first thing a phone visitor sees | Settled 2026-10-26: one purple Donate in the card, the strip holds the tagline |
| B | A phone-demotion pass: the Helping Paw application band still leads with "Call (831) 718-9122", and about 30 Call buttons across Get help, Perpetual Care, surrender and the form pages do the same. If forms first is the rule, the form becomes the primary button everywhere and the phone becomes the fallback line | Her stated rule; one sitting to apply | Talk |
| C | The list of pages she still thinks are "bizarre or incorrect" | We can only fix what she names | Talk |
| D | Round 5: why is the Become a Foster page a problem? The review asks for the menu item to go to the Volunteer Application; Andrew finds the page benign and wants the reason before changing it | Eleven other links go to that page too | **Pinned, Andrew to ask** |
| E | Round 5: slideshow phrases and photos; titles and photos for the two help sections | Interim wording and the current site's photos are standing in | Waiting on Carie |
| F | Round 5: the number of people Helping Paw has helped | The reviewers want it in By the Numbers; we must not invent it | Waiting on staff |

## Log

- 2026-10-22: file created; round 1 applied and pushed (Design.md section 11
  in Ronnie's repo has the same list).
- 2026-10-26: round 2, the fine-tooth comb. 50 factual fixes built and pushed;
  31 wording items for Carie in docs/FINE-TOOTH-COMB-2026-10-26.md.
- 2026-10-26: round 3, the page-by-page meeting. 58 items built and pushed
  to Ronnie's main; 1 for Ronnie (foster reason field); 1 question for
  Carie (hospice call button); search buckets parked.
- 2026-11-02: verification pass on round 3 against Andrew's recap; hero
  panel taken to 42% plus text shadow (9a92171); everything else confirmed.
- 2026-11-18: round 4, meeting notes. Four items built and pushed to
  Ronnie's main (f456ee3); per-dog foster reason still POMDR-65.
- 2026-11-18: foster reasons on the card and the dog page (4462cd0); needs
  Ronnie to run migration 042 and the fill file.
- 2026-11-23: round 5, the full-site review from a senior staff reviewer and
  her husband. Three pushes to Ronnie's main: the top of every page
  (7461df8), the home page and videos (9673938), the footer (509d10c).
  Become a Foster link pinned for Andrew; phrases, photos and titles waiting
  on Carie; the Helping Paw figure waiting on staff.
- 2026-11-23: round 5 reverted the same day at Andrew's request (e5660c0 on
  Ronnie's main). The site is as it was before the round; the three commits
  stay in the history.
