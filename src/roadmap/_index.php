<?php
/**
 * @title      Roadmap
 * @section    roadmap
 * @abstract   What's actually being considered next — not a promise, not a
 *             release schedule.
 */
?>

<section class="section wrap doc-head">
    <span class="eyebrow">Roadmap</span>
    <h1><?php echo str_htmlesc($title); ?></h1>
    <p class="lead"><?php echo str_htmlesc($abstract); ?></p>
</section>

<section class="section wrap">
    <div class="prose">
        <markdown>
          Kirigami has one maintainer and no release schedule. This page is
          the honest version of that: the open backlog, grouped by theme,
          in roughly the order it's likely to get picked up — not a
          commitment, and the order shifts whenever something more useful
          shows up first. See the [Changelog](../changelog/) for what's
          already shipped.

          ## On this site

          - **`/examples`** — a gallery of small, focused code snippets (one
            PHPDOC trick, one Sass function, one plugin hook) — shorter and
            more scannable than the tutorial or the reference.
          - **`/design`** — the design language this site itself uses
            (palette, type, the handful of shared components in
            `@kirigami/canva`'s `styles/main`), as a reference rather than
            something you have to reverse-engineer from the CSS.

          ## Plugins

          - **Plugin-declared tasks and commands.** `kirigami.type: "plugin"`
            exists; a plugin contributing its own **task type** (beyond
            `esbuild`/`sass`/`prepros`/`dist`) or its own `kiri` subcommand
            doesn't yet. Related: right now a plugin that needs to bundle its
            own client-side script (`plugin-highlight`'s copy button,
            `plugin-embed`'s observer script) just requires the *project* to
            already have an `esbuild` task — it'd be nicer if a plugin could
            supply that bundling itself when the project has none.
          - **A hard failure for a genuinely unknown option**, not just a
            silent warning — `plugin-highlight`'s `languages:` list is the
            concrete case: a typo'd language name can't be caught by JSON
            Schema (the valid values are highlight.js's own internal names,
            an open set), so it currently just warns and skips it.

          ## Build & authoring

          - **A real end-to-end test of the `kiribuild` GitHub Action** — not
            just a smoke test, but confirming a real site actually deploys
            correctly through it, `kiribuild@v2` → commit-back → Pages, with
            real content.
          - **A `favicon.ico` / `apple-touch-icon.png` generator** from one
            source image, alongside the existing image autogenerator
            (`image.source` / `image.dest`) — multi-resolution `.ico` needs
            Imagick, not just GD.
          - **A build-free theme toggle.** The managed `<head>` already
            injects a small FOUC guard; extending it to also drop in the full
            toggle runtime when a page has `data-theme-toggle` would mean
            zero JS/import/task needed for a working light/dark switch —
            `@kirigami/canva/theme` stays the option for anyone who wants the
            JS API (`setTheme()`, events) instead.
          - **A `prepros:before-render` hook** — `prepros:html` (after
            render) already exists; a symmetric before-render one is being
            weighed.
          - Smaller ones: a clearer error than a raw `EADDRINUSE` when
            `kiri serve`'s port is already taken; the `sass` task's package
            importer (Node-style resolution, `npm link`/pnpm/workspaces
            included) doing the same for `esbuild`'s imports, if a real case
            for it ever shows up.

          ## Bigger picture

          - **A Kirigami-maintained fork of the PHP→WebAssembly build**,
            instead of tracking the upstream WordPress Playground fork as-is
            — for more control over which PHP version and extensions ship.
            Not scoped yet: this is a real undertaking (reproducing the build
            pipeline, deciding what to keep), and it'll get its own planning
            pass before any code moves.
        </markdown>
    </div>
</section>
