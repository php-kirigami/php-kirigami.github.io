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
          extension and any future interface drive the same `Project` engine
          from `@kirigami/kirigami`. A new interface extends that engine; it
          never re-implements a build, an export or a watcher of its own.

          ## Project and build extensibility

          - **A `prepros:before-render` hook.** `prepros:html` already lets a
            JavaScript hook rewrite the generated HTML; a symmetric hook
            would run before PHP renders the page.
          - **Composer support.** Mount a project's `vendor/` tree and load
            `vendor/autoload.php`, with clear limits on what can run
            in the WebAssembly environment.
          - **Inherited page metadata.** Let an `_index.php` pass its PHPDOC
            fields down to the pages of its section, so section-wide
            defaults stop being repeated on every page.
          - **Package resolution for `esbuild`.** The `sass` task already
            resolves Node-style package imports (npm, pnpm, workspaces,
            `npm link`); `esbuild` would get the same, once a hook hands it
            a package name rather than an absolute path.
          - **A local CI environment file.** A gitignored file using the
            same variable names as GitHub Actions, so a build can be
            reproduced locally under close-to-CI conditions.

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

          ## Upcoming plugins

          Each one does its heavy lifting at build time, so the generated
          page ships only the result. The two media players rest on native
          code compiled to WebAssembly.

          - **`plugin-player`: an audio player.** A player card with a
            SoundCloud-style waveform under the seek bar. The waveform peaks
            come from [BBC's `audiowaveform`](https://github.com/bbc/audiowaveform)
            compiled to WebAssembly: MP3, WAV, FLAC, Ogg (Vorbis and Opus),
            M4A/AAC and WebM audio.
          - **`plugin-clip`: a video player.** A video card whose cover
            image is picked automatically by
            [`@kirigami/bestframe`](https://www.npmjs.com/package/@kirigami/bestframe),
            a WebAssembly module: it samples frames across the video, discards the unusable ones
            and lets a small embedded aesthetic model choose the best one.
            H.264, VP9, HEVC and AV1, in MP4, Matroska and WebM.
          - **`plugin-gdrive`: build a site from Google Drive.** Write
            pages in Google Docs and keep data in Google Sheets; the build turns each Doc into a Markdown page and each
            Sheet into a `_data/` file your templates already know how to
            read. Editors never touch the repository. Fetched documents are
            cached on disk, like `plugin-extlink`'s previews, so a rebuild
            only downloads what changed.

          ## Generated site features

          - **Analytics.** Inject Google Analytics' `gtag.js` from a
            measurement ID in the `seo:` block.
          - **A build-free theme toggle.** When a page has
            `data-theme-toggle`, inject canva's small theme runtime, so a
            working light/dark switch needs no JavaScript, import or task.
            `@kirigami/canva/theme` stays the source of truth for its
            storage, events and attributes.
          - **Icon generation.** `favicon.ico` and `apple-touch-icon.png`
            from one source image, through Imagick and the existing `image:`
            configuration.
          - **Line numbers for `plugin-highlight`.** An option, with the
            matching markup and styles, for a numbered gutter on code
            blocks.
          - **A React template.** An official JSX/TSX template (esbuild
            already compiles both). Not scoped yet: build-time static HTML,
            client hydration or both, and how it sits next to PHP pages,
            need deciding first.

          ## Interfaces and delivery

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
            integration and multi-root workspaces.
          - **`kiri deploy`, with provider plugins.** Ship an exported
            `dist/` over FTP, to a Git branch or elsewhere, for hosting
            outside GitHub Pages. It complements
            [kiribuild](https://github.com/php-kirigami/kiribuild) rather than
            replacing it.
          - **A desktop app.** An Electron interface for people who don't
            work in an editor, driving the same `Project` engine.
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
