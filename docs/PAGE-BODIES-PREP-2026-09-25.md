# Page bodies: prep for the next session

Written 2026-09-25 to start the next session from facts. **Done 2026-09-29:**
the review that followed is `docs/BODY-DESIGN-REVIEW-2026-09-29.md`, and every
page in the table below was rebuilt on the kit and pushed. Kept as the
before-picture.
The goal for next session: turn every section page body in New-Build from a
text block into a designed page, with layouts, text presentation,
storytelling, photo placement and orientation, and reasons to keep reading.
Start by reading this, then `New-Build/Design.md` sections 3, 4 and 4e, then
open `/patterns.php` on the dev server.

## 1. What each body is today (measured, 1440px)

"Body" means everything below the opening band. Sorted by word count.

| Page | Title | Body words | Headings | Body photos | Band photo | Form frame | Components used |
|---|---|---|---|---|---|---|---|
| /testimonials/ | What people say | 8874 | 0 | 0 | no |  | plain text |
| /adopt/ | Adoptable Dogs | 1529 | 84 | 83 | no |  | pet-card |
| /resources/ | Resources | 1478 | 8 | 0 | no |  | plain text |
| /fostering/ | Fostering | 1427 | 15 | 12 | yes |  | quote band story-list cta-strip |
| /videos/ | Videos | 1199 | 17 | 2 | no | yes | plain text |
| /perpetual-care-program/ | Perpetual Care | 1185 | 9 | 0 | yes |  | plain text |
| /jobs/ | Work with us | 1122 | 3 | 0 | yes |  | plain text |
| /volunteer/ | Volunteer | 808 | 13 | 7 | yes |  | plain text |
| /why/ | Why a senior dog | 703 | 9 | 0 | yes |  | card-grid qa quote band two-col cta-strip |
| /about/ | Our story | 659 | 41 | 28 | yes |  | team-grid |
| /media/ | In the media | 541 | 0 | 0 | yes |  | plain text |
| /donate/ | Donate | 489 | 17 | 16 | no |  | plain text |
| /surrender/ | Help placing your dog | 416 | 1 | 0 | yes |  | plain text |
| /helping-paw/ | Helping Paw | 414 | 5 | 0 | yes |  | plain text |
| /foster-needs/ | Dogs needing a foster home | 403 | 4 | 2 | no |  | pet-card |
| /clinic/ | Veterinary clinic | 390 | 12 | 1 | yes |  | card-grid band two-col cta-strip |
| /process/ | How adopting works | 275 | 0 | 0 | no |  | plain text |
| /bauer-center/ | The Bauer Center | 253 | 10 | 1 | yes |  | card-grid band two-col cta-strip |
| /benefit-shop/ | Benefit Shop | 227 | 6 | 0 | yes |  | plain text |
| /intake-questionnaire/ | Placing your dog with us | 196 | 5 | 0 | no | yes | card-grid band |
| /adoption-questionnaire/ | Adoption questionnaire | 190 | 5 | 0 | no | yes | steps band |
| /get-help/ | Get help | 167 | 6 | 0 | no |  | card-grid band cta-strip |
| /helping-paw-application/ | Apply for Helping Paw | 155 | 2 | 0 | no | yes | band |
| /volunteer-application/ | Volunteer application | 145 | 4 | 0 | no | yes | card-grid band |
| /helping-paw-application/es/ | Solicitar ayuda de Helping Paw | 136 | 2 | 0 | no | yes | band |
| /contact/ | Contact us | 129 | 10 | 0 | no |  | card-grid |
| /hospice/ | Hospice care | 122 | 5 | 5 | no |  | pet-card |
| /adopted/ | Happy tails | 110 | 0 | 66 | no |  | plain text |
| /events/ | What's happening | 94 | 5 | 0 | no |  | event-list |
| /mailing-list/ | Join our mailing list | 63 | 1 | 0 | no | yes | plain text |
| /sponsor-a-dog/ | Sponsor a dog | 61 | 1 | 0 | no | yes | plain text |
| /courtesy-listings/ | Other adoptable dogs | 59 | 0 | 0 | no |  | plain text |
| /terms/ | Terms of service | 10 | 0 | 0 | no |  | plain text |
| /privacy/ | Privacy policy | 9 | 0 | 0 | no |  | plain text |

What stands out:

- **/testimonials/ is 8,874 words with no headings.** It is the worst wall on
  the site, and the richest story material POMDR has.
- **Five long pages are pure prose:** Resources, Videos, Perpetual Care, Jobs
  and Volunteer (800 to 1,500 words each).
