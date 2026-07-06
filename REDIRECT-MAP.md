# REDIRECT-MAP.md

301 redirect map for the POMDR migration: every legacy dog URL and every
page-level URL from the old site's navigation, mapped to the new install and
verified on the Local mirror.

Refreshed 2026-07-06 by live crawl of the old site + the mirror's WordPress
REST API, then imported into the Redirection plugin on the mirror and tested
with `curl -I`. Supersedes the 2026-06-12 draft and the old `redirects.csv`
stub.

## Canonical target (settled)

Per STACK.md (decision 2026-06-09), the canonical dog URL is the **integer**
`/pets/{ID}/`, where `{ID}` is the **new-install** WordPress post ID. Every
legacy dog URL 301s to that. The old (`dog.php?id=`, ~3500 to ~4928) and new
(2402 to 3091) ID spaces are **completely disjoint** (different WordPress
installs), so every dog redirect is an explicit, name-matched rule.

## What changed in this refresh

- **Only `adopt.php` emits `dog.php?id=` links.** `courtesylistings.php`,
  `hospicedogs.php`, and `adopted.php` render dog names but **zero** `dog.php`
  links (adopted.php lists 3,536 names, 0 links). So per-dog redirects are
  scoped to currently-adoptable dogs; every *legacy adopted* `dog.php?id=` URL
  (indexed from old emails and social, not crawlable) is handled by one
  catch-all.
- **Confident dog rules: 45** (was 42). This is the *union* of the
  2026-06-12 map and the fresh crawl. Seven dogs adopted since the prior crawl
  and fell off the live adoptable page, but their `dog.php?id=` and `/pets/`
  pages both still exist, so those rules stay valid. The fresh crawl adds
  Gumdrop (2578), **Forest (3069, promoted out of the old unmatched list)**, and
  Ketchup (3071). No ID conflicts.
- **Unmatched-old: 43** currently-adoptable dogs that are not in the
  mirror snapshot (the mirror was imported before they were listed, e.g. the
  Mustard / Mayo / McGruff / Onion Ring litter). Decision: **catch-all to
  `/adopt/`** now; regenerate per-dog on production where they will exist.
- **Unmatched-new: 75**
  mirror pets with no legacy `dog.php` URL (new intakes / other statuses).
  Informational only.

### The caveat that governs accuracy

Matching *live-adoptable* dogs against a *mirror snapshot* is a moving target.
The mirror proves the mechanism. The authoritative old-to-new map must be
**regenerated against the production `pets` CPT at deploy**, ideally seeded from
the old WordPress DB's own Redirection rules so genuinely-adopted dogs map to
their own `/pets/` page instead of the catch-all.

## Scheme A: dog.php?id= to /pets/{id}/ (45 per-dog 301s)

Stored as **non-regex** rows carrying the query (`/dog.php?id=N`). Redirection
splits path and query and matches the `id` exactly, so each dog resolves to its
own page. (Regex is the wrong tool here: Redirection strips the query before
regex matching, see "Redirection mechanics" below.)

