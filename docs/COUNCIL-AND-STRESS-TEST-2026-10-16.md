# A thousand everyday visits, and a council of three

**Written 2026-10-16 for Andrew Z.** Companion to `PRE-LAUNCH-THESIS-2026-10-16.md`. All three council reports are in. Where the thesis measured the pages, this measures the journeys: what happens when people try to do the ten things they most come to pomdr.org to do.

Two kinds of evidence, kept apart because they answer different questions:

1. **The stress test.** Ten journeys, each run 100 times by a scripted visitor whose circumstances were drawn at random: phone (320, 390 or 430px), tablet or laptop; browser text at 100, 125, 150 or 200 percent; JavaScript on or (8 percent) off; reduced motion on or off; keyboard only (12 percent); arriving at the homepage or landing straight on the section page as from a search; and a patience of 8, 12 or 20 seconds. The visitor finds things the way a person does, by reading labels and tapping them. Third-party hosts were blocked and recorded, so reaching a Little Green Light form counts as reaching the goal. Script: `.playwright-mcp/study/stress.mjs`; results in `/tmp/claude-501/stress/`.
2. **The council.** Three reviewers walked the site independently with their own eyes and hands: Margaret, 74, Pacific Grove, large text on a phone, would rather phone than type; Dana, 48, communications and development director at a peer animal-welfare nonprofit, judging conversion and trust; Luis, 61, an accessibility practitioner and Spanish-speaking son of an 85-year-old dog owner, keyboard only, reading the page as a screen reader would. Each was told to be honest and to report only what they saw.

A caution on the stress test's own limits, so the numbers are read correctly. Two of its failure modes were the script, not the site: at exactly 1440px the script looked for the menu in the wrong container (the same menu passes 97 percent at 1280px, and the desktop suites pass by hand), so the 1440 rows are left out of the figures below; and roughly a fifth of the phone "gave up" results ended on the step "open menu", where the script was waiting for a section to expand, which a person does with one more tap. The remaining failures are the site's, and they are specific.

---

## 1. The ten journeys, scored

835 runs after the 1440 rows are removed. "Taps" is the median number of deliberate actions a visitor made.

| # | Journey | Success | Median time | Taps | Where it failed, when it failed |
|---|---|---|---|---|---|
| 1 | Find a small senior dog (filter by size and age) and start the adoption questionnaire | **96%** | 1.0 s | 6 | Two checkbox taps at 200% text did not register (the filter row overflows at that size) |
| 2 | Learn how adopting works, then start the questionnaire | **89%** | 1.0 s | 3 | The band's button leaves the site at once (section 2) |
| 3 | See dogs needing a foster home, then start the foster application | **86%** | 1.1 s | 3 | Drawer second-level waits on phones; one dog listed against "up to 15 waiting" |
| 4 | Get help keeping my dog (Helping Paw), in English or Spanish | **61%** | 1.1 s | 3 | **Every Spanish attempt failed**: the band sends people to the English LGL form, bypassing the page that holds the Spanish route (section 2) |
| 5 | I can no longer keep my dog: find the phone number and the intake form | **92%** | 0.6 s | 4 | Phone number on the first screen in 100% of runs; the rest were taps at 200% text |
| 6 | Donate, or sponsor a particular dog | **100%** | 0.5 s | 2 | None (but see Dana on what happens after the tap) |
| 7 | Volunteer: read the ways to help and start the application | **90%** | 0.6 s | 3 | Taps at 200% text on small phones |
| 8 | What is happening: find the next event, then join the mailing list | **90%** | 0.5 s | 3 | Taps at 125 to 200% text on the smallest phone |
| 9 | Find where to visit: address and hours of the center, shop or clinic | **91%** | 0.6 s | 2 | Address cards overflow sideways at 125% text and above (section 2) |
| 10 | Read a staff member's story; find an adopted dog by name | **93%** | 0.6 s | 1 | A few waits; the dialog opened in 35 of 42 story runs, the page in the other 7 (no JavaScript) |

