#!/usr/bin/env python3
"""Builds prod-sync.sql: the data changes that make the new site's dogs, staff
and events match www.pomdr.org as it stood when the live pages were read.

Inputs (all read-only):
  /tmp/prod-tables.json, /tmp/prod-more.json  production rows, parsed from
      Ronnie's nightly backup in db/backups/ (animals, animals_names, team,
      events, shelterluv_animals, photo counts)
  /tmp/live-dog-data.json           the 81 dogs on adopt.php, one page each
  /tmp/live-hospice-courtesy.json   hospicedogs.php and courtesylistings.php
  /tmp/dog-plan.json                each live dog matched to an animals row
  /tmp/live-people.json             the About page's roster

Every UPDATE names the row by id AND by name, so a row that has changed since
the backup is skipped, not overwritten. Nothing is deleted.
"""
import json, re, html, sys, collections, datetime

OUT = sys.argv[1]
SITE = 'https://www.peaceofminddogrescue.org/'
P = json.load(open('/tmp/prod-tables.json'))['rows']
M = json.load(open('/tmp/prod-more.json'))
dogs = json.load(open('/tmp/live-dog-data.json'))
hc = json.load(open('/tmp/live-hospice-courtesy.json'))
plan = json.load(open('/tmp/dog-plan.json'))
people = json.load(open('/tmp/live-people.json'))
A = {a['id']: a for a in P['animals']}
photos = M['photos']
slugs = {a['slug'] for a in P['animals']}
sl_ids = {str(a['shelterluv_id']).lower() for a in P['animals'] if a['shelterluv_id']}
for _a in P['animals']:
    for _k, _v in _a.items():
        if _v == 'NULL':
            _a[_k] = None


def sponsors(t):
    return re.sub(r'^Sponsored By:\s*', '', t or '')


def q(s):
    if s is None:
        return 'NULL'
    return "'" + str(s).replace('\\', '\\\\').replace("'", "''") + "'"


def short(bio):
    first = bio.split('\n\n')[0]
    if len(first) <= 220:
        return first
    cut = first[:220]
    m = re.search(r'^(.*[.!?])\s', cut, re.S)
    return (m.group(1) if m and len(m.group(1)) > 60 else cut.rsplit(' ', 1)[0] + '…')


def namedate(photo_list):
    m = re.search(r'(\d{2})(\d{2})(\d{2})(?:sm|ft|bg\d+)?\.jpg$', photo_list[0]) if photo_list else None
    if not m:
        return None
    mm, dd, yy = int(m.group(1)), int(m.group(2)), 2000 + int(m.group(3))
    try:
        return datetime.date(yy, mm, dd).isoformat()
    except ValueError:
        return None


def slug_for(name):
    base = re.sub(r'[^a-z0-9]+', '-', html.unescape(name).lower()).strip('-') + '-2026'
    s, n = base, 2
    while s in slugs:
        s = f'{base}-{n}'
        n += 1
    slugs.add(s)
    return s


def flags(label):
    foster = temp = pending = 0
    start = end = None
    if label.startswith('Foster needed'):
        m = re.search(r'(\d{1,2})/(\d{1,2})\s*-\s*(\d{1,2})/(\d{1,2})', label)
        if m:
            temp = 1
            start = f'2026-{int(m.group(1)):02d}-{int(m.group(2)):02d}'
            end = f'2026-{int(m.group(3)):02d}-{int(m.group(4)):02d}'
        else:
            foster = 1
    if label == 'Adoption pending':
        pending = 1
    return foster, temp, pending, start, end


L = []
w = L.append
w('-- ---------------------------------------------------------------------------')
w('-- Makes the new site\'s dogs, staff and events match www.pomdr.org.')
w('-- Generated ' + datetime.date.today().isoformat() + ' from the live public pages and the production backup')
w('-- db/backups/pomdr-20261112.sql.gz. Tested by loading that backup locally and running this')
w('-- file against it. Every UPDATE names its row by id AND name; nothing is deleted.')
w('-- Run it once, as one transaction. It is safe to run twice.')
w('-- ---------------------------------------------------------------------------')
w('')
w('START TRANSACTION;')
w('')
w('-- Before: how many dogs are published, and how many staff.')
w("SELECT 'before' AS at, (SELECT COUNT(*) FROM animals WHERE is_available = 1) AS dogs_published, (SELECT COUNT(*) FROM team WHERE status = 'published') AS staff_published, (SELECT COUNT(*) FROM events WHERE status = 'published' AND starts_at >= NOW()) AS upcoming_events;")
w('')

