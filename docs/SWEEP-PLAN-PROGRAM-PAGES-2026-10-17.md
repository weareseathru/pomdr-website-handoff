# The sweep: what can be built now, across the program pages

**Written 2026-10-17 for Andrew Z.** The buildable half of `PROGRAM-PAGES-UPGRADE-BRIEF-2026-10-17.md`: every item that needs no fact, name, photo or decision from anyone else. The other half is on the tracker as POMDR-47 (for Andrew). Each item names the page, the change, the kit piece it uses, what it is built from (only things already on the site or the live site), and the check that proves it.

## Ground rules for the sweep

- Facts only from the live site archive, the current pages, or Ronnie's data. No new numbers, names or promises.
- Verbatim voices only; a quote may move pages but not change.
- One stylesheet, no script a page needs, no inline styles; every new piece on `/patterns.php`.
- Each item ships as its own commit with a Design.md section 11 line, lint, the render of its page, the three suites, and the large-text sweep where layout changed.
- Phone first: every new block is checked at 390px and 150 percent text before desktop.

## A. One new kit piece, used four times

**A1. The promise strip** (`includes/promise-strip.php`, `.promise-strip`). A quiet tinted band with the live site's lifetime-commitment sentence, verbatim, and a small real photograph already in `assets/img/` (the Perpetual Care pair, or the hospice stroller dog). Identical on every page it appears, so a visitor learns to recognise it. Optional second line per page, passed in: on Get help "Whatever you decide, your dog's story has an ending we have already written"; on Placing Your Dog "Once your dog is ours, they are ours for life"; on Hospice and Why the sentence alone.
*Used on:* Get help (foot), Placing Your Dog (after the steps), Hospice (above the dogs), Why (replacing the "No dog is ever left behind" paragraph's framing, keeping its words).
*Check:* patterns page, 390/1280, 150 percent, contrast of the band's text.

## B. Page by page

### B1. Get help
- Spanish within one tap: a small "En español" link in the band's lead or as a third action (secondary style), to `/helping-paw-application/es/`. Already exists as a page.
- The promise strip at the foot, above the close.
*Built from:* existing pages. *Check:* stress journey 4 Spanish path from the hub.

### B2. Helping Paw
- **Eligibility first:** a two-card "Is this for me?" block after the first split, lifting the two folded answers (counties; the income rule) into plain sentences. The folds stay for detail. Facts verbatim from the page.
- **One client voice:** a `voice-feature` with an existing Helping Paw testimonial (Steve's, already featured on the testimonials page, or another of the 36), words only until a photo is approved.
- **Volunteer the other way:** a quiet card at the end linking to the Volunteer page's Helping Paw role ("Could you walk a dog for someone who cannot?").
*Check:* render, phone, the Helping Paw stress journey.

### B3. Placing your dog
- The promise strip after the four steps.
- The intake questionnaire page opens with the same no-judgement sentence from the new copy and keeps its Call button above the frame (it already has the distress flag; add the sentence).
*Check:* render both pages; journey 5.

### B4. Perpetual Care
- **Print stylesheet** for the site, checked on this page first: hide the strip, nav, slideshow controls, footer columns and the drawer; keep the band text, steps, questions (details forced open), the phone number and address; link addresses printed after links. A "Print this page" arrow link under the band (`window.print()` via a tiny inline-free handler in a shared `print.js`, or simply a note that the browser's print works; the no-script rule says the note).
- The first two questions open by default (`open` attribute on the two `details`).
*Check:* a PDF print of the page at Letter size, read end to end.

### B5. Fostering, Become a foster, Dogs needing a foster home
- **Dogs first:** on Fostering, a strip of up to four foster-needed dogs (`pet-card`, from `pets_needing_foster(get_public_foster_candidates())`) under the band, with "See all N" to the list. Falls away when none.
- **Short-term and long-term grouped** on the list page: `pet_temp_foster_open()` already distinguishes them. Two headings, each with its one-line promise from existing copy ("a few weeks while someone is in hospital" is live-site wording for Helping Paw temporary foster; "until adopted" is the page's own).
- **A foster FAQ** (`qa`), eight questions answered only from the live site and current pages: do I need a yard; can I foster if I rent; who pays the vet; who provides food; what if it does not work out; can I adopt my foster; how long does it last; what support do I get. Placed on Fostering above the voices.
*Check:* render all three; journey 3; large-text sweep.

### B6. Volunteer
- **Grid without an orphan:** split "Your specialty" into "Writing, grants and design" and "Photography and video" (both are in the live site's list), so ten cards become eleven, or set the grid to two columns at the width where ten leaves one. Prefer the split; it also makes the photography ask visible, which the timeline proposal needs.
- **One volunteer voice:** a `voice-feature` with an existing foster-or-volunteer testimonial, words only.
*Check:* render at 1280 and 390.

### B7. Donate
- **"What a gift does" placeholder held back** until clinic costs arrive (POMDR-47); nothing invented.
- **Tribute path first** in "Give in memory or in honor": a card linking to the existing LGL form with the note that a card can be sent, only if the live site's tribute-card page (20 words, archived) confirms it; otherwise reorder only.
- **Impact report as a card**: title, year and the two figures already on the About page, linking to the PDF, in place of the bare bullet. No cover image (would need a render of the PDF; held).
- The chips stay until POMDR-9 is answered; a one-line note under them says what they do today ("opens our giving form; choose the amount there").
*Check:* render; Dana's donate journey.

### B8. Events
- **Add to calendar** as a Google Calendar link per event (a URL built from the event's fields; nothing loads until tapped). An `.ics` file would be written by the build and is Ronnie's.
- "Which dogs will be there" waits on the event-dog join (POMDR-46).
*Check:* link opens a prefilled calendar event.

### B9. Hospice and Sponsor a dog
- The promise strip above the dogs.
- Sponsor page: the band's lead names what sponsorship pays for in the words the Hospice page already uses.
*Check:* render; journey 6.

### B10. Why a senior dog
- **The lifetime commitment as a reason to adopt:** a fourth reason card, "If it ever does not work out, they come back to us, for life," from the live site's commitment.
- **The honest medical block:** one `story-split` or tinted block gathering what is already scattered: every dog has a full exam, a senior blood panel and dental work before adoption (process page); adoption fees $155 to $305 by age and health (process page); the lifetime commitment. No new claims.
- **Share image** for the page: a prepared face photograph already in assets, set as the band image so `og:image` carries it.
*Check:* render; the band image on a phone.

## C. Order of work (about three working days)

1. A1 promise strip and its four placements (B1, B3, B9, B10). Half a day.
2. B5 foster pages (dogs first, grouped list, FAQ). One day.
3. B2 Helping Paw and B10 Why (eligibility first, voices, the medical block, fourth reason). Half a day.
4. B4 print stylesheet and Perpetual Care defaults. Half a day.
5. B6, B7, B8 small items. Half a day.
6. The full gate at the end: lint, 71-page render, three suites, zoom sweep, a 300-run stress sample on journeys 3, 4 and 6, Lighthouse on four pages, Design.md lines, push.

## D. Held, on purpose

Anything with a number, a name, a face or a promise not already on the site (POMDR-47); anything in `build/`, `bin/`, `db/` or the admin (POMDR-45 and 46); the donate chips' behaviour (POMDR-9); News into Events (needs the item list); the Thank You page (needs "use as is").
