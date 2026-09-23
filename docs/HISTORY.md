# Site history

How the site was built, moved out of `CLAUDE.md` on 2026-09-23. Kept as a
record; the rules that still apply are in `CLAUDE.md`. Some notes describe
Kirigami bugs that have since been fixed upstream (the PHPDOC `@` continuation
line and multi-line list items).

## Phases 0–3 (as of 2026-09-11)

**Phase 0 — Foundations** shipped: `kirigami.yaml` on the current schema,
`package.json` (`@kirigami/kirigami` + `@kirigami/canva` +
`@kirigami/plugin-highlight`), `.github/workflows/page.yml` (kiribuild v2),
`.vscode/`, `banner.txt`, this file, `@kirigami/canva` wired in (`conf` with the
site's own palette + `prose`), the header/footer/nav shell, and one doc-page
model page (`/docs/`) demonstrating the prose wrapper + build-time syntax
highlighting. The header/footer use the **official Kirigami mark** via canva's
icon system — `--icon-logo-2` (compact glyph) in the header, `--icon-logo` (full
wordmark) in the footer — not a hand-drawn shape.

**Phase 1 — The pitch** also shipped: the home page is a real pitch (hero, a
"you write / it compiles to" code comparison, a 6-card feature grid, a
requirements list, the dogfooding note), plus four new pages — `/about/`
(why PHP+WASM, the maintainer, MIT/GPL licensing), `/templates/` (`default` +
`demo`, `kiri create` usage), `/showcase/` (this site + both templates, live +
source links), and `/ecosystem/` (the package table with **live npm version
numbers fetched at build time** via `curl_get_contents()` + `CACHE`, 1h TTL —
see `kirigami_pkg_version()` in `_lib/functions.php`; confirmed working against
the real npm registry from inside the WASM sandbox, `prepros.network: true`).
The footer grew from a one-liner to a 3-column grid (brand/tagline, Site links,
Kirigami links) now that there are enough pages to link.

Design identity locked in phase 0 (do not redecide per-page): warm paper /
near-black ink, a single deep-green accent. Type switched 2026-09-11 to match
`template-demo`'s pairing: Quicksand for headings, Roboto Flex for body copy,
JetBrains Mono for labels/nav numbers/code — all three embedded locally via
`canva`'s `$fonts` (`src/styles/partials/_conf.scss`, files in
`assets/fonts/`), no Google Fonts request. `plugin-highlight`'s own
`embedFont` is off (`kirigami.yaml`) so JetBrains Mono isn't duplicated.

