/**
 * Fidelity harness v2 (council-amended program).
 *
 * Per page, per width:
 *  0. REF-DRIFT GATE: live reference vs frozen golden must be clean; any
 *     cluster halts the page (the standard may not move silently).
 *  1. Native vs GOLDEN pixel comparison via CLUSTER detection: any
 *     contiguous diff cluster with a bounding box of 24px or more in both
 *     dimensions fails; antialias noise does not. Top clusters reported
 *     with coordinates for localization.
 *  2. Text-anchored DOM parity (live ref vs live native): same words must
 *     have identical typography (zero tolerance), geometry within 1px
 *     width / 2px height, and the same wrapped line count.
 *  3. Semantic parity: heading outline (levels + text) exact, landmark
 *     counts (main/nav/footer), picture/webp source count, island
 *     structural presence.
 *  4. Mobile gates at 390 (no h-scroll, tap targets, min font: never
 *     worse than the reference). Zoom/senior gate runs in the pilot loop.
 *
 * Usage: node fidelity.mjs culture ... | --all
 * Requires goldens (node golden.mjs) or fails loudly.
 */
import fs from 'node:fs';
import path from 'node:path';
import { PNG } from 'pngjs';
import pixelmatch from 'pixelmatch';
import { BASE, WIDTHS, MASKS, ALL, dirs, launch, newCtx, settle, gotoRobust, refPath, here } from './common.mjs';

const CLUSTER_MIN = 24;      // px, both dimensions of a cluster bbox
const CELL = 8;              // diff-grid resolution for clustering

const args = process.argv.slice(2);
const slugs = args.includes('--all') ? ALL : args.filter((a) => !a.startsWith('--'));
if (!slugs.length) { console.error('usage: node fidelity.mjs --all | <slugs>'); process.exit(1); }

const manifestPath = path.join(here, 'goldens-manifest.json');
if (!fs.existsSync(manifestPath)) { console.error('No goldens. Run golden.mjs first.'); process.exit(1); }

function readPng(p) { return PNG.sync.read(fs.readFileSync(p)); }

/** Pixel diff -> clusters of contiguous difference (bbox in px). */
function clusters(aPath, bPath) {
  const a = readPng(aPath); const b = readPng(bPath);
  const w = Math.min(a.width, b.width);
  const h = Math.max(a.height, b.height);
  const pad = (img) => {
    if (img.height === h && img.width === w) return img;
    const out = new PNG({ width: w, height: h });
    PNG.bitblt(img, out, 0, 0, Math.min(img.width, w), Math.min(img.height, h), 0, 0);
    return out;
  };
  const A = pad(a); const B = pad(b);
  const diff = new PNG({ width: w, height: h });
  const n = pixelmatch(A.data, B.data, diff.data, w, h, { threshold: 0.12 });

  // Downsample diff mask to CELL grid, then BFS clusters.
  const gw = Math.ceil(w / CELL); const gh = Math.ceil(h / CELL);
  const grid = new Uint8Array(gw * gh);
  for (let y = 0; y < h; y++) {
    for (let x = 0; x < w; x++) {
      const i = (y * w + x) * 4;
      if (diff.data[i] === 255 && diff.data[i + 1] === 0) { // pixelmatch red
        grid[Math.floor(y / CELL) * gw + Math.floor(x / CELL)] = 1;
      }
    }
  }
  const seen = new Uint8Array(gw * gh);
  const found = [];
  for (let gy = 0; gy < gh; gy++) {
    for (let gx = 0; gx < gw; gx++) {
      const idx = gy * gw + gx;
      if (!grid[idx] || seen[idx]) continue;
      let minX = gx, maxX = gx, minY = gy, maxY = gy, cells = 0;
      const q = [idx]; seen[idx] = 1;
      while (q.length) {
        const c = q.pop(); cells++;
        const cy = Math.floor(c / gw), cx = c % gw;
        minX = Math.min(minX, cx); maxX = Math.max(maxX, cx);
        minY = Math.min(minY, cy); maxY = Math.max(maxY, cy);
        for (const [dx, dy] of [[1, 0], [-1, 0], [0, 1], [0, -1]]) {
          const nx = cx + dx, ny = cy + dy;
          if (nx < 0 || ny < 0 || nx >= gw || ny >= gh) continue;
          const ni = ny * gw + nx;
          if (grid[ni] && !seen[ni]) { seen[ni] = 1; q.push(ni); }
        }
      }
      const bw = (maxX - minX + 1) * CELL; const bh = (maxY - minY + 1) * CELL;
      if (bw >= CLUSTER_MIN && bh >= CLUSTER_MIN) {
        found.push({ x: minX * CELL, y: minY * CELL, w: bw, h: bh, cells });
      }
    }
  }
  found.sort((p, q) => q.cells - p.cells);
  return { diffPixels: n, clusters: found, heightDelta: Math.abs(a.height - b.height) };
}

