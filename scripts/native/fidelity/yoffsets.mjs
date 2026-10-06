/** Walk matched text elements top-to-bottom; report where Y drift enters. */
import { launch, newCtx, gotoRobust, BASE } from './common.mjs';

const slug = process.argv[2] || 'process';
const width = Number(process.argv[3] || 1440);
const browser = await launch();
const ctx = await newCtx(browser, width);

async function collect(url) {
  const page = await ctx.newPage();
  await gotoRobust(page, url);
  await page.waitForLoadState('networkidle').catch(() => {});
  await page.evaluate(async () => { await document.fonts.ready; });
  const rows = await page.evaluate(() => {
    const scope = document.querySelector('#main-content') || document.body;
    const out = [];
    scope.querySelectorAll('h1,h2,h3,h4,p,li,a,figcaption,span').forEach((el) => {
      const own = Array.from(el.childNodes).some((n) => n.nodeType === 3 && n.textContent.trim().length > 3);
      if (!own) return;
      const t = (el.textContent || '').replace(/\s+/g, ' ').trim();
      if (t.length < 4) return;
      const r = el.getBoundingClientRect();
      if (r.width === 0) return;
      out.push({ key: el.tagName + '|' + t.slice(0, 45), y: Math.round(r.top + scrollY) });
    });
    return out;
  });
  await page.close();
  return new Map(rows.map((r) => [r.key, r.y]));
}

const ref = await collect(`${BASE}/${slug}/`);
const nat = await collect(`${BASE}/native-staging-${slug}/`);
let prev = 0;
for (const [key, ry] of ref) {
  if (!nat.has(key)) continue;
  const d = nat.get(key) - ry;
  if (d !== prev) {
    console.log(`drift ${prev >= 0 ? '+' : ''}${prev} -> ${d >= 0 ? '+' : ''}${d}  at refY=${ry}  ${key}`);
    prev = d;
  }
}
console.log('final drift:', prev);
await browser.close();
