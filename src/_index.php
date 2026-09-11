<?php
/**
 * @title    Kirigami
 * @section  home
 * @abstract A static site generator that turns PHP into fast, dependency-free
 *           HTML — no server required.
 */
?>

<section class="hero wrap">
    <p class="eyebrow">PHP 8.5 &middot; WebAssembly &middot; zero server</p>
    <h1><?php echo str_htmlesc($tagline); ?></h1>
    <p class="lead"><?php echo str_htmlesc($description); ?></p>
    <p>
        <a class="btn" href="<?php echo $relroot; ?>docs/">Read the docs</a>
        <a class="btn btn--ghost" href="https://github.com/php-kirigami/kirigami">Kirigami on GitHub</a>
    </p>
</section>

<hr class="fold">

<section class="section wrap">
    <h2>You write PHP. It compiles to flat HTML.</h2>
    <p class="lead lead--wide">
        A page is a real PHP file — includes, loops, a PHPDOC block that
        becomes your variables. Kirigami runs a PHP 8.5 runtime entirely in
        WebAssembly, inside Node, and compiles every page straight to plain
        HTML at build time. Nothing runs on your server, because there is no
        server.
    </p>

    <div class="compare">
        <div class="compare__panel">
            <span class="compare__label">src/_index.php</span>
            <markdown>
            ```php
            /**
             * @title Home
             */

            echo str_htmlesc($tagline);
            ```
            </markdown>
        </div>
        <div class="compare__panel">
            <span class="compare__label">src/index.html — after `kiri build`</span>
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
    <h2>What you get</h2>

    <div class="grid" data-reveal>
        <article class="card">
            <h3>Real PHP templating</h3>
            <p>Includes, loops, Markdown, YAML — compiled to clean HTML at
            build time, not served at runtime.</p>
        </article>
        <article class="card">
            <h3>Integrated asset pipeline</h3>
            <p>esbuild for JavaScript, Sass for styles, wired in from a single
            <code>kirigami.yaml</code>.</p>
        </article>
        <article class="card">
            <h3>One-command export</h3>
            <p><code>kiri export</code> ships a fully static site, ready for
            GitHub Pages or any static host — banner and sitemap included.</p>
        </article>
        <article class="card">
            <h3>Instant scaffolding</h3>
            <p><code>kiri create</code> clones an <a href="<?php echo $relroot; ?>templates/">official template</a>
            so a new project starts from something real.</p>
        </article>
        <article class="card">
            <h3>A managed &lt;head&gt;</h3>
            <p>Stylesheet, bundle, theme guard, SEO metadata and JSON-LD —
            injected automatically, nothing to hand-wire per page.</p>
        </article>
        <article class="card">
            <h3>Zero native dependencies</h3>
            <p>Images, the cache, tar extraction — all built on WASM and
            Node's own standard library, not a compiled binding.</p>
        </article>
    </div>
</section>

<hr class="fold">

<section class="section wrap">
    <h2>What it needs — and doesn't</h2>
    <ul class="reqs">
        <li><strong>Node.js ≥ 24</strong> and npm — that's the whole install.</li>
        <li><strong>No PHP install.</strong> The runtime is bundled, compiled to WebAssembly.</li>
        <li><strong>No server process</strong> at any point, dev or production — <code>kiri watch</code> rebuilds files, it doesn't serve them.</li>
        <li><strong>No headless browser</strong> anywhere in the toolchain.</li>
        <li>Everything is MIT, except the bundled PHP runtime itself (<code>@kirigami/php-wasm</code>, GPL-2.0-or-later) — see <a href="<?php echo $relroot; ?>about/">About</a>.</li>
    </ul>
</section>

<hr class="fold">

<section class="section wrap">
    <h2>Built with itself, in the open</h2>
    <div class="prose">
        <markdown>
        This page is a `_index.php` file, compiled by the same `kiri` CLI
        anyone installs from npm. The site is being built in public, phase by
        phase — [`/docs/`](docs/) is the doc-page model, [`/ecosystem/`](ecosystem/)
        pulls its version numbers live from npm at build time, and more lands
        as each phase ships.

        ```bash
        npm install -D @kirigami/kirigami
        npx kiri watch      # rebuild on save, no server
        npx kiri export     # production build into dist/
        ```
        </markdown>
    </div>
</section>
