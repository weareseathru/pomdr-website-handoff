#!/usr/bin/env bash
# ---------------------------------------------------------------------------
# One command for the things done at the start of every session and before
# every push to the site (Ronnie's repository, cloned as New-Build/).
#
#   scripts/dev.sh up       start the local preview, and say what is missing
#   scripts/dev.sh check    the full gate that must pass before a push
#   scripts/dev.sh status   what is running, and where the repository stands
#   scripts/dev.sh down     stop the preview this script started
#
# It never commits, pushes or touches a database. It only starts a local web
# server and runs read-only checks.
#
# Settings, all optional (export them, or put them in scripts/dev.local.sh,
# which git ignores):
#   POMDR_SITE     path to the clone of ronniereid/pomdr   (default: ../New-Build)
#   POMDR_PORT     preview port                            (default: 8777)
#   POMDR_PHP_DIR  folder holding the php binary, if php is not on your PATH
#   POMDR_MYSQL_CMD  a command that starts a local MySQL, if you use one
#
# On Andrew's Mac there is no system PHP; PHP and MySQL come from the Local
# app, and this script finds them there by itself.
# ---------------------------------------------------------------------------

set -u

HERE="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
ROOT="$(cd "$HERE/.." && pwd)"

[ -f "$HERE/dev.local.sh" ] && . "$HERE/dev.local.sh"

SITE="${POMDR_SITE:-$ROOT/New-Build}"
PORT="${POMDR_PORT:-8777}"
RUN="$ROOT/.dev-run"
PIDFILE="$RUN/preview.pid"
LOGFILE="$RUN/preview.log"

say()  { printf '%s\n' "$*"; }
ok()   { printf '  ok    %s\n' "$*"; }
bad()  { printf '  FAIL  %s\n' "$*"; }
note() { printf '  note  %s\n' "$*"; }

# --- find PHP ---------------------------------------------------------------
find_php() {
    if [ -n "${POMDR_PHP_DIR:-}" ] && [ -x "$POMDR_PHP_DIR/php" ]; then
        PATH="$POMDR_PHP_DIR:$PATH"; return 0
    fi
    if command -v php >/dev/null 2>&1; then return 0; fi
    # The Local app (localwp.com) keeps its own PHP builds here on a Mac.
    local base="$HOME/Library/Application Support/Local/lightning-services"
    local dir
    dir="$(ls -d "$base"/php-8.*/bin/darwin*/bin 2>/dev/null | sort -V | tail -1)"
    if [ -n "$dir" ] && [ -x "$dir/php" ]; then PATH="$dir:$PATH"; return 0; fi
    return 1
}

need_site() {
    if [ ! -d "$SITE/.git" ]; then
        bad "No clone of the site at $SITE"
        say "        git clone https://github.com/ronniereid/pomdr.git \"$SITE\""
        say "        (the repository is private; Ronnie grants access)"
        exit 1
    fi
}

need_php() {
    if ! find_php; then
        bad "PHP was not found. Install PHP 8.2 or newer, or set POMDR_PHP_DIR."
        exit 1
    fi
}

port_open() { (exec 3<>"/dev/tcp/127.0.0.1/$1") 2>/dev/null; }

# --- up ---------------------------------------------------------------------
cmd_up() {
    need_site; need_php
    mkdir -p "$RUN"
    say "Site:  $SITE"
    say "PHP:   $(php -r 'echo PHP_VERSION;') ($(command -v php))"

    if [ ! -f "$SITE/includes/config.local.php" ]; then
        bad "includes/config.local.php is missing."
        say "        Copy includes/config.local.php.example and ask Ronnie for the values."
        say "        It holds the database credentials. Never commit it."
        exit 1
    fi
    ok "config.local.php is present (not read, not printed)"

    if [ -n "${POMDR_MYSQL_CMD:-}" ]; then
        note "Starting your local MySQL: POMDR_MYSQL_CMD"
        ( eval "$POMDR_MYSQL_CMD" >"$RUN/mysql.log" 2>&1 & )
        sleep 3
    fi

    if port_open "$PORT"; then
        ok "A preview is already answering on port $PORT"
    else
        ( cd "$SITE" && nohup php -S "127.0.0.1:$PORT" bin/dev-server.php >"$LOGFILE" 2>&1 & echo $! >"$PIDFILE" )
        sleep 1
        if port_open "$PORT"; then ok "Preview started (log: $LOGFILE)"; else bad "Preview did not start; see $LOGFILE"; exit 1; fi
    fi

    local code
    code="$(curl -s -o /dev/null -w '%{http_code}' "http://127.0.0.1:$PORT/" || true)"
    if [ "$code" = "200" ]; then
        ok "Home page answers 200"
    else
        bad "Home page answered $code. The usual cause is the database: check config.local.php and that the database is reachable."
    fi

    say ""
    say "Open   http://127.0.0.1:$PORT/"
    say "Kit    http://127.0.0.1:$PORT/patterns.php   (every shared component)"
}

