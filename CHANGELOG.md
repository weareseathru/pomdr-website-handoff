# Changelog

All notable changes to the POMDR website redesign live here. Format follows
[Keep a Changelog](https://keepachangelog.com/en/1.1.0/). Versioning is
session-based until we cut a real release tag.

## [Unreleased]

### Added

- Repo hygiene baseline: `.gitattributes` (LF line endings), full `.gitignore`,
  PR template, CODEOWNERS, CHANGELOG, README, BRAND-ASSETS pointer doc.
- Voice-rule CI: `scripts/check-voice.sh` runs in PRs (diff mode) and on
  pushes to main (report-only full scan). Opt-in pre-commit hook at
  `.githooks/pre-commit` shares the same script.

## [divi-integration-v2] - 2026-06-09

### Added

- WordPress implementation layer as a Divi **child theme**,
  `divi-child-integration/` (theme name "POMDR 2026"): production
  `functions.php`, `single-pets.php` dog template, and reference templates
  under `temp/`, with the v2 redesign layered on top via a single additive
  require of `inc/enqueue.php`.
- v2 design system in `assets/css/pomdr-design.css` plus `gallery.js`
  (lightbox) and `pomdr-nav.js`, enqueued over the production Divi theme.
- Homepage redesign v2 in the static prototype: image-forward hero, removed
  ticker, new sections.
- `docs/LOCAL-BRIDGE.md`: how the Git repo, the Local WP mirror, and
  new.pomdr.org connect (symlinked theme, WP Migrate DB for content).
- `docs/SESSION-CHECKLIST.md`: end-of-session voice-check, commit, push, and
  PR routine.

### Changed

- **Architecture decision (2026-06-06): keep Divi.** The integration target
  moved from a clean FSE block theme (`pomdr-2026/`) to the Divi child theme
  above, to retain the staff-familiar Divi editing surface and the working
  ACF `pets` CPT, LGL forms, and Redirection. Recorded in CLAUDE.md §3 and
  STACK.md §7; `WP-SCAFFOLD-NOTES.md` is now historical record only.
- Confirmed local environment runs PHP 8.2 (`php-8.2.29`).
- Removed an inherited em dash from the adoption form title (voice rule).

### Notes

- `inc/acf-fields.php` and `inc/redirects.php` are intentionally NOT loaded
  by `functions.php` (the ACF plugin owns the live field groups; redirects
  are opt-in). Wire either only after confirming with Andrew.

## [session-2-3] - 2026-05-17

### Added

- 7-status dog badge system covering `available`, `foster-needed`,
  `foster-needed-dated`, `adoption-pending`, `recently-adopted`, `hospice`,
  `courtesy-listing`.
- Campaign ribbons on adoption cards (`forever-starts-here`, `helping-paw`).
- Vanilla-JS gallery lightbox with keyboard navigation, focus trap, and
  reduced-motion respect.
- Impact section redesign pairing stat tiles with photos and voice-compliant
  micro-stories.
- Status strips and per-status bottom cta-strips on all 12 dog detail pages.
- Skip links, visible focus rings, and `prefers-reduced-motion` gates.
- Session 3 docking strategy in `WP-SCAFFOLD-NOTES.md`: ACF field map, REST
  API surface, FSE template map, Shelterluv sync plan, 10-step execution
  order.

### Changed

- `--blue-700` (#006c8a) replaces `--blue` on body-size text to clear WCAG
  AA 4.5:1.
- Footer column heads normalized to `h2` for landmark structure.
- Programs cards wired with real `href`s; "furever" corrected to "forever".

## [session-1] - 2026-05-09

### Added

- Initial commit of the static prototype, audit deliverables (INVENTORY,
  VOICE, STACK, DESIGN-TOKENS, GAP-ANALYSIS), redirect map, and draft
  `theme.json` for the future WordPress block theme.
