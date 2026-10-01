<?php
/**
 * @title      Roadmap
 * @section    roadmap
 * @type       doc
 * @abstract   Directions being considered for Kirigami — not a promise, not a
 *             release schedule.
 */
?>

<section class="section wrap">
    <div class="prose">
        <markdown>
          Kirigami has one maintainer and no release schedule. Everything
          below is an open direction, grouped by theme, not a commitment:
          the order shifts whenever something more useful shows up first.
          What already shipped lives in the [Changelog](../changelog/).

          One rule shapes all of it: the CLI, the MCP server, the VS Code
          extension, Kiri Studio and any future interface drive the same
          `Project` engine from `@kirigami/kirigami`. A new interface extends
          that engine; it never re-implements a build, an export or a watcher
          of its own.

          ## Recently moved to the changelog

          These were on this page and have shipped, so they are no longer
          directions: the audio player (`plugin-player`) and the video player
          (`plugin-clip`), Markdown pages (`_index.md`), inherited page
          metadata (`@@tag`), line numbers for `plugin-highlight`, and a
          [desktop app for site owners](../studio/), now Kiri Studio. The
          course components of `plugin-educ` and two new templates, Kiridoc
          and Blog, joined them.

          ## Project and build extensibility

          - **A `prepros:before-render` hook.** `prepros:html` already lets a
            JavaScript hook rewrite the generated HTML; a symmetric hook
            would run before PHP renders the page.
          - **Composer support.** Mount a project's `vendor/` tree and load
            `vendor/autoload.php`, with clear limits on what can run
            in the WebAssembly environment.
          - **Package resolution for `esbuild`.** The `sass` task already
            resolves Node-style package imports (npm, pnpm, workspaces,
            `npm link`); `esbuild` would get the same, once a hook hands it
            a package name rather than an absolute path.
          - **A local CI environment file.** A gitignored file using the
            same variable names as GitHub Actions, so a build can be
            reproduced locally under close-to-CI conditions.
          - **One helper to publish an image through the pipeline.**
            `plugin-player` (cover art) and `plugin-clip` (poster) each
            replicate what `img-asset()` does: output naming, the
            image job, the export destination. It belongs in `@kirigami/sdk`,
            where every plugin could use it.

          ## Content and data

          The common thread: feeding a page or a `_data/` file from
          something other than a hand-written YAML or Markdown file.

          - **Internationalization.** Externalize strings first, then settle
            language routing, project layout and `hreflang` output.
          - **A WordPress REST client.** A PHP helper in the spirit of
            `SCRAPER` for posts and other `/wp-json/` resources.
          - **A database bridge.** Expose the bundled `navicat` extension
            through a PHP API for MySQL, PostgreSQL and SQLite queries over
            an HTTP tunnel: live data instead of a static export.
          - **Native JSON Schema validation.** The native `jsonk` extension
            already replaces JSON encoding and decoding; it may also replace
            the pure-PHP validator behind `SCHEMA`, but only once it proves
            compatible with Kirigami's and the plugins' schemas.
          - **JSONPath in the PHP classes.** The runtime now ships the
            `jsonpath` extension; a small wrapper would make it as easy to
            reach from a page as `YAML::` and `MD::`.

          ## Plugins

          Each one does its heavy lifting at build time, so the generated
          page ships only the result.

          - **`plugin-gdrive`: build a site from Google Drive.** Write
            pages in Google Docs and keep data in Google Sheets; the build
            turns each Doc into a Markdown page and each Sheet into a
            `_data/` file your templates already know how to read. Editors
            never touch the repository. Fetched documents are cached on
            disk, like `plugin-extlink`'s previews, so a rebuild only
            downloads what changed. Open: the access model (links published
            to the web, or a service-account token from the environment), how
            pages and sections map, and how images are handled.
          - **A faster poster for `plugin-clip`.** Picking a poster takes
            about 16 seconds for a three-minute 720p video. The decoder and
            the scoring in `bestframe` have cheap wins left to find.

          ## Generated site features

          - **Analytics.** Inject Google Analytics' `gtag.js` from a
            measurement ID in the `seo:` block.
          - **A build-free theme toggle.** When a page has
            `data-theme-toggle`, inject canva's small theme runtime, so a
            working light/dark switch needs no JavaScript, import or task.
            `@kirigami/canva/theme` stays the source of truth for its
            storage, events and attributes.
          - **A theme fade that plugins keep.** A rule that sets its own
            `transition` replaces canva's palette fade on that element, so
            its colours flip at once, as on the `extlink` card and the
            `embed` play button. canva would expose the list as a custom
            property that plugins reuse, and settle whether the colour fade
            should survive `prefers-reduced-motion`.
          - **Icon generation.** `favicon.ico` and `apple-touch-icon.png`
            from one source image, through Imagick and the existing `image:`
            configuration.
          - **A React template.** An official JSX/TSX template (esbuild
            already compiles both). Not scoped yet: build-time static HTML,
            client hydration or both, and how it sits next to PHP pages,
            need deciding first.

          ## Interfaces and delivery

          - **Kiri Studio, next.** Signed installers (Windows warns on first
            install and macOS needs right-click, Open today), which would also
            turn on automatic updates for macOS; more than one images folder
            per site; and a wider real-world check, with more clients and more
            kinds of sites.
          - **MCP that knows about Studio.** The server validates a `studio:`
            block but cannot explain it. A `studio` topic for
            `kirigami_doc_hints` and an example in `kirigami_site_blueprint`
            would let an assistant set a site up for editing.
          - **MCP discovery for more AI clients.** Today `kiri mcp` is found
            automatically by Claude Code (through the project's `.mcp.json`)
            and by VS Code agents when the Kirigami extension is installed.
            New projects would also get the file each other client reads:
            `.vscode/mcp.json` for VS Code without the extension,
            `.cursor/mcp.json` for Cursor, `.gemini/settings.json` for
            Gemini CLI, `.codex/config.toml` for Codex and
            `.zed/settings.json` for Zed. Clients with only a global
            configuration, such as Windsurf and Claude Desktop, get a
            documented snippet instead. Undecided: written by default, or
            only on request, since each one adds a tool-specific file to
            every project.
          - **MCP background operations.** `serve` and `watch` over MCP,
            with explicit start, status and stop, so an agent always knows
            who owns the long-lived server.
          - **More from the VS Code extension.** Editor diagnostics, task
            integration and multi-root workspaces, once the extension has been
            checked on every platform it ships for.
          - **`kiri deploy`, with provider plugins.** Ship an exported
            `dist/` over FTP, to a Git branch or elsewhere, for hosting
            outside GitHub Pages. It complements
            [kiribuild](https://github.com/php-kirigami/kiribuild) rather than
            replacing it.
          - **SchemaStore registration.** Submit `kirigami.schema.json` to
            [SchemaStore](https://www.schemastore.org/), so YAML tooling
            completes `kirigami.yaml` without a schema comment at the top of
            the file.

          ## Runtime and toolchain

          - **A reproducible PHP → WebAssembly build.** Grow the current
            compiler, derived from upstream, into a build pipeline Kirigami
            clearly owns for PHP, its libraries and the bundled extensions.
            The supported extension matrix and the reproducibility checks
            get scoped before anything replaces today's process.
        </markdown>
    </div>
</section>
