    </main>

    <footer class="site-footer">
        <div class="wrap">
            <div class="site-footer__grid">
                <div class="site-footer__col">
                    <span class="footer-logo" role="img" aria-label="<?php echo str_htmlesc($project); ?>"></span>
                    <p class="site-footer__tagline"><?php echo str_htmlesc($tagline); ?></p>
                </div>
                <div class="site-footer__col">
                    <h3>Learn</h3>
                    <ul>
                        <li><a href="<?php echo $relroot; ?>start/">Getting started</a></li>
                        <li><a href="<?php echo $relroot; ?>docs/">Documentation</a></li>
                        <li><a href="<?php echo $relroot; ?>plugins/">Plugins</a></li>
                        <li><a href="<?php echo $relroot; ?>examples/">Examples</a></li>
                        <li><a href="<?php echo $relroot; ?>templates/">Templates</a></li>
                        <li><a href="<?php echo $relroot; ?>showcase/">Showcase</a></li>
                    </ul>
                </div>
                <div class="site-footer__col">
                    <h3>Kirigami</h3>
                    <ul>
                        <li><a href="<?php echo $relroot; ?>about/">About</a></li>
                        <li><a href="<?php echo $relroot; ?>ecosystem/">Ecosystem</a></li>
                        <li><a href="<?php echo $relroot; ?>changelog/">Changelog</a></li>
                        <li><a href="<?php echo $relroot; ?>roadmap/">Roadmap</a></li>
                        <li><a href="<?php echo $relroot; ?>design/">Design</a></li>
                        <li><a href="https://github.com/php-kirigami/kirigami">Source</a></li>
                        <li><a href="https://www.npmjs.com/org/kirigami">npm</a></li>
                        <li><a href="https://github.com/php-kirigami/kiribuild">kiribuild</a></li>
                    </ul>
                </div>
            </div>

            <div class="site-footer__inner">
                <p>&copy; <year> <?php echo str_htmlesc($author); ?>. This site is MIT licensed.</p>
                <p>
                    Built with <a href="https://github.com/php-kirigami/kirigami">Kirigami</a>
                    &middot; <a href="<?php echo $relroot; ?>sitemap.xml">Sitemap</a>
                    &middot; <a href="https://github.com/php-kirigami/php-kirigami.github.io">Source of this site</a>
                </p>
            </div>
        </div>
    </footer>
</body>
</html>
