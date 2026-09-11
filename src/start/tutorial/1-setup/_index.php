<?php
/**
 * @title    Tutorial · Setup
 * @section  start
 * @abstract Scaffold the project and get kiri watching it.
 */
?>

<section class="section wrap doc-head">
    <span class="eyebrow">Tutorial &middot; Part 1 of 7</span>
    <h1>Setup</h1>
    <p class="lead"><?php echo str_htmlesc($abstract); ?></p>
</section>

<section class="section wrap">
    <ol class="steps">
        <li>
            <h3>Scaffold from a template</h3>
            <div class="prose">
                <markdown>
                ```bash
                npx kiri create default studio-plie
                cd studio-plie
                ```

                `kiri create` clones the `default` template's repo, deep-merges
                its `package.json`, writes what you type into `kirigami.yaml`,
                then runs `git init` and `npm install` for you. Run
                `npx kiri create --list` any time to see the official templates.
                </markdown>
            </div>
        </li>
        <li>
            <h3>Look at what you got</h3>
            <div class="prose">
                <markdown>
                ```
                studio-plie/
                ├── kirigami.yaml
                ├── package.json
                └── src/                 ← kirigami.root
                    ├── _layouts/        ← header.php / footer.php
                    ├── _lib/            ← functions.php (tags/hooks/plugins)
                    ├── _index.php       → index.html
                    ├── styles/
                    └── scripts/
                ```

                Two naming rules explain the whole tree: a file `_name.php`
                compiles to `name.html` in the same folder (`_index.php` →
                `index.html`); a folder starting with `_` is never walked as a
                page directory — that's where layouts, includes and data live.
                </markdown>
            </div>
        </li>
        <li>
            <h3>Start watching</h3>
            <div class="prose">
                <markdown>
                ```bash
                npx kiri watch
                ```

                Leave this running. It rebuilds pages, styles and scripts on
                every save — no server, no browser refresh. Open
                `src/index.html` in a browser tab and reload it by hand, or
                point an editor live-preview extension at `src/`.
                </markdown>
            </div>
        </li>
    </ol>
</section>

<hr class="fold">

<section class="section wrap">
    <div class="prose">
        <markdown>
        > [!NOTE]
        > Nothing was compiled by a server and nothing needs to run again to
        > serve the site — `kiri watch` only regenerates files on disk. The
        > next part turns the single starter page into the three real pages
        > Studio Plié needs.
        </markdown>
    </div>
</section>

<div class="wrap">
    <nav class="tutorial-nav">
        <span class="tutorial-nav__step">Part 1 of 7</span>
        <a rel="prev" href="<?php echo $relroot; ?>start/tutorial/">Overview</a>
        <a rel="next" href="<?php echo $relroot; ?>start/tutorial/2-pages-layout/">Part 2 — Pages &amp; layout</a>
    </nav>
</div>
