<?php
/**
 * prepros.types.docs.after / prepros.types.guide.after — closes rail.before.php:
 * previous / next links, then the <!--toc--> marker (see functions.php).
 */
?>
        <?php echo kirigami_rail_pager($railSection, $absurl, $relroot); ?>
    </article>
    <!--toc-->
</div>
