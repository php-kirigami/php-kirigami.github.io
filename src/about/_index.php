<?php
/**
 * @title    About
 * @section  about
 * @type     doc
 * @abstract Why Kirigami exists, who maintains it, and how it's licensed.
 */
?>

<section class="section wrap">
    <div class="prose">
        <markdown>
        ## Why PHP, why WebAssembly

        Static site generators are usually JavaScript. Kirigami starts from a
        different premise: a huge number of developers already know PHP —
        includes, loops, real control flow, a standard library — and none of
        that needs a running PHP server to produce a static site. It only
        needs to run **once**, at build time.

        So Kirigami compiles a custom PHP 8.5 build to WebAssembly and runs it
        directly inside Node. No PHP install on the machine, no server
        process, no headless browser rendering pages to scrape their output —
        just PHP templates going in and flat HTML coming out, one command.
        That constraint shapes everything else: the asset pipeline (esbuild +
        Sass), the image autogenerator, the plugin system — all wired to work
        without a native dependency anywhere in the toolchain.

        ## Who's behind it

        Kirigami is maintained by **Maxime Larrivée-Roy**, as a side project —
        the whole [`php-kirigami`](https://github.com/php-kirigami) GitHub org,
        every `@kirigami/*` package on npm, this site. Issues and pull
        requests are genuinely welcome; response times will vary with a
        one-person maintainer.

        ## License

        Kirigami's packages are **GPL-3.0-or-later**, with two exceptions:
        [`@kirigami/php-wasm`](https://www.npmjs.com/package/@kirigami/php-wasm),
        the compiled PHP runtime, is **GPL-2.0-or-later**, inherited from its
        [WordPress Playground](https://github.com/WordPress/wordpress-playground)
        upstream, and `@kirigami/bestframe` is **LGPL-2.1-or-later**.

        The license covers the tools, not what you build with them: a site
        built with Kirigami is plain HTML/CSS/JS of your own, under whatever
        license you choose. This site, for instance, is MIT.

        See the full [package-by-package breakdown](<?php echo $relroot; ?>ecosystem/)
        for versions and licenses at a glance.
        </markdown>
    </div>
</section>
