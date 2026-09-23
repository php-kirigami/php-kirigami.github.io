<?php
/**
 * @title      Roadmap
 * @section    roadmap
 * @type       doc
 * @abstract   What's actually being considered next — not a promise, not a
 *             release schedule.
 */
?>

<section class="section wrap">
    <div class="prose">
        <markdown>
          Kirigami has one maintainer and no release schedule. This page is
          the honest version of that: the open backlog, grouped by theme,
          in roughly the order it's likely to get picked up — not a
          commitment, and the order shifts whenever something more useful
          shows up first. See the [Changelog](../changelog/) for what's
          already shipped — recently, exactly two items that used to live on
          this page: `/examples` and `/design`.

          ## Plugins

          - **Plugin-declared tasks and commands.** `kirigami.type: "plugin"`
            exists; a plugin contributing its own **task type** (beyond
            `esbuild`/`sass`/`prepros`/`dist`) or its own `kiri` subcommand
            doesn't yet. Related: right now a plugin that needs to bundle its
            own client-side script (`plugin-highlight`'s copy button,
            `plugin-embed`'s observer script) just requires the *project* to
            already have an `esbuild` task — it'd be nicer if a plugin could
            supply that bundling itself when the project has none.
          - **Line numbers for `plugin-highlight`.** No option today to show
            a `#` gutter next to a highlighted block.
          - **A `class=""` attribute on `<extlink>`**, so the generated
            preview card can be styled per-instance without a global
            override.

          ## Build & authoring

          - **`kiri deploy`, with a plugin system for the deploy mechanism**
            (FTP, a push to a Git branch, …) — takes the exported `dist/` and
            ships it, for hosting outside GitHub Pages. Complements
            `kiribuild` (already GitHub Pages via Actions) rather than
            replacing it.
          - **A local, gitignored env file that mirrors GitHub Actions'**,
            so a build can be tested locally under close-to-CI conditions
            (the same env var names) without needing a real GitHub run.
          - **A `humans.txt` generator** from `kirigami.yaml`'s dev info
            (`author` / `email`), generated alongside `sitemap.xml` /
            `robots.txt` — the same spirit as `fillBanner()` already filling
            `###AUTHOR###` / `###EMAIL###` from that block.
          - **A `favicon.ico` / `apple-touch-icon.png` generator** from one
            source image, alongside the existing image autogenerator
            (`image.source` / `image.dest`) — multi-resolution `.ico` needs
            Imagick, not just GD.
          - **Submitting `kirigami.schema.json` to [SchemaStore](https://www.schemastore.org/)**
            so editor autocompletion works without a
            `# yaml-language-server: $schema=...` comment in every project's
            `kirigami.yaml`.
          - **A build-free theme toggle.** The managed `<head>` already
            injects a small FOUC guard; extending it to also drop in the full
            toggle runtime when a page has `data-theme-toggle` would mean
            zero JS/import/task needed for a working light/dark switch —
            `@kirigami/canva/theme` stays the option for anyone who wants the
            JS API (`setTheme()`, events) instead.
          - **A `prepros:before-render` hook** — `prepros:html` (after
            render) already exists; a symmetric before-render one is being
            weighed.
          - Smaller: the `sass` task's package importer (Node-style
            resolution, `npm link`/pnpm/workspaces included) doing the same
            for `esbuild`'s imports, if a real case for it ever shows up.

          ## Data integrations

          Further out, and less scoped than the rest of this page — the
          common thread is feeding a page or a `_data/` file from something
          other than a hand-written YAML/Markdown file:

          - A **`kiri run`-friendly class for querying a database over an
            HTTP tunnel**, in the spirit of Navicat's HTTP-tunnel mechanism
            (the concept, not borrowed code) — live data instead of a static
            export.
          - A **PHP class for WordPress' REST API** (`/wp-json/wp/v2/posts`,
            …), the same spirit as `SCRAPER`, for pulling real WordPress
            content into a page or `_data/`.
          - Official **Google Docs → Markdown** and **Excel → JSON**
            conversion scripts/actions, for feeding a `_data/` file from a
            source non-developers can actually edit.

          ## Bigger picture

          - **`template-react`** — a new official template with a real
            JSX/TSX pipeline (esbuild already supports it natively). Not
            scoped yet: build-time-only static rendering vs. client
            hydration, and how it sits alongside the existing PHP pages, both
            need deciding first.
          - **A Kirigami-maintained fork of the PHP→WebAssembly build**,
            instead of tracking the upstream WordPress Playground fork as-is
            — for more control over which PHP version and extensions ship.
            Not scoped yet: this is a real undertaking (reproducing the build
            pipeline, deciding what to keep), and it'll get its own planning
            pass before any code moves.
        </markdown>
    </div>
</section>