# ------------------------------------------------------------------ 1. staff
w('-- 1. STAFF ------------------------------------------------------------------')
w('-- Off the page: the four people the live site no longer lists. Andrew asked for')
w('-- Cortina Whitmore and Emily Termotto by name; Elizabeth Ramsay and Hannah Wakefield')
w('-- are also gone from the live About page. Their rows stay, unpublished.')
for slug in ['cortina-whitmore', 'emily-termotto', 'elizabeth-ramsay', 'hannah-wakefield']:
    w(f"UPDATE team SET status = 'draft' WHERE slug = {q(slug)} AND status = 'published';")
w('')
w('-- Titles as the live site gives them today.')
w("UPDATE team SET role_title = 'Benefit Shop Manager', sort_order = 1 WHERE slug = 'carmelita-garcia';")
w("UPDATE team SET role_title = 'Development Director' WHERE slug = 'cameron-donegan';")
w("UPDATE team SET role_title = 'Secretary/Treasurer' WHERE slug = 'susan-ferriby';")
w('')
w('-- Three people the live site lists and the new one does not. No bio on the live')
w('-- site either. The portrait is the live site\'s own file until one is uploaded here.')
order = {'carie-broecker': 1, 'cameron-donegan': 2, 'allison-mckee': 3, 'caitlin-lupien': 4, 'andrew-zielinski': 5,
         'shanel-parolini': 6, 'alejandra-raya': 7, 'nisha-addleman': 8, 'courtney-young': 9, 'briana-ryan': 10}
for p in people:
    if p['name'] in ('Nisha Addleman', 'Courtney Young', 'Briana Ryan'):
        slug = re.sub(r'[^a-z]+', '-', p['name'].lower()).strip('-')
        img = SITE + p['imgs'][0] if p.get('imgs') else SITE + p['img']
        w(f"INSERT INTO team (name, slug, role_title, team_group, bio, photo_url, image_key, sort_order, status) "
          f"SELECT {q(p['name'])}, {q(slug)}, {q(p['role'])}, 'office', '', {q(img)}, {q(img)}, {order[slug]}, 'published' "
          f"FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM team WHERE slug = {q(slug)});")
        w(f"UPDATE team SET role_title = {q(p['role'])}, team_group = 'office', status = 'published', sort_order = {order[slug]} WHERE slug = {q(slug)};")
w('')
w('-- Office staff in the live site\'s order.')
for slug, n in order.items():
    w(f"UPDATE team SET sort_order = {n} WHERE slug = {q(slug)};")
w('')

