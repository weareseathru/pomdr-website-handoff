#!/bin/sh
# POMDR deploy verification crawl.
#
#   ./scripts/deploy-verify.sh https://the-test-bed.example
#
# Crawls every public page and checks the canaries born from the 2026-09-01
# deploy failure (docs/DEPLOY-FORENSICS-2026-09-01.md):
#   - HTTP 200 and an <h1> on every page
#   - pomdr-build meta present (current functions.php + enqueue layer alive)
#   - pomdr-chrome.css enqueued (it hides the legacy Theme Builder chrome,
#     including its mangled code band, until those records are removed)
#   - no "newpomdr-local" anywhere (dev-machine addresses; skipped when the
#     crawl target IS the dev machine, where its own hostname is expected)
#   - no "maximum-scale" (zoom stays enabled for older visitors)
#   - no PHP fatals/warnings in the output
# Exits 0 only when every page passes.

BASE="${1:?usage: deploy-verify.sh <base-url, e.g. https://new-test-bed.example>}"
BASE="${BASE%/}"
case "$BASE" in
  *newpomdr-local*) CHECK_LEAK=0 ;;
  *) CHECK_LEAK=1 ;;
esac

PAGES="/ /adopt/ /adopted/ /process/ /why/ /about/ /about/culture/
/bauer-center/ /benefit-shop/ /clinic/ /jobs/ /surrender/ /helping-paw/
/volunteer/ /fostering/ /foster-needs/ /donate/ /testimonials/ /media/
/news/ /events/ /videos/ /perpetual-care-program/ /perpetual-care-faq/
/maxs-fund/ /forms/ /recources/ /terms/ /privacy/ /mailing-list/
/sponsor-a-dog/ /adoption-questionnaire/ /helping-paw-application/
/intake-questionnaire/ /volunteer-application/ /donation/ /thanks/
/courtesy-listings/ /hospice/"

fails=0
total=0
tmp="$(mktemp)"
trap 'rm -f "$tmp"' EXIT

for p in $PAGES; do
  total=$((total + 1))
  code="$(curl -sL --max-time 45 -o "$tmp" -w '%{http_code}' "$BASE$p")"
  flags=""
  [ "$code" = "200" ] || flags="$flags HTTP-$code"
  grep -q "<h1" "$tmp"                    || flags="$flags NO-H1"
  grep -q 'name="pomdr-build"' "$tmp"     || flags="$flags NO-BUILD-MARKER"
  grep -q "assets/css/pomdr-chrome.css"  "$tmp" || flags="$flags NO-CHROME-CSS"
  if [ "$CHECK_LEAK" = "1" ]; then
    grep -q "newpomdr-local" "$tmp"       && flags="$flags LOCAL-URL-LEAK"
  fi
  grep -q "maximum-scale" "$tmp"          && flags="$flags ZOOM-LOCKED"
  grep -q "Fatal error\|critical error\|Warning: " "$tmp" && flags="$flags PHP-ERROR"
  if [ -n "$flags" ]; then
    fails=$((fails + 1))
    echo "FAIL $p --$flags"
  fi
done

# Content spot checks on the pages whose emptiness was the silent symptom.
for pair in "/:dog-card" "/foster-needs/:dog-card" "/videos/:vid-item"; do
  page="${pair%%:*}"; needle="${pair#*:}"
  n="$(curl -sL --max-time 45 "$BASE$page" | grep -c "$needle")"
  if [ "$n" -eq 0 ]; then
    fails=$((fails + 1))
    echo "FAIL $page -- NO '$needle' CONTENT (dynamic island empty)"
  fi
done

if [ "$fails" -eq 0 ]; then
  echo "PASS: all $total pages green (build marker, no leaks, no errors, islands populated)"
else
  echo "$fails FAILURE(S) across $total pages. Do not show this site to anyone yet."
  exit 1
fi
