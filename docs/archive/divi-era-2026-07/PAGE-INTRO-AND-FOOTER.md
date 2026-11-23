# The page intro band and the footer

What the old Divi site did, what we kept, what we changed, and how to use the
rebuilt versions.

Date: 2026-09-20. Built, not proposed. Local only, nothing pushed.

---

## 1. The page intro band

### What the old site did

Twenty of the Divi templates opened with the same component, called `.ph-split`
in the shared stylesheet. Copy on the left, one supporting photograph on the
right, both the same height, on a single horizontal row. It collapsed to one
column below 860px.

Inside the text column: a large serif title, a shorter serif line underneath
saying what the page was about, a lead paragraph, and the page's buttons.

Nineteen of the twenty put the text first and the photograph on the right. One,
the About page, reversed it.

### What it was for

It is the answer to the single biggest complaint on record about the legacy
site: the thing you came to do was buried in a paragraph. The band puts the
title, the purpose and the action above the explanation, so someone in a hurry
can act without reading and someone who wants the detail can scroll. Both are
served by the same layout.

### What we kept

The shape, because it works and because it is familiar to staff. Two columns,
text slightly the wider of the two, stretched to equal height, photograph on the
right by default with an occasional flip to the left.

### What we changed

- **Named it for what it is.** `.page-intro`, `.page-intro-text`,
  `.page-intro-media`, rather than `.ph-split`. The project's first rule is that
  readability beats cleverness, and nobody arriving at this codebase knows what
  `ph` stands for.
- **It is one PHP partial, not markup copied into twenty templates.** A page
  sets an array and requires the file. Changing the band changes it everywhere.
- **The photograph reserves its space.** Every image carries its real width and
  height, so the text below does not jump as the photo loads. The Divi version
  did not, and that was a measurable layout-shift cost on every page.
- **Reading order is preserved when the photo is on the left.** The flip is done
  by reordering the drawn columns, not the markup, so a screen reader and a
  keyboard still meet the heading first.
- **Two actions at most, and only one of them loud.** The second is outlined
  rather than filled. A page with two equally weighted buttons is a page that
  cannot say what it is for.
- **The phone number is a real action, not a footnote.** On Helping Paw and on
  the surrender page it sits next to the application as the second button,
  because a good share of the people who need those programs would rather talk
  to somebody, and some cannot complete a web form at all.
- **Dropped the italic blue sub-title** that used to sit beside the main title.
  It was already retired in the design system, and on a coloured band it was one
  of the measured contrast failures.

### How a page uses it

```php
$page_intro = [
    'title'     => 'Helping Paw',
    'narrative' => 'Keeping pets and their people together.',
    'lead'      => 'One short paragraph saying what this page is for.',
    'actions'   => [
        ['label' => 'Apply for Helping Paw', 'url' => '/...'],
        ['label' => 'Or call (831) 718-9122', 'url' => 'tel:+18317189122', 'style' => 'quiet'],
    ],
    'image'     => '/assets/img/helping-paw.jpg',
    'image_alt' => 'What is actually in the photograph.',
    'image_w'   => 800,
    'image_h'   => 600,
];

require_once $root . '/includes/header.php';
require $root . '/includes/page-intro.php';
```

Only `title` is required. Anything omitted is simply not printed, so a page with
no photograph yet still gets a proper opening. Add `'media_first' => true` to
draw the photograph on the left.

### Where it is live so far

| Page | Photo side | Actions |
|---|---|---|
| /helping-paw/ | Right | Apply, then the phone |
| /volunteer/ | Right | Start an application |
| /surrender/ | Right | Intake questionnaire, then the phone |
| /jobs/ | Left | None, the openings are the content |
| /benefit-shop/ | Right | Directions, then the phone |

Two of those pages had an em dash in the heading, which breaks the brand rule.
Both are fixed.

Where the page that hosts a form has not been built yet, the button links
straight to the Little Green Light form rather than to an address that does not
answer. The donate page already did this, so it is an established pattern here
rather than a new one.

### Measured

Checked in a browser, not assumed.

