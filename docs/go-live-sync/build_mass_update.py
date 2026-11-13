#!/usr/bin/env python3
"""Builds the mass update that makes the new site's staff, events and dogs match
www.pomdr.org, and a plain-words report of every change in it.

    python3 docs/go-live-sync/build_mass_update.py <prod-tables.json> <prod-more.json> <out.sql> <out-report.md>

Inputs:
  data/live-staff.json, data/live-events-and-news.json, data/live-dogs.json
      everything read from the live public pages (see README)
  prod-tables.json, prod-more.json
      production rows parsed from Ronnie's nightly backup in db/backups/
      (animals, animals_names; team, events, shelterluv_animals, photo counts)

Rules it keeps:
  - every UPDATE names its row by id AND by name, so a row that changed since
    the backup is skipped;
  - nothing is deleted; people and dogs are unpublished, never removed;
  - a row flagged community care (is_cc = 1) is never published;
  - a dog is matched to its row by Shelterluv number first, by name only when
    the row has no Shelterluv link.
"""
import json, re, html, sys, collections, datetime, os, difflib

HERE = os.path.dirname(os.path.abspath(__file__))
PT, PM, OUT, REPORT = sys.argv[1:5]
SITE = 'https://www.peaceofminddogrescue.org/'
P = json.load(open(PT))['rows']
M = json.load(open(PM))
for rows in list(P.values()) + [M['team'], M['events'], M['sl']]:
    for r in rows:
        for k, v in r.items():
            if v == 'NULL':
                r[k] = None
staff = json.load(open(f'{HERE}/data/live-staff.json'))
news = json.load(open(f'{HERE}/data/live-events-and-news.json'))
live = json.load(open(f'{HERE}/data/live-dogs.json'))
A = {a['id']: a for a in P['animals']}
photos = M['photos']
slugs = {a['slug'] for a in P['animals']}
team = {t['slug']: t for t in M['team']}

L = []
R = []
w = L.append
r = R.append


def q(s):
    if s is None:
        return 'NULL'
    return "'" + str(s).replace('\\', '\\\\').replace("'", "''") + "'"


def plain(paragraphs):
    """The live site's paragraphs as the new site stores them: plain text, a
    blank line between paragraphs, a link kept as its words."""
    return '\n\n'.join(re.sub(r'\[\[(.*?)\|.*?\]\]', r'\1', p) for p in paragraphs)


def squash(s):
    return re.sub(r'\s+', ' ', s or '').strip()


def norm(n):
    return re.sub(r'[^a-z0-9]+', ' ', html.unescape(n or '').lower()).strip()


def variants(n):
    n = html.unescape(n or '')
    out = {norm(n)}
    for part in re.split(r'\baka\b|\bwas\b|[()]', n, flags=re.I):
        if norm(part):
            out.add(norm(part))
    out.add(norm(re.sub(r'\s+the\s+\w+$', '', n, flags=re.I)))
    out.add(norm(re.sub(r'^\d+-\d+\s*', '', n)))
    if 'allan' in norm(n):
        out.add(norm(n).replace('allan', 'allen'))
    return {o for o in out if o}


def short(bio):
    first = bio.split('\n\n')[0]
    if len(first) <= 220:
        return first
    cut = first[:220]
    m = re.search(r'^(.*[.!?])\s', cut, re.S)
    return m.group(1) if m and len(m.group(1)) > 60 else cut.rsplit(' ', 1)[0] + '…'


def namedate(photo_list):
    m = re.search(r'(\d{2})(\d{2})(\d{2})(?:sm|ft|bg\d+)?\.jpg$', photo_list[0]) if photo_list else None
    if not m:
        return None
    try:
        return datetime.date(2000 + int(m.group(3)), int(m.group(1)), int(m.group(2))).isoformat()
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


