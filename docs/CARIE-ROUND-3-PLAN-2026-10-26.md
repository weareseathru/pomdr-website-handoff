# Carie's feedback, round 3: the plan before the edits

Date: 2026-10-26, from the 1.5 hour page-by-page meeting with Carie.
Status: BUILT 2026-10-26 after Andrew's decisions (D1 $3,000; D2 now; D3 switch
off; D4 move; D5 most; D6 keep photo until the photo pass; D7 all five). Pushed
to Ronnie's main; logged as round 3 in `docs/CARIES-FEEDBACK.md`.

Carie's notes are grouped by page in site order. Each item names the file,
what it says today, and what it will say. Items that need a decision from
Andrew, or that only Ronnie can do, are pulled out at the end.

Rules that run through everything: no em dashes, US English, no question
endings, calling is the last resort (forms first), "guardian" never "owner".

---

## A. Site-wide

| # | Carie said | Change |
|---|---|---|
| A1 | Change owner to guardian everywhere | Our own copy in 9 places: Perpetual Care lead, Fostering FAQ, Why page, Courtesy Listings (3), Foster Needs group lead, footer link blurb, Resources descriptions (2). Quoted press headlines on In the Media stay as the newspapers wrote them. Code comments are not visitor copy and stay. |
| A2 | Nav: take away Happy Tails | Remove from the Adopt menu and the footer's Adopt column. The page `/adopted/` stays reachable from the dog tabs and the videos page. |
| A3 | Nav: take away How Adopting Works and Why a Senior Dog; make Adoptable Dogs the first item | Adopt menu becomes: Adoptable Dogs, Other adoptable dogs, Hospice care. The two removed pages become the two buttons on Adoptable Dogs (B6). Footer column follows the menu. |
| A4 | Nav: big purple Donate button, "Helping senior dogs and people" in the center | Action bar: tagline centered, Donate button larger (taller, bolder) and purple, right-aligned. |
| A5 | MC Gives banner takes the tagline's place; Donate goes to MC Gives during the campaign | Build a campaign switch in `site-info.php`: when on, the action bar shows the Monterey County Gives banner where the tagline sits and both Donate buttons (bar and menu) point at the MC Gives page. Off by default. Needs the URL and the dates (Decision D3). |
| A6 | Work with us → Job opportunities | Menu, footer, team page button, page title and browser title on `/jobs/`. |
| A7 | Join our mailing list moves to About Us | Interpreting "About us: add Join our mailing list to that section" as moving the menu item from Events to About Us, Who we are column. (Decision D4 if that reading is wrong.) |
| A8 | Remove the Bauer Center page | Delete `/bauer-center/`, its menu item, footer link and the About page link; About page's "Our Office" keeps the address as plain text. `/bauer-center/` redirects to `/contact/` in the redirect map (POMDR-61). Carie will write the new building page later. |
| A9 | "Prefer to talk? Call" button on forms | Remove the button from the shared form include (all five forms). The small "If you have trouble filling out this form, call..." line at the bottom stays; Carie said that part is good. |

## B. Home and Adopt

| # | Carie said | Change |
|---|---|---|
| B1 | Hero cards more transparent, drop shadow under the white text | Card background from 80% to about 55% opacity (teal and purple), keep the blur; add a text shadow on the title, lead and tag. Contrast checked after: white on the darkest and brightest photo spots must stay at or above 4.5:1, so the exact opacity is tuned on the real photos. |
| B2 | Lifetime commitment slide | "Every dog in our care is ours for life. If a placement does not work, for any reason, ever, they come home to us." → "We have a lifetime commitment to every dog in our care. If a placement does not work out, they come back into our program." Same sentence on About ("What we believe") to match. |
| B3 | Helping Paw slide: dogs → pets | "...so guardians and their dogs never have to say goodbye too soon." → "...so guardians and their pets never have to say goodbye too soon." |
| B4 | Events above testimonials; remove "nothing on the calendar, call us" | Move the Events section above "Hear from our clients". Empty state becomes "Nothing is on the calendar right now. Join the mailing list to hear about the next one." with the link. |
| B5 | "See all videos" tile purple | The outline tile uses the purple token instead of blue. |
| B6 | Adoptable Dogs: two buttons, How adopting works and Why a senior dog | Page intro actions become two buttons: How adopting works, Why a senior dog. |
| B7 | Search filters: small/medium/large, under 7, 7 to 9 | Marked IGNORE in the notes. Parked. |
| B8 | How Adopting Works: "Most of our dogs live in foster homes" | Step 1 "Every dog lives in a foster home" → "Most of our dogs live in foster homes". Note: this morning's decision was "say they all live in foster homes"; Carie's wording wins here (Decision D5 confirms). |
| B9 | Every dog arrives ready: drop "unless our veterinarian advises against"; add "Every dog receives excellent care"; photo of vet staff giving care | Sentence ends at "altered, microchipped and vaccinated." Add "Every dog receives excellent care." as the paragraph's first line. Photo: we have clinic exterior shots only; needs a staff photo of care being given (Decision D6). |
| B10 | How Adopting Works: remove the questionnaire buttons, send people back to Adoptable Dogs | Intro button "Start an adoption questionnaire" → "Adoptable dogs". Step 1 link → "See the adoptable dogs". Closing strip keeps "Meet the dogs", drops "Start the questionnaire". |
| B11 | All questionnaire buttons off the adopt page say Adoptable dogs or Meet the dogs | After B10 the only questionnaire links left are the Adopt button on each dog's page and the Adoptable Dogs lead sentence. Checked site-wide. |
| B12 | Click a dog, go straight to the form | Dog page Adopt button lands on the questionnaire page at the form itself (anchor), with the dog's name prefilled as today. |
| B13 | Clean up the adoption questionnaire page | Shorter intro (one line), no "Prefer to talk" (A9), "What happens next" stays, "How adopting works, in full" link stays. |

