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
