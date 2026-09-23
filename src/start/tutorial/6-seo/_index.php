<?php
/**
 * @title    Tutorial · SEO
 * @section  start
 * @type     guide
 * @abstract One config block and a per-page override, and the whole head
 *           is handled.
 */
?>

<section class="section wrap">
    <ol class="steps">
        <li>
            <h3>Turn metadata on</h3>
            <div class="prose">
                <markdown>
                One block in `kirigami.yaml`, fine empty:

                ```yaml
                seo: {}
                ```

                `seo:` derives `<title>`, the description, Open Graph,
                Twitter Card, the canonical link, and the favicon tags from
                the `kirigami:` block plus each page's PHPDOC — nothing to
                write by hand in `header.php`. From the same values it adds
                a schema.org `<script type="application/ld+json">` graph for
                search engines; `jsonld: false` in the block turns just that
                part off. Drop `favicon.ico` and
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
