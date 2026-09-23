<?php
/**
 * @title    Templates
 * @section  templates
 * @type     doc
 * @abstract Official starting points for `kiri create` — clone one instead
 *           of starting from a blank folder.
 */
?>

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
            <img asset="showcase/template-default.png" width="640" height="360" cover class="card__thumb" alt="The default template's homepage">
            <h3>default</h3>
            <p>
                The minimal starter: a layout, two pages, a themeable
                light/dark stylesheet, and a few lines of progressive-
                enhancement JavaScript. Start here for a real site — there's
                nothing to strip out first.
            </p>
            <p class="card__links">
                <a href="https://php-kirigami.github.io/template-default/">Live demo</a>
                &middot; <a href="https://github.com/php-kirigami/template-default">Source</a>
            </p>
        </article>
        <article class="card">
            <img asset="showcase/template-demo.png" width="640" height="360" cover class="card__thumb" alt="The demo template's homepage">
            <h3>demo</h3>
            <p>
                A guided tour of every feature — Markdown, images, code
                highlighting, data files, tags &amp; hooks, three official
                plugins — each as a real page with its source alongside.
                Start here to see what Kirigami can do before committing to
                a shape for your own site.
            </p>
            <p class="card__links">
                <a href="https://php-kirigami.github.io/template-demo/">Live demo</a>
                &middot; <a href="https://github.com/php-kirigami/template-demo">Source</a>
            </p>
        </article>
    </div>
</section>

<hr class="fold">

<section class="section wrap">
    <div class="prose">
        <markdown>
          Both templates ship a `.github/workflows/page.yml` wired to
          [kiribuild](https://github.com/marketplace/actions/kiribuild), our
          GitHub Action published on the Marketplace — push to `main` and it
          deploys to GitHub Pages, no further setup. See
          [Tutorial → Deploy](../start/tutorial/7-deploy/) for how that
          workflow is put together, or [Showcase](../showcase/) to see both
          templates live, deployed exactly as cloned.

          Each also ships its own `CLAUDE.md` — a project-specific brief for
          [Claude Code](https://claude.com/claude-code) covering where things
          live and how this exact template is put together, so an AI-assisted
          session starts oriented instead of guessing from the file tree.
        </markdown>
    </div>
</section>