/** Text-anchored style + geometry + wrap collection. */
async function collectDom(page) {
  return page.evaluate(() => {
    const PROPS = ['font-size', 'font-family', 'font-weight', 'line-height', 'color', 'letter-spacing', 'text-transform'];
    const out = { text: {}, headings: [], landmarks: {}, webpSources: 0 };
    const scope = document.querySelector('#main-content') || document.querySelector('main') || document.body;
    scope.querySelectorAll('h1, h2, h3, h4, p, li, a, em, figcaption, blockquote, span').forEach((el) => {
      const own = Array.from(el.childNodes).some((n) => n.nodeType === 3 && n.textContent.trim().length > 3);
      if (!own) return;
      const text = (el.textContent || '').replace(/\s+/g, ' ').trim();
      if (text.length < 4) return;
      const key = el.tagName + '|' + text.slice(0, 70);
      if (out.text[key]) return;
      const cs = getComputedStyle(el);
      const r = el.getBoundingClientRect();
      if (r.width === 0) return; // hidden (TB remnants)
      const rec = { w: Math.round(r.width), h: Math.round(r.height) };
      PROPS.forEach((p) => { rec[p] = cs.getPropertyValue(p); });
      const lh = parseFloat(cs.lineHeight) || (parseFloat(cs.fontSize) * 1.5);
      rec.lines = Math.max(1, Math.round(r.height / lh));
      out.text[key] = rec;
    });
    scope.querySelectorAll('h1, h2, h3, h4').forEach((h) => {
      const r = h.getBoundingClientRect();
      if (r.width > 0) out.headings.push(h.tagName + ':' + (h.textContent || '').replace(/\s+/g, ' ').trim().slice(0, 50));
    });
    out.landmarks = {
      main: document.querySelectorAll('main, [role="main"]').length,
      nav: document.querySelectorAll('nav').length,
      footer: document.querySelectorAll('footer').length,
    };
    out.webpSources = scope.querySelectorAll('picture source[type="image/webp"]').length;
    return out;
  });
}

async function mobileGates(page) {
  return page.evaluate(() => {
    const doc = document.documentElement;
    const scope = document.querySelector('#main-content') || document.body;
    const small = [];
    scope.querySelectorAll('a, button').forEach((el) => {
      const r = el.getBoundingClientRect();
      if (r.width === 0 || r.height === 0) return;
      const size = Math.min(r.width, r.height);
      if (size < 44 && (el.textContent || '').trim().length > 0) small.push(Math.round(size));
    });
    let minFont = 999;
    scope.querySelectorAll('p, li').forEach((el) => {
      if (!(el.textContent || '').trim()) return;
      const r = el.getBoundingClientRect();
      if (r.width === 0) return;
      minFont = Math.min(minFont, parseFloat(getComputedStyle(el).fontSize));
    });
    return { hscroll: doc.scrollWidth - doc.clientWidth, smallCount: small.length, minFont };
  });
}

const browser = await launch();
const summary = [];

