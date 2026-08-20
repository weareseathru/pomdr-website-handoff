import { chromium } from 'playwright';
const browser = await chromium.launch();
const ctx = await browser.newContext({ viewport: { width: 1440, height: 900 } });
for (const url of ['http://newpomdr-local.local/why/', 'http://newpomdr-local.local/native-staging-why/']) {
  const page = await ctx.newPage();
  await page.goto(url, { waitUntil: 'networkidle', timeout: 60000 });
  await page.evaluate(() => {
    const el = Array.from(document.querySelectorAll('h2'))
      .find((e) => (e.textContent || '').includes('Caring for the people') && e.getBoundingClientRect().width > 0);
    if (el) el.setAttribute('data-probe', 'x');
  });
  const client = await ctx.newCDPSession(page);
  await client.send('DOM.enable'); await client.send('CSS.enable');
  const doc = await client.send('DOM.getDocument');
  const q = await client.send('DOM.querySelector', { nodeId: doc.root.nodeId, selector: '[data-probe="x"]' });
  const st = await client.send('CSS.getMatchedStylesForNode', { nodeId: q.nodeId });
  console.log(url.includes('native') ? 'NAT all matched (last 8):' : 'REF all matched (last 8):');
  (st.matchedCSSRules || []).slice(-8).forEach((m) => {
    const fs = (m.rule.style.cssProperties || []).find((p) => p.name === 'font-size');
    console.log('  ', m.rule.selectorList.text.slice(0, 80), fs ? '| fs:' + fs.value : '');
  });
  await page.close();
}
await browser.close();
