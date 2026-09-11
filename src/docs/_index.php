<?php
/**
 * @title      Docs
 * @section    docs
 * @abstract   The complete reference — config, CLI, writing pages, the PHP
 *             class library.
 * @breadcrumb true
 */

$sections = fs_get_children();
?>

<section class="section wrap doc-head">
    <span class="eyebrow">Documentation</span>
    <h1><?php echo str_htmlesc($title); ?></h1>
    <p class="lead"><?php echo str_htmlesc($abstract); ?></p>
</section>

<section class="section wrap">
    <div class="grid" data-reveal>
        <?php foreach ($sections as $s): ?>
            <a class="card" href="<?php echo kirigami_page_href($s, $relroot); ?>">
                <h3><?php echo str_htmlesc($s->title ?? ''); ?></h3>
                <p><?php echo str_htmlesc($s->abstract ?? ''); ?></p>
            </a>
        <?php endforeach; ?>
    </div>
</section>

<hr class="fold">

<section class="section wrap">
    <div class="prose">
        <markdown>
          New to Kirigami? These four pages are the reference — precise, but
          terse. If you want a guided walk instead, start with
          [Getting started](../start/) — the [tutorial](../start/tutorial/)
          builds a real small site, one concept per part, and links back here
          wherever a reference page has the full detail.

          Every fenced code block on this site, including every example
          below, is colored **at build time** by
          [`@kirigami/plugin-highlight`](https://github.com/php-kirigami/kirigami/tree/main/packages/plugin-highlight) —
          zero bytes of highlight.js reach your browser. Hover a block for
          the copy button.

          The card grid above isn't hand-written: it comes from
          [`FS::getChildren()`](php/#fs), walking this page's own
          sub-folders. Add a fifth `_index.php` under `docs/` and it appears
          here on the next build, no list to maintain.
        </markdown>
    </div>
</section>
