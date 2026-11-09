// Every picture every page prints: <img> (with srcset) and CSS background images,
// with the section it sits in, its alt text and its box on a 1280px screen.
import { chromium } from 'playwright';
import fs from 'fs';
const base = process.argv[2] || 'https://pomdr-public.pages.dev';
const pages = fs.readFileSync(process.argv[3], 'utf8').split('\n').filter(Boolean);
const b = await chromium.launch();
const ctx = await b.newContext({ viewport: { width: 1280, height: 900 } });
const out = [];
let i = 0;
async function work() {
  const p = await ctx.newPage();
  while (i < pages.length) {
    const path = pages[i++];
    try {
      const r = await p.goto(base + path, { waitUntil: 'domcontentloaded', timeout: 45000 });
      const rows = await p.evaluate(() => {
        const heading = el => {
          let n = el;
          while (n && n !== document.body) {
            let s = n.previousElementSibling;
            while (s) {
              const h = s.matches('h1,h2,h3') ? s : s.querySelector('h1,h2,h3');
              if (h) return h.textContent.trim().replace(/\s+/g, ' ');
              s = s.previousElementSibling;
            }
            n = n.parentElement;
            const own = n && n.querySelector(':scope > h1, :scope > h2, :scope > h3, :scope > header h1, :scope > header h2');
            if (own && own.compareDocumentPosition(el) & Node.DOCUMENT_POSITION_FOLLOWING) return own.textContent.trim().replace(/\s+/g, ' ');
          }
          return '';
        };
        const zone = el => el.closest('header.site-header, .site-header') ? 'header' : el.closest('footer') ? 'footer' : 'main';
        const res = [];
        document.querySelectorAll('img').forEach(im => {
          const r = im.getBoundingClientRect();
          const sec = im.closest('section,[class*="hero"],article,li');
          res.push({ kind: 'img', src: im.getAttribute('src'), srcset: im.getAttribute('srcset') || '', alt: im.getAttribute('alt'),
            w: Math.round(r.width), h: Math.round(r.height), heading: heading(im), zone: zone(im),
            cls: (im.className || '') + ' < ' + (sec ? sec.className : ''), card: (im.closest('a') || {}).href || '' });
        });
        document.querySelectorAll('main *, header *').forEach(el => {
          const bg = getComputedStyle(el).backgroundImage;
          if (bg && bg.includes('url(') && !bg.includes('.svg')) {
            const r = el.getBoundingClientRect();
            res.push({ kind: 'bg', src: bg.match(/url\("?([^")]+)"?\)/)[1], srcset: '', alt: el.getAttribute('aria-label') || '', w: Math.round(r.width), h: Math.round(r.height), heading: heading(el), zone: zone(el), cls: el.className, card: '' });
          }
        });
        return res;
      });
      for (const row of rows) out.push({ page: path, status: r.status(), ...row });
      process.stderr.write('.');
    } catch (e) { out.push({ page: path, error: String(e).slice(0, 120) }); }
  }
  await p.close();
}
await Promise.all([work(), work(), work(), work()]);
fs.writeFileSync(process.argv[4], JSON.stringify(out, null, 1));
console.log('\nrows', out.length, 'errors', out.filter(r => r.error).length);
await b.close();
