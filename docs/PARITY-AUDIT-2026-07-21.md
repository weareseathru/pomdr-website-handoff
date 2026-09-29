# Live vs Mirror Parity Re-Audit, 2026-07-21

A fresh, exhaustive two-level crawl of every page on www.peaceofminddogrescue.org
(56 pages) cross-referenced against the mirror, run after the July feature
sprints closed the first audit's top gaps. Sections: A parity table, B gap
detail with live copy quoted for porting, C dead/legacy links (REPAIRED same
day, see note), D the ranked implementation plan.

REPAIRED 2026-07-21, same session: every section-C legacy link whose mirror
page exists was repointed internally (8 in theme templates, 19 in Divi page
content), and the dead surrender courtesy-listing link now goes to
/intake-questionnaire/. Remaining external links are the donation-form family,
which waits on the D1 processor decision and the fund-passthrough work (plan
items 6 and 7).

---

# POMDR Live vs Mirror Parity Re-Audit, 2026-07-21

Method: full two-level crawl of www.peaceofminddogrescue.org (56 pages found, crawl closed with no new URLs), every page fetched and text-diffed against its mirror equivalent on newpomdr-local.local (50 WP pages + pets CPT, 110 pets). All mirror LGL form embeds enumerated and matched against the 14 live LGL form IDs.

## A. COMPLETE PARITY TABLE

