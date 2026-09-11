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
    <h2>How it works</h2>
    <p class="lead" style="max-width:60ch">
        You write <code>_*.php</code> page templates — real PHP, with includes,
        loops and Markdown. Kirigami runs a PHP 8.5 runtime entirely in
        WebAssembly, inside Node, and compiles every page straight to plain
        HTML. No PHP install, no server, no headless browser.
    </p>

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
            <p><code>kiri create</code> clones an official template so a new
            project starts from something real, not a blank folder.</p>
        </article>
    </div>
</section>

<hr class="fold">

<section class="section wrap">
    <h2>Built with itself, in the open</h2>
    <div class="prose">
        <markdown>
        This page is a `_index.php` file, compiled to `index.html` by the same
        `kiri` CLI anyone installs from npm. The site is being built in
        public, phase by phase — the [`/docs/`](docs/) page is the first real
        piece of documentation, and more lands as each phase of the plan ships.

        ```bash
        npm install -D @kirigami/kirigami
        npx kiri watch      # rebuild on save, no server
        npx kiri export     # production build into dist/
        ```
        </markdown>
    </div>
</section>
