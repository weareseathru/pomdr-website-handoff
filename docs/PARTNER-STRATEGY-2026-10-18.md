# Partner strategy: what the new site needs from POMDR, what we give back, and where every piece starts

**Written 2026-10-18 for Andrew Z.**, to carry into the staff and Ronnie conversations. An honest list, as allies rather than as a vendor with a punch list. The code side is in good shape; the next jump in quality comes from POMDR's own material and a handful of decisions. For each item: why it matters to a visitor, what exactly we need, how we help carry it, where it starts (links, files, people), and what we do the hour it arrives.

Addresses used throughout: public preview `https://pomdr-public.pages.dev/`, old live site `https://www.pomdr.org/` (full archive in `docs/archive/live-site-2026-10-16/`), tracker `https://projects.imerge.com` (project POMDR), admin `admin.pomdr.org` (Ronnie, behind Cloudflare Access), repository `github.com/ronniereid/pomdr`, Cloudflare bulk redirects `https://dash.cloudflare.com/afb5676f396aba99dbc2563bfbac5935/bulk-redirects`.

---

## 0. The honest picture in five lines

1. The public site is built, checked and already carrying POMDR's words. What it is missing is **POMDR's own facts, faces and figures** in about a dozen slots that wait empty rather than hold invented text.
2. Two things are **visibly wrong today** to any visitor: the Events page says nothing is scheduled, and the team page on production shows former staff. Both are data, both are small, both need a person.
3. **Three decisions** are blocking launch mechanics more than any code: the Umami analytics question, which Little Green Light form each donate option points at, and what a dog needs before it goes public.
4. **Nobody has watched an older visitor use it yet.** Everything was measured by machine or by me. One afternoon at an adoption event with a paper menu closes that gap.
5. Ronnie's side carries the launch plumbing (photos onto R2, sitemap, redirects, roster SQL). The proposal in `PROPOSAL-BUILDING-ON-RONNIES-WORK-2026-10-17.md` is written to make each of those a small, welcome addition rather than an ask.

---

## 1. The plan, in four waves

| Wave | When | What closes | Who |
|---|---|---|---|
| **A. This week, no meetings needed** | by 2026-10-25 | Events entered; former staff removed; volunteer count decided; logo file sent; Umami decision; donate form map confirmed | Andrew + one staff hour |
| **B. One staff sitting** | one 90-minute session, week of 2026-10-27 | the dozen content slots (stories, photos, figures, names, times); the "first 24 hours" lines; the three adoption-day questions | Carie, Monica, Allison, Shanel/Alejandra, with Andrew; we bring the form |
| **C. The Ronnie meeting** | next scheduled call | POMDR-32 roster SQL + DB access; POMDR-28 readiness rule; POMDR-6 photos to R2; POMDR-17 sitemap/redirects; POMDR-13 field registry; the timeline discussion | Andrew + Ronnie (agenda is POMDR-45) |
| **D. Test with people, then launch list** | first adoption event after B | tree test + hallway test (POMDR-25); screen-reader walk; LGL reCAPTCHA check; privacy/terms text; redirect list | Andrew + 5 to 8 visitors; we write up |

Everything in A and B lands on the site with no code change beyond pasting words and running photographs through `bin/prepare-page-image.php`. We do that the same day.

---

## 2. Wave A: this week, no meeting needed

