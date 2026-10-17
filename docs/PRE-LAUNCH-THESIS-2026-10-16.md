# Before the DNS moves: a whole-site examination of the POMDR rebuild

**A pre-launch thesis, written 2026-10-16 for Andrew Z.**
Subject: the public site in `New-Build` (ronniereid/pomdr, main at ecb5a39), as served on the development server and at pomdr-public.pages.dev.

---

## Abstract

The rebuilt POMDR site is, by the measures that matter to its audience, already a good site: every one of 71 pages has one `<h1>`, a skip link, a single `main` landmark, a canonical address and social preview tags; nothing on it is set below 16px; every interactive control clears the 44px floor; every page does its whole job without JavaScript; the heading order never skips a level; and Lighthouse reports 100 for accessibility on every page audited. Those are not common results for a site of this size, and they should be defended.

This document is about what is left. It examines every page, link, word, type setting, spacing, interaction, script, image and delivery header, and finds that the remaining risks are concentrated in four places that the design work could not reach by itself: **photographs still served from the two old hosts** (3,984 of 11,313 dog photographs and all 27 staff portraits, which together make the team page an 11 MB download and will break outright when those hosts are switched off); **the first screen on a slow phone** (LCP of 4.6 seconds under Slow 4G, 84 percent of it the hero photograph); **words that are still placeholders or owed** (Privacy and Terms at eleven words each, a broken link to a Culture page that does not exist, the production staff roster a week out of date); and **a short list of hygiene items** (no `sitemap.xml`, dead external links on the Resources page, assets served with `max-age=0`).

It recommends 23 changes, ranked into three tiers, with the owner and the evidence for each. Eleven of them are ours to make in the public layer without touching the design; eight need Ronnie; four need POMDR. None of them asks the design to change direction. The argument throughout is the one the project has held since September: the reader is seventy, on a phone, often in a hard moment, and every second, every byte and every word is spent on her behalf or wasted.

---

## 1. Method

Everything below was measured, not judged by eye, and the scripts are kept so the measurements can be repeated (`.playwright-mcp/study/` in the handoff repository: `site-audit.mjs`, `mobile-audit.mjs`, `coverage-nojs.mjs`, `mobile-sweep.mjs`, `chunks.mjs`, plus the suites `hero-b.mjs`, `team-dialog.mjs`, `pet-thumbs.mjs`, `drawer2.mjs`).

| Dimension | How it was measured |
|---|---|
| Links | Every `<a>` on 71 pages (38 section pages, 27 person pages, 6 dog pages): 104 distinct external addresses checked with HEAD; internal targets checked against the page list; inbound counts per page |
| Words | The visible text of each page: word count, Flesch-Kincaid grade, sentences over 28 words, POMDR's voice rules (em dash, "he or she", "senior citizen", British spelling, "Meet [Name]" openers, closing questions), sentences repeated on three or more pages |
| Type and spacing | Computed font size of every text node, paragraph measure in characters, line heights, families, the vertical gap between every pair of adjacent blocks in `main` |
| Targets and focus | Every control's rendered size; the first six Tab stops on three pages with their focus rings; `focus-visible` rules counted |
| Images | Count, `alt`, `loading`, `width`/`height`, natural versus rendered size, host |
| Weight and speed | Bytes by type per page at 1440px over a warm server; LCP from `PerformanceObserver`; a Chrome performance trace of the homepage at 390px, 3x, Slow 4G, 4x CPU; production headers, compression and TTFB from pomdr-public.pages.dev |
| Structure | DOM size, inline styles, scripts per page, iframes, third-party hosts, CSS and JS used (indicative) |
| Behaviour | All pages with JavaScript off; the drawer through tap, Tab, scroll, Escape and tap-outside; the slideshow, team dialog, thumbnail strip and filters through their suites |
| Phones | 320, 360, 390 and 430px on every page (the two passes of 2026-10-09) |

