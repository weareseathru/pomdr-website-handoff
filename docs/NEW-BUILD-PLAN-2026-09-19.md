# POMDR New-Build: findings and plan

Date: 2026-09-19. Author: Claude, with a council of four specialist reviews
(architecture, design and UX, nonprofit product and data, performance and
accessibility).

Scope of the review: the whole `pomdr-website-handoff` folder, the Divi child
theme, the older static prototype, and every markdown document in
`github.com/ronniereid/pomdr`, read twice.

---

## 1. The headline: that repository is not a reference, it is the rebuild

The brief assumed we would start a new build in `New-Build/`. We should not.

`github.com/ronniereid/pomdr` is already a ground-up, standalone rebuild of the
POMDR site. It contains no WordPress, no Divi, no framework, no package manager
and no build toolchain. It is plain PHP 8.4 and MariaDB over PDO. It is further
along than anything in this folder:

| Thing | State |
|---|---|
| Public pages with real migrated copy | 21, plus the homepage and a per-dog page |
| Database tables | 13 |
| Clinic notes imported | 14,856 |
| Shelterluv records mirrored, read only | 5,219 animals, 18,498 events |
| Static build | Renders 109 pages hourly, publishes to Cloudflare Pages |
| Photo pipeline | Master on the admin box, synced to R2, resized at the edge |
| Monitoring | Dead man's switch on a job table, alerting through Better Stack |
| Public stylesheet | 372 lines, marked "TEMPORARY. The real design replaces this file." |
| JavaScript files in the repo | Zero |

The architecture is a deliberate split. An admin host behind a Cloudflare tunnel
and Cloudflare Access serves both the staff admin and a live preview of the
public site. An hourly cron renders that site to flat HTML into a second private
repository, which Cloudflare Pages serves. The public site therefore runs no
PHP, holds no database credentials, and has nothing to compromise.

The decisions behind all of this are written down and argued in `PROS-CONS.md`,
including the ones that went against convenience. The code is unusually well
commented and consistently structured. This is good work and it should be built
on, not repeated.

### What that means for this folder

The two bodies of work are complementary, not competing.

- The rebuild repository has the architecture, the data model, the operations
  and the content migration. It has no design.
- This handoff folder has the design system, the information architecture, the
  voice rules, the accessibility research and the audit history. Its stack is
  being abandoned, but none of that thinking is.

The rebuild repository has deliberately left a hole exactly the shape of what
this folder contains. The job is to fill it.

### Status of `New-Build/`

Done already this session:

- `New-Build/` is a working clone of `github.com/ronniereid/pomdr`, with `origin`
  pointing at that repository, so we can pull and push from there.
- `New-Build/` is added to this repository's `.gitignore`, because it carries its
  own git history and must never be committed into the handoff repo.

Two things still need doing before any commit:

- No git identity is configured on this machine, so a commit would fail today.
- Read access to the repository is confirmed. Write access is not yet proven.

---

## 2. Verified findings

Each of these was checked in the code, not inferred.

### 2.1 Adopted dogs 404, and the launch plan depends on them not doing so

This is the most important finding in the review.

`public/pets/detail.php` returns a 404 for any dog who is not currently
available. `build/build.php` only generates a page per available dog. So:

- `/adopted/` renders a card for every adopted dog, and every one of those cards
  links to a page that does not exist.
- `/courtesy-listings/` and `/foster-needs/` have the same problem for any dog
  not also flagged available.
- `AtLaunch.md` plans to redirect 4,594 legacy dog addresses to
  `/pets/<slug>/`. For adopted dogs, those redirects would land on 404s.

That last point undoes the stated purpose of the whole redirect exercise. The
document is explicit that 3,596 adopters keep their dog's page, return to it on
the anniversary, and come back to it after the dog dies. A 404 there is not a
broken link, it is somebody discovering their dog has been deleted.

The fix is a decision, not a large amount of code: an adopted dog's page should
persist as a happy-ending page rather than disappear.

### 2.2 The dog page has no way to adopt the dog

There is no adoption questionnaire link anywhere on `public/pets/detail.php`, and
no `?dogname=` prefill anywhere in the public site. The repository's own README
says that link is mandatory and that a stripped query string should be treated as
a broken link. The page currently ends with a link back to the list.

### 2.3 Two statuses staff use have nowhere to live

The live content model has seven statuses. The new schema has flags for
available, foster, hospice, courtesy, adopted, draft and community care. It has
no flag for **Sponsor Needed** and none for **Adoption Pending**. It also
conflates "is in a foster home" with "needs a foster home" in a single flag.