| Old `dog.php?id=` | Dog | New `/pets/{id}/` | Match |
|---:|-----|---|:---:|
| `?id=3878` | Mihla | `/pets/2408/` | exact |
| `?id=3966` | Corey | `/pets/2414/` | exact |
| `?id=4201` | Izzy | `/pets/2437/` | exact |
| `?id=4208` | Bumper Car | `/pets/2442/` | exact |
| `?id=4264` | Mattie aka Matteo | `/pets/2448/` | partial, verify |
| `?id=4295` | Zelda | `/pets/2460/` | exact |
| `?id=4297` | Nougat | `/pets/2466/` | exact |
| `?id=4320` | Winkle | `/pets/2454/` | partial, verify |
| `?id=4330` | Gracie | `/pets/2472/` | exact |
| `?id=4377` | Pauly D | `/pets/2495/` | exact |
| `?id=4417` | Mimi | `/pets/2507/` | exact |
| `?id=4418` | Watson | `/pets/2513/` | exact |
| `?id=4469` | Hot Sauce | `/pets/2524/` | exact |
| `?id=4563` | Casper | `/pets/2556/` | exact |
| `?id=4573` | Yankee Candle | `/pets/2561/` | exact |
| `?id=4594` | Gumdrop | `/pets/2578/` | exact |
| `?id=4610` | Leaf | `/pets/2598/` | exact |
| `?id=4611` | Clove | `/pets/2604/` | exact |
| `?id=4620` | Yeti | `/pets/2610/` | exact |
| `?id=4628` | Osmosis Jones | `/pets/2622/` | exact |
| `?id=4631` | Spork | `/pets/2628/` | exact |
| `?id=4652` | Jamesy Boy | `/pets/2640/` | exact |
| `?id=4654` | Mighty Joe | `/pets/2646/` | exact |
| `?id=4656` | Miss Marley | `/pets/2652/` | exact |
| `?id=4666` | Malcolm | `/pets/2668/` | exact |
| `?id=4679` | Walrus | `/pets/2676/` | exact |
| `?id=4680` | Gingerbread | `/pets/2682/` | exact |
| `?id=4687` | Snowflake | `/pets/2692/` | exact |
| `?id=4692` | Tarzan and Jane | `/pets/2704/` | exact |
| `?id=4711` | Beanie Baby | `/pets/2728/` | exact |
| `?id=4717` | Blockbuster | `/pets/2734/` | exact |
| `?id=4718` | Furby | `/pets/2739/` | exact |
| `?id=4721` | Tamagotchi | `/pets/2750/` | exact |
| `?id=4724` | Hubba Bubba | `/pets/2755/` | exact |
| `?id=4727` | Romeo | `/pets/2761/` | exact |
| `?id=4730` | Dash | `/pets/2745/` | exact |
| `?id=4735` | Suki | `/pets/2772/` | exact |
| `?id=4744` | Gigi | `/pets/2783/` | exact |
| `?id=4751` | Mya | `/pets/2723/` | exact |
| `?id=4762` | Chocolate Chunk | `/pets/2823/` | exact |
| `?id=4772` | Patches | `/pets/2865/` | exact |
| `?id=4801` | Jelly Bean | `/pets/2793/` | exact |
| `?id=4816` | Buddy the Lab | `/pets/2402/` | exact |
| `?id=4856` | Forest | `/pets/3069/` | exact |
| `?id=4922` | Ketchup | `/pets/3071/` | exact |

## Unmatched-old (43): catch-all to /adopt/, regenerate on production

These are live-*adoptable* dogs absent from the mirror snapshot. They are not
ambiguous; the mirror simply does not have them yet. One catch-all rule
(`^/dog\.php(?=$|\?)` to `/adopt/`, placed last) covers them plus every
legacy adopted-dog URL. On production, replace the catch-all's coverage of
these specific dogs with per-dog rules generated from the production `pets` CPT.

| Old `dog.php?id=` | Dog name (old) | Target |
|---:|-----|-----|
| `?id=3505` | Hatchi | `/adopt/` (catch-all) |
| `?id=3728` | Poppy | `/adopt/` (catch-all) |
| `?id=3735` | Phylo | `/adopt/` (catch-all) |
| `?id=4260` | Chad - Aged to Perfection | `/adopt/` (catch-all) |
| `?id=4596` | Biggie | `/adopt/` (catch-all) |
| `?id=4709` | Hawk | `/adopt/` (catch-all) |
| `?id=4710` | Rita | `/adopt/` (catch-all) |
| `?id=4792` | Guppy | `/adopt/` (catch-all) |
| `?id=4804` | Doodle the Poodle aka Scooby | `/adopt/` (catch-all) |
| `?id=4805` | Old Man Jenkins | `/adopt/` (catch-all) |
| `?id=4813` | Aloha | `/adopt/` (catch-all) |
| `?id=4820` | Katsu | `/adopt/` (catch-all) |
| `?id=4833` | Doc Ricketts | `/adopt/` (catch-all) |
| `?id=4835` | Stormie | `/adopt/` (catch-all) |
| `?id=4842` | Lenny | `/adopt/` (catch-all) |
| `?id=4844` | Jackie | `/adopt/` (catch-all) |
| `?id=4847` | Neptune | `/adopt/` (catch-all) |
| `?id=4853` | Orange Crush | `/adopt/` (catch-all) |
| `?id=4854` | Firefly | `/adopt/` (catch-all) |
| `?id=4862` | Pine | `/adopt/` (catch-all) |
| `?id=4866` | Gabby | `/adopt/` (catch-all) |
| `?id=4874` | Franc | `/adopt/` (catch-all) |
| `?id=4880` | Peppercorn | `/adopt/` (catch-all) |
| `?id=4889` | Margaret | `/adopt/` (catch-all) |
| `?id=4892` | Brisket | `/adopt/` (catch-all) |
| `?id=4898` | Pina Colada | `/adopt/` (catch-all) |
| `?id=4901` | Snorkel | `/adopt/` (catch-all) |
| `?id=4903` | Echo | `/adopt/` (catch-all) |
| `?id=4904` | Belle | `/adopt/` (catch-all) |
| `?id=4905` | Lance | `/adopt/` (catch-all) |
| `?id=4906` | Antoine | `/adopt/` (catch-all) |
| `?id=4907` | Maxwell | `/adopt/` (catch-all) |
| `?id=4911` | Bingo | `/adopt/` (catch-all) |
| `?id=4914` | Abby | `/adopt/` (catch-all) |
| `?id=4915` | Sparkler | `/adopt/` (catch-all) |
| `?id=4917` | Potato | `/adopt/` (catch-all) |
| `?id=4918` | Betsy | `/adopt/` (catch-all) |
| `?id=4921` | Mustard | `/adopt/` (catch-all) |
| `?id=4923` | Pickles | `/adopt/` (catch-all) |
| `?id=4925` | Mayo | `/adopt/` (catch-all) |
| `?id=4926` | McGruff | `/adopt/` (catch-all) |
| `?id=4927` | Fritter | `/adopt/` (catch-all) |
| `?id=4928` | Onion Ring | `/adopt/` (catch-all) |