Two cautions on the numbers. The development server sends files uncompressed over HTTP/1.1, so absolute timings there overstate production (which serves Brotli over HTTP/2 with a 90 ms TTFB); the proportions hold. And the local database is a copy restored from Ronnie's backups, so image hosts and counts reflect production as of 2026-10-14, not today.

---

## 2. What is strong, and should not be touched

A thesis about what is missing owes the reader an honest account of what is not. The following were checked on every page and hold without exception:

- **Structure.** One `h1`, one `main`, `header`, `nav` and `footer` landmarks, a working skip link as the first Tab stop, no heading level skipped, `lang="en"` set. Breadcrumbs on all 49 pages that are inside a section.
- **Type.** Two families only (Source Serif 4 for headings and numbers, Source Sans 3 for everything else), self-hosted, preloaded. The size table is tight: 16px for small text, 20px body, 22 lead, 24 h3, 40 h2, 60 h1, with a handful of fluid in-betweens. Nothing under 16px anywhere. Paragraph measure never exceeds 68 characters (the single 111-character reading was a `<p>` that holds only a centred button).
- **Rhythm.** The gaps between adjacent blocks in `main` across all pages are 64px (159 times), 32px (94), 20px (25), 12px (23) and 48px (4). That is a system, and it is being kept.
- **Targets.** Every control at or above 44px; the two "28px links" the sweep first reported on `/videos/` were a false positive of the sweep, not of the page.
- **Resilience.** With JavaScript off, every page keeps its words, its menu (30 links in the drawer) and its buttons. The only two pages under 60 words are Privacy and Terms, which are placeholders in any mode.
- **Keyboard.** Tab order on the homepage, `/adopt/` and a dog page: skip link, call, Foster, logo, Adopt, "Adopt pages" button, each with a visible ring.
- **Meta.** No duplicate titles or descriptions. Canonical and Open Graph on every page. One title too long (the homepage, 73 characters) and a few descriptions outside the 50 to 160 range, listed in section 5.
- **Mobile.** No sideways overflow at any width from 320 up; the deliberate exceptions are documented in `Design.md` section 5.

None of the recommendations below asks any of this to change.

---

## 3. Finding one: the photographs are still on borrowed ground

This is the largest finding and the one that cannot wait for launch, because it is a dependency, not a polish.

