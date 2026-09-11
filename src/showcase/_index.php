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
            <h3>Kirigami</h3>
            <p>
                This site. Built in the open, phase by phase, with the
                <code>@kirigami/canva</code> design system and
                <code>@kirigami/plugin-highlight</code> — the same packages
                anyone installs from npm.
            </p>
            <p style="margin-top:.8rem">
                <a href="https://github.com/php-kirigami/php-kirigami.github.io">Source</a>
            </p>
        </article>
        <article class="card">
            <h3>Kirigami Demo</h3>
            <p>
                The <code>demo</code> template, deployed: a guided tour of
                Markdown, images, syntax highlighting, data files, and the
                tag/hook extension points — each feature as a real page.
            </p>
            <p style="margin-top:.8rem">
                <a href="https://php-kirigami.github.io/template-demo/">Live</a>
                &middot; <a href="https://github.com/php-kirigami/template-demo">Source</a>
            </p>
        </article>
        <article class="card">
            <h3>Kirigami Site (starter)</h3>
            <p>
                The <code>default</code> template, deployed as-is: what
                <code>npx kiri create default</code> gives you before you've
                changed a single line.
            </p>
            <p style="margin-top:.8rem">
                <a href="https://php-kirigami.github.io/template-default/">Live</a>
                &middot; <a href="https://github.com/php-kirigami/template-default">Source</a>
            </p>
        </article>
    </div>
</section>

<hr class="fold">

<section class="section wrap">
    <div class="prose">
        <p>
            Built something with Kirigami you'd like listed here? Open an
            issue on <a href="https://github.com/php-kirigami/kirigami/issues">the
            main repo</a> with a link — this list is meant to grow.
        </p>
    </div>
</section>