**By circumstance.** Text at 100 percent: 97 percent success. At 125 and 150 percent: 88 to 90 percent. **At 200 percent: 63 percent.** Without JavaScript: 93 percent (higher than with, because the no-script paths are simpler). Keyboard only: 87 percent. Arriving from a search: 97 percent; arriving at the homepage and navigating: 86 percent. Laptop 97, tablet 92, large phone 91, standard phone 86, **small phone 78**.

**What the thousand runs say in one sentence:** the site works, quickly, for almost everyone at normal text size, and it works less well the larger a person has set their text, which is exactly the person this site is for.

---

## 2. Findings that are the site's, in order of weight

### 2.1 Large text breaks the grids (the biggest finding)

At 150 percent browser text, a 390px phone shows sideways overflow on the homepage (72px), Helping Paw, Placing Your Dog and Contact. At 200 percent it is 226px on those pages, and the adopt filters, the dog page thumbnails, the team page and the "How adopting works" arrow links all spill too. On a 1280px laptop at 200 percent the homepage hero overflows by 192px. Margaret met this on `/contact/`: the address cards ran off the right edge and "Boand Veterinary Clinic" was cut to "Clini".

**Cause, measured.** Twelve grids declare their column floor in `rem` without a guard: `repeat(auto-fit, minmax(18rem, 1fr))` and the like (`site.css` lines 1407, 1779, 1960, 2493, 2768, 3971, 4042, 4084, 4186, 4225, 4310, 5922). A `rem` grows with the user's text size, so at 150 percent an 18rem floor is 432px, wider than the 390px screen, and the grid refuses to shrink. The hero's overflow has the same root: a width built from `rem` units. This is WCAG 1.4.4 (Resize Text) and 1.4.10 (Reflow) territory, and it is the one accessibility failure the Lighthouse 100s cannot see, because Lighthouse tests at 100 percent.

**Fix (ours, Tier 0, small).** Wrap every rem floor in `min(100%, …)`: `minmax(min(100%, 18rem), 1fr)`. Twelve lines. The hero needs its own look. Then re-run the 150 and 200 percent sweep, which now exists (`council` scripts and the stress harness). This single change should lift the 200 percent success rate from 63 toward the 90s.

### 2.2 The pinned header at large text

At 150 percent text on a phone the strip plus the navigation card measure 194px, and Margaret reported it covering 42 percent of her screen and hiding the Call button and the opening hours as she scrolled. (It is not `position: fixed` in the stylesheet; it is the first thing on every page and tall, and at large text that is enough.) **Fix (ours, Tier 1):** on phones, let the strip (phone and Foster pills) scroll away and keep only the card row; cap the card's height in `px` rather than `rem` so text size does not grow it; or collapse the strip after the first scroll.

### 2.3 Two opening bands leave the site before the reassurance

`/helping-paw/`'s band buttons ("Apply for Helping Paw", "Solicitar en español") and `/process/`'s "Start an adoption questionnaire" link straight to Little Green Light, while the buttons lower on the same pages, every dog page, and the menu send people to our own `/helping-paw-application/` and `/adoption-questionnaire/` pages, which carry the "before you begin" notes, the time estimate, the phone fallback and, for Helping Paw, the Spanish route. Same words, two destinations. In the stress test every Spanish-speaking visitor who took the band failed to find the Spanish form. **Fix (ours, Tier 0, two lines):** point both bands at our own pages. (`/donate/`'s band goes straight to LGL too; for a one-step gift that is defensible, and Dana's point in 3.2 about what the chips do is the larger issue there.)

### 2.4 The dog page's facts at large text

At 150 percent, the three-across facts block on a phone breaks words ("Chihu ahua", "Femal e"), because `overflow-wrap: anywhere` is doing what it was told. **Fix (ours, Tier 1):** let the block fall to two columns when the text is large, by giving it `repeat(auto-fit, minmax(min(100%, 7rem), 1fr))` instead of a fixed three; at normal size it stays three across, at 150 percent it becomes two.

### 2.5 Small things the runs turned up

