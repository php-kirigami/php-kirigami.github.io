<?php
/**
 * @title      Design
 * @section    design
 * @type       doc
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

<section class="section wrap">
    <div class="prose">
        <markdown>
          Warm paper, near-black ink, one deep-green accent — decided once,
          early, and never redecided per page. Every color below is a
          `@kirigami/canva` `conf` token: a CSS custom property, so this
          whole page (and the whole site) repaints correctly in dark mode
          with nothing hand-toggled. Try the switch in the header.

          None of these eight names are this site's invention — they're
          `conf`'s own token set (`$bg`, `$surface`, `$ink`, `$accent`, …),
          the same eight every Kirigami project starts from and overrides
          through `@forward "@kirigami/canva/conf" with (...)`. This page is
          what that override looks like once it's actually been used —
          [Config → conf tokens](../docs/config/#conf) has the full option
          list, including the dark-palette counterparts (`$dark-bg`,
          `$dark-ink`, …) this site's own dark mode is built from.
        </markdown>
    </div>
</section>

<section class="section wrap">
    <h2>Palette</h2>
    <div class="grid" data-reveal>
        <?php foreach ($tokens as [$name, $role]): ?>
            <div class="card card--static token-card">
                <div class="token-card__swatch token-card__swatch--<?php echo $name; ?>"></div>
                <div class="token-card__body">
                    <code>--<?php echo $name; ?></code>
                    <p class="token-card__label"><?php echo str_htmlesc($role); ?></p>
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
            All three are declared once, in <code>_conf.scss</code>'s
            <code>$fonts</code> map — `conf` handles the <code>@font-face</code>
            (variable-font ranges included) and the two role variables
            (<code>--font-heading</code> / <code>--font-body</code>);
            <code>--font-mono</code> is this site's own third role, since
            `conf` only ships the first two.
        </p>
    </div>

    <div class="grid" data-reveal>
        <div class="card card--static">
            <p class="eyebrow">Heading &middot; Quicksand</p>
            <h3 class="type-sample__heading">Fold PHP into flat HTML</h3>
        </div>
        <div class="card card--static">
            <p class="eyebrow">Body &middot; Roboto Flex</p>
            <p>A static site generator that turns PHP into fast, dependency-free HTML — no server required.</p>
        </div>
        <div class="card card--static">
            <p class="eyebrow">Mono &middot; JetBrains Mono</p>
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
          `@use "@kirigami/canva/main";` — every one of them is `conf`
          tokens underneath, so light/dark comes for free. Each is used for
          real elsewhere on this site (linked below its demo), not just
          shown here in isolation.
        </markdown>
    </div>

    <div class="component-demo">
        <div class="doc-head">
            <p class="eyebrow">Breadcrumb</p>
        </div>
        <p class="component-demo__note">
            A trail of links plus the current page — pairs with
            <code>FS::getBreadcrumb()</code>, which walks a page's real
            position in the file tree instead of a hand-maintained array.
            Every <a href="../docs/">Docs</a> sub-page uses exactly this.
        </p>
        <nav class="breadcrumb" aria-label="Breadcrumb">
            <a href="../docs/">Docs</a> <span aria-hidden="true">/</span>
            <a href="../docs/php/">PHP class library</a> <span aria-hidden="true">/</span>
            <span aria-current="page">FS</span>
        </nav>
    </div>

    <div class="component-demo">
        <div class="doc-head">
            <p class="eyebrow">Quick-jump list</p>
        </div>
        <p class="component-demo__note">
            An inline list of anchor links for a long reference page — see
            the top of <a href="../docs/php/">PHP class library</a> or
            <a href="../docs/cli/">CLI</a> for the real thing, one link per
            `##` heading.
        </p>
        <ul class="docs-toc">
            <?php foreach (['PREPROS', 'MD', 'HTML', 'YAML', 'IMG', 'FS', 'CURL'] as $name): ?>
                <li><a href="../docs/php/#<?php echo strtolower($name); ?>"><?php echo $name; ?></a></li>
            <?php endforeach; ?>
        </ul>
    </div>

    <div class="component-demo">
        <div class="doc-head">
            <p class="eyebrow">Table &amp; badges</p>
        </div>
        <p class="component-demo__note">
            A generic data table (wrap in <code>.table-wrap</code> to scroll
            horizontally instead of widening the page) plus a small
            status/license tag — <a href="../ecosystem/">Ecosystem</a> uses
            both for the real package/version/license grid.
        </p>
        <div class="table-wrap">
            <table class="table">
                <thead><tr><th>Package</th><th>Version</th><th>License</th></tr></thead>
                <tbody>
                    <tr><td><code>@kirigami/kirigami</code></td><td class="table__num">v1.5.7</td><td><span class="badge">MIT</span></td></tr>
                    <tr><td><code>@kirigami/php-wasm</code></td><td class="table__num">v8.5.10-5</td><td><span class="badge badge--muted">GPL-2.0-or-later</span></td></tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="component-demo">
        <div class="doc-head">
            <p class="eyebrow">Palette swatches</p>
        </div>
        <p class="component-demo__note">
            Live — <code>IMG::palette()</code> run against this page's own
            source photo, not hard-coded. `colors()` (the Sass function
            version, for a value baked in at build time rather than fetched
            in PHP) does the same median-cut extraction; see
            <a href="../docs/php/#img">Docs → PHP → IMG</a>.
        </p>
        <ul class="palette palette--compact">
            <?php foreach ($palette as $color): ?>
                <li style="background: <?php echo str_htmlesc($color); ?>" title="<?php echo str_htmlesc($color); ?>"></li>
            <?php endforeach; ?>
        </ul>
    </div>
</section>
