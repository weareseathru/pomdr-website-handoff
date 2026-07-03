# REDIRECT-MAP.md

Proposed 301 rules for the POMDR migration, covering both legacy dog-URL
schemes, plus the list of pet IDs that need manual verification.

Generated 2026-06-12 by direct HTTP crawl + WordPress REST API.

## Canonical target (settled)

Per STACK.md (decision 2026-06-09), the canonical dog URL is the **integer**
`/pets/{ID}/`, where `{ID}` is the **new.pomdr.org** WordPress post ID. All
legacy dog URLs 301 to that.

## The two legacy URL schemes

| Scheme | Where it lives | Count seen | ID space |
|--------|----------------|-----------:|----------|
| `dog.php?id={id}` | Old production (`peaceofminddogrescue.org`, and `pomdr.org` which 301s to it) | 88 adoptable | old WP install, IDs ~3500 to ~4905 |
| `/pets/{id}/` | `new.pomdr.org` (the migration target, becomes canonical) | 87 pets | new WP install, IDs 2402 to 3037 |

**The two ID spaces are completely disjoint** (old 3500 to 4905 vs new 2402 to
3037). They are different WordPress installs, so there is **no arithmetic or
pattern** that maps an old ID to a new ID. Every `dog.php?id=` redirect must be
an explicit per-dog rule, matched by dog name. That matching is below.

## Scheme A: dog.php?id= to /pets/{id}/ (per-dog 301s)

Name-matched from the live old `/adopt/` page (88 dogs) against the
new REST pets list (87). **42 matched** (40 exact name, 2 partial),
**46 could not be matched** and are listed in the verification section.

Implement these via the Redirection plugin (already in the stack) or a RewriteMap.
Example Apache form for one row:

```apache
# RewriteEngine On
RewriteCond %{QUERY_STRING} (^|&)id=4201(&|$)
RewriteRule ^/?dog\.php$ /pets/2437/? [R=301,L]
```

Full confident map (old to new):

| Old `dog.php?id=` | Dog | New `/pets/{id}/` | Match |
|---:|-----|---|:---:|
| `?id=3878` | Mihla - Aged to Perfection | `/pets/2408/` | exact |
| `?id=3966` | Corey - Aged to Perfection | `/pets/2414/` | exact |
| `?id=4201` | Izzy | `/pets/2437/` | exact |
| `?id=4208` | Bumper Car - Aged to Perfection | `/pets/2442/` | exact |
| `?id=4264` | Mattie aka Matteo - Aged to Perfection | `/pets/2448/` | **partial, verify** |
| `?id=4295` | Zelda - Aged to Perfection | `/pets/2460/` | exact |
| `?id=4297` | Nougat - Aged to Perfection | `/pets/2466/` | exact |
| `?id=4320` | Winkle - Aged to Perfection | `/pets/2454/` | **partial, verify** |
| `?id=4330` | Gracie - Aged to Perfection | `/pets/2472/` | exact |
| `?id=4377` | Pauly D - Aged to Perfection | `/pets/2495/` | exact |
| `?id=4417` | Mimi - Aged to Perfection | `/pets/2507/` | exact |
| `?id=4418` | Watson - Aged to Perfection | `/pets/2513/` | exact |
| `?id=4469` | Hot Sauce | `/pets/2524/` | exact |
| `?id=4563` | Casper - Aged to Perfection | `/pets/2556/` | exact |
| `?id=4573` | Yankee Candle - Aged to Perfection | `/pets/2561/` | exact |
| `?id=4610` | Leaf - Aged to Perfection | `/pets/2598/` | exact |
| `?id=4611` | Clove - Aged to Perfection | `/pets/2604/` | exact |
| `?id=4620` | Yeti - Aged to Perfection | `/pets/2610/` | exact |
| `?id=4628` | Osmosis Jones - Aged to Perfection | `/pets/2622/` | exact |
| `?id=4631` | Spork - Aged to Perfection | `/pets/2628/` | exact |
| `?id=4652` | Jamesy Boy - Aged to Perfection | `/pets/2640/` | exact |
| `?id=4654` | Mighty Joe - Aged to Perfection | `/pets/2646/` | exact |
| `?id=4656` | Miss Marley - Aged to Perfection | `/pets/2652/` | exact |
| `?id=4666` | Malcolm - Aged to Perfection | `/pets/2668/` | exact |
| `?id=4679` | Walrus - Aged to Perfection | `/pets/2676/` | exact |
| `?id=4680` | Gingerbread - Aged to Perfection | `/pets/2682/` | exact |
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

