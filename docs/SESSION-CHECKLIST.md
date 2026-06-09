# End-of-session checklist

Run this at the end of every working session so progress always lands on
GitHub. It is a deliberate manual step (no auto-push), and it respects the
project rule: **never push to `main`, always open a PR for Andrew to review.**

## Steps

1. **Voice check** (must be clean for anything new you changed):

   ```bash
   bash scripts/check-voice.sh --diff main
   ```

   Inherited prototype violations do not block. New ones do. Fix before
   continuing. (No em dashes, no "Meet [Name]," openers, no closing rhetorical
   questions, `~age` with a tilde.)

2. **Review what changed:**

   ```bash
   git status
   git diff
   ```

3. **Commit in logical chunks.** One logical change per commit. Plain-language
   messages, no em dashes. End each commit message with:

   ```
   Co-Authored-By: Claude Opus 4.8 <noreply@anthropic.com>
   ```

4. **Confirm you are on a feature branch, never `main`:**

   ```bash
   git branch --show-current
   ```

   Branch prefixes: `feature/`, `fix/`, `chore/`, `content/`, `design/`,
   `a11y/`.

5. **Push the feature branch:**

   ```bash
   git push -u origin "$(git branch --show-current)"
   ```

6. **Open or update a PR against `main`** using the PR template, so Andrew can
   review. If the `gh` CLI is installed:

   ```bash
   gh pr create --base main --fill   # first time
   gh pr view --web                  # to review/update an existing PR
   ```

   If `gh` is not installed (`command not found`), open the PR from the
   GitHub web UI, or use the GitHub MCP server from within the agent. The
   push in step 5 is enough to make GitHub offer a "Compare & pull request"
   button on the branch.

7. **Log progress** in `CHANGELOG.md` (one line under the current session).

## Notes

- The pre-commit voice hook is optional; enable it once per clone with
  `git config core.hooksPath .githooks`.
- If a commit is blocked by the hook in an emergency, `git commit --no-verify`
  bypasses it, but CI will still run the voice check on the PR.
- Claude will proactively prompt to run this checklist at the end of a session,
  and will run the steps on your go-ahead. Claude does not push or open PRs
  without you saying so.
