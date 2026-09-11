<?php
/**
 * @title    Templates
 * @section  templates
 * @abstract Official starting points for `kiri create` — clone one instead
 *           of starting from a blank folder.
 */
?>

<section class="section wrap doc-head">
    <span class="eyebrow">Templates</span>
    <h1><?php echo str_htmlesc($title); ?></h1>
    <p class="lead"><?php echo str_htmlesc($abstract); ?></p>
</section>

<section class="section wrap">
    <div class="prose">
        <markdown>
        ```bash
        npx kiri create          # interactive: pick a template, name your project
        npx kiri create default  # or name one directly
        npx kiri create --list   # see what's available
        ```

        `kiri create` clones the template's repo, deep-merges its
        `package.json`, writes your answers into `kirigami.yaml`, then
        `git init`s and `npm install`s for you.
        </markdown>
    </div>

    <div class="grid" data-reveal>
        <article class="card">
            <h3>default</h3>
            <p>
                The minimal starter: a layout, two pages, a themeable
                light/dark stylesheet, and a few lines of progressive-
                enhancement JavaScript. Start here for a real site.
            </p>
            <p style="margin-top:.8rem">
                <a href="https://php-kirigami.github.io/template-default/">Live demo</a>
                &middot; <a href="https://github.com/php-kirigami/template-default">Source</a>
            </p>
        </article>
        <article class="card">
            <h3>demo</h3>
            <p>
                A guided tour of every feature — Markdown, images, code
                highlighting, data files, tags &amp; hooks — each as a real
                page with its source alongside. Start here to see what
                Kirigami can do before committing to a shape for your own site.
            </p>
            <p style="margin-top:.8rem">
                <a href="https://php-kirigami.github.io/template-demo/">Live demo</a>
                &middot; <a href="https://github.com/php-kirigami/template-demo">Source</a>
            </p>
        </article>
    </div>
</section>

<hr class="fold">

<section class="section wrap">
    <div class="prose">
        <p>
            Both templates ship a <code>.github/workflows/page.yml</code>
            wired to <a href="https://github.com/marketplace/actions/kiribuild">kiribuild</a>,
            our GitHub Action published on the Marketplace — push to
            <code>main</code> and it deploys to GitHub Pages, no further
            setup. See <a href="<?php echo $relroot; ?>docs/">the docs</a>
            for how that workflow is put together.
        </p>
    </div>
</section>
