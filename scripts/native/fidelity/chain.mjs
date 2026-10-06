import { chromium } from 'playwright';
const browser = await chromium.launch();
const ctx = await browser.newContext({ viewport: { width: 1440, height: 900 } });
for (const url of ['http://newpomdr-local.local/why/', 'http://newpomdr-local.local/native-staging-why/']) {
  const page = await ctx.newPage();
  await page.goto(url, { waitUntil: 'networkidle', timeout: 60000 });
  const chain = await page.evaluate(() => {
    let el = Array.from(document.querySelectorAll('h2'))
      .find((e) => (e.textContent || '').includes('Caring for the people') && e.getBoundingClientRect().width > 0);
    const out = [];
    while (el && out.length < 12) {
      out.push(`${el.tagName}.${(el.className || '').toString().split(' ').slice(0, 3).join('.')} fs=${getComputedStyle(el).fontSize}`);
      el = el.parentElement;
    }
    return out;
  });
  console.log(url.includes('native') ? 'NAT' : 'REF');
  chain.forEach((c) => console.log('  ', c));
  await page.close();
}
await browser.close();