- **Most bodies have no photographs,** even where the band has one.
- **/process/ has no headings**, although it is a sequence. It is the obvious
  use for the kit's `steps`.
- **Terms and Privacy are placeholders** (about 10 words each). They need
  POMDR's real legal text, not design.

## 2. Material available

- **58 photos** already prepared in `New-Build/public/assets/img/`.
- **65 images** in the Divi theme (`divi-child-integration/assets/images/`,
  including `pages/`: benefit shop, Helping Paw, Perpetual Care, surrender,
  the Bauer house).
- **About 985 original uploads** in the local WordPress media library
  (`~/Local Sites/newpomdr-local/app/public/wp-content/uploads/`).
- Every photo has to go through `php bin/prepare-page-image.php` into
  `public/assets/img/`: 2000px for full width, 1200 in a column, 600 small,
  under 300 KB. Dog photos in database lists stay in R2 and are not placed by
  hand.
- **Real quotes to draw on:** the testimonials (8,874 words), 12 foster parents,
  6 adopters on /why/, and former-employee quotes owed on /jobs/ (July parity
  audit).
- **Real numbers:** 3,596 dogs rehomed (the 4,500+ figure is still
  unreconciled), over 1,500 volunteers, founded 2009, clinic opened November
  2019, Bauer house donated September 2011.

## 3. The method, per page (d.school / IDEO / Whipsaw)

For each page, before any layout:

1. **Who arrives, and in what state?** For example: an adult child whose parent
   has just gone into care, a retired couple browsing, a donor checking where
   the money goes.
2. **The one job,** as "How might we..." in one sentence.
3. **The story arc:** hook (a real dog or person), what we do, how it works,
   proof (quotes, numbers, photos), the ask. Repeat the ask at the end.
4. **Ruthless cut:** every block earns its place or goes. Long reference text
   (resources, testimonials) is organised to be scanned, not read top to
   bottom.

## 4. Storytelling patterns to design and add to the kit

All of them must be plain HTML that staff can edit, work without JavaScript,
use no inline styles, and meet the floors: 16px+ text, 4.5:1 contrast, 48px
targets.

| Pattern | For | Pages that want it |
|---|---|---|
| Image and text split, alternating sides (landscape photo beside 2 to 4 short paragraphs) | Breaking up prose with real photos | Helping Paw, Perpetual Care, Volunteer, About, Surrender |
| Featured story (one large photo, a pull quote, a name) | The hook at the top of a body | Testimonials, Fostering, Why, Helping Paw |
| Quote wall with filters or grouped headings (adopters, fosters, Helping Paw clients) | Long quote collections | Testimonials (8,874 words), Jobs |
| Numbers band (3 to 4 real figures) | Proof | About, Donate, Volunteer |
| Timeline | History | About (2009, 2011, 2019), Bauer Center |
| Steps with photos | Sequences | Process, Surrender, Perpetual Care enrolment |
| Directory: grouped, with anchors and a jump list | Scannable reference | Resources, Media, Videos |
| Photo mosaic (portrait and landscape mixed) | Warmth without words | Volunteer (the party albums), Benefit Shop, Adopted |
| FAQ (`qa`, which exists) | Objections | Perpetual Care (the old FAQ), Helping Paw, Process |

**Photo orientation rule to settle next session:** landscape for splits and
bands, portrait for people, square for mosaics. Crop from the prepared master
with `object-fit`, never by distorting it. Every photo gets real alt text
describing what is in it.

## 5. Suggested order (traffic and conversion first)

1. `/process/` and `/helping-paw/`: short, high-value, quick wins with steps
   and splits.
2. `/perpetual-care-program/` and `/surrender/`: hard moments; calm layout and
   the phone number always visible.
3. `/volunteer/`, `/donate/` and `/about/`: proof and belonging.
4. `/testimonials/`: the big restructure, with a grouped quote wall.
5. `/resources/`, `/media/`, `/videos/` and `/jobs/`: directories.
6. The rest: Benefit Shop, form pages, courtesy listings, events.

## 6. Open questions to bring

- Can POMDR supply more real photos per program (Helping Paw walks, Perpetual
  Care dogs, clinic staff at work)? What is in the WordPress library is the
  fallback.
- Which testimonials are the strongest? Staff could star 10 for featuring.
- The Culture and News text, and the two missing foster quotes (14 counted,
  12 held).
- Does LGL's form carry a reCAPTCHA? It showed up on /sponsor-a-dog/ on
  2026-09-25. Image challenges shut out older visitors, and the June audit
  said to avoid them. That is LGL's setting, but worth asking POMDR to review.
