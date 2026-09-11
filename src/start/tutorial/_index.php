<?php
/**
 * @title    Tutorial
 * @section  start
 * @abstract Build a small real site end to end — one concept per part.
 */

$parts = [
    ['1-setup',          'Setup',              'kiri create, the folder layout, kiri watch.'],
    ['2-pages-layout',   'Pages & layout',     'Three pages, a shared header/footer, PHPDOC.'],
    ['3-content',        'Content',            'Markdown, a YAML data file, @content.'],
    ['4-styles-scripts', 'Styles & scripts',   'Retheming with canva, dark mode, the managed <head>.'],
    ['5-images',         'Images',             'The image autogenerator — a hero and a gallery.'],
    ['6-seo',            'SEO',                'The meta: and jsonld: blocks, per-page overrides.'],
    ['7-deploy',         'Deploy',             'kiribuild, GitHub Pages, kiri export.'],
];
?>

<section class="section wrap doc-head">
    <span class="eyebrow">Getting started</span>
    <h1><?php echo str_htmlesc($title); ?></h1>
    <p class="lead"><?php echo str_htmlesc($abstract); ?></p>
</section>

<section class="section wrap">
    <div class="prose">
        <p>
            Across seven short parts, you'll build <strong>Studio Plié</strong> —
            a small site for a paper-folding studio: a home page, a notes
            section, a gallery. Each part adds exactly one capability to the
            same project, so by the end you've touched every corner of
            Kirigami on something real, not seven disconnected snippets.
        </p>
        <p>Assumes you've done <a href="<?php echo $relroot; ?>start/install/">install</a> — everything else starts from here.</p>
    </div>

    <ol class="steps">
        <?php foreach ($parts as $i => [$slug, $label, $desc]): ?>
            <li>
                <h3><a href="<?php echo $relroot . 'start/tutorial/' . $slug . '/'; ?>"><?php echo str_htmlesc($label); ?></a></h3>
                <p><?php echo str_htmlesc($desc); ?></p>
            </li>
        <?php endforeach; ?>
    </ol>
</section>

<div class="wrap">
    <nav class="tutorial-nav">
        <span class="tutorial-nav__step">Part 0 of 7</span>
        <a rel="prev" href="<?php echo $relroot; ?>start/quickstart/">Quickstart</a>
        <a rel="next" href="<?php echo $relroot; ?>start/tutorial/1-setup/">Part 1 — Setup</a>
    </nav>
</div>
