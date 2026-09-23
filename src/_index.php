<?php
/**
 * @title    Kirigami
 * @section  home
 * @abstract A static site generator that turns PHP into fast, dependency-free
 *           HTML, no server required.
 */
?>

<section class="hero wrap">
    <p class="eyebrow">PHP 8.5 &middot; WebAssembly &middot; zero server</p>
    <h1><?php echo str_htmlesc($tagline); ?></h1>
    <p class="lead"><?php echo str_htmlesc($description); ?></p>
    <p>
        <a class="btn" href="<?php echo $relroot; ?>start/">Get started</a>
        <a class="btn btn--ghost" href="<?php echo $relroot; ?>docs/">Read the docs</a>
    </p>
</section>

<section class="section wrap">
    <h2 class="section-title">Four ways in, one engine</h2>

    <div class="gateways">
        <gateway name="vscode" kicker="In your editor" title="VS Code extension" href="<?php echo $relroot; ?>docs/vscode/">
            Create a project, build, export and preview from the Command
            Palette. The status bar runs the dev server; saves rebuild and
            reload the preview.
        </gateway>
        <gateway name="cli" kicker="In your terminal" title="Command line" href="<?php echo $relroot; ?>docs/cli/">
            `kiri serve` while you write, `kiri export` when you ship. One
            `npm install`, no PHP on the machine.
        </gateway>
        <gateway name="mcp" kicker="With your AI assistant" title="MCP server" href="<?php echo $relroot; ?>docs/mcp/">
            `kiri mcp` hands your assistant the project: it scaffolds,
            builds, validates and reads the results as structured data.
        </gateway>
        <gateway name="api" kicker="In your own code" title="JavaScript API" href="<?php echo $relroot; ?>docs/api/">
            `load()` a project and call `build()`, `export()` or `serve()`
            from any Node script: the engine the other three are built on.
        </gateway>
    </div>
</section>

<hr class="fold">

<section class="section wrap">
    <h2 class="section-title">You write PHP. It compiles to flat HTML.</h2>
    <p class="lead lead--wide">
        A page is a real PHP file: includes, loops, a PHPDOC block that
        becomes your variables. Kirigami runs PHP 8.5 entirely in
        WebAssembly, inside Node, and compiles every page to plain HTML at
        build time. Nothing runs on your server, because there is no server.
    </p>

    <div class="compare">
        <div class="compare__panel">
            <span class="compare__label">src/_index.php</span>
            <markdown>
            ```php
            /**
             * @title Home
             * @type  page
             */

            echo str_htmlesc($tagline);
            ```
            </markdown>
        </div>
        <div class="compare__panel">
            <span class="compare__label">src/index.html, after kiri build</span>
            <markdown>
            ```html
            <h1>Fold PHP into flat HTML.</h1>
            ```
            </markdown>
        </div>
    </div>
</section>

<hr class="fold">

<section class="section wrap">
    <h2 class="section-title">What you get</h2>

    <div class="grid" data-reveal>
        <card title="Real PHP templating">
            Includes, loops, Markdown, YAML and page types, compiled to clean
            HTML at build time, not served at runtime.
        </card>
        <card title="An asset pipeline">
            esbuild for JavaScript, Sass for styles, an image autogenerator,
            all wired from one `kirigami.yaml`.
        </card>
        <card title="SEO without the boilerplate">
            Title, description, Open Graph, canonical links and a schema.org
            graph, generated from one `seo:` block.
        </card>
        <card title="One-command export">
            `kiri export` ships a static site for GitHub Pages or any static
            host, sitemap included.
        </card>
        <card title="Instant scaffolding" href="<?php echo $relroot; ?>templates/">
            `kiri create` starts from an official template, so a new project
            begins with something real.
        </card>
        <card title="Zero native dependencies">
            PHP, images and the cache run on WebAssembly and Node's standard
            library: no compiler, no binding to install.
        </card>
    </div>
</section>

<hr class="fold">

<section class="section wrap">
    <h2 class="section-title">What it needs, and what it doesn't</h2>
    <ul class="reqs">
        <li><strong>Node.js ≥ 24</strong> and npm: that's the whole install.</li>
        <li><strong>No PHP install.</strong> The runtime is bundled, compiled to WebAssembly.</li>
        <li><strong>No server</strong> in production: the output is plain files.</li>
        <li><strong>No headless browser</strong> anywhere in the toolchain.</li>
        <li>Open source: Kirigami's packages are GPL-3.0-or-later, the bundled PHP runtime GPL-2.0-or-later. Your site's own license is yours to choose; see <a href="<?php echo $relroot; ?>about/">About</a>.</li>
    </ul>
</section>

<hr class="fold">

<section class="section wrap">
    <h2 class="section-title">Built with itself</h2>
    <div class="prose">
        <markdown>
        This site is a Kirigami project: every page is an `_index.php`, the
        documentation uses a page type for its sidebar, and
        [Ecosystem](ecosystem/) fetches its version numbers from npm at build
        time. Its source is [on GitHub](https://github.com/php-kirigami/php-kirigami.github.io).

        ```bash
        npm install --save-dev @kirigami/cli
        npx kiri create     # pick a template
        npx kiri serve      # build, then live-reload while you edit
        ```
        </markdown>
    </div>
</section>