for (const slug of slugs) {
  const masks = MASKS[slug] || [];
  const rep = { slug, widths: {}, styleFails: [], geomFails: [], wrapFails: [], semantic: {}, refDrift: false, pass: true };

  for (const width of WIDTHS) {
    const golden = path.join(dirs.goldens, `${slug}-${width}.png`);
    if (!fs.existsSync(golden)) { rep.widths[width] = { error: 'NO GOLDEN' }; rep.pass = false; continue; }

    const ctx = await newCtx(browser, width);
    const ref = await ctx.newPage();
    const nat = await ctx.newPage();
    await gotoRobust(ref, `${BASE}${refPath(slug)}`);
    await gotoRobust(nat, `${BASE}/native-staging-${slug}/`);
    await settle(ref, masks);
    await settle(nat, masks);

    // Functional smoke BEFORE any scoring: the native page must actually be
    // the page (a draft/404 otherwise poisons every number downstream).
    const [refH1, natH1] = await Promise.all([ref, nat].map((p) => p.evaluate(() => {
      const h = Array.from(document.querySelectorAll('h1')).find((x) => x.getBoundingClientRect().width > 0);
      return h ? (h.textContent || '').replace(/\s+/g, ' ').trim() : '';
    })));
    if (!natH1 || natH1 !== refH1) {
      rep.widths[width] = { error: `NOT RENDERED: ref h1 "${refH1}" vs native "${natH1}"` };
      rep.pass = false;
      await ctx.close();
      continue;
    }

    const refShot = path.join(dirs.shots, `${slug}-${width}-ref.png`);
    const natShot = path.join(dirs.shots, `${slug}-${width}-nat.png`);
    await ref.screenshot({ path: refShot, fullPage: true });
    await nat.screenshot({ path: natShot, fullPage: true });

    // 0. Reference drift gate.
    const drift = clusters(golden, refShot);
    if (drift.clusters.length) {
      rep.refDrift = true; rep.pass = false;
      rep.widths[width] = { error: `REF DRIFT: ${drift.clusters.length} clusters, first at y=${drift.clusters[0].y}` };
      await ctx.close();
      continue;
    }

    // 1. Native vs golden clusters.
    const px = clusters(golden, natShot);
    rep.widths[width] = {
      clusters: px.clusters.length,
      heightDelta: px.heightDelta,
      top: px.clusters.slice(0, 4).map((c) => `y${c.y}+${c.h}x${c.w}`),
    };

    // 2-3. DOM parity at the hard gates.
    if (width === 390 || width === 1440) {
      const [R, N] = [await collectDom(ref), await collectDom(nat)];
      for (const key of Object.keys(R.text)) {
        if (!N.text[key]) continue;
        const r = R.text[key]; const n = N.text[key];
        const styleBad = ['font-size', 'font-family', 'font-weight', 'line-height', 'color', 'letter-spacing', 'text-transform']
          .filter((p) => r[p] !== n[p]).map((p) => `${p}:${r[p]}->${n[p]}`);
        if (styleBad.length) rep.styleFails.push({ width, key: key.slice(0, 60), styleBad });
        if (Math.abs(r.w - n.w) > 1 || Math.abs(r.h - n.h) > 2) {
          rep.geomFails.push({ width, key: key.slice(0, 60), ref: `${r.w}x${r.h}`, nat: `${n.w}x${n.h}` });
        }
        if (r.lines !== n.lines) rep.wrapFails.push({ width, key: key.slice(0, 60), ref: r.lines, nat: n.lines });
      }
      if (width === 1440) {
        rep.semantic = {
          headingsMatch: JSON.stringify(R.headings) === JSON.stringify(N.headings),
          headingsRef: R.headings.length, headingsNat: N.headings.length,
          mainRef: R.landmarks.main, mainNat: N.landmarks.main,
          webpRef: R.webpSources, webpNat: N.webpSources,
        };
      }
    }
    if (width === 390) {
      const [gr, gn] = [await mobileGates(ref), await mobileGates(nat)];
      rep.mobile = { ref: gr, nat: gn, worse: gn.hscroll > Math.max(1, gr.hscroll) || gn.smallCount > gr.smallCount || gn.minFont < Math.min(gr.minFont, 16) };
    }
    await ctx.close();
  }

  const clusterTotal = Object.values(rep.widths).reduce((s, w) => s + (w.clusters || 0), 0);
  rep.pass = rep.pass && !rep.refDrift && clusterTotal === 0 && rep.styleFails.length === 0
    && rep.geomFails.length === 0 && rep.wrapFails.length === 0
    && (rep.semantic.headingsMatch !== false) && (rep.semantic.mainNat >= 1)
    && (rep.semantic.webpNat >= (rep.semantic.webpRef || 0)) && !(rep.mobile && rep.mobile.worse);

  fs.writeFileSync(path.join(dirs.reports, `${slug}.json`), JSON.stringify(rep, null, 1));
  const worst = Object.entries(rep.widths).map(([w, v]) => `${w}:${v.error ? 'ERR' : (v.clusters ?? '?')}`).join(' ');
  console.log(`${rep.pass ? 'PASS' : 'FAIL'} ${slug}  clusters[${worst}]  style=${rep.styleFails.length} geom=${rep.geomFails.length} wrap=${rep.wrapFails.length} main=${rep.semantic.mainNat ?? '?'} webp=${rep.semantic.webpNat ?? '?'}/${rep.semantic.webpRef ?? '?'}`);
  summary.push({ slug, pass: rep.pass });
}

fs.writeFileSync(path.join(dirs.reports, 'summary.json'), JSON.stringify(summary, null, 1));
console.log(`\n${summary.filter((s) => s.pass).length}/${summary.length} pages pass`);
await browser.close();
