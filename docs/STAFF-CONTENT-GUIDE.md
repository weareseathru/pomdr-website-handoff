# Staff Content Guide: editing the site with no code

How POMDR staff add and edit the daily content. Everything here is done in the
WordPress admin (wp-admin). You never touch code, and the designed cards update
themselves.

Owner: Andrew Z. Last updated: 2026-06-29.

---

## What you can edit (and where)

| You want to | Go to | The site updates |
| --- | --- | --- |
| Add or edit a dog | **Pets** | The dog's card on `/adopt/`, the homepage row, and its profile |
| Add or edit a staff member | **Team** | The right group on `/about/` |
| Add or edit an event | **Events** | The events list and the homepage "Upcoming" |
| Run a promotion / fundraiser banner | **Promo Banner** (options page) | The banner below the homepage hero |

The header, footer, page layouts, and colors are managed by the developer and do
not need staff edits.

---

## Adding a dog (Pets)

1. wp-admin > **Pets** > **Add New**.
2. **Title** = the dog's name (for example, Pebble).
3. **Featured image** (top right) = the dog's main photo. This is the card photo.
4. Fill the fields:
   - **Status** (checkboxes, you can pick more than one): Adoptable, Foster
     Needed, Sponsor Needed, Adoption Pending, Adopted, Hospice, Courtesy Listing.
   - **Age**, **Weight**, **Sex**, **Looks like / breed**.
   - **Description**: a warm, personality-forward blurb.
   - **Gallery**: more photos for the profile page.
5. **Publish** (or **Update**).

Copy rules for dogs (keep the voice consistent):
- Write approximate age as **~age** with a tilde and no space (for example,
  `~12 yrs`). Never "estimated", "about", or "approximately".
- Do not start the blurb with "Meet [Name],". Do not end with a question.
- No em dashes; use commas, periods, or parentheses.

When a dog is adopted, set its status to **Adopted**; it moves to the happy-tail
treatment automatically.

---

## Adding a staff member (Team)

1. wp-admin > **Team** > **Add New**.
2. **Title** = the person's name.
3. **Featured image** = a clear headshot (square works best; it is shown as a
   round photo). No photo is fine; the card shows their initials.
4. Fields: **Title/role** (for example, Executive Director), **Group** (Board of
   Directors, Office Staff, Advisory Council, Clinic Staff, Benefit Shop Staff),
   and a short **bio**.
5. **Publish**.

They appear in the matching section on `/about/`.

---

## Adding an event (Events)

1. wp-admin > **Events** > **Add New**.
2. **Title**, then the **Event Start** (and optional end), an optional **Event
   Type** label, and a short **Event Details** blurb. Each field has a plain hint
   under it, so you can just follow along.
3. **Featured image** (right sidebar) = the event photo. Any size works; it is
   shown in full inside a neat frame, so a landscape photo or an upright flyer
   both look good.
4. **Publish**. It shows on the events page, soonest first. **Past events drop
   off on their own**, so there is nothing to remove later.

---

## The promo banner (campaigns)

1. wp-admin > **Promo Banner** (an options page in the admin menu).
2. Toggle it **on**, set the **label**, **message**, **button text + link**, and
   optional **start/end dates**.
3. Save. It shows below the homepage hero, and hides itself when off or outside
   the date range.

(One-time setup by the developer: create the 8 promo-banner fields on this options
page. After that it is all yours.)

---

## Photo tips (so cards look great)

- Use **real, well-lit photos** of the actual dog or person. We never use AI dog
  images.
- **Landscape** photos work best for dog cards; **square or head-on** for staff.
- Aim for a reasonably large file (at least ~1200px wide); WordPress makes the
  smaller sizes automatically. Avoid tiny or blurry images.
- Set the dog photo as the **Featured image**, not just inside the description.
- Add **alt text** when WordPress prompts (a short description of the photo) for
  accessibility.

---

## What to prepare for the launch

- Current **dogs** with photos, statuses, and blurbs.
- **Staff** headshots, roles, and short bios for each group.
- Upcoming **events**.
- Real **testimonials** (to replace the sample quotes), **YouTube links** for the
  videos row, and current **impact numbers**.

If you can gather the photos and copy, the site fills in automatically. Ask Andrew
for the wp-admin login and which environment (mirror or live) to load them into.
