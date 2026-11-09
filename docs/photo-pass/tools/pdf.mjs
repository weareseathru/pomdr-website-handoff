import { chromium } from 'playwright';
import path from 'path';
const b = await chromium.launch();
const p = await b.newPage({ viewport: { width: 1100, height: 1400 } });
const file = 'file://' + path.resolve('docs/photo-pass/website-photo-pass.html');
// The packet is a body fragment (the artifact host adds the skeleton); a
// browser handles that fine for print.
await p.goto(file, { waitUntil: 'networkidle' });
await p.evaluate(async () => { for (const i of document.images) { i.loading = 'eager'; if (!i.complete) await new Promise(r => { i.onload = i.onerror = r; }); } });
await p.pdf({ path: 'docs/photo-pass/Website-Photo-Pass-2026-11-09.pdf', format: 'Letter', printBackground: true, displayHeaderFooter: true,
  headerTemplate: '<span></span>',
  footerTemplate: '<div style="font:8px Arial,sans-serif;color:#667;width:100%;padding:0 0.5in;display:flex;justify-content:space-between"><span>POMDR Website Photo Pass · 9 November 2026</span><span>Page <span class="pageNumber"></span> of <span class="totalPages"></span></span></div>',
  margin: { top: '0.5in', bottom: '0.6in', left: '0.5in', right: '0.5in' } });
await p.emulateMedia({ media: 'print' });
for (const [n, sel] of [['cover', 'header'], ['part1', '.page-block'], ['dogs', '.dog'], ['team', '.people']]) {
  const el = p.locator(sel).first(); await el.scrollIntoViewIfNeeded();
  const bb = await el.boundingBox();
  await p.screenshot({ path: `.playwright-mcp/study/photos/shot-${n}.png`, clip: { x: 0, y: bb.y - 20, width: 1100, height: 900 } });
}
await b.close();
