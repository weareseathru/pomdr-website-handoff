## What changed

<!-- One paragraph. What does this PR do? -->

## Why

<!-- Link to plan, issue, or design doc. Why was this work prioritized? -->

## Screenshots / preview

<!-- Drag in before/after screenshots, or a preview URL. -->

## Voice check (CLAUDE.md non-negotiables)

- [ ] No em dashes in copy, alt text, commit messages, or admin-facing strings
- [ ] No "Meet [Name]" openers in dog-facing copy
- [ ] No closing questions in dog-facing copy
- [ ] Approximate ages use `~age` (tilde, no space)
- [ ] Brand colors used: teal `#0099A8`, purple `#5B2C6F`
- [ ] No invented dog data (names, ages, breeds, weights, histories)

## Accessibility check (WCAG 2.2 AA)

- [ ] axe-core scan run on changed pages, zero new violations
- [ ] Keyboard-only walkthrough of new interactive elements
- [ ] Visible focus indicators on every focusable element
- [ ] `prefers-reduced-motion: reduce` honored by any new animation
- [ ] Body text minimum 16px, contrast ratio ≥ 4.5:1
- [ ] No hover-only or time-based interactions

## Performance check (only if applicable)

- [ ] Lighthouse mobile on the most-affected page
- [ ] No regression > 3 points on any Core Web Vital
- [ ] LCP < 2.5s, INP < 200ms, CLS < 0.1

## Test plan

<!-- Bulleted list. What did you do to verify this works end to end? -->

- [ ] Ran `npm run smoke` against a live mirror (green) if this touches a smoke-covered page

## Risk

<!-- Anything reviewers should specifically poke at. Production-data risks,
     irreversibility, downstream effects on emails, social, or staff workflow. -->