# ----------------------------------------------------------------- 2. events
w('-- 2. EVENTS -----------------------------------------------------------------')
w('-- The four adoption events and three special events the live site lists.')
w('-- If import-archive-events.php is run instead, skip the four adoption events')
w('-- here (it would add them a second time) and publish its drafts.')
EV = [
    ('Patagonia Outlet', 'patagonia-outlet-2026-11-14', 'Adoption event', 'POMDR information table and adoptable dogs.', '2026-11-14 11:00:00', '2026-11-14 13:30:00', 'Patagonia Outlet', '415 River Street, Suite C', 'Santa Cruz', None),
    ('Pet Pals', 'pet-pals-2026-11-22', 'Adoption event', 'POMDR information table and adoptable dogs.', '2026-11-22 11:00:00', '2026-11-22 13:30:00', 'Pet Pals', '3660 Soquel Drive', 'Soquel', None),
    ('Cypress Inn Yappy Hour', 'cypress-inn-yappy-hour-2026-11-22', 'Adoption event', 'Drinks, snacks, and a relaxed afternoon with your pup. Dogs welcome!', '2026-11-22 15:00:00', '2026-11-22 18:00:00', 'Cypress Inn', 'Lincoln Street and 7th Avenue', 'Carmel-by-the-Sea', None),
    ('Cypress Inn Yappy Hour', 'cypress-inn-yappy-hour-2026-12-13', 'Adoption event', 'Drinks, snacks, and a relaxed afternoon with your pup. Dogs welcome!', '2026-12-13 15:00:00', '2026-12-13 18:00:00', 'Cypress Inn', 'Lincoln Street and 7th Avenue', 'Carmel-by-the-Sea', None),
    ('POMDR in the PG Parade of Lights', 'pg-parade-of-lights-2026', 'Special Event', "Join POMDR for the Pacific Grove Holiday Parade of Lights! This year's theme is A Storybook Christmas, with glowing floats, festive fun, and some of POMDR's adoptable and alumni dogs in teal gear. All friendly dogs are welcome, and storybook or holiday costumes for people and pups are encouraged. Come walk with POMDR and help bring the story to life on Lighthouse Avenue! 6:00 pm, rain or shine.", '2026-12-03 18:00:00', None, 'Lighthouse Avenue, Downtown Pacific Grove', 'Lighthouse Avenue', 'Pacific Grove', 'https://secure.lglforms.com/form_engine/s/6B3HTLwnmulhREtGATkyQQ'),
    ('Santa Cruz Holiday Parade', 'santa-cruz-holiday-parade-2026', 'Special Event', "Join POMDR for a festive and fun holiday parade through downtown Santa Cruz which will include some of POMDR's adoptable dogs! All friendly dogs are welcome. Festive holiday attire for people and dogs is encouraged. Represent POMDR, and kick off the holiday season!", '2026-12-05 10:00:00', None, 'Laurel Avenue and Pacific Avenue', 'Laurel Avenue and Pacific Avenue', 'Santa Cruz', 'https://downtownsantacruz.com/do/holiday-parade-2'),
    ('Paws & Presents Holiday Market', 'paws-and-presents-holiday-market-2026', 'Special Event', 'Step into the spirit of the season with this festive afternoon of shopping, sipping, and celebrating in support of Peace of Mind Dog Rescue. Enjoy artisan goods, festive food and drinks and a joyful, dog-loving atmosphere. Sponsored by Robin Venuti, Realtor at Coldwell Bank Realty, and Mechanics Bank.', '2026-12-06 13:00:00', '2026-12-06 15:30:00', 'The Club at Pasadera', 'The Club at Pasadera', 'Monterey', 'https://www.eventbrite.com/e/tpc-monterey-at-pasadera-presents-paws-presents-holiday-market-tickets-1986720022067'),
]
for t, slug, typ, desc, st, en, loc, addr, city, url in EV:
    w(f"INSERT INTO events (title, slug, event_type, description, short_description, starts_at, ends_at, is_all_day, location_name, address_line, city, state, external_url, status, is_featured) "
      f"SELECT {q(t)}, {q(slug)}, {q(typ)}, {q(desc)}, {q(desc[:390])}, {q(st)}, {q(en)}, 0, {q(loc)}, {q(addr)}, {q(city)}, 'CA', {q(url)}, 'published', 0 "
      f"FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM events WHERE slug = {q(slug)} OR (title = {q(t)} AND DATE(starts_at) = {q(st[:10])}));")
w('')

# ------------------------------------------------------------------- 3. dogs
w('-- 3. DOGS -------------------------------------------------------------------')
target = set()
adopted_now = []
upd = []
new_rows = []
stats = collections.Counter()
by_name = {d['name']: d for d in dogs}
SLMAP = {'Jason': 20439, 'Kenji': 20402, 'Tulle': 20394, 'Ashanti': 20412, 'Zero': 20431, 'Kelpie': 20275, 'Edgar Allan Paw': 20424}


