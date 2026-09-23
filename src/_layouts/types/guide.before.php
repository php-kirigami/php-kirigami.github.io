<?php
/**
 * prepros.types.guide.before — a page of the "Getting started" path
 * (@type guide). Same head as `doc`; tutorial parts get their heading and
 * "Tutorial · Part N of 7" eyebrow from kirigami_guide_steps().
 */
[, , $guideHeading, $guideEyebrow] = kirigami_guide_steps()[ltrim($absurl, '/')] ?? [null, null, null, null];
echo kirigami_doc_head(
    $guideEyebrow ?? $eyebrow ?? kirigami_eyebrow($section ?? ''),
    $guideHeading ?? $title,
    $abstract ?? '',
    $title,
    $relroot
);
