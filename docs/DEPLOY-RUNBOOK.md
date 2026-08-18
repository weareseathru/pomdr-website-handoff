# First-Go Deploy Runbook (council-hardened v1)

Date: 2026-08-18. Owner: Andrew Z. Scope: deploying the native-Divi
conversion to new.pomdr.org so it works on the first go, without ever
touching the staff's live content there.

Reviewed by the deploy council's QA seat (verdict on the draft:
not-deploy-ready, 8 first-go breakers) and adjudicated inline against the
repo and the live local database. Every breaker below has a fix baked into
the runbook. The remaining council seats hit the session usage cap and can
re-review on request; the QA review answered all five open questions with
evidence, and the two sharpest claims were independently verified against
the code and database before adoption.

---

## 1. The cutover mechanism (decided, evidence-based)

Sidecar templates (page-{slug}.php) never call the_content(), and WordPress
template hierarchy prefers them for ANY page with a matching slug. So:

- Native D5 content is staged on a DRAFT page, validated there, then copied
  (post_content + the three builder metas) onto the EXISTING page record in
  one scripted transaction. Same ID, same slug, same URL, same menus, same
  SEO meta. No slug ever changes. Nothing is ever imported over a target
  page as a new record.
- The sidecar renderer is gated by a single template_include filter keyed on
  a DEDICATED meta: `_pomdr_native = on`, written only by our cutover
  script. **Never key this gate on `_et_pb_use_divi_5`**: verified 2026-08-18,
  27 of the 40 sidecar-covered page records already carry that meta with
  stale base-build Divi content (testimonials holds 54KB of it), and keying
  on it would cut all 27 over to stale content instantly.
- Per-page rollback: restore the pre-conversion dump (content + metas,
  `_pomdr_native` cleared); the sidecar resumes instantly. Template files
  stay in the tree until the whole batch is done and verified.
- After each real cutover, the validator re-runs against the REAL slug:
  asset enqueues are slug-conditional (inc/enqueue.php gates on
  is_front_page() and is_page('adopt'), verified lines 85/121), and body
  classes change with page identity, so draft-stage validation alone is not
  sufficient.

## 2. Rollback that actually restores (breaker 1 fix)

The free All-in-One importer caps at 512MB and the full payload is ~700MB,
so "restore the target's export" is not executable as written. Pick ONE in
P0 and prove it by doing it once on the rehearsal site:

- RECOMMENDED: purchase All-in-One WP Migration Unlimited (~$69 one-time).
  It unblocks both the emergency full restore AND the transplant rehearsal
  import. Decision for Andrew.
- OR: verify the host's own backup-restore (e.g. SiteGround daily backups)
  by actually restoring one on a disposable copy.
- Layered rollbacks stay first-line either way: per-page dump restore
  (seconds), theme-folder swap back (minutes), full restore (last resort,
  budgeted by the rehearsal timing).

## 3. P0 precondition gate (read-only session on the target, before anything)

Record all of these; abort on any surprise:

1. Divi version (must be exactly 5.2.1 before any page lands; if newer,
   STOP and re-run the local upgrade rehearsal first; if older, update
   target first, Andrew only).
2. PHP version (local is 8.2), active plugin list, ACF Pro version +
   license state.
3. Access method truth: wp-cli available? git? SSH/rsync? Or SFTP/cPanel
   only? Steps 6 to 9 below are then rewritten for the toolset that
   actually exists BEFORE deploy day, not improvised.
4. WordPress Importer plugin present (WXR path needs it if wp-cli is absent).
5. Redirection plugin: is the URL monitor on? Record its state; it can
   auto-create 301s when slugs or titles change.
6. The admin USER the import will run as, verified to hold unfiltered_html
   (kses silently strips all D5 block markup otherwise; proven in G1).
7. Target export size vs the 512MB cap; secure the restore path (section 2).
8. State of target's DB field groups (Pets/Events/Team keys) for the
   acf-json precedence check in step 5 below.
9. Staff-content divergence: list pets/events/media edited on target since
   the local clone date (nothing in this runbook overwrites them; the list
   is for awareness and for media-delta planning).

## 4. Transplant rehearsal (the deploy is not attempted until this passes)

Build a disposable local site FROM THE TARGET'S OWN fresh export (not from
a copy of our local site; a local copy shares IDs and URLs with local and
cannot fail on the divergence class this test exists to catch). Execute the
deploy-day sequence (section 5) verbatim under the target's confirmed
toolset. Pass criteria:

- P1 theme swap: every existing target page renders, pets count unchanged,
  zero PHP fatals, acf-json performs zero automatic DB writes.
- P2 media delta: 100% of pilot-page attachments mapped local-ID to
  target-ID; re-run is idempotent.
