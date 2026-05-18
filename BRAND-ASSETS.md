# POMDR Brand Assets

This doc tracks where the heavy or canonical brand assets live and which
copies are authoritative.

## In-repo (already tracked)

- `pomdr-website/project/uploads/Brand Guidelines_december2025.pdf` (20 MB)
  Canonical brand guidelines (December 2025). Tracked because the team needs
  it accessible at clone time. Revisit if more large binaries arrive; if so,
  move binaries over the threshold to Git LFS rather than letting the
  history bloat.
- `pomdr-website/project/uploads/*.jpeg`, `*.jpg`, `*.png`
  Dog photos and design references contributed by staff and volunteers.
  These are real photos; never replace with AI-generated images.

## Out of repo (sources of truth)

- **Google Drive: POMDR Brand** — full brand asset library (vector logos,
  print masters, photography raws). Get share access from Andrew.
- **Figma** — design specs and tokens. Pull via Figma MCP when working on
  visual updates.
- **Shelterluv** — long-term source of truth for dog photos and bios once
  the sync adapter is live. Until then, photos live in the WP media library
  on new.pomdr.org.

## Asset rules (from CLAUDE.md §2)

- All dog photos are real, taken by volunteers or staff. Do not generate
  photorealistic AI images of dogs.
- Brand colors: teal `#0099A8` (accents `#008DAF`, `#008BB0`); purple
  `#5B2C6F` (accent `#632F88`). White is the default background.
- Type: Myriad Pro is the brand font; on web, fall back to Source Sans 3
  served via a reputable host.

## Future: Git LFS

If we add a second large binary, migrate `*.pdf` to LFS via:

```bash
git lfs install
git lfs track "*.pdf"
git add .gitattributes
```

History rewrite to retroactively LFS-track the existing 20 MB PDF is
intentionally not done; it's destructive and not worth it for one file.
