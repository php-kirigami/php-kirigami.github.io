<?php
/**
 * @title    Tutorial · Setup
 * @section  start
 * @type     guide
 * @abstract Scaffold the project and get kiri serving it.
 */
?>

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
            <h3>Start the dev server</h3>
            <div class="prose">
                <markdown>
                ```bash
                npx kiri serve
                ```

                Leave this running. It rebuilds pages, styles and scripts on
                every save, serves `src/` at `http://127.0.0.1:4321`, and
                reloads the open tab once a rebuild finishes — a `sass`-only
                change even hot-swaps the stylesheet without a full reload.
                Open that URL and leave it be for the rest of this tutorial.
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
        > `kiri serve`'s reload is Server-Sent Events, not a live-reload
        > framework or a WebSocket library — `node:http` and `node:fs` are
        > the whole dependency list. The next part turns the single starter
        > page into the three real pages Studio Plié needs.
        </markdown>
    </div>
</section>
