<?php
/**
 * @title      CLI
 * @section    docs
 * @type       docs
 * @group      Tools
 * @position   10
 * @abstract   Every kiri command, with its flags and real example output —
 *             build, export, watch, serve, run, mcp, create, install,
 *             cache, phpinfo.
 */
?>

    <markdown>
      ## Overview

      `kiri` comes from the `@kirigami/cli` package. Install it as a dev
      dependency and run it as `npx kiri <command>`, or through the
      `package.json` scripts every official template ships (`npm run serve`,
      `npm run build`, …):

      ```bash
      npm install --save-dev @kirigami/cli
      ```

      Every command accepts `--help`, and so does bare `kiri`:

      ```
      $ npx kiri --help

      kiri — Kirigami CLI

      USAGE
        kiri <command> [options]

      COMMANDS
        build       Compile project for development
        export      Compile and export project for production
        watch       Start dev-mode with hot-reload
        serve       Dev-mode with hot-reload, served locally in a browser
        run         Run a PHP command script from the scripts/ folder
        mcp         Serve this project over MCP (stdio) for an AI agent
        create      Create a new project from an official template
        install     Install a plugin and print its kirigami.yaml options
        cache       Purge the local caches (.node.db / .cache.db / .cookie.txt)
        phpinfo     Print phpinfo() from the embedded PHP-WASM runtime

      GLOBAL OPTIONS
        --help, -h    Show help
        --version, -v Show version
      ```

      `kiri --version` also prints the bundled PHP version alongside the
      CLI's own — useful when filing an issue, since `@kirigami/php-wasm`'s
      own version number encodes the PHP build it ships.

      ## kiri build

      Compiles the project for **development**: every entry under
      `tasks:` runs once, in order, with no minification and no export
      step. If a top-level `prepros:` block is set, every PHP page (plus
      `sitemap.xml` / `robots.txt`) renders first — page-source `.html`
      files land right next to their `_index.php`, so a plain static
      server can preview `src/` directly.

      ```bash
      kiri build
      ```

      This is the real output from building this very site — the plugin
      loader banner, every page rendered, then the `sass` and `esbuild`
      tasks:

      ```
      kiri — Build Project

      › Project   : Kirigami
      › Base URL  : https://php-kirigami.github.io
      › Root      : /path/to/project/src

      Plugins:
      › ✔ @kirigami/plugin-highlight v0.1.7
      › ✔ @kirigami/plugin-extlink v0.1.3
      › ✔ @kirigami/plugin-embed v0.1.4

      Tasks:

      › PREPROS: render-all ✔
          src/index.html
          src/about/index.html
          …
          src/sitemap.xml
          src/robots.txt

      › SASS: css-core ✔
          (this site's compiled stylesheet + source map)

      › ESBUILD: js-core ✔
          (this site's compiled bundle + source map)

      ✔ Build finished!
      ```

      A task with no build step for the current change (nothing to
      re-run) is skipped, unless it sets `force: true`. `kiri build`
      fires the `before-build` [trigger](#run) first — a `scripts:`
      entry can hook into that without any manual `kiri run` call. For
      production output (minified, exported to `dist/`), use
      [`kiri export`](#export) instead.

      ## kiri export

      The production build: **forces** every task to run (even ones
      `kiri build` would have skipped), copies the result into
      `export:path` (default `dist/`), and stamps the banner
      (`kirigami:banner`, or the bundled ASCII one) into every exported
      text file with its `### ###` tokens filled in.

      ```bash
      kiri export
      ```

      Trigger order is `before-export` → `before-build` → the build
      itself → `after-export` (see [`kiri run`](#run) for what a trigger
      actually runs). That means anything a project wires to
      `before-build` also runs during export — no need to duplicate a
      script under both triggers.

      Point CI at this command; [`kiribuild`](https://github.com/php-kirigami/kiribuild)
      (the reusable GitHub Action) runs exactly `kiri export` under the
      hood. See [Tutorial → Deploy](../../start/tutorial/7-deploy/) for
      the full GitHub Pages setup.

      ## kiri watch

      Dev mode without a server: watches every file that feeds an
      `esbuild` / `sass` / `prepros` task and rebuilds on change — 150ms
      debounced, batched per task so five saves in the same second
      trigger one rebuild, not five. `node_modules/`, `.git/` and
      `dist/` are always ignored. `Ctrl+C` stops cleanly.

      ```bash
      kiri watch
      ```

      It only writes files to disk — no server, no browser reload. For
      that, use [`kiri serve`](#serve), which does everything `watch`
      does plus the two things it's missing.

      ## kiri serve

      Everything [`kiri watch`](#watch) does, plus: serves
      `kirigami.root` over plain HTTP and reloads any open browser tab
      once a rebuild batch finishes (Server-Sent Events — no WebSocket
      library, no live-reload framework, just `node:http` + `node:fs`).
      A `sass`-only change hot-swaps the stylesheet in place instead of
      reloading the page, so scroll position and form state survive.

      | Flag | Default | Purpose |
      |---|---|---|
      | `--port, -p <n>` | `4321` | Port to listen on. |
      | `--host <host>` | `127.0.0.1` | Host to bind to. |

      ```bash
      kiri serve
      kiri serve --port 5000
      ```

      If the port is already taken, `kiri serve` fails with a direct
      fix instead of a raw Node stack trace:

      ```
      Error: Port 4321 on 127.0.0.1 is already in use — try a different one with --port 4322.
      ```

      ## kiri run

      Runs `scripts/<name>.php` inside the same PHP-WASM runtime and
      class library every page renders with — `PREPROS::$config->data`
      is populated, every autoloaded class (`FS`, `IMG`, `CURL`, …) is
      available — but the script produces no page. Use it for anything
      one-off or triggered that isn't itself content: image conversion,
      a data migration, a deploy notification.

      ```bash
      kiri run <name> [args...]
      ```

      Every word after `<name>` becomes a `$argv` entry inside the
      PHP script:

      ```bash
      kiri run deploy production --force
      ```

      ```php
      // scripts/deploy.php
      // $argv === ['production', '--force']
      ```

      A script isn't limited to manual invocation — declare it under
      `scripts:` in `kirigami.yaml` with a `trigger`, and `kiri build` /
      `kiri export` fire it automatically:

      ```yaml
      scripts:
        deploy:
          trigger: after-export
      ```

      Full reference: [Config → scripts](../config/#scripts).

      ## kiri create

      Scaffolds a new project from an official `template-*` repo (the
      `php-kirigami` org). Running it with no arguments, in an
      interactive terminal, starts a wizard (template, directory, name /
      description / author / base URL, `--email` / `--repo`); every
      answer is written into `package.json` and `kirigami.yaml` for you.

      | Flag | Purpose |
      |---|---|
      | `--list, -l` | List available templates (cached 1h). |
      | `--name <name>` | Project name (`package.json` name + `kirigami.yaml` project). |
      | `--description <s>` | Project description. |
      | `--author <name>` | Author. |
      | `--email <email>` | Author email (`kirigami.yaml` `email`). |
      | `--baseurl <url>` | Site base URL (`kirigami.yaml` `baseurl`). |
      | `--repo <url>` | Git repo URL (`kirigami.yaml` `repo`; default: derived from `--baseurl`). |
      | `--yes, -y` | Non-interactive: take defaults, ask nothing. |
      | `--no-git` | Don't run `git init` / the first commit. |
      | `--no-install` | Don't run `npm install` afterwards. |

      ```bash
      kiri create                  # interactive wizard
      kiri create --list
      kiri create blog my-blog
      ```

      Extraction never overwrites: existing files are kept as-is, and
      `package.json` is deep-merged (your own dependencies win over the
      template's). A missing `package.json` / `banner.txt` gets a
      starter one — the banner keeps its `### ###` tokens on disk, filled
      in on every `build` / `export` afterward. Unless `--no-git` /
      `--no-install` are passed, `create` also runs `git init` plus an
      initial commit (skipped if already inside a repo) and
      `npm install`. Every template ships its own `CLAUDE.md`, so a
      freshly created project is Claude Code-ready immediately.

      ## kiri install

      Installs a Kirigami plugin and prints the `plugins:` block to
      paste into `kirigami.yaml` — it never edits the file itself.

      ```bash
      kiri install <plugin...>
      kiri install --save <plugin...>   # regular dependency, not devDependency
      ```

      A bare name expands against the `@kirigami/plugin-*` /
      `kirigami-plugin-*` naming conventions and is checked against npm;
      a full package name (anyone's, not just `@kirigami/*`) is used
      as-is:

      ```bash
      kiri install highlight                    # → @kirigami/plugin-highlight
      kiri install @kirigami/plugin-highlight    # same thing, spelled out
      kiri install highlight @kirigami/plugin-embed   # more than one at once
      ```

      Already installed? `install` checks npm for a newer version and
      updates it. Either way it ends by printing the options block,
      built straight from the plugin's own `kirigami.optionsSchema` —
      so the flags you see match that exact version:

      ```yaml
      plugins:
        - name: "@kirigami/plugin-highlight"
          options:
            languages: [js, ts, php, bash, yaml, json, html, css]
            theme: auto
      ```

      ## kiri cache

      Kirigami caches two SQLite stores at the project root —
      `.cache.db` (`CACHE`, used by `SCRAPER` and the image pipeline) and
      `.node.db` (`@kirigami/sdk`'s `Cache`, for plugin-side caching) —
      plus a `.cookie.txt` jar (`CURL`). All three are meant to be
      `.gitignore`d locally but *committed* in CI-built projects, so a
      fresh checkout never needs to re-crawl or re-encode anything a
      previous run already resolved (see
      [`<extlink>`](../../plugins/#extlink) for a real example).

      ```bash
      kiri cache purge              # delete .node.db, .cache.db, .cookie.txt
      kiri cache purge meta_*       # keep the files, delete only matching keys
      kiri cache purge "colors_*"
      ```

      `<mask>` is a glob over the key namespace, not a filename — quote
      it if your shell would otherwise expand the `*`. Acts on the
      current directory.

      ## kiri mcp

      Starts the Kirigami [MCP server](../mcp/) on stdio, bound to the
      current directory, so an AI assistant can build, validate, export
      and scaffold the project. It is meant to be launched by an MCP
      client, not typed by hand:

      ```bash
      npx kiri mcp
      ```

      ## kiri phpinfo

      Prints `phpinfo()` from the embedded PHP-WASM runtime — the exact
      PHP version and extension set every page actually renders with,
      not whatever PHP (if any) happens to be on the host machine.

      | Flag | Output |
      |---|---|
      | *(none)* | HTML, exactly like a browser-rendered `phpinfo()`. |
      | `--md, -m` | Markdown table. |
      | `--json, -j` | JSON. |

      ```bash
      kiri phpinfo
      kiri phpinfo --json > phpinfo.json
      kiri phpinfo --md > phpinfo.md
      ```

      Writes to stdout either way — redirect it to a file, or pipe it
      straight into a diff when chasing a version-specific bug.

      ## Typical workflow

      A normal session runs `kiri serve` once, in one terminal, and
      leaves it open through the whole editing session; `kiri export`
      is the last step, right before a push, to sanity-check the actual
      production output in `dist/`:

      ```bash
      npx kiri serve --port 4321
      # …edit src/**, save, the open tab reloads on its own…
      npx kiri export
      ```

      `kiribuild` runs `kiri export` again in CI on push — so a clean
      local `kiri export` is a strong signal the deploy will succeed too.
      If a build ever behaves differently locally than in CI, `kiri cache
      purge` rules out a stale local cache before anything else.
    </markdown>
