# Mass update report

Built 2026-11-16 from www.pomdr.org as read 2026-11-16 at 16:55, against the newest production backup.
Every line below is one change the SQL file makes. Nothing is deleted.

## 1. Staff

The live About page lists 27 people. 17 already match word for word. 7 change, 3 are added, 4 come off.

- **Susan Ferriby**: title "Treasurer" becomes "Secretary/Treasurer".
- **Cameron Donegan**: title "Interim Development Director" becomes "Development Director"; bio updated to the live text (1% different).
- **Caitlin Lupien**: bio updated to the live text (22% different).
- **Andrew Zielinski**: bio updated to the live text (18% different).
- **Added: Nisha Addleman**, Behavior Coordinator (Office Staff), with the live site's bio and portrait.
- **Added: Courtney Young**, Adoptions Coordinator (Office Staff), with the live site's bio and portrait.
- **Added: Briana Ryan**, Volunteer Coordinator (Office Staff), with the live site's bio and portrait.
- **Carmelita Garcia**: title "Benefit Shop Sales Associate, part-time" becomes "Benefit Shop Manager".
- **Dr. Erin Trannel**: bio updated to the live text (6% different).
- **Monica Rua**: bio updated to the live text (1% different).
- **Unpublished: Cortina Whitmore** (Benefit Shop Manager). Not on the live About page. The record is kept.
- **Unpublished: Hannah Wakefield** (Interim Dog Behavior and Enrichment Coordinator). Not on the live About page. The record is kept.
- **Unpublished: Elizabeth Ramsay** (Volunteer Coordinator). Not on the live About page. The record is kept.
- **Unpublished: Emily Termotto** (Adoptions Coordinator). Not on the live About page. The record is kept.

## 2. Events

- **Adoption event: Cypress Inn Yappy Hour**, Sun Nov 22, 3PM, Cypress Inn Yappy Hour, Carmel-by-the-Sea.
- **Adoption event: Pet Pals**, Sun Nov 22, 11AM, Pet Pals, Soquel.
- **Adoption event: Cypress Inn Yappy Hour**, Sun Dec 13, 3PM, Cypress Inn Yappy Hour, Carmel-by-the-Sea.
- **Special Event: POMDR in the PG Parade of Lights**, Thu Dec 3, 6PM, Lighthouse Avenue, Downtown Pacific Grove, Pacific Grove.
- **Special Event: Santa Cruz Holiday Parade**, Sat Dec 5, 10AM, Laurel Avenue and Pacific Avenue, Santa Cruz.
- **Special Event: Paws & Presents Holiday Market**, Sun Dec 6, 1PM, The Club at Pasadera, Monterey.

The What's Happening page also carries 6 standing items that are not dated events: POMDR Benefit Shop; The Monica Rua Silver Hearts Fund; Two Bitch Bourbon Affiliate Link; The Tiny Dog with the Huge Heart; Wooftrax "Walk for a Dog"; Cards and More by Catherine Sullivan Benefiting POMDR. They are gathered in `data/live-events-and-news.json` and placed on `/special-events/` (with Benefit Shop also at `/benefit-shop/` and Silver Hearts Fund on `/donate/`).

## 3. Dogs

The live site lists 80 dogs on the adoptable page (4 of them marked Recently Adopted), 6 hospice dogs and 10 courtesy listings. Every one has a bio and photos, gathered in `data/live-dogs.json` and the photo folder.

### Adoptable dogs: 76

