# Header, footer and navigation: ground-up rebuild plan

Date: 2026-09-19. Status: proposal, awaiting approval. Nothing is built yet and
nothing is pushed anywhere.

Companion document: `docs/NEW-BUILD-PLAN-2026-09-19.md`, which covers the whole
project. This one covers only the first piece of work.

---

## 1. What I understand the brief to be

1. The standalone repository is adopted as the foundation. That decision is made.
2. The design and content work in this folder, built for WordPress and Divi, is
   not ported. It is rethought and rewritten as native code for that repository,
   to the standard that codebase sets for itself.
3. The first piece is the site chrome: header, footer, and the navigation
   behaviour.
4. All of it is built and reviewed here, locally, first. Nothing reaches GitHub
   until you have approved it.

That last point shapes the whole plan, so section 7 is about how you actually
see and judge the work before it goes anywhere.

---

## 2. Three versions of the chrome exist. None of them is the answer

| Source | What it gets right | What it gets wrong |
|---|---|---|
| Divi child theme | The information architecture, the Donate emphasis, the paw badge, the footer grouping | Two tiers and 172px of chrome, JavaScript dropdowns, a scroll-shrink effect, and on a 390px phone the bar and logo ate about 140px before any content |
| Static prototype | Breadcrumbs, a real main landmark, keyboard-handled menus, a mobile drawer | It is all injected by JavaScript, so it is invisible to the static build and disappears without scripting |
| New-Build today | Nothing is hidden behind a hover or a click, which is the single most senior-friendly decision anywhere in the project. No JavaScript at all | A text-only logo, no phone number, no mobile treatment, no breadcrumbs, no programmatic current-page marker, and 26 links stacked above the first heading |

The rebuild keeps the New-Build principle, takes the information architecture and
the visual language from the Divi work, takes breadcrumbs from the prototype, and
fixes what none of them solved.

---

## 3. The blocking problem, which I would rather find now than halfway through

**Twelve of the navigation destinations in the Divi menu do not exist in the new
build.** I checked all thirty-one by path:

Missing: why, hospice, fostering, intake-questionnaire, perpetual-care-faq,
helping-paw-application, maxs-fund, news, culture, bauer-center, clinic,
mailing-list.

Porting the menu across as it stands would ship twelve dead links on every page
of the site. So the navigation cannot be rebuilt in isolation. Each of those
twelve needs a decision before the menu is final, and there are only three
possible answers per item: build the page now, fold it into an existing page as
an anchor, or leave it out of the menu until it exists.

My recommendation, for your yes or no:

| Destination | Recommendation | Why |
|---|---|---|
| why | Build | The best adoption-persuasion page there is, and the process page used to link to it |
| hospice | Build | Hospice is a status we display, and the launch document flags the old hospice page as having nowhere to go |
| fostering | Build | Fostering is the organisation's actual capacity constraint |
| intake-questionnaire | Build | It is one form embed, and it is the primary action of the surrender page |
| helping-paw-application | Build | Same, and it has an English and a Spanish version |
| mailing-list | Build | Same, one embed |
| bauer-center, clinic | Build | Real places with addresses and hours, and both are flagged in the launch document as having no redirect destination |
| perpetual-care-faq | Fold | Anchors on the perpetual care page |
| culture | Fold | A section on the about page |
| maxs-fund | Fold | A card and an anchor on the donate page |
| news | Leave out for now | It needs an editable content decision first, and it is the only one that does |

That is eight small pages, three folds and one deferral. The eight are mostly
short: three of them are a form embed and a paragraph.

---

## 4. Defects in the current navigation, measured

I parsed the menu array rather than eyeballing it.

- 26 links are rendered in the header of every page.
- 5 sub-links point at their own parent: Adopt to Adopt, Volunteer to Volunteer,
  About Us to About, and both Helping Paw children to Helping Paw.
- 2 pairs of siblings are identical: both Helping Paw children, and Surrender and
  Placing Your Dog both point at the surrender page.
- The current page is signalled by colour and a border only, with no
  programmatic equivalent, so a screen reader cannot tell where it is.
- Sub-links are about 14px with roughly 20-pixel-tall targets, against a 16px
  floor and a 44px target standard.
- Navigation links are distinguished from body text by colour alone, at a ratio
  too low for that to be permitted.
- The skip link becomes statically positioned when focused, which reflows the
  entire page the moment a keyboard user presses Tab.

Removing the duplicates alone takes the header from 26 links to about 20 without
losing a single destination.

---

## 5. What I would build

### The header

One bar, not two. The Divi action bar duplicated Adopt and Volunteer, which were
already in the menu directly below it, and charged 172px for the privilege.

The bar carries four things, and nothing else earns a place: the logo, the
tagline, the phone number, and Donate.

**The phone number goes in the header at every screen size**, as a tap-to-call
link. For someone facing a surrender or applying to Helping Paw, often in a bad
week, the phone is the accessible path, and right now it appears only in the
footer. Donate keeps the outline-that-fills treatment from the Divi build, which
is a good pattern and survives.

**The menu needs no JavaScript.** On wide screens the sub-links stay visible, as
they are now, because nothing hidden is the right call for this audience and the
space exists. Below roughly 1000px the whole menu collapses into a single panel
opened by a button, using the browser's own popover behaviour: it closes on
Escape and on an outside click for free, with no script, and it fades in with a
starting-style rule. That gives a genuinely modern mobile menu with zero
JavaScript, which I think is the nicest result in this plan.

**Breadcrumbs** go under the header on every page below the top level, generated
from the same menu data, with the trail following the menu hierarchy rather than
the URL. They are specified in the audit, exist only in the prototype, and are
absent from both the Divi build and the new one.