# --- check: the gate before a push -------------------------------------------
cmd_check() {
    need_site; need_php
    cd "$SITE" || exit 1
    local failed=0

    local tmp; tmp="$(mktemp)"
    say "1. Syntax (bin/check.php)"
    if php bin/check.php >"$tmp" 2>&1; then ok "$(tail -1 "$tmp")"; else bad "see below"; cat "$tmp"; failed=1; fi
    rm -f "$tmp"

    say "2. Unit checks (tests/unit/run.php)"
    local unit; unit="$(php tests/unit/run.php 2>&1 | tail -1)"
    case "$unit" in *" 0 failed"*) ok "$unit" ;; *) bad "$unit"; failed=1 ;; esac

    say "3. Every page renders"
    local n=0 badpages=0 u out
    for u in $(php bin/list-pages.php); do
        out="$(php build/render-page.php "$u" 2>&1)"
        n=$((n + 1))
        if [ "${#out}" -lt 2000 ] || printf '%s' "$out" | grep -qE 'Warning|Fatal|Deprecated'; then
            bad "$u"; badpages=$((badpages + 1))
        fi
    done
    if [ "$badpages" -eq 0 ]; then ok "$n pages, none failing"; else failed=1; fi

    say "4. No em dash in what you added (staged and unstaged, against origin/main)"
    git fetch -q origin main 2>/dev/null || note "could not reach GitHub; comparing with the last fetch"
    local dashes; dashes="$(git diff origin/main | grep '^+' | grep -c $'\xe2\x80\x94')"
    if [ "$dashes" -eq 0 ]; then ok "none"; else bad "$dashes added line(s) contain an em dash"; failed=1; fi

    say "5. Nothing removed or changed in Ronnie's folders"
    local touched; touched="$(git diff origin/main -- admin build bin db deploy includes/db.php includes/functions.php | grep -c '^-[^-]')"
    if [ "$touched" -eq 0 ]; then ok "0 lines"; else bad "$touched of his lines changed or removed. Stop and undo, or ask Ronnie."; failed=1; fi

    say "6. Design.md change log"
    # New files count as changes too, which a plain diff would not see.
    if git diff origin/main --quiet -- . ':!Design.md' && [ -z "$(git status --porcelain --untracked-files=all -- . ':!Design.md')" ]; then
        note "no changes to the site"
    elif git diff origin/main -- Design.md | grep -q '^+- \*\*20'; then
        ok "a dated line was added to section 11"
    else
        bad "add a dated line at the top of Design.md section 11 saying what changed and why"; failed=1
    fi

    say ""
    if [ "$failed" -eq 0 ]; then
        say "All checks passed. Still to do by eye: look at the changed pages at 390px and 1440px wide."
        say "Then: git add, git commit, git pull --rebase origin main, git push origin main."
    else
        say "Something failed. Do not push."
        exit 1
    fi
}

# --- status -----------------------------------------------------------------
cmd_status() {
    need_site
    say "Site:  $SITE"
    ( cd "$SITE" && say "Branch and sync: $(git status -sb | head -1)" && say "Last commit:     $(git log --oneline -1)" && say "Uncommitted:     $(git status --short | wc -l | tr -d ' ') file(s)" )
    if port_open "$PORT"; then say "Preview:         running at http://127.0.0.1:$PORT/"; else say "Preview:         not running (scripts/dev.sh up)"; fi
}

# --- down -------------------------------------------------------------------
cmd_down() {
    if [ -f "$PIDFILE" ] && kill "$(cat "$PIDFILE")" 2>/dev/null; then
        rm -f "$PIDFILE"; say "Preview stopped."
    else
        say "No preview started by this script is running."
    fi
}

case "${1:-}" in
    up)     cmd_up ;;
    check)  cmd_check ;;
    status) cmd_status ;;
    down)   cmd_down ;;
    *)      sed -n '2,12p' "$0" | sed 's/^# \{0,1\}//' ;;
esac
