# A photo and video timeline for every dog

**Proposal, written 2026-10-17 for Andrew Z. to take to Ronnie and staff.** A design to argue with, in the spirit of Ronnie's own proposal documents (`Shelterluv.md`, `Clinic.md`, `Foster.md`, `Connections.md`): it says what would be built, how it fits the Hub, what is unverified, and the order of work. Nothing here is built. Tracker: the discussion ticket filed today (see section 7).

**This is an internal system, part of POMDR Hub, the back office Ronnie is building.** It lives in the admin at `admin.pomdr.org`, behind Cloudflare Access, for staff, the clinic and (later, through the foster site) fosters. It is not a public-facing area of the website. The public dog page is unchanged by it; the only thing a visitor could ever see is an item a staff member has deliberately switched to public, and that is an optional last step (section 3.4), not the point. The point is that the team's pictures, clips and the words around them are organised in one robust place, with the same storage, sync, backup, audit and access controls the rest of the Hub already has.

---

## 1. The problem, from each seat

**Marketing (Andrew's team).** A dog's photographs are spread across staff phones, foster phones, a Dropbox or two, the clinic, Shelterluv and the old website. Telling the story of a dog from matted arrival to the couch at home means asking three people and losing a day. The newsletter, the Herald ad, the social post and the website each start from scratch.

**Operations (Caitlin, Nisha, the fosters).** The same picture is needed for the intake record, the foster handoff and the adoption write-up, and nobody knows which copy is current. Notes that travel with a picture ("heavily matted on arrival", "limping on the left hind") are typed into a text field or lost.

**The public site.** The dog page already shows a gallery and a story, but the gallery is flat: no dates, no stages, no words from the people who were there. The richest material POMDR has, the before-and-after, is not on the page because it is not in the database.

**How might we** let anyone who photographs a dog drop the picture somewhere ordinary and have it land on the right dog, in order, with their words, so that the story writes itself?

## 2. What exists, and what the proposal adds

Everything below is an extension of what Ronnie has already built, and it inherits the Hub's robustness rather than adding a parallel system: pictures are written to the admin box first and copied to R2 (two copies, `README.md` section 8); the database is dumped nightly; `bin/audit-images.php` checks daily that database, disk and bucket agree; the admin host is never published and sits behind Cloudflare Access; migrations are guarded so code can land before its column exists; every query is a prepared statement in `includes/db.php`; and nothing is ever written back to Shelterluv, LGL or Instinct. A media timeline built on those rails is as safe as the animal record itself. The verified state of the Hub (from the repository, 2026-10-17):

| Exists today | Where | What it gives us |
|---|---|---|
| One table per dog's photographs: `pet_id`, `image_key` (R2), `content_hash`, `synced_at`, `alt_text`, `sort_order`, `source_url`, `wp_media_id` | `db/schema.sql`, `animals_photos` | The storage, de-duplication and ordering of pictures |
| Write locally first, then `bin/sync-images.php` copies to R2 every five minutes; `bin/audit-images.php` daily | `includes/images.php`, `TECH.md` section 6 | The delivery pipeline; nothing new needs inventing |
| `prepare_image()` resizes, strips metadata, fixes EXIF rotation | `includes/images.php` | The one place a picture is processed |
| `save_pet_photo()`, `set_primary_photo()`, `get_pet_photos()` | `includes/db.php` | The queries a watcher would call |
| `animals.youtube_video`; Shelterluv's `Videos` list `[{VideoId, EmbedUrl, ThumbUrl, YoutubeUrl}]` | `Animals.md` sections 5.3, 5.4 | The start of the video side, already synced |
| People connected to a dog over time, with an append-only history (`adopter`, `foster`, `potential_adopter`…) | `Connections.md` | Where "who adopted Buddy, and when" lives |
| Potential intakes entered before Shelterluv (POMDR-27), process status, medical status, flags | October's admin work | The stages of a dog's life are now data |
| Staff-only vs public is undecided per field | POMDR-13 | The same decision this feature needs |

**What the proposal adds, in one sentence:** a stage, a date, a caption and a credit on every picture; a drop folder that files pictures onto the right dog by itself; and dated words from adopters and fosters beside the pictures.

## 3. The design

### 3.1 The data: four columns, one table

Add to `animals_photos` (or, if Ronnie prefers to keep that table as it is, a sibling `animals_media` with the same key columns):

| Column | Type | Meaning |
|---|---|---|
| `stage` | varchar(30) | `shelter_intake`, `intake`, `clinic`, `foster`, `adoption_day`, `happy_tail`, `other`. A plain string, like `team_group`, so a new stage needs no ALTER |
| `taken_at` | datetime null | From EXIF `DateTimeOriginal` when present; else the file's date; else the upload date, flagged as a guess |
| `caption` | text null | The words of the person who took it, verbatim |
| `credit` | varchar(120) null | Who took it (a name, or "Foster: Rhonda"), for thanks and for rights |
| `kind` | varchar(10) | `photo` or `video` (a video row holds a YouTube or R2 key; no transcoding on the box) |
| `is_public` | tinyint | Staff switch: may this appear on the public page? Default off |

`taken_at` becomes the sort for the timeline; `sort_order` stays as the hand-picked order of the public gallery, so nothing already built changes behaviour.

### 3.2 Words at the moments that matter: `animals_words`

A small Hub-owned table for the things people say, separate from the picture captions because a quote often has no picture:

| Column | Meaning |
|---|---|
| `pet_id`, `person_connection_id` (nullable) | The dog, and the adopter or foster if known (Connections.md) |
| `asked` | The question, from a short fixed list staff maintain: "Tell us a couple of reasons you fell in love with Buddy", "What surprised you in the first week?", "What would you tell someone thinking about a senior dog?" |
| `answer` | Verbatim |
| `said_at`, `said_by_name`, `is_public` | When, who, and whether it may be shown |

This is the field that feeds the happy tail, the testimonial and the newsletter, without anyone pasting from email. The adoption-day questions are a staff script, three lines on the adoption checklist; the admin form is one textarea per question on the dog's record under the adopter connection.

### 3.3 Getting pictures in: the drop folder

The rule that makes it cheap for people: **nobody logs in to upload.** Staff and fosters put files where they already put them. The box reads from there.

- **The source.** One shared folder POMDR chooses (Dropbox is the most likely, since it has a Linux client and a free API; a Google Drive or iCloud shared album can be exported into it). The admin box mounts or syncs it to `/var/www/pomdr-drop/`.
- **The convention.** A folder per dog named as the dog's slug or working name (`buddy-2026/`), with an optional stage folder inside (`foster/`, `intake/`). Files inside need no special names. For people who will not make folders, a filename prefix works too: `buddy foster day 3.jpg`.
- **The watcher.** `bin/ingest-drop.php`, one more cron job beside `sync-images.php`: every five minutes it looks for new files, matches the folder or prefix to a dog (exact slug first, then name match on current and potential-intake dogs, newest first), reads EXIF for `taken_at`, calls `prepare_image()` and `save_pet_photo()` with the stage, and moves the original into a dated `done/` folder so nothing is processed twice. A `caption.txt` or a same-named `.txt` beside a picture becomes its caption.
- **The tray.** Anything it cannot match goes to `/admin/media/unsorted/`: a grid of thumbnails with a dog-search box beside each (the same search-and-pick pattern Connections.md uses for people). One drag or one pick files it. Nothing is ever deleted by the watcher.
- **Phones.** For the common case, a foster texting a photo: a Dropbox "file request" link per dog, which Dropbox already provides, posts straight into the dog's folder from any phone with no account. The admin could print that link on the foster handoff sheet.

### 3.4 Showing it: the admin is the product; the public strip is optional

- **Admin:** a **Timeline** tab on the dog's record (the tabbed edit page already exists, POMDR-40), newest first, grouped by stage, each item with its picture, date, caption, credit and the public switch; the adopter's words appear in the same stream dated to adoption day. Multi-select to set a stage or the public switch at once.
- **Public (optional, later, and only by choice):** nothing on the public site changes by default. Every item is staff-only unless a person switches it to public. If POMDR ever wants it, a dog with public items could gain a quiet "Buddy's story so far" strip under the story on the dog page, built on the kit's timeline pattern; that is a separate decision, after POMDR-13 settles what the public may see, and it is the last step in section 6, not the first.
- **Marketing's export:** a per-dog zip of the public items with a `story.txt` (captions and words in order), from the admin, so a newsletter or an ad starts from one download.

## 4. How it fits the larger vision

This is back-office infrastructure for the team, in the same family as the intake flow, the foster lists, the prospective-adoption board and the medical status Ronnie has built this month: one more thing the Hub holds about a dog, kept where staff work. Hub.md says the thing being built joins the systems that do not talk to each other with the pet as the central record. Pictures and the words of the people around a dog are the one kind of record none of the three masters (LGL, Shelterluv, Instinct) holds well, and the one the public and the donors care about most. This is Hub-owned data by Hub.md's own test: POMDR produces it, nobody else masters it, nothing is written back. It also gives the Dashboard a new real signal (dogs without a photo in the last 30 days), the Foster site (`Foster.md`) a place to post from, and the field registry (POMDR-13) its first concrete use of a per-item public switch.

## 5. What is unverified

- Whether staff and fosters will follow a folder convention; the tray exists because some will not.
- Which drop service POMDR actually uses today (the archive of the old site mentions none; staff know).
- EXIF survives on photos sent through Messages or WhatsApp less often than one would hope; many will fall back to the file date.
- Video: whether anything beyond YouTube links is wanted in the first version. Storing clips in R2 is possible but transcoding on the admin box is not something to take on lightly.
- Rights: a foster's photograph is theirs; the `credit` column records who, but a one-line consent in the foster agreement is a POMDR matter.

## 6. Order of work

1. **Decide with staff** (the discussion ticket): drop service, naming convention, the three adoption questions, stages that matter. Half an hour.
2. **Schema** (Ronnie): the columns in 3.1 and the table in 3.2, as migration 034, guarded with `animals_has_column()` like 027. Small.
3. **Admin Timeline tab and the words form** (Ronnie's admin; we can draft the layout to his admin pattern). A day.
4. **The watcher and the tray** (Ronnie's `bin/`; the matching rule and the folder convention from step 1). A day, plus a week of staff using it to find the edge cases.
5. **The export** (Ronnie's admin). Small.
6. **Only if POMDR wants it, and after POMDR-13:** the public "story so far" strip (ours, in the public layer, behind the `is_public` switch). Half a day.

Steps 2 to 5 are Ronnie's: this is his back office, and the design-only rule for our work in his repository holds. Step 6 is ours if it ever happens. Step 1 and the questions are POMDR's.

## 7. Tracker

Filed 2026-10-17 as a discussion task for Andrew: "Discuss: a photo and video timeline per dog (intake to adoption), with a drop-folder ingest and adoption-day words." The tracker's agent API cannot assign, so Andrew assigns it to himself and moves it to `todo`.
