#!/usr/bin/env python3
"""Builds docs/photo-pass/photo-placement-plan.html from plan.json (argv[1])."""
import json, html, sys
pl = json.load(open(sys.argv[1]))
e = lambda s: html.escape(str(s), quote=True)
NAMES = {'/': 'Home', '/process/': 'How adopting works', '/why/': 'Why a senior dog', '/hospice/': 'Hospice care',
         '/become-a-foster/': 'Become a foster', '/foster-needs/': 'Foster needs', '/fostering/': 'Fostering',
         '/helping-paw/': 'Helping Paw', '/surrender/': 'Placing your dog', '/perpetual-care-program/': 'Perpetual Care',
         '/volunteer/': 'Volunteer', '/jobs/': 'Job opportunities', '/donate/': 'Ways to give', '/about/': 'About us',
         '/clinic/': 'Veterinary clinic', '/benefit-shop/': 'Benefit Shop', '/media/': 'In the media', '/videos/': 'Videos'}
HERO = {'home-hero-adopt.jpg': 'Home slideshow, slide 1 (Adoption)', 'home-hero-helping-paw.jpg': 'Home slideshow, slide 2 (Helping Paw)',
        'home-hero-mission.jpg': 'Home slideshow, slide 3 (Our mission)', 'home-hero-volunteer.jpg': 'Home slideshow, slide 4 (Volunteer)'}

def rating(w, shown):
    r = w / shown
    return ('bad', 'Too small') if r < 1 else ('soft', 'Soft') if r < 1.6 else ('good', 'Sharp')

def kept(src, shown):
    a, b = src[0] / src[1], shown[0] / shown[1]
    return round(min(a, b) / max(a, b) * 100)

def numbers(r):
    oc, ol = rating(r['old'][0], r['shown'][0]); nc, nl = rating(r['new'][0], r['shown'][0])
    return f'''<table class="nums">
<tr><th></th><th>Now</th><th>Planned</th></tr>
<tr><td>Photo size</td><td>{r['old'][0]:,} × {r['old'][1]:,} px</td><td>{r['new'][0]:,} × {r['new'][1]:,} px</td></tr>
<tr><td>Against the {r['shown'][0]} px it fills</td><td>{r['old'][0]/r['shown'][0]:.1f}× <span class="chip chip-{oc}">{ol}</span></td><td>{min(r['new'][0],2000)/r['shown'][0]:.1f}× <span class="chip chip-{nc}">{nl}</span></td></tr>
<tr><td>Of the photo that survives the crop</td><td>{kept(r['old'], r['shown'])}%</td><td>{kept(r['new'], r['shown'])}%</td></tr>
</table>'''

def where(r):
    return HERO.get(r['file']) or f'{NAMES.get(r["page"], r["page"])}, under "{r["heading"]}"'

up = ''.join(f'''
<li class="row">
  <div class="pair"><figure><img src="plan-img/{r['key']}-before.jpg" alt="" loading="lazy"><figcaption>On the site now</figcaption></figure>
  <figure><img src="plan-img/{r['key']}-after.jpg" alt="" loading="lazy"><figcaption>Original in your library</figcaption></figure></div>
  <div class="text">
    <p class="where">{e(where(r))}</p>
    <p class="proof"><strong>Proof it is the same photo:</strong> the two fingerprints differ in {r['dist']} of 256 points. Unrelated photos differ in about 128.</p>
    {numbers(r)}
    {'' if r['local'] else '<p class="note">This original is only in iCloud. Open it in Photos to download it before export.</p>'}
  </div>
</li>''' for r in pl['upgrade'])

LAB = ['Feeling', 'Light', 'Framing', 'POMDR in view', 'Story']; MAXS = [3, 2, 2, 1, 2]
rep = ''.join(f'''
<li class="row">
  <div class="pair"><figure><img src="plan-img/{r['key']}-before.jpg" alt="" loading="lazy"><figcaption>On the site now</figcaption></figure>
  <figure><img src="plan-img/{r['key']}-after.jpg" alt="" loading="lazy"><figcaption>Planned, cropped as the page will show it</figcaption></figure></div>
  <div class="text">
    <p class="where">{e(where(r))}</p>
    <p class="why"><strong>Why this photo:</strong> {e(r['why'])}</p>
    {numbers(r)}
    <p class="score"><strong>Screening score {sum(r['score'])} of 10.</strong> {' · '.join(f'{l} {s}/{m}' for l, s, m in zip(LAB, r['score'], MAXS))}. {e(r['reason'])}</p>
    {'' if r['local'] else '<p class="note">This original is only in iCloud. Open it in Photos to download it before export.</p>'}
  </div>
</li>''' for r in pl['replace'])

