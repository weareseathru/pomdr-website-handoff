# VOICE.md

POMDR brand voice rules. These govern every piece of editorial copy on the
new site, every block placeholder, every email sourced from a site post,
every print ad generated from site content.

Authored 2026-05-09 from two sources:

1. The global `CLAUDE.md` voice rules at the Antigravity working dir
   (project-wide, version-controlled, last updated 2026-04-16).
2. A copy audit of the design's React data file
   (`pomdr-website/project/data.jsx`) and the layout injector
   (`pomdr-website/project/pomdr-layout.js`).

The handoff package also contains a 20 MB `Brand Guidelines_december2025.pdf`
in `pomdr-website/project/uploads/`. Andrew's review of that PDF is required
before this file is locked. Anything in this doc that the brand guide
contradicts gets fixed in a follow-up edit. Where this doc and the brand
guide agree, this doc is the implementation reference.

---

## 1. Hard rules (zero tolerance)

Every block pattern, every placeholder, every dog bio, every email, every
print ad, every commit message visible to staff conforms.

### 1.1 No em dashes. Anywhere.

The em dash (`—`, Unicode U+2014) is banned. So is the en dash (`–`,
U+2013) when used as a sentence break.

Replace with one of:

- A comma, when joining a clause.
- A period, when starting a new sentence.
- A pair of parentheses, when adding an aside.

Acceptable separator characters in copy: comma, period, semicolon,
parentheses. Acceptable layout separators in pure UI (between metadata
fields like `11 yrs · Monterey`): the middle dot (`·`, U+00B7).

A grep for `—` and `–` across any new file or post content should return
nothing.

### 1.2 No "Meet [Name]," opener

Banned constructions:

- "Meet Pebble!"
- "Meet Pebble, the sweetest little terrier..."
- "Meet Pebble · 11 yrs · Monterey" (audit found this in `data.jsx` line 54
  and similar; it is a violation even when used as a hero tag.)

Open the bio with the dog's personality, a specific moment, or a sensory
detail. If a name introduction is needed, place it inside the sentence:
"Pebble is a quiet eleven-year-old who arrived at POMDR in March."

### 1.3 No closing-question CTA

Banned closes:

- "Could you be his person?"
- "Is Pebble the one?"
- "Ready to bring her home?"

Replace with a declarative invitation:

- "Pebble is ready to meet his next chapter."
- "Submit an inquiry and we'll be in touch within 48 hours."

### 1.4 Use "~age" with tilde, no space

Approximate ages use a tilde with no space.

- Correct: `~11 yrs`
- Wrong: `about 11 years`, `est. 11`, `approximately 11`, `~ 11 yrs`,
  `est 11 yrs`.

Audit found `data.jsx` uses bare `11 yrs` consistently, which is fine when
the age is known. Use the tilde only when the age is approximate.

### 1.5 No "stole your heart" / "stole my heart"

Banned. So are similar saccharine phrasings:

- "tugged at my heartstrings"
- "stole the show"
- "won us over instantly"

These are filler. Replace with something specific to the dog: "Pebble
sleeps with one paw on top of the other and it kills me."

### 1.6 Sign-off

When a human author is credited, the sign-off is `Andrew Z.` (period after
Z, no period after Andrew). Never `Andrew`, never `andrew z`.

---

## 2. Style guidance (judgment calls)

Slightly softer rules. Use these to break ties, not to enforce.

### 2.1 Concise, personality-forward, warm

A dog bio of 60 to 120 words is the right length for the listing card
excerpt (`story_short`). The full bio (`story_long`) can run 200 to 400
words. Personality and specifics beat generalities. "Likes everyone she
meets" is filler. "Has a habit of leaning on your leg until you sit down"
is voice.

### 2.2 Emoji use

Natural and sparing. A heart, a sun, a paw print, fitting the moment, is
fine. Stuffing four emojis into a sentence is not.

The Mailchimp version of a post can carry slightly more emoji than the
website version. The website version is the authoritative source.

### 2.3 Sentence-level rhythm

Vary sentence length. Don't open every sentence with the dog's name.
Avoid the pattern:

> Pebble loves naps. Pebble does not love car rides. Pebble enjoys a slow
> walk.

Better:

> Pebble loves naps, especially the kind that last all afternoon. Car
> rides are a nope. A slow walk through the redwoods is the dream.

### 2.4 What "warm" means in this brand

Warm here is not folksy. It's specific, observed, tender, and a little
funny when the dog gives you the material. The voice respects that the
audience includes seniors making real decisions about their final dog. It
does not condescend, does not infantilize, and does not perform.

---

## 3. Required formats

### 3.1 Listing card excerpt (`story_short`)

- Length: 60 to 120 words.
- Structure: lead with a specific behavioral or personality detail.
  Second sentence places the dog in a setting (foster home, shelter,
  walking partner). Third sentence is the gentle pull, never a question.