## C. Hospice and Why a Senior Dog

| # | Carie said | Change |
|---|---|---|
| C1 | Hospice: swap top and bottom; dogs first | Order becomes: intro, dog list, then "No dog is ever left behind" story, then the closing strip. |
| C2 | Sideways cards with a Sponsor button, no tags | A hospice card variant: photo left, name and short note right, one Sponsor button per card, no status badges and no trait chips. Used only on the hospice page. |
| C3 | Get rid of the call button on hospice | There is no call button on the hospice page today, nor on Sponsor a Dog. Nothing to remove; will confirm with Carie which button she meant. |
| C4 | Why: take out "We never leave an adopter to navigate care alone" | Removed from the fees paragraph and from the FAQ. |
| C5 | Why: "The vet bills will be overwhelming" answer | → "We are transparent about each dog's health and discuss with the adopter the ability to handle future medical bills." |
| C6 | Why: "a guardian enters care", never owner | Covered by A1. |

## D. Foster

| # | Carie said | Change |
|---|---|---|
| D1 | Become a Foster: "POMDR relies heavily on foster homes. We provide the crate, bed, etc." | Lead → "POMDR relies heavily on foster homes. We cover the vet care and provide the supplies: crate, bed, leash, bowls and toys. You provide the home." Narrative "A room in your house, and a dog who needs it." → "A guest in your home, for a few months." |
| D2 | Foster form bottom is good | No change. |
| D3 | "A couch is all it takes" is bad → "Fostering is a bridge to a better future." | Foster Needs closing strip heading. |
| D4 | "Waiting for a couch" → "You're the bridge" | Foster Needs story heading. Also the footer blurb "The dogs waiting for a couch right now." → "The dogs who need a foster home right now." and the group lead "you provide the food and the couch" → "you provide the food and the home". |
| D5 | Foster Needs: a line on each dog saying why they need a foster | Needs a per-dog field in Ronnie's admin and database (foster reason). We will show it on the card and the dog page once the field exists. Ronnie item R1. |
| D6 | Volunteer application: "a room in your house" | → "A guest in your home for a few months." |

## E. Get Help

| # | Carie said | Change |
|---|---|---|
| E1 | Helping Paw first line: provides both volunteer and financial assistance | Lead → "Our Helping Paw program provides both volunteer assistance and financial assistance so seniors, hospice patients and other pet guardians facing hard times can keep their dogs and cats with them." Title stays Helping Paw; lead names Financial Assistance and Dog Walking. |
| E2 | Two buttons: Apply for Financial Assistance, Apply for Volunteer Assistance | Financial → existing `/helping-paw-application/`. Volunteer → new page `/helping-paw-volunteer-application/` embedding the live site's LGL volunteer assistance form (`DaCs_nW1lVLf4MyhMJ-vxg`), same form wrapper as the others. |
| E3 | Spanish hyperlink below the buttons | One line under the buttons: "Solicitud en español" linking to `/helping-paw-application/es/`. Replaces the Spanish "call us" note. |
| E4 | Remove "A helping paw when you need one" section | Whole story block removed (its photo moves up into the intro). |
| E5 | "Is this for me" → "What we offer"; "Financial help: a vet bill up to $750" → "Financial help"; up to $3,000 for major medical | Heading and card title changed. $2,750 → $3,000 in the card and in the FAQ "What financial help covers" (Decision D1: live site says $2,750). |
| E6 | Take out "Not sure if you qualify... nobody turned away for asking" | Removed. |
| E7 | Take out the paragraph below the qualify cards and "Three kinds of help" | Both removed as redundant. |
| E8 | Keep "Supplies we can give you" | Stays. |
| E9 | Volunteer with Helping Paw at the bottom goes to the volunteer application | Button → `/volunteer-application/`. |
| E10 | Call buttons on Helping Paw | Intro and closing "Call" buttons removed (forms first); the FAQ "I cannot fill out the application" keeps the number. |
| E11 | Placing Your Dog: no call buttons except the inline link; no "delay" wording; "fastest way to get help is this form" | Remove the three Call buttons (intro, step 1 is inline and stays, closing strip). "please be aware that this will delay the processing of your request" → "The fastest way to get help is to fill out this form." Spanish note matched. |
| E12 | Intake questionnaire: no call button, keep the small call line | Intro Call button removed; bottom "If you have trouble" line stays. |
| E13 | Perpetual Care lead: remove the last line | "It is the worry that keeps a lot of older owners awake." removed. |
| E14 | Perpetual Care: call buttons are OK | Stay. |
| E15 | "A promise made now": take out "It costs nothing... where you went" | That paragraph removed. |
| E16 | How to enroll: development director, not board member; remove step 4 | Step 1 → "Call us. Our development director will talk with you about your dog..." Step 4 "Consider an annual gift" removed; steps renumber to three. |
| E17 | Questions people ask us: delete the "prints well" line | The intro line under the heading removed. |
| E18 | Resources: take out Max's Helping Paws (they do not exist); update the page | Entry removed. Every outside link on the page checked and dead ones removed or repointed (list in the build report). |

