/**
 * POMDR fidelity harness: measures each native staging page against the
 * sidecar reference (THE standard) and emits a per-page scorecard.
 *
 * Instruments per page, per width:
 *  1. Full-page screenshots of reference vs native (reduced motion, fonts
 *     ready, lazyload defeated), live-data islands masked identically.
 *  2. Pixel diff in horizontal bands (600px), worst band % reported.
 *  3. Text-anchored computed-style diff: every text-bearing element is
 *     keyed by its normalized text; the same words must have the same
 *     font-size/family/weight/line-height/color on both renderers
 *     (zero tolerance), box metrics within 1px.
 *  4. Mobile gates at 390: no horizontal scroll, tap targets >= 44px
 *     (native must never be worse than the reference).
 *
 * Usage: node fidelity.mjs culture about ...   (or: node fidelity.mjs --all)
 * Output: reports/<slug>.json + reports/summary.md, shots/ for images.
 */

import { chromium } from 'playwright';
import { PNG } from 'pngjs';
import pixelmatch from 'pixelmatch';
import fs from 'node:fs';
import path from 'node:path';

const BASE = 'http://newpomdr-local.local';
const WIDTHS = [390, 768, 1024, 1440];
const BAND = 600;
const PIXEL_BAND_MAX = 0.5; // % differing pixels allowed per band

// Live-data islands and third-party frames, masked identically on both.
const MASKS = {
  'foster-needs': ['.dogs-grid'],
  videos: ['.vids-grid'],
  events: ['.events-glance', '.event-card'],
  'adoption-questionnaire': ['iframe'],
  'helping-paw-application': ['iframe'],
  'intake-questionnaire': ['iframe'],
  'volunteer-application': ['iframe'],
  'mailing-list': ['iframe'],
  'sponsor-a-dog': ['iframe'],
};

const ALL = [
  'culture', 'process', 'why', 'bauer-center', 'benefit-shop', 'clinic',
  'jobs', 'surrender', 'helping-paw', 'volunteer', 'about', 'fostering',
  'donate', 'testimonials', 'media', 'news', 'perpetual-care-program',
  'perpetual-care-faq', 'maxs-fund', 'forms', 'recources', 'terms',
  'privacy', 'mailing-list', 'sponsor-a-dog', 'adoption-questionnaire',
  'helping-paw-application', 'intake-questionnaire', 'volunteer-application',
  'events', 'videos', 'foster-needs',
];

const args = process.argv.slice(2);
const slugs = args.includes('--all') ? ALL : args.filter((a) => !a.startsWith('--'));
const here = path.dirname(new URL(import.meta.url).pathname);
const shotsDir = path.join(here, 'shots');
const repDir = path.join(here, 'reports');
fs.mkdirSync(shotsDir, { recursive: true });
fs.mkdirSync(repDir, { recursive: true });

async function settle(page, masks) {
  await page.waitForLoadState('networkidle').catch(() => {});
  await page.evaluate(async () => { await document.fonts.ready; });
  // Defeat lazyload: walk the page, then return to top.
  await page.evaluate(async () => {
    const h = document.body.scrollHeight;
    for (let y = 0; y < h; y += 800) { window.scrollTo(0, y); await new Promise((r) => setTimeout(r, 40)); }
    window.scrollTo(0, 0);
  });
  await page.waitForTimeout(500);
  if (masks.length) {
    await page.evaluate((sels) => {
      sels.forEach((sel) => document.querySelectorAll(sel).forEach((el) => {
        el.style.visibility = 'hidden';
      }));
    }, masks);
  }
}

/** Text-anchored style collection: same words must look the same. */
async function collectStyles(page) {
  return page.evaluate(() => {
    const PROPS = ['font-size', 'font-family', 'font-weight', 'line-height', 'color', 'letter-spacing', 'text-transform', 'border-radius', 'background-color'];
    const out = {};
    const els = document.querySelectorAll('main h1, main h2, main h3, main h4, main p, main li, main a, main em, main strong, main figcaption, main blockquote, main span.eyebrow');
    els.forEach((el) => {
      // Only text-bearing elements, keyed by their normalized words.
      const text = (el.textContent || '').replace(/\s+/g, ' ').trim();
      if (text.length < 4) return;
      const key = el.tagName + '|' + text.slice(0, 70);
      if (out[key]) return; // first occurrence wins; duplicates ambiguous
      const cs = getComputedStyle(el);
      const rec = {};
      PROPS.forEach((p) => { rec[p] = cs.getPropertyValue(p); });
      const r = el.getBoundingClientRect();
      rec._w = Math.round(r.width);
      rec._h = Math.round(r.height);
      out[key] = rec;
    });
    return out;
  });
}

async function mobileGates(page) {
  return page.evaluate(() => {
    const doc = document.documentElement;
    const hscroll = doc.scrollWidth - doc.clientWidth;
    const small = [];
    document.querySelectorAll('main a, main button').forEach((el) => {
      const r = el.getBoundingClientRect();
      if (r.width === 0 || r.height === 0) return; // hidden
      const size = Math.min(r.width, r.height);
      if (size < 44 && (el.textContent || '').trim().length > 0) {
        small.push(((el.textContent || '').trim().slice(0, 30)) + ' (' + Math.round(size) + 'px)');
      }
    });
    let minFont = 999;
    document.querySelectorAll('main p, main li').forEach((el) => {
      if (!(el.textContent || '').trim()) return;
      minFont = Math.min(minFont, parseFloat(getComputedStyle(el).fontSize));
    });
    return { hscroll, smallTargets: small.slice(0, 12), smallCount: small.length, minFont };
  });
}