Everything about how dogs are presented is blocked on the data being able to say
what staff mean.

### 2.4 Nightly database dumps are committed to git

`bin/backup-db.php` commits a compressed dump into `db/backups/`. The current
file is 4.7 MB and contains real rows for the clinic notes table, the people
table and the admin users table, including password hashes.

The repository is private. I verified that: anonymous access is refused over both
the web and the git protocol. That matters, and it makes this less severe than it
first appears. It does not make it fine:

- Most of the clinic notes are community clinic patients, which is to say
  members of the public. That is third-party medical and contact information.
- Git history cannot be pruned after the fact. Every clone holds a permanent
  copy, on every laptop, forever.
- The repository lives in an individual's personal GitHub account. POMDR's master
  data and its only routine backup currently depend on one person's account
  staying accessible.

Recommendation: stop committing the dump, move backups to encrypted object
storage with a retention policy, and move the repository into a POMDR
organisation with at least two owners.

### 2.5 The homepage bypasses the image pipeline entirely

The homepage does not use the shared dog card. It has its own copy of the loop,
and that copy prints the raw legacy photo address straight from the database. It
never calls the image helper. So the eight dogs on the homepage load as
full-resolution originals from the old WordPress site, with no width, no height,
no lazy loading and no edge resizing.

That single block is simultaneously the worst loading defect on the site, the
worst layout-shift defect, and a divergence from the one component every other
listing page shares. On a rural connection it is several megabytes above the
fold. The fix is to delete the duplicated loop and use the shared card.

### 2.6 The dog biography is printed as raw unescaped HTML

The dog page prints the staff-written description without escaping or filtering.
This is deliberate and the comment explains the reasoning, but the consequence is
that anything pasted into the admin, including a tracking snippet, a Word
document blob or inline styles, is baked permanently into a static page that
adopters share for years. It needs a tag allow-list, and the build should refuse
to publish anything outside it.

### 2.7 The thirty-day asset cache does not exist in production

The cache headers live in the web server configuration for the admin host. The
public site is served by Cloudflare Pages, which never reads that file, and there
is no headers file in the published output. The caching everyone believes is in
place is not.

### 2.8 Smaller verified issues

- The dog card gives its image a width but no height, which guarantees layout
  shift on every grid.
- The card wraps only the photo and the name in the link, leaving the summary
  line outside it, which halves the tap area.
- The age helper prints "13 years old". The brand rule requires the tilde form,
  `~13 yrs`.
- The footer hardcodes the phone number, four addresses and the hours. Its own
  comment calls this a placeholder.
- No image anywhere uses a responsive image set.
- The stylesheet has no focus-visible rule at all, so focus depends entirely on
  browser defaults, which are often invisible over custom backgrounds.
- The navigation signals the current page with colour and a border only, with no
  programmatic equivalent, and its sub-links are about 14px with roughly
  20-pixel-tall targets.
- The videos page embeds fourteen YouTube players on one page. Lazy loading
  defers the fetch, not the cost of fourteen browser instances.
- Four of the five form embeds pin their origin to the old domain, which POMDR
  must fix inside Little Green Light before cutover. That is an account change,
  not a code change, and it has a lead time.

---

## 3. Recommendation

**Adopt the rebuild repository as the foundation. Port the design into it. Do
not start a third parallel build.**

The 133 commits already there are the unglamorous work that cannot be skipped:
the database layer, session authentication, the photo pipeline, the image audit
that catches silent breakage, the build that renders everything before writing
anything, and the schema. Starting again would take months to reach today's
position, on a project whose real risk is the launch cutover rather than the
stack.

What the brief actually asks for, which is animations, speed, the dog and event
experience, editability, forms and a future app, is overwhelmingly the stylesheet
and the page bodies. That is precisely the part that has been left undone.

### What ports from this folder, and what does not

**Ports as decisions:** the design token system, the information architecture and
navigation, the voice rules, the accessibility research, the audit findings, the
redirect analysis, the status badge priority rules, and the component standards
for the split page header and the photo card.

**Does not port:** the roughly 10,000 lines of CSS in the Divi child theme. It is
written against Divi's DOM and is worthless outside it. The same goes for the
whole family of shortcodes that exist only to get field data into Divi's builder.

**One correction to make before porting.** The design documents disagree with
each other by date. `design-system/MASTER.md` and `BRAND-GUIDELINES.md` are stale
on colour: they still show warm cream backgrounds and a live orange. Leadership
overrode that on 2026-07-09. The current values are in the child theme's token
file: cream became the light blue `#E8F2F6`, the hairline became `#cfe0e9`, and
orange is retired and aliased to purple. Port the live values, not the stale
documents, and then update the documents.