## Scheme B: /pets/{id}/ handling

- On `new.pomdr.org` these are already the canonical pages (HTTP 200). When the
  new site becomes the primary domain, the **same WordPress install** moves with
  it, so `/pets/{id}/` paths carry over **1:1** and need no per-dog rule.
- Add a **domain-consolidation 301** so the staging host and old domain land on
  the canonical host:

```apache
# new.pomdr.org and pomdr.org consolidate to the canonical host, path preserved
RewriteCond %{HTTP_HOST} ^(new\.)?pomdr\.org$ [NC]
RewriteRule ^ https://www.peaceofminddogrescue.org%{REQUEST_URI} [R=301,L]
```

- If a **slug** dog URL is ever exposed (the prototype uses `dog/{slug}.html`,
  and the REST API exposes slugs), 301 it to the integer canonical, for example
  `/pets/pebble/` to `/pets/3037/`. Not currently live, so this is preventive.

## Page-level 301s (non-dog)

Confirmed from the crawl; folds into the existing `redirects.csv`:

| Legacy | New | Why |
|--------|-----|-----|
| `/recources/` | `/resources/` | Misspelled slug live on the site (301 already noted in redirects.csv) |
| `/adoptionevents.php` | `/adoption-events/` | Legacy page in the primary nav; now a filtered Events view (301 in redirects.csv) |
| `pomdr.org/*` | `peaceofminddogrescue.org/*` | `pomdr.org` already 301s to the full domain; keep it |
| `new.pomdr.org/*` | canonical host `/*` | Retire the staging host after launch |

Every other nav page kept its path (`/adopt/`, `/donate/`, `/about/`, etc.), so
those are 200 same-path, no redirect needed.

## Pet IDs to verify manually

### A. Partial name matches, confirm the dog is correct (2)

- `dog.php?id=4264` "Mattie aka Matteo - Aged to Perfection" to `/pets/2448/` (matched on partial name)
- `dog.php?id=4320` "Winkle - Aged to Perfection" to `/pets/2454/` (matched on partial name)

### B. Old adoptable dogs with NO new match (46)

These appear on the live old `/adopt/` but did not name-match any of the 87 new
pets. Likely reasons: adopted/removed on the new site, a renamed slug, or a
bonded-pair/spelling difference. Verify each, then point it at the right
`/pets/{id}/`, or to `/adopted/` (if gone) or `/adopt/` (safe fallback).

