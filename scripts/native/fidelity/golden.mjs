/**
 * Golden capture: freezes the sidecar reference (THE standard) as
 * screenshots per page per width, masked identically to scoring runs,
 * with a committed manifest (sha256 per shot, git SHA, CSS hashes) so
 * drift and tampering are detectable. Goldens themselves stay gitignored.
 *
 * Usage: node golden.mjs --all | node golden.mjs culture process ...
 * Re-running overwrites: only do that deliberately (a design-standard
 * change signed by Andrew), never to make a diff pass.
 */
import { execSync } from 'node:child_process';
import fs from 'node:fs';
import path from 'node:path';
import { BASE, WIDTHS, MASKS, ALL, dirs, launch, newCtx, settle, gotoRobust, sha256, here } from './common.mjs';

const args = process.argv.slice(2);
const slugs = args.includes('--all') ? ALL : args.filter((a) => !a.startsWith('--'));
if (!slugs.length) { console.error('usage: node golden.mjs --all | <slugs>'); process.exit(1); }

const manifestPath = path.join(here, 'goldens-manifest.json');
const manifest = fs.existsSync(manifestPath) ? JSON.parse(fs.readFileSync(manifestPath, 'utf8')) : { pages: {} };

const repo = path.resolve(here, '../../..');
manifest.capturedAt = new Date().toISOString();
manifest.gitSha = execSync('git rev-parse HEAD', { cwd: repo }).toString().trim();
manifest.cssHashes = {};
for (const f of ['tokens.css', 'pomdr.css', 'pomdr-design.css', 'native-pages.css', 'a11y.css']) {
  const p = path.join(repo, 'divi-child-integration/assets/css', f);
  if (fs.existsSync(p)) manifest.cssHashes[f] = sha256(p);
}

const browser = await launch();
for (const slug of slugs) {
  const masks = MASKS[slug] || [];
  manifest.pages[slug] = manifest.pages[slug] || {};
  for (const width of WIDTHS) {
    const ctx = await newCtx(browser, width);
    const page = await ctx.newPage();
    await gotoRobust(page, `${BASE}/${slug}/`);
    await settle(page, masks);
    const file = path.join(dirs.goldens, `${slug}-${width}.png`);
    await page.screenshot({ path: file, fullPage: true });
    manifest.pages[slug][width] = { sha256: sha256(file), bytes: fs.statSync(file).size };
    await ctx.close();
    console.log(`golden ${slug} @${width}`);
  }
}
fs.writeFileSync(manifestPath, JSON.stringify(manifest, null, 1));
console.log(`manifest updated: ${Object.keys(manifest.pages).length} pages, git ${manifest.gitSha.slice(0, 8)}`);
await browser.close();
