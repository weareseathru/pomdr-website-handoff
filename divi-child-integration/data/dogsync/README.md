# Dog roster sync data

Scraped from the live site (peaceofminddogrescue.org) 2026-07-21: the complete
adoptable / courtesy / hospice / foster-needed rosters with writeups, statuses,
contacts, sponsors, and photo URLs, plus the full adopted-names wall (3,539).

Import with `scripts/sync-dogs.php` (idempotent, batched; see its header).
To refresh before launch: re-run the scrape (the agent prompt is documented in
docs/PARITY-AUDIT-2026-07-21.md context), replace these JSON files, re-run the
importer. Names are the match key; statuses use the canonical Title Case set.