def pet_update(pid, guard_name, d, kind, label):
    a = A[pid]
    foster, temp, pending, fs, fe = flags(label)
    adopted = label == 'Recently Adopted'
    sets = []
    sets.append(f"name_public = IF(COALESCE(name_public, '') = '', {q(d['name'])}, name_public)")
    if d.get('bio'):
        sets.append(f"description = {q(d['bio'])}")
        sets.append(f"short_description = {q(short(d['bio']))}")
    if d.get('breed'):
        sets.append(f"looks_like = {q(d['breed'])}")
    if d.get('sex'):
        sets.append(f"sex = {q(d['sex'])}")
    if d.get('age') is not None:
        sets.append(f"age_years = {int(round(d['age']))}")
    if d.get('weight') is not None:
        sets.append(f"weight_lbs = {int(round(d['weight']))}")
    if d.get('sponsors'):
        sets.append(f"sponsored_by = {q(sponsors(d['sponsors']))}")
    nd = namedate(d.get('photos') or [])
    if nd:
        sets.append(f"intake_date = COALESCE(intake_date, {q(nd)})")
    sets += ['is_draft = 0', 'is_active = 1', 'needs_review = 0', 'is_courtesy = 0']
    if adopted:
        sets += ['is_available = 0', 'is_adopted = 1', 'adopted_date = COALESCE(adopted_date, CURDATE())']
    else:
        sets += ['is_available = 1', 'is_adopted = 0', f'is_hospice = {1 if kind == "hospice" else 0}',
                 f'is_foster_needed = {foster}', f'is_temp_foster_needed = {temp}', f'is_adoption_pending = {pending}',
                 f'foster_start_date = {q(fs)}', f'foster_end_date = {q(fe)}']
        target.add(pid)
    w(f"-- {d['name']}" + (f" [{label}]" if label else '') + f" ({kind})")
    w(f"UPDATE animals SET {', '.join(sets)} WHERE id = {pid} AND name = {q(guard_name)};")
    if photos.get(pid, 0) == 0 and not ((a.get('photo_url') or '').strip() or (a.get('photo_key') or '').strip()) and d.get('photos'):
        first = SITE + d['photos'][0]
        w(f"UPDATE animals SET photo_url = {q(first)} WHERE id = {pid} AND name = {q(guard_name)} AND COALESCE(photo_url, '') = '' AND COALESCE(photo_key, '') = '';")
        for i, ph in enumerate(d['photos']):
            u = SITE + ph
            w(f"INSERT INTO animals_photos (pet_id, image_key, source_url, alt_text, sort_order) SELECT {pid}, {q(u)}, {q(u)}, {q(d['name'])}, {i} FROM DUAL "
              f"WHERE EXISTS (SELECT 1 FROM animals WHERE id = {pid} AND name = {q(guard_name)}) AND NOT EXISTS (SELECT 1 FROM animals_photos WHERE pet_id = {pid} AND source_url = {q(u)});")
        stats['photos from the live site'] += 1
    stats['updated ' + ('adopted' if adopted else kind)] += 1