today = datetime.date.today().isoformat()
w('-- ---------------------------------------------------------------------------')
w('-- Mass update: staff, events and dogs as www.pomdr.org has them.')
w(f'-- Built {today}. Live pages read {live["read_at"]}. Production rows from the newest')
w('-- backup in db/backups/. Every UPDATE names its row by id AND name; nothing is')
w('-- deleted. One transaction; safe to run twice.')
w('-- ---------------------------------------------------------------------------')
w('')
w('START TRANSACTION;')
w('')
w("SELECT 'before' AS at, (SELECT COUNT(*) FROM animals WHERE is_available = 1) AS dogs_published, (SELECT COUNT(*) FROM team WHERE status = 'published') AS staff_published, (SELECT COUNT(*) FROM events WHERE status = 'published' AND starts_at >= NOW()) AS upcoming_events;")
w('')

r('# Mass update report')
r('')
r(f'Built {today} from www.pomdr.org as read {live["read_at"].replace("T", " at ")}, against the newest production backup.')
r('Every line below is one change the SQL file makes. Nothing is deleted.')
r('')

# ------------------------------------------------------------------ 1. staff
GROUPS = {'Board of Directors': 'board', 'Office Staff': 'office', 'Benefit Shop Staff': 'benefit-shop', 'Clinic Staff': 'clinic', 'Advisory Council': 'advisory'}
w('-- 1. STAFF ------------------------------------------------------------------')
r('## 1. Staff')
r('')
live_slugs = set()
order = collections.Counter()
st = collections.Counter()
changes = []
for p in staff['people']:
    slug = re.sub(r'[^a-z]+', '-', p['name'].lower()).strip('-')
    live_slugs.add(slug)
    g = GROUPS[p['group']]
    order[g] += 1
    bio = plain(p['bio'])
    t = team.get(slug)
    if t is None:
        img = SITE + p['portrait']
        w(f"INSERT INTO team (name, slug, role_title, team_group, bio, photo_url, image_key, sort_order, status) SELECT {q(p['name'])}, {q(slug)}, {q(p['role'])}, {q(g)}, {q(bio)}, {q(img)}, {q(img)}, {order[g]}, 'published' FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM team WHERE slug = {q(slug)});")
        changes.append(f"- **Added: {p['name']}**, {p['role']} ({p['group']}), with the live site's bio and portrait.")
        st['added'] += 1
        continue
    sets = [f"sort_order = {order[g]}", f"team_group = {q(g)}", "status = 'published'"]
    notes = []
    if (t['role_title'] or '') != p['role']:
        sets.append(f"role_title = {q(p['role'])}")
        notes.append(f'title "{t["role_title"]}" becomes "{p["role"]}"')
    if squash(t['bio']) != squash(bio):
        sets.append(f"bio = {q(bio)}")
        ratio = difflib.SequenceMatcher(None, squash(t['bio']), squash(bio)).ratio()
        notes.append(f'bio updated to the live text ({round((1 - ratio) * 100)}% different)')
    w(f"UPDATE team SET {', '.join(sets)} WHERE slug = {q(slug)};")
    if notes:
        changes.append(f"- **{p['name']}**: " + '; '.join(notes) + '.')
        st['changed'] += 1
    else:
        st['same'] += 1
gone = [t for t in M['team'] if t['slug'] not in live_slugs and t['status'] == 'published']
for t in gone:
    w(f"UPDATE team SET status = 'draft' WHERE slug = {q(t['slug'])} AND status = 'published';  -- not on the live site")
    changes.append(f"- **Unpublished: {t['name']}** ({t['role_title']}). Not on the live About page. The record is kept.")
w('')
r(f"The live About page lists {len(staff['people'])} people. {st['same']} already match word for word. {st['changed']} change, {st['added']} are added, {len(gone)} come off.")
r('')
R.extend(changes)
r('')

