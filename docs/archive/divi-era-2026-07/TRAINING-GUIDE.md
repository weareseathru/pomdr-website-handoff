# POMDR Website Training and Onboarding Guide

A friendly, start-from-zero manual for anyone who will look after the Peace of
Mind Dog Rescue (POMDR) website. You do not need any coding or web experience.
If you can fill out an online form and drag a photo onto a screen, you can run
this site.

This guide assumes you have never seen the site or WordPress before. Read it
top to bottom once. After that, keep it open as a reference and jump to the
section you need.

Owner: Andrew Z. Audience: POMDR staff and volunteers who manage content.

---

## Table of contents

1. [Welcome: what this site is and who it serves](#1-welcome)
2. [The big picture: how the site is built](#2-the-big-picture)
3. [Logging in and a tour of the admin menus](#3-logging-in-and-the-admin-menus)
4. [Posting a dog, step by step](#4-posting-a-dog)
5. [Posting an event, step by step](#5-posting-an-event)
6. [Posting or editing a team member](#6-posting-a-team-member)
7. [The Promo Banner (announcements and fundraisers)](#7-the-promo-banner)
8. [Image guidance, all in one place](#8-image-guidance)
9. [Brand: colors, fonts, and the POMDR voice](#9-brand-colors-fonts-and-voice)
10. [Where all the buttons and levers are](#10-where-the-buttons-and-levers-are)
11. [Best practices and pre-publish checklists](#11-best-practices-and-checklists)
12. [Things to keep an eye on, and who to call](#12-things-to-keep-an-eye-on)
13. [Glossary](#13-glossary)

---

## 1. Welcome

### What this website is for

The POMDR website is how people find our dogs and decide to help. It exists to
make four things easy: **adopt, foster, donate, and volunteer.** Every page has
one clear job and puts the main action up front as a big, easy-to-tap button, so
a busy visitor can act in one tap and a curious visitor can still read the whole
story.

Your job as a content manager is the warm, human part: keep the dogs, events,
and people on the site current, with real photos and honest, kind writing. The
design takes care of itself. When you add a dog in the admin, its card appears
on the site already styled and laid out. You are filling in the story, not
building the page.

### Who we serve

POMDR was founded in **2009** and focuses on **senior dogs and senior people.**
We place older dogs, and through our Helping Paw program we help senior owners
keep the dogs they love. That shapes everything about the site: the audience
skews older, so the type is large, the buttons are big, the contrast is strong,
and the writing is calm and clear. Keep that person in mind. Picture a kind
70-year-old on a tablet, maybe a little worried, definitely a dog lover.

### The org facts (safe to reuse anywhere)

| Item | Value |
| --- | --- |
| Full name | Peace of Mind Dog Rescue (POMDR) |
| Founded | 2009 |
| Focus | Senior dogs and senior people |
| Tax ID (EIN) | 27-1154816 |
| Main address | Patricia J. Bauer Center, 615 Forest Ave, Pacific Grove, CA |
| Vet clinic | 1251 10th St, Monterey, CA |
| Benefit Shop | 223 Grand Ave, Pacific Grove, CA |
| General email | info@pomdr.org |
| Phone | (831) 718-9122 |
| Website | https://www.pomdr.org |

These are correct and reusable in emails, flyers, and social posts. If any of
them ever change (a new phone number, a new address), that is a job for the
developer, not a content edit, because they are built into the site in several
places.

---

## 2. The big picture

You do not need to understand the plumbing to run the site. But a simple mental
model makes everything else click into place, so here it is in plain language.

### The layers, from the ground up

- **WordPress** is the software the whole site runs on. It is the most widely
  used website platform in the world. You will spend your time in its admin
  area, called **wp-admin.** Think of WordPress as the filing cabinet and the
  printing press combined: you put content in, it publishes pages out.

- **Divi** is a design toolkit that sits on top of WordPress. It is the "parent
  theme." You will almost never touch it directly.

- **POMDR 2026** is our **child theme.** A child theme is a layer that sits on
  top of Divi and adds our custom look, our page designs, and the special
  boxes that turn dog information into pretty cards. All of our design lives
  here. It was built so that staff can edit content without ever editing code.

You can picture it as three stacked sheets of glass. WordPress on the bottom,
Divi in the middle, POMDR 2026 on top. You look through all three and see the
finished, branded website.

### Four plain-English definitions you will meet

These four words come up constantly. Here is what each one means, once.

- **CPT (Custom Post Type).** A CPT is just a special kind of entry with its own
  admin screen and its own set of fields. Our site has three: **Dogs** (called
  "Pets" behind the scenes), **Events,** and **Team.** A blog uses "Posts" for
  everything. We use a dedicated CPT for dogs so a dog has fields like age and
  weight, which a blog post would not.

- **ACF field (Advanced Custom Fields).** These are the extra labeled boxes you
  fill in on a dog, event, or team member. "Age," "Weight," "Status," "Looks
  like," and "Description" are all ACF fields. They are how structured
  information (not just free text) gets stored, so the site can lay it out
  neatly and consistently every time.

- **Featured image.** Every dog, event, and team member has one main photo,
  called the **Featured image.** It lives in a box in the top right of the edit
  screen. This is the photo that becomes the big card image. It is separate from
  any photos you drop into the description or the gallery. If you remember one
  thing: **the Featured image is the card photo.**

- **Shortcode.** A shortcode is a little tag in square brackets, like
  `[pet_home]` or `[events]`, that the developer has placed on a page. It is a
  placeholder that says "insert the live list of dogs here" or "insert the
  events here." You will almost never type one. They matter because they are the
  reason your edits appear automatically: the page holds a shortcode, and the
  shortcode pulls in whatever dogs or events currently exist. You edit the dog,
  the shortcode reshows it. Nothing on the page needs to be re-laid-out by hand.

### The one idea to hold onto

**You edit content in wp-admin. The designed cards on the public site update
themselves.** Add a dog, and its card shows up on the adopt page and the
homepage row, correctly styled, with its status badge and its photo, without
anyone touching a page layout. That is the whole point of how this was built.

---

## 3. Logging in and the admin menus

### How to log in

1. In your web browser, go to the site address followed by `/wp-admin` (for
   example, `https://www.pomdr.org/wp-admin`). Andrew will give you the exact
   address and confirm **which site to use** (see "mirror vs live" below).
2. Enter the **username and password** Andrew set up for you. Do not share them.
3. You land on the **Dashboard.** The dark menu down the left side is how you get
   everywhere.

If you ever get locked out, or a login link expires, that is a call to Andrew,
not something to troubleshoot on your own.

### Mirror vs live (important)

There are usually two copies of the site:

- A **mirror** (also called local or staging): a private practice copy. Safe to
  experiment in. Changes here do not show to the public.
- The **live** (production) site: the real one the public sees.

Before you add real content, **ask Andrew which one you are logged into.** When
in doubt, assume you are on the live site and behave accordingly. There is more
on this in the glossary.

### The menus you will actually use

Depending on your account, the left menu may look full (an administrator) or
tidy and short (a **POMDR Content Editor,** see the next note). Either way, these
are the items that matter to you:

| Menu item | What it is for |
| --- | --- |
| **Dogs** (shows as "Pets" for admins) | Add and edit adoptable dogs. This is where you will spend most of your time. |
| **Events** | Add and edit events. |
| **Team** | Add and edit staff, board, and volunteers shown on the About page. |
| **Media** | The photo library. Every image you upload lives here. You can upload here directly or, more often, while editing a dog. |
| **Promo Banner** | An options page for the announcement bar on the homepage (campaigns and fundraisers). See section 7. |

### A note on the "POMDR Content Editor" role

Most staff and volunteers get an account with the **POMDR Content Editor** role.
It is a deliberately simplified, safety-first account. When you log in with it:

- The **Pets** menu is relabeled **Dogs,** to keep things friendly.
- You can fully add, edit, and delete **Dogs, Events, and Team members,** and
  upload **Media.** That is your whole job, and you have full control of it.
- Everything else is hidden: no Plugins, no Themes, no Appearance, no Users, no
  Settings, no Divi builder, no blog Posts, no Comments, no Tools. You cannot
  reach the parts of the site that would let you break the design or the code,
  because your account simply does not have keys to those rooms.
- A short **welcome box** on your dashboard links back to the staff guide.

This is on purpose. You should feel free to click around your menus without
worry. If a screen is not in your menu, it is not your job and you cannot harm
it.

---

## 4. Posting a dog

This is the heart of the site. Take your time here. A good dog listing needs one
great photo, an honest status, a few numbers, and a warm few sentences.

### The quick version

1. Go to **Dogs** (or **Pets**) and click **Add New.**
2. **Title** at the very top: the dog's **name.** That is all the title is.
3. **Featured image** (top-right box): the dog's main photo. This is the card
   photo. Click "Set featured image," upload or choose a photo, add alt text,
   and set it.
4. Fill in the fields below the title: **Status, Sex, Looks like, Age, Weight,**
   and the **Description.**
5. Add extra photos to the **Photo Gallery** if you have them, and a **YouTube
   Video** link if there is one.
6. Click **Publish** (or **Update** if you are editing an existing dog).

That is it. The card appears on the site, styled for you.

### The fields, explained

- **Title (the name).** Just the dog's name, like `Pebble`. Nothing else. The
  name is required. If you leave it blank, the site will kindly stop you and ask
  for a name before it saves.

- **Featured image (the card photo).** The single most important choice. This is
  a **required** photo: the site will not let you publish a dog without one,
  because an empty card looks broken. Use a real, landscape (wider than tall),
  well-lit photo. More on photos in section 8.

- **Status** (checkboxes, you can tick more than one). This controls where the
  dog shows up and what colored badge appears on the card. The exact choices and
  what each does are in the table below. This is the field that most changes what
  visitors see, so get it right.

- **Sex** (Male / Female checkboxes).

- **Looks like** (the breed, in plain words). This is a "looks like" field on
  purpose, because most rescue dogs are lovely mixes. Write what they resemble,
  like `Terrier mix` or `Chihuahua blend`. It shows on the card and profile.

- **Age (yrs).** Type a **number only,** like `12`. The site automatically prints
  it as `~12 yrs` with the tilde, which is our way of saying the age is
  approximate. You do not type the tilde or the word "years" here. Age must be a
  number between 0 and 25, or the site will gently ask you to fix it. Any dog
  aged 10 or older automatically gets a small "Senior" tag on the card.

- **Weight (lbs).** A **number only,** like `18`. Must be between 1 and 250.

- **Description** (the personality blurb). This is **required.** A few warm,
  honest sentences about who this dog is. This is the copy that makes someone
  fall in love, so it matters most. Follow the voice rules below and in section
  9.

- **Photo Gallery.** Extra photos for the dog's profile page. Add as many good
  ones as you have. These do not replace the Featured image; they add to it.

- **YouTube Video.** If there is a video of the dog, paste the YouTube link here
  and it will embed on the profile. Optional.

- **Foster Start Date / Foster End Date.** Only fill these in if the dog needs a
  foster for a specific window (for example, while a foster is on vacation). When
  set, the foster badge shows the dates. Leave blank otherwise.

- **Date Adopted.** When a dog is adopted, set this date. It powers the "recently
  adopted" happy-tail treatment for the first two weeks after adoption.

- **Feature** (Yes / No). Set to **Yes** to pin this dog toward the front of the
  homepage row and the top of the adopt listing. Use it sparingly for the dogs
  you most want seen. Most dogs stay "No."

- **Sponsored By.** If a donor sponsors this dog's care, their name or message
  can go here. Optional.

### The Status choices and exactly what each one does

Status is a checkbox list, so a dog can hold more than one at once (for example,
**Hospice** plus **Sponsor Needed**). If two are ticked, the card shows the most
urgent badge automatically, in this order of priority: Adoption Pending, then
Foster Needed, then Hospice, then Sponsor Needed, then Courtesy Listing, then
Adopted, then Adoptable.

| Status (tick the box) | Badge on card | What it does on the public site |
| --- | --- | --- |
| **Adoptable** | Available | The main one. The dog appears on the Adopt page and in the homepage featured row, ready to meet. |
| **Foster Needed** | Foster Needed | Flags the dog as needing a foster home and lists it where fosters are surfaced. If you set foster start and end dates, the badge shows those dates. |
| **Sponsor Needed** | Sponsor Needed | Marks the dog as needing sponsorship to fund their care while they wait. A sponsor section can show on the profile. |
| **Adoption Pending** | Adoption Pending | An application is in its final stages. The dog stays visible but is clearly marked as spoken for. |
| **Adopted** | Adopted | The happy ending. The dog moves out of the adoptable listings into the happy-tail treatment. Set the Date Adopted too, so it counts as "recently adopted" for two weeks. |
| **Hospice** | Hospice | A sanctuary-care dog living out their days with us. Not adoptable. Shows in the hospice listing. |
| **Courtesy Listing** | Courtesy Listing | Listed on behalf of another rescue or an owner, not a standard POMDR adoption. |

**Tip on adoption:** when a dog gets adopted, tick **Adopted** (you can untick
Adoptable), add the **Date Adopted,** and Update. The site handles the rest,
moving them into the happy-tail area on its own.

### Copy rules for dog descriptions (the short version)

These are POMDR's brand rules. The full explanation and examples are in section
9, but here they are in one place while you write:

- **No em dashes, ever.** Use a comma, a period, or parentheses instead.
- **Do not open with "Meet [the dog's name],".** Lead with the dog's story or
  personality instead.
- **Do not end on a question.** End on a warm, confident statement.
- **Write approximate age as `~12 yrs`** (tilde, no space). Never "estimated,"
  "about," or "approximately."
- Keep it concise, warm, and personality-forward. Natural emoji are fine when
  they fit. Do not stuff them.

### What breaks if you leave a field blank

The site is forgiving, and it will stop you before anything looks broken:

| If this is blank | What happens |
| --- | --- |
| Name (title) | The site will not save, and asks you to add a name. |
| Featured image | The site will not save, and asks you to add a photo, so no dog ever shows an empty card. |
| Description | Required by the field, so you will be asked to fill it in. |
| Status | If nothing is ticked, the dog may not appear in any listing. Always tick at least one status (usually Adoptable). |
| Age, Weight, Looks like, Sex | The card simply leaves that detail off. Nothing breaks, but the card is more useful when they are filled in. |
| Off-list status value | The site only accepts the seven listed statuses. It will refuse anything else, which keeps the badges consistent. |

---

## 5. Posting an event

Events are quick. Title, when, what, and a photo.

### Steps

1. Go to **Events** and click **Add New.**
2. **Title:** the event name, like `Adoption Day at the Bauer Center`.
3. **Event Start:** use the date-and-time picker to set the day and start time.
   This is **required.** If you leave it blank, the site will ask for it, because
   an event with no date cannot appear on the calendar.
4. **Event End:** set the end **time** (the end is a time on the same day).
5. **Event Type:** choose from the dropdown. The default and standard choice is
   **Special Event,** which is what shows in the main events list. The other
   types (Adoption Event, Perpetual Event) are for special uses; if you are not
   sure, leave it on Special Event.
6. **Event Details:** a short description of the event.
7. **Featured image** (top-right box): the event photo. Set it just like a dog's
   photo. Any shape works here; the event card shows the whole image, so a
   poster or flyer image is fine. (There is also an "Event Image" field for other
   uses, but the card uses the Featured image.)
8. Click **Publish.**

### How events behave on the site

- Events **sort by date automatically,** soonest first.
- **Past events drop off the upcoming list on their own.** If you set a start
  date that has already passed, the site shows you a friendly heads-up that it
  will not appear in the upcoming list. That is fine if you meant to log a past
  event, but it is your cue to double-check the date if you did not.
- You do not need to delete old events one by one to keep the list clean. That
  said, tidying up now and then is good housekeeping.

---

## 6. Posting a team member

Team members are the people on the About page: the board, office staff, advisory
council, clinic staff, and benefit shop staff.

### Steps

1. Go to **Team** and click **Add New.**
2. **Title:** the **person's name.**
3. **Featured image:** a clear headshot. **Square works best,** because it is
   shown as a round photo. If you do not have a photo, that is okay: the card
   shows the person's initials in a colored circle instead.
4. Fill in the fields:
   - **Title (role):** their role, like `Executive Director` or `Volunteer`.
   - **Group** (checkboxes): pick the section they belong in. Choices are **Board
     of Directors, Office Staff, Advisory Council, Clinic Staff,** and **Benefit
     Shop Staff.** This decides which part of the About page they appear in.
   - **Bio:** a short paragraph about them.
   - **Sort:** an optional number that controls their order within the group
     (lower numbers appear first). Leave it blank if you do not care about order.
5. Click **Publish.**

They appear in the matching section on the About page, ordered by the Sort
number.

---

## 7. The Promo Banner

The Promo Banner is the announcement strip that can appear just below the big
image at the top of the homepage. It is perfect for a campaign, a fundraiser, or
a timely message (for example, a senior-adoption fee waiver, or a matching-gift
drive).

### How to run it

1. Go to **Promo Banner** in the admin menu. It is an options page, not a post,
   so there is one of it, and your edits change the single banner.
2. Set it up:
   - **Toggle it on** (or off).
   - **Label:** a short kicker, like `This month`.
   - **Message:** the announcement text.
   - **Button text** and **button link:** the call to action, for example
     `Donate now` pointing at the donation page.
   - **Start date** and **end date** (optional): the window it should show.
3. **Save.**

### How it date-gates itself

The banner is smart about timing. It only shows when it is **toggled on** and, if
you set dates, only **between the start and end dates.** Outside that window, or
when toggled off, it hides itself. So you can set up a fundraiser banner in
advance with a start and end date, and it will appear and disappear on schedule
without anyone remembering to turn it off.

> One-time note: the developer sets up the banner's option fields once. After
> that, the banner is entirely yours to run.

---

## 8. Image guidance

Great photos are the single biggest thing you control. Here is exactly what each
area wants, in one reference.

### What each area needs

| Area | Which image | Shape and size | Notes |
| --- | --- | --- | --- |
| **Dog card and profile** | The dog's **Featured image** | **Landscape** (wider than tall), at least ~1200px wide | This is the card photo. Well-lit, in focus, the dog clearly the subject. |
| **Dog profile extras** | **Photo Gallery** | Any good shape, larger is better | Add several. These build out the profile page. |
| **Team member** | The **Featured image** (headshot) | **Square** or head-on | Shown as a round photo. Face centered. No photo is fine (shows initials). |
| **Event** | The **Featured image** | Any shape, at least ~1000px wide | Shown whole, so posters and flyers work. |
| **Homepage hero, page banners, section art** | Managed by the developer | n/a | You do not need to touch these. |

### General photo rules

- **Real photos only.** Every dog and person photo is a real one, taken by a
  volunteer or staff member. **We never use AI-generated images of dogs.** Ever.
- **Aim large.** Upload something at least around 1200px wide when you can.
  WordPress makes the smaller sizes automatically. It cannot make a small or
  blurry photo bigger, so start with a good original.
- **Well-lit and in focus.** Natural light, dog or person as the clear subject,
  not too far away.
- Set the dog or event photo as the **Featured image,** not only inside the
  description text. The card reads the Featured image.

### Alt text (please do this)

When WordPress prompts for **alt text** on an image, add a short, plain
description of what is in the photo, like `A gray-muzzled terrier sitting on a
green lawn`. Alt text is read aloud by screen readers and shown if an image fails
to load. It is a small kindness that makes the site work for people who cannot
see the photo, and our audience especially benefits. A sentence is plenty.

---

## 9. Brand: colors, fonts, and voice

The look and feel of POMDR is warm, calm, and trustworthy. You do not set colors
or fonts yourself (the design handles that), but knowing the brand helps you
write and choose photos that fit.

### Colors

POMDR's brand palette is built on two families, teal and purple, on white
backgrounds. These are the official brand colors:

| Color | Hex | Where it is used |
| --- | --- | --- |
| Teal primary | `#0099A8` | The main brand color for accents and highlights |
| Teal accent | `#008DAF` | A supporting teal |
| Teal accent | `#008BB0` | A supporting teal |
| Purple primary | `#5B2C6F` | The second brand color |
| Purple accent | `#632F88` | A supporting purple |
| White | white | The default background. The site stays light and open, not dark. |

On the redesigned site these settle into a simple **two-color working system:** a
**blue** for "available" and adoptable dogs and for primary action buttons, and
**purple** as the second brand color (used for foster and other accents), all on
warm, light neutrals. Buttons use a slightly deeper blue so the white text on
them is easy to read. You do not need to memorize the hex codes. Just know the
site is intentionally a calm blue-and-purple world on white, and if you are ever
picking a graphic or a flyer image, lean warm and simple to match.

If you ever feel a new color is needed (say, for a special campaign), that is a
conversation with the developer, who will check it against the palette and for
readability before it goes on the site.

### Fonts

The brand font is **Myriad Pro.** On the web we fall back to a free, friendly
sans-serif (Source Sans 3 or Open Sans) that looks very close to it. Body text is
never smaller than 16px, and the design base is a comfortable 17px, because our
readers value large, clear type. The fonts are set by the theme; you do not
choose them when you write.

### The POMDR voice (these are the rules)

Every word on the site follows these rules. They come straight from the project
charter and they are not optional. When you write a dog description, an event
blurb, or a bio, write like this:

- **No em dashes. Ever.** Not in copy, not in alt text. Use commas, periods, or
  parentheses instead.
- **Do not lead with "Meet [the dog's name],"** in dog copy. Start with the dog's
  personality or story.
- **Do not close with a question.** End on a warm, confident statement, not
  "could this be your dog" style endings.
- **Use `~age`** (a tilde, no space) when an age is approximate, like `~12 yrs`.
  Never "estimated," "about," or "approximately."
- **Concise, personality-forward, and warm.** Natural emoji use is fine when it
  fits the voice. No emoji stuffing.
- When a human author is credited, the sign-off is **Andrew Z.**

### Good and not-so-good dog blurbs

Here are examples so the voice is concrete. The names are made up for teaching.

**Good (Pebble):**

> Pebble is a ~12 yrs terrier mix with strong opinions about breakfast and a soft
> spot for sunny windowsills. She walks nicely on leash, settles fast in a quiet
> home, and would happily nap the afternoon away beside her person. A calm house
> and a gentle routine, and she is yours. 🐾

Why it works: it opens with personality, uses the `~12 yrs` format, stays warm
and specific, and ends on a confident statement.

**Good (Biscuit):**

> Biscuit arrived a little unsure, and these days he leans into every ear scratch
> like he invented them. He is ~9 yrs, easy with other calm dogs, and does best
> where the days are predictable. A patient home gets a loyal, velvet-eared
> shadow in return.

Why it works: honest about his start, specific about his needs, no clichés, no
closing question.

**What not to do** (this is the anti-pattern, written with a placeholder so we
are not publishing it):

> Opens with "Meet [dog name],". States the age as "estimated 12 years." Joins
> two thoughts with a long dash. Ends on a question like "is he the one for you?"

Each of those four is a rule break: the introduction opener, the word
"estimated" (use `~12 yrs`), the long dash (use a comma or a period), and the
closing question (use a statement). Fix all four and you are back to the good
examples above.

---

## 10. Where the buttons and levers are

A quick map of what you control versus what the developer controls. The short
version: **you own the content, the developer owns the container.**

### You control (from the admin, no code)

- **Dogs (Pets):** every adoptable dog, its photo, status, details, and story.
- **Events:** the event list.
- **Team:** the people on the About page.
- **Media:** the photo library.
- **Promo Banner:** the homepage announcement strip.

Editing any of these updates the live cards and lists on the site automatically.

### The developer controls (not a content edit)

- The **header and navigation menu** at the top of every page.
- The **footer** at the bottom.
- **Page layouts and templates** (how the About page, the Adopt page, and the
  homepage are arranged).
- **Colors, fonts, and design tokens.**
- **Animations and interactive behavior.**
- The **Adopt page search, filters, and sorting.**
- **Forms** (the adoption and donation forms) and where their buttons point.
- **Redirects** (making old web links still work).
- The per-dog **profile logic** on the single dog page.

### You cannot break the core site from the content screens

This is worth saying plainly. From the Dogs, Events, Team, Media, and Promo
Banner screens, **there is no button that damages the site's design or code.**
The POMDR Content Editor account does not even have access to the parts that
could. The worst that happens is a card looks a little empty or a dog is in the
wrong list, and both are fixed by editing that dog and saving again. So explore,
add, and edit with confidence. If something looks wrong and re-saving does not
fix it, that is when you call the developer.

---

## 11. Best practices and checklists

### Everyday good habits

- **Preview before you publish.** WordPress has a Preview button. Use it to see
  the card the way visitors will.
- **One dog, one clear photo.** The Featured image does the heavy lifting.
- **Keep statuses honest and current.** An adopted dog still marked Adoptable
  confuses visitors and staff. Update it the day it changes.
- **Write for reuse.** Site copy often feeds our emails and social posts, so a
  good blurb does double duty.
- **When unsure, save as Draft.** A draft is not public. You can come back to it.

### Pre-publish checklist: a dog

Before you hit Publish or Update on a dog, check:

- [ ] Title is the dog's **name** only.
- [ ] **Featured image** is set, landscape, real, well-lit, and at least ~1200px.
- [ ] **Alt text** added to the photo.
- [ ] **Status** is ticked correctly (usually Adoptable; update the day it
      changes).
- [ ] **Age** is a plain number, and reads as `~N yrs` on the preview.
- [ ] **Weight, Sex, Looks like** are filled in.
- [ ] **Description** is warm and specific, and follows the voice rules: no em
      dashes, no "Meet [name]," opener, no closing question, `~age` format.
- [ ] **Gallery** photos added if you have them.
- [ ] Previewed the card. It looks right.

### Pre-publish checklist: an event

- [ ] **Title** is clear and specific.
- [ ] **Event Start** date and time are set (required).
- [ ] **Event End** time is set.
- [ ] **Event Type** chosen (Special Event is the standard).
- [ ] **Details** written.
- [ ] **Featured image** set (the event photo).
- [ ] The start date is **in the future** (unless you are logging a past event on
      purpose).

---

## 12. Things to keep an eye on

### Accessibility basics (our readers count on these)

Our audience skews older, so accessibility is not a nice-to-have, it is the
mission. Three simple things are in your hands:

- **The Larger Text button.** The public site has a "Larger text" toggle so
  visitors can bump up the type. Know it is there so you can point people to it.
  The design already uses large, high-contrast text by default.
- **Alt text on every photo.** As covered in section 8. A short description on
  each image.
- **Real photos, honest words.** No AI dog images, no clickbait, no hover-only
  tricks. Status is always shown as a labeled badge (text plus color), never
  color alone, so it reads for everyone.

### Keep the site current

- **Update dog statuses promptly.** Adopted, Adoption Pending, Foster Needed:
  change these the day the situation changes.
- **Keep events fresh.** Past events fall off on their own, but add new ones in
  good time so the upcoming list is never empty.
- **Retire the Promo Banner** when a campaign ends (or set its end date up front
  so it retires itself).

### When to call the developer (Andrew Z.)

Reach out to Andrew for anything that is not a content edit, including:

- Anything about the **header, footer, menus, page layout, colors, or fonts.**
- A **form that will not submit,** or a donation or adoption button pointing at
  the wrong place.
- Needing a **brand-new page,** or a new section that does not exist yet.
- **Login trouble,** a locked account, or a new staff account.
- Anything involving **plugins, themes, settings, or code.**
- A card or list that looks wrong and does **not** fix itself when you re-save.
- Broken links from old pages (redirects).

Contacts:

- Developer: **Andrew Z.** (apzielinski62@gmail.com)
- POMDR general: info@pomdr.org, (831) 718-9122

When you write to Andrew, a screenshot and the dog or event name save a lot of
back and forth.

---

## 13. Glossary

- **WordPress:** the software the site runs on. You work inside its admin area.
- **wp-admin:** the admin area of WordPress. You reach it at the site address
  plus `/wp-admin`. This is your workshop.
- **Dashboard:** the first screen you see after logging in.
- **Divi:** the design toolkit (the "parent theme") the site is built on. You do
  not edit it.
- **POMDR 2026:** our **child theme,** the layer that adds POMDR's custom look
  and the dog, event, and team card designs on top of Divi.
- **Child theme:** a design layer on top of a parent theme, so custom work can be
  done without editing the parent.
- **CPT (Custom Post Type):** a special kind of entry with its own screen and
  fields. Ours are Dogs (Pets), Events, and Team.
- **ACF (Advanced Custom Fields):** the plugin that provides the labeled boxes
  (Age, Weight, Status, and so on) you fill in on a dog, event, or team member.
- **ACF field:** one of those boxes.
- **Featured image:** the single main photo of a dog, event, or team member, set
  in the top-right box. It becomes the card photo.
- **Gallery:** the extra photos on a dog's profile (an ACF field, separate from
  the Featured image).
- **Shortcode:** a small tag in square brackets (like `[pet_home]`) the developer
  places on a page to insert a live list of dogs or events. You rarely touch one.
- **Status:** the checkbox field on a dog that sets where it appears and which
  badge shows (Adoptable, Foster Needed, Sponsor Needed, Adoption Pending,
  Adopted, Hospice, Courtesy Listing).
- **Options page:** a single admin screen for site-wide settings, like the Promo
  Banner (there is one of it, not many).
- **Media Library:** where all uploaded images live.
- **Publish / Update / Draft:** Publish makes an entry live, Update saves changes
  to a live entry, Draft saves privately without showing the public.
- **Role (POMDR Content Editor):** the simplified, safe account most staff use.
  It can edit Dogs, Events, Team, and Media, and nothing else.
- **Mirror (local / staging):** a private practice copy of the site. Changes here
  are not public.
- **Production (live):** the real, public site.
- **Slug:** the short, lowercase text in a page's web address after the domain
  (for example, the `adopt` in `pomdr.org/adopt`).
- **Permalink:** the full web address of a page or dog profile.
- **Alt text:** a short written description of an image, read aloud by screen
  readers and shown if the image fails to load.

---

Welcome aboard, and thank you for helping these dogs find their people. If this
guide does not answer something, that is a good sign it should, so tell Andrew
and it will get added.

_Owner: Andrew Z. Companion to the shorter `docs/STAFF-CONTENT-GUIDE.md`._