## F. Volunteer

| # | Carie said | Change |
|---|---|---|
| F1 | Match the live volunteer page; no Tabling; fold Fundraising into Specialty Skills; "Helping Paw Dog Walking" with Apply | Roles become: Foster home, Transportation, Adoption events, Behavior and Enrichment Team, Benefit Shop, Helping Paw dog walking, Specialty skills (fundraising, writing, grants, photography, social media and media), Volunteer brigade. Tabling removed. |
| F2 | Apply links go away; just buttons to the application | Per-card "Apply" arrow links removed. Application buttons stay at the top, after the roles, and in the closing strip. |
| F3 | "Once a year, we throw a party" → photos from past parties | Heading → "Photos from past volunteer appreciation parties". |
| F4 | Volunteer application: "See the eleven ways to help" → "See the ways to help"; "Other ways in" → "Other ways to get involved" | Both. |

## G. Events and About Us

| # | Carie said | Change |
|---|---|---|
| G1 | Adoption Events, and another page for Special Events | `/events/` becomes Adoption events only. New `/special-events/` page lists fundraisers and special events. Menu: Events → Adoption events, Special events. Home shows both lists. |
| G2 | Our Team: advisors to the bottom | Public team page orders groups Board, Office, Clinic, Benefit Shop, Advisory Council. Done in our page file; Ronnie's `team_groups()` is not touched. |
| G3 | Benefit Shop: all good; remove the call | Intro "Or call (831) 718-9122" button removed. The shop phone stays in the Visit card. |
| G4 | 229B Grand Avenue | Address → 229B Grand Ave, Pacific Grove, CA 93950 in `site-info.php` (footer, Contact, shop page), the Donate page sentence, and the map links. (Decision D2: now or at the Nov 10 opening.) |
| G5 | Vet clinic: remove the Visit, Call and Email cards | The three cards removed; page opens on "A clinic of our own". |
| G6 | Contact: "A real person will answer" → we return every call | Narrative → "We return every call." |
| G7 | Contact: Mailing Address instead of Post | Location name → "Mailing Address". |
| G8 | Follow the Dogs: icons, centered | Contact page social list gets the same icons as the footer, label beside each, centered. |

---

## Decisions for Andrew (D)

| # | Question | Default if no answer |
|---|---|---|
| D1 | Helping Paw major medical: Carie said $3,000; the live site and this morning's decision say $2,750. | Use $3,000 (Carie runs the program). |
| D2 | Benefit Shop 229B Grand Ave: switch now, or on Nov 10 when the new location opens? | Switch now, per Carie. |
| D3 | MC Gives: the campaign URL and the on/off dates. | Build the switch off; turn on when you send the URL. |
| D4 | "Join our mailing list" moving into About Us: right reading? | Move it. |
| D5 | "Most of our dogs live in foster homes" on How Adopting Works contradicts this morning's "all live in foster homes". Carie's wording wins? | Yes, "most". |
| D6 | Photo of vet staff giving care for the "Every dog arrives ready" block: do you have one? | Keep the current photo until one arrives. |
| D7 | "Prefer to talk" button removed from all five forms, not just the adoption questionnaire? | All five. |

## Ronnie items (R)

| # | What | Why Ronnie |
|---|---|---|
| R1 | A per-dog "why this dog needs a foster" field in the admin and database. | Schema and admin are his. We display it once it exists. |
| R2 | Roster SQL (`docs/team-roster-live-2026-10-05.sql`) on production. | Already with him (POMDR-32). |

## Not changed, on purpose

- B7 search buckets: Carie marked IGNORE.
- C3: no call button exists on the hospice page.
- Press headlines on In the Media keep "owners" as published.

## Count

| | |
|---|---|
| Items from Carie | 67 |
| Built in this pass | 58 |
| Decisions for Andrew | 7 |
| Ronnie | 1 new (R1) |
| Parked or no change | 3 |
