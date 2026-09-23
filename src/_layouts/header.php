<?php
/**
 * prepros.before — prepended to every rendered page.
 *
 * $title / $abstract / $section come from each page's PHPDOC block. Every
 * loose key under `kirigami:` ($project, $baseurl, $author, $tagline, …) is
 * also in scope, alongside $relroot (path back to the site root) and $absurl.
 *
 * <title>, meta description, Open Graph, Twitter Card, canonical and the
 * favicon are all handled automatically by the `meta: {}` block in
 * kirigami.yaml — nothing to hand-write here. Same for the theme guard, the
 * stylesheet <link> and the bundle <script>, via the managed <head>.
 */

$section = $section ?? '';

// Registers the sitewide og:image / favicon / apple-touch-icon derivatives —
// the deterministic output paths kirigami.yaml's `image` / `meta.favicon` /
// `meta.appleTouchIcon` keys point at. Needs to run once per build regardless
// of which page is first, so it lives here rather than on a specific page.
IMG::asset('meta/ogimage.png', 1200, 630, true);

// favicon.ico / apple-touch-icon.png live outside this site's webp default —
// browsers (and iOS specifically, for the touch icon) expect a real PNG for
// both, so the project format is swapped for these two calls only.
$__format = PREPROS::$config->image->format;
PREPROS::$config->image->format = 'png';
IMG::asset('meta/favicon.png', 48, 48);
IMG::asset('meta/favicon.png', 180, 180);
PREPROS::$config->image->format = $__format;

// path (relative to the site root) => [label, section key]. Only list pages
// that actually exist — grow this as each phase of the site lands.
$nav = [
    'start/'    => ['Start',    'start'],
    'docs/'     => ['Docs',     'docs'],
    'plugins/'  => ['Plugins',  'plugins'],
    'examples/' => ['Examples', 'examples'],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body class="page-<?php echo str_htmlesc($section ?: 'home'); ?>">
    <a class="skip-link" href="#main">Skip to content</a>

    <header class="site-header">
        <div class="wrap site-header__inner">
            <a class="brand" href="<?php echo $relroot; ?>">
                <span class="brand__mark" aria-hidden="true"></span>
                <?php echo str_htmlesc($project); ?>
            </a>

            <nav class="site-nav" id="site-nav" aria-label="Primary">
                <ul>
                    <?php foreach ($nav as $path => [$label, $key]): ?>
                        <li>
                            <a href="<?php echo $relroot . $path; ?>"<?php
                                echo ($section ?: 'home') === $key ? ' aria-current="page"' : ''; ?>><?php echo $label; ?></a>
                        </li>
                    <?php endforeach; ?>
                    <li><a href="https://github.com/php-kirigami/kirigami">GitHub</a></li>
                </ul>
            </nav>

            <button class="theme-toggle lightswitch" type="button" data-theme-toggle aria-label="Toggle dark mode">
                <svg viewBox="0 0 55 55" aria-hidden="true">
                    <path d="M55 27.5C55 42.6878 42.6878 55 27.5 55C12.3122 55 0 42.6878 0 27.5C0 12.3122 12.3122 0 27.5 0C42.6878 0 55 12.3122 55 27.5Z"/>
                </svg>
            </button>

            <button class="nav-toggle" type="button" aria-expanded="false" aria-controls="site-nav" aria-label="Toggle menu">
                <span></span>
            </button>
        </div>
    </header>

    <main id="main">
