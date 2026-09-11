<?php
/**
 * @title    Showcase
 * @section  showcase
 * @abstract Sites actually running on Kirigami — starting with this one.
 */
?>

<section class="section wrap doc-head">
    <span class="eyebrow">Showcase</span>
    <h1><?php echo str_htmlesc($title); ?></h1>
    <p class="lead"><?php echo str_htmlesc($abstract); ?></p>
</section>

<section class="section wrap">
    <div class="grid" data-reveal>
        <article class="card">
            <img asset="showcase/kirigami.png" width="640" height="360" cover class="card__thumb" alt="Kirigami's own documentation site, homepage">
            <h3>Kirigami</h3>
            <p>
                This site. Every page you're reading is a PHP template
                compiled by <code>kiri export</code> — the docs, the CLI
                reference, this showcase — built in the open, phase by
                phase, on the <code>@kirigami/canva</code> design system and
                <code>@kirigami/plugin-highlight</code> for its code blocks:
                the same packages anyone installs from npm, not a private
                fork.
            </p>
            <p class="card__links">
                <a href="https://github.com/php-kirigami/php-kirigami.github.io">Source</a>
            </p>
        </article>
        <article class="card">
            <img asset="showcase/template-demo.png" width="640" height="360" cover class="card__thumb" alt="The Kirigami Demo template, homepage">
            <h3>Kirigami Demo</h3>
            <p>
                The <code>demo</code> template, deployed as-is: a guided tour
                of Markdown, images, syntax highlighting, data files, and the
                tag/hook extension points — each one demoed as a real page
                rather than described in prose. It's also where
                <code>&lt;extlink&gt;</code> and <code>&lt;youtube&gt;</code>
                / <code>&lt;vimeo&gt;</code> — two of the official plugins —
                get their first real usage outside their own repos.
            </p>
            <p class="card__links">
                <a href="https://php-kirigami.github.io/template-demo/">Live</a>
                &middot; <a href="https://github.com/php-kirigami/template-demo">Source</a>
            </p>
        </article>
        <article class="card">
            <img asset="showcase/template-default.png" width="640" height="360" cover class="card__thumb" alt="The Kirigami Site starter template, homepage">
            <h3>Kirigami Site (starter)</h3>
            <p>
                The <code>default</code> template, deployed without a single
                line changed — exactly what <code>npx kiri create default</code>
                gives you. Its own homepage doubles as a map of the project
                (<em>"Where things live"</em>): where pages, layouts, tags,
                and styles/scripts each go, so the very first thing a new
                project shows is how it's organized.
            </p>
            <p class="card__links">
                <a href="https://php-kirigami.github.io/template-default/">Live</a>
                &middot; <a href="https://github.com/php-kirigami/template-default">Source</a>
            </p>
        </article>
    </div>
</section>

<hr class="fold">

<section class="section wrap">
    <div class="prose">
        <markdown>
          All three deploy the same way: push to `main`, and
          [`kiribuild`](https://github.com/php-kirigami/kiribuild) (the
          reusable GitHub Action) runs `kiri export` and publishes `dist/`
          to GitHub Pages — no separate hosting, no build server to
          maintain. See [Tutorial → Deploy](../start/tutorial/7-deploy/) for
          the exact workflow file each of them ships.

          Built something with Kirigami you'd like listed here? Open an
          issue on [the main repo](https://github.com/php-kirigami/kirigami/issues)
          with a link — this list is meant to grow.
        </markdown>
    </div>
</section>