## Page-level 301s

Legacy nav/footer URLs to the new slugs. Stored as **regex path** rules
(`^/legacy\.ext(?=$|\?)`) so they match with or without a trailing query and
**pass any query (e.g. `?utm_source=`) through** to the target.

| Legacy | New | Note |
|--------|-----|------|
| `/adoptionevents.php` | `/events/` |  |
| `/news.html` | `/` | no news page; homepage |
| `/lifetimecare.html` | `/perpetual-care-program/` |  |
| `/helpplacingdog.html` | `/surrender/` |  |
| `/inthemedia.html` | `/media/` |  |
| `/maxsfund.html` | `/donate/` | Max's Fund, giving |
| `/hospicedogs.php` | `/adopt/` | hospice tab on /adopt/ |
| `/courtesylistings.php` | `/courtesy-listings/` |  |
| `/adopted.php` | `/adopted/` |  |
| `/thankyou.html` | `/thanks/` |  |
| `/tributedonations.php` | `/donate/` | tribute giving |
| `/POMDRMailingList.php` | `/mailing-list/` |  |
| `/bauercenter.html` | `/bauer-center/` |  |
| `/vetclinic.html` | `/clinic/` |  |
| `/benefitshop.html` | `/benefit-shop/` |  |
| `/termsandprivacy.html` | `/terms/` | combined legacy page |
| `/videos.html` | `/videos/` |  |
| `/jobs.html` | `/jobs/` |  |
| `/aboutus.html` | `/about/` |  |
| `/adoptionprocess.html` | `/process/` |  |
| `/resources.html` | `/recources/` | mirror slug is /recources/ (misspelled) |

### Form endpoints (query-preserving)

| Legacy | New | Status |
|--------|-----|--------|
| `/POMDRAdoptionQuestionnaire.php?dogname=*` | `/adoption-questionnaire/?dogname=*` | **Active.** D2 confirmed: the theme maps `dogname` to LGL `field_21` (form `utzjcNEZaqAcJk3QURlQmw`). `dogname` preserved. |
| `/POMDRDonation.php?initialdonation=*&fund=*` | `/donate/?initialdonation=*&fund=*` | **Active (LGL variant), BLOCKED-ON-D1.** Targets the new donate page (LGL form `62FAoG7Obtf81TYETJMN3Q`), params preserved. If D1 keeps the legacy processor, delete this rule and leave `POMDRDonation.php` live. |
| `/POMDRSponsorDog.php?dogname=*` | `/donate/?dogname=*` | **NOT imported, blocked.** No dedicated sponsor page yet; the dog-page Sponsor CTA currently uses this legacy endpoint as its interim target. Preserve `dogname` for the eventual sponsor form; enable once the sponsor destination is confirmed. |