- `/foster-needs/` lists one dog under copy that says "up to 15 dogs waiting"; the mismatch reads as stale (Dana) and the stress test saw it every run. Data, not design: the staff switches (migration 027 and the new intake flags) are what will fill it.
- The "Ask us about Perpetual Care" button is a `tel:` link; on a laptop it does nothing visible (Margaret). Put the number in the label, or on wide screens make it the contact page.
- "More about Carie" on the team cards is a span inside the card link, with an arrow but no underline; Margaret had no finger cue that the card opened. Underline the phrase, or style it as the arrow links elsewhere.
- In the story dialog the Close pill sits over the first line of the bio at large text; a little top padding on the text column clears it.
- The adopt page at 200 percent: two filter checkboxes did not take the tap because the filter row had overflowed; 2.1's fix covers it.

---

## 3. The council

### 3.1 Margaret (74, large text, phone first)

Her four journeys, in her words where they help, are in the appendix. Her findings, in order: the tall pinned header at large text (2.2); the address cards overflowing on Contact (2.1); the LGL questionnaire in its frame ignoring her text size ("12px type, 117 fields, radio buttons the size of a grain of rice; I would stop there and phone instead"); the broken words in the dog facts (2.4); and the team card's missing finger cue. What reassured her: the phone number pinned to the top of every page, "It is not a test and there are no wrong answers", "Somebody reads every one and replies", the Perpetual Care headline ("exactly my question"), "no fee during your lifetime", and "Send checks here, not to the office". The one thing she would never change: the phone number at the top and the words "a real person will answer".

Her asks we can meet: a large "Open the form in its own page" button *above* each embedded form rather than a footnote below it, and "Prefer to talk? Call us and we will fill it in with you" as a real button. (Tier 1, ours.) The frame's own 12px type is Little Green Light's and is the strongest argument yet for the pinned Shelterluv rebuild of the adoption form.

### 3.2 Dana (development director, peer)

Donate is two taps from anywhere and the trust signals are better than most (Tax ID in every footer, three impact reports and three 990s as real PDFs, the numbers band, the PO Box line). Her central finding: **every amount chip, "Monthly", "Annual", "Legacy", "Silver Hearts" and every in-memory card points at the same LGL address with nothing carried over**, so a donor who taps "$275" or "Monthly" chooses again on the next screen; "a chip that does nothing teaches the donor the page is cosmetic". `/sponsor-a-dog/?dogname=Sebastian` never says Sebastian and the form does not receive the name. Under "Give for a dog", the Sponsor card links to `/adopt/` rather than `/sponsor-a-dog/`. There is no plain "give in memory of a person or pet, we send a card" path, which is the commonest tribute ask. The donate band is the only one on the site without a face.

Elsewhere: `/become-a-foster/`'s only first-screen button on a phone is the secondary "See dogs needing a foster home"; the form starts 1,270px down. `/events/` has one event with no venue, address or map, and an empty fundraisers column; "one event and an empty column makes a 17-year-old org with 1,500 volunteers look quieter than it is". The mailing-list promise says "one email a month" on the homepage and "a few times a month" on the sign-up page. Dog page `<title>` is the name alone; "Sebastian, ~12, Pomeranian" would carry in a feed, and the og:image is a portrait that Facebook will crop. Team photos are hotlinked from the two old hosts with empty alt text (the thesis's first finding, seen again from the outside).

What she says already beats most nonprofit sites: the three asks (phone, Donate, Foster) one tap on every page including the phone header; "before you begin" on every application; the surrender and Perpetual Care testimonials ("a program nobody else talks about, told in clients' words"); team dialogs that name each person's dogs; reports and 990s within three clicks with the Tax ID everywhere.

Her ranked asks: carry amount and frequency into the LGL URL or remove the chips (POMDR-9 is this); show "You are sponsoring Sebastian" and pass the name; a plain tribute-gift card; venue, address and map on events plus a "Recently" strip of two or three past-event photos; one inline email field and one cadence statement; the 2024 report as a card with its cover; dog page titles with the hook. Three gaps only POMDR can fill: program numbers for Helping Paw and the clinic; sponsor names on dog pages and the real foster-needed roster; photographs on the new site's own domain.

### 3.3 Luis (accessibility, keyboard, Spanish)