| Old `dog.php?id=` | Dog name (old) | Suggested fallback |
|---:|-----|-----|
| `?id=3505` | Hatchi | `/adopt/` then verify |
| `?id=3728` | Poppy | `/adopt/` then verify |
| `?id=4260` | Chad - Aged to Perfection | `/adopt/` then verify |
| `?id=4596` | Biggie | `/adopt/` then verify |
| `?id=4709` | Hawk | `/adopt/` then verify |
| `?id=4710` | Rita | `/adopt/` then verify |
| `?id=4790` | Sea Bass | `/adopt/` then verify |
| `?id=4803` | Breeze | `/adopt/` then verify |
| `?id=4804` | Doodle the Poodle aka Scooby | `/adopt/` then verify |
| `?id=4805` | Old Man Jenkins | `/adopt/` then verify |
| `?id=4813` | Aloha | `/adopt/` then verify |
| `?id=4815` | Mahalo | `/adopt/` then verify |
| `?id=4820` | Katsu | `/adopt/` then verify |
| `?id=4822` | Musubi | `/adopt/` then verify |
| `?id=4833` | Doc Ricketts | `/adopt/` then verify |
| `?id=4835` | Stormie | `/adopt/` then verify |
| `?id=4841` | Lucy | `/adopt/` then verify |
| `?id=4842` | Lenny | `/adopt/` then verify |
| `?id=4843` | London Fog | `/adopt/` then verify |
| `?id=4844` | Jackie | `/adopt/` then verify |
| `?id=4847` | Neptune | `/adopt/` then verify |
| `?id=4848` | Saturn | `/adopt/` then verify |
| `?id=4853` | Orange Crush | `/adopt/` then verify |
| `?id=4854` | Firefly | `/adopt/` then verify |
| `?id=4856` | Forest | `/adopt/` then verify |
| `?id=4861` | Cream Corn | `/adopt/` then verify |
| `?id=4862` | Pine | `/adopt/` then verify |
| `?id=4866` | Gabby | `/adopt/` then verify |
| `?id=4868` | Bernardus | `/adopt/` then verify |
| `?id=4869` | Grizz | `/adopt/` then verify |
| `?id=4874` | Franc | `/adopt/` then verify |
| `?id=4880` | Peppercorn | `/adopt/` then verify |
| `?id=4884` | Bogart | `/adopt/` then verify |
| `?id=4885` | Jessica | `/adopt/` then verify |
| `?id=4886` | Celeste | `/adopt/` then verify |
| `?id=4889` | Margaret | `/adopt/` then verify |
| `?id=4890` | Betty | `/adopt/` then verify |
| `?id=4891` | Emily | `/adopt/` then verify |
| `?id=4892` | Brisket | `/adopt/` then verify |
| `?id=4893` | Chloe | `/adopt/` then verify |
| `?id=4899` | Reef | `/adopt/` then verify |
| `?id=4900` | Summer | `/adopt/` then verify |
| `?id=4901` | Snorkel | `/adopt/` then verify |
| `?id=4902` | Shelly | `/adopt/` then verify |
| `?id=4903` | Echo | `/adopt/` then verify |
| `?id=4905` | Lance | `/adopt/` then verify |

### C. New pets with no old /adopt/ match (45, informational)

These new pets were not on the old adoptable list (new intakes, or a different
status such as courtesy/adopted/foster). No legacy `dog.php` URL needs to point
at them, but if any DO have an old URL you know of, add it to Scheme A.

New IDs: `2420`(koda), `2426`(cider), `2431`(astrid), `2478`(floss), `2483`(newt), `2489`(bindi), `2501`(adele), `2518`(baguette), `2529`(marbles), `2534`(nana), `2540`(ozzy), `2545`(melon), `2551`(chucky), `2567`(aragorn), `2573`(sunshine-sophie), `2578`(gumdrop), `2583`(oscar), `2588`(tootsie-roll), `2593`(chris-anthumum), `2616`(blizzard), `2634`(panda), `2657`(root-beer), `2662`(leela), `2674`(hopper), `2687`(moose), `2698`(pirata), `2710`(dottie), `2716`(santas-little-helper), `2766`(lovey), `2777`(pearl), `2788`(neo), `2798`(pie), `2803`(ahi-tuna), `2808`(free-willy), `2813`(sugar-cookie), `2818`(shortbread), `2828`(whimsy), `2833`(skipper), `2839`(sebastian), `2844`(orca), `2849`(oyster), `2855`(flounder), `2860`(sun-bear), `2870`(godiva), `3037`(pebble)

## Important coverage gap

The old `/adopt/` page only lists **currently adoptable** dogs (88). Dogs that
were already **adopted** on the old site still have live `dog.php?id=` URLs
(indexed, linked from old emails and social) but are **not enumerable** from the
crawl, and the old site has **no sitemap.xml** (404). To build a complete
`dog.php` to `/pets/` map, export the full old dog list from the old WordPress
DB or the existing Redirection plugin rules. Without that, adopted-dog legacy
URLs should 301 to `/adopted/` as a catch-all:

```apache
# Catch-all for any dog.php?id= not in the explicit map above
RewriteCond %{QUERY_STRING} (^|&)id=[0-9]+
RewriteRule ^/?dog\.php$ /adopted/? [R=301,L]
```