- P3 page transfer, WXR and Divi Portability head-to-head on the 3 pilot
  pages: three builder metas intact, content byte-equal to the rewritten
  source, zero manual fixes. Whichever path passes clean wins; if both,
  WXR wins on scriptability. (WXR is the primary hypothesis: it is a text
  artifact we can URL/ID-rewrite BEFORE import and it carries postmeta in
  the same file. Portability is admin-UI only, per-page manual, and
  re-imports media unpredictably.)
- P4 reference integrity: zero local-host strings anywhere in imported
  content (including srcset, inline styles, and JSON attrs like
  data-photos); every image resolves 200; edit a mapped attachment's alt on
  the rehearsal site and confirm the render changes (proves ID binding, not
  URL luck).
- P5 full validator re-run on the rehearsal, computed-style diff as the
  cross-machine gate (pixel thresholds relaxed only for font rasterization;
  cross-OS screenshots are never the only target-side check).
- P6 Editor-role save cycle under the Divi Role Editor lockdown: no-op save
  byte-stable, one-edit save passes the class census (staff saves are the
  permanent operating condition, and kses behavior differs by role).
- P7 cutover with Redirection in the target's real monitor state: cut page
  serves 200, no redirect hop, no auto-created rule, nothing new in the
  404 log.
- P8 all three rollback paths executed and TIMED; the times become the
  deploy-day budget. This is where the 512MB restore problem surfaces
  safely instead of mid-incident.
- P9 the entire rehearsal ran only with tools the target actually has.

Fold every deviation back into this runbook, then re-run the rehearsal
clean end to end once.

## 5. Deploy day sequence

1. Staff notified; short no-editing window; one named person (Andrew)
   announces start and finish.
2. Target-side full export taken and stashed (restore point), plus a
   DB-only export (small, always restorable).
3. Theme code ships (git pull, or zip upload + folder swap with the old
   folder kept as divi-child-prev-DATE). acf-json rides inside. Parent Divi
   untouched. The template_include gate ships here but no page carries
   `_pomdr_native` yet, so NOTHING changes visually.
4. Cache purge (et-cache/Divi static CSS regenerate, host cache) and a
   sidecar spot check: the site must look exactly as before.
5. ACF definitions: in wp-admin, resolve field-group sync so the
   version-controlled JSON is authoritative; delete the stale duplicate-key
   Pets/Events DB groups (duplicates verified locally); verify the pets
   edit screen shows the canonical 7-value status checkbox once.
6. Design options seeding: run the SAME idempotent scripts proven locally
   (global colors incl. Customizer routing, Design Variables, plus every
   neutralization option added in Phase 2), each with read-back
   verification. The et_divi options blob is never exported wholesale.
7. Media delta: upload only new local media referenced by converted pages;
   import so attachment rows exist; record the ID map.
8. Page records: JSON-aware rewrite (URLs incl. escaped forms, attachment
   IDs via the map) then import via the rehearsal-proven path AS the
   unfiltered_html user. Post-import, per page: three metas present, parse
   round-trip passes, zero local-host references, h1 and module count
   present (content-presence check; a 404 crawl cannot see a 200-OK empty
   page).
9. Cutovers: per page, the scripted same-record copy sets `_pomdr_native`.
   After each: validator quick-pass at the REAL slug (enqueue-handle
   parity, computed-style spot check, h1/landmarks, console clean).
10. Permalink flush. Redirection 404 log checked.
11. Smoke suite in order: live $1 donation through LGL FIRST; the three
    form-prefill paths; adopt filter/sort; one dog page per status value;
    events; videos; og:title/og:image/canonical parity on key pages
    (Mailchimp and Metricool consume these); a no-JS pass on one native
    page (progressive enhancement rule); Mailchimp template image check;
    redirect spot checks; full-site crawl for 404s AND content presence;
    target-side computed-style spot check (body font stack, section
    padding, .btn box + radius) on one native page.
12. Only after smoke passes: staff notified, window closed. Nothing is
    announced done before the donation test clears (CLAUDE.md: done means
    verified).

## 6. Staging hygiene during the whole conversion window

- Native staging drafts stay drafts (authenticated validation), carry
  noindex if ever published for testing, and are excluded from sitemaps.
- After each cutover: link-integrity crawl asserting zero internal
  references to staging slugs and zero nav items pointing at retired
  records.
- Local remains the layout workshop; new.pomdr.org remains the content
  source of truth for pets/events/team/media at all times.

## 7. Open decisions for Andrew

1. Purchase All-in-One Unlimited (~$69) as the restore path (recommended),
   or rely on verified host-level restore.
2. Approve the P0 read-only inspection session on new.pomdr.org (nothing
   is written; it fills in section 3's unknowns).
3. Re-convene the four missed council seats after the session cap resets,
   or accept the QA-seat review + inline adjudication as sufficient for
   proceeding to the rehearsal.