| Check | Desktop 1440 | Phone 375 |
|---|---|---|
| Layout | Two columns, photo right | Stacked |
| Columns finish level | Yes | n/a |
| Photo shape when stacked | n/a | 16:10 as specified |
| Button height | 52px | 52px |
| Horizontal scroll | None | None |
| Title size | 48px | 32px |

On the flipped page the photograph draws on the left while the heading remains
first in reading order.

---

## 2. The footer

### What the old site did

Three link columns (Adopt, Get Involved, About), a contact row with the phone,
email and the office address, then a bottom bar with the copyright, the
501(c)(3) line and the tax ID, plus privacy and terms links and the social
icons.

### What we changed

- **No facts are typed into the markup.** They live in `includes/site-info.php`
  and the footer asks for each one. This is the shape of the settings table the
  project already plans: when that table is built, the function reads from the
  database and no template changes.
- **All three locations, not one.** The Bauer Center, the veterinary clinic and
  the Benefit Shop each have an address, and each is somewhere a member of the
  public may turn up. Only the office appeared before.
- **All five social accounts.** Instagram, Facebook, TikTok, YouTube and
  LinkedIn. The new build had three. The full set was taken from the live site
  rather than chosen.
- **The phone is a tap-to-call link** and the largest thing in its block.
- **Every link points at a page that exists.** A footer is on every page, so one
  dead link in it is a dead link site-wide. New pages get added when they are
  built, not in advance.

### Still missing, on purpose

**Opening hours.** They are recorded nowhere in this project and nobody has
supplied them. The footer prints nothing rather than a guess that would send
somebody to a locked door. The fields exist and are empty, so filling them in is
a one-line edit per location.

---

## 3. What this does not cover

The header itself beyond the phone number, the navigation model, breadcrumbs,
and the logo. Those are in `docs/CHROME-REBUILD-PLAN.md` and are still waiting
on three decisions: the vector logo, the twelve navigation destinations that do
not yet exist as pages, and the opening hours above.

---

## 4. Which pages take the band, and which do not

Settled 2026-09-20 by walking all 21 public pages plus the homepage and the dog
page.

### Take the band (19 of 21)

With a photograph: about (flipped left), benefit-shop, donate, helping-paw,
jobs (flipped left), media, perpetual-care-program, surrender, videos,
volunteer.

Without one, because the page's own content is the imagery or no suitable
photograph exists yet: adopted, courtesy-listings, events, foster-needs,
privacy, process, resources, terms, testimonials.

The four listing pages are deliberate. A photograph in the band would compete
with the grid of dogs directly beneath it, which is the thing the visitor came
for. The two legal pages take a title only, with no narrative line and no lead.

### Do not take the band (the exceptions)

| Page | Why it is different |
|---|---|
| Homepage | It is the hero and the whole argument, not a section opening |
| /adopt/ | The dog grid, its counts and its filters are the page; an intro band pushes them below the fold |
| /volunteer-application/ | A form host. What it needs is the reassurance block above the form, not a band |
| /pets/&lt;slug&gt;/ | One dog. It wants a gallery and a fact panel, which is its own design |

### The design thinking, briefly

Every band was written to answer one question: what is the one job of this page,
and what is the single thing a visitor should be able to do about it without
reading a paragraph first. A few of those answers are worth recording because
they are judgements rather than layout:

- **Perpetual Care** offers a phone call rather than a form. People want to have
  that conversation with a person.
- **Foster needs** says who pays for the food and the vet in the band itself.
  That is the question that actually stops people fostering, and it was in
  paragraph four.
- **Events** promises that nobody will ask you to take a dog home. Fear of being
  talked into something is the commonest reason people do not come.
- **Help placing your dog** is titled that rather than "surrender", which is the
  internal word and the wrong one to meet somebody with.
- **Courtesy listings** leads by saying these dogs are not in POMDR's care,
  because getting that wrong wastes an enquiry.
- **How adopting works** says it takes about a week, which removes the commonest
  reason for putting off starting.

### Also fixed in this pass

Nine em dashes in body copy and three in headings, all replaced with the
punctuation the sentence actually wanted rather than a hyphen. The public pages
now contain none.

Two pages were showing their band photograph a second time further down. One
photograph per subject per page, so the duplicates were removed.
