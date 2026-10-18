# Building on Ronnie's momentum: eight upgrades on his side, each serving the public site, and what the public site gives back

**Proposal, written 2026-10-17 for Andrew Z. to bring to Ronnie.** Companion to the meeting agenda (POMDR-45) and to the research synthesis (`RESEARCH-SENIORS-ADOPTION-AND-THE-WEB-2026-10-17.md`). Every item below is written as an addition to what Ronnie has built this month, follows his conventions (`animals_has_column()` guards, prepared statements in `db.php`, `e()` on output, one folder per admin page, `bin/check.php`), and names what the design side gives in return. Nothing here is built. Where there is a code sketch it is a sketch for him to accept, rewrite or refuse.

---

## 1. What October gave us, read from the design side

Ronnie's thirty-odd commits since 2026-10-09 changed the shape of the Hub: dogs now exist in the database **before** Shelterluv has them (potential intakes, POMDR-27); every dog has a **process status**, a **behavior flag**, a **medical status** and four **flags** (028 to 031, all applied on production); staff can post a dog to the foster page with a switch (`is_foster_posted`); Foster LT and ST lists and a Prospective Adoptions board link dogs to LGL people; the edit page is tabbed and saves only its own fields, with a who-has-this-open notice (POMDR-40); Slack hears about new intakes and exam-needed dogs; and the All board shows the whole pipeline in four columns.

Three consequences for the public site, in his favour and ours:

- **The public site now reads his workflow directly.** `/foster-needs/` lists `get_public_foster_candidates()`, so a flag a staff member sets at 9:00 is on the public page at the 10:17 build. Seven dogs are live through it today. That is exactly the link the IA plan wanted and could not have in September.
- **The words and pictures the public page needs are now first written in his admin.** Intake form, edit page tabs, the Timeline we proposed yesterday. The quality of the adopt page is decided on his screens.
- **His Slack alerts and the All board are the staff's attention.** Anything the public site needs from staff (a photo, a story, a sponsor name) is best asked for there, not in a document.

So the harmonious move is not a list of things we want him to do for us. It is: his systems carry a few more public-facing facts, and in return the public site shows the fruit of his workflow sooner and with less staff effort, and sends him measurements he does not have.

## 2. The eight upgrades

Ranked by what a visitor feels, then by how little of his structure each touches.

### 2.1 A public-readiness rule in one place (POMDR-28, POMDR-13)
**Why:** a potential intake with no photograph and a working name can reach `/foster-needs/` today. The research is unambiguous that a listing without a good photograph waits (Lampe and Witte), and Andrew's answer on POMDR-28 is to wait for the photo and write-up.
**What:** one private helper in `includes/db.php` that both `get_public_foster_candidates()` and `public_profile_where()` use, so the rule cannot drift between the list and the page:
```php
// A dog may appear on the public site only when there is something to
// show: at least one photograph and a story. A dog staff have posted
// for fostering before Shelterluv has it still waits for both.
function public_ready_where()
{
    return "a.description <> ''
            AND EXISTS (SELECT 1 FROM animals_photos p
                         WHERE p.pet_id = a.id AND p.image_key IS NOT NULL)";
}
```
appended to the two WHERE clauses with `AND`. (If the Timeline lands later, `AND p.is_public = 1` joins it.)
**His side:** `db.php`, ten lines. **Ours:** nothing; the pages already read those functions. **Gives back:** the All board could show a small "not yet public: needs photo / story" chip from the same helper, so staff see why a dog is not on the site without asking.

### 2.2 A story prompt on the intake form
**Why:** adopters trust the words of the person who knows the dog over anything else (Weiss et al.), and the public dog page's story is the first thing after the photo. Today the intake form has an "arrival note" but no public story field, so the story is written later, or not at all.
**What:** on `admin/animal/new/` and the edit page's record tab, the existing `description` textarea with a two-line prompt as its `placeholder`, in staff's own voice: *"Two or three sentences a stranger would read first: who this dog is at home, what they love, what they need. Skip breed and weight; the page shows those."* And a note under it: *"This is the public story. It goes on the website as written."* Optionally a counter (the page reads best at 60 to 180 words).
**His side:** the form markup, no schema. **Ours:** the dog page already renders `description` through `pet_story_paragraphs()`.

### 2.3 Slack asks for what the public page is missing
**Why:** he has just built the channel staff actually watch. The two things the public site most often lacks are a photograph and a story, and both are cheapest to ask for the day the dog arrives.
**What:** beside `slack_notify_new_intake()` and `slack_notify_exam_needed()`, a daily `bin/nudge-public-gaps.php` from cron (or a line in the existing daily audit) that posts one message to a `#website` channel: dogs posted for fostering or marked available in the last 7 days with no photograph, no story, or a story under 40 words, each with its admin link. Nothing more than that; one message a day, silent when there is nothing to say.
**His side:** `includes/slack.php` plus one cron line, same pattern as POMDR-43. **Ours:** nothing. **Gives back:** the single biggest lever on adoption speed (photo quality) gets a daily reminder with no new screen to learn.

