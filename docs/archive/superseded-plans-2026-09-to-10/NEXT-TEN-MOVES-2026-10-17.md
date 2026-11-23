# The next ten moves

**Written 2026-10-17 for Andrew Z.**, after reading every ticket and comment on the tracker, Ronnie's commits since 2026-10-09, his `NEXT.md`, the latest production backup, and the published site.

## Where things stand this morning

**Ronnie.** Thirty-plus commits in eight days, all admin: the potential-intake flow, process statuses, behavior flags, medical status, Foster LT and ST lists, Prospective Adoptions with LGL people linked, tabbed editing with a who-has-this-open notice, Slack alerts (new intake, and now exam-needed, POMDR-43), and yesterday a four-column "All" board. The backup from last night shows **migrations 028 to 033 are applied on production** (his NEXT.md still lists them as pending; it lags). He has not touched a file of ours, and the hourly build is publishing our work: Donate's new photograph, the Spanish page as `lang="es"` and the seven foster dogs staff have now flagged are all live.

**Still untouched by him:** POMDR-32 (the roster; production `team` still has last year's 28), POMDR-28 (foster-page timing, your answer not yet posted), POMDR-17 (sitemap), POMDR-9 (donate forms), POMDR-6 (photo masters, which is also the R2 import). **Yours on the tracker:** POMDR-1 (in review), POMDR-11 (hours, Culture and News words), POMDR-13 (which animal fields may be public), and the four I filed for you on 10-05 (22 to 25) still unassigned.

**One thing his work changes for us.** Staff are now entering dogs *before* Shelterluv has them (potential intakes), and those dogs can appear on `/foster-needs/` through `get_public_foster_candidates()`. A dog with no photograph, no story and a working name can reach the public page. That is the design question in POMDR-28 and it is now live, not hypothetical.

## The ten moves, ranked

Each move names the owner, what "done" looks like from the visitor's side, and how to get it done. Ordered by what a visitor would feel first.

### 1. Put the right people on the team page (POMDR-32)
*Owner: Ronnie, or you with cluster access. One hour.*
The one visible wrong thing on the live site: four people who have left are on `/team/`, three who work there are not, and Susan's title is a year old. The SQL is written, idempotent, and attached to the ticket. **How:** post one line on POMDR-32 ("No bug; run `docs/team-roster-live-2026-10-05.sql` on prod, and please allow-list 162.200.150.126 so we can do these ourselves"), and move it to `todo` so it is on his board. Done when `pomdr-public.pages.dev/team/` shows 27 faces.

### 2. Decide what a dog needs before it goes public (POMDR-28, POMDR-13)
*Owner: you, with Ronnie. A conversation and a ten-line rule.*
Potential intakes can now reach `/foster-needs/`. Your answer is "wait for the photo and the write-up"; it needs to be in his code as a rule in `get_public_foster_candidates()` (a photograph and a non-empty story, or the dog is held back), and it belongs in the same decision as POMDR-13: which fields the public may see at all. **How:** post your answer on POMDR-28 today; propose the two-condition rule as a comment, since the function is his; and schedule the POMDR-13 field decision with him, because every new admin field (behavior flag, medical status, adoption status) is one more thing that must never leak to the public page. Design side, we add a visible "Photo coming soon" treatment only if you decide a dog may go live without one; otherwise nothing changes.

### 3. Photographs onto R2 (POMDR-6)
*Owner: Ronnie. The single biggest performance and resilience item.*
3,984 dog photographs and all 27 portraits are still served from the two old hosts at full size; `/team/` is 11 MB and every one of those pictures breaks the day either host goes dark. The portrait import is on his unmerged `cursor/team-portraits-r2` branch and the columns are already on production (`team.image_key` is there). **How:** a ticket of our own would duplicate POMDR-6; instead comment on POMDR-6 with the numbers from the thesis and the branch name, and ask for a date. Done when `/team/` is under 2 MB and no `<img>` on the public site points at `peaceofminddogrescue.org` or `new.pomdr.org`.

### 4. Donate that keeps its promises (POMDR-9)
*Owner: you decide, we build; Ronnie unaffected.*
Every amount chip, "Monthly", "Annual", "Legacy" and tribute card lands on one LGL form with nothing carried over. The live site had separate forms (`POMDRMonthlyDonation.php`, `POMDRAnnualDonation.php`, `?initialdonation=60`, `?fund=…`, `?donationtype=tribute`), all archived, so LGL evidently supports distinct forms and prefilled amounts. **How:** you confirm in LGL which form IDs and URL parameters exist (the archive lists the old targets as a checklist); we wire each chip and card to its own target, add the plain "give in memory, we send a card" path, and show the 2024 impact report as a card with its cover. Half a day once the IDs are in hand. Done when tapping "$275 monthly" opens a form that already says $275 monthly.

### 5. The pages the old site still has and we do not
*Owner: us, with POMDR's yes on content. One to two days.*
From the archive, in the order a visitor would miss them: **Thank You** (named donors, 1,151 words, plus 498 of plaque sponsors), **News** (13 items, which also fills the thin Events page and gives the council its "Recently" strip), **Max's Helping Paws Fund** (a card under "Give for a dog" with its own paragraph and link), **Culture** (the words are owed, POMDR-11), **Privacy and Terms** (3,778 words on the live site, movable as is with a date). **How:** you confirm the donor lists and news items are current (or say "use as is"); we build Thank You as a directory by year on the kit, fold News into Events, add the fund card. Done when nothing a returning visitor bookmarked on the old site is missing from the new one.

### 6. The launch mechanics nobody else will notice until launch day
*Owner: Ronnie, small. One short ticket.*
`sitemap.xml` from the build's own address list and a `Sitemap:` line in `robots.txt` (POMDR-17); `immutable` caching on the hashed assets via a `_headers` file (today every visit re-asks for the stylesheet and fonts); the redirect map from the 56 old addresses, which `AtLaunch.md` already describes and the archive now makes exact. **How:** one ticket, "Launch mechanics", listing the three with the file each lives in; all three are in `build/` and `deploy/`, so they are his by our rule. Done when a search engine can find every page and a repeat visit downloads nothing it already has.

### 7. Test with people (POMDR-25)
*Owner: you. Two afternoons.*
Everything measured so far was measured by machine or by me. The menu tree test and the program-page hallway test with five to eight older visitors (Benefit Shop volunteers would do) is the one audit no script replaces, and wording is cheap to change before launch and expensive after. **How:** the eight tasks are written in `docs/NAV-IA-PLAN-2026-09-25.md` section 6; a phone, a notebook and a quiet table. Record first taps and the words people use. We change labels, not structure, from what you hear.

### 8. The form rebuild on Shelterluv (pinned 2026-09-24)
*Owner: you and POMDR. The longest lead time, so start the conversation now.*
Margaret's verdict on the LGL questionnaire ("12px type, 117 fields, radio buttons the size of a grain of rice; I would stop there and phone") is the strongest single usability finding of the month, and nothing in our layer can fix a frame we do not own. The buttons above every frame are the bridge, not the answer. **How:** confirm with Shelterluv what their hosted application supports (large text, prefilled dog, Spanish); if it is better, the dog pages already send `?dogname=` and the switch is one URL per form.

### 9. The last phone details
*Owner: us. Half a day, when nothing above is waiting on us.*
Adopt filters start folded on phones with the search box alone showing; the drawer's accordion opens on first tap for the no-script case; the Perpetual Care and Placing Your Dog pages each gain one staff paragraph on what happens in the first 24 hours after the form (content from POMDR); the mailing-list cadence becomes one sentence once you tell me which is true; and the residual sideways overflow at 320px with 150 percent text on three long pages, if you want it chased past what WCAG asks.

### 10. Keep the standard alive after launch
*Owner: us, then whoever edits the site. An hour to set up.*
Everything we hold to is written down (Brand.md, Design.md section 11, the three audit documents) and the checks exist as scripts. What is missing is the habit: a one-page `docs/RELEASE-CHECKLIST.md` (lint, render, suites, large-text sweep, structure audit, Design.md line) that any future session runs before a push, and a quarterly re-run of the stress test. **How:** write the checklist from this week's commits; ask Ronnie whether the suites may live in his repo under `tests/` so they travel with the code (they are design-only, so they can, if he wants them).

## What I would do first, if you say go

1 and 2 are messages, not code: post them this morning. 6 is a ticket I can file now. 5 (Thank You, News into Events, the fund card) is the largest piece of visitor-facing work that needs only your "use the archived content as is", and it is what I would start building today while 1 to 4 wait on other people.
