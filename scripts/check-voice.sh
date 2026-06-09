#!/usr/bin/env bash
#
# check-voice.sh
#
# Enforces the CLAUDE.md voice rules on user-facing source files.
# Used by CI (.github/workflows/voice-check.yml) and by the optional
# pre-commit hook (.githooks/pre-commit).
#
# Rules enforced:
#   1. No em dash (U+2014). Use commas, periods, parens instead.
#   2. No "Meet [Name]," openers in user-facing copy.
#   3. No closing rhetorical questions in dog-facing copy.
#
# Modes:
#   --all                  scan everything under $ROOT (default: pomdr-website/project)
#   --diff [BASE]          scan only files changed vs BASE (default: origin/main)
#   --staged               scan only files currently staged for commit
#
# Scope filters:
#   Only checks .html, .jsx, .js, .css under pomdr-website/project/.
#   Skips vendor/, node_modules/, uploads/, dist/, build/.
#   Skips markdown by design (docs cite em dashes in audit tables).
#
# Exit codes:
#   0  clean
#   1  voice violation found
#   2  script misuse / missing tool
#
set -euo pipefail

MODE="--all"
BASE="origin/main"
ROOT="pomdr-website/project"

while [[ $# -gt 0 ]]; do
  case "$1" in
    --all) MODE="--all"; shift ;;
    --diff) MODE="--diff"; shift; if [[ $# -gt 0 && "$1" != --* ]]; then BASE="$1"; shift; fi ;;
    --staged) MODE="--staged"; shift ;;
    --root) shift; ROOT="$1"; shift ;;
    -h|--help)
      sed -n '2,30p' "$0"
      exit 0
      ;;
    *) echo "check-voice.sh: unknown arg: $1" >&2; exit 2 ;;
  esac
done

if [[ ! -d "$ROOT" ]]; then
  echo "check-voice.sh: directory not found: $ROOT" >&2
  exit 2
fi

# Build the candidate file list.
#
# Note: we use a `while read` loop rather than `mapfile`/`readarray` because
# mapfile is a bash 4+ builtin. macOS ships bash 3.2, where this script also
# needs to run (local pre-commit hook + manual runs). The loop is portable to
# both. Process substitution `< <(...)` is fine in bash 3.2.
CANDIDATES=()
case "$MODE" in
  --all)
    while IFS= read -r line; do
      CANDIDATES+=("$line")
    done < <(
      find "$ROOT" \
        \( -name "*.html" -o -name "*.jsx" -o -name "*.js" -o -name "*.css" \) \
        -not -path "*/vendor/*" \
        -not -path "*/node_modules/*" \
        -not -path "*/uploads/*" \
        -not -path "*/dist/*" \
        -not -path "*/build/*"
    )
    ;;
  --diff)
    if ! git rev-parse --git-dir > /dev/null 2>&1; then
      echo "check-voice.sh: --diff requires a git repo" >&2
      exit 2
    fi
    while IFS= read -r line; do
      CANDIDATES+=("$line")
    done < <(
      git diff --name-only --diff-filter=AM "$BASE"...HEAD -- \
        "$ROOT/*.html" "$ROOT/**/*.html" \
        "$ROOT/*.jsx" "$ROOT/**/*.jsx" \
        "$ROOT/*.js" "$ROOT/**/*.js" \
        "$ROOT/*.css" "$ROOT/**/*.css" \
        2>/dev/null | grep -v -E "/(vendor|node_modules|uploads|dist|build)/" || true
    )
    ;;
  --staged)
    while IFS= read -r line; do
      CANDIDATES+=("$line")
    done < <(
      git diff --cached --name-only --diff-filter=AM -- \
        "$ROOT/*.html" "$ROOT/**/*.html" \
        "$ROOT/*.jsx" "$ROOT/**/*.jsx" \
        "$ROOT/*.js" "$ROOT/**/*.js" \
        "$ROOT/*.css" "$ROOT/**/*.css" \
        2>/dev/null | grep -v -E "/(vendor|node_modules|uploads|dist|build)/" || true
    )
    ;;
esac

# Filter out missing files (e.g., deleted in diff mode).
FILES=()
for f in "${CANDIDATES[@]:-}"; do
  [[ -n "$f" && -f "$f" ]] && FILES+=("$f")
done

if [[ ${#FILES[@]} -eq 0 ]]; then
  echo "check-voice.sh ($MODE): no files in scope. OK."
  exit 0
fi

echo "check-voice.sh ($MODE): scanning ${#FILES[@]} files."
fail=0

# Rule 1: em dash (U+2014) in user-facing copy.
#
# Scope (per Andrew, 2026-06-09): the no-em-dash rule protects brand copy that
# people read, not developer-only text. So em dashes in CODE COMMENTS are
# allowed; em dashes in actual copy (HTML text, JS/JSX strings, CSS `content:`)
# still fail. We strip comments before checking, preserving line numbers so the
# report still points at the right line. The literal em dash characters below
# are intentional; do not "fix" them.
#
# strip_comments blanks comment bodies but keeps newlines, so line numbers in
# the stripped stream match the original file.
strip_comments() {
  case "$1" in
    *.css)
      perl -0777 -pe 's{/\*.*?\*/}{ my $c=$&; $c=~s/[^\n]//g; $c }ges' "$1"
      ;;
    *.js|*.jsx)
      perl -0777 -pe 's{/\*.*?\*/}{ my $c=$&; $c=~s/[^\n]//g; $c }ges; s{//[^\n]*}{}g' "$1"
      ;;
    *.html|*.htm)
      perl -0777 -pe 's{<!--.*?-->}{ my $c=$&; $c=~s/[^\n]//g; $c }ges' "$1"
      ;;
    *)
      cat "$1"
      ;;
  esac
}

em_dash_hit=0
for f in "${FILES[@]}"; do
  matches=$(strip_comments "$f" | grep -n --color=never -- "—" || true)
  if [[ -n "$matches" ]]; then
    printf '%s\n' "$matches" | awk -v f="$f" '{print f ":" $0}'
    em_dash_hit=1
  fi
done
if [[ $em_dash_hit -eq 1 ]]; then
  echo ""
  echo "FAIL: em dash found in user-facing copy. Replace with comma, period, or parens." >&2
  echo "(Em dashes inside code comments are allowed and not flagged.)" >&2
  fail=1
fi

# Rule 2: "Meet [Name]," openers in dog copy.
if grep -nHE --color=never '(^|>|["!?]\s+)Meet [A-Z][a-zA-Z]+,' "${FILES[@]}" 2>/dev/null; then
  echo ""
  echo "FAIL: 'Meet [Name],' opener found. Lead with the dog's story, not an introduction." >&2
  fail=1
fi

# Rule 3: closing rhetorical questions in dog-facing copy. Narrow pattern
# to avoid flagging FAQ pages.
if grep -nHE --color=never 'Could you be (his|her|their) (person|family|forever home)' "${FILES[@]}" 2>/dev/null; then
  echo ""
  echo "FAIL: 'Could you be his/her/their person' closer found. Replace with a statement." >&2
  fail=1
fi

if [[ $fail -eq 0 ]]; then
  echo "OK: voice check passed."
fi

exit $fail
