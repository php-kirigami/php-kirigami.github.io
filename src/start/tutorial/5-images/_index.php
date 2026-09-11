<?php
/**
 * @title    Tutorial · Images
 * @section  start
 * @abstract One image config, three surfaces, no native dependency.
 */
?>

<section class="section wrap doc-head">
    <span class="eyebrow">Tutorial &middot; Part 5 of 7</span>
    <h1>Images</h1>
    <p class="lead"><?php echo str_htmlesc($abstract); ?></p>
</section>

<section class="section wrap">
    <ol class="steps">
        <li>
            <h3>Point Kirigami at your originals</h3>
            <div class="prose">
                <markdown>
                Drop full-resolution source photos in `assets/images/` (kept
                out of `src/`, never served as-is), then add a small block to
                `kirigami.yaml`:

                ```yaml
                image:
                  format: webp
                  source: assets/images
                  dest:   images
                ```

                `dest` is relative to `kirigami.root` — generated files land
                in `src/images/` and get exported like any other page asset.
                Never hand-edit that folder; it's regenerated from `source`.
                </markdown>
            </div>
        </li>
        <li>
            <h3>A hero background, from Sass</h3>
            <div class="prose">
                <markdown>
                `img-asset()` resizes, registers the source for the build, and
                returns the generated URL — one function, used like any other
                Sass value:

                ```scss
                .hero {
                    background-image: img-asset("atelier-hero.jpg", 1600, 700, true);
                }
                ```

                The three trailing arguments are width, height, and `cover`
                (crop to fill instead of contain) — the exact same shape every
                surface below takes.
                </markdown>
            </div>
        </li>
        <li>
            <h3>A gallery, from a page template</h3>
            <div class="prose">
                <markdown>
                `atelier/_index.php` gets a plain `<img asset>` tag per photo
                — no PHP call needed for the common case:

                ```html
                <img asset="fold-01.jpg" width="480" height="320" cover
                     alt="A box-pleat in progress">
                ```

                Any other attribute (`alt`, `class`, `loading`) passes through
                untouched. Need the URL itself instead — for a `srcset`, say —
                call `IMG::asset()` directly from PHP with the same
                `(path, width, height, cover)` shape.
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
        > Sass function, PHP method, HTML tag — three doors onto the same
        > engine, the same config, and the same output files. Resize logic
        > lives in exactly one place (the `IMG` class, GD with an Imagick
        > fallback, both inside the WASM runtime) — there's no native image
        > library anywhere in the toolchain.
        </markdown>
    </div>
</section>

<div class="wrap">
    <nav class="tutorial-nav">
        <span class="tutorial-nav__step">Part 5 of 7</span>
        <a rel="prev" href="<?php echo $relroot; ?>start/tutorial/4-styles-scripts/">Part 4 — Styles &amp; scripts</a>
        <a rel="next" href="<?php echo $relroot; ?>start/tutorial/6-seo/">Part 6 — SEO</a>
    </nav>
</div>
