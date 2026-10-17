import { chromium } from 'playwright';
const browser = await chromium.launch();
const ctx = await browser.newContext({ viewport: { width: 1440, height: 900 } });
for (const url of ['http://newpomdr-local.local/process/', 'http://newpomdr-local.local/native-staging-process/']) {
  const page = await ctx.newPage();
  await page.goto(url, { waitUntil: 'networkidle' });
  const m = await page.evaluate(() => {
    const y = (sel) => { const el = document.querySelector(sel); return el ? Math.round(el.getBoundingClientRect().top + scrollY) : null; };
    const h1 = Array.from(document.querySelectorAll('h1')).find(h => h.getBoundingClientRect().width > 0);
    return {
      h1top: h1 ? Math.round(h1.getBoundingClientRect().top + scrollY) : null,
      header: y('.page-header'),
      firstSection: y('.section'),
      bodyH: document.body.scrollHeight,
    };
  });
  console.log(url.includes('native') ? 'NAT' : 'REF', JSON.stringify(m));
  await page.close();
}
await browser.close();