n_up, n_rep = len(pl['upgrade']), len(pl['replace'])
page = f'''<title>Photo Placement Plan</title>
<meta name="claude:viewport-fit-sides" content="cover">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Source+Sans+3:wght@400;600;700&family=Source+Serif+4:opsz,wght@8..60,400;8..60,600&display=swap">
<style>
/* Layout: an evidence ledger. Each row is one spot on the site: the two
   pictures on the left, the reason and the measurements on the right. */
:root {{ --paper:#fff; --ink:#1d2a30; --muted:#56656c; --rule:#d5dee1; --wash:#f1f6f7; --teal:#006c8a; --purple:#632f88;
  --good:#1f7a4d; --soft:#8a5a00; --bad:#b3261e; --good-bg:#e6f4ec; --soft-bg:#fbf1dc; --bad-bg:#fbe6e4;
  --serif:"Source Serif 4",Georgia,serif; --sans:"Source Sans 3","Myriad Pro","Segoe UI",Arial,sans-serif; }}
@media (prefers-color-scheme: dark) {{ :root:not([data-theme="light"]) {{ --paper:#11191c; --ink:#e6eef0; --muted:#a3b3b9; --rule:#2c3a3f; --wash:#18242a; --teal:#5cc3db; --purple:#c7a3e6; --good:#7fd4a3; --soft:#f0c46a; --bad:#ff9a90; --good-bg:#173326; --soft-bg:#342a12; --bad-bg:#3a1c1a; color-scheme:dark; }} }}
:root[data-theme="dark"] {{ --paper:#11191c; --ink:#e6eef0; --muted:#a3b3b9; --rule:#2c3a3f; --wash:#18242a; --teal:#5cc3db; --purple:#c7a3e6; --good:#7fd4a3; --soft:#f0c46a; --bad:#ff9a90; --good-bg:#173326; --soft-bg:#342a12; --bad-bg:#3a1c1a; color-scheme:dark; }}
* {{ box-sizing:border-box; }}
body {{ background:var(--paper); color:var(--ink); font:16px/1.5 var(--sans); }}
.wrap {{ max-width:62rem; margin:0 auto; padding-block:2rem 4rem; padding-left:max(16px, env(safe-area-inset-left, 0px)); padding-right:max(16px, env(safe-area-inset-right, 0px)); }}
h1,h2,h3 {{ font-family:var(--serif); font-weight:600; line-height:1.15; text-wrap:balance; margin:0; }}
h1 {{ font-size:2.6rem; color:var(--purple); }}
h2 {{ font-size:1.8rem; color:var(--purple); margin-top:2.75rem; padding-top:1rem; border-top:3px solid var(--purple); }}
h3 {{ font-size:1.2rem; color:var(--teal); margin:1.5rem 0 .5rem; }}
p {{ margin:0; }}
.kicker {{ font:700 .8rem/1 var(--sans); letter-spacing:.12em; text-transform:uppercase; color:var(--teal); margin-bottom:.75rem; }}
.lede {{ font-size:1.12rem; color:var(--muted); max-width:44rem; margin-top:.7rem; }}
table {{ border-collapse:collapse; font-variant-numeric:tabular-nums; }}
table.sum {{ margin-top:1rem; width:100%; max-width:44rem; }}
table.sum td {{ border-bottom:1px solid var(--rule); padding:.45rem .6rem .45rem 0; vertical-align:top; }}
table.sum td:first-child {{ font-weight:700; color:var(--purple); width:4rem; }}
ul.rows, ul.plain {{ list-style:none; margin:0; padding:0; }}
.row {{ display:grid; grid-template-columns:minmax(0, 23rem) minmax(0, 1fr); gap:1.25rem; padding:1.1rem 0; border-top:1px solid var(--rule); break-inside:avoid; }}
.pair {{ display:grid; grid-template-columns:1fr 1fr; gap:.5rem; align-content:start; }}
figure {{ margin:0; min-width:0; }}
figure img {{ width:100%; display:block; border-radius:4px; background:var(--wash); }}
figcaption {{ font-size:.8rem; color:var(--muted); margin-top:.25rem; line-height:1.25; }}
.text {{ display:grid; gap:.55rem; align-content:start; min-width:0; }}
.where {{ font-weight:700; font-size:1.05rem; line-height:1.3; }}
.why, .proof, .score {{ font-size:.97rem; }}
.note {{ font-size:.9rem; color:var(--soft); }}
table.nums {{ font-size:.9rem; width:100%; }}
table.nums th {{ text-align:left; font-weight:700; color:var(--muted); font-size:.8rem; text-transform:uppercase; letter-spacing:.06em; padding:.15rem .5rem .15rem 0; }}
table.nums td {{ padding:.2rem .5rem .2rem 0; border-top:1px solid var(--rule); }}
table.nums td:first-child {{ color:var(--muted); }}
.chip {{ display:inline-block; font:700 .75rem/1.2 var(--sans); padding:.12rem .4rem; border-radius:.25rem; margin-left:.25rem; }}
.chip-good {{ color:var(--good); background:var(--good-bg); }} .chip-soft {{ color:var(--soft); background:var(--soft-bg); }} .chip-bad {{ color:var(--bad); background:var(--bad-bg); }}
.tests {{ display:grid; grid-template-columns:repeat(auto-fit, minmax(min(100%, 15rem), 1fr)); gap:1rem 1.5rem; margin-top:1rem; }}
.tests > div {{ min-width:0; background:var(--wash); border-radius:6px; padding:.9rem 1rem; }}
.tests h4 {{ font:700 1rem/1.3 var(--sans); margin:0 0 .3rem; }}
.tests p {{ font-size:.94rem; }}
.plain li {{ padding:.55rem 0; border-top:1px solid var(--rule); max-width:46rem; }}
.plain strong {{ display:block; }}
@media (max-width:44rem) {{ .row {{ grid-template-columns:1fr; }} h1 {{ font-size:2rem; }} }}
@page {{ size:letter; margin:.5in; }}
@media print {{ :root {{ --paper:#fff; --ink:#1d2a30; --muted:#4f5e65; --rule:#cfd8db; --wash:#f1f6f7; --teal:#006c8a; --purple:#632f88; --good:#1f7a4d; --soft:#8a5a00; --bad:#b3261e; --good-bg:#e6f4ec; --soft-bg:#fbf1dc; --bad-bg:#fbe6e4; color-scheme:light; }}
  body {{ font-size:10pt; }} .wrap {{ max-width:none; padding:0; }} h2 {{ break-before:page; margin-top:0; }} h2.keep {{ break-before:auto; margin-top:1.5rem; }} .chip, .tests > div {{ -webkit-print-color-adjust:exact; print-color-adjust:exact; }} }}
</style>
<div class="wrap">
<header>
  <p class="kicker">Peace of Mind Dog Rescue · new website</p>
  <h1>Photo Placement Plan</h1>
  <p class="lede">Where each photo goes, why, and the evidence for it. Prepared 12 November 2026 for Andrew Z. Nothing here has been changed on the site yet.</p>
</header>

<section>
  <h2 class="keep">The plan in one look</h2>
  <p class="lede">The rule you set: do not change what already works. So the first move is to keep the photos you like and make them sharp. A photo is only replaced where it is too small and no original could be found.</p>
  <table class="sum">
    <tr><td>65</td><td><strong>Left exactly as they are.</strong> Already sharp for the space they fill.</td></tr>
    <tr><td>{n_up}</td><td><strong>Same photo, made sharp.</strong> The full-size original is in your library, so the picture does not change. This includes all four home slideshow photos.</td></tr>
    <tr><td>{n_rep}</td><td><strong>Replaced from "website photos 2.0".</strong> Too small today, with no original to upgrade from.</td></tr>
    <tr><td>{len(pl['left'])}</td><td><strong>Need something the album does not have.</strong> Listed in part C with what each one needs.</td></tr>
    <tr><td>{112 - n_rep}</td><td><strong>Album photos held in reserve.</strong> For the optional additions in part D, dog pages, and later rounds.</td></tr>
  </table>

  <h3>How each choice is proved</h3>
  <div class="tests">
    <div><h4>1. Measured, not judged</h4><p>Every photo's pixel width is compared with the width it fills on a laptop. Under 1× is too small, 1× to 1.6× is soft on phones and new laptops, above that is sharp. The site stores photos at 2,000 px at most, so the planned figure is capped there.</p></div>
    <div><h4>2. Shown, not described</h4><p>Each planned photo appears cropped to the exact shape of its spot, next to what is there today, with the share of the photo that survives the crop.</p></div>
    <div><h4>3. Scored against your guide</h4><p>Replacements are scored out of 10 on the draft screening guide: feeling, light, framing, POMDR in view, and story. The guide is still waiting for your approval, so treat the scores as provisional.</p></div>
  </div>
</section>

<section>
  <h2>Part A · Same photo, made sharp ({n_up})</h2>
  <p class="lede">Nothing visible changes except the sharpness. Each pair below is the site's copy beside the library original, with the fingerprint test that shows they are one photo.</p>
  <ul class="rows">{up}</ul>
</section>

<section>
  <h2>Part B · Replaced from the album ({n_rep})</h2>
  <p class="lede">Each of these is too small today and has no original in the library. The planned photo is shown in the shape the page will give it. Crops are set by hand at build time, so a face near an edge here will be centered.</p>
  <ul class="rows">{rep}</ul>
  <p class="note" style="margin-top:1rem">Before the Ways to give tiles change: if any current tile shows a named donor, say so and that tile stays, and we look for its original.</p>
</section>

<section>
  <h2>Part C · What the album cannot fix ({len(pl['left'])})</h2>
  <ul class="plain">
    <li><strong>About us: Monica Rua with Zorro (249 px, shown at 532).</strong>A photo of a named person cannot be swapped for another. It needs the original of this exact picture; the library search did not find it.</li>
    <li><strong>Benefit Shop: "What you will find" (600 px, shown at 532).</strong>The album has no photo of the shop. Needs a new one: the storefront or a volunteer at the counter.</li>
    <li><strong>Ways to give: plaque sponsorship, sponsor a suite, sponsor an ad, tribute card.</strong>These show the actual object. Each needs a new photo of that object, about 800 px square.</li>
    <li><strong>Videos page: five video posters.</strong>These are frames from YouTube. The fix is to fetch YouTube's larger frame for each, which needs no photo from you.</li>
  </ul>
</section>

<section>
  <h2>Part D · Optional: making the site fuller</h2>
  <p class="lede">These pages have little or no photography today. Each idea adds a photo where there is none, so each needs your yes and a small layout change. None is in the plan above.</p>
  <ul class="plain">
    <li><strong>Adoption events page.</strong>The July 2026 plaza event: dogs at the water bowl, a volunteer showing a dog on stage.</li>
    <li><strong>Special events page.</strong>September 2026, the three under the "For the Love of Dogs" banner.</li>
    <li><strong>Our team page.</strong>The staff group on the steps, August 2026, above the portraits.</li>
    <li><strong>Helping Paw page body.</strong>Two or three more guardians at home with their dogs, from the twelve in the album.</li>
    <li><strong>Volunteer page.</strong>Volunteers in POMDR shirts with dogs beside the role descriptions.</li>
    <li><strong>Testimonials.</strong>Adopters with their dogs beside the words they wrote.</li>
  </ul>
</section>

<section>
  <h2>What happens on your yes</h2>
  <ul class="plain">
    <li><strong>Part A first.</strong>I export the {n_up} originals, prepare each at the site's sizes with location data stripped, keep every file name, and show you the result locally before anything goes live.</li>
    <li><strong>Part B next, in two waves.</strong>The page photos, then the Ways to give tiles.</li>
    <li><strong>Then the same measurement again.</strong>Every spot in parts A and B should come back sharp.</li>
  </ul>
</section>
</div>
'''
open('docs/photo-pass/photo-placement-plan.html', 'w').write(page)
print('ok', len(page))