- No "Meet" opener. No closing question. No em dashes.

Example:

> Pebble does her best thinking from the foot of the couch. At eleven,
> she's a slow-walker who likes weekly trips to the post office and a yard
> with sunny corners. Her foster says she sleeps next to whoever's
> reading. Pebble would do well in a quiet home where someone is around
> most of the day.

### 3.2 Full bio (`story_long`)

- Length: 200 to 400 words.
- Open with a scene or a specific moment, not a vital-stats summary.
- Close with concrete next steps (how to meet her, what the foster is
  named, where she's staying).
- Sign-off: `Andrew Z.` (when authored by a human) or attributed to the
  foster ("Foster note from Sarah"). No closing question.

### 3.3 Herald print ad

The format is fixed by the Herald template:

```
**Name** (bold)
breed | sex | age | weight

Peace of Mind Dog Rescue

www.POMDR.org
```

- Bold name on its own line.
- Stats line uses pipe (`|`) separators, not em dashes, not commas.
- Blank line between entries when multiple dogs are featured in one ad.
- Always close with the URL on its own line, capitalized as `POMDR.org`.

### 3.4 Hero tag (homepage carousel)

Format: `Name · age · location` (with the middle dot, not a hyphen, not an
em dash). Example: `Pebble · ~11 yrs · Monterey`. **Never** prefix with
"Meet."

### 3.5 Email subject lines (sourced from a post)

- Subject lines under 50 characters.
- No em dashes. No "Meet" openers.
- Front-load the dog's name only when the email is about that specific
  dog.

---

## 4. Banned phrase list

A starting list. Add to it whenever a violation is caught.

- "Meet [Name]" as an opener (any form)
- "stole your heart" / "stole my heart" / "stole the show"
- "tugged at my heartstrings"
- "furever" (audit found this in `data.jsx` line 86; flagged as a marginal
  violation that the brand guide may rule on either way)
- "fur baby"
- "doggo," "pupper," "good boi" as primary descriptors
- "Could you be his person?" and any closing-question CTA
- "stole the night" / "stole the day"
- Em dashes in any context

---

## 5. Audit findings against the design's current copy

The handoff design's React data file (`pomdr-website/project/data.jsx`)
ships with copy that violates the rules. Each violation must be rewritten
before any of this copy lands in the live theme as placeholder text or
seed content.

| Source                       | Violation                                                  | Fix |
|------------------------------|------------------------------------------------------------|-----|
| `data.jsx:54` HERO_SLIDES.tag | "Meet Pebble · 11 yrs · Monterey"                         | "Pebble · ~11 yrs · Monterey" |
| `data.jsx:59` HERO_SLIDES.sub | "second chance at life — especially the ones..."          | Replace em dash with comma: "second chance at life, especially the ones..." |
| `data.jsx:61` HERO_SLIDES.tag | "Meet Sun Bear · 9 yrs · Santa Cruz"                       | "Sun Bear · ~9 yrs · Santa Cruz" |
| `data.jsx:67` HERO_SLIDES.sub | "Rescue, foster, adoption, hospice and education — we walk..." | Replace em dash with period: "...hospice and education. We walk..." |
| `data.jsx:69` HERO_SLIDES.tag | "Meet Oyster · 13 yrs · San Benito"                        | "Oyster · ~13 yrs · San Benito" |
| `data.jsx:86` PROGRAMS.desc   | "furever home"                                              | "forever home" (or rephrase). Brand guide will rule. |
| `data.jsx:94` PROGRAMS.desc   | "Support for guardians facing hardship — walking brigade..." | Replace em dash with period: "...facing hardship. Walking brigade..." |
| `data.jsx:102` PROGRAMS.desc  | "after you're gone — a promise, not a program."            | Comma: "after you're gone, a promise, not a program." |
| `data.jsx:120` TAILS.quote    | "They didn't just match us with a dog — they matched us..." | Comma: "They didn't just match us with a dog, they matched us..." |

Each placeholder text in a new block pattern must include a voice-rules
quote so editors understand the constraints inline. Example placeholder
for `story_short`:

> Lead with a specific behavioral detail. Around 60 to 120 words. No
> "Meet [Name]" opener. No em dashes (use commas, periods, parentheses).
> Close with a gentle invitation, never a question. Use "~age" with tilde
> when age is approximate.

---

## 6. Pending review

These items wait on Andrew or the brand guide:

1. Read pages 1 to 30 of `Brand Guidelines_december2025.pdf` and
   reconcile any voice rules not captured here. Confirm or override the
   "furever" call.
2. Confirm the canonical sign-off when the author is not Andrew. Is it
   "Foster: Sarah," "From the foster," or something else?
3. Decide whether testimonials and happy tails carry their own voice
   tweaks (slightly more sentimental? slightly more first-person?).
4. Decide whether email-only copy (Mailchimp) gets an emoji budget that
   web copy does not.
