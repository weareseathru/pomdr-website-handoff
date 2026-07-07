// Playwright config for the POMDR smoke suite.
//
// Base URL comes from SMOKE_BASE_URL, defaulting to the Local mirror. Point it
// at any environment before merge or deploy, for example:
//   SMOKE_BASE_URL=https://newpomdr-local.local npm run smoke
//
// The Local mirror serves a self-signed cert, so HTTPS errors are ignored.
// Deliberately no visual-diff / screenshot assertions here (flaky in CI); this
// suite is a tripwire, not a visual-regression harness.

const { defineConfig, devices } = require('@playwright/test');

const BASE_URL = process.env.SMOKE_BASE_URL || 'https://newpomdr-local.local';

module.exports = defineConfig({
  testDir: __dirname,
  // Keep the whole suite fast (target: under 90s). Short, firm timeouts.
  timeout: 25000,
  expect: { timeout: 6000 },
  fullyParallel: true,
  forbidOnly: !!process.env.CI,
  retries: 0,
  reporter: [['list']],
  use: {
    baseURL: BASE_URL,
    ignoreHTTPSErrors: true, // Local mirror uses a self-signed certificate
    navigationTimeout: 15000,
    actionTimeout: 8000,
    ...devices['Desktop Chrome'],
  },
});
