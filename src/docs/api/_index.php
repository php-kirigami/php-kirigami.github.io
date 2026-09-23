<?php
/**
 * @title    JavaScript API
 * @section  docs
 * @type     docs
 * @group    Tools
 * @position 13
 * @abstract Load a Kirigami project in your own Node code and build, export,
 *           serve or run it: the engine behind the CLI, the VS Code
 *           extension and the MCP server.
 */
?>

<markdown>
  ## Overview

  `@kirigami/kirigami` is the engine. The [CLI](../cli/), the
  [VS Code extension](../vscode/) and the [MCP server](../mcp/) are thin
  front ends over its `Project` API, and your own tools can use it the same
  way: a build step in another pipeline, a custom dev server, a test that
  renders a fixture site.

  ```bash
  npm install --save-dev @kirigami/kirigami
  ```

  ## Load and build

  ```js
  import { load } from '@kirigami/kirigami';

  // Run this process from the directory that holds kirigami.yaml.
  const project = await load();

  const result = await project.build();
  if (!result.success) throw new Error(JSON.stringify(result));
  ```

  A resolved promise is not necessarily a successful build: check
  `success`, which is `false` when a task failed, and read the task
  `results` for the diagnostics.

  ## The Project API

  | Member | What it does |
  |---|---|
  | `build()` | Run the `before-build` trigger, the page rendering and every buildable task. Returns `{ success, trigger, results }`. |
  | `export({ path }?)` | Production build into `export.path` (or `path`). Returns `success`, `dist` and the trigger and task results. |
  | `serve({ port, host, onBuildResult }?)` | Build once, then watch and serve. Returns `{ address, port, url, close() }`. Port `0` picks a free port. |
  | `watch()` | Build once, then rebuild on change. Returns `{ close() }`. |
  | `run(name, argv?)` | Run `scripts/<name>.php` and return its result. |
  | `runTask(name)` | Run one task by name, without triggers or other tasks. |
  | `validate()` | Re-read and validate `kirigami.yaml`. |
  | `reload()` | Reload the configuration and plugins. |
  | `config`, `plugins`, `tasks`, `scripts` | The resolved configuration, active plugins, and the discovered tasks and scripts. |

  ## A dev server in a few lines

  ```js
  import { load } from '@kirigami/kirigami';

  const project = await load();
  const server = await project.serve({
      port: 0,
      onBuildResult: (event) => {
          if (event.status === 'done') console.log(event.success ? 'rebuilt' : 'build failed');
      },
  });
  console.log(`Preview at ${server.url}`);
  // later: await server.close();
  ```

  ## Scaffolding

  Project creation, the code behind `kiri create`, needs no loaded project:
  import `listTemplates()` and `createProject()` from
  `@kirigami/kirigami/create`.

  ## Limits

  - One project per process, and the working directory must be the
    project folder **before** the engine is imported.
  - Run project operations one after another; don't start a build while
    another one is running.
  - Plugin JavaScript is cached by Node: restart the process after changing
    a plugin's code.
</markdown>
