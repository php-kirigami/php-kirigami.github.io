<?php
/**
 * prepros.types.docs.before / prepros.types.guide.before — opens a page of a
 * rail section (docs, start, plugins), laid out like a Kiridoc course page:
 * the section's pages as a sidebar on the left, the article in the middle,
 * "On this page" on the right (a post_render hook fills the <!--toc--> marker
 * left by rail.after.php). The page itself only writes its body; sections
 * of the tutorial get their heading and eyebrow from kirigami_guide_steps().
 */
$railSection = $section ?? 'docs';
$railPages   = kirigami_rail_pages($railSection);
$railAt      = kirigami_rail_current($railPages, $absurl);
[, , $railHeading, $railEyebrow] = $railSection === 'start'
    ? (kirigami_guide_steps()[ltrim($absurl, '/')] ?? [null, null, null, null])
    : [null, null, null, null];
$railHeading = $railHeading ?? $title;
$railEyebrow = $railEyebrow ?? ($eyebrow ?? ($railAt !== null ? $railPages[$railAt]->group : kirigami_rail_label($railSection)));
$railHome    = $railPages[0]->href ?? '';
?>
<div class="doc">
    <?php echo kirigami_rail_nav($railSection, $absurl, $relroot); ?>

    <article class="doc__body prose">
        <nav class="breadcrumb" aria-label="Breadcrumb">
            <ol>
                <li><a href="<?php echo $relroot; ?>">Home</a></li>
                <li><a href="<?php echo $relroot . $railHome; ?>"><?php echo str_htmlesc(kirigami_rail_label($railSection)); ?></a></li>
                <?php if (ltrim($absurl, '/') !== $railHome): ?>
                    <li><span aria-current="page"><?php echo str_htmlesc($title); ?></span></li>
                <?php endif; ?>
            </ol>
        </nav>
        <header class="doc__head">
            <span class="eyebrow"><?php echo str_htmlesc($railEyebrow); ?></span>
            <h1><?php echo str_htmlesc($railHeading); ?></h1>
            <?php if (!empty($abstract)): ?>
                <p class="lead"><?php echo str_htmlesc($abstract); ?></p>
            <?php endif; ?>
        </header>
