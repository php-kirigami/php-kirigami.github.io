<?php
/**
 * @title    VS Code extension
 * @section  docs
 * @type     docs
 * @group    Tools
 * @position 11
 * @abstract Build, export, run scripts and preview a Kirigami site from the
 *           Command Palette and the status bar, without a terminal.
 */
?>

<markdown>
  ## Overview

  The Kirigami extension for VS Code (`php-kirigami.kirigami-vscode`) drives
  the same engine as the [CLI](../cli/). It activates in any workspace whose
  root holds a `kirigami.yaml`, and adds a **Kirigami** category to the
  Command Palette, a status bar item for the dev server, and a **Kirigami**
  Output channel laid out like the `kiri` output.

  Every official template recommends it in `.vscode/extensions.json`, so
  VS Code offers to install it when you open a new project.

  ## Commands

  | Command | What it does |
  |---|---|
  | Kirigami: Create Project… | Pick a folder, a template and the project's metadata, then scaffold it, optionally with `git init` and `npm install`. Available in any window. |
  | Kirigami: Build | One development build, like `kiri build`. |
  | Kirigami: Export | Production build into `dist/`, like `kiri export`. |
  | Kirigami: Run Script… | Pick one of the project's `scripts/*.php` and run it, like `kiri run`. |
  | Kirigami: Validate kirigami.yaml | Check the configuration against the schema. |
  | Kirigami: Toggle Dev Server | Start or stop the live-reloading dev server, like `kiri serve`. |

  The project commands appear once a Kirigami project is loaded. Results
  show as a notification; the full detail, including every rebuild the
  watcher runs, goes to the **Kirigami** Output channel.

  ## Dev server and preview

  The status bar item, bottom right, shows the dev server's state: idle,
  running (the tooltip shows its URL), building, or error when the last
  rebuild failed. Clicking it starts the server, after one initial build,
  and asks whether to open the preview in VS Code's Simple Browser or in
  your default browser. Clicking it again stops the server. Saved changes
  rebuild and reload the preview on their own.

  ## Settings

  | Setting | Default | Description |
  |---|---|---|
  | `kirigami.nodePath` | `node` | Node.js 24+ executable that runs the Kirigami engine. Set it when `node` is not on your PATH, then restart the extension. |
  | `kirigami.previewPort` | `4321` | Port of the dev server; applies the next time it starts. |

  ## Requirements

  - VS Code 1.90 or later, in a trusted workspace.
  - Node.js 24 or later with WebAssembly JSPI, installed on the machine: the
    engine runs in its own Node process, not in VS Code's.
  - One project per window: the extension binds to the first workspace
    folder.
</markdown>
