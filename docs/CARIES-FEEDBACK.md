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

## Open with Carie (to settle in the next conversation)

| # | Question | Why it matters | Status |
|---|----------|----------------|--------|
| A | Two Donate buttons stacked on phones (strip and header card) | It is the first thing a phone visitor sees | Settled 2026-10-26: one purple Donate in the card, the strip holds the tagline |
| B | A phone-demotion pass: the Helping Paw application band still leads with "Call (831) 718-9122", and about 30 Call buttons across Get help, Perpetual Care, surrender and the form pages do the same. If forms first is the rule, the form becomes the primary button everywhere and the phone becomes the fallback line | Her stated rule; one sitting to apply | Talk |
| C | The list of pages she still thinks are "bizarre or incorrect" | We can only fix what she names | Talk |

## Log

- 2026-10-22: file created; round 1 applied and pushed (Design.md section 11
  in Ronnie's repo has the same list).
- 2026-10-26: round 2, the fine-tooth comb. 50 factual fixes built and pushed;
  31 wording items for Carie in docs/FINE-TOOTH-COMB-2026-10-26.md.
- 2026-10-26: round 3, the page-by-page meeting. 58 items built and pushed
  to Ronnie's main; 1 for Ronnie (foster reason field); 1 question for
  Carie (hospice call button); search buckets parked.