| Live URL | Mirror URL | Verdict | Gap note |
|---|---|---|---|
| index.php | / | GAPS | Placeholder stats and invented home testimonials |
| adopt.php | /adopt/ | GAPS | Dog data stale; 0 foster-needed; no Aged to Perfection badges |
| dog.php?id=N | /pets/{id}/ | GAPS | IDs differ from live; Adopt CTA shown on hospice/adopted/courtesy dogs |
| courtesylistings.php | /courtesy-listings/ | GAPS | Stale roster; writeups truncated; contact person lost; wrong CTA |
| hospicedogs.php | /hospice/ | GAPS | Per-dog "Sponsored By" name lists missing |
| adopted.php | /adopted/ | GAPS | 12 names vs ~3,500 on live |
| fosterneeds.html | /foster-needs/ | GAPS | No dog listing at all; page is a generic foster landing |
| fostering.html | /fostering/ | OK | All 14 foster-parent quotes present; 2 legacy links |
| adoptionprocess.html | /process/ | OK | Minor: spam-folder note absent |
| whyadoptseniordog.html | /why/ | GAPS | All 6 real adopter quotes missing |
| helpplacingdog.html | /surrender/ | OK | Courtesy-listing CTA points at dead live URL |
| lifetimecare.html | /perpetual-care-program/ | OK | No link to FAQ (FAQ page missing entirely) |
| lifetimecareFAQ.html | none | MISSING | Whole 6-question FAQ page absent |
| aboutus.html | /about/ | GAPS | Staff roster stale/incomplete; clinic+shop staff, reports, 990s missing |
| POMDRCulture.html | /about/culture/ | GAPS | Real culture values replaced with invented ones |
| adoptionevents.php | /events/ | GAPS | Aug 23 Pet Pals + Aug 30 Pawsh Retreat missing; /adoption-events/ is an empty placeholder page |
| calendar.html | /events/ | GAPS | Ticket purchase links (Paybee, LGL wine class) missing |
| news.html | none | MISSING | No What's Happening page; several fundraising items unrepresented |
| inthemedia.html | /media/ | GAPS | Placeholder: 3 generic items vs 37 dated links |
| maxsfund.html | none | MISSING | Max's Helping Paws Fund story page absent |
| thankyou.html | /thanks/ | MISSING | Mirror /thanks/ is a form-confirmation page; donor recognition page absent |
| tributedonations.php | /tribute-card/ | GAPS | Tribute donor lists (800KB of names) missing; card image only |
| POMDRMailingList.php | /mailing-list/ | OK | Correct LGL form gTxA6Gd |
| bauercenter.html | /bauer-center/ | OK | Patricia Bauer story condensed but core facts kept |
| vetclinic.html | /clinic/ | OK | All services, diagnostics, referrals present |
| benefitshop.html | /benefit-shop/ | OK | Hours, donate-goods, accepted/declined lists present |
| termsandprivacy.html | /terms/ + /privacy/ | GAPS | Both are self-declared placeholders; real legal text not ported |
| videos.html | /videos/ | OK | 16 embeds vs live 10; all live videos covered |
| jobs.html | /jobs/ | GAPS | Only 1 of 4 current openings; former-employee quotes missing |
| resources.html | /recources/ | OK | Complete directory (note slug typo "recources") |
| volunteer.html | /volunteer/ | OK | Appreciation-party photo album links (2015-2024) missing |
| testimonials.php | /testimonials/ | GAPS | 3 invented quotes vs 170 real; placeholder banner |
| donateoverview.html | /donate/ | GAPS | 5 giving options missing; 10 links point at live site |
| POMDRAdoptionQuestionnaire.php | /adoption-questionnaire/ | OK | Form + dogname prefill working |
| POMDRIntakeQuestionnaire.php | /intake-questionnaire/ | OK | Correct form qaEwtEw |
| POMDRVolunteerApplication.php | /volunteer-application/ | OK | Correct form HsUdXNp |
| POMDRSponsorDog.php | /sponsor-a-dog/ | OK | Correct form k1_dO7Vq + dogname prefill |
| POMDRDonation.php | /donation/ | GAPS | No fund=/donationtype= passthrough; hardcoded field_26=60 |
| POMDRMonthlyDonation.php | none | MISSING | Monthly form 4T09LUBDyJ not embedded anywhere |
| POMDRAnnualDonation.php | none | MISSING | Annual form 1nOlM3Ns not embedded anywhere |
| formsPOMDR.html | /forms/ | GAPS | Educational Videos section missing; all 38 PDFs still hosted on live |
| helpingpaw.html | /helping-paw/ + application | OK | Funds, limits, Spanish, supplies all present |
| legacydonor.html | /legacy/ | OK | Verbatim port |
| plannedgiving.html | /planned-giving/ | GAPS | Legal name + tax ID lines missing (needed for bequests) |
| wishlist.html | /wish-list/ | OK | Full parity |
| sponsorad.html | /sponsor-an-ad/ | OK | Full parity |
| majorsponsorshipops.html | /sponsorship/ | OK | But 3 form CTAs point at live .php pages |
| majorplaquestonesponsors.html | /stone-sponsors/ | OK | All 28 sponsor entries present |
| dogTagDonorOp.html | /dog-tag-donor/ | GAPS | "Become a Dog Tag Donor" is a dead header, no link to form |
| doggieSuiteSponsorshipOp.html | /doggie-suite-sponsorship/ | GAPS | "Sponsor a Doggie Suite" CTA has no link to form |
| POMDRDogTagDonor.php | none | MISSING | Form 6PuyQVxz has no mirror page |
| POMDRDoggieSuiteSponsorship.php | none | MISSING | Form z0H3cG has no mirror page |
| POMDRMuralPlaqueSponsorship.php | /mural-plaque-sponsorship-donation/ | OK | Correct form ZSFUXxL |
| POMDRRiverStoneSponsorship.php | /garden-river-stone-sponsorship-donation/ | OK | Correct form BL4Ya |
| POMDRWelcomeRoomPlaqueSponsorship.php | /welcome-room-plaque-sponsorship-donation/ | OK | Correct form 3rLUKb8 |
| (Silver Hearts, live has no page) | /silver-hearts-fund/ | OK | Mirror is richer than live |

## B. GAP DETAIL

**B1. Dog data is a stale snapshot (adopt, courtesy, adopted, foster).** The mirror roster (Pebble, Patches, Sun Bear...) does not match today's live roster (Fanta, Kelpie, Blitzen, Dr. Pepper, Mia, Messi, Fifa, Pocket, Strawberry, Puffin, Axel, Froyo, Mako, Otter, Tide, Cove, Wave, Jackie...). Live adopt.php shows 95 cards with status flags "Foster needed", "Foster needed 9/26-10/23", "Adoption pending", "Recently Adopted", and the "Aged to Perfection" campaign suffix. Mirror shows "0 Need foster" and has no Aged to Perfection treatment anywhere. This is a sync problem, not a template problem; the templates render what the CPT holds.

