<?php
/**
 * @title      CLI
 * @section    docs
 * @position   2
 * @abstract   Every kiri command — build, export, watch, serve, run,
 *             create, install, phpinfo.
 * @breadcrumb true
 */
?>

<section class="section wrap doc-head">
    <?php echo kirigami_breadcrumb_nav($title, $relroot); ?>
    <span class="eyebrow">Documentation</span>
    <h1><?php echo str_htmlesc($title); ?></h1>
    <p class="lead"><?php echo str_htmlesc($abstract); ?></p>
</section>

<section class="section wrap">
    <div class="prose">
        <markdown>
          `kiri` is provided by the `@kirigami/kirigami` dev dependency —
          run it as `npx kiri <command>`. Every command has `--help`.

          | Command | What it does |
          |---|---|
          | `kiri build` | Run every `tasks` entry once, in order, for development (no minify/export). If `prepros:` is set, renders all pages + `sitemap.xml` first. Fires the `before-build` trigger. |
          | `kiri export` | Production build. Fires `before-export` then `before-build`; forces the `prepros` task, all `tasks`, and a `dist` copy into `export.path`; stamps the banner; fires `after-export`. |
          | `kiri watch` | Dev mode: watches files for `esbuild` / `sass` / `prepros` tasks and rebuilds on change (150 ms debounce, batched). `node_modules/`, `.git/`, `dist/` always ignored. No server. `Ctrl+C` to stop. |
          | `kiri serve` | Everything `watch` does, plus a static file server over `kirigami.root` and browser hot-reload (Server-Sent Events — a tab reloads once a batch finishes). `--port` (default `4321`) / `--host` (default `127.0.0.1`). Zero-dependency: `node:http` + `node:fs`, no live-reload framework. |
          | `kiri run <script> [args…]` | Run `scripts/<script>.php` in the Kirigami PHP runtime — full class library, `PREPROS::$config->data` populated. Extra words become `$argv` entries. |
          | `kiri create [template] [dir]` | Scaffold from an official `template-*` repo. No args → interactive wizard (template, dir, name / description / author / base URL, `--email` / `--repo`) written into `package.json` + `kirigami.yaml`. `--list` / `-l` to list. Extraction never overwrites — existing files are kept, `package.json` deep-merged. Then `git init` + an initial commit (unless already in a repo or `--no-git`) and `npm install` (unless `--no-install`). |
          | `kiri install <plugin>` | `npm install`s a plugin — devDependency by default, `--save` for a regular one. A bare name (`highlight`) resolves against the `@kirigami/plugin-*` / `kirigami-plugin-*` conventions via the npm registry; a full package name is used as-is. Already installed → checks npm for a newer version and updates. Prints the `plugins:` entry to paste into `kirigami.yaml`, built from the plugin's own option schema — never edits the file itself. |
          | `kiri phpinfo` | Print `phpinfo()` from the embedded runtime. `--md` / `--json` for other formats. |
          | `kiri --version` | `kiri` version + bundled PHP version. |

          A typical loop during development is `kiri serve` in one terminal —
          edit, save, watch the browser tab reload — then `kiri export` once,
          right before a push, to check the production output in `dist/`.

          ```bash
          npx kiri serve --port 4321
          # …edit src/**, browser reloads on save…
          npx kiri export
          ```

          `kiri run` is for one-off or triggered scripts that need the full
          PHP class library but produce no page — image conversion, a data
          migration, a report. Declare it under `scripts:` in
          [the config reference](../config/#scripts) to run it on
          `before-build` / `before-export` / `after-export`, or leave
          `trigger` out for manual-only.
        </markdown>
    </div>
</section>
