#!/usr/bin/env python3
"""Builds docs/photo-pass/website-photo-pass.html from the crawl data in
.playwright-mcp/study/photos/. Run from the handoff repo root."""
import json, html, collections, os

S = '.playwright-mcp/study/photos'
OUT = 'docs/photo-pass/website-photo-pass.html'
pp = json.load(open(f'{S}/page-photos.json'))
loc = json.load(open(f'{S}/local-meta.json'))
db = json.load(open(f'{S}/db-photos.json'))
titles = json.load(open(f'{S}/titles.json'))
e = lambda s: html.escape(str(s), quote=True)

NAMES = {'/': 'Home', '/process/': 'How adopting works', '/why/': 'Why a senior dog',
         '/hospice/': 'Hospice care', '/become-a-foster/': 'Become a foster',
         '/foster-needs/': 'Foster needs', '/fostering/': 'Fostering (foster stories)',
         '/get-help/': 'Get help', '/helping-paw/': 'Helping Paw', '/surrender/': 'Placing your dog',
         '/perpetual-care-program/': 'Perpetual Care', '/volunteer/': 'Volunteer',
         '/jobs/': 'Job opportunities', '/donate/': 'Ways to give (Donate)', '/about/': 'About us',
         '/clinic/': 'Veterinary clinic', '/benefit-shop/': 'Benefit Shop', '/media/': 'In the media',
         '/videos/': 'Videos'}
ORDER = ['/', '/process/', '/why/', '/hospice/', '/become-a-foster/', '/foster-needs/', '/fostering/',
         '/get-help/', '/helping-paw/', '/surrender/', '/perpetual-care-program/', '/volunteer/', '/jobs/',
         '/donate/', '/about/', '/clinic/', '/benefit-shop/', '/media/', '/videos/']
GRID_PAGES = {'/donate/', '/fostering/', '/videos/'}
HERO = {'home-hero-adopt.jpg': 'Home slideshow, slide 1 (Adoption)',
        'home-hero-helping-paw.jpg': 'Home slideshow, slide 2 (Helping Paw)',
        'home-hero-mission.jpg': 'Home slideshow, slide 3 (Our mission)',
        'home-hero-volunteer.jpg': 'Home slideshow, slide 4 (Foster, donate, volunteer)',
        'home-tile-adopt.jpg': 'Under "Three ways we keep dogs and people together": the Adopt tile',
        'home-tile-donate.jpg': 'Under "Three ways we keep dogs and people together": the Donate tile',
        'home-tile-volunteer.jpg': 'Under "Three ways we keep dogs and people together": the Volunteer tile'}


def thumb(f):
    return 'img/page-' + f.rsplit('.', 1)[0] + '.jpg'


def shape(w, h):
    if not w or not h:
        return ''
    r = w / h
    return 'square' if 0.9 <= r <= 1.1 else ('wide' if r > 1 else 'tall')


def verdict(src_w, shown_w):
    """Sharpness from pixels alone: a photo needs about twice the width it is
    shown at to look crisp on a phone or a new laptop."""
    if not src_w or not shown_w:
        return ('ok', 'Not measured')
    r = src_w / shown_w
    if r < 1.0:
        return ('bad', 'Too small: blurry on every screen')
    if r < 1.6:
        return ('soft', 'Soft on phones and new laptops')
    return ('good', 'Sharp')


def crop_note(sw, sh, dw, dh):
    if not (sw and sh and dw and dh):
        return ''
    a, b = sw / sh, dw / dh
    kept = min(a, b) / max(a, b)
    if kept < 0.75:
        return f'Cropped hard to fit: about {round((1 - kept) * 100)}% of the photo is cut off. A {shape(dw, dh)} photo fits this spot.'
    return ''


uses = collections.defaultdict(list)
for p in pp['placements']:
    uses[p['file']].append(p['page'])