# ----------------------------------------------------------------- 2. events
w('-- 2. EVENTS -----------------------------------------------------------------')
w('-- If import-archive-events.php --write is run instead, skip the adoption events here.')
r('## 2. Events')
r('')
have = {(e['title'], (e['starts_at'] or '')[:10]) for e in M['events']}
EV = []
for e in news['adoption_events']:
    addr = e['address'].replace('Lincol St', 'Lincoln St')
    slug = re.sub(r'[^a-z0-9]+', '-', e['name'].lower()).strip('-') + '-' + e['date']
    EV.append((e['name'], slug, 'Adoption event', e['notes'], f"{e['date']} {e['starts']}", f"{e['date']} {e['ends']}", e['name'], addr, e['city'], None, None))
SP = {
    'POMDR in the PG Parade of Lights!': ('POMDR in the PG Parade of Lights', 'pg-parade-of-lights-2026', '2026-12-03 18:00:00', None, 'Lighthouse Avenue, Downtown Pacific Grove', 'Lighthouse Avenue', 'Pacific Grove'),
    'Santa Cruz Holiday Parade': ('Santa Cruz Holiday Parade', 'santa-cruz-holiday-parade-2026', '2026-12-05 10:00:00', None, 'Laurel Avenue and Pacific Avenue', 'Laurel Avenue and Pacific Avenue', 'Santa Cruz'),
    'Paws & Presents Holiday Market': ('Paws & Presents Holiday Market', 'paws-and-presents-holiday-market-2026', '2026-12-06 13:00:00', '2026-12-06 15:30:00', 'The Club at Pasadera', 'The Club at Pasadera', 'Monterey'),
}
for it in news['whats_happening']:
    if it['kind'] != 'event':
        continue
    title, slug, st_, en, loc, addr, city = SP[it['title']]
    what = it['fields'].get('What', '')
    extra = [p for p in it['paragraphs'] if not re.match(r'(Date|Time|Where|What|RSVP):', p) and not any(p == l['text'] for l in it['links'])]
    desc = ' '.join([what] + extra).strip()
    if 'rain or shine' in it['fields'].get('Time', ''):
        desc += ' 6:00 pm, rain or shine.'
    url = it['links'][0]['url'] if it['links'] else it.get('image_link')
    EV.append((title, slug, 'Special Event', desc, st_, en, loc, addr, city, url, it.get('image')))
for t, slug, typ, desc, st_, en, loc, addr, city, url, img in EV:
    w(f"INSERT INTO events (title, slug, event_type, description, short_description, starts_at, ends_at, is_all_day, location_name, address_line, city, state, external_url, photo_url, status, is_featured) "
      f"SELECT {q(t)}, {q(slug)}, {q(typ)}, {q(desc)}, {q(desc[:390])}, {q(st_)}, {q(en)}, 0, {q(loc)}, {q(addr)}, {q(city)}, 'CA', {q(url)}, {q(img)}, 'published', 0 "
      f"FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM events WHERE slug = {q(slug)} OR (title = {q(t)} AND DATE(starts_at) = {q(st_[:10])}));")
    when = datetime.datetime.strptime(st_, '%Y-%m-%d %H:%M:%S').strftime('%a %b %-d, %-I:%M%p').replace(':00', '')
    r(f"- **{typ}: {t}**, {when}, {loc}, {city}." + (' Already in production.' if (t, st_[:10]) in have else ''))
w('')
ongoing = [it for it in news['whats_happening'] if it['kind'] != 'event']
r('')
r(f"The What's Happening page also carries {len(ongoing)} standing items that are not events: " + '; '.join(i['title'] for i in ongoing) + '. They are gathered in `data/live-events-and-news.json` with their text, links and images. The new site has no table for them, so they are not in the SQL.')
r('')

# ------------------------------------------------------------------- 3. dogs
CARE = {'In Foster Home (regular)', 'In Foster Home (temp)', 'Awaiting Foster', 'Hospice', 'Pending Acceptance'}
slkey = lambda s: 'pom-a-' + re.sub(r'^pom-a-', '', str(s['shelterluv_id']).lower())
by_sl = {str(a['shelterluv_id']).lower(): a for a in P['animals'] if a['shelterluv_id']}
slidx = collections.defaultdict(list)
for s in M['sl']:
    for v in variants(s['name']):
        slidx[v].append(s)
