<?php
/**
 * @title    Plugin SDK
 * @section  docs
 * @type     docs
 * @group    Extend
 * @position 20
 * @abstract The hook registry, command and task-type registries and on-disk
 *           cache that plugins share with the Kirigami engine.
 */
?>

<markdown>
  ## Overview

  `@kirigami/sdk` is the runtime shared by the engine and every plugin: an
  in-memory registry of **hooks**, **commands** and **task types**, plus a
  small persistent **cache**. The engine fires named hooks during a build; a
  plugin subscribes with `on(hookName, fn)` and never needs to know the
  engine's internals.

  ```bash
  npm install @kirigami/sdk
  ```

  Core and plugins must resolve the *same* installed SDK module, since they
  share its registry. From SDK 0.3.0 on, two copies of the package (a plugin
  that pins another version than the engine) see the same hooks, commands
  and task types. Needs `@kirigami/kirigami` 3.0.0 for the 0.3 features. To
  write a whole plugin, start with [Writing a plugin](../../plugins/authoring/).

  ## A plugin in one function

  ```js
  import { on, HOOKS } from '@kirigami/sdk';
  import path from 'node:path';
  import { fileURLToPath } from 'node:url';

  const pluginDir = path.dirname(fileURLToPath(import.meta.url));

  export default function register() {
      on(HOOKS.SASS_BEFORE, () => path.join(pluginDir, 'styles/before.scss'));
      on(HOOKS.SASS_AFTER,  () => path.join(pluginDir, 'styles/after.scss'));

      // Client-side script, bundled into the first esbuild entry.
      on(HOOKS.ESBUILD_AFTER, () => path.join(pluginDir, 'client/init.js'));

      // Rewrite the rendered HTML of every page (a waterfall: return the new string).
      on(HOOKS.PREPROS_HTML, (html, { file }) => html.replaceAll('<table>', '<table class="striped">'));
  }
  ```

  Register everything **inside** the default registration function: a
  reload resets the shared registry, then calls it again. A listener may
  return a single value, an array (flattened into the result), or
  `null`/`undefined` to contribute nothing this run; it can be `async`, and
  each one is awaited before the next.

  ## Available hooks

  The Sass and esbuild hooks receive one `hookContext`, shaped
  `{ __root, task, exportPath, config }`.

  | Hook | Task | Fired with | Expected return |
  |---|---|---|---|
  | `SASS_BEFORE` | sass | `hookContext` | `.scss` path(s), compiled before the entry |
  | `SASS_AFTER` | sass | `hookContext` | `.scss` path(s), compiled after the entry |
  | `SASS_FUNCTIONS` | sass | `hookContext` | object(s) `{ 'signature($arg)': (args) => SassValue }`, as in the Sass API's `functions` option |
  | `ESBUILD_BEFORE` | esbuild | `hookContext` | `.js` / `.ts` path(s), bundled as side-effect imports before the entry |
  | `ESBUILD_AFTER` | esbuild | `hookContext` | `.js` / `.ts` path(s), bundled after the entry |
  | `ESBUILD_PLUGINS` | esbuild | `hookContext` | esbuild plugin object(s), as in the API's `plugins` option |
  | `PREPROS_HTML` | prepros | `(html, { file, abs, exportPath, config })` | the modified HTML, or `null` to leave it alone. A **waterfall** hook. |
  | `PREPROS_PHP` | prepros | `({ __root, config })` | absolute path(s) of `.php` files `include_once`d before any page renders, to register tags and hooks from PHP |
  | `SCRIPTS_REGISTER` | engine | `({ config })` | object(s) `{ name, file, trigger?, mount? }`: a runnable PHP script |
  | `TASKS_REGISTER` | engine | `({ config })` | object(s) shaped like a `tasks:` entry (`{ name, type, … }`) |
  | `COMMANDS_REGISTER` | engine | `({ config })` | object(s) `{ name, description?, run }` |

  A few rules worth knowing:

  - Prefer **absolute** paths resolved from the plugin itself. A relative
    path would resolve from the working directory of the project, not the
    plugin. esbuild files are bundled as bare imports, so the order is kept:
    before, entry, after.
  - `*_BEFORE` / `*_AFTER` fire for the **first** sass task and the **first**
    esbuild task only, so a site with several stylesheets doesn't get the
    plugin's files repeated in each. `SASS_FUNCTIONS` and `ESBUILD_PLUGINS`
    fire for every task. If a Sass function collides with a native one
    (`inline-file`, `img-asset`, `colors`, `font-*`), the native one wins.
  - `PREPROS_HTML` fires once per rendered `.html` file. When no plugin
    listens, the engine doesn't even read the files back.
  - **Scripts**: `name` is what `kiri run <name>` or `Project#run(name)`
    calls; `file` is an absolute path to the plugin's own `.php` (inside the
    project or an active plugin's package, so linked and workspace plugins
    work); `trigger` is `'before-build'`, `'before-export'` or
    `'after-export'`; `mount` is an optional list of globs to mount first. A
    project's own `scripts/<name>.php` always wins over a plugin's.
  - **Tasks**: collected once after the plugins load and appended to the
    project's `tasks:`, so they follow the same validation, build, export and
    watch path. The `type` is usually one the plugin registers itself.
    Names must be unique.
  - **Commands**: routed through `registerCommand()`, so a duplicate name or
    a non-function `run` throws either way.

  ## Registries and functions

  | Function | What it does |
  |---|---|
  | `on(hook, fn)` | Subscribe. Returns the unsubscribe function. The same function object is registered once per hook. |
  | `off(hook, fn)` | Unsubscribe. |
  | `run(hook, ...args)` | Run every listener in order and flatten the results one level into an array. A throw or rejection stops dispatch and rejects `run()`. The engine calls it; a plugin rarely does. |
  | `runWaterfall(hook, value, ...args)` | Pipe `value` through each listener; a `null` / `undefined` return keeps the previous value. |
  | `has(hook)` | `true` when a hook has at least one listener. |
  | `reset(hook?)` | Remove the listeners of one hook, or all. Commands are cleared by `resetCommands()`. The engine owns this on reload. |
  | `HOOKS` | The frozen list of known hook names. Prefer it over hand-typed strings. |

  Don't mutate registrations during a dispatch: listeners are iterated from
  a live `Set`.

  ### Commands

  `registerCommand(name, { description, run })` adds a `kiri <name>`
  subcommand. `run(args, project)` receives the raw arguments and the loaded
  project. `getCommand(name)`, `listCommands()` and `resetCommands(name?)`
  complete the registry. Declare `kirigami.type: "command"` in the plugin's
  manifest, or return the entries from `COMMANDS_REGISTER` instead.

  ### Task types

  ```js
  import { registerTaskType } from '@kirigami/sdk';

  export default function register() {
      registerTaskType('manifest', {
          taskname: 'Generate manifest',
          canbuild: true,
          canwatch: false,
          validate(root, task) {
              if (!task.output) throw new Error('manifest tasks require output');
          },
          async run(root, task, exportPath) {
              // Generate task.output, then return the standard task result.
              return { success: true, files: [task.output] };
          },
      });
  }
  ```

  `run(root, task, exportPath?)` is required. `taskname` defaults to the
  registered name; `canbuild` and `canwatch` default to `false`. `validate`
  is optional and may be async. A watchable type sets `canwatch: true` and
  provides `getWatcher(root, task)`, returning the watch engine's rule
  shape. Built-in names take precedence and can't be overridden.
  `getTaskType()`, `listTaskTypes()` and `resetTaskTypes()` complete the
  registry.

  ## Cache

  A persistent key/value cache on disk, backed by `node:sqlite`: built into
  Node, no native compilation. The engine uses it for its own data
  (representative colors, font metadata); a plugin can keep its own file.

  ```js
  import { Cache } from '@kirigami/sdk';
  import path from 'node:path';

  const cache = new Cache(path.join(process.cwd(), '.my-plugin.db'));

  cache.set('meta_home', { hello: 'world' }, 3600); // 1 h TTL, in seconds (0 = never)
  cache.get('meta_home');   // { hello: 'world' }, or null if missing or expired
  cache.del('meta_home');
  cache.purge();            // drop the expired entries
  cache.purge('meta_*');    // drop every "meta_" entry, expired or not
  cache.close();
  ```

  | Method | Returns | Description |
  |---|---|---|
  | `get(key)` | value or `null` | The stored value; `null` when missing or expired. |
  | `set(key, val, ttl = 0)` | `boolean` | Store `val` as JSON. `ttl` in whole seconds, `0` for no expiry. |
  | `del(key)` | `boolean` | Delete one entry; whether it existed. |
  | `purge(mask?)` | `number` | Without a mask, delete expired entries; with a glob mask, every matching entry. Returns the rows deleted. |
  | `close()` | `void` | Close the connection (reopened lazily). |

  - **Key naming**: every key starts with a namespace, one or more `[a-z]`
    characters followed by `_` (`colors_`, `meta_`, …); anything else
    throws. The namespace is what `purge('meta_*')` targets (`*` is the only
    wildcard).
  - **Values** round-trip through JSON: dates, class instances and buffers
    lose their type, and circular values or BigInt throw.
  - `new Cache()` with no argument uses `.node.db` in the working directory,
    the file the engine itself uses. Pass a path to keep a plugin's cache
    separate, and create its parent directory yourself. Calls are
    synchronous; SQLite errors propagate. Reads don't delete expired rows:
    `purge()` does.

  The shipped `index.d.ts` covers hooks, `reset`, `Cache` and the command
  registry. Requires Node 24+ and ESM.
</markdown>