### 2.4 Sitemap, caching and redirects in the build (POMDR-17)
**Why:** the build is the only place the full address list exists; a new site with few inbound links is the one case Google says needs a sitemap; the old `dog.php?id=` addresses are what Google ranks today and what 3,596 adopters have bookmarked (`AtLaunch.md`); and every asset already carries a content hash but is served `max-age=0`.
**What:** three files written by `build/build.php` into the output, next to `404.html`, through the same `write_if_changed()`:
```php
// sitemap.xml, from the list this run rendered. lastmod is the dog's or
// page's updated_at where known, else today, so it changes only when the
// page does (the output must stay byte-stable, README rule 11).
$xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n"
     . '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
foreach ($urls as $u) {
    if ($u === '/404/') { continue; }
    $xml .= "  <url><loc>" . e($config['site_url'] . $u) . "</loc></url>\n";
}
$xml .= "</urlset>\n";
record(write_if_changed($output_root, 'sitemap.xml', $xml, $options['dry_run']), 'sitemap.xml', $written, $added, $changed);

// robots.txt: the one line Cloudflare's default lacks.
record(write_if_changed($output_root, 'robots.txt', "User-agent: *\nAllow: /\nSitemap: " . $config['site_url'] . "/sitemap.xml\n", $options['dry_run']), 'robots.txt', ...);

// _headers: a year for anything with a hash in its address.
record(write_if_changed($output_root, '_headers', "/assets/*\n  Cache-Control: public, max-age=31536000, immutable\n", $options['dry_run']), '_headers', ...);
```
`_redirects` from the AtLaunch map is the same shape, one line per old address, or stays as Cloudflare Bulk Redirects in the dashboard as `AtLaunch.md` plans; either is fine, and his is the one that survives a build change. The 80 dog pages' `lastmod` can come from `animals.updated_at`, which the build already has in hand.
**His side:** `build/build.php`, about thirty lines; precedent is the `/team/` loop and the `404.html` write we added. We will write it as an addition if he prefers to review. **Gives back:** Search Console data, which neither side has today, and faster repeat visits for the whole site.

### 2.5 A WebP beside every page JPEG (`bin/prepare-page-image.php`)
**Why:** the performance trace estimates 663 KB saved on the homepage's images alone; a phone on Slow 4G waits 3.9 s for the hero.
**What:** after `imagejpeg()` in `prepare_image()`, if `function_exists('imagewebp')`, also write `<destination>.webp` at the same quality; and let `bin/prepare-page-image.php` accept a `--sizes=640,960` argument so the small copies we now make by hand are made in the same run. The public layer then uses `<picture>` with the WebP first. R2 already serves `format=auto` for database photographs, so this only affects the hand-placed page images.
**His side:** `includes/images.php` and the bin script, additive. **Ours:** `page-intro.php`, the homepage, and the kit's photo partials.

### 2.6 Sponsor names reach the dog page (data, not code)
**Why:** the sponsor page promises "sponsors are thanked by name on the dog's own page" and the page already prints `sponsored_by`; it is empty for every dog today because nothing writes it.
**What:** a one-line field on the edit page's Adoption or PR tab, or a sponsor connection role in Connections (which keeps history and the LGL link), surfaced as `sponsored_by`. His call which; the public page needs no change.

### 2.7 Event photographs (POMDR-10) through the same pipeline
**Why:** the council read the Events page as thin; a photo per event and a small "Recently" strip of past events is what makes a seventeen-year-old organisation look alive.
**What:** an `image_key` on `events` written through `prepare_image()` and synced like a dog's photograph, with the admin event screen gaining an upload. **Ours:** the event card and the strip.

### 2.8 Measurements flowing back to him
**Why:** harmony runs both ways. The public site now has a thousand-visit stress harness, a large-text sweep, a link checker and a per-page audit; none of it reaches his repository.
**What:** the suites move under `tests/` in his repo if he wants them (Playwright only, no build step, design-only), with a one-page `RELEASE-CHECKLIST.md`; and Umami's public-site data (which pages, which devices, which dogs) is shared with us monthly, since it answers the "do older visitors use phones here" question with POMDR's own numbers rather than Pew's.

## 3. What the design side commits to in return

- Every public page keeps reading his functions and never queries the database directly; the `get_public_*` twins stay the only door.
- Nothing admin-side, nothing in `build/`, `bin/` or `db/` changes without being proposed here first; where we add (2.4, 2.5), it is additive, guarded, and listed in `Design.md` section 10.
- The public page shows the fruit of his workflow within the hour it is set: foster switches, statuses the field registry allows, sponsor names, event photographs.
- We carry the staff-facing words (the story prompt, the photo standard, the Slack messages) in POMDR's voice, so his screens read like the public site.
- We report what the public site measures back to him, in the tracker, with the numbers.

## 4. Order

1. 2.1 (the readiness rule) and 2.6 (sponsor names): small, and the first is a welfare rule. This week.
2. 2.4 (build files) before the DNS moves. We will draft it as an addition on request.
3. 2.2 and 2.3 (story prompt, Slack nudge): together, since they ask staff for the same two things.
4. 2.5 and 2.7 (WebP, event photos): with the photo work in POMDR-6 and POMDR-44.
5. 2.8 (tests and data): whenever he likes.

## 5. Not asked for

A framework, a build step, a CMS, a second stylesheet, or any change to the escape hatch of running the site on a plain PHP host. The public site remains forty pages of PHP and one stylesheet, and the point of this document is that it stays that way while his back office grows.