- **Ashanti**: new record with bio and 4 photos, linked to Shelterluv pom-a-20412.
- **Axel**: bio added, published.
- **Bagel Boss**: bio added, published, 4 photos from the live site.
- **Barbara Walters**: bio added, published.
- **Barnabas**: bio added, published, 4 photos from the live site.
- **Bel Air**: bio added, published.
- **Biggie**: bio added, published.
- **Bob Barker**: bio added, published.
- **Bones**: bio added, published, 4 photos from the live site.
- **Boots**: bio added, published, 4 photos from the live site.
- **Bumper Car**: bio updated.
- **Capri Sun**: bio added, published.
- **Casper**: bio updated.
- **Charlie**: bio added, published.
- **Cola**: bio added, published.
- **Cream Cheese**: bio added, published, 4 photos from the live site.
- **Dr. Pepper**: new record with bio and 4 photos, linked to Shelterluv pom-a-20286.
- **Edgar Allan Paw**: bio added, published, 5 photos from the live site.
- **Elm**: bio added, published, 4 photos from the live site.
- **Evie**: bio added, published, 4 photos from the live site.
- **Funyun** (Foster needed): bio added, published.
- **Gabby**: bio added, published.
- **Gobblina** (Foster needed): new record with bio and 4 photos, linked to Shelterluv pom-a-20473.
- **Grim** (Foster needed): bio added, published, 4 photos from the live site.
- **Gumdrop**: bio updated.
- **Guppy**: bio added, published.
- **Harvest** (Foster needed): new record with bio and 5 photos, linked to Shelterluv pom-a-20472.
- **Hocus**: bio added, published, 5 photos from the live site.
- **Jackie**: bio added, published.
- **Jason**: new record with bio and 4 photos, linked to Shelterluv pom-a-20439.
- **Jasper**: bio added, published.
- **Kelpie**: cleared is_cc flag (in Shelterluv foster care and live on adopt.php), bio added, published.
- **Kenji**: new record with bio and 5 photos, linked to Shelterluv pom-a-20402.
- **Lacey**: bio added, published, 5 photos from the live site.
- **Leaf**: bio updated.
- **Licorice** (Foster needed): bio added, published, 4 photos from the live site.
- **Loretta**: bio added, published, 4 photos from the live site.
- **Malibu**: bio added, published.
- **Mattie aka Matteo**: bio updated.
- **Mayo**: bio added, published.
- **Mihla**: bio updated.
- **Miko**: bio added, published, 5 photos from the live site.
- **Mustard**: bio added, published.
- **Nougat**: bio updated.
- **Onion Ring**: bio added, published.
- **Otter**: bio added, published.
- **Parker** (Foster needed): bio added, published, 4 photos from the live site.
- **Pocket**: bio added, published.
- **Pocus**: bio added, published, 4 photos from the live site.
- **Potion**: bio added, published, 4 photos from the live site.
- **Praline**: bio added, published.
- **Pudding** (Foster needed): new record with bio and 4 photos, linked to Shelterluv pom-a-20475.
- **Puffin**: bio added, published.
- **Pumba**: bio added, published.
- **Pumpkin** (Foster needed): bio added, published, 4 photos from the live site.
- **Relish**: bio added, published.
- **Rita** (Foster needed): bio added, published.
- **Salem**: bio added, published, 4 photos from the live site.
- **Simba**: bio added, published.
- **Snorkel**: bio added, published.
- **Spaghetti**: bio added, published.
- **Sparkler**: bio added, published.
- **Strawberry**: bio added, published.
- **Tang**: bio added, published.
- **Tarzan and Jane**: bio updated.
- **Thistle**: bio added, published, 4 photos from the live site.
- **Timon**: bio added, published.
- **Tiny Dancer**: bio added, published.
- **Tofurky**: bio added, published, 4 photos from the live site.
- **Tulle**: new record with bio and 5 photos, linked to Shelterluv pom-a-20394.
- **Tuxedo** (Adoption pending): bio updated.
- **Watson**: bio updated.
- **Wave**: bio added, published.
- **Yankee Candle**: bio updated.
- **Zephyr (was Sonny)**: cleared is_cc flag (in Shelterluv foster care and live on adopt.php), bio added, published.
- **Zero**: new record with bio and 4 photos, linked to Shelterluv pom-a-20431.

### Recently adopted (shown under Adopted): 4

- **Fawn**: bio added, 4 photos from the live site.
- **Jamesy Boy**: bio updated.
- **Teddy**: bio added, 4 photos from the live site.
- **Van Helsing**: bio added, 4 photos from the live site.

### Hospice dogs: 6

- **Gigi (aka Spork)**: bio updated.
- **Huey**: bio added, published.
- **Snowflake**: bio updated.
- **Suki**: bio updated.
- **Winkle**: bio updated.
- **Zelda**: bio updated.

### Courtesy listings (other people's dogs): 10

- **Appa**: new record with bio and 1 photos.
- **Bailey Bae**: new record with bio and 1 photos.
- **Birka**: new record with bio and 1 photos.
- **Chikki**: new record with bio and 1 photos.
- **Chimmu**: new record with bio and 1 photos.
- **Daisey**: new record with bio and 1 photos.
- **Gypsy**: new record with bio and 1 photos.
- **Luna**: new record with bio and 1 photos.
- **Obi**: new record with bio and 1 photos.
- **Wilson and Walter**: new record with bio and 1 photos.

### Coming off the public pages: 72

Published on the new site today and not on the live site. Only the published switch changes.

- Shelterluv says "Healthy In Home" (59): Ahi Tuna, Aragorn, Astrid, Baguette, Beanie Baby, Blizzard, Buddy, Chris-anthumum, Cider, Clove, Dottie, Floss, Flounder, Free Willy, Gigi, Gingerbread, Godiva, Gracie, Hopper, Hot Sauce, Hubba Bubba, Izzy, Jelly, Leela, Lovey, Malcolm, Marbles, Melon, Mighty Joe, Miss Marley, Moose, Mya, Nana, Neo, Newt, Orca, Oscar, Osmosis Jones, Oyster, Ozzy, Panda, Patches, Pauly D, Pearl, Pebble, Pie, Pirata, Romeo, Root Beer, Sebastian, Shortbread, Skipper, Sugar Cookie, Sun Bear, Sunshine Sophie, Tamagotchi, Tootsie Roll, Walrus, Whimsy.
- Shelterluv says "Deceased" (6): Adele, Bindi, Blockbuster, Chucky, Dash, Yeti.
- Shelterluv says "Transferred Out" (4): Chocolate Chunk, Corey, Furby, Mimi.
- Shelterluv says "In Foster Home (regular)" (1): Jamesy Boy.
- Shelterluv says "No Rescue Groups (from foster)" (1): Koda.
- Shelterluv says "no Shelterluv record" (1): Santa’s Little Helper.