---

## 4. Working alongside the other developer

This is the constraint that shapes everything else, and it is a people problem
before it is a git problem.

That repository is not ours. It is moving at roughly 30 commits a day, all by
Ronnie Reid, including commits made today. Two people redesigning the same files
without an agreement will produce exactly the mess this project is trying to
escape.

**Before the first commit, agree the seam in writing.**

The good news is that the seam is clean and the evidence supports it. Over the
last week, the heavily-edited files are the database layer, the admin stylesheet,
the helpers and the pet edit screen. The public stylesheet was touched five
times, and most public page bodies three.

| Ours | Theirs |
|---|---|
| The public stylesheet | The database layer and helpers |
| Committed page images | Everything under the admin, bin, build, db and deploy directories |
| The homepage and each public page body | The project documents |
| The dog page markup | Photo, job and Shelterluv machinery |
| The header and footer, with care | |

Mechanics: fork the repository, rename the current remote to `upstream` and keep
it read only, add the fork as `origin`. Work on topic branches. Rebase onto
upstream daily rather than merging it in. Open small pull requests upstream and
get them merged quickly. A long-lived divergent fork is the Divi mistake
repeating one layer down.

There is no test suite and no continuous integration. The repository's own lint
script is the entire gate, and it must be run before every commit.

**One shared cleanup makes a good first pull request.** The header file mixes the
navigation data with the chrome markup, and the footer has POMDR's contact facts
typed into it. Splitting the navigation into its own file and moving the footer
facts into a settings table is mechanical, uncontroversial, and it permanently
separates the two workstreams.

---

## 5. The six asks, answered

### Animations

Build them, in CSS, with one narrow exception.

The council was unanimous on the principle: nothing on the page may move unless
the user caused it to move by scrolling or navigating. For an audience of older
adults, an auto-advancing carousel is the worst available pattern. It imposes a
time limit on reading and moves the tap target out from under an unsteady finger.
The current site has a five-slide rotating hero. It should not survive.

What to build instead, all with modern CSS:

- Scroll-driven section reveal using a view timeline, a short 12-pixel rise, not
  a 40-pixel one.
- Cross-document view transitions, so tapping a dog card morphs its photo into
  the dog's page. A flat static multi-page site is the ideal case for this, and
  it degrades to an ordinary page load where unsupported. This is the highest
  value motion on the site because it makes the whole thing feel like an app for
  nothing.
- A starting-style transition on the mobile navigation panel.
- The homepage dog strip as a scroll-snap rail with real anchor controls, not a
  carousel.

Every one of these sits behind a feature query so older browsers see static
content, and all of it is switched off under reduced motion. That gate is not
negotiable.

**The signature paw trail.** The existing prototype has a scroll-scrubbed
watercolour paw trail that walks down the homepage. It is genuinely distinctive
and it is already written to degrade to a static finished trail. It also depends
on GSAP, which the rebuild's rules forbid. My recommendation is to rebuild it
without the library, driven by a scroll-linked animation, and to treat it as the
one piece of decoration the site earns. If that proves impractical, drop it
rather than importing a dependency.

**On JavaScript.** Zero JavaScript is a current fact of that repository, not a
written rule. The written rule forbids frameworks, package managers and
bundlers, and the README already contemplates "a little JavaScript". I recommend
negotiating exactly one hand-written file, no dependencies, well under 5 KB, that
does two things a stylesheet cannot: the larger-text toggle, which is the single
most valuable control on the site for this audience, and a height listener for
the embedded forms. Everything else, including the navigation, can be
declarative. If that file ever becomes load-bearing, something above it was built
wrong.

### The dog experience

This is where the work pays off, and it is currently a stub.

- Fix the card: one link across the whole card, a real responsive image set,
  height attributes, and a status badge.
- Rebuild the dog page. It is the most-linked page on the site. It needs the
  photo gallery, a fact panel that stays in view, the tilde age form, and two
  buttons above the story and repeated below it: start an adoption questionnaire
  for this dog, and sponsor this dog. It must stop 404ing adopted dogs.
- Add the two missing status flags first. Then render status with three redundant
  signals, an icon, the full word and a border treatment, never colour alone. A
  dog can hold two statuses at once, so badges stack rather than merge.

**Filtering without JavaScript.** Do not use CSS checkbox tricks. They break the
back button, bookmarking and the URL. Build real facet pages instead, generated
by the same build mechanism that already produces a page per dog: small dogs,
seniors, needs a foster, hospice, and so on. Six to eight facets, presented as
large pill links with the current one marked, each stating its own count. On
sorting, the honest answer is that with fewer than a hundred dogs nobody uses it.
Default to longest-waiting first, which serves the dogs who need it most.

