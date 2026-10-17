# Navigation and page architecture plan (New-Build)

Status: APPROVED by Andrew and BUILT in New-Build, 2026-09-25 (all six
recommendations in section 8 accepted). Refinements made while building, to
fit Ronnie's rules in `includes/header.php`:

- **No "Meet the dogs", "Our story", "Upcoming events" or "Volunteer with us"
  entries.** A dropdown never repeats its parent's own address; the top word
  itself goes there.
- **Get help got its own front page, /get-help/**, so its word has somewhere
  to go: five cards, one per situation, plus the phone number.
- **Culture and News are not in the menu yet.** No address without a page, and
  their real text is not in hand (the Divi culture values were invented).
- **Annual reports point at /about/#financials**, where Impact Reports already
  are, not at a new page.
- **The validation in section 6 has not been run.** It needs staff and older
  visitors.
- **Changes after the build, 2026-09-29 (Andrew):** every label is the page's
  own title, except "Placing Your Dog" for /surrender/ and title case on Get
  Help and About Us; the clinic moved from Get Help to About Us, Visit us; Foster
  gained `/become-a-foster/` as its first page; the footer follows the same four
  groups.
Updates `docs/IA-UX-AUDIT.md` (June 2026), whose menu decisions were made for
the Divi build.

Sources compared:

- **www.pomdr.org** (the old site at peaceofminddogrescue.org): 56 flat pages,
  crawled 2026-07-21 (`docs/PARITY-AUDIT-2026-07-21.md`) and 2026-09-23.
- **newpomdr-local**, the Divi build: the menu in
  `divi-child-integration/inc/chrome.php`, about 50 pages.
- **New-Build**: `$main_nav` in `includes/header.php`, 29 pages.

---

## 1. Who arrives, and the one job each came to do

| Visitor | The job | Their state of mind |
|---|---|---|
| Adopter, often 55+ | Find a calm senior dog, understand the process, apply | Hopeful, browsing, reads a lot |
| Foster | See who needs a home now, apply | Ready to help, wants the list |
| Senior owner or family in crisis | Keep the dog, rehome it, or plan ahead | Stressed, short on patience, often phones |
| Donor or sponsor | Give once or monthly, sponsor one dog, leave a legacy | Wants to know where the money goes |
| Volunteer, job seeker | Sign up, see openings | Practical |
| Community | Events, news, the Benefit Shop, the vet clinic, a visit | Returning, local |
| Past adopter | Find their dog on the wall | Nostalgic |

**How might we** let every visitor find their job in one look at the bar, in
their own words, without first having to learn POMDR's program names?

## 2. What each existing architecture gets wrong

- **The old site** has a page for everything, but it is flat, and the
  applications are links buried in paragraphs.
- **newpomdr-local** is right to have *What's Happening* and a grouped *About*.
  But its *Dogs* menu mixes Adopt, Foster **and Surrender**, so the owner in
  crisis has to look under "Dogs" for help giving theirs up.
- **New-Build today:**
  - *Placing your dog* takes a top-level slot on its own.
  - Foster is hidden under *Volunteer*.
  - *Helping Paw* has no sub-pages.
  - Seven pages are in no menu at all:
    - `/hospice/`
    - `/sponsor-a-dog/`
    - `/mailing-list/`
    - `/adoption-questionnaire/`
    - `/helping-paw-application/` and its Spanish page
    - `/surrender-questionnaire/`
  - The dropdowns open **only on hover or keyboard focus**. On a tablet or
    touchscreen laptop, tapping *Adopt* goes straight to /adopt/, and the
    pages under it cannot be reached from the menu. That breaks the project
    rule of no hover-only functions.

## 3. Principles

1. **Label by what the visitor wants, in plain words.** Use concrete nouns
   and verbs. Vague labels ("Get involved", "Programs") test worst with older
   visitors.
2. **Six top-level words plus Donate.** Each dropdown has 3 to 7 items.
3. **One home per page.** Every page has exactly one parent, which is also
   its breadcrumb (the trail already follows the menu).
4. **Donate is the only thing in the bar that shouts.** It stays a single
   button with no dropdown.
5. **The menu finds pages; buttons take actions.** Applications are
   high-contrast buttons at the top of their pages, not menu items.
6. **Nothing depends on hover.**
7. **No published address changes** (README rule 12). New pages get new
   addresses, and old addresses redirect.

## 4. The proposed menu

```
[Logo]  Adopt   Foster   Get help   Volunteer   Events   About us   [Donate]
```

The labels total 42 characters, against 49 today ("Placing your dog" alone
is 16), so the doubled navigation still fits on one row at the current
breakpoint.

| Top level | Dropdown (in order) | Address | Exists? |
|---|---|---|---|
| **Adopt** | Meet the dogs | /adopt/ | yes |
| | How adopting works | /process/ | yes |
| | Why a senior dog | /why/ | **new** |
| | Courtesy listings | /courtesy-listings/ | yes |
| | Sanctuary and hospice dogs | /hospice/ | yes, not in menu |
| | Happy tails | /adopted/ | yes |
| **Foster** | Dogs who need a foster | /foster-needs/ | yes |
| | About fostering | /fostering/ | **new**; its button goes to /volunteer-application/ |
| **Get help** | Keep your dog: Helping Paw | /helping-paw/ | yes |
| | Find your dog a new home | /surrender/ | yes |
| | Plan ahead: Perpetual Care | /perpetual-care-program/ | yes (FAQ becomes a section on it) |
| | Vet clinic | /clinic/ | **new** |
| | Resources | /resources/ | yes |
| **Volunteer** | Volunteer with us | /volunteer/ | yes |
| | Volunteer application | /volunteer-application/ | yes |
| | Jobs | /jobs/ | yes |
| **Events** | Upcoming events | /events/ | yes |
| | Adoption events | /events/#adoption-events | page yes; the anchor is **new** (split the list by event type) |
| | News | /news/ | **new** |
| | Mailing list | /mailing-list/ | yes, not in menu |
| **About us** | *Who we are:* Our story, Our team, Culture, Impact reports, Testimonials, Videos, In the media | /about/, /about/#team, /about/culture/, /about/#financials, /testimonials/, /videos/, /media/ | Culture is **new**; the #team anchor is **new**; Impact Reports already exist on /about/ |
| | *Visit us:* Bauer Center, Benefit Shop, Contact | /bauer-center/, /benefit-shop/, /contact/ | Bauer Center and Contact are **new** |
| **Donate** (button) | none; /donate/ is the Ways to Give hub | /donate/ | yes |

Reached by buttons, not the menu:

- /adoption-questionnaire/ from each dog page and /process/
- /helping-paw-application/ and es/ from /helping-paw/
- /surrender-questionnaire/ from /surrender/
- /sponsor-a-dog/ from each dog page and /donate/
- The giving pages (monthly, planned giving, legacy, tribute, wish list,
  Max's fund) as cards on /donate/, following the June hub decision.

### Why these calls

- **Foster at the top.** Fostering is the rescue's bottleneck: a dog cannot
  come in without a foster. The homepage already sells it.
- **"Get help" joins Helping Paw, rehoming and Perpetual Care.** All three
  are the same person, a senior owner, at different moments. Plain words lead;
  the brand name stays in the dropdown text ("Keep your dog: Helping Paw"),
  where newcomers learn it.
- **Events at the top.** It is the reason community members come back.
  newpomdr-local proved the pattern; "Events" is plainer than "What's
  Happening".
- **About us in two labelled groups**, as on newpomdr-local. Ten items read
  easily as 7 plus 3, where one long list would not.
- **The vet clinic sits under Get help.** Owners arrive there for community
  care. It is also listed under Visit us in the footer.

## 5. How the menu behaves

- **Desktop:** each label stays a link, so the site works with no
  JavaScript. A separate chevron button beside it opens and closes the list on
  click or tap, with `aria-expanded` (W3C's disclosure-navigation pattern).
  Hover can still open it as a convenience. Escape closes it and returns
  focus.
- **Every section's front page lists its own pages** under a heading, "In
  this section". This is the fallback with no JavaScript, and it also helps
  readers who like to see everything.
- **Phones:** the same tree in the drawer, as accordions. Donate and the
  phone number are pinned at the top of the drawer.
- **The strip above the bar:** keep the tagline, and swap the Adopt and
  Volunteer pills for **the phone number** and **Foster**. Adopt is already
  the first word in the bar, and many of these visitors would rather call.
- **The footer is the sitemap:** columns for Dogs, Get help, Give and About,
  plus contact, the three addresses and the legal links.
- **Breadcrumbs:** unchanged. They follow the menu, so they follow this plan
  automatically.

## 6. Validation, before building it for real

1. **Card sort with staff** (Carie, Monica, Allison, 30 minutes): do they
   group the pages this way?
2. **Tree test with 5 to 8 older visitors**, on the text-only menu, 8
   tasks. For example:
   - "Your mother is moving into care and cannot take her dog."
   - "You want to pay for one dog's care."
   - "You want to meet the dogs at an event this month."

   Pass mark: 80% find it on the first click. If a task fails, change
   **words** before changing structure.
3. After launch, count clicks from each menu item to a started application.

## 7. Build order in New-Build

1. **Menu data and behaviour** (`includes/header.php`, `site.css`, plus a
   small script):
   - the new array, with support for About's two groups
   - the chevron buttons
   - the drawer accordions
   - "In this section" on each front page
2. **Put the orphan pages into the menu** (hospice, mailing list); no new
   pages needed.
3. **New pages, in traffic order:**
   - /fostering/ and /why/ (port the text from newpomdr-local and the old
     site; the six real adopter quotes belong on /why/)
   - /contact/
   - /clinic/
   - /news/
   - /about/culture/ (with the real values, not the invented ones)
   - /bauer-center/
   - the `#team` and `#adoption-events` anchors
4. **Redirects in `AtLaunch.md`:**
   - the old site's 56 `.html` and `.php` addresses (the map is the parity
     table in `docs/PARITY-AUDIT-2026-07-21.md`)
   - /recources/ to /resources/
   - /intake-questionnaire/ (see decision 5)

## 8. Decisions for Andrew

1. **"Get help" as one top-level item**, or keep *Helping Paw* on its own
   and move *Placing your dog* into it? Recommended: Get help.
2. **Foster at the top**, rather than under Volunteer? Recommended: yes.
3. **Events at the top**, rather than under About? Recommended: yes.
4. **The strip above the bar:** phone number and Foster instead of the Adopt
   and Volunteer pills? Recommended: yes.
5. **The rehoming form's address.** We built /surrender-questionnaire/, but
   new.pomdr.org and the old site's forms use /intake-questionnaire/.
   Recommended: move ours to /intake-questionnaire/ before launch, since that
   is the address already in use.
6. **The words "Find your dog a new home"** in place of "Placing your dog"
   (the page title can stay)? Recommended: yes, but ask staff.
