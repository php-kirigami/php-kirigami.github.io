<?php
/**
 * @title      Design
 * @section    design
 * @abstract   The palette, type and shared components this site itself is
 *             built from — a reference, not something to reverse-engineer
 *             from the CSS.
 */

$tokens = [
    ['bg', 'Page background'],
    ['surface', 'Cards, code blocks'],
    ['surface-2', 'A step up — nav, pills, table stripes'],
    ['border', 'Hairlines, outlines'],
    ['ink', 'Body text, headings'],
    ['ink-muted', 'Secondary text, captions'],
    ['accent', 'Links, the one brand color'],
    ['accent-soft', "Accent's own quiet background"],
];

$palette = img_palette('male-african-bush-elephant.jpg', 5);
?>

<section class="section wrap doc-head">
    <span class="eyebrow">Design</span>
    <h1><?php echo str_htmlesc($title); ?></h1>
    <p class="lead"><?php echo str_htmlesc($abstract); ?></p>
</section>

<section class="section wrap">
    <div class="prose">
        <markdown>
          Warm paper, near-black ink, one deep-green accent — decided once,
          early, and never redecided per page. Every color below is a
          `@kirigami/canva` `conf` token: a CSS custom property, so this
          whole page (and the whole site) repaints correctly in dark mode
          with nothing hand-toggled. Try the switch in the header.
        </markdown>
    </div>
</section>

<section class="section wrap">
    <h2>Palette</h2>
    <div class="grid" data-reveal>
        <?php foreach ($tokens as [$name, $role]): ?>
            <div class="card" style="padding:0; overflow:hidden;">
                <div style="height:4.5rem; background:var(--<?php echo $name; ?>); border-bottom:1px solid var(--border);"></div>
                <div style="padding:1rem;">
                    <code>--<?php echo $name; ?></code>
                    <p style="margin-top:.4rem; color:var(--ink-muted); font-size:.88rem;"><?php echo str_htmlesc($role); ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<hr class="fold">

<section class="section wrap">
    <h2>Type</h2>
    <div class="prose">
        <p>
            Three roles, three families, all embedded locally (no Google
            Fonts request): <strong>Quicksand</strong> for headings,
            <strong>Roboto Flex</strong> for body copy,
            <strong>JetBrains Mono</strong> for labels, nav numbers and code.
        </p>
    </div>

    <div class="grid" data-reveal>
        <div class="card" style="cursor:default;">
            <p class="eyebrow" style="margin-bottom:.6rem;">Heading &middot; Quicksand</p>
            <h3 style="font-size:1.8rem;">Fold PHP into flat HTML</h3>
        </div>
        <div class="card" style="cursor:default;">
            <p class="eyebrow" style="margin-bottom:.6rem;">Body &middot; Roboto Flex</p>
            <p>A static site generator that turns PHP into fast, dependency-free HTML — no server required.</p>
        </div>
        <div class="card" style="cursor:default;">
            <p class="eyebrow" style="margin-bottom:.6rem;">Mono &middot; JetBrains Mono</p>
            <code>kiri build &amp;&amp; kiri export</code>
        </div>
    </div>
</section>

<hr class="fold">

<section class="section wrap">
    <h2>Shared components</h2>
    <div class="prose">
        <markdown>
          Five small, generic patterns that kept turning up identically
          across Kirigami sites — lifted into
          [`@kirigami/canva`'s `styles/main`](https://www.npmjs.com/package/@kirigami/canva)
          so nobody has to re-implement them. Opt-in:
          `@use "@kirigami/canva/main";`.
        </markdown>
    </div>

    <div class="doc-head" style="margin-top:2rem;">
        <p class="eyebrow">Breadcrumb</p>
    </div>
    <nav class="breadcrumb" aria-label="Breadcrumb" style="margin-bottom:2rem;">
        <a href="../docs/">Docs</a> <span aria-hidden="true">/</span>
        <a href="../docs/php/">PHP class library</a> <span aria-hidden="true">/</span>
        <span aria-current="page">FS</span>
    </nav>

    <div class="doc-head">
        <p class="eyebrow">Quick-jump list</p>
    </div>
    <ul class="docs-toc" style="margin-bottom:2rem;">
        <?php foreach (['PREPROS', 'MD', 'HTML', 'YAML', 'IMG', 'FS', 'CURL'] as $name): ?>
            <li><a href="../docs/php/#<?php echo strtolower($name); ?>"><?php echo $name; ?></a></li>
        <?php endforeach; ?>
    </ul>

    <div class="doc-head">
        <p class="eyebrow">Table &amp; badges</p>
    </div>
    <div class="table-wrap" style="margin-bottom:2rem;">
        <table class="table">
            <thead><tr><th>Package</th><th>Version</th><th>License</th></tr></thead>
            <tbody>
                <tr><td><code>@kirigami/kirigami</code></td><td class="table__num">v1.5.3</td><td><span class="badge">MIT</span></td></tr>
                <tr><td><code>@kirigami/php-wasm</code></td><td class="table__num">v8.5.10-5</td><td><span class="badge badge--muted">GPL-2.0-or-later</span></td></tr>
            </tbody>
        </table>
    </div>

    <div class="doc-head">
        <p class="eyebrow">Palette swatches</p>
    </div>
    <p style="color:var(--ink-muted); margin-bottom:.75rem;">
        Live — <code>IMG::palette()</code> run against this page's own source
        photo, not hard-coded:
    </p>
    <ul class="palette" style="max-width:20rem;">
        <?php foreach ($palette as $color): ?>
            <li style="background: <?php echo str_htmlesc($color); ?>" title="<?php echo str_htmlesc($color); ?>"></li>
        <?php endforeach; ?>
    </ul>
</section>