counts = collections.Counter()
section_html = []
n_spots = 0
for pi, page in enumerate(ORDER, 1):
    items = [p for p in pp['placements'] if p['page'] == page]
    if not items:
        continue
    out = []
    grid = page in GRID_PAGES
    for i, p in enumerate(items, 1):
        n_spots += 1
        m = loc.get(p['file'], {})
        sw, sh = m.get('w', 0), m.get('h', 0)
        cls, label = verdict(sw, p['w'])
        counts[cls] += 1
        crop = crop_note(sw, sh, p['w'], p['h'])
        where = HERO.get(p['file']) or (f'Under "{p["heading"]}"' if p['heading'] else 'Top of the page')
        others = sorted({NAMES.get(x, x) for x in uses[p['file']] if x != page})
        also = f'<p class="also">Same photo also on: {e(", ".join(others))}</p>' if others else ''
        pid = f'{pi}.{i}'
        if grid:
            out.append(f'''
<li class="tile">
  <img src="{thumb(p['file'])}" alt="" loading="lazy">
  <div class="tile-body">
    <p class="tile-id">{pid} <span class="chip chip-{cls}">{e(label.split(':')[0])}</span></p>
    <p class="tile-where">{e(where)}</p>
    <p class="boxes"><span class="box"></span>Keep <span class="box"></span>Replace</p>
  </div>
</li>''')
        else:
            out.append(f'''
<li class="spot">
  <img class="spot-img" src="{thumb(p['file'])}" alt="" loading="lazy">
  <div class="spot-body">
    <p class="spot-id">{pid}</p>
    <p class="spot-where">{e(where)}</p>
    <p class="chip chip-{cls}">{e(label)}</p>
    <dl class="facts">
      <dt>Shown at</dt><dd>{p['w']} × {p['h']} px on a laptop ({shape(p['w'], p['h'])})</dd>
      <dt>Photo is</dt><dd>{sw} × {sh} px <span class="file">{e(p['file'])}</span></dd>
    </dl>
    {f'<p class="crop">{e(crop)}</p>' if crop else ''}
    {also}
  </div>
  <div class="decide">
    <p><span class="box"></span>Keep</p>
    <p><span class="box"></span>Replace with:</p>
    <p class="line"></p>
    <p><span class="box"></span>Take a new photo</p>
  </div>
</li>''')
    kind = 'tiles' if grid else 'spots'
    section_html.append(f'''
<section class="page-block">
  <h3><span class="pnum">{pi}</span> {e(NAMES.get(page, titles.get(page, page)))} <span class="url">{e(page)}</span></h3>
  <ul class="{kind}">{''.join(out)}</ul>
</section>''')

# ---------- dogs
dogs = sorted(db['dogs'], key=lambda d: d['name'].lower())
dog_html = []
dog_soft = dog_small = 0
for d in dogs:
    ph = d['photos']
    lead = ph[0] if ph else {}
    cls, label = verdict(lead.get('w', 0), 532)
    if cls == 'bad':
        dog_small += 1
    elif cls == 'soft':
        dog_soft += 1
    small = sum(1 for x in ph if max(x.get('w', 0), x.get('h', 0)) < 600)
    status = ', '.join(d['badges']) if d['badges'] else ('Hospice' if 'hospice' in d['lists'] else 'Adoptable')
    note = []
    if small:
        note.append(f'{small} of {len(ph)} photos {"is" if small == 1 else "are"} under 600 px')
    if len(ph) < 4:
        note.append(f'only {len(ph)} photo{"s" if len(ph) != 1 else ""}')
    dog_html.append(f'''
<li class="dog">
  <div class="dog-head">
    <p class="dog-name">{e(d['name'])}</p>
    <p class="dog-status">{e(status)} · {len(ph)} photos</p>
    <p class="chip chip-{cls}">Main photo: {e(label)}</p>
    {f'<p class="dog-note">{e("; ".join(note).capitalize())}</p>' if note else ''}
  </div>
  <img class="strip" src="img/{e(d['strip'])}" alt="" loading="lazy">
  <p class="dog-decide">Best main photo: #<span class="blank"></span>
     <span class="box"></span>Ask the foster for new photos</p>
</li>''')

# ---------- team
team_html = []
groups = collections.OrderedDict()
for t in db['team']:
    groups.setdefault(t['group'], []).append(t)
for g, people in groups.items():
    cards = ''.join(f'''
<li class="person">
  <img src="img/team-{e(t['slug'])}.jpg" alt="" loading="lazy">
  <p class="person-name">{e(t['name'])}</p>
  <p class="person-role">{e(t['role'])}</p>
  <p class="boxes"><span class="box"></span>Keep <span class="box"></span>New portrait</p>
</li>''' for t in people)
    team_html.append(f'<h3>{e(g)}</h3><ul class="people">{cards}</ul>')
