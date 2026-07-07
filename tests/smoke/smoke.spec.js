// POMDR smoke suite: a small, boring tripwire.
//
// Because the redesign layers CSS/JS/templates over Divi (risk G1), regressions
// are silent until a human looks. These checks run on every PR (locally, against
// a live mirror) and before every deploy. They are deliberately shallow: HTTP
// health, one h1 per page, a main landmark, no first-party console errors or
// failed requests, plus a few per-page anchors (dog cards, the Adopt/Sponsor
// CTAs, donate amounts, the newsletter form). Not a test pyramid.
//
// Base URL: SMOKE_BASE_URL (see playwright.config.js).

const { test, expect } = require('@playwright/test');

// Third-party hosts we allowlist on purpose. Their embeds (LGL forms for
// adopt/donate/sponsor, YouTube videos, Mailchimp newsletter) throw their own
// console noise and occasional request failures that are outside our control.
// A regression in OUR code must never hide behind these, so the match is by
// explicit host fragment only.
const THIRD_PARTY = [
  'lglforms.com',        // LGL: adoption, donation, sponsor, volunteer forms
  'youtube.com',         // YouTube video embeds
  'youtu.be',
  'ytimg.com',
  'mailchimp.com',       // Mailchimp newsletter
  'list-manage.com',
  'mcusercontent.com',
  'googleapis.com',      // Google Fonts stylesheet
  'gstatic.com',         // Google Fonts files
  'google-analytics.com',
  'googletagmanager.com',
  'facebook.com',        // social embeds/pixels
  'instagram.com',
  'doubleclick.net',
];
const isThirdParty = (url) => !!url && THIRD_PARTY.some((h) => url.includes(h));

// Attach collectors for first-party console errors and failed/4xx-5xx requests.
function watch(page) {
  const consoleErrors = [];
  const failed = [];
  page.on('console', (msg) => {
    if (msg.type() !== 'error') return;
    const loc = (msg.location() && msg.location().url) || '';
    if (isThirdParty(loc)) return;
    consoleErrors.push(`${loc || '(no url)'}: ${msg.text()}`);
  });
  page.on('requestfailed', (req) => {
    if (isThirdParty(req.url())) return;
    failed.push(`${req.url()} (${(req.failure() && req.failure().errorText) || 'failed'})`);
  });
  page.on('response', (res) => {
    if (res.status() >= 400 && !isThirdParty(res.url())) {
      failed.push(`${res.url()} (HTTP ${res.status()})`);
    }
  });
  return { consoleErrors, failed };
}

// The checks every page must pass.
async function checkPage(page, path) {
  const w = watch(page);
  const res = await page.goto(path, { waitUntil: 'load' });

  expect(res, `${path} should respond`).not.toBeNull();
  expect(res.status(), `${path} should return HTTP 200`).toBe(200);

  // Exactly one main landmark (a <main> element or role="main").
  const main = page.locator('main, [role="main"]');
  await expect(main, `${path} should have exactly one main landmark`).toHaveCount(1);

  // Exactly one h1, inside that landmark (page chrome h1s are outside main).
  await expect(main.locator('h1'), `${path} should have exactly one h1 in main`).toHaveCount(1);

  // Let late resources settle, then assert a clean console and network.
  await page.waitForTimeout(1500);
  expect(w.consoleErrors, `${path} first-party console errors`).toEqual([]);
  expect(w.failed, `${path} first-party failed requests`).toEqual([]);

  return w;
}

test('home is healthy', async ({ page }) => {
  await checkPage(page, '/');
});

test('adopt lists dogs and shows the status tabs', async ({ page }) => {
  await checkPage(page, '/adopt/');
  // More than zero dog cards render (the CPT grid is wired).
  expect(await page.locator('.dog-card').count(), 'adopt should render dog cards').toBeGreaterThan(0);
  // The status-group tabs exist (Other Adoptable / Hospice / Adopted).
  await expect(page.locator('.dog-tabs')).toHaveCount(1);
  expect(await page.locator('.dog-tab').count(), 'status tabs should exist').toBeGreaterThan(0);
});

test('a dog detail page (discovered) shows the Adopt and Sponsor CTAs', async ({ page }) => {
  // Discover the first pet link from the adopt page rather than hardcoding an ID.
  await page.goto('/adopt/');
  const href = await page.locator('a[href*="/pets/"]').first().getAttribute('href');
  expect(href, 'adopt page should link to at least one dog').toBeTruthy();

  await checkPage(page, href);

  // Adopt CTA: a real link to the questionnaire carrying the encoded dogname.
  const adopt = page.locator('a[href*="dogname="]');
  expect(await adopt.count(), 'dog page should have an Adopt CTA with dogname').toBeGreaterThan(0);
  await expect(adopt.first()).toHaveAttribute('href', /dogname=.+/);

  // Sponsor CTA is present. NOTE: on `main` its href is a placeholder "#"; the
  // dogname-carrying sponsor href lands with the sponsor-CTA work (PR #5) once
  // the sponsor-form destination is confirmed. Until then this asserts the CTA
  // exists, which is enough to catch an accidental removal.
  await expect(page.locator('a.et_pb_button', { hasText: 'Sponsor' }).first()).toBeVisible();
});

test('donate shows amount buttons that each carry their value', async ({ page }) => {
  await checkPage(page, '/donate/');
  const btns = page.locator('.amount-btn');
  const n = await btns.count();
  expect(n, 'donate should render amount buttons').toBeGreaterThan(0);

  // D1 is OPEN in the roadmap: donate CTAs point at the legacy processor and the
  // chosen amount is carried as `data-amount` on each button (wired by JS to the
  // legacy POMDRDonation.php `initialdonation` param), not yet as an href param.
  // We assert the implemented reality: every button carries a non-empty amount.
  // When D1 is resolved to hrefs with `initialdonation`, tighten this to the href.
  for (let i = 0; i < n; i++) {
    const amount = await btns.nth(i).getAttribute('data-amount');
    expect(amount, `amount button ${i} should carry its value`).toBeTruthy();
  }
});

test('volunteer is healthy', async ({ page }) => {
  await checkPage(page, '/volunteer/');
});

test('mailing-list shows a usable form or a graceful fallback', async ({ page }) => {
  await checkPage(page, '/mailing-list/');
  // Risk B2: the newsletter backend is not wired to Mailchimp yet. The page must
  // show either a form that submits somewhere or a graceful fallback (a contact
  // link), never a form that posts nowhere.
  const forms = page.locator('form');
  if (await forms.count() > 0) {
    const action = await forms.first().getAttribute('action');
    expect(action, 'newsletter form must submit somewhere').toBeTruthy();
    expect(action, 'newsletter form action must not be an empty anchor').not.toBe('#');
  } else {
    await expect(
      page.locator('a[href^="mailto:"], a[href*="contact"]').first(),
      'with no form, a contact fallback must be present'
    ).toBeVisible();
  }
});
