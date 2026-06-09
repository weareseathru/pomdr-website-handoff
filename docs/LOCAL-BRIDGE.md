# Local WP bridge

How the Git repo, the Local WP mirror, and `new.pomdr.org` connect, so you
can edit in Antigravity, see it in Local, and ship it through GitHub.

## The three places

| Place | What it holds | Role |
|-------|---------------|------|
| **Git repo** (`divi-child-integration/`) | The theme code (PHP, CSS, JS) | **Source of truth for code** |
| **Local WP** (`~/Local Sites/newpomdr-local`) | Full WordPress: theme + database + media | Where you preview and test |
| **new.pomdr.org** | The live staging site | Where code and content come from, and ship to |

## How the bridge works (symlink)

Local's active theme folder is a **symlink** to the repo:

```
~/Local Sites/newpomdr-local/app/public/wp-content/themes/divi-child
    ->  <repo>/divi-child-integration
```

So when you edit a theme file in Antigravity and save, it is **immediately
live in Local**. Just refresh the browser. No copy step.

- The active theme is still identified to WordPress as `divi-child` (the
  symlink name) with Theme Name "Divi Child", so the site's active-theme
  setting did not change.
- The original production theme is backed up next to it as
  `divi-child.pre-bridge-backup-<timestamp>`. To undo the bridge, delete the
  symlink and rename the backup back to `divi-child`.

### Code vs content

- **Code** (theme PHP/CSS/JS) lives in Git and flows repo -> Local via the
  symlink, then Git -> `new.pomdr.org` on deploy.
- **Content** (the ~95 dogs, pages, media, ACF values) lives in the
  **database**, not Git. It flows the other way: pull *down* from
  `new.pomdr.org` into Local with the **WP Migrate DB** plugin (already
  installed). Never commit the database to Git.

## Daily workflow

1. Edit theme files in Antigravity (`divi-child-integration/...`).
2. Refresh `https://newpomdr-local.local/` to preview. (Start the site first
   in the Local app if it is not running.)
3. When happy, commit on a feature branch and push. Open a PR to `main` for
   Andrew (see `docs/SESSION-CHECKLIST.md`).
4. To refresh local content from staging, use WP Migrate DB to pull from
   `new.pomdr.org`.

## Deploying code up to new.pomdr.org

The theme is the `divi-child` folder on the server. Options, in order of
preference:

1. **Git on the server** (cleanest): pull the merged `main` into the server's
   `wp-content/themes/divi-child`. Requires shell/Git access on the host.
2. **SFTP** the `divi-child-integration/` contents into the server's
   `wp-content/themes/divi-child`.

Do not deploy with WP Migrate DB; that tool is for the database, not theme
files. Confirm hosting and access with Andrew before any production deploy
(see CLAUDE.md: never publish to production without confirmation).

## Confirmed environment (Local mirror)

- WordPress on **PHP 8.2** (Local bundled `php-8.2.29`).
- Parent theme: **Divi**. Active theme: **Divi Child** (now the repo, via
  symlink).
- Plugins present: **ACF Pro**, **Redirection**, **WP Migrate DB**.

## Known follow-up

`assets/css/pomdr-design.css` (the v2 design system) was authored against the
static HTML prototype in `pomdr-website/project/`, not against Divi's markup.
It is now enqueued on top of the production theme via `inc/enqueue.php`, but
the visual fit to Divi's DOM (the `et_pb_*` classes and the `pom-*` /
`custom-acf-posts-grid` markup the production templates emit) still needs
iteration. That iteration is exactly what this bridge is for: edit, refresh,
compare.