aidx = collections.defaultdict(set)
for a in P['animals']:
    for f in ('name', 'name_public'):
        for v in variants(a[f]):
            aidx[v].add(a['id'])
for n in P['animals_names']:
    for v in variants(n['name']):
        aidx[v].add(n['pet_id'])
published_by_name = {norm(a['name_public'] or a['name']): a for a in P['animals'] if a['is_available'] == '1' and a['is_cc'] != '1'}


def match(d):
    vs = variants(d['name'])
    adopted = d['status_label'] == 'Recently Adopted'
    sc = [s for v in vs for s in slidx.get(v, [])]
    sc = [s for s in sc if s['status'] in CARE or (adopted and s['status'] == 'Healthy In Home')]
    sc.sort(key=lambda s: (s['status'] in CARE, s['last_updated'] or ''), reverse=True)
    for s in sc:
        if slkey(s) in by_sl:
            return by_sl[slkey(s)], 'Shelterluv number', None
    c = [A[i] for v in vs for i in aidx.get(v, set()) if i in A]
    c = [a for a in c if a['is_cc'] != '1' and not a['shelterluv_id'] and a['is_adopted'] != '1']
    c.sort(key=lambda a: int(a['id']), reverse=True)
    if c:
        return c[0], 'name, no Shelterluv link', None
    if norm(d['name']) in published_by_name:
        return published_by_name[norm(d['name'])], 'already published under this name', None
    return None, '', (slkey(sc[0]) if sc else None)


target = set()
stats = collections.Counter()
rep = collections.defaultdict(list)
w('-- 3. DOGS -------------------------------------------------------------------')


def facts_of(d):
    a = d['animals'][0] if d.get('animals') else {}
    return a.get('looks_like') or None, a.get('sex') or None, a.get('age_years'), a.get('weight_lbs')


def pet_update(a, d, how):
    pid = a['id']
    kind = d['list']
    label = d['status_label']
    foster, temp, pending, fs, fe = flags(label)
    adopted = label == 'Recently Adopted'
    bio = plain(d['bio'])
    breed, sex, age, weight = facts_of(d)
    sets = [f"name_public = IF(COALESCE(name_public, '') = '', {q(d['name'])}, name_public)"]
    notes = []
    if bio:
        sets += [f"description = {q(bio)}", f"short_description = {q(short(bio))}"]
        if not squash(a['description']):
            notes.append('bio added')
        elif squash(a['description']) != squash(bio):
            notes.append('bio updated')
    if breed:
        sets.append(f"looks_like = {q(breed)}")
    if sex:
        sets.append(f"sex = {q(sex)}")
    if age is not None:
        sets.append(f"age_years = {int(round(age))}")
    if weight is not None:
        sets.append(f"weight_lbs = {int(round(weight))}")
    if d.get('sponsors'):
        sets.append(f"sponsored_by = {q(d['sponsors'])}")
    nd = namedate(d.get('photos') or [])
    if nd:
        sets.append(f"intake_date = COALESCE(intake_date, {q(nd)})")
    sets += ['is_draft = 0', 'is_active = 1', 'needs_review = 0', 'is_courtesy = 0']
    if adopted:
        sets += ['is_available = 0', 'is_adopted = 1', 'adopted_date = COALESCE(adopted_date, CURDATE())']
    else:
        sets += ['is_available = 1', 'is_adopted = 0', f'is_hospice = {1 if kind == "hospice" else 0}', f'is_foster_needed = {foster}', f'is_temp_foster_needed = {temp}',
                 f'is_adoption_pending = {pending}', f'foster_start_date = {q(fs)}', f'foster_end_date = {q(fe)}']
        target.add(pid)
        if a['is_available'] != '1':
            notes.append('published')
    w(f"-- {d['name']}" + (f" [{label}]" if label else '') + f" ({kind}; matched by {how})")
    w(f"UPDATE animals SET {', '.join(sets)} WHERE id = {pid} AND name = {q(a['name'])};")
    if photos.get(pid, 0) == 0 and not ((a.get('photo_url') or '').strip() or (a.get('photo_key') or '').strip()) and d.get('photos'):
        first = SITE + d['photos'][0]
        w(f"UPDATE animals SET photo_url = {q(first)} WHERE id = {pid} AND name = {q(a['name'])} AND COALESCE(photo_url, '') = '' AND COALESCE(photo_key, '') = '';")
        for i, ph in enumerate(d['photos']):
            u = SITE + ph
            w(f"INSERT INTO animals_photos (pet_id, image_key, source_url, alt_text, sort_order) SELECT {pid}, {q(u)}, {q(u)}, {q(d['name'])}, {i} FROM DUAL "
              f"WHERE EXISTS (SELECT 1 FROM animals WHERE id = {pid} AND name = {q(a['name'])}) AND NOT EXISTS (SELECT 1 FROM animals_photos WHERE pet_id = {pid} AND source_url = {q(u)});")
        notes.append(f"{len(d['photos'])} photos from the live site")
        stats['dogs given live-site photos'] += 1
    key = 'recently adopted' if adopted else kind
    stats[key + ', existing record'] += 1
    rep[key].append(f"- **{d['name']}**" + (f" ({label})" if label and not adopted else '') + ': ' + (', '.join(notes) if notes else 'already published; facts refreshed') + '.')


