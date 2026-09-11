<?php
/**
 * prepros.includes entry — include_once'd before any page renders.
 *
 * Register custom tags, Markdown shortcodes and render-pipeline hooks here.
 * Kept intentionally small in phase 0 — grows with the site (the plugin-
 * authoring tutorial in a later phase will add a real example plugin
 * alongside it, not into this file).
 */

// Used in _layouts/footer.php's copyright line.
register_tag('year', fn() => date('Y'));

// Cookbook examples (see /examples/) — small, real, one-off tags registered
// straight from a project's own prepros.includes, no plugin package needed.

// {% badge default|muted text %} — wraps canva's `.badge`/`.badge--muted`.
md_register_plugin('badge', function (array $args, string $body): string {
    $tone = ($args[0] ?? 'default') === 'muted' ? ' badge--muted' : '';
    $text = implode(' ', array_slice($args, 1));
    if ($text === '') return '<!-- badge: missing text -->';
    return '<span class="badge' . $tone . '">' . str_htmlesc($text) . '</span>';
});

// <shortcut keys="Ctrl+K"> — a row of <kbd> for a keyboard shortcut. The "+"
// between keys is a `kbd + kbd::before` rule (_main.scss), not markup.
register_tag('shortcut', function (string $tag, array $attrs, string $body): string {
    $keys = array_filter(array_map('trim', explode('+', $attrs['keys'] ?? '')));
    if (!$keys) return '<!-- shortcut: missing keys attribute -->';
    return implode('', array_map(fn($k) => '<kbd>' . str_htmlesc($k) . '</kbd>', $keys));
});


/**
 * Site-relative URL to another page's `_index.php` — the shape
 * FS::getChildren() / FS::getBreadcrumb() results come in. Built from
 * `PREPROS::$config->root` (same technique FS::getBreadcrumb() itself uses
 * to find the source root) plus the calling page's own `$relroot`, since
 * PREPROS::backtraceFile() reports the file that called *this* function —
 * `_lib/functions.php`, not the page — one frame too shallow to use here.
 */
function kirigami_page_href(object $page, string $relroot): string
{
    $root = rtrim(str_replace('\\', '/', realpath(PREPROS::$config->root)), '/');
    $dir  = rtrim(str_replace('\\', '/', dirname($page->file)), '/');
    $rel  = ltrim(substr($dir, strlen($root)), '/');
    return $relroot . ($rel !== '' ? $rel . '/' : '');
}

/**
 * Renders "Docs / Config" above a /docs/ sub-page, from FS::getBreadcrumb().
 * Every ancestor in the trail needs its own `@breadcrumb true` tag (that's
 * what stops the walk at /docs/ instead of reaching all the way to Home) —
 * see the PHPDOC block on /docs/_index.php and each /docs/ sub-page.
 */
function kirigami_breadcrumb_nav(string $currentTitle, string $relroot): string
{
    $crumbs = fs_get_breadcrumb();
    if (!$crumbs) {
        return '';
    }

    $links = array_map(
        fn($c) => '<a href="' . kirigami_page_href($c, $relroot) . '">' . str_htmlesc($c->title ?? '') . '</a>',
        $crumbs
    );

    return '<nav class="breadcrumb" aria-label="Breadcrumb">'
        . implode(' <span aria-hidden="true">/</span> ', $links)
        . ' <span aria-hidden="true">/</span> <span aria-current="page">' . str_htmlesc($currentTitle) . '</span>'
        . '</nav>';
}


/**
 * The monorepo's own packages — used by /ecosystem/. One place to edit when a
 * package is added or its role changes; kept as a plain function (not a data
 * file) since `npm_pkg` doubles as the key `kirigami_pkg_version()` fetches.
 */
function kirigami_packages(): array
{
    return [
        ['npm_pkg' => 'kirigami',         'role' => 'The kiri CLI — build / export / watch / serve / run / create / install / cache / phpinfo.', 'license' => 'MIT'],
        ['npm_pkg' => 'php-prepros',      'role' => 'The PHP → HTML compiler and PHP class library.',                                'license' => 'MIT'],
        ['npm_pkg' => 'php-wasm',         'role' => 'PHP 8.5 compiled to WebAssembly for Node (JSPI, no browser support).',          'license' => 'GPL-2.0-or-later'],
        ['npm_pkg' => 'struct-walker',    'role' => 'Recursive YAML/JSON walker — file refs, data URIs.',                            'license' => 'MIT'],
        ['npm_pkg' => 'sdk',              'role' => 'Plugin hook registry, and the on-disk Cache.',                                  'license' => 'MIT'],
        ['npm_pkg' => 'canva',            'role' => 'Shared Sass/JS design system — this site included.',                            'license' => 'MIT'],
        ['npm_pkg' => 'plugin-highlight', 'role' => 'Build-time syntax highlighting, 0 runtime JS.',                                  'license' => 'MIT'],
        ['npm_pkg' => 'plugin-extlink',   'role' => 'External link preview cards, SCRAPER-backed, cached to disk.',                   'license' => 'MIT'],
        ['npm_pkg' => 'plugin-embed',     'role' => 'YouTube / Vimeo oEmbed video cards, resolved client-side.',                      'license' => 'MIT'],
    ];
}

/**
 * Latest published npm version of an @kirigami/<pkg>, cached for an hour so a
 * `kiri watch` session doesn't refetch on every save. Needs `prepros.network:
 * true`. Returns null (never throws) on a cold build with no network, or if
 * the registry lookup fails for any reason — callers should render a fallback.
 */
function kirigami_pkg_version(string $pkg): ?string
{
    $cacheKey = "npm_version_{$pkg}";
    $cached = cache_get($cacheKey);
    if ($cached !== null) {
        return $cached;
    }

    $json = curl_get_contents("https://registry.npmjs.org/@kirigami/{$pkg}/latest");
    if ($json === false) {
        return null;
    }

    $data = json_decode($json);
    $version = $data->version ?? null;
    if ($version) {
        cache_set($cacheKey, $version, 3600);
    }
    return $version;
}
