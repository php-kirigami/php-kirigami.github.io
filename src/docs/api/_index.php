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

  The API is ESM, needs Node 24+ and ships TypeScript declarations for its
  public entry point. Everything below is exported from the package root.
  For how the pieces fit together, see [Internals](../internals/).

  ## Load and build

  ```js
  import { load } from '@kirigami/kirigami';

  // Run this process from the directory that holds kirigami.yaml.
  const project = await load();

  const result = await project.build();
  if (!result.success) throw new Error(JSON.stringify(result));
  ```

  `load()` takes no directory argument: launch Node from the folder that
  holds `kirigami.yaml`. `Kirigami.load()` is an equivalent factory, and
  `new Project()` constructs an unloaded project whose operational methods
  load lazily.

  A resolved promise is not necessarily a successful build: check
  `success`, which is `false` when a task failed, and read the task
  `results` for the diagnostics.

  ## Lifecycle and state

  | Member | Result | Behavior |
  |---|---|---|
  | `load()` / `Kirigami.load()` | `Promise<Project>` | Construct and reload a project. |
  | `new Project()` | `Project` | Construct unloaded; operational methods load lazily. |
  | `reload()` | `Promise<Project>` | Refresh configuration and plugins, and invalidate the PHP runtime and includes. |
  | `validate()` | `Promise<true>` | Validate `kirigami.yaml` on disk; rejects on invalid input. It does not reload plugins or PHP, and does not replace a loaded configuration: call `reload()` to apply a change. |
  | `config` | object or `null` | The resolved configuration; `null` before loading. |
  | `plugins` | `{ name, version }[]` | The activated plugins; `version` may be `null`. |
  | `tasks` | array | The configured tasks, plus the implicit forced `render-all` when `prepros` is enabled. Export's synthetic copy task is not listed. |
  | `scripts` | `{ name, mount, trigger }[]` | The sorted `scripts/*.php` files with their optional configuration (`trigger` defaults to `null`), plus any plugin-registered script not shadowed by a project file of the same name. |

  Getters do not return immutable snapshots: treat their values as
  read-only. Call `reload()` after editing the configuration, and restart
  the process after changing a plugin's JavaScript (Node caches modules).

  ## Build, export, tasks and scripts

  | Method | Options | Returns |
  |---|---|---|
  | `build()` | none | `{ success, trigger, results }` |
  | `export({ path }?)` | Optional destination override; otherwise `export.path`, or `dist` | `{ success, dist, beforeExport, beforeBuild, afterExport, results, error? }` |
  | `runTask(name)` | An exact name from `tasks` | The task result, with `task`, `type`, `taskname`; an unknown name returns `{ success: false, error }` |
  | `run(command, argv = [])` | A script basename without `.php`, and an array of arguments | The script result, including a `files` array |

  - The build `trigger` is `{ success, results }` for `before-build`.
    Export trigger fields are `null` for a stage that has not run.
  - A task result normally has `success` and may add `files`, `error`,
    `warnings`, `stderr` or `debug`. The payload varies by task: don't
    assume an optional field exists.
  - `runTask()` forces the named task and bypasses build triggers.
  - `run()` executes `scripts/<command>.php`, including a script with no
    YAML entry (an entry only adds mount and trigger metadata). A missing
    script throws.
  - Export resolves its destination relative to the working directory, not
    `kirigami.root`. Source and destination must be separate trees,
    including through symlinks. The destination is cleared, and nothing is
    rolled back after a failure.

  These methods run project code: they are not a sandbox for untrusted
  projects.

  ## Errors and sequencing

  Check both rejected promises and structured failures:

  ```js
  import { load } from '@kirigami/kirigami';

  try {
      const project = await load();
      const result = await project.build();
      if (!result.success) {
          console.error(JSON.stringify(result, null, 2));
          process.exitCode = 1;
      }
  } catch (error) {
      // Some existing paths throw strings rather than Error instances.
      console.error(error instanceof Error ? error.message : String(error));
      process.exitCode = 1;
  }
  ```

  - A successful result may carry warnings. A failed one can contain files
    produced before the failure.
  - Don't reduce a failure to `result.error`: build failures often live in
    `results` or in the trigger results.
  - Core methods never call `process.exit()`, but the tasks underneath may
    log to the console.
  - **Await operations one after another.** There is no whole-project
    queue: `Promise.all([project.build(), project.reload()])` is
    unsupported. The queue inside PHP-prepros only protects PHP work.

  ## Watching and serving

  ```js
  import { load } from '@kirigami/kirigami';

  const project = await load();
  const server = await project.serve({
      port: 0,
      onBuildResult: (event) => {
          if (event.status === 'done' && event.success === false) {
              console.error(event.error ?? event);
          }
      },
  });
  console.log(`Preview at ${server.url}`);
  // The embedding application keeps `server` and awaits server.close()
  // when the session ends.
  ```

  | Method | Options | Returns |
  |---|---|---|
  | `watch(opts?)` | `initialBuild = true` | `Promise<{ close() }>`, once the watcher is ready |
  | `serve(opts?)` | `port = 4321`, `host = '127.0.0.1'`, `onBuildResult`, `initialBuild = true` | `Promise<{ address, port, url, close() }>`, once the server and watcher are up |

  - Both await a complete initial build before allocating any resource.
    Pass `initialBuild: false` if the output is already current. A failed
    initial build rejects with the diagnostics in `error.result`.
  - `port: 0` asks for a free port; read the real URL from the handle.
  - Always await `close()` when your application stops. There is no general
    `Project.dispose()`.
  - `onBuildResult` is awaited. Its events carry `status: 'start' | 'done'`,
    `rule` and `type`; the initial build has `initial: true` and
    `rule: "initial-build"`, and the `done` event holds the full build
    result. Don't assume `success` is always present, keep the observer
    light, and handle its own errors: an exception can fail a rebuild
    notification, and a rejection during startup rejects the startup.
  - Watch rules are fixed when a handle starts: after changing the task or
    plugin configuration, `reload()` and create a new handle.

  ## Project scaffolding

  Creating a project from an official template needs no loaded project and
  no `kirigami.yaml`. The functions are exported from the root and from
  `@kirigami/kirigami/create`, which skips loading the engine (no PHP
  runtime). `kiri create`, the VS Code *Create Project* command and the MCP
  `kirigami_create_project` tool all call them; none of them prompts,
  prints or exits.

  ```js
  import { listTemplates, createProject, installDependencies } from '@kirigami/kirigami/create';

  const templates = await listTemplates();   // [{ template: 'default', description, … }]
  const result = await createProject({
      template: 'default',                   // a name, "template-default", or a listTemplates() entry
      target: 'my-site',                     // created if missing
      meta: { name: 'My Site', baseurl: 'https://me.github.io' },
      git: true,
  });
  if (!result.success) throw new Error(result.error);
  await installDependencies(result.target);  // { success, code, error? }
  ```

  | Function | Result | Behavior |
  |---|---|---|
  | `listTemplates({ refresh })` | `Promise<TemplateInfo[]>` | The `php-kirigami/template-*` repositories, sorted, each with its short `template` name. Cached for one hour; rejects when GitHub is unreachable and nothing is cached. |
  | `findTemplate(name)` | entry or `null` | Accepts `blog` or `template-blog`. |
  | `inspectTarget(dir)` | `{ target, exists, hasPackageJson, hasConfig, entries }` | What the target already holds. |
  | `gitUserConfig()` | `{ name, email }` | Author defaults from git (empty strings when unset). |
  | `resolveMeta(dir, meta)` | metadata with `slug` | Defaults: the name from the directory, the repo from a `*.github.io` base URL. |
  | `canInitGit(dir)` | `{ ok }` or `{ ok: false, reason }` | `reason` is `git-missing` or `inside-worktree`. |
  | `createProject(options)` | `{ success: true, … }` or `{ success: false, error }` | Downloads and extracts the template, writes the metadata and the starter tooling files when missing, then optionally runs `git init` and a first commit. |
  | `installDependencies(dir, { stdio })` | `{ success, code, error? }` | Runs `npm install` without a shell. A caller bound to stdout, such as an MCP server, must keep npm off it, for example `["ignore", 2, 2]`. |

  `createProject` options are `template`, `target` (default: the working
  directory), `meta` (`name`, `description`, `author`, `email`, `baseurl`,
  `repo`; an empty field keeps the template's value), `git` (default
  `true`), `cliVersion` (the `@kirigami/cli` caret range written to a
  starter `package.json`) and `onProgress({ step: 'download', url })`.

  Extraction never overwrites: existing files are kept, and an existing
  `package.json` is deep-merged with its own values winning. Caches and the
  lockfile are never copied. A failed git commit is reported in the result's
  `git` field and does not fail the creation. GitHub is queried anonymously
  (60 requests an hour per IP) unless `GITHUB_TOKEN` or `GH_TOKEN` is set;
  the token is only ever sent to `api.github.com`.

  ## Extending the engine from a plugin

  A plugin, through [`@kirigami/sdk`](../sdk/), can add to what `Project`
  exposes, and everything it adds goes through the same validation, build,
  export, `runTask()` and watch paths as built-in features:

  - **Task types**: register a new `type` usable in `tasks:`. A configured
    type with no active registration is rejected.
  - **Tasks**: the `tasks:register` hook injects ready-made entries, shaped
    like a `tasks:` entry. They are merged once after the plugins load, so a
    project needs no `tasks:` line of its own. Names must stay unique across
    the merged list.
  - **Scripts**: the `scripts:register` hook registers a PHP file under a
    name that `run()` and `kiri run` can call, with an optional `trigger`.
    A project's own `scripts/<name>.php` always wins.

  ## Limits

  - One project per process, and the working directory must be the
    project folder **before** the engine is imported: the configuration
    modules capture `process.cwd()` when they are evaluated.
  - Run project operations one after another; don't start a build while
    another one is running.
  - Plugin JavaScript is cached by Node: restart the process after changing
    a plugin's code.

  ## Related references

  | Need | Where |
  |---|---|
  | Hooks, waterfalls, commands, the on-disk cache | [Plugin SDK](../sdk/) |
  | Direct PHP rendering and the PHP classes | [PHP class library](../php/) |
  | CLI flags and scaffolding | [CLI](../cli/) |
  | MCP tools | [MCP server](../mcp/) |
  | Execution order and state ownership | [Internals](../internals/) |
</markdown>
