import { chromium } from 'playwright';
const browser = await chromium.launch();
const ctx = await browser.newContext({ viewport: { width: 1440, height: 900 } });
const page = await ctx.newPage();
await page.goto('http://newpomdr-local.local/native-staging-culture/', { waitUntil: 'networkidle', timeout: 60000 });
await page.evaluate(() => {
  const p = document.querySelectorAll('.value-card p')[0];
  p.setAttribute('data-probe', 'x');
});
const client = await ctx.newCDPSession(page);
await client.send('DOM.enable'); await client.send('CSS.enable');
const doc = await client.send('DOM.getDocument');
const q = await client.send('DOM.querySelector', { nodeId: doc.root.nodeId, selector: '[data-probe="x"]' });
const st = await client.send('CSS.getMatchedStylesForNode', { nodeId: q.nodeId });
(st.matchedCSSRules || []).slice(-8).forEach((m) => {
  const fs = (m.rule.style.cssProperties || []).find((p) => p.name === 'font-size');
  console.log(m.rule.selectorList.text.slice(0, 85), fs ? '| fs:' + fs.value : '');
});
await browser.close();