**B2. /foster-needs/ lost its core function.** Live fosterneeds.html is a working listing of every dog needing a foster, each with a full writeup and this repeated CTA: "If you are interested in fostering and you are already a POMDR volunteer, email us. If you are interested in fostering and are not a volunteer yet, please fill out a volunteer application. Or, if you're interested in adopting, fill out an adoption questionnaire." Also intro copy: "Some dogs in our program are very popular, and multiple foster volunteers may be interested in fostering the same one. If the dog you are interested in is no longer available, please be patient, many others still need a foster home." The mirror page is a generic "Open your home. Save a life." landing with no dog list. A query of `status = Foster Needed` pets rendering cards would restore parity.

**B3. Courtesy listings break their own contract.** Live instructs "Please contact the person named in the dog's writeup." On the mirror, Kamere's writeup is truncated mid-sentence and the contact line ("Please call/text Christina 831-233-9310 if you'd like to learn more about Kamere!") is gone, while the page shows "Adopt Kamere", which routes to POMDR's own questionnaire. Same pattern risk for all courtesy dogs. Live also stamps each listing "Updated 7/17/2026" / "Posted 7/9/2026"; mirror has no dates. Courtesy dogs currently live-only: Kimber (Kevin, (831) 277-8458), Ms. Daisy, and others; mirror-only strays: Bailey Bae, Chester, Chikki, Chimmu, Cricket, Gypsy, Pepper.

**B4. Hospice sponsor recognition missing.** Live hospicedogs.php shows, under Wilson: "Sponsored By: Susan Sullivan, Kathy Kirros, Joanna A., Paula Lindsey, Tamara Rieser, Jimmy Mason, The Booboo Foundation, Kira Steinberg, Elbina Rafizadeh" plus a "Sponsor Wilson" button. Mirror hospice page and Wilson's pet page have no sponsor list, and Wilson's page offers "Adopt Wilson", which live deliberately does not.

**B5. Adopted Dogs wall gutted.** Live adopted.php lists ~3,500 adopted dog names ("What do all these dogs have in common? They are all adopted!"). Mirror shows 12. Needs a bulk import or a compact name-wall rendering fed from the historical list.

**B6. lifetimecareFAQ.html has no mirror page.** Six Q&As, verbatim source available in livetxt; key ones: "What if my dog is old or in poor health?... POMDR will accept your dog regardless of his or her physical condition"; "Do I need to pay anything up front? No. The Perpetual Care Program is funded through a pet trust that you set up with your attorney. There is no membership fee or cost to you during your lifetime..."; "Where will my dog live while he or she is awaiting a new home?... In the event that we do not have a foster home available for your dog, we will board him or her in a comfortable boarding facility with regular walks and outings...". Also /perpetual-care-program/ should link to it ("For more information, visit our page of Frequently Asked Questions").

**B7. About page team and reports.** Mirror staff grid is stale: lists Emily Termotto, Elizabeth Ramsay, Hannah Wakefield (no longer on live) and omits Nisha Addleman (Behavior Coordinator), Courtney Young (Adoptions Coordinator), Briana Ryan (Volunteer Coordinator), the entire Benefit Shop Staff (Carmelita Garcia, Manager) and Clinic Staff (Dr. Erin Trannel, Medical Director; Dr. Shahla Dourod, Veterinarian; Kelly Fischer, Clinic Manager; Celina Arceo, Head RVT; Caite Jackson, Elisa Clay, Veron Yip, Veterinary Assistants; Toni Selby, Receptionist). Missing download blocks: Impact Reports (2025, 2024, 2023 Annual Report) and 990 Forms (2024, 2023, 2022), live at /downloads/POMDRImpactReport2025, POMDRImpactReport2024.pdf, POMDRAnnualReport2023.pdf, POMDR990form2024.pdf, POMDR990form2023.pdf, POMDR990form2022.pdf. Mirror also claims "1,800+ Volunteers"; live says "over 1500 volunteers."

**B8. Culture page content replaced.** Mirror /about/culture/ invents values (Compassion first, Honesty always, Dignity to the end, Community over ego). The live POMDRCulture.html values are: Feedback, Best Practices, Positive Attitude, Solution-driven, Responsibility, Professional, Volunteer and Donor Appreciation, each with a definition (full text captured; e.g. "Responsibility: To agree that it is everyone's responsibility to create a positive experience for volunteers, guardian surrenders, donors, adopters and the general public whether or not what they need is in our job description."). Staff link to this page from the handbook; the real text should be restored.