def pet_insert(d, slid):
    kind = d['list']
    label = d['status_label']
    foster, temp, pending, fs, fe = flags(label)
    adopted = label == 'Recently Adopted'
    slug = slug_for(d['name'])
    bio = plain(d['bio'])
    breed, sex, age, weight = facts_of(d)
    first = SITE + d['photos'][0] if d.get('photos') else None
    cols = ['name', 'name_public', 'slug', 'species', 'shelterluv_id', 'description', 'short_description', 'looks_like', 'sex', 'age_years', 'weight_lbs', 'sponsored_by', 'intake_date', 'photo_url',
            'is_available', 'is_adopted', 'adopted_date', 'is_hospice', 'is_courtesy', 'is_foster_needed', 'is_temp_foster_needed', 'is_adoption_pending', 'foster_start_date', 'foster_end_date',
            'is_draft', 'is_active', 'needs_review']
    vals = [q(d['name']), q(d['name']), q(slug), "'dog'", q(slid), q(bio or None), q(short(bio) if bio else None), q(breed), q(sex), str(int(round(age))) if age is not None else 'NULL',
            str(int(round(weight))) if weight is not None else 'NULL', q(d.get('sponsors') or None), q(namedate(d.get('photos') or [])), q(first), '0' if adopted else '1', '1' if adopted else '0',
            'CURDATE()' if adopted else 'NULL', '1' if kind == 'hospice' else '0', '1' if kind == 'courtesy' else '0', str(foster), str(temp), str(pending), q(fs), q(fe), '0', '1', '0']
    w(f"-- NEW RECORD: {d['name']}" + (f" [{label}]" if label else '') + f" ({kind})" + (f", Shelterluv {slid}" if slid else ''))
    w(f"INSERT INTO animals ({', '.join(cols)}) SELECT {', '.join(vals)} FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM animals WHERE slug = {q(slug)}" + (f" OR shelterluv_id = {q(slid)}" if slid else '') + ");")
    for i, ph in enumerate(d.get('photos') or []):
        u = SITE + ph
        w(f"INSERT INTO animals_photos (pet_id, image_key, source_url, alt_text, sort_order) SELECT a.id, {q(u)}, {q(u)}, {q(d['name'])}, {i} FROM animals a "
          f"WHERE a.slug = {q(slug)} AND NOT EXISTS (SELECT 1 FROM animals_photos p WHERE p.pet_id = a.id AND p.source_url = {q(u)});")
    key = 'recently adopted' if adopted else kind
    stats[key + ', new record'] += 1
    rep[key].append(f"- **{d['name']}**" + (f" ({label})" if label and not adopted else '') + f": new record with bio and {len(d.get('photos') or [])} photos" + (f', linked to Shelterluv {slid}' if slid else '') + '.')


