<?php
/**
 * @title    Install
 * @section  start
 * @type     guide
 * @abstract One dependency, no PHP install, no server.
 */
?>

<section class="section wrap">
    <div class="prose">
        <markdown>
        ## Requirements

        - **Node.js ≥ 24.0.0**
        - **npm ≥ 10.2.3**

        That's the whole list. No PHP to install, no database, no headless browser.

        ## Install the CLI

        Add it as a dev dependency to your project:

        ```bash
        npm install -D @kirigami/cli
        ```

        The `kiri` command is now available through `npx`, or as an npm script.
        It brings the engine, `@kirigami/kirigami`, along with it.

        Prefer your editor? The [VS Code extension](../../docs/vscode/) runs
        the same commands from the Command Palette, and its **Create
        Project** command scaffolds a new site without a terminal.

        ## Verify it

        ```bash
        npx kiri --version
        ```

        Prints the `kiri` version alongside the bundled PHP version — the WASM
        runtime ships with the CLI, so this also confirms PHP itself is there.

        > [!TIP]
        > Every command has its own `--help`, with the full flag list and a
        > few usage notes.
        </markdown>
    </div>
</section>

<hr class="fold">