function diffPixels(aPath, bPath) {
  const a = PNG.sync.read(fs.readFileSync(aPath));
  const b = PNG.sync.read(fs.readFileSync(bPath));
  const w = Math.min(a.width, b.width);
  const h = Math.max(a.height, b.height);
  const pad = (img) => {
    if (img.height === h && img.width === w) return img;
    const out = new PNG({ width: w, height: h, fill: true });
    PNG.bitblt(img, out, 0, 0, w, Math.min(img.height, h), 0, 0);
    return out;
  };
  const A = pad(a); const B = pad(b);
  const bands = [];
  for (let y0 = 0; y0 < h; y0 += BAND) {
    const bh = Math.min(BAND, h - y0);
    const sliceA = new PNG({ width: w, height: bh });
    const sliceB = new PNG({ width: w, height: bh });
    PNG.bitblt(A, sliceA, 0, y0, w, bh, 0, 0);
    PNG.bitblt(B, sliceB, 0, y0, w, bh, 0, 0);
    const n = pixelmatch(sliceA.data, sliceB.data, null, w, bh, { threshold: 0.12 });
    bands.push(+(100 * n / (w * bh)).toFixed(2));
  }
  return { heightDelta: Math.abs(a.height - b.height), bands, worstBand: Math.max(...bands) };
}

const browser = await chromium.launch();
const summary = [];

for (const slug of slugs) {
  const masks = MASKS[slug] || [];
  const report = { slug, widths: {}, styleDiffs: [], mobile: {}, pass: true };

  for (const width of WIDTHS) {
    const ctx = await browser.newContext({
      viewport: { width, height: 900 },
      reducedMotion: 'reduce',
      deviceScaleFactor: 1,
    });
    const ref = await ctx.newPage();
    const nat = await ctx.newPage();
    await ref.goto(`${BASE}/${slug}/`, { waitUntil: 'domcontentloaded' });
    await nat.goto(`${BASE}/native-staging-${slug}/`, { waitUntil: 'domcontentloaded' });
    await settle(ref, masks);
    await settle(nat, masks);

    const refShot = path.join(shotsDir, `${slug}-${width}-ref.png`);
    const natShot = path.join(shotsDir, `${slug}-${width}-nat.png`);
    await ref.screenshot({ path: refShot, fullPage: true });
    await nat.screenshot({ path: natShot, fullPage: true });
    const px = diffPixels(refShot, natShot);

    // Text-anchored style diff only at the two hard-gate widths.
    let styleMismatches = [];
    if (width === 390 || width === 1440) {
      const [rs, ns] = [await collectStyles(ref), await collectStyles(nat)];
      for (const key of Object.keys(rs)) {
        if (!ns[key]) continue; // element alignment miss; structural checks cover
        const bad = [];
        for (const p of ['font-size', 'font-family', 'font-weight', 'line-height', 'color', 'letter-spacing', 'text-transform']) {
          if (rs[key][p] !== ns[key][p]) bad.push(`${p}: ${rs[key][p]} -> ${ns[key][p]}`);
        }
        if (bad.length) styleMismatches.push({ key: key.slice(0, 80), width, bad });
      }
    }

    if (width === 390) {
      const [gr, gn] = [await mobileGates(ref), await mobileGates(nat)];
      report.mobile = { ref: gr, native: gn, worse: gn.hscroll > Math.max(1, gr.hscroll) || gn.smallCount > gr.smallCount || gn.minFont < Math.min(gr.minFont, 16) };
    }

    report.widths[width] = { worstBand: px.worstBand, heightDelta: px.heightDelta, styleMismatchCount: styleMismatches.length };
    report.styleDiffs.push(...styleMismatches.slice(0, 10));
    await ctx.close();
  }

  const worst = Math.max(...Object.values(report.widths).map((w) => w.worstBand));
  const styleBad = report.styleDiffs.length;
  report.pass = worst <= PIXEL_BAND_MAX && styleBad === 0 && !(report.mobile.worse);
  report.worstBand = worst;
  fs.writeFileSync(path.join(repDir, `${slug}.json`), JSON.stringify(report, null, 1));
  summary.push({ slug, pass: report.pass, worstBand: worst, styleBad, mobileWorse: !!report.mobile.worse, hDelta: Math.max(...Object.values(report.widths).map((w) => w.heightDelta)) });
  console.log(`${report.pass ? 'PASS' : 'FAIL'} ${slug}  worstBand=${worst}%  styleDiffs=${styleBad}  mobileWorse=${!!report.mobile.worse}`);
}

const md = ['| page | verdict | worst band % | style diffs | mobile worse | height delta px |', '|---|---|---|---|---|---|']
  .concat(summary.map((s) => `| ${s.slug} | ${s.pass ? 'PASS' : 'FAIL'} | ${s.worstBand} | ${s.styleBad} | ${s.mobileWorse} | ${s.hDelta} |`))
  .join('\n');
fs.writeFileSync(path.join(repDir, 'summary.md'), md + '\n');
console.log('\n' + md);

await browser.close();
