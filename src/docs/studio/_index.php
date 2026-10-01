<?php
/**
 * @title    Kiri Studio
 * @section  docs
 * @type     docs
 * @group    Tools
 * @position 14
 * @abstract Set up a Kirigami site so its owner can edit and publish it with
 *           Kiri Studio, the desktop app.
 */
?>

<markdown>
  ## Overview

  [Kiri Studio](../../studio/) is a desktop app for the **owner** of a
  Kirigami site: they sign in with GitHub, edit text, data, images and
  documents, see the real page in a live preview, and click Publish. It sits
  next to the [CLI](../cli/), the [VS Code extension](../vscode/), the
  [MCP server](../mcp/) and the [JavaScript API](../api/): the same engine,
  for people who never open a terminal.

  This page is for you, the maintainer, who prepares the site. The owner does
  none of it.

  ## How it works

  - **Sync, not clone.** The app downloads the repository's tarball at the
    branch head into a cache and remembers the commit. It re-checks every
    three minutes and when its window regains focus, so it notices other
    people's changes. There is no local Git.
  - **Drafts.** Edits are kept as an overlay apart from the synced files, and
    saved on every change: a crash or a restart loses nothing.
  - **Preview.** The app runs the site's own Kirigami in a worker process,
    through the `Project` API, so the preview is the real render. The site
    needs `@kirigami/kirigami` **3.1.1 or newer**. The preview folds into a
    thin rail to give the editor the full width.
  - **Publish.** One blob per changed file, one tree, one commit, then a
    single branch update that is refused if someone else published meanwhile.
    The app then syncs again and rebuilds on the new head, and follows the
    GitHub Actions run to tell the owner when the site is online. Files over
    25 MB are refused, except paths routed through Git LFS (up to 100 MB).

  ## One-time setup per site

  1. Install the **Kiri Studio** GitHub App on the site's repository.
  2. Give the owner write access to that repository.
  3. Optionally add a `studio:` block to `kirigami.yaml`.

  The owner's sign-in only reaches repositories where the App is installed
  *and* they can write. Publishing pushes source files only: the site's
  existing GitHub Pages workflow builds and deploys.

  ## The `studio:` block

  Nothing is required: without a block, the content that pages load and the
  site's images are editable. `studio: {}` is the explicit minimum. Every
  `.md`, `.yaml`, `.yml` and `.json` file that a page loads through a PHPDOC
  annotation (`@content _about.md`, `@articles _articles.yaml`) becomes
  editable, grouped under the page's `@title`. The build ignores the block.

  ```yaml
  studio:
    files: src/documents
    exclude: [src/features/data/_stats.json]
    labels:
      src/features/data/_articles.yaml: Articles
    schemas:
      assets/schemas/articles.schema.json: _articles.yaml
    include:
      - { path: "src/news/*/_index.md", label: Posts, create: true,
          header: { date: today } }
    pageMedia: [images, videos, files]
    pageImage: true
  ```

  | Key | Default | What it does |
  |---|---|---|
  | `branch` | repo default | Branch synced from and published to. |
  | `images` | `image.source` | The image manager folder; `false` hides it. |
  | `imageWidth` | `800` | Width in the `{% img-asset %}` code the app copies for an image. |
  | `files` | – | The document folder, under `kirigami.root`; omit to hide the manager. |
  | `include` | `[]` | Extra editable paths or globs. `{ path, label, create, header }` lets owners add and delete matching files; `header` sets default tags for new pages (`today` fills a date). |
  | `exclude` | `[]` | Paths or globs hidden from owners. |
  | `labels` | `{}` | Names shown to owners, by path. Page labels otherwise come from `@title`. |
  | `schemas` | `{}` | JSON Schemas for data files: schema path or URL, to file glob(s). |
  | `types` | `prepros.types` | The page layouts offered in the page's "Page layout" select, as a list, or `false` to hide it. |
  | `pageMedia` | off | `true` (`images/` and `videos/`) or a list of folder names; each Markdown page gets its own media folders beside its `_index.md`. Include `files` for downloads. |
  | `pageImage` | off | Needs `pageMedia`: each image gets "Use as page image", which sets the page's `@image`. |

  `pageMedia` needs core 3.2.2 and `pageImage` core 3.2.3: a site can only set
  an option its installed core's schema knows.

  ### Collections

  With `create: true`, a glob makes a creatable collection:

  - `folder/*/_index.md` creates `folder/<slug>/_index.md` (never over an
    existing folder), with `@title` plus the `header` defaults.
  - `folder/**/_index.md` is a **tree**: the sidebar nests the pages, with a
    "+" on each page to add a sub-page.

  Deleting a page of such a collection removes its whole folder (sub-pages
  and files beside it) after a confirmation that counts them; the top page of
  a tree waits until it has no sub-pages.

  ### Markdown pages and their header

  An `_index.md` that starts with `@tag` lines and has no `_index.php` beside
  it is a page. The editor shows its header as a form above the text (date
  picker, checkboxes, a textarea for the abstract, the layout select) and
  rewrites only the line you changed, so an untouched header keeps its exact
  bytes. Technical tags (`type`, `content`, names starting with `_`, `@@`)
  stay hidden.

  ### Schemas

  Data files are edited as text and checked against a JSON Schema as the
  owner types. A file's schema is found like VS Code's YAML extension does:
  a `# yaml-language-server: $schema=` line in the file, then
  `studio.schemas`, then `yaml.schemas` in `.vscode/settings.json`. A site
  already set up for VS Code usually needs nothing more.

  ## Media and Git LFS

  - Images are **downscaled** before they are committed, so plain Git holds
    them. Each image can copy a ready `{% img-asset %}` snippet, its Markdown,
    or its path.
  - Documents get a "Copy Markdown link" action (a root-relative path that
    includes the site's base path) and "Copy file name".
  - Audio (mp3, m4a, aac, wav, flac, ogg, opus) and video (mp4, m4v, webm,
    ogv, mov) files play in the app, with seeking.
  - For large binaries, route the extensions through LFS in `.gitattributes`
    (for example `*.mp4 filter=lfs diff=lfs merge=lfs -text`). Studio uploads
    those through the LFS batch API and commits the pointer. Remember that
    the Pages build then checks LFS files out on every deploy: new sites
    should keep media out of LFS where they can.

  ## Install and update

  Installers for Windows (x64), macOS (arm64, x64) and Linux (AppImage and
  `.deb`) are on the
  [releases page](https://github.com/php-kirigami/kiri-studio/releases).
  They are not code-signed yet: Windows warns on first install, and macOS
  needs right-click, Open. Installed apps update themselves in the
  background and apply the update on quit (Windows and the AppImage for now).
  The interface is available in English and French.

  ## Limits

  - One images folder per site (`studio.images`).
  - Studio has one running instance at a time: a second launch shows a dialog
    and raises the first window.
  - It is in early development: expect rough edges, and report them on the
    [issue tracker](https://github.com/php-kirigami/kiri-studio/issues).
</markdown>
