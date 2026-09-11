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


/**
 * The monorepo's own packages — used by /ecosystem/. One place to edit when a
 * package is added or its role changes; kept as a plain function (not a data
 * file) since `npm_pkg` doubles as the key `kirigami_pkg_version()` fetches.
 */
function kirigami_packages(): array
{
    return [
        ['npm_pkg' => 'kirigami',         'role' => 'The kiri CLI — build / export / watch / run / create / phpinfo.',              'license' => 'MIT'],
        ['npm_pkg' => 'php-prepros',      'role' => 'The PHP → HTML compiler and PHP class library.',                                'license' => 'MIT'],
        ['npm_pkg' => 'php-wasm',         'role' => 'PHP 8.5 compiled to WebAssembly for Node (JSPI, no browser support).',          'license' => 'GPL-2.0-or-later'],
        ['npm_pkg' => 'struct-walker',    'role' => 'Recursive YAML/JSON walker — file refs, data URIs.',                            'license' => 'MIT'],
        ['npm_pkg' => 'sdk',              'role' => 'Plugin hook registry, and the on-disk Cache.',                                  'license' => 'MIT'],
        ['npm_pkg' => 'canva',            'role' => 'Shared Sass/JS design system — this site included.',                            'license' => 'MIT'],
        ['npm_pkg' => 'plugin-highlight', 'role' => 'Build-time syntax highlighting, 0 runtime JS.',                                  'license' => 'MIT'],
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