### Events

Currently an upcoming-only list. It should be three things stacked: the next
event as a full card with a spelled-out day, a plain-text address and a
directions link; the rest of the upcoming list; and a small gallery of recent past
events, which is the proof that these happen regularly and are relaxed. Each
event needs its own shareable page and a calendar download. Past events are
already queryable.

### Forms

Keep Little Green Light. Do not build a form endpoint. A native form would mean
the data is re-keyed into Little Green Light anyway, which adds double entry, the
exact thing this project exists to remove. It would also put personal data on
POMDR's own server, which the current arrangement deliberately avoids.

"Integrated" should mean one shared embed include instead of five ported
variations, a reserved box so the page does not jump, a title attribute on every
frame, and the dog name carried through as a prefill.

The bigger win is not technical. Before every embed, put the form's name in
plain language, how long it takes, what the person will need, and the phone
number with the words "prefer to do this by phone?". For a senior in a stressful
moment, the phone is the accessible path and it belongs at the top.

For the two longest forms, I would seriously consider linking out to the hosted
form as a full page rather than trapping a 3,600-pixel form inside a scrolling
box.

### Editability

The rebuild's position is that page wording lives in git and staff cannot change
it. Its own decision log names this as the single biggest known weakness. The
council agreed the position is right about page structure and wrong about page
facts.

The recommendation, in order:

1. A settings table and one admin screen for the phone number, the three
   addresses, the hours, the email, the tax ID and the social links, before
   launch. The footer reads from it. This is about a day's work and it removes
   the most embarrassing failure mode, which is a four-year-old phone number.
2. A named content block table for perhaps 15 to 25 high-churn fragments: the
   adopt introduction, the foster needs copy, the Helping Paw eligibility lines,
   an emergency banner. Staff edit a plain textarea with a small safe markup
   subset, not a rich text editor. Blocks render at build time, so the static
   architecture is untouched.
3. A publish-now button in the admin, so a typo fix does not wait an hour.
4. No GitHub web editor for staff. It is free and it is a trap. One bad paste
   breaks the build and the person who broke it cannot tell.
5. No custom content management system.

Before building any of it, ask the four staff the question the decision log
already poses: of the things you change most often, which ones fall on the wrong
side of that line? The answer should decide the scope, not our guess.

### Speed, and the eventual app

On performance, the architecture is already close to ideal. Flat HTML on a global
edge network with images resized at the edge is hard to beat. The problems are
all in the details, and the ranked list is: the homepage bypassing the image
pipeline, the fourteen video embeds, the missing responsive image sets and height
attributes, the form frames, and the caching headers that are not actually in
production.

Two specifics worth settling early. Fix the image widths to a short ladder and
snap every request to the nearest rung, because an open-ended set of widths
multiplies edge cache misses on a feature POMDR pays for per transformation. And
on fonts, the recommendation from the performance review is to keep the system
font stack for body text and self-host a single serif file for headings. System
fonts are already on the device, are tuned for that operating system, and respect
the reader's own size settings, which matters more here than brand fidelity.
Either way, never call a font service from the public site.

Suggested budgets: under 500 KB and 40 requests per page, largest paint under two
seconds on a mid-range Android over slow mobile data, layout shift under 0.05.

On the app: do not build an API, and do not build a native app. An organisation
with a handful of staff and dozens of fosters cannot maintain two codebases and
app store accounts. The right first app is the foster extranet that is already
designed in the repository: a phone-shaped page with a magic-link login showing a
foster their dog's medication schedule, what to do today, who to call, and a way
to say they are worried. It needs the server to be able to send email, which it
currently cannot, so that is the real prerequisite.

What to do now, cheaply, to keep the option open: have the existing build emit
static JSON alongside the HTML, from the same queries that render the pages. That
is a free, cached, read-only public interface, and it is most of what any future
companion app needs.

---

## 6. Decisions needed from Andrew

Work can start without these, but each one blocks something downstream.

1. **Adopted dog pages.** Do they persist as happy-ending pages? This blocks the
   redirect plan and it is the single most consequential answer on the list.
2. **The seam with Ronnie.** Who owns which files, and does the public stylesheet
   become ours outright?
3. **One JavaScript file, or none.**
4. **The hero.** One static hero, or keep the rotating carousel? The council
   recommends killing the rotation.
