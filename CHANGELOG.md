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

## [session-2-3] - 2026-05-17

### Added

- 7-status dog badge system covering `available`, `foster_needed`,
  `foster_needed_dated`, `adoption_pending`, `recently_adopted`, `hospice`,
  `courtesy_listing`.
- Campaign ribbons on adoption cards (`forever_starts_here`, `helping_paw`).
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
