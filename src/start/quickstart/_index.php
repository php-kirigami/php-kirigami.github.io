<?php
/**
 * @title    Quickstart
 * @section  start
 * @abstract One page, one config file, two commands. Five minutes.
 */
?>

<section class="section wrap doc-head">
    <span class="eyebrow">Getting started</span>
    <h1><?php echo str_htmlesc($title); ?></h1>
    <p class="lead"><?php echo str_htmlesc($abstract); ?></p>
</section>

<section class="section wrap">
    <ol class="steps">
        <li>
            <h3>Install</h3>
            <div class="prose">
                <markdown>
                ```bash
                npm install -D @kirigami/kirigami
                ```
                </markdown>
            </div>
        </li>
        <li>
            <h3>Add a <code>kirigami.yaml</code></h3>
            <div class="prose">
                <markdown>
                At the root of your project:

                ```yaml
                kirigami:
                  project:  My Site
                  baseurl:  https://example.com
                  root:     src

                prepros: {}
                ```

                `prepros: {}` — even empty — is what turns on the PHP → HTML
                compiler for every `_*.php` file under `root`.
                </markdown>
            </div>
        </li>
        <li>
            <h3>Write a page</h3>
            <div class="prose">
                <markdown>
                `src/_index.php`:

                ```php
                /**
                 * @title Home
                 */

                echo '<h1>Hello, Kirigami.</h1>';
                ```

                A file named `_name.php` compiles to `name.html` in the same
                folder — `_index.php` becomes `index.html`.
                </markdown>
            </div>
        </li>
        <li>
            <h3>Serve it</h3>
            <div class="prose">
                <markdown>
                ```bash
                npx kiri serve
                ```

                Rebuilds `src/index.html` every time you save, serves `src/`
                at `http://127.0.0.1:4321`, and reloads the open tab once the
                rebuild finishes — nothing to open by hand. (`kiri watch`
                does the same rebuild with no server, if that's all you
                need.)
                </markdown>
            </div>
        </li>
        <li>
            <h3>Ship it</h3>
            <div class="prose">
                <markdown>
                ```bash
                npx kiri export
                ```

                Writes a fully static, dependency-free site into `dist/` —
                ready for GitHub Pages or any static host.
                </markdown>
            </div>
        </li>
    </ol>
</section>

<hr class="fold">

<section class="section wrap">
    <div class="prose">
        <p>
            That's the whole loop. For a site with more than one page, real
            content, styling and a deploy workflow, the
            <a href="<?php echo $relroot; ?>start/tutorial/">full tutorial</a>
            builds one from scratch, one concept at a time.
        </p>
    </div>
</section>

<div class="wrap">
    <nav class="tutorial-nav">
        <span class="tutorial-nav__step">Getting started</span>
        <a rel="prev" href="<?php echo $relroot; ?>start/install/">Install</a>
        <a rel="next" href="<?php echo $relroot; ?>start/tutorial/">Full tutorial</a>
    </nav>
</div>