5. **Button case.** Leadership mandated all caps on 2026-07-09. The August
   benchmark argues caps measurably slow reading for this audience and proposes
   sentence case. These two directives contradict each other and the conflict is
   still open.
6. **The paw trail.** Rebuild without the library, or drop it?
7. **The committed database backups.** Stop them, and move the repository to a
   POMDR organisation?

Three items are POMDR's to answer rather than ours, and each has a lead time:
whether the Cloudflare image plan is paid for, who inside Little Green Light can
change the allowed domains on each form, and whose phone the monitoring alert
reaches at the weekend.

---

## 7. Sequence

The ordering principle is that the token layer has the widest blast radius, the
dog experience carries the most value, and animation comes late because it is
additive and doing it before the layout settles means doing it twice.

**Phase 0, before anything visual.** Agree the seam. Set up the fork and a git
identity. Confirm the Cloudflare image plan is paid for, because without it every
photo on the site is broken while the database looks perfect, which has already
happened once.

**Phase 1, foundations.** Before any design work, put the reduced-motion block
and a global focus ring into the stylesheet, so nothing can be built afterwards
that ignores them. Then split the navigation data out of the header and the
contact facts out of the footer, write the token layer and type scale using the
live values rather than the stale documents, and add the migration for the two
missing statuses.

Ten defects are worth fixing immediately, roughly in this order, because each is
small and each is currently costing something: filter the dog biography; delete
the duplicated homepage card loop; add the reduced-motion and focus rules;
replace the fourteen video embeds with linked thumbnails; replace the tallest
form frames with a prominent link out; load the first dog photo eagerly and give
every photo a height; underline links, mark the current page programmatically
and raise text and targets to the floors; add a headers file for caching and
security; snap image widths to the ladder; and wire the checks below into the
build's failure path.

**Phase 2, chrome.** Rebuild the header, navigation and footer. Add the phone
number to the header. Add breadcrumbs, which are specified everywhere and exist
nowhere. Every page improves at once.

**Phase 3, dogs.** The card, then the dog page, then the adopt page and its facet
pages. Restore the adoption link. Stop adopted dogs 404ing. This is the highest
value work in the project.

**Phase 4, homepage.** Rebuild to a clearer section order: hero with one action,
dogs available now with a live count, two doors for the two audiences, proof,
ways to help, next event.

**Phase 5, the rest.** The remaining pages against the design system and the
voice rules, one pull request each, plus the missing pages the new build does not
yet have.

**Phase 6, polish.** The shared form include and the reassurance block. The
animation layer. The one JavaScript file, last, so that it is provably optional.

**Running in parallel, because it is POMDR-decision-heavy and has the longest
lead time:** the redirect inventory and the sitemap. Nothing generates a sitemap
today, and the full dog redirect list cannot be produced until the archive import
has run.

---

## 8. Quality gates

There is no test suite and no continuous integration today. The lint script is
the only gate. That is thin for a site that publishes itself hourly with nobody
pressing anything.

The build already refuses to publish when a page fails to render, and that is the
right hook to hang everything else on. Add to it: an HTML validator over the
generated output, an accessibility scan over the same files, a link checker, a
size guard that fails when a committed image exceeds the stated limit, and a
performance budget check. Extend the existing lint to forbid inline styles,
absolute internal links and unescaped output in public pages. The biography
defect should have been caught by a lint rather than a review.

Some things cannot be automated and should be done every release: a keyboard-only
pass from the homepage to a dog and back, zoom to 400 percent at desktop width,
a screen reader pass over the homepage and a dog page, a real-device check on an
older tablet and a mid-range Android, and printing a dog page. Seniors print.

The highest-value test is not a tool. Watch two POMDR volunteers over 65 complete
the volunteer application unassisted. That will teach more than the rest combined.

---

## 9. Risks

- **The redirects are the one launch mistake that cannot be undone.** Traffic
  lost to 404s comes back slowly, if at all, and the human cost is adopters
  finding their dog's page gone.
- **Two developers, one repository, no tests, no continuous integration.** The
  mitigation is the agreed seam, small pull requests and daily rebasing.
- **Bus factor.** One person currently holds the Cloudflare account, the object
  storage, the Shelterluv key, the GitHub account and the server. Document and
  co-own every credential before launch.
- **The photo masters are still not backed up**, and they are the only copy.
- **The freeze.** Anything staff change in the old system after the final import
  is lost. This is the item most likely to be discovered rather than planned, and
  staff need to be told the exact hour in advance.
- **Scope.** The biggest risk to this project is not the stack. It is that a
  second system competes for attention and neither gets finished. The site is not
  launched yet. Launch it.
