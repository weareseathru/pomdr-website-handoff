# Staff Editability Map — POMDR 2026

What staff can change, where, and what still needs a developer. Written
for Carie, Monica, Allison, and Andrew.

## Edit every day in wp-admin (no builder needed)

| Content | Where | Notes |
|---|---|---|
| Dogs: photos, bio, status, foster needs, hospice | wp-admin > Pets | Feeds the adopt grid, foster needs, home page dogs, and each dog's page automatically |
| Events | wp-admin > Events | What's Happening + Adoption Events sections update automatically |
| Videos | wp-admin > Videos | Videos page grid updates automatically |
| Team members | wp-admin > Team | About page |

## Edit in the Divi Visual Builder (pages > Edit with Divi)

Every converted page opens in the Visual Builder. Two kinds of blocks:

1. **Native Divi blocks** (headings, text, buttons on newer sections):
   click, type, save. Safe.
2. **Design blocks (Code modules)**: the pixel-faithful sections ported
   from the approved design currently live as single HTML blocks. Text
   inside them CAN be edited (open the block, edit the words between the
   tags, save; the save cycle is verified lossless), but the editing
   surface shows HTML. Converting the most-edited of these into friendly
   click-and-type modules is the next planned pass, page by page,
   WITHOUT changing how anything looks.

## Needs a developer (by design)

- Home hero slides and rotation, the paw-print trail, the adopt page
  search/filter mechanics, site navigation and footer structure.
- Anything involving new page layouts or new sections.

## Rules that keep the design safe

- The design system (colors, fonts, spacing) is locked in stylesheets and
  Divi Design Variables; page edits cannot break it accidentally.
- Every page's approved look is stored as a reference snapshot; an
  automated comparison can flag any page that drifts.
