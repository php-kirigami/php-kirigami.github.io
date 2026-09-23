<?php
/**
 * prepros.types.guide.after — prev/next links along kirigami_guide_steps().
 */
$guideSteps = kirigami_guide_steps();
$guidePaths = array_keys($guideSteps);
$guideAt    = array_search(ltrim($absurl, '/'), $guidePaths, true);
if ($guideAt === false) return;
$guidePrev = $guidePaths[$guideAt - 1] ?? null;
$guideNext = $guidePaths[$guideAt + 1] ?? null;
?>
<div class="wrap">
    <nav class="tutorial-nav">
        <span class="tutorial-nav__step"><?php echo str_htmlesc($guideSteps[$guidePaths[$guideAt]][1] ?? ''); ?></span>
        <?php if ($guidePrev !== null): ?>
            <a rel="prev" href="<?php echo $relroot . $guidePrev; ?>"><?php echo str_htmlesc($guideSteps[$guidePrev][0]); ?></a>
        <?php endif; ?>
        <?php if ($guideNext !== null): ?>
            <a rel="next" href="<?php echo $relroot . $guideNext; ?>"><?php echo str_htmlesc($guideSteps[$guideNext][0]); ?></a>
        <?php endif; ?>
    </nav>
</div>
