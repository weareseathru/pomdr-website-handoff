# Design standards review, fall 2026

Written 2026-10-05 for Andrew. Two parts: an audit of the New-Build site
against the standards we set ourselves (New-Build `Design.md`, the body review
of 2026-09-29, the project's accessibility floors), and what a morning on
godly.design suggests is worth adopting for a senior-focused nonprofit site:
achievable, stable, and efficient, not fashionable for its own sake.

Everything measured below was measured on the site as it stands today
(commit `8012089` on Ronnie's main).

---

## 1. Where we stand against our own standards

| Standard | State | Evidence |
|---|---|---|
| Accessibility 100 on every page (Lighthouse) | Holding | 100 on every page audited this month: home, dogs, team, forms, videos |
| Nothing under 16px, 48px targets, 4.5:1 contrast | Holding | Measured and recorded per component in `Design.md` sections 3, 4d, 6 |
| Works without JavaScript | Holding | Menu, slideshow, finder, walls, videos all have a no-script path; tested each |
| Reduced motion respected | Holding | One block at the top of `site.css`; the slideshow waits for Play |
| One stylesheet, no framework, no build step | Holding | `site.css` is 138 KB, 32 KB over the wire; four small scripts |
| No third-party calls before the visitor acts | **Now holding** | Videos fixed today: posters until Play, then YouTube's no-cookie domain. LGL form frames remain, by necessity |
| Images prepared and under 300 KB | Holding | 108 JPEGs, all through `prepare-page-image.php` |
| One vertical rhythm | Holding | 64 / 32 / 12px measured down every page on 2026-09-29 |
| Two colours with meaning (blue finds a dog, purple cares) | Holding | `tone` on the band and the menu |
| Print | Partial | Three print rules exist; no page has been printed and checked |

**Gaps found in this audit, none of them visible yet to a visitor:**

1. **No social preview tags.** Nothing in `<head>` says what a page is when a
   link is shared on Facebook, Instagram, Messages or in Mailchimp: no
   `og:title`, `og:image`, `og:description`, `twitter:card`. POMDR lives on
   those channels, so every shared dog page today shows a bare link. The
   highest-value item in this review.
2. **No 404 page.** An unknown address on the static site gets Cloudflare's
   default. The dog and team pages already say "we could not find that" for a
   bad slug; the site needs the same page for everything else.
3. **The logo is a PNG** (27 KB, soft on a 2x screen). Known since September;
   the vector lives in POMDR's Drive.
4. **Only JPEG for our own images.** The dog photos come through Cloudflare
   as AVIF already; the 108 page images we ship are JPEG only. A WebP or AVIF
   beside each, chosen by `<picture>`, would roughly halve their weight.
5. **No `theme-color`, no web manifest.** Small, but a phone's browser chrome
   takes the brand colour from it, and "add to home screen" gets a name and an
   icon.
6. **`text-wrap: balance` is set once** (on one heading). Every display heading
   should have it, and `text-wrap: pretty` on body text ends the one-word last
   lines on the phone.
7. **Testing with people has not happened.** The menu tree test and the
   hallway test of the program pages are still owed, and no amount of design
   review replaces them.

## 2. What godly.design shows, and what to take from it

The gallery's trending work (fall 2026) is dominated by software and AI
products, but the patterns underneath are legible and most of them transfer.
What recurs:

- **One photograph, one sentence.** The strongest heroes (Superpower,
  Cloaked) are a single full-bleed photograph of a real person with one
  serif headline and one button. Ours already do this; keep resisting the urge
  to add.
- **Numbers as colour blocks.** Cloaked's "60+ million / 13+ million / 2.2+
  billion" in saturated orange tiles is the most-shared CTA on the site this
  week. Our numbers band is quiet on a tint. A **deep-colour variant** (white
  figures on purple or on the deep teal) for Donate and About would carry
  more weight, with the same real figures.
- **Editorial typography.** Big serif display with a small, plain sans for
  everything else is now the default register for anything that wants to feel
  trustworthy rather than techy. We are already there (Source Serif 4 and
  Source Sans 3). The refinement everyone has made: tighter letter-spacing on
  display sizes (-0.02em) and more size contrast between the headline and the
  lead.
- **Pill buttons, two tiers, never three.** Universal. Ours match.
- **Footers as sitemaps with a newsletter box.** Every footer on the page has
  the signup in the footer, not only on a page of its own. Ours has the
  mission and Donate; the Mailchimp signup belongs there too.
- **Restraint in motion.** Scroll-driven reveals are gone from the best work;
  what remains is one hero motion and hover lifts. Our paw trail and rise-in
  are gentle and reduced-motion safe, but we should keep them to the homepage
  and not spread them.
- **Soft "frosted" surfaces** (translucent panels with a blur) over
  photographs, exactly as our slideshow panel and controls do. Current.
- **Large rounded radii** (24 to 32px) on cards and media, consistent across
  a site. Ours are 16 and 28. Consistent is what matters; we are.
- **OG images designed as objects**, not screenshots: the gallery has a whole
  section for them. Every site there has one. See gap 1.
- **Light theme first.** Dark mode appears on product sites, almost never on
  consumer or care sites. Our choice (white, no dark mode) is the right one
  for a reader in their seventies, and the gallery does not argue otherwise.

**What not to take:** oversized display type (one site sets 120px headlines;
our audience's phone cannot hold it), horizontal scroll sections, cursor
effects, and anything that depends on a script to show content.

## 3. Standards to add, fall 2026

Each is stable in every browser this audience uses, costs little, and can be
checked in the gates.

1. **Social preview tags on every page.** `og:title` from the page title,
   `og:description` from `$page_description`, `og:image` from the band image
   or the dog's photo, `twitter:card: summary_large_image`, a `canonical`.
   One block in `header.php`, data the pages already carry. Plus **one
   designed default image** (logo, a dog, the tagline) for pages without a
   photograph.
2. **A 404 page** in the same voice as the dog page's "we could not find that
   dog": a short apology, the menu, the phone number, the dogs waiting.
   Written as `public/404.html` (static, since the public site cannot run
   PHP at request time) and wired through Cloudflare Pages' convention.
3. **A deep-colour numbers band variant** for the giving pages.
4. **Newsletter signup in the footer**, the LGL mailing-list form's address
   with a one-line promise ("Sweet stories, happy tails and good news").
5. **Typography refinements:** `text-wrap: balance` on all display headings,
   `text-wrap: pretty` on paragraphs, `letter-spacing: -0.02em` at the h1 and
   h2 sizes. Three lines of CSS.
6. **`theme-color` and a small web manifest**, so the phone's chrome goes
   brand blue and the home-screen icon is the paw.
7. **WebP beside every page JPEG**, produced by the same prepare script, served
   through `<picture>` with the JPEG as fallback. Halves image weight on the
   heavy pages (Donate, Volunteer, Fostering).
8. **A print check** of the five pages people print: a dog's page, Perpetual
   Care, Help placing your dog, Helping Paw, Contact. Hide the chrome, show
   link addresses, keep the phone number.
9. **The SVG logo**, when POMDR supplies the vector.
10. **Testing with people**, as planned: the menu tree test and the program
    page hallway test, five to eight older visitors.

Items 1, 2, 4, 5 and 6 are a short session together. Item 7 needs a small
addition to Ronnie's `prepare-page-image.php` or a sibling script, so it goes
to him as a proposal. Items 9 and 10 need POMDR.

## 4. What stays as it is

The decisions worth defending when the next gallery of inspiration comes
round: white backgrounds and no dark mode; 20px body text at a 68-character
measure; two colours with meaning; serif headings at regular weight; one
stylesheet; nothing that needs a script to show content; buttons above prose;
real photographs only; US English; no em dashes; and the no-tracking stance,
now extended to the videos.
