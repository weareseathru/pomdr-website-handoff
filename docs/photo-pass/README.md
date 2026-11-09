# Website photo pass

`website-photo-pass.html` is the decision packet: every photo on the new site,
where it sits, a sharpness rating from its pixel size against the space it
fills on a 1280px laptop, and tick boxes. The PDF beside it is the same page
printed on Letter paper (not committed: rebuild it with `tools/pdf.mjs`).

Built 2026-11-09 from the preview (pomdr-public.pages.dev). To rebuild or to
run the after-check once replacements are in, from the handoff repo root:

    node docs/photo-pass/tools/crawl.mjs https://pomdr-public.pages.dev <page-list> .playwright-mcp/study/photos/inventory.json
    python3 docs/photo-pass/tools/measure.py .playwright-mcp/study/photos
    python3 docs/photo-pass/tools/build_packet.py
    node docs/photo-pass/tools/pdf.mjs

`<page-list>` comes from `php bin/list-pages.php` in New-Build. The dog strips
and team thumbnails in `img/` were made during the 2026-11-09 run; the page
placement and dog/team groupings (`page-photos.json`, `db-photos.json`, copied with the raw crawl into `data/`) were
derived from the crawl in that session and are the packet's inputs.

Rating rule: a photo wants about twice the width it is shown at. Under 1x is
"Too small", 1x to 1.6x is "Soft on phones and new laptops", above is "Sharp".
Dog main photos are rated against the 532px they fill on a dog's page.
