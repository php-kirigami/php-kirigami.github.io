<?php
/**
 * prepros.types.docs.before — the /docs/ sub-site (@type docs): the docs
 * sidebar, then the page head and an "On this page" box filled in from the
 * page's <h2> headings (a post_render hook in _lib/functions.php). The page
 * itself writes Markdown; it is already inside a .prose column.
 */
?>
<div class="docs wrap">
    <?php echo kirigami_docs_nav($relroot); ?>
    <article class="docs-main">
        <header class="docs-head">
            <span class="eyebrow"><?php echo str_htmlesc($group ?? 'Documentation'); ?></span>
            <h1><?php echo str_htmlesc($title); ?></h1>
            <?php if (!empty($abstract)): ?>
                <p class="lead"><?php echo str_htmlesc($abstract); ?></p>
            <?php endif; ?>
        </header>
        <nav class="docs-toc" data-auto></nav>
        <div class="prose">