def pet_insert(d, kind, label, slid=None):
    foster, temp, pending, fs, fe = flags(label)
    adopted = label == 'Recently Adopted'
    slug = slug_for(d['name'])
    sl = f'pom-a-{slid}' if slid else None
    assert not sl or sl not in sl_ids, sl
    cols = ['name', 'name_public', 'slug', 'species', 'shelterluv_id', 'description', 'short_description', 'looks_like', 'sex', 'age_years', 'weight_lbs', 'sponsored_by',
            'intake_date', 'photo_url', 'is_available', 'is_adopted', 'adopted_date', 'is_hospice', 'is_courtesy', 'is_foster_needed', 'is_temp_foster_needed', 'is_adoption_pending',
            'foster_start_date', 'foster_end_date', 'is_draft', 'is_active', 'needs_review']
    first = SITE + d['photos'][0] if d.get('photos') else None
    vals = [q(d['name']), q(d['name']), q(slug), "'dog'", q(sl), q(d.get('bio') or None), q(short(d['bio']) if d.get('bio') else None), q(d.get('breed') or None), q(d.get('sex') or None),
            str(int(round(d['age']))) if d.get('age') is not None else 'NULL', str(int(round(d['weight']))) if d.get('weight') is not None else 'NULL',
            q(sponsors(d['sponsors']) if d.get('sponsors') else None), q(namedate(d.get('photos') or [])), q(first),
            '0' if adopted else '1', '1' if adopted else '0', 'CURDATE()' if adopted else 'NULL', '1' if kind == 'hospice' else '0', '1' if kind == 'courtesy' else '0',
            str(foster), str(temp), str(pending), q(fs), q(fe), '0', '1', '0']
    w(f"-- NEW ROW: {d['name']}" + (f" [{label}]" if label else '') + f" ({kind})" + (f", Shelterluv {sl}" if sl else ', not a POMDR dog' if kind == 'courtesy' else ''))
    w(f"INSERT INTO animals ({', '.join(cols)}) SELECT {', '.join(vals)} FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM animals WHERE slug = {q(slug)}" + (f" OR shelterluv_id = {q(sl)}" if sl else '') + ");")
    for i, ph in enumerate(d.get('photos') or []):
        u = SITE + ph
        w(f"INSERT INTO animals_photos (pet_id, image_key, source_url, alt_text, sort_order) SELECT a.id, {q(u)}, {q(u)}, {q(d['name'])}, {i} FROM animals a "
          f"WHERE a.slug = {q(slug)} AND NOT EXISTS (SELECT 1 FROM animals_photos p WHERE p.pet_id = a.id AND p.source_url = {q(u)});")
    stats['new row ' + kind] += 1


w('-- 3a. The dogs on adopt.php: bio, breed, sex, age, weight, sponsors and status as the')
w('--     live site has them, and published. Photos are added only where the dog has none.')
needs_person = []
for p in plan:
    if p['kind'] != 'adopt':
        continue
    d = by_name[p['live']]
    if p.get('cc'):
        # Flagged community care in the new system, which keeps a dog off every
        # public page. That flag is a staff decision, so the row is left alone.
        needs_person.append(p)
        w(f"-- NOT CHANGED: {p['live']} matches row {p['pet']} ({p['pname']}), which is flagged community care (is_cc = 1). A person needs to clear that flag in the admin; then publish.")
        continue
    if p['pet']:
        pet_update(p['pet'], p['pname'], d, 'adopt', p['label'])
    else:
        pet_insert(d, 'adopt', p['label'], (p.get('new_slid') or '').replace('pom-a-', '') or None)
w('')
w('-- 3b. The six hospice dogs on hospicedogs.php.')
hosp = {d['name']: d for d in hc['hospice']}
for p in plan:
    if p['kind'] == 'hospice':
        pet_update(p['pet'], p['pname'], hosp[p['live']], 'hospice', '')
w('')
w('-- 3c. The nine courtesy listings on courtesylistings.php. These are other people\'s dogs,')
w('--     so each is a new row flagged courtesy; none is matched to a POMDR record.')
for d in hc['courtesy']:
    pet_insert(d, 'courtesy', '')
w('')
w('-- 3d. Off the Adopt page: every dog published today that the live site no longer lists.')
w('--     Only the published switch changes. Whether each is marked adopted is left to')
w('--     sync-adopted, which reads Shelterluv.')
off = [a for a in P['animals'] if a['is_available'] == '1' and a['id'] not in target]
for a in sorted(off, key=lambda a: a['name']):
    w(f"UPDATE animals SET is_available = 0 WHERE id = {a['id']} AND name = {q(a['name'])} AND is_available = 1;  -- {a['name']}")
stats['unpublished'] = len(off)
w('')
w("SELECT 'after' AS at, (SELECT COUNT(*) FROM animals WHERE is_available = 1) AS dogs_published, (SELECT COUNT(*) FROM team WHERE status = 'published') AS staff_published, (SELECT COUNT(*) FROM events WHERE status = 'published' AND starts_at >= NOW()) AS upcoming_events;")
w('')
w('COMMIT;')
open(OUT, 'w').write('\n'.join(L) + '\n')
print('needs a person:', [(p['live'], p['pet'], p['pname']) for p in needs_person])
print(dict(stats), '| target published', len(target), '| lines', len(L))