Method: keyboard only, the accessibility tree and ARIA snapshots, JavaScript off, reduced motion on, screenshots at 1280 and 390. He separates failures from preferences, and most of what he found is in the second column.

**WCAG 2.2 AA failures, three:**

| Criterion | Where | What | Weight |
|---|---|---|---|
| 3.1.1 Language of Page | `/helping-paw-application/es/` | `<html lang="en">` while the h1 ("Solicitar ayuda de Helping Paw"), the intro and "Antes de empezar" have no `lang="es"` ancestor; only the last band carries it. A Spanish voice is never chosen for the page's main content | High |
| 2.4.11 Focus Not Obscured | Homepage with the drawer open | Tabbing past the last drawer item leaves the popover open and puts focus on the slideshow controls underneath it; at 390px only a sliver of the focus ring shows beside the drawer | Medium |
| 3.1.1 / 3.1.2 (third party) | The LGL frame on the Spanish page | The frame's own `lang` is `en`, seven field labels are English ("First Name", "ZIP/Postal Code") inside the Spanish form, and its headings jump h1 to h4 | Medium, Little Green Light's |

Borderline, logged as usability rather than failure: two links named "Apply for Helping Paw" on one page going to different places (finding 2.3).

**Better than required, with the evidence:** the slideshow holds while keyboard focus is inside it (verified 9.5 seconds on "Meet our dogs"), on pointer and in a hidden tab; under reduced motion the control reads "Play slideshow" and every keyframe collapses to 0.01 s; inactive slides are `inert`, dots carry `aria-current`, the live region is off until the visitor acts; arrows 60px, dots 44x48, pause 48. The drawer toggle exposes its expanded state natively through `popovertarget` with no ARIA needed, Escape light-dismisses, focus returns to "Menu", and without JavaScript all 24 sub-links are visible. The team dialog is a true modal, labelled by the name, focus to Close, Escape closes, focus back on the exact card; without JavaScript the card is a real link. The phone number is tab stop 2 on every page; on the hard-moment pages the Call button precedes the form frame; frame titles are in the right language; the "open it in its own page" fallback is always visible. The adopt finder has real labels, fieldsets, a polite status ("Showing 52 of 83 dogs"), a URL that mirrors its state, a kind no-results message with the phone number, and a reset. Focus rings are 3px solid on chips and thumbnails, and the gallery radios respond to the arrow keys.

**His seven fixes, ranked for an older or assisted user:**

