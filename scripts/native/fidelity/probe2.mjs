import { chromium } from 'playwright';
const slug = process.argv[2] || 'process';
const browser = await chromium.launch();
const ctx = await browser.newContext({ viewport: { width: 1440, height: 900 }, reducedMotion: 'reduce' });
async function probe(url, sel) {
  const page = await ctx.newPage();
  await page.goto(url, { waitUntil: 'networkidle' });
  const rows = await page.evaluate((sel) => {
    const out = [];
    document.querySelectorAll(sel).forEach((el) => {
      const r = el.getBoundingClientRect();
      const t = (el.textContent || '').replace(/\s+/g, ' ').trim().slice(0, 34);
      out.push({ tag: el.tagName, cls: (el.className || '').toString().split(' ')[0].slice(0, 24), h: Math.round(r.height), t });
    });
    return out;
  }, sel);
  await page.close();
  return rows;
}
const ref = await probe(`http://newpomdr-local.local/${slug}/`, '#main-content > * > * > *');
const nat = await probe(`http://newpomdr-local.local/native-staging-${slug}/`, '.et_pb_text_inner > * > *');
const n = Math.max(ref.length, nat.length);
console.log('idx | ref h | nat h | ref el | nat el');
for (let i = 0; i < n; i++) {
  const a = ref[i] || {}, b = nat[i] || {};
  const mark = Math.abs((a.h||0)-(b.h||0)) > 8 ? ' <<<' : '';
  console.log(`${String(i).padStart(2)} | ${String(a.h??'-').padStart(5)} | ${String(b.h??'-').padStart(5)} | ${(a.tag||'')+'.'+(a.cls||'')} "${a.t||''}" | ${(b.tag||'')+'.'+(b.cls||'')} "${b.t||''}"${mark}`);
}
await browser.close();