no_portrait = [s.replace('-', ' ').title() for s in db['noportrait']]

# ---------- spares
SPARE_NOTE = {'share-default.jpg': 'The picture that shows when someone shares a site link by text, email or Facebook. In use, listed here so it gets a decision.'}
spares = ''.join(f'''
<li class="tile">
  <img src="{thumb(f)}" alt="" loading="lazy">
  <div class="tile-body">
    <p class="tile-where"><span class="file">{e(f)}</span></p>
    <p class="tile-meta">{loc.get(f, {}).get('w', 0)} × {loc.get(f, {}).get('h', 0)} px</p>
    {f'<p class="tile-meta">{e(SPARE_NOTE[f])}</p>' if f in SPARE_NOTE else ''}
    <p class="boxes"><span class="box"></span>Use for spot #<span class="blank short"></span></p>
  </div>
</li>''' for f in pp['unused'])

all_dog_photos = sum(len(d['photos']) for d in dogs)
under600 = sum(1 for d in dogs for x in d['photos'] if max(x.get('w', 0), x.get('h', 0)) < 600)
largest = max(max(x.get('w', 0), x.get('h', 0)) for d in dogs for x in d['photos'])

page = f'''<title>Website Photo Pass</title>
<meta name="claude:viewport-fit-sides" content="cover">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Source+Sans+3:wght@400;600;700&family=Source+Serif+4:opsz,wght@8..60,400;8..60,600&display=swap">
<style>
/* Layout: a print packet first. Letter pages, one decision row per photo,
   tick boxes on the right; on screen the same rows read as a long checklist. */
:root {{
  --paper: #ffffff;
  --ink: #1d2a30;
  --muted: #56656c;
  --rule: #d5dee1;
  --wash: #f1f6f7;
  --teal: #006c8a;
  --purple: #632f88;
  --good: #1f7a4d;
  --soft: #8a5a00;
  --bad: #b3261e;
  --good-bg: #e6f4ec;
  --soft-bg: #fbf1dc;
  --bad-bg: #fbe6e4;
  --serif: "Source Serif 4", Georgia, "Times New Roman", serif;
  --sans: "Source Sans 3", "Myriad Pro", "Segoe UI", Arial, sans-serif;
}}
@media (prefers-color-scheme: dark) {{ :root:not([data-theme="light"]) {{
  --paper: #11191c; --ink: #e6eef0; --muted: #a3b3b9; --rule: #2c3a3f; --wash: #18242a;
  --teal: #5cc3db; --purple: #c7a3e6; --good: #7fd4a3; --soft: #f0c46a; --bad: #ff9a90;
  --good-bg: #173326; --soft-bg: #342a12; --bad-bg: #3a1c1a; color-scheme: dark; }} }}
:root[data-theme="dark"] {{
  --paper: #11191c; --ink: #e6eef0; --muted: #a3b3b9; --rule: #2c3a3f; --wash: #18242a;
  --teal: #5cc3db; --purple: #c7a3e6; --good: #7fd4a3; --soft: #f0c46a; --bad: #ff9a90;
  --good-bg: #173326; --soft-bg: #342a12; --bad-bg: #3a1c1a; color-scheme: dark; }}
* {{ box-sizing: border-box; }}
body {{ background: var(--paper); color: var(--ink); font: 16px/1.5 var(--sans); }}
.wrap {{ max-width: 60rem; margin: 0 auto; padding-block: 2rem 4rem;
  padding-left: max(16px, env(safe-area-inset-left, 0px)); padding-right: max(16px, env(safe-area-inset-right, 0px)); }}
h1, h2, h3 {{ font-family: var(--serif); font-weight: 600; text-wrap: balance; line-height: 1.15; margin: 0; }}
h1 {{ font-size: 2.75rem; color: var(--purple); }}
h2 {{ font-size: 1.9rem; color: var(--purple); margin-top: 2.5rem; padding-top: 1rem; border-top: 3px solid var(--purple); }}
h3 {{ font-size: 1.3rem; margin: 1.75rem 0 0.75rem; color: var(--teal); }}
p {{ margin: 0; }}
.kicker {{ font: 700 0.8rem/1 var(--sans); letter-spacing: 0.12em; text-transform: uppercase; color: var(--teal); margin-bottom: 0.75rem; }}
.lede {{ font-size: 1.15rem; max-width: 42rem; margin-top: 0.75rem; color: var(--muted); }}
.intro p, .intro li {{ max-width: 44rem; }}
.intro p + p {{ margin-top: 0.6rem; }}
.cols {{ display: grid; grid-template-columns: repeat(auto-fit, minmax(min(100%, 17rem), 1fr)); gap: 1.25rem 2rem; margin-top: 1.25rem; }}
.cols > div {{ min-width: 0; }}
.cols h4 {{ font: 700 0.95rem/1.3 var(--sans); margin: 0 0 0.4rem; }}
ol, ul.plain {{ margin: 0; padding-left: 1.25rem; }}
li + li {{ margin-top: 0.3rem; }}
table.sum {{ border-collapse: collapse; margin-top: 1rem; font-variant-numeric: tabular-nums; width: 100%; max-width: 40rem; }}
table.sum td {{ border-bottom: 1px solid var(--rule); padding: 0.4rem 0.5rem 0.4rem 0; vertical-align: top; }}
table.sum td:first-child {{ font-weight: 700; white-space: nowrap; color: var(--purple); width: 5.5rem; }}
.key {{ display: flex; flex-wrap: wrap; gap: 0.5rem 1.25rem; margin-top: 0.75rem; }}
.chip {{ display: inline-block; font: 700 0.8rem/1.2 var(--sans); padding: 0.2rem 0.55rem; border-radius: 0.3rem; }}
.chip-good {{ color: var(--good); background: var(--good-bg); }}
.chip-soft {{ color: var(--soft); background: var(--soft-bg); }}
.chip-bad  {{ color: var(--bad);  background: var(--bad-bg); }}
.chip-ok   {{ color: var(--muted); background: var(--wash); }}
.box {{ display: inline-block; width: 0.95rem; height: 0.95rem; border: 1.5px solid var(--ink); border-radius: 2px; vertical-align: -0.12rem; margin-right: 0.35rem; }}
.blank {{ display: inline-block; width: 3rem; border-bottom: 1.5px solid var(--ink); margin: 0 0.75rem 0 0.15rem; }}
.blank.short {{ width: 2.5rem; }}
.file {{ font: 0.8rem/1.3 ui-monospace, Menlo, Consolas, monospace; color: var(--muted); word-break: break-all; }}
.url {{ font: 400 0.9rem var(--sans); color: var(--muted); margin-left: 0.4rem; }}
.pnum {{ display: inline-grid; place-items: center; min-width: 1.8rem; height: 1.8rem; padding: 0 0.35rem; border-radius: 0.9rem; background: var(--teal); color: var(--paper); font: 700 0.95rem var(--sans); margin-right: 0.3rem; vertical-align: 0.15rem; }}
ul.spots, ul.tiles, ul.dogs, ul.people {{ list-style: none; margin: 0; padding: 0; }}
.spot {{ display: grid; grid-template-columns: 11rem minmax(0, 1fr) 11.5rem; gap: 1rem; padding: 0.9rem 0; border-top: 1px solid var(--rule); break-inside: avoid; }}
.spot + .spot {{ margin-top: 0; }}
.spot-img {{ width: 11rem; height: 8.25rem; object-fit: contain; background: var(--wash); border-radius: 4px; }}
.spot-body {{ display: grid; gap: 0.3rem; align-content: start; min-width: 0; }}
.spot-id {{ font: 700 0.85rem var(--sans); color: var(--teal); }}
.spot-where {{ font-weight: 700; font-size: 1.05rem; line-height: 1.3; }}
.spot-body .chip {{ justify-self: start; }}
.facts {{ display: grid; grid-template-columns: auto minmax(0, 1fr); gap: 0.1rem 0.6rem; margin: 0.2rem 0 0; font-size: 0.92rem; }}
.facts dt {{ color: var(--muted); }}
.facts dd {{ margin: 0; font-variant-numeric: tabular-nums; }}
.crop, .also {{ font-size: 0.9rem; color: var(--muted); }}
.crop {{ color: var(--soft); }}
.decide {{ display: grid; gap: 0.45rem; align-content: start; font-size: 0.95rem; border-left: 1px solid var(--rule); padding-left: 0.9rem; }}
.decide .line {{ border-bottom: 1.5px solid var(--ink); height: 1.3rem; margin-top: -0.2rem; }}
ul.tiles {{ display: grid; grid-template-columns: repeat(auto-fill, minmax(min(100%, 13.5rem), 1fr)); gap: 1rem; }}
.tile {{ break-inside: avoid; border: 1px solid var(--rule); border-radius: 6px; overflow: hidden; margin: 0 !important; }}
.tile img {{ width: 100%; aspect-ratio: 4 / 3; object-fit: contain; background: var(--wash); display: block; }}
.tile-body {{ padding: 0.55rem 0.65rem 0.7rem; display: grid; gap: 0.3rem; font-size: 0.9rem; }}
.tile-id {{ font-weight: 700; color: var(--teal); display: flex; gap: 0.4rem; align-items: center; flex-wrap: wrap; }}
.tile-meta {{ color: var(--muted); font-size: 0.85rem; }}
.boxes {{ display: flex; flex-wrap: wrap; gap: 0.2rem 0.9rem; align-items: center; }}
ul.dogs {{ display: grid; gap: 0; }}
.dog {{ display: grid; grid-template-columns: 12rem minmax(0, 1fr); grid-template-rows: auto auto; gap: 0.35rem 1rem; padding: 0.85rem 0; border-top: 1px solid var(--rule); break-inside: avoid; margin: 0 !important; }}
.dog-head {{ grid-row: span 2; display: grid; gap: 0.25rem; align-content: start; }}
.dog-head .chip {{ justify-self: start; }}
.dog-name {{ font: 600 1.25rem/1.2 var(--serif); }}
.dog-status, .dog-note {{ font-size: 0.88rem; color: var(--muted); }}
.strip {{ width: 100%; max-width: 100%; border-radius: 4px; }}
.dog-decide {{ font-size: 0.95rem; display: flex; align-items: center; flex-wrap: wrap; gap: 0.3rem; }}
ul.people {{ display: grid; grid-template-columns: repeat(auto-fill, minmax(min(100%, 10.5rem), 1fr)); gap: 1rem; }}
.person {{ break-inside: avoid; margin: 0 !important; display: grid; gap: 0.2rem; font-size: 0.9rem; }}
.person img {{ width: 100%; aspect-ratio: 1; object-fit: cover; border-radius: 6px; background: var(--wash); }}
.person-name {{ font-weight: 700; font-size: 1rem; margin-top: 0.25rem; }}
.person-role {{ color: var(--muted); min-height: 2.6em; }}
.shot {{ display: grid; grid-template-columns: 1.25rem minmax(0, 1fr); gap: 0.6rem; padding: 0.6rem 0; border-top: 1px solid var(--rule); break-inside: avoid; margin: 0 !important; }}
.shot strong {{ display: block; }}
.shot span.need {{ color: var(--muted); font-size: 0.92rem; }}
ul.shots {{ list-style: none; padding: 0; margin: 0.5rem 0 0; }}
.blankrow {{ height: 2.2rem; border-bottom: 1px solid var(--rule); }}
.steps li {{ margin-top: 0.6rem; max-width: 46rem; }}
.callout {{ background: var(--wash); border-radius: 6px; padding: 1rem 1.15rem; margin-top: 1rem; max-width: 46rem; }}
@media (max-width: 40rem) {{
  .spot {{ grid-template-columns: 1fr; }}
  .spot-img {{ width: 100%; height: auto; aspect-ratio: 4 / 3; }}
  .decide {{ border-left: 0; padding-left: 0; border-top: 1px dashed var(--rule); padding-top: 0.6rem; }}
  .dog {{ grid-template-columns: 1fr; }}
  .dog-head {{ grid-row: auto; }}
  h1 {{ font-size: 2.1rem; }}
}}
@page {{ size: letter; margin: 0.5in 0.5in 0.55in; }}
@media print {{
  :root {{ --paper: #fff; --ink: #1d2a30; --muted: #4f5e65; --rule: #cfd8db; --wash: #f1f6f7;
    --teal: #006c8a; --purple: #632f88; --good: #1f7a4d; --soft: #8a5a00; --bad: #b3261e;
    --good-bg: #e6f4ec; --soft-bg: #fbf1dc; --bad-bg: #fbe6e4; color-scheme: light; }}
  body {{ font-size: 10.5pt; }}
  .wrap {{ max-width: none; padding: 0; }}
  h2 {{ break-before: page; margin-top: 0; }}
  h2.keep {{ break-before: auto; margin-top: 1.5rem; }}
  h3 {{ break-after: avoid; }}
  .chip, .pnum {{ -webkit-print-color-adjust: exact; print-color-adjust: exact; }}
}}
</style>

<div class="wrap">

<header>
  <p class="kicker">Peace of Mind Dog Rescue · new website</p>
  <h1>Website Photo Pass</h1>
  <p class="lede">Every photo on the new site, where it sits, whether it is sharp enough for the spot, and a box to tick for each one. Prepared 9 November 2026 for Andrew Z. and Carie.</p>
</header>

<section class="intro">
  <h2 class="keep">How to use this packet</h2>
  <ol class="steps">
    <li><strong>Go page by page.</strong> Each photo has a number, a thumbnail, the words it sits under, and a rating. Tick Keep, Replace (write the photo you want in its place), or Take a new photo.</li>
    <li><strong>Start with the red and amber ratings.</strong> Red means the file is smaller than the space it fills, so it looks blurry everywhere. Amber means it looks fine on an older screen but soft on a phone or a new laptop. Green is sharp.</li>
    <li><strong>For dogs, circle the best main photo.</strong> Each dog has a numbered strip of every photo on their page. Write the number of the photo that should lead, or tick the box to ask the foster for new ones.</li>
    <li><strong>Hand the packet back to Andrew.</strong> The last page explains how the chosen photos get onto the site.</li>
  </ol>
  <div class="key"><span class="chip chip-good">Sharp</span><span class="chip chip-soft">Soft on phones and new laptops</span><span class="chip chip-bad">Too small: blurry on every screen</span></div>

  <h3>What the pass found</h3>
  <table class="sum">
    <tr><td>{n_spots}</td><td>photo spots on {len(section_html)} pages, using {len({p['file'] for p in pp['placements']})} different photos.</td></tr>
    <tr><td>{counts['bad']}</td><td>spots where the photo is too small for the space. All four home slideshow photos are among the weakest: 1,024 to 1,280 px wide filling a screen that wants 2,000, the site's largest size.</td></tr>
    <tr><td>{counts['soft']}</td><td>spots that look soft on phones and new laptops, including every small tile on the Ways to give page.</td></tr>
    <tr><td>{len(dogs)}</td><td>dogs with {all_dog_photos} photos between them. None is larger than {largest:,} px, because they came across from the old site at reduced size, and {under600} are under 600 px.</td></tr>
    <tr><td>{len(db['team'])}</td><td>team portraits, all sharp enough. The question for those is whether each is current and consistent.</td></tr>
    <tr><td>{len(pp['unused'])}</td><td>photos already prepared and sitting unused, at the back of the packet. Some may fill a Replace box straight away.</td></tr>
  </table>

  <h3>What makes the right photo for a spot</h3>
  <div class="cols">
    <div><h4>The picture</h4><ul class="plain">
      <li>A real POMDR dog or person, never stock.</li>
      <li>Eyes in focus, natural light, the dog's face clear of leashes and hands.</li>
      <li>Matches the job of the page: a calm senior dog for adoption, a guardian with their dog for Helping Paw, hands giving care for the clinic.</li>
      <li>The dog is still in the program, or the page is about a happy tail.</li>
    </ul></div>
    <div><h4>The file</h4><ul class="plain">
      <li>The shape matches the spot: wide spots need wide photos, square tiles need room around the subject.</li>
      <li>Full-size originals only. A photo saved from Facebook, a text message or a screenshot is already shrunk and cannot be made sharp again.</li>
      <li>Wide banners: 2,000 px wide or more. Photos in a column: 1,200 px. Small tiles: 800 px. Any phone camera original is 3,000 px or more, which covers all of them.</li>
      <li>Everyone pictured has said yes, especially Helping Paw clients.</li>
    </ul></div>
  </div>
</section>

<section>
  <h2>Part 1 · Photos placed on the pages</h2>
  <p class="lede">These live in the website's own files, so Andrew swaps them. Numbers run page by page in menu order.</p>
  {''.join(section_html)}
</section>

<section>
  <h2>Part 2 · Team portraits</h2>
  <p class="lede">Staff change these in the admin on each person's record. Tick New portrait for anyone whose photo is out of date, low light, or a different style from the rest.</p>
  {''.join(team_html)}
  <div class="callout"><p><strong>Also decide:</strong> {e(', '.join(no_portrait))} each have a profile page on the new site with no portrait, and none of them is on the team list. Keep those pages with a new portrait, or take them down.</p></div>
</section>

<section>
  <h2>Part 3 · The dogs</h2>
  <p class="lede">Every adoptable and hospice dog on the site today, in alphabetical order. The rating is for the main photo, the first one people see. Staff can pick a different main photo or upload new ones on the dog's record in the admin, and the site updates within the hour.</p>
  <ul class="dogs">{''.join(dog_html)}</ul>
</section>

<section>
  <h2>Part 4 · Photos already on hand, not in use</h2>
  <p class="lede">Prepared earlier and sitting unused, mostly from the Bauer Center page that was taken down and older Silver Hearts stories. Write a spot number to use one.</p>
  <ul class="tiles">{spares}</ul>
</section>

<section>
  <h2>Part 5 · Shot list</h2>
  <p class="lede">Photos the site needs that do not exist yet. Add to it as you go through the packet.</p>
  <ul class="shots">
    <li class="shot"><span class="box"></span><div><strong>Four home slideshow photos, wide, 2,000 px or more</strong><span class="need">Adoption, Helping Paw, Our mission, Foster/donate/volunteer. The first thing every visitor sees. Leave calm space on the left, where the words sit.</span></div></li>
    <li class="shot"><span class="box"></span><div><strong>Vet staff giving care</strong><span class="need">For "Every dog arrives ready" on How adopting works, asked for in Carie's round 3. Hands on a dog, a stethoscope, a calm patient.</span></div></li>
    <li class="shot"><span class="box"></span><div><strong>The Ways to give tiles</strong><span class="need">Sixteen small square tiles, most only about 300 px. Square photos with room around the subject, 800 px or more.</span></div></li>
    <li class="shot"><span class="box"></span><div><strong>About us: Monica and the Helping Paw fund</strong><span class="need">Both are about 250 px shown at twice that size.</span></div></li>
    <li class="shot"><span class="box"></span><div><strong>The new building page</strong><span class="need">The Bauer Center page is down until the new building page is written. Exterior, the welcome room, people at work.</span></div></li>
    <li class="shot"><span class="box"></span><div><strong>Dogs flagged in Part 3</strong><span class="need">New photos from fosters for each dog with a red or amber main photo, sent as full-size originals.</span></div></li>
    <li class="blankrow"></li><li class="blankrow"></li><li class="blankrow"></li><li class="blankrow"></li>
  </ul>
</section>

<section>
  <h2>After the meeting: how the photos get onto the site</h2>
  <ol class="steps">
    <li><strong>One shared folder.</strong> Put every chosen photo in one Google Drive folder, full size, and name each file with its number from this packet, for example <span class="file">1.3 home slideshow mission.jpg</span>. Uploading to Drive from a phone keeps the original. Texting or emailing a photo usually shrinks it, so avoid both.</li>
    <li><strong>Page photos (Part 1 and Part 4).</strong> Andrew prepares each one with the site's photo tool, which sizes it for its spot, makes the phone versions, and strips the hidden location data cameras add. The new photo keeps the old file's name, so no page has to change. One batch per group of pages, checked on the preview before it goes live.</li>
    <li><strong>Team portraits (Part 2).</strong> Upload on the person's record in the admin. Square, face centered, plain background, the same style for everyone.</li>
    <li><strong>Dogs (Part 3).</strong> On the dog's record in the admin: upload the new photos, then use Make main photo on the one circled. The admin keeps a 2,000 px master and serves each size on demand.</li>
    <li><strong>The check.</strong> Andrew re-runs the same measurement that made this packet and confirms every red and amber spot is now green, before the site goes live.</li>
  </ol>
</section>

</div>
'''
open(OUT, 'w').write(page)
print('spots', n_spots, dict(counts), 'dogs', len(dogs), 'dog main bad/soft', dog_small, dog_soft, 'pages', len(section_html))
