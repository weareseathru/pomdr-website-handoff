/** Shared config + capture helpers for the fidelity toolchain. */
import { chromium } from 'playwright';
import fs from 'node:fs';
import path from 'node:path';
import crypto from 'node:crypto';

export const BASE = 'http://newpomdr-local.local';
export const WIDTHS = [390, 767, 980, 1440]; // council: include Divi's own stack boundaries
export const here = path.dirname(new URL(import.meta.url).pathname);
export const dirs = {
  goldens: path.join(here, 'goldens'),
  shots: path.join(here, 'shots'),
  reports: path.join(here, 'reports'),
};
Object.values(dirs).forEach((d) => fs.mkdirSync(d, { recursive: true }));

/** Live-data islands and third-party frames, masked IDENTICALLY everywhere
 * (goldens included), so staff content edits never break a frozen golden. */
export const MASKS = {
  'foster-needs': ['.dogs-grid'],
  videos: ['.vids-grid'],
  events: ['.events-glance', '.event-card', '.event-photo'],
  'adoption-questionnaire': ['iframe'],
  'helping-paw-application': ['iframe'],
  'intake-questionnaire': ['iframe'],
  'volunteer-application': ['iframe'],
  'mailing-list': ['iframe'],
  'sponsor-a-dog': ['iframe'],
};

export const ALL = [
  'culture', 'process', 'why', 'bauer-center', 'benefit-shop', 'clinic',
  'jobs', 'surrender', 'helping-paw', 'volunteer', 'about', 'fostering',
  'donate', 'testimonials', 'media', 'news', 'perpetual-care-program',
  'perpetual-care-faq', 'maxs-fund', 'forms', 'recources', 'terms',
  'privacy', 'mailing-list', 'sponsor-a-dog', 'adoption-questionnaire',
  'helping-paw-application', 'intake-questionnaire', 'volunteer-application',
  'events', 'videos', 'foster-needs',
];

export async function launch() {
  return chromium.launch();
}

export async function newCtx(browser, width) {
  return browser.newContext({
    viewport: { width, height: 900 },
    reducedMotion: 'reduce',
    deviceScaleFactor: 1,
  });
}

/** Deterministic settle: fonts, lazyload, scroll home, masks. */
export async function settle(page, masks) {
  await page.waitForLoadState('networkidle').catch(() => {});
  await page.evaluate(async () => { await document.fonts.ready; });
  await page.evaluate(async () => {
    const h = document.body.scrollHeight;
    for (let y = 0; y < h; y += 800) { window.scrollTo(0, y); await new Promise((r) => setTimeout(r, 40)); }
    window.scrollTo(0, 0);
  });
  await page.waitForTimeout(400);
  if (masks && masks.length) {
    await page.evaluate((sels) => {
      sels.forEach((sel) => document.querySelectorAll(sel).forEach((el) => {
        el.style.visibility = 'hidden';
      }));
    }, masks);
  }
}

export function sha256(file) {
  return crypto.createHash('sha256').update(fs.readFileSync(file)).digest('hex');
}