needs_person = []
for d in live['dogs']:
    if d['list'] == 'courtesy':
        pet_insert(d, None)
        continue
    a, how, new_slid = match(d)
    if a is not None and a['is_cc'] == '1':
        needs_person.append((d, a))
        w(f"-- NOT CHANGED: {d['name']} matches row {a['id']} ({a['name']}), flagged community care (is_cc = 1). A person clears that flag in the admin, then publishes.")
        continue
    if a is not None:
        pet_update(a, d, how)
    else:
        pet_insert(d, new_slid)
w('')
w('-- Off the public pages: every dog published today that the live site no longer lists.')
off = sorted([a for a in P['animals'] if a['is_available'] == '1' and a['id'] not in target], key=lambda a: a['name'])
sl_status = {slkey(s): s['status'] for s in M['sl']}
for a in off:
    w(f"UPDATE animals SET is_available = 0 WHERE id = {a['id']} AND name = {q(a['name'])} AND is_available = 1;  -- {a['name']}")
w('')
w("SELECT 'after' AS at, (SELECT COUNT(*) FROM animals WHERE is_available = 1) AS dogs_published, (SELECT COUNT(*) FROM team WHERE status = 'published') AS staff_published, (SELECT COUNT(*) FROM events WHERE status = 'published' AND starts_at >= NOW()) AS upcoming_events;")
w('')
w('COMMIT;')

n = collections.Counter(d['list'] for d in live['dogs'])
r('## 3. Dogs')
r('')
r(f"The live site lists {n['adoptable']} dogs on the adoptable page ({sum(d['status_label'] == 'Recently Adopted' for d in live['dogs'])} of them marked Recently Adopted), {n['hospice']} hospice dogs and {n['courtesy']} courtesy listings. Every one has a bio and photos, gathered in `data/live-dogs.json` and the photo folder.")
r('')
for key, title in [('adoptable', 'Adoptable dogs'), ('recently adopted', 'Recently adopted (shown under Adopted)'), ('hospice', 'Hospice dogs'), ('courtesy', 'Courtesy listings (other people\'s dogs)')]:
    if rep[key]:
        r(f'### {title}: {len(rep[key])}')
        r('')
        R.extend(sorted(rep[key], key=str.lower))
        r('')
if needs_person:
    r(f'### Needs a person: {len(needs_person)}')
    r('')
    r('On the live adoptable page, but the matching record is flagged community care, which hides a dog from every public page. The update leaves these alone.')
    r('')
    for d, a in needs_person:
        r(f"- **{d['name']}**: record {a['id']}, held under the name \"{a['name']}\".")
    r('')
r(f'### Coming off the public pages: {len(off)}')
r('')
r('Published on the new site today and not on the live site. Only the published switch changes.')
r('')
by = collections.defaultdict(list)
for a in off:
    by[sl_status.get(str(a['shelterluv_id'] or '').lower(), 'no Shelterluv record')].append(a['name_public'] or a['name'])
for s, names in sorted(by.items(), key=lambda x: -len(x[1])):
    r(f"- Shelterluv says \"{s}\" ({len(names)}): " + ', '.join(sorted(names)) + '.')
r('')
open(OUT, 'w').write('\n'.join(L) + '\n')
open(REPORT, 'w').write('\n'.join(R) + '\n')
print(dict(stats))
print('staff:', dict(st), 'unpublished', [t['name'] for t in gone])
print('needs a person:', [(d['name'], a['id'], a['name']) for d, a in needs_person])
print('published after:', len(target), '+ new available', sum(1 for d in live['dogs'] if False), '| coming off', len(off), '| sql lines', len(L))
