<?php
/**
 * @title    Troubleshooting
 * @section  docs
 * @type     docs
 * @group    Build a site
 * @position 4
 * @abstract What to check first when a build, a preview or an export doesn't
 *           do what you expect.
 */
?>

<markdown>
  ## First checks

  Run every command from the folder that holds `kirigami.yaml`, and use
  `npx kiri <command> --help` for the options of a command. If a build
  fails, read the diagnostics before anything else: they name the file and
  the task.

  | Symptom | First check |
  |---|---|
  | `kiri` not found | Install `@kirigami/cli` in the project and run it with `npx kiri`, or from an npm script. |
  | Configuration not found | You are not in the folder that contains `kirigami.yaml`. |
  | Empty preview or a 404 | Read the initial build diagnostics, and check that the page follows the `_*.php` convention (`_index.php`, `_about.php`). |
  | Invalid wrapper path | The `prepros.before` / `prepros.after` paths are relative to `kirigami.root`. Create the referenced file. |
  | Unsafe export destination | Keep source and output in separate trees such as `src` and `dist`. They must not equal, contain or sit inside each other, even through symlinks or junctions. |
  | A plugin change is ignored | Restart the command: Node caches imported JavaScript modules. |
  | PHP extension warnings at startup | Nearby extension packages may be auto-discovered and loaded; see the PHP-WASM documentation on extension discovery. |
  | A change to tasks or plugins has no effect in `kiri serve` | Watch rules are created when the command starts. Restart it. |

  ## Pages

  - **A page doesn't appear.** Source pages are `_index.php` (for
    `folder/index.html`) or `_name.php` (for `name.html`). Edit the PHP
    source, never the generated `.html`, which is overwritten.
  - **A tag shows up as text, or a block closes early.** A tag shown as
    example text (`<markdown>`, `<img asset>`, a plugin tag) must be in a
    code span or a fenced block, not bare text and not an indented code
    block: it would run, and a bare closing `</markdown>` closes the real
    block.
  - **A PHP open tag typed as an example runs.** Describe it in prose instead
    of typing it.
  - **A variable is undefined.** Page annotations (`@title`, `@abstract`, …)
    and the keys of the `kirigami:` block become PHP variables; check the
    spelling and that the block is the page's first PHPDOC comment.

  ## Export

  - `kiri export` **replaces** the destination's contents and writes a
    `.kirigami-export` marker. An existing non-empty folder without that
    marker is refused rather than emptied: empty it yourself, or create the
    marker to confirm it may be replaced.
  - The copy leaves out PHP, Sass sources, source maps, files whose name
    starts with `_` or `.`, and non-minified JavaScript. Declare an esbuild
    task for your scripts: a raw `app.js` is not copied. Use `export.ignore`
    to leave out more.
  - A failed build or export can leave a partial output. Deploy only after
    a success, and never from a folder you also edit by hand.
  - The preview server serves JavaScript and source maps for debugging, so
    its file set is not the one export produces: judge a deployment by the
    exported folder.

  ## Embedding and scripts

  - `load()` reads the configuration from the **current** working directory:
    change directory before the import, not after.
  - Don't run two operations at once (`build()` while `reload()`); await
    them one after another.
  - A resolved promise isn't a successful build. Check `success` and read
    `results`; some failures are in the trigger results rather than in
    `error`.
  - Network access at build time (npm version lookups, link previews) needs
    `prepros.network: true`; without it, such calls return nothing and the
    page must render a fallback.

  ## Still stuck?

  Run `kiri phpinfo` to inspect the bundled PHP, `kiri cache` to clear
  stale caches, and open an issue on
  [GitHub](https://github.com/php-kirigami/kirigami/issues) with the
  command, the diagnostics and your `kirigami.yaml`. If you use an AI
  assistant, the [MCP server](../mcp/) lets it read the same structured
  results.
</markdown>
