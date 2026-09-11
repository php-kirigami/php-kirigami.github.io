<?php
/**
 * @title    Tutorial · Deploy
 * @section  start
 * @abstract One workflow file, push to main, done.
 */
?>

<section class="section wrap doc-head">
    <span class="eyebrow">Tutorial &middot; Part 7 of 7</span>
    <h1>Deploy</h1>
    <p class="lead"><?php echo str_htmlesc($abstract); ?></p>
</section>

<section class="section wrap">
    <ol class="steps">
        <li>
            <h3>Add the workflow</h3>
            <div class="prose">
                <markdown>
                `.github/workflows/page.yml`:

                ```yaml
                name: Build & Deploy

                on:
                  push:
                    branches: ["main"]

                permissions:
                  contents: write
                  pages: write
                  id-token: write

                jobs:
                  build-and-deploy:
                    runs-on: ubuntu-latest
                    steps:
                      - uses: actions/checkout@v7
                      - uses: php-kirigami/kiribuild@v2
                        with: { node-version: '24' }
                      - uses: actions/upload-pages-artifact@v5
                        with: { path: dist }
                      - uses: actions/deploy-pages@v5
                ```

                `kiribuild@v2` does exactly three things: install Node 24,
                install the `kiri` CLI, run `kiri export`. Checkout, uploading
                the artifact, and the Pages deploy step are yours to wire —
                that's deliberate, so the action stays small and the workflow
                stays readable. It's a real published action — listed on the
                [GitHub Marketplace](https://github.com/marketplace/actions/kiribuild),
                findable straight from a workflow file's Actions sidebar,
                not just a repo you happen to reference.
                </markdown>
            </div>
        </li>
        <li>
            <h3>Turn on Pages, once</h3>
            <div class="prose">
                <markdown>
                In the repo's settings: **Settings → Pages → Source: GitHub
                Actions**. No branch to pick, no folder — the workflow above
                handles the whole thing from here on.
                </markdown>
            </div>
        </li>
        <li>
            <h3>Push</h3>
            <div class="prose">
                <markdown>
                ```bash
                git add -A
                git commit -m "Studio Plié: first deploy"
                git push
                ```

                Every push to `main` re-runs `kiri export` and republishes
                `dist/`. Locally, the same export step is one command:

                ```bash
                npx kiri export
                ```
                </markdown>
            </div>
        </li>
    </ol>
</section>

<hr class="fold">

<section class="section wrap">
    <div class="prose">
        <markdown>
        > [!NOTE]
        > That's the whole tutorial. Studio Plié has pages, real content, a
        > theme with dark mode, a resized image pipeline, full SEO metadata,
        > and now a live deploy on every push — the same seven pieces every
        > Kirigami site is made of, including this one.
        </markdown>
    </div>
</section>

<div class="wrap">
    <nav class="tutorial-nav">
        <span class="tutorial-nav__step">Part 7 of 7</span>
        <a rel="prev" href="<?php echo $relroot; ?>start/tutorial/6-seo/">Part 6 — SEO</a>
        <a rel="next" href="<?php echo $relroot; ?>docs/">Explore the docs</a>
    </nav>
</div>
