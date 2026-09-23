<?php
/**
 * prepros.types.doc.before — the page head shared by content pages (@type doc):
 * breadcrumb, eyebrow (from @section, or @eyebrow), @title and @abstract.
 * The page itself only writes its sections.
 */
echo kirigami_doc_head($eyebrow ?? kirigami_eyebrow($section ?? ''), $title, $abstract ?? '', $title, $relroot);