**What was measured.** `/team/` weighs **10.9 MB in images alone** at 1440px: 27 portraits served from `new.pomdr.org` (24) and `www.peaceofminddogrescue.org` (3) at their original dimensions, the largest 1.3 MB (Briana Ryan), 1.1 MB (Shanel Parolini, a PNG), 918 KB (Dr. Erin Trannel). Each person page carries 5.4 MB for the same reason (the large portrait plus the colleagues' grid). `/adopted/` weighs **8.6 MB**: 66 of 67 thumbnails come from `www.peaceofminddogrescue.org/images/dogs/` at 600×450 for a 175px slot. The local copy of `animals_photos` shows **3,984 of 11,313 photographs still have no `image_key`**: 3,596 point at the old site and 388 at the WordPress test site.

**Why it matters.** Three reasons, in order of severity. First, both hosts are scheduled to disappear: `www.peaceofminddogrescue.org` is the site being replaced, and `new.pomdr.org` is a test site. The day either is switched off, every affected photograph on the public site becomes a broken image, with no error in any log. Second, `image_url()` passes a full address through untouched, so none of these pictures receives Cloudflare's resizing and format conversion: a 175px thumbnail costs 300 KB instead of about 12. On a rural connection the Happy Tails wall takes over a minute. Third, the first impression of the people page, for a visitor who clicked "Our team" to see who they would be trusting with a dog, is a page that fills in slowly, face by face.

**What the design already does right.** Every one of these images is `loading="lazy"` with width and height set, so the page does not jump and the browser does not fetch what is far below the fold. That is why the measured LCP on these pages is still under 100 ms. The cost is in bytes and in fragility, not in layout shift.

**Recommendation (Tier 0, Ronnie).** Run the photo import for the remaining 3,984 animal photographs and the team portrait import (the code is on his unmerged `cursor/team-portraits-r2` branch, and the `team_portrait_*` helpers are already in `db.php`), so every image has an `image_key` and is served through `images.pomdr.org` with `width=` and `format=auto`. This is POMDR-6's territory and the single largest performance and resilience gain available. Until it is done, a **Tier 0 safety net we can add ourselves**: `team_portrait_url()` and the thumbnail partial could request a resized copy through the Cloudflare image path for any address on the two old hosts, since `cdn-cgi/image` accepts a full URL. I have not made that change because it depends on which origins the Cloudflare zone allows, which only Ronnie can confirm.

---

## 4. Finding two: the first screen on a slow phone

**What was measured.** A performance trace of the homepage at 390px, 3x density, Slow 4G and a 4x slower CPU (a fair stand-in for an older phone on a rural carrier): **LCP 4,610 ms**. The breakdown: time to first byte 32 ms, load delay 662 ms, **load duration 3,884 ms (84 percent)**, render delay 32 ms. The LCP element is the hero photograph, `home-hero-adopt.jpg`, 1280×960, 268 KB. Two more things showed in the same trace: `site.css` (157 KB uncompressed on the dev server, 38 KB Brotli in production) was the one render-blocking request, worth about 576 ms of first paint under these conditions; and the three photographs of slides 2 to 4 downloaded during the first seconds even though no visitor had asked for them, because once the script stacks the slides in one grid cell, all four count as "in the viewport" for lazy loading.

**Why the 640 and 960 copies did not help here.** A 390px phone at 3x asks for 1,170 device pixels for a 100vw image, so the browser correctly chooses the 1280 original. The copies help 1x and 2x phones; at 3x the full file is the right file by the rules, even though the eye could not tell a 960 copy from it.

**Recommendations (Tier 1, ours unless noted).**

1. **Let the visible slide win.** Keep `fetchpriority="high"` on slide one, and have `home.js` fill slides 2 to 4 from a `data-src` the first time the show advances (seven seconds in). Without the script, slides 2 to 4 are `hidden` and never load anyway. Saves about 450 KB on every phone load and takes nothing from the experience.
2. **Serve the hero in a modern format.** The trace estimates 63 KB saved on slide one alone from WebP or AVIF, 663 KB across the page's images. This needs a sibling to `bin/prepare-page-image.php` that writes a `.webp` beside each `.jpg`, with `<picture>` choosing it. That is Ronnie's script, so it goes to him as a proposal (it was already item 7 of the October standards review).
3. **A 3x-honest `sizes`.** Declare `sizes="(max-width: 47.9375rem) 390px, 100vw"` on the hero, so a phone asks for at most 1,170 device pixels and a 960 copy becomes eligible at 2.5x and below. It is a small, defensible lie about the slot width that saves 120 KB on most Android phones. Optional, and I would do it only if (1) and (2) are not enough.
4. **Inline the critical rules.** The stylesheet is one 38 KB file and the page cannot paint until it arrives. Inlining the roughly 6 KB that style the strip, the navigation card and the opening band (and letting the rest load after) would cut first paint by about half a second on a slow phone. It is a build-time change to `header.php` and `build.php`, so it is a proposal for Ronnie rather than a quiet edit; the gain is real but the hourly build is his.

Everything else in the delivery path is already right: fonts preloaded and subset (48 KB together), scripts deferred, Brotli and HTTP/2 at the edge, a 90 ms TTFB.

---

## 5. Finding three: the words

The site's voice is in good shape. Across 71 pages the voice rules found: **no em dashes**, no "Meet [Name]" openers, no page that closes on a question, one British spelling (inside a verbatim testimonial, which stays as the person wrote it), and three "fur baby" or "pet parent" (also inside quotes, also verbatim). The reading grade of the program pages sits between 4.6 (the intake and volunteer applications) and about 9, which is where it should be for an older general reader.

What needs attention:

- **"he or she" twice**, on `/about/` and `/fostering/`. Brand.md asks for "they" or "your dog". Two sentences to rewrite (Tier 1, ours).
- **"senior citizen" twice**, on `/about/` and `/resources/`. POMDR's own usage is "senior people" or "seniors"; the phrase reads as dated to the people it describes. (Tier 1, ours; the Resources instance is in a quoted organisation name and may have to stay.)
- **Descriptions.** Too short to be useful in a search result on `/foster-needs/` (38 characters), `/volunteer/` (46), `/videos/` (49), `/privacy/` (14) and `/terms/` (16); too long on the dog pages (189 to 257), where the description is the first 155 characters of the story plus the badge text. Trim the dog description to the first sentence. The homepage title is 73 characters and will be cut in a tab; 60 is the ceiling. (Tier 1, ours.)
- **The person pages read at grade 13.** The bios are verbatim from the live site and that is the right decision for accuracy, but it means the "Our team" section is the hardest reading on the site. A one-line summary at the top of each card ("Executive Director. Co-founded POMDR in 2009.") would give a scanning reader the gist without editing anyone's words. (Tier 2, ours, with POMDR supplying or approving the lines.)
- **Thin pages.** `/events/` has 91 words and one event; `/courtesy-listings/` 100 words and, today, no dogs; `/mailing-list/` 105. Each is honest about being thin ("Nothing is scheduled at the moment"), which is better than padding. But Events is a top-level menu word, and a visitor who opens it and finds one item learns that the site is not kept. The fix is editorial, not design: the News items the live site holds (13 of them) belong here, and POMDR-11 already asks for the Culture and News words. (Tier 0 for the words, POMDR; Tier 1 for the page, ours.)
- **`/testimonials/` at 8,883 words** is organised (grouped, with a jump list, 152 voices), and 117 of its sentences run past 28 words because people write that way when they are grateful. It is the one page where I would resist editing anything; it is the proof.
- **Repeated sentences.** "You can stop part way through and come back to it" appears on seven form pages, "Any trouble at all, call (831) 718-9122" on five. That is deliberate reassurance in the one place a visitor is anxious; it reads as care, not as boilerplate, and I would keep it.

---

## 6. Finding four: the hygiene list

Small, cheap, and each one a thing a launch checklist would catch.

1. **`/about/` links to `/about/culture/`, which does not exist.** The only broken internal link on the site, and it sits on the page that introduces the organisation. Remove the link until the Culture words arrive, or point it at the mission section. (Tier 0, ours, five minutes.)
2. **No `sitemap.xml`.** Production returns 404 for it, and `robots.txt` is Cloudflare's default content-signals text with no `Sitemap:` line. POMDR-17 already covers this; the sitemap is one loop in `build.php` over the addresses it already renders. (Tier 0, Ronnie.)
3. **Dead external links on `/resources/`.** Of 104 external addresses, 16 did not answer 200. Four are plain 404s on sites that still exist (The Magic Bullet Fund's apply page, Brown Dog Foundation's "ask for help", SAGE C4A's apply page, and Carmel Magazine's article); two hosts no longer resolve (`info.iamfine.com`, `legacyforyourpet.com`); two still use `http://` and now redirect; the rest are 403s from sites that refuse automated requests and are probably fine in a browser. A visitor following "Financial assistance" to a 404 at the worst moment of her week is the failure this page exists to prevent. (Tier 0, ours for the links we can verify by hand, with POMDR confirming replacements.)
4. **`Cache-Control: max-age=0, must-revalidate` on every asset.** Cloudflare Pages' default. Every page view re-asks for `site.css`, the fonts and the logo (a 304 each, but a round trip each on a slow connection). The assets already carry a content hash in their address (`?v=9f31e3d4`), so they could be `immutable` for a year with no risk. That is a `_headers` file in the output the build writes, which is Ronnie's. (Tier 1, Ronnie; the performance trace counted 1.8 MB of "wasted" repeat-visit bytes on the homepage alone.)
5. **The homepage tile photographs** (`home-tile-adopt/donate/volunteer.jpg`) are 900px for a 700px slot and could lose 300 KB between them with a 700 copy and modern format. (Tier 2, ours, same script.)
6. **The logo** is still a 600px PNG shown at 253px, 27 KB, soft on a 2x screen. POMDR-23 asks POMDR for the vector. (Tier 0, POMDR.)
7. **The Spanish application page** declares `lang="es"` on its content but `<html lang="en">`. A screen reader will announce the page in English. The header should take a per-page language. (Tier 1, ours; a three-line change in `header.php`.)
8. **Umami analytics** now loads on every public page (Ronnie, POMDR-42, 4.7 KB, deferred, cookieless). It is harmless to performance and does not read anything a visitor typed. But it is a third-party request before the visitor acts, which `Brand.md` lists under "do not", and CLAUDE.md asks for approval before tracking. This is a decision for Andrew, not a defect: either accept it and amend the two documents to say "Umami only, cookieless", or ask Ronnie to gate it. (Tier 0, Andrew.)

---

## 7. Interaction and accessibility: what remains owed

Measured and holding: the drawer (a native popover; Escape, tap-outside and the button all close it, focus returns), the slideshow (pausable, eight seconds a slide, held under the pointer and in a hidden tab, `inert` on the slides not showing), the team dialog (23-check suite), the adopt filters, the thumbnail strip with arrow keys, and reduced motion throughout.

Still owed, and worth doing before launch because the audience makes them more likely than usual:

- **A screen-reader walk** of the five journeys: find a dog and start the questionnaire; find help keeping a dog; find the phone number; read a person's story; donate. The structure is right (landmarks, labels, `aria-roledescription` on the carousel, live region off until the visitor acts), but structure that is right and a journey that is pleasant are different measurements, and only a walk with VoiceOver or NVDA tells the second. (Tier 0, Andrew or a volunteer, one hour.)
- **Zoom at 200 and 400 percent.** The widths tested (320 to 430) are what a 1280px laptop becomes at 300 to 400 percent zoom, so the layouts are known to hold. What has not been checked is whether text at 400 percent still fits the fixed-height things (the navigation card's strip, the dialog's close bar). (Tier 1, ours, half an hour.)
- **The LGL forms.** Eight pages embed Little Green Light forms in an iframe (titled, lazy, with an "open it in its own page" fallback). Two things are outside our control and worth knowing: LGL showed a reCAPTCHA image challenge on the sponsor form in September, which shuts out a share of older visitors; and the iframe cannot pre-fill the dog's name, so the form asks for it again. The adoption form is pinned for a rebuild on Shelterluv; the reCAPTCHA question belongs with POMDR's LGL account. (Tier 1, POMDR.)
- **Testing with people** (POMDR-25). Everything in this document was measured by machine or by me. The menu tree test and the hallway test of the program pages with five to eight older visitors are the one audit no script replaces, and they should happen before the DNS moves, because wording is cheap to change the week before launch and expensive the week after.

---

## 8. Structure and code

- **One stylesheet, 157 KB raw, 38 KB over the wire.** The indicative coverage run (all 71 pages at two widths) could not be trusted (Chrome reports coverage per final navigation unless reset is handled per page), so I make no claim about unused rules. What can be said: the file is organised by component, each phone adjustment sits after the component it adjusts, and this week's review removed the one duplicated block. A real pruning pass would be a Tier 2 task with a proper per-page coverage harness.
- **Four scripts, all small, all deferred, each with a no-script path.** `nav.js` 5.4 KB, `home.js` 24 KB, `adopt.js` 10 KB, `team.js` 2 KB (raw). `home.js` is the one worth a second look: it holds the slideshow, the rise-in and the paw trail, and the paw trail's SVG work is the largest part. It is only loaded on the homepage, so the cost is contained.
- **No inline styles, no data URIs, no `<style>` blocks** on any page: README rules 14 and 15 hold everywhere.
- **DOM sizes are modest.** The largest page, `/team/`, is 910 nodes with its 27 dialogs; `/adopt/` with 83 dogs is under 1,500. The dialogs add about 30 KB of HTML to `/team/` and `/about/`; the alternative (fetching a bio on demand) would need a request-time endpoint the static site cannot have, so this is the right trade.
- **A fragility worth naming.** The dog card lifts its status pills onto the photograph by positioning them against the card. This works only while `.pet-card-media` is the card's first child and `.pet-card-body` has no `position` of its own; the comment in `pet-card.php` now says so. A future maintainer who adds a positioned wrapper will see the pills drop into the text on desktop. Acceptable, documented, and the phone layout it enables is worth it.
- **The `-640`/`-960` convention** for page-opening photographs is documented in `Brand.md` and `Design.md` section 5. It is a manual step; a one-line addition to `prepare-page-image.php` that writes the two copies automatically would remove the chance of forgetting. (Tier 2, proposal to Ronnie.)

---

## 9. Experience: the d.school reading

Setting the measurements aside and walking the site as the four visitors in the IA plan would:

**The adopter** finds dogs in one tap from anywhere (Adopt is the first word, the strip repeats it), can filter by size, age and the words in a story, opens a dog and finds a photo, the facts in one glance, the story, and the ask twice. This journey is finished. Two refinements: on `/adopt/` the sort and filters take the whole first screen on a phone before the first dog appears (the filters could start folded, with the search box alone showing); and a dog's page could show the dog's own YouTube video where one exists (the live site had one per dog; the field is in the data).

**The owner in crisis** finds Get Help in the menu, a phone number in the strip, in the band and in the footer, and three plain cards. The pages read at grade 5 to 7. This journey is finished. One refinement: the Perpetual Care and Placing Your Dog pages each end with a deep band and a button, but neither says what happens in the first 24 hours after the form, and that is the question a frightened person has. One paragraph each, from staff.

**The donor** gets the Donate button everywhere and a Ways to Give hub, but every option still lands on the same Little Green Light form (POMDR-9), so "Give in memory" and "Give monthly" are promises the form does not yet keep. The numbers band tells her what the money does; the impact report links tell her where it went. Finished when POMDR-9 is.

**The community member** gets Events with one item, no News, and a Videos page that now plays in place. This is the thinnest journey and the one most visible to the people who already love POMDR. The content exists (13 live news items, the Culture values); it is waiting on words, not design.

**What a visitor cannot do anywhere:** search the site (there is no search box, and for 38 pages a good menu is better than a bad search, so this is correct), print a dog's page cleanly (three print rules exist; nobody has printed one; Tier 2, half an hour), or find the annual reports from the footer in one tap (they are under About; the footer line "Our annual reports and 990 filings" does link there, which is right).

---

## 10. The plan, ranked

Owner in brackets. "Ours" means the public layer, with no change to the design direction.

**Tier 0: before the DNS moves**

1. Import the 3,984 remaining dog photographs and the 27 portraits into R2 so no public image depends on the two old hosts [Ronnie, POMDR-6 and the portrait branch]. Until then, consider resizing old-host images through `cdn-cgi/image` if the zone allows it [Ronnie to confirm, ours to wire].
2. Run the roster SQL on production, or make the edits in the admin [Ronnie, POMDR-32]. The published `/team/` has shown last year's staff for nine days.
3. Replace the Privacy and Terms placeholders with POMDR's real text [POMDR].
4. Remove or redirect the `/about/culture/` link [ours].
5. Fix or remove the six dead links on Resources; re-verify the 403s by hand [ours, POMDR for replacements].
6. `sitemap.xml` from the build's own address list, and a `Sitemap:` line in `robots.txt` [Ronnie, POMDR-17].
7. Decide on Umami and amend Brand.md and CLAUDE.md to match [Andrew].
8. The Culture and News words, so Events stops looking abandoned [POMDR, POMDR-11].
9. The vector logo [POMDR, POMDR-23].
10. A one-hour screen-reader walk of the five journeys [Andrew or a volunteer].

**Tier 1: the first fortnight after**

11. Slides 2 to 4 load on first advance, not on page load [ours].
12. WebP/AVIF beside each page JPEG, written by the prepare script, chosen by `<picture>` [proposal to Ronnie].
13. Critical CSS inlined; the rest deferred [proposal to Ronnie].
14. `immutable` caching for hashed assets via a `_headers` file in the output [Ronnie].
15. The two "he or she", the "senior citizen", the five short and six long descriptions, the 73-character homepage title [ours].
16. `<html lang>` per page, so the Spanish page is Spanish [ours].
17. Zoom at 200 and 400 percent on the chrome and the dialog [ours].
18. Adopt filters folded by default on phones [ours].
19. The reCAPTCHA question to LGL; the first-24-hours paragraph on the two hard-moment pages [POMDR].

**Tier 2: when there is room**

20. One-line summaries on the team cards [ours, POMDR approves].
21. A per-dog video on the dog page where Shelterluv has one [ours].
22. The 700px tile copies, the print check of five pages, and a real CSS coverage pass [ours].
23. Testing with people: the menu tree test and the program-page hallway test [POMDR-25].

---

## 11. What this thesis argues against

Three things a fresh pair of eyes might propose, and why the evidence says no:

- **A redesign of the homepage hero for speed.** The hero is slow on a throttled 3x phone because it is a photograph, and the photograph is the point. The fixes in section 4 cut most of the cost without touching what the visitor sees. Replacing it with a lighter composition would save a second and lose the one thing every strong nonprofit site in this year's galleries has in common: a real face, large.
- **A JavaScript framework, a build step, or a CSS pipeline** to prune the stylesheet. The whole site is 38 KB of CSS and four small scripts; a framework would cost more than it saves on day one and would end Ronnie's "escape hatch" of running the site on any PHP host.
- **Shortening the testimonials or the bios.** They read hard because they are real. The site's job is to carry them, and the jump lists and the folded quotes already make them scannable.

The site is ready for people. What stands between it and launch is almost entirely a matter of moving pictures to where they will survive, finishing a few sentences, and letting a handful of real visitors tell us what the measurements cannot.

Andrew Z.

---

### Appendix: the numbers in one place

| Measure | Result |
|---|---|
| Pages audited | 71 (38 sections, 27 people, 6 dogs) |
| Broken internal links | 1 (`/about/culture/`) |
| External links / not answering 200 | 104 / 16 (6 confirmed dead or 404, 2 http redirects, 8 bot-blocking 403s) |
| Pages with one `h1`, skip link, one `main`, canonical, OG | 71 / 71 |
| Heading level skips | 0 |
| Text under 16px | 0 instances |
| Controls under 44px | 0 (after false positives) |
| Paragraph measure over 68ch | 0 (one centred-button wrapper excepted) |
| Section gaps | 64 / 32 / 20 / 12 / 48px, in that frequency |
| Fonts | Source Serif 4, Source Sans 3, 48 KB preloaded |
| Pages fully usable without JS | 71 / 71 |
| Heaviest pages (1440px) | `/team/` 11.2 MB, `/adopted/` 8.9 MB, person pages 5.6 MB |
| Dog photographs not yet on R2 | 3,984 of 11,313 (3,596 old site, 388 WordPress) |
| Staff portraits not yet on R2 | 27 of 27 |
| Homepage LCP, Slow 4G, 3x, 4x CPU | 4,610 ms (84% image download) |
| Production | HTTP/2, Brotli, TTFB 90 ms, `max-age=0` on assets, no sitemap |
| `site.css` | 157 KB raw, 38 KB Brotli |
| Scripts | 4, deferred, 42 KB raw total |
| Third parties on a page | Umami (every page), LGL (8 form pages), YouTube (on press only) |
| Voice rule hits | em dash 0, "he or she" 2, "senior citizen" 2, closing questions 0 |
| Reading grade, program pages | 4.6 to ~9; person pages ~13 (verbatim bios) |
| Lighthouse accessibility | 100 on every page audited |