The current page gets a real programmatic marker plus a visual treatment that is
not only colour. Every link in the chrome is underlined. Targets go to 48px with
space between them, because for a hand with a tremor the gaps matter as much as
the sizes.

### The footer

Logo and tagline, then three link columns matching the menu's top-level
grouping, then a contact block, then the legal line.

The contact block carries what is currently missing everywhere: the phone as a
tap-to-call link, the email, and all three locations with their addresses and
opening hours. The Bauer Center, the vet clinic and the Benefit Shop each have an
address; only the first appears anywhere today, and no hours appear at all.

Then the 501(c)(3) line with the tax ID, privacy and terms, and the social links.

**Every one of those facts comes from one array in one file.** That matters more
than it sounds. It means the footer markup is written once, and when the settings
table gets built the swap is a one-line change rather than a rewrite. It is also
the difference between a phone number that can be corrected and one that needs a
developer.

### What comes with it, unavoidably

You cannot style chrome without a base layer, so this piece also lays down the
design tokens, the type scale, the link and focus treatment, the reduced-motion
block and the page measure. That is the foundation every other page inherits, so
doing it here is the right order anyway rather than a scope leak.

One correction to carry: the design system document and the brand guidelines are
stale on colour. They still show warm cream and a live orange. Leadership
overrode that in July. I will use the live token values, cream as the light blue
and orange retired, and update the two stale documents in the same pass.

### What I would deliberately drop

The two-tier chrome. The JavaScript dropdowns and their hide delays. The
shrink-on-scroll effect. The floating larger-text button, which collided with two
other controls in the Divi build, though the feature itself deserves to survive
in a better form. The sticky mobile bottom bar from the prototype, which spent
viewport this audience needs.

---

## 6. Assets I do not have

**There is no vector logo anywhere in this repository.** The only logo is a
600 by 133 pixel PNG in the Divi theme. The brand notes say the vector masters
live in Google Drive, and that connector is not authorised in this session.

The header is the most-repeated image on the site and it should be an SVG. I need
either the vector file or your say-so to ship the PNG at double resolution as an
interim. The two paw badge SVGs do exist and will be reused.

I also need the real opening hours for the three locations, which appear in none
of the documents I have read.

---

## 7. How you see it before it goes anywhere

I checked that this works rather than assuming it.

There is no PHP and no MySQL on this machine's path, but the Local app bundles
both. The bundled PHP is 8.2.30 and has every extension the project needs. The
production box runs 8.4; nothing in this work uses anything newer than 8.2, and
if you would rather match production exactly, Homebrew can install 8.4.

The repository's own development server reproduces the production URL rules
exactly, so what I show you is what the build would produce. I started it and
walked every page:

- **15 pages render with no database at all.** That is enough to judge the
  header, footer, navigation, breadcrumbs and the whole base layer.
- **6 pages plus the homepage and the dog pages need a database**, because they
  list dogs, events or team members.

So the chrome work can begin and be reviewed immediately, with nothing installed.
For the dog and event pages I would stand up a local database from the schema
file in the repository and seed a handful of fixture dogs. We never connect to
POMDR's real database from here; it is on their network and it is live.

**What you get at each review point:** a running local site you can click through
at an address on your own machine, plus screenshots at 375, 768, 1024 and 1440
pixels, a shot at 400 percent zoom, a keyboard-only walk, and an automated
accessibility scan. We iterate here until you are happy.

---

## 8. Getting it to GitHub, after you approve

1. Work happens on a local branch in the clone. Nothing is pushed.
2. You approve.
3. Fork the repository. Keep the original as a read-only upstream.
4. Rebase onto the current upstream, because it moves daily.
5. Run the repository's own lint script, which is the only gate that exists.
6. Push the branch to the fork and open a small pull request.

One prerequisite that is not technical. The three files this work touches, the
header, the footer and the stylesheet, are shared with the other developer, who
is committing to that repository daily. The seam needs agreeing with him before
the pull request lands, or we will be rewriting each other's work. The evidence
says the seam is clean, those are among the least-touched files in the
repository, but the agreement still has to be made rather than assumed.

---

## 9. What I need from you before I start

Blocking:

1. **The vector logo**, or approval to ship the PNG for now.
2. **The twelve orphan destinations.** Yes or no to the table in section 3.
3. **Opening hours and confirmation of the three addresses.**

Shapes the work, but I can start without:

4. **The menu model.** Keep sub-links always visible on desktop, as I propose, or
   collapse them into disclosures?
5. **One small JavaScript file, or none.** The only thing that genuinely needs it
   is the larger-text control, which is the single most valuable interaction on
   the site for this audience and cannot be done in CSS. Navigation does not need
   it.
6. **Social links.** Instagram, Facebook and YouTube are in the footer today. The
   live site also has LinkedIn and TikTok. Include them?
7. **Button case.** Leadership mandated all caps on 9 July. The August benchmark
   argues caps measurably slow reading for older readers and proposes sentence
   case. Those two directives contradict each other, it has never been resolved,
   and the chrome buttons are the first place it bites.
8. **Has the seam been agreed with the other developer?**

---

## 10. What this piece is not

It does not include the dog card, the dog page, the homepage, the adopt page or
any content page body. It does not include the settings table, though it is
written so that table drops in cleanly. It does not fix the defects listed in the
main plan, including adopted dogs returning 404, the homepage bypassing the image
pipeline, or the unfiltered biography. Those are separate, and some of them are
more urgent than this one.

I am starting with the chrome because you asked for it, and because it is the
right first move anyway: it appears on every page, it forces the token layer into
existence, and it is the least contended set of files in a repository somebody
else is actively working in.