### A1. Put the next events on the site
- **Why:** Events is a top-level menu word. Today it says "Nothing is scheduled at the moment" in both sections. A visitor learns in one tap that the site is not kept.
- **Need:** the next 4 to 8 adoption events and any fundraiser: title, date, start and end time, venue name, street address, one sentence, a link if there is one.
- **Starts at:** the old site's `https://www.pomdr.org/adoptionevents.php` and `news.html` (archived as `adoptionevents-php.md`, `news-html.md`); Metricool and Mailchimp calendars already hold the schedule; staff enter them in the admin's events screen (Ronnie's), or send them to us and we hand Ronnie the rows.
- **We do:** the page already groups adoption events and fundraisers, prints address, map link and an "Add to my calendar" link per event. Nothing to build.

### A2. Remove former staff from production (POMDR-24 part 2, POMDR-32)
- **Why:** the team page is the trust page. Production still shows the old roster of 28; the local copy has the right 27 from the live site.
- **Need:** either Andrew deletes the stale records in the admin staff screen, or Ronnie runs `docs/team-roster-live-2026-10-05.sql` on production (POMDR-32, his since 10-07).
- **Starts at:** `docs/TEAM-ROSTER-SYNC-2026-10-05.md` has the diff and the SQL; the live roster is `https://www.pomdr.org/aboutus.html`.
- **We do:** nothing further; the pages read the table.

### A3. Decide the volunteer number (POMDR-47 decision 1)
- **Why:** the site says 1,500+ in five places; the live Volunteer page says "over 1800". One of them is wrong on a trust figure.
- **Need:** one number from Carie or Monica.
- **We do:** change five strings the same hour (About, Happy tails, Donate report card, Volunteer, numbers band).

### A4. Send the vector logo and glance at the share card (POMDR-23)
- **Why:** the header logo is a 600px PNG and reads soft on every modern phone; every shared link carries a default card that nobody at POMDR has approved.
- **Need:** the logo as SVG, AI or EPS (it is in POMDR's Google Drive brand folder); a yes or a change on `https://pomdr-public.pages.dev/assets/img/share-default.jpg`.
- **We do:** convert, replace in header, footer and share image, re-run Lighthouse; half a day.

### A5. The Umami decision (thesis finding 6.8)
- **Why:** Ronnie added cookieless analytics on every page (POMDR-42). It is harmless and useful, and it is also a third-party request the brand rules say to approve first.
- **Need:** Andrew says "accept, cookieless only" or "gate it".
- **Starts at:** the script is `https://stats.imerge.net/a.js`; Umami's privacy page `https://umami.is/privacy`; the dashboard is Ronnie's, ask for read access so POMDR has its own numbers (and the "do older visitors use phones here" answer).
- **We do:** amend `Brand.md` and `CLAUDE.md` to say so, or write the gate as a proposal.

### A6. Map each donate option to its Little Green Light form (POMDR-9)
- **Why:** all 26 donate links go to the general form `62FAoG7Obtf81TYETJMN3Q`. A monthly donor, a dog-tag donor and a suite sponsor each end up on the wrong form and have to explain themselves in a notes box. It is money, which is why it was left for a human.
- **Need:** the form id for each: general, monthly, annual, tribute, dog tag, suite, plaque, Max's fund, Silver Hearts fund, wish list. Found in the LGL account under Forms, or in the earlier rebuild's templates (NEXT.md item 8).
- **Starts at:** LGL login `https://secure.lglforms.com/` (POMDR's account); forms already in use on the new site: adoption `utzjcNEZaqAcJk3QURlQmw`, Helping Paw `HsUdXNpwCEoU8Kc9e_H6Hw` (EN and ES), mailing list `gTxA6GdJUmSjS2J4hXvcKg`, plus volunteer, foster, intake and sponsor ids in their page files.
- **We do:** one table in `donate/index.php`, the chips carry amount and frequency where the form accepts a query string, and we check every link by hand with you. One hour once the ids arrive.

---

## 3. Wave B: one staff sitting (90 minutes)

Bring a laptop with the preview open and this list. Each item is a slot that already exists on a page.

### B1. Two stories with a face
- **Helping Paw:** one client story and photograph, with consent, for the opening split on `/helping-paw/`. Steve's quote is there now; a face beside it doubles its weight.
- **Perpetual Care:** one enrolled family or dog, with photograph, for `/perpetual-care-program/`.
- **Starts at:** the 36 Helping Paw testimonials already on `/testimonials/#helping-paw`; Shanel and Alejandra know which clients would say yes. Consent line: a one-sentence email reply is enough; keep it in Drive.

### B2. The numbers that make Donate honest
- "Where your gift goes" on `/donate/` is written in words because we have no figures. Needed: what a dental costs POMDR, clinic visits a year, dogs in care today, Helping Paw grants given last year, the dollar cap (confirm $500).
- **Starts at:** the 2024 Impact Report PDF already on the site (`/assets/docs/impact-report-2024.pdf`) has several; the clinic manager (Kelly) and Monica have the rest; the 2023 990 is linked on `/about/#financials`.
- **We do:** a "What a gift does" block with three real figures, and the Helping Paw page's own numbers band.

### B3. Names and times (POMDR-47 items 6 to 11)
- Who answers the Get help line and which days ("You will reach Shanel or Alejandra, Monday to Saturday").
- Who handles intake calls for Placing Your Dog and the usual reply time in days.
- "What to expect in the first 24 hours and the first week" for Placing Your Dog and Perpetual Care: three plain lines each from the people who do it.
- Time per volunteer role and which two or three need no training.
- Foster mentor facts: how many fosters a mentor supports.
- Helping Paw eligibility wording confirmed exactly as the page states it.
- **We do:** named people on the care pages (purple side), time chips on the Volunteer role cards, "what happens next" steps on the two intake pages.

### B4. Ten featured testimonials and their groups
- Star ten of the 152 for the featured spots across the site, and tag any quote whose group the source does not say.
- **Starts at:** `/testimonials/` on the preview, five group jump links. A printed list with checkboxes works.

### B5. The three adoption-day questions (for the timeline, POMDR-44)
- Agree the three questions staff ask every adopter on adoption day ("Tell us a couple of reasons you fell in love with Buddy", "What surprised you in the first week?", "What would you tell someone thinking about a senior dog?"). This feeds happy tails, the newsletter and the Herald ad from one place.
- **Starts at:** `docs/PHOTO-TIMELINE-PROPOSAL-2026-10-17.md` section 3.2; the drop-folder question (Dropbox or Drive, which one staff actually use) is the other decision in that document.

### B6. Small confirmations
- Which photograph is "Darla with Tori" (both earlier sites used a file named eileentori.jpg).
- Mailing-list cadence: once a month or a few times a month.
- Spanish button: "Solicitar en español" or "Solicitar ayuda".
- May we quote the foster statistic (one night in foster, 14 times more likely to be adopted) once the primary source is confirmed.
- Hours for the Bauer Center and the Benefit Shop confirmed (site says Mon to Sat 11 to 4, and Tue to Sat 10 to 5).

### B7. Four pages the old site has and we do not
- **Thank You** (`thankyou.html`, 1,151 words of donor thanks): confirm "use as is" and the year it covers.
- **Max's Helping Paws Fund** and **Silver Hearts Fund** (`maxsfund.html`, `silverheartsfund.html`): confirm the words and whether each still has its own LGL fund.
- **Wish List** (`wishlist.html`): the current list, or an Amazon or Chewy link if that is how it runs now.
- **Culture** (`POMDRCulture.html`): the values as written.
- **Starts at:** all archived under `docs/archive/live-site-2026-10-16/`; each is an afternoon's page on the kit once the words are confirmed.

### B8. Legal text
- Privacy and Terms are ten-word placeholders. Needed: the real text, or permission to adapt the old site's (it had none of substance).
- **Starts at:** a plain-language nonprofit privacy template, for example the California Attorney General's CalOPPA guidance `https://oag.ca.gov/privacy/ccpa` and the Nonprofit Risk Management Center's checklist; LGL's own privacy notice covers the form data `https://www.littlegreenlight.com/privacy/`; Umami's cookieless statement if A5 is "accept". We draft, POMDR's board signs.

---

## 4. Wave C: the Ronnie meeting (agenda is POMDR-45)

Each of these is already written up as an addition to his work in `docs/PROPOSAL-BUILDING-ON-RONNIES-WORK-2026-10-17.md` (POMDR-46). The ask in the meeting is order and ownership, not design.

| Item | Ticket | What the visitor gets | Starter |
|---|---|---|---|
| Roster SQL on production, DB read access for Andrew's machine | POMDR-32 | the right people on the team page; local work on real data | `docs/team-roster-live-2026-10-05.sql`; IP to allow-list is in the ticket; needs `do-mysql-ca.crt` and read-only creds |
| A public-readiness rule (photo + story before a dog shows) | POMDR-28, 13 | no bare dog on Foster needs | proposal section 2.1, ten lines in `db.php` |
| Photographs onto R2 (3,984 + 27 portraits) | POMDR-6 | fast pictures on the new domain; no dependence on the old hosts `www.pomdr.org` and `www.peaceofminddogrescue.org` | his `cursor/team-portraits-r2` branch; `bin/sync-images.php` |
| Sitemap, robots, cache headers, redirects | POMDR-17 | Google finds the new site; 3,596 bookmarked dog pages keep working | proposal section 2.4; `AtLaunch.md`; Cloudflare bulk redirects dashboard |
| Field registry: what the public may see | POMDR-13 | the timeline's public switch; sponsor names on dog pages | his four-level list in the ticket |
| Story prompt on intake, Slack nudge for missing photo or story | new | better dog pages with less staff effort | proposal 2.2 and 2.3 |
| Event photographs and an `.ics` per event | POMDR-10 | an Events page that looks alive | proposal 2.7 |
| The photo and video timeline | POMDR-44 | one organised place for every dog's pictures and words (internal) | `docs/PHOTO-TIMELINE-PROPOSAL-2026-10-17.md` |
| Share Umami data monthly | new | POMDR's own answer to "do older visitors use phones here" | his dashboard |

What we give back in the same meeting: the test suites moved under `tests/` in his repo if he wants them, a one-page release checklist, and the commitment that every public page keeps reading his functions and never queries the database directly.

---

## 5. Wave D: test with people, then the launch list

### D1. The tree test and the hallway test (POMDR-25)
- **Why:** everything so far is machine-measured. Wording is cheap to change the week before launch and expensive the week after.
- **How:** at an adoption event or at the Bauer Center. A printed menu (seven words, their children) and a phone with the preview. Eight everyday tasks, for example "your mother is moving into care and cannot take her dog", "you want to pay for one dog's care", "you want to meet the dogs this month". Pass mark 80 percent first-click. Then: can each visitor start the application on Helping Paw, Placing Your Dog and Fostering in one tap.
- **Starts at:** `docs/NAV-IA-PLAN-2026-09-25.md` has the task list and scoring sheet; five to eight visitors, fifteen minutes each. If a task fails, change words before structure.
- **We do:** the write-up and the word changes the same week.

### D2. A screen-reader walk (one hour)
- Five journeys with VoiceOver on an iPhone (Settings, Accessibility, VoiceOver): find a dog and start the questionnaire; find help keeping a dog; find the phone number; read a person's story; donate.
- **Starts at:** Apple's VoiceOver guide `https://support.apple.com/guide/iphone/turn-on-and-practice-voiceover-iph3e2e415f/ios`; the structure is already right (landmarks, labels, live regions), this measures whether it is pleasant.

### D3. The LGL questions (POMDR's account)
- In September LGL showed a reCAPTCHA image challenge on the sponsor form, which shuts out a share of older visitors. Ask LGL support whether it can be turned down for POMDR's forms. The adoption form rebuild on Shelterluv is pinned separately (2026-09-24).
- **Starts at:** LGL support `https://help.littlegreenlight.com/`.

### D4. The launch mechanics
- The redirect list from old addresses (`dog.php?id=`, the `.html` pages) to new ones; the DNS cutover order; the week-after watch (404s in Cloudflare, Search Console once the sitemap exists).
- **Starts at:** `AtLaunch.md` in Ronnie's repo; `docs/DEPLOYMENT-ROADMAP.md` is Divi-era and only its decision list still applies.

---

## 6. How we help, as partners

- **We bring the form, not the homework.** For Wave B we will print a two-page sheet with every slot, an example answer, and a box. Staff talk; we type.
- **Nothing waits on code.** Every slot in this document already exists on a page. Words arrive by email or in a tracker comment and are on the preview within the hour, on the public site at the next hourly build.
- **We protect the voice.** POMDR's own sentences go in verbatim; where we shorten, we show before and after.
- **We carry Ronnie's load where we can.** Each item on his side is already written as an additive patch he can accept, rewrite or refuse, with the design-only rule intact.
- **We measure and report back.** After each wave: the stress harness, the large-text sweep, Lighthouse on the changed pages, and a short note in the tracker with the numbers.
- **We keep the standard after launch.** The release checklist, the suites and the brand reference stay in the repository so the next person, or the next year's staff, can keep it.

---

## 7. What to say to staff, in one paragraph

The new site is built and it is kind to the people it is for. What it needs now is you: the next events, two stories with a face, the real numbers behind "where your gift goes", who answers the phone and when, and ten quotes you would star. Ninety minutes together covers it. Nothing you give us is edited without showing you, and every piece goes live the same day.
