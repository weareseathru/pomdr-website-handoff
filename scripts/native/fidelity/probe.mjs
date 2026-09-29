/** Per-section height probe: where does the native page grow? */
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
      const cs = getComputedStyle(el);
      const r = el.getBoundingClientRect();
      out.push({
        cls: (el.className || '').toString().split(' ').slice(0, 3).join('.').slice(0, 40),
        h: Math.round(r.height),
        pt: cs.paddingTop, pb: cs.paddingBottom, mt: cs.marginTop, mb: cs.marginBottom,
      });
    });
    out.push({ cls: 'BODY', h: document.body.scrollHeight });
    return out;
  }, sel);
  await page.close();
  return rows;
}

const ref = await probe(`http://newpomdr-local.local/${slug}/`, '#main-content > *');
const nat = await probe(`http://newpomdr-local.local/native-staging-${slug}/`, '.et_builder_inner_content > .et_pb_section');

console.log('REF (sidecar):');
ref.forEach((r) => console.log(` ${String(r.h).padStart(5)}px pt=${r.pt} pb=${r.pb} ${r.cls}`));
console.log('NATIVE:');
nat.forEach((r) => console.log(` ${String(r.h).padStart(5)}px pt=${r.pt} pb=${r.pb} ${r.cls}`));

await browser.close();
