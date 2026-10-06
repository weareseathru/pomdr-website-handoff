import { chromium } from 'playwright';
const browser = await chromium.launch();
const ctx = await browser.newContext({ viewport: { width: 1440, height: 900 } });

const TARGETS = [
  { name: 'care-h2', find: 'h2', text: 'Caring for the people', props: ['font-size', 'font-weight'] },
  { name: 'they-h3', find: 'h3', text: 'They are already', props: ['font-size'] },
];

for (const url of ['http://newpomdr-local.local/why/', 'http://newpomdr-local.local/native-staging-why/']) {
  const side = url.includes('native') ? 'NAT' : 'REF';
  const page = await ctx.newPage();
  await page.goto(url, { waitUntil: 'networkidle' });
  await page.evaluate((targets) => {
    targets.forEach((t, i) => {
      const el = Array.from(document.querySelectorAll(t.find))
        .find((e) => (e.textContent || '').trim().startsWith(t.text) && e.getBoundingClientRect().width > 0);
      if (el) el.setAttribute('data-probe', t.name);
    });
  }, TARGETS);
  const client = await ctx.newCDPSession(page);
  await client.send('DOM.enable'); await client.send('CSS.enable');
  const doc = await client.send('DOM.getDocument');
  for (const t of TARGETS) {
    const q = await client.send('DOM.querySelector', { nodeId: doc.root.nodeId, selector: `[data-probe="${t.name}"]` });
    if (!q.nodeId) { console.log(side, t.name, 'NOT FOUND'); continue; }
    const st = await client.send('CSS.getMatchedStylesForNode', { nodeId: q.nodeId });
    const hits = [];
    for (const m of (st.matchedCSSRules || [])) {
      const props = (m.rule.style.cssProperties || []).filter((p) => t.props.includes(p.name) && p.value);
      if (props.length) hits.push(`${m.rule.selectorList.text.slice(0, 70)} { ${props.map((p) => p.name + ':' + p.value).join(';')} }`);
    }
    let inh = 0;
    for (const level of (st.inherited || [])) {
      inh++;
      for (const m of (level.matchedCSSRules || [])) {
        const props = (m.rule.style.cssProperties || []).filter((p) => t.props.includes(p.name) && p.value);
        if (props.length) hits.push(`[inherited-${inh}] ${m.rule.selectorList.text.slice(0, 70)} { ${props.map((p) => p.name + ':' + p.value).join(';')} }`);
      }
    }
    const computed = await page.evaluate(({ name, prop }) => {
      const el = document.querySelector(`[data-probe="${name}"]`);
      return getComputedStyle(el).getPropertyValue(prop);
    }, { name: t.name, prop: t.props[0] });
    console.log(`${side} ${t.name} computed=${computed}`);
    hits.slice(-5).forEach((h) => console.log('   ', h));
  }
  await page.close();
}
await browser.close();