**Phase 2 — Getting started** also shipped: `/start/` (hub), `/start/install/`,
`/start/quickstart/`, and the full **7-part tutorial** at `/start/tutorial/` —
a single fil-rouge project ("Studio Plié", a paper-folding studio) built one
concept per part: setup → pages & layout → content (Markdown/YAML/`@content`)
→ styles & scripts (canva retheme + dark mode + managed head) → images (the
autogenerator's 3 surfaces) → SEO (`meta:`/`jsonld:`) → deploy (kiribuild v2).
Shared components: `.steps` (numbered), `.tutorial-nav` (Part N of 7,
prev/next). Header nav gained **Start** (Home, Start, Docs, GitHub).

Writing the tutorial surfaced two real, confirmed bugs in `@kirigami/php-prepros`
(both logged in the monorepo's `todo.md`, not silently worked around):
a PHPDOC continuation line starting with a literal `@word` gets misparsed as a
new annotation — landed on `@content` specifically, which silently wiped an
entire rendered page (no error); and nesting a literal `<markdown>` tag example
inside a real `<markdown>` block breaks the tag's non-greedy pairing. **Two
authoring rules for every future page on this site, until those land upstream:**
never start a PHPDOC continuation line with `@`, and never write a literal
`<markdown>`/`</markdown>` as example text inside a `<markdown>` block — describe
it in prose or point at "view source" instead (see `/start/tutorial/3-content/`
for the pattern). Also hit and fixed in myself, not Kirigami: a literal
`<?php`/`<?=` typed as prose text executes for real — PHP doesn't know it's
sitting inside a fenced code example. Same rule: never type an opening PHP tag
as example text; show the body only, or describe it around the tag.

**Phase 3 — Reference & plugins** shipped: `/docs/` filled out beyond the one
model page — `/docs/authoring/` (project structure, PHPDOC header, auto-loaded
data files, `@content`/`@indent`, built-in tags, registering tags/hooks/MD
plugins, `@kirigami/sdk` Sass/esbuild hooks), `/docs/cli/` (every `kiri`
command including `serve`/`install`), `/docs/config/` (full `kirigami.yaml`
reference, `meta:`/`jsonld:`), `/docs/php/` (the PHP class library, with a
**live** image-autogenerator demo — `<img asset>` at two sizes + `IMG::palette()`
swatches from one source photo). Typography switched to the
Quicksand/Roboto Flex/JetBrains Mono trio (see above); syntax-highlight theme
derived from the site's own palette instead of plugin-highlight's default
blue-grey preset (`styles/partials/_highlight-theme.scss`).

Also Phase 3: **`/plugins/`** — the three official plugins
(`@kirigami/plugin-highlight`/`-extlink`/`-embed`), each with a **live** demo
on the page itself (a real `<extlink>` card, real `<youtube>`/`<vimeo>`
cards). The site now installs and actually uses `plugin-extlink` +
`plugin-embed`, not just `plugin-highlight` — `prepros.network: true` was
already on.

Plus **`/plugins/authoring/`** — the plugin-authoring tutorial. Builds a real
toy plugin (`kirigami-plugin-badge`) step by step: package shape/naming
convention, `on(HOOKS.PREPROS_PHP, …)` registering a tag, shipping default
styles via `SASS_AFTER`, an options schema, then a full hook reference table
and a publishing checklist. Found and documented a real gotcha while
verifying the example: running `npm install` *inside* a plugin's own folder
(to resolve a "Cannot find @kirigami/sdk" error while testing locally with a
bare `file:` dependency) leaves it with its own separate copy of
`@kirigami/sdk` — the hook registry is a module-level `Map`, so two copies
means two disconnected registries and `on()` in one is invisible to `run()`
in the other, silently (no error either side). Doesn't affect a normal
`npm install` of a published plugin (npm dedupes `@kirigami/sdk` to one copy
in the consuming project in the normal case) — only bites ad-hoc local `file:`
testing. Worth remembering for future plugin work in the monorepo itself too.

Nav split by design: the header (`_layouts/header.php`) only ever lists pages
that exist — now Home, Start, Docs, **Plugins**, GitHub; `/examples` joins it
once that hub exists too. Secondary pages (`/about`, `/templates`,
`/showcase`, `/ecosystem`, `/changelog`) live in the footer grid instead of
crowding the header.

Also shipped: **`/changelog/`** — a grouped, human-written summary (not a
raw commit log), newest first, one entry per package per date with the
version jump and 1-2 sentences on what actually matters, linking to that
package's own README for the full detail. Covers 2026-09-10 and
2026-09-11 so far; older history stays in each README's own "What's new".

And **`/roadmap/`** — the monorepo's own `todo.md`, translated into public
language and grouped by theme (this site, plugins, build/authoring, the
big unscoped `php-wasm-builder` idea), framed honestly as backlog/ordering
that can shift, not a release schedule. Nothing invented — every item maps
to something already open in `todo.md`. **Found a real `md.class.php` bug
writing this page**: a `- ` list item written across multiple *source*
lines (an indented continuation under the marker) closes the `<li>`/`</ul>`
after the first line and dumps the rest as a flat `<p>`, then reopens a new
`<ul>` for the next item — logged in the monorepo's `todo.md`, worked
around here by keeping every list item on one source line.

Also shipped: **`/design/`** — the palette (as live CSS-custom-property
swatches, so it repaints in dark mode with nothing hand-toggled), the
Quicksand/Roboto Flex/JetBrains Mono type trio, and the five
`@kirigami/canva` `styles/main` shared components (breadcrumb, docs-toc,
table + badges, palette swatches — the last one a real `IMG::palette()`
call against the site's own demo photo, not hard-coded hex values).

Also shipped: **`/examples/`** — a cookbook, distinct from `/docs/` (exhaustive
reference) and the tutorial (guided walk): six short recipes combining
existing pieces the way a real project actually uses them (SEO in two
`kirigami.yaml` blocks, a one-off `{% %}` Markdown shortcode, a one-off HTML
authoring tag, `FS::getChildren()` for a self-maintaining page list, an image
gallery from an auto-loaded YAML file + `IMG::asset()`, `<extlink>`/`<youtube>`
for rich cards). Two recipes (`{% badge %}`, `<shortcut keys="…">`) are
registered for real on this site's own `_lib/functions.php` and demoed live
on the page itself, not just described. All six phases from the game plan are
now shipped — nothing left in "not started."

**Nav: Design and Roadmap promoted from the footer into the header.** The
user reported not being able to find them — they were footer-only by the
original "secondary pages live in the footer" rule. `_layouts/header.php`'s
`$nav` now carries Home / Start / Docs / Plugins / Examples / Design /
Roadmap / GitHub (still also in the footer grid, same as Home already was —
duplication there is fine).

**Real bug found and fixed: theme toggle reflowed the whole page.** Reported
by the user while browsing `/docs/authoring/`. Root cause was in
`@kirigami/canva`'s `styles/prose.scss`, not this site — see the monorepo's
own `CLAUDE.md` "Current work" for the full specificity analysis. Fixed
upstream (canva 2.5.1, released), this site's `@kirigami/canva` floor bumped
to `^2.5.1` and verified with a Playwright repro (full-page height diff
before/after a real `[data-theme-toggle]` click) against the real published
package: 0px difference anywhere on `/docs/authoring/` or `/examples/`.

Two new CSS conventions landed alongside the `/examples/` build, per user
feedback: no `style="…"` inline attributes (a small `.demo` class in
`_main.scss` covers the one-off spacing that used to be inline), and prefer
`::before`/`::after` over extra markup for a purely decorative element (the
`<shortcut>` tag's `+` separator between `<kbd>`s is `kbd + kbd::before`, not
a `<span>`).