Blocked/pending rules live in `redirects.csv` (annotated) but are **not** in
`redirects-import.csv`, so importing the file does not fire them.

## Redirection mechanics (verified on the mirror, plugin 5.8.1)

Findings that shaped the rule syntax, confirmed by reading the plugin source
and by `curl -I`:

- **Import format** is `source,target,regex,code` (header row auto-skipped).
  `regex` is `0`/`1`. The file imports through the plugin's own
  `Red_Csv_File::load()`.
- **Regex sources are matched against the full path+query string**, not the
  bare path. So `^/dog\.php$` fails on `/dog.php?id=1` (the `$` hits before the
  `?`). The correct idiom is a **lookahead**: `^/path(?=$|\?)` matches the path,
  does not consume the `?`, and lets Redirection append the remaining query to
  the target cleanly.
- **The default Query Parameters mode is "Exact match in any order."** A
  non-regex source *without* a query does **not** match a request that *has*
  one, so a plain `/aboutus.html` rule 404s on `/aboutus.html?utm_source=x`.
  Page rules are therefore regex (above), which match regardless of query.
- **Dog rules are the deliberate exception:** non-regex `/dog.php?id=N` uses the
  exact-query matcher to distinguish each `id`. The catch-all regex is ordered
  **last** so specific dogs win by position.

## QA sample (hand-verifiable, tested 2026-07-06)

Imported 69 active rules into a "POMDR Migration 2026-07-06" group (91 total
items in Redirection; see `docs/redirects/redirection-rulecount.png`). All rows
returned HTTP 301 with the Location shown:

| # | Request | 301 Location | Rule |
|--:|---------|--------------|------|
| 1 | `/dog.php?id=3878` | `/pets/2408/` | Mihla (exact) |
| 2 | `/dog.php?id=4816` | `/pets/2402/` | Buddy the Lab (exact) |
| 3 | `/dog.php?id=4922` | `/pets/3071/` | Ketchup (fresh match) |
| 4 | `/dog.php?id=4264` | `/pets/2448/` | Mattie aka Matteo (partial-verify) |
| 5 | `/dog.php?id=4320` | `/pets/2454/` | Winkle (partial-verify) |
| 6 | `/dog.php?id=99999` | `/adopt/?id=99999` | catch-all |
| 7 | `/adoptionevents.php` | `/events/` | page |
| 8 | `/aboutus.html` | `/about/` | page |
| 9 | `/helpplacingdog.html` | `/surrender/` | page |
| 10 | `/POMDRAdoptionQuestionnaire.php?dogname=Rex` | `/adoption-questionnaire/?dogname=Rex` | form passthrough (D2) |

Robustness spot-checks also passed: `/aboutus.html?utm_source=email` to
`/about/?utm_source=email` (query preserved), and every page target resolves
200 (including the passthrough targets).

## Files

- **`redirects-import.csv`**: the machine artifact: 69 active rules in
  Redirection's `source,target,regex,code` format. Import via
  Tools to Redirection to Import/Export (or the plugin's CSV importer). This
  replaces the old stub as the operational file.
- **`redirects.csv`**: the annotated human master: every rule including the
  blocked/pending ones, with a `note` column. Not for direct import.

## Blocked / pending (do not enable without a decision)

- **D1 (donation processor):** the active donation rule assumes the LGL/new-site
  path. If D1 keeps the legacy processor, delete `/POMDRDonation.php` to
  `/donate/`.
- **Sponsor form:** `/POMDRSponsorDog.php` stays live (interim CTA target).
  Enable its redirect once a sponsor destination exists.
- **`/recources/` to `/resources/`:** the redesign intends to fix the misspelled
  slug, but `/resources/` is currently 404 on the mirror, so `resources.html`
  targets `/recources/` for now. After the rename, flip `resources.html` to
  `/resources/` and enable `/recources/` to `/resources/`.

## Production deploy checklist (redirects)

1. Regenerate Scheme A against the production `pets` CPT (post IDs differ per
   install) and, if available, the old WordPress Redirection export for adopted
   dogs.
2. Import `redirects-import.csv` (page + form rules are install-independent).
3. Resolve D1 and the sponsor destination; enable/remove the blocked rows.
4. Add the domain-consolidation 301 (`pomdr.org` and `new.pomdr.org` to the
   canonical host, path preserved).
5. Do **not** enable any of this on production until launch. Mirror only until
   then.