1. `<html lang="es">` on the Spanish page, with `lang="en"` on the header and footer. One line. (Tier 0, ours.)
2. Point the Helping Paw band, including "Solicitar en español", at our own wrapper pages (finding 2.3; "a Spanish speaker never sees 'Llame al (831) 718-9122', the ten-minute note, or the fallback"). (Tier 0, ours.)
3. Close the drawer when focus leaves it, as `nav.js` already does for the wide menu row; or make a visible "Close" its last item. (Tier 1, ours.)
4. Restore the accents on the Spanish wrapper page ("debería", "razón", "atención", "más", "ábralo", "página") and in the link label "Solicitar en español"; `/surrender/` already has them right. (Tier 0, ours, with a Spanish reader's check.)
5. The no-JavaScript finder: `/adopt/?size=small` without JavaScript shows all 83 dogs with no submit button; honour the parameters server side and add a submit button hidden once enhanced; hide the slideshow arrows, dots and Pause unless `.is-enhanced` (they render as dead buttons without JavaScript). Project stance, not WCAG. (Tier 1, ours.)
6. Put the Pause control before the slides in DOM order; it is the fourteenth tab stop today. (Tier 2, preference.)
7. Distinct alt text per gallery photo ("Tuxedo, photo 2 of 8"); all eight stage photos say "Tuxedo" today. (Tier 2, preference; 1.1.1 is met.)

Not verified: real VoiceOver or NVDA output (modelled from the tree), numeric contrast ratios (no axe in the test folder), the Sort select by keyboard in headless Chromium, and whether LGL allows a per-form language.

---

## 4. What to do, in order

Tier 0 (before launch), all ours and all small unless marked:

1. Guard the twelve rem grid floors with `min(100%, …)` and fix the hero's large-text overflow; re-run the 150/200 percent sweep. (Finding 2.1. Half a day.)
2. Point the Helping Paw and How-adopting-works bands at our own application pages. (2.3. Minutes.)
3. The Sponsor card on `/donate/` to `/sponsor-a-dog/`; `/sponsor-a-dog/` says the dog's name in its band. (Dana. An hour.)
4. One statement of the mailing-list cadence. (Dana. Minutes; POMDR confirms which.)
5. "Open the form in its own page" and "Prefer to talk? Call us" as real buttons above every embedded form. (Margaret. An hour.)
6. `<html lang="es">` on the Spanish application page, and its accents restored. (Luis. Minutes, plus a Spanish reader's check.)

Tier 1 (first fortnight):

7. The phone header: strip scrolls away, card height in px. (2.2.)
8. The drawer closes when focus leaves it; the no-JavaScript finder gets a submit button and server-side filters; slideshow controls hidden until enhanced. (Luis.)
9. Facts block to auto-fit columns so it falls to two at large text. (2.4.)
10. Perpetual Care's button says the number; team card phrase underlined; dialog text clears the Close pill. (2.5.)
11. `/become-a-foster/`: the primary action above the fold on a phone. (Dana.)
12. Events: venue, address, map link per event; a small "Recently" strip. (Dana; needs POMDR's photos.)
13. Dog page titles with the hook; a landscape share image where the photo allows. (Dana.)
14. Donate chips carry amount and frequency, or go. (Dana; this is POMDR-9, with LGL's URL parameters.)

Owed by POMDR and Ronnie, unchanged from the thesis: the photographs onto R2, the roster, Privacy and Terms, the Culture and News words, the sitemap, the vector logo, program numbers, sponsor names, the foster-needed roster.

---

## 5. What held under load

The rest is worth stating plainly, because it is what a thousand visits confirm: the phone number was on the first screen in every run that looked for it; Donate succeeded 100 times out of 100 in two taps; the adopt filters found a small senior dog and reached the questionnaire 96 times out of 100 in six taps and one second; the drawer, the dialog, the thumbnail strip and the slideshow did not break once across any device, text size or input method; no run met a 404; no page overflowed at normal text size. The site's weakness is narrow and nameable, which is the best kind to have a week before launch.

---

### Appendix A: Margaret's walk, in her words

*Journey 1, a small calm older dog.* "Tapped 'Meet our dogs' (big white pill, easy). On the dogs page I tapped the 'calm' chip, then 'Smallest first' in Sort. Pie, 5 lb Chihuahua, ~12, came first. Her page says 'professional cuddler', 'gentle, easygoing'. Tapped 'Adopt Pie' at the bottom. The phone header takes the top of my screen at my text size and stays there, so every page starts with a scroll. The dog stats grid breaks words. The questionnaire itself is a third-party form in a frame: 12px type, 117 fields, radio buttons the size of a grain of rice, and my large-text setting does not reach it. I would stop there and phone instead."

*Journey 2, Perpetual Care.* "Menu, Get Help, Perpetual Care, three taps; the accordion was clear. The heading 'What happens to your dog if something happens to you' is exactly my question. The four steps are plain and honest, and 'no fee during your lifetime' is a relief. Did not get lost."

*Journey 3, phone, hours, cheques.* "The number is at the very top of every page and in the footer. 'Call (831) 718-9122' is a big button, but at my size it is hidden under the header after one scroll. Hours and 'Send checks here, not to the office, P.O. Box 51554' are there, but the address cards run off the right edge."

*Journey 4, a staff member.* "Menu, About Us, Our team. Section pills, then 'More about Carie'. A pop-up card opened with her photo and dog, and a big Close bubble. Readable, kind, good."

### Appendix B: how to re-run

```
cd .playwright-mcp/study
SEED=11 node stress.mjs 1,2,3 100      # journeys 1 to 3
SEED=23 node stress.mjs 4,5,6 100
SEED=37 node stress.mjs 7,8,9,10 100
python3 /tmp/claude-501/stress/agg.py  # the table above
```

The development server must be up on 127.0.0.1:8777 with the local database. Each batch takes about ten minutes.
