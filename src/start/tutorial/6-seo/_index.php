<?php
/**
 * @title    Tutorial · SEO
 * @section  start
 * @abstract One config block and a per-page override, and the whole head
 *           is handled.
 */
?>

<section class="section wrap doc-head">
    <span class="eyebrow">Tutorial &middot; Part 6 of 7</span>
    <h1>SEO</h1>
    <p class="lead"><?php echo str_htmlesc($abstract); ?></p>
</section>

<section class="section wrap">
    <ol class="steps">
        <li>
            <h3>Turn metadata on</h3>
            <div class="prose">
                <markdown>
                One block in `kirigami.yaml`, fine empty:

                ```yaml
                seo:
                  jsonld: {}
                ```

                `seo:` derives `<title>`, the description, Open Graph,
                Twitter Card, the canonical link, and the favicon tags from
                the `kirigami:` block plus each page's PHPDOC — nothing to
                write by hand in `header.php`. The nested `jsonld:` does the
                same for a schema.org `<script type="application/ld+json">`
                block — its own independent opt-in, so a project can skip it
                and keep just the `<head>` tags. Drop `favicon.ico` and
                `apple-touch-icon.png` in `src/` and they're picked up
                automatically.
                </markdown>
            </div>
        </li>
        <li>
            <h3>Override per page</h3>
            <div class="prose">
                <markdown>
                A page's own PHPDOC wins over the project-wide defaults:

                ```php
                /**
                 * @title           The first pleat
                 * @meta_description How Studio Plié got its name.
                 * @meta_image      images/fold-01.jpg
                 */
                ```

                A tag `header.php` already writes by hand is detected and
                left alone — `seo:` fills gaps, it never duplicates.
                </markdown>
            </div>
        </li>
        <li>
            <h3>Check the sitemap</h3>
            <div class="prose">
                <markdown>
                Every build with `prepros:` set regenerates `sitemap.xml` at
                `kirigami.root`, one `<url>` per rendered page, from
                `kirigami.baseurl` — nothing to maintain by hand as pages get
                added or removed.
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
        > This whole site runs on exactly this one block — every page
        > you've read in this tutorial got its title, description and social
        > card the same way, straight from its own PHPDOC. It goes one step
        > further for its favicon and `og:image`: since it already has the
        > [image pipeline](../5-images/) wired up, `seo.favicon` /
        > `seo.appleTouchIcon` / `seo.image` point at generated files instead
        > of ones dropped in by hand — see
        > [Docs → Config → seo](../../../docs/config/#seo) for that option.
        </markdown>
    </div>
</section>

<div class="wrap">
    <nav class="tutorial-nav">
        <span class="tutorial-nav__step">Part 6 of 7</span>
        <a rel="prev" href="<?php echo $relroot; ?>start/tutorial/5-images/">Part 5 — Images</a>
        <a rel="next" href="<?php echo $relroot; ?>start/tutorial/7-deploy/">Part 7 — Deploy</a>
    </nav>
</div>
