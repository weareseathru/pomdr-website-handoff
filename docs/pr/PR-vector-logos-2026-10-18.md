Title: Vector logos: SVG logotype, logomark home button, footer mark

Open at: https://github.com/ronniereid/pomdr/compare/main...feat/vector-logos?expand=1

Eight SVGs from POMDR's vector master replace `logo.png`.

**What changed**
- Header is a `<picture>`: the compact logotype (mark + Peace of Mind + DOG RESCUE) at 56px above 30rem; the thick brush mark as a 44px square home button at 30rem and below, so the name is never squeezed. One `<img>`, alt "Peace of Mind Dog Rescue home", width/height from each viewBox, all through `asset_url()`.
- Footer carries the white thick mark (48px) above the organisation's name; decorative (`alt=""`, `aria-hidden="true"`), the h2 stays text.
- `logo.png` stays one release as a fallback; nothing references it. Favicons unchanged (the paw).
- Design.md section 2 (file table) and section 8 item 4; `public/assets/img/README.md` gains a Logos section with the rules.

**Checks**
- `php bin/check.php`: 134 files, no problems.
- Header and footer screenshots on the homepage, /adopt/, a dog page and /404/ at 320, 390, 768, 1024 and 1440, plus 390 at 200 percent text: logo crisp and unstretched (rendered ratio equals viewBox ratio), compact above 30rem and mark below, home link with the accessible name, logo + Donate + menu on one row at 320 and 390 with nothing overflowing, footer mark decorative, h2 text.
- Chrome performance trace, homepage: CLS 0.00 at 1440 and 0.00 at 390 (3x, touch).
- Local `build/build.php --output=<scratch>`: all eight SVGs copied, built pages reference them, served as image/svg+xml.
- Design-only: no change under admin/, build/, bin/, db/, deploy/, includes/db.php.

The SVGs are final files from POMDR and are not edited, optimised or recoloured here. Each is about 44 KB compressed.

Screenshot grid: `.playwright-mcp/study/shots/logos2/GRID.png` in the design project (attach to the PR by dragging it into the description).

Generated with [Claude Code](https://claude.com/claude-code)