**B9. Events.** Mirror /events/ is good but misses two live events: "Sunday, August 23, 11 - 1:30pm, Pet Pals, 3660 Soquel Drive, Soquel" and "Sunday, August 30, 11 - 3:00pm, Pawsh Retreat at Chaminade Resort & Spa, One Chaminade Lane". No ticket CTAs anywhere on mirror: live links Paybee (https://paybee.io/quickpay.html?handle=pomdr&ppid=20#optionList, For the Love of Dogs) and LGL wine-class tickets (form 3QI94qg-cetdBi5l5CGqPQ, $75 per ticket; raffle: "$10 each (3 for $25). Call Bob at (831) 477-9807"). Separately, /adoption-events/ on the mirror is a live, indexable placeholder page containing only a search widget and "No comments to show." It should be deleted or redirected to /events/.

**B10. News / What's Happening page missing.** Live news.html items with no mirror home: Paws and Prosecco details ("Brunch ticket $80. Bottomless mimosa ticket $95... Reservation by Phone: (831) 424-5555"), Wine Class details, Pet Trust Seminar ("Email to register and receive the Zoom link"), Benefit Shop hours plug, Two Bitch Bourbon affiliate ("POMDR receives 10% of every sale"), "The Tiny Dog with the Huge Heart" book (proceeds benefit POMDR), Wooftrax "Walk for a Dog" app, Catherine Sullivan cards and Zazzle shop ("100% of proceeds... go to POMDR").

**B11. Media page placeholder.** Live inthemedia.html has 37 dated press links (Monterey County Weekly, KSBW, Carmel Pine Cone, CBS, MSN, King City Rustler, etc., 2015-2026, newest: "Fostering Saves Lives", Pet Care Pointers, June 16 2026). Mirror has 3 generic cards and the literal text "Full archive moves here on WordPress launch."

**B12. Testimonials placeholder with invented people.** Mirror shows 3 fabricated quotes ("Lily & Pebble", "Marco & Watson", "Andrea & Honeybee") and the note "Full testimonial archive ships with the WordPress build." Live has 170 real quoted testimonials with attributions (Jill C., Brant S., Mohini P., Debra S., Terry G., Veronica M., Lenore H., and many more). Same issue on the homepage: three invented testimonials ("The Alvarez Family adopted Rosie", "Eleanor W. adopted Finn", "David and Marta adopted Biscuit") and unverified stats ("250+ Seniors supported, 3,200+ Dogs adopted, 1,800+ Active volunteers"). These violate the project's own no-invented-content rule and must be swapped for real data before launch.

**B13. Thank You donor recognition page missing.** Live thankyou.html (64 content blocks) recognizes donors by tier: "Keystone Donors (over $100,000 donated)" with subtiers Over $1,000,000 / Over $500,000 / Over $100,000, then category sections: Adoption Event Hosting, Advertising, Advertising Sponsorship, Alternative Treatments, Boarding, Dog Supplies, Event Sponsors, Lucky Dog Gala Sponsors, Fundraisers, Grants, Grooming, Marketing and PR, Photography and Videography, Veterinary Services, Last but Not Least. The mirror /thanks/ slug is already used for form confirmations, so the donor page needs its own slug (e.g. /thank-you/).

**B14. Tribute donor lists missing.** Live tributedonations.php is an 800KB recognition page ("We would like to thank the following people for making a tribute donation...") with yearly In Memory / In Honor lists for people and pets. Mirror /tribute-card/ shows only the card image. Decide: port the lists (large, staff-maintained) or consciously drop with Andrew's sign-off.

**B15. Donation form family incomplete.** Missing pages for Monthly (LGL 4T09LUBDyJtOCrfw3GMJqA) and Annual (LGL 1nOlM3NsxAGAZbqwYelwUg) donations; the mirror /donate/ "Set Up Monthly Gift" button currently sends donors to the live site. /donation/ hardcodes field_26=60 and ignores ?fund= and ?donationtype= parameters, so fund-restricted giving (Max's, Silver Hearts, Helping Paw, tribute) cannot complete on the mirror at all. Mirror /donate/ also omits five live giving options: Dog Tag Donor, Sponsor a Doggie Suite, Sponsor a Plaque or River Stone, Corporate Sponsor ("Email us for more information"), Annual Gift ("We will contact you annually to renew your gift").

**B16. Dog Tag Donor and Doggie Suite dead-ends.** Both mirror pages reproduce copy and donor lists but their CTAs ("Become a Dog Tag Donor" $500, "Sponsor a Doggie Suite" $5,000/yr) are unlinked promo headers. Needs two form pages embedding LGL 6PuyQVxzC0csoooyizCrhQ and z0H3cG-z_EGZ9Vq8qVgh_g, matching the three plaque/stone form pages that already exist.

**B17. Max's Fund page missing.** Live maxsfund.html tells the origin story (Max, Dyana Klein and Dr. Jonathan Fradkin, MHP helped 2,400+ pets 2016-2025, POMDR adopted the programs Get 'Em to the Vet / Short-Term / Continued Care, cap raised "from $500 to up to $2,500 per case") and ends with a Donate CTA. Mirror mentions the fund on /donate/ and /helping-paw/ but the "More info" story page does not exist.

**B18. Terms and Privacy are placeholders.** Both mirror pages state "This page is a placeholder. The final terms/policy will be reviewed by counsel before launch." The live termsandprivacy.html contains the actual policy (Device Information, cookies, Shopify/processor language, etc., 147 blocks) ready to port pending counsel review.

**B19. Jobs page missing current openings.** Mirror lists only Relief/Sunday Veterinarian. Live currently also posts: Adoption Coordinator ($28/hour plus benefits, 40 hrs Tue-Sat, Deadline April 3, 2026, apply to carie@pomdr.org), Development Director (open until filled), Dog Behavior and Enrichment Coordinator ($60,000-65,000, Deadline March 15, 2026), and RVT/Experienced Veterinary Assistant (open). Also missing: "Hear From Some of Our Former Employees" quotes section.

**B20. Smaller items.** /process/: add live note "Responses to our adoption questionnaires are typically sent via email - please check your spam/junk file." /why/: port the six real quotes (Wes, Dina Eastwood, Christine D., Linda L., Sue C., Sharon S.). /planned-giving/: add "Our full, legal name is Peace of Mind Dog Rescue" and "Our federal taxpayer identification number is 27-1154816." /forms/: add Educational Videos: Dog Body Language 20 min (youtube.com/watch?v=ra6ob7pEgaA), 60 min (NHQm2N6wDZY), Foster Training (0IhK0ytrr4E), The Importance of Management (spJoNCP6XrI), Vet Packs (ydN8stWwYo0). /volunteer/: appreciation-party album links 2015-2024 absent. Pet detail template: suppress "Adopt" CTA for Hospice/Adopted/Courtesy statuses. URL note: live dog IDs (dog.php?id=4917) do not match mirror pet IDs (/pets/3091/), so the redirect map must translate per-dog, not per-pattern.

## C. DEAD/LEGACY LINKS ON THE MIRROR

Dead now (404 on live too):
- /surrender/ "Request a Courtesy Listing" -> https://www.peaceofminddogrescue.org/POMDRsurrendercourtesy.php (404). Should point to /intake-questionnaire/.

Legacy links that will break at cutover (all should become internal):
- /donate/: POMDRMonthlyDonation.php; POMDRDonation.php?fund=Max's Helping Paws Fund / Silver Hearts Fund / Helping Paw Fund; POMDRDonation.php?donationtype=tribute; benefitshop.html; legacydonor.html; plannedgiving.html; sponsorad.html; wishlist.html (mirror pages exist for the last five; links never updated)
- /fostering/: fosterneeds.html ("click here"), POMDRVolunteerApplication.php
- /volunteer/: benefitshop.html, fostering.html
- /sponsorship/: POMDRMuralPlaqueSponsorship.php, POMDRRiverStoneSponsorship.php, POMDRWelcomeRoomPlaqueSponsorship.php (mirror -donation pages exist)
- /forms/: all 38 downloads point at peaceofminddogrescue.org/downloads/... (media must migrate or the path must be preserved)

Mirror-internal issues: /adoption-events/ is an orphan placeholder page; /wishlist/ (no hyphen) redirects to a bare JPG while /wish-list/ is the real page.

## D. RANKED IMPLEMENTATION PLAN

> STATUS 2026-07-21 (same day, implementation session): items 1-4, 6-10, 12-20
> are DONE on the mirror (dog re-sync with repeatable importer, foster listing,
> courtesy contract, CTA logic, monthly/annual/dog-tag/doggie-suite forms, fund
> passthrough, link repointing, Max's Fund page, Thank You wall, Perpetual Care
> FAQ, jobs quotes, real testimonials + honest stats, media archive, news page,
> About roster + reports, real culture values, real terms/privacy pending
> counsel). Remaining: item 5 (per-dog redirects, waiting on the production
> redirect import), item 11 tribute donor lists (Andrew's call, 800KB), item 21
> partials (recources slug rename decision), and PDF media migration at launch.

Adoption funnel:
1. Dog data re-sync from live (adopt/courtesy/hospice/adopted rosters, statuses, foster dates, Aged to Perfection flag). Effort M (ideally a repeatable import script, not one-off). Goes to: pets CPT.
2. Foster Needs dog listing: query Foster Needed pets with writeups + tri-CTA block. Effort M. Goes to: /foster-needs/ template.
3. Courtesy listing fixes: full untruncated writeups incl. contact person, posted/updated dates, replace Adopt CTA with "Contact [name]". Effort S-M. Goes to: pets template (Courtesy Listing status branch).
4. Pet template CTA logic: hide Adopt for Hospice/Adopted/Courtesy; show hospice "Sponsored By" list (new ACF field or repeater). Effort S.
5. Per-dog redirect map old dog.php?id -> new /pets/{id}/. Effort M. Goes to: Redirection plugin import.

Giving:
6. Monthly + Annual donation form pages, and /donate/ "Set Up Monthly Gift" pointed internally. Effort S. New pages /monthly-donation/, /annual-donation/.
7. /donation/ fund + tribute param passthrough to the LGL iframe (mirror the dogname prefill pattern). Effort S.
8. Repoint all /donate/, /sponsorship/, /fostering/, /volunteer/ legacy links to internal slugs; fix dead surrender courtesy link to /intake-questionnaire/. Effort S.
9. Dog Tag Donor + Doggie Suite form pages (LGL 6PuyQVxz, z0H3cG) and link the two dead CTAs; add the five missing giving cards (Dog Tag, Doggie Suite, Plaque/Stone, Corporate, Annual) to /donate/. Effort S-M.
10. Max's Fund story page. Effort S. New /maxs-fund/, linked from /donate/ and /helping-paw/.
11. Thank You donor recognition page (new slug, /thanks/ is taken) and Tribute donor lists (or a signed-off decision to drop). Effort M and L respectively.

Programs:
12. Perpetual Care FAQ page + link from /perpetual-care-program/. Effort S. New /perpetual-care-faq/.
13. Jobs: add the three staff openings + former-employee quotes. Effort S.
14. Real testimonials: replace the 3 invented quotes with the live 170 (or a curated subset), and replace invented homepage testimonials/stats with real ones. Effort M. Blocking issue for brand trust.

Informational:
15. Media archive: import 37 dated press items. Effort M. /media/.
16. News/What's Happening: decide destination for evergreen items (Two Bitch affiliate, book, Wooftrax, Sullivan cards); event items already live in the events CPT; add ticket links + 2 missing events. Effort M.
17. About page: current staff roster incl. clinic and shop staff, Impact Reports + 990 downloads, correct volunteer count. Effort S-M.
18. Restore real POMDR Culture values text. Effort S.
19. Port real Terms + Privacy text (flag for counsel review). Effort S.
20. Migrate /downloads/ PDFs (38 files) into WP media or preserve the path at cutover; add Educational Videos to /forms/. Effort M.
21. Cleanup: remove/redirect /adoption-events/ placeholder; consider renaming slug "recources"; /wishlist/ image redirect. Effort S.

Working files (all under the session scratchpad): live pages in `.../scratchpad/live/`, extracted text in `livetxt/`, mirror pages in `mirror/`, text in `mirtxt/`. Read-only audit; nothing on either site was modified.