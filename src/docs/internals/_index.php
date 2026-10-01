<?php
/**
 * @title    Internals
 * @section  docs
 * @type     docs
 * @group    Extend
 * @position 21
 * @abstract How a build actually runs: who owns what, in which order tasks
 *           execute, where the PHP boundary sits, and how watching works.
 */
?>

<markdown>
  ## Entry points and ownership

  Everything goes through one object, the `Project` of
  [`@kirigami/kirigami`](../api/):

  | Front end | What it adds |
  |---|---|
  | `kiri` CLI | Terminal presentation: output, exit codes, prompts. |
  | MCP server | Translates results into text content for an assistant. |
  | VS Code extension | Stages the runtime and starts a **separate Node process**, whose working directory is the site, before importing the engine. |
  | Your code | Calls `Project` directly. |

  Behind `Project` sit the configuration and plugin registration, the task
  modules, the PHP-prepros bridge (which owns a PHP-WASM runtime), Sass and
  esbuild, and the watch scheduler with its HTTP server.

  The configuration and script modules capture `process.cwd()` when they are
  evaluated, and plugin hooks and commands use shared registries. Several
  `Project` objects therefore do **not** give independent projects: use one
  process per site, set its working directory before importing the engine,
  and await operations one after another.

  ## Configuration and reload

  `kirigami.yaml` is read through [struct-walker](../struct-walker/), which
  resolves nested file references. The result is validated against the
  bundled JSON schema, then checked imperatively: paths are resolved, files
  verified and defaults supplied.

  `Project.reload()` runs these steps in order:

  1. Mark the project unloaded and drop its configuration and plugin list.
  2. Clear the configuration cache.
  3. Wait for the PHP-prepros runtime reset, then clear the collected plugin
     PHP includes.
  4. Read the configuration and validate its schema, built-in tasks and paths.
  5. Re-register the active plugins, including their task types, then
     collect every `commands:register` result.
  6. Collect every `tasks:register` result and append it to `config.tasks`.
  7. Strictly validate every task (the project's and plugins' alike), and
     mark the project loaded.

  Plugins are resolved from the project first, then from the engine's own
  installation. Their package metadata and option schemas are checked before
  `register(options, { config, name })` is awaited. A reload resets the
  shared hooks, commands and task types, but **Node's module cache stays**:
  restart the process after editing plugin code. `validate()` only refreshes
  the configuration, and an existing watcher keeps its original rules.

  ## Build and export ordering

  | Operation | Ordered work |
  |---|---|
  | `build()` | `before-build` scripts; the forced implicit `render-all` when `prepros` is set; the configured tasks |
  | `export()` | Check that source and destination are separate; `before-export`; `before-build`; implicit rendering; the forced `copy-files`; the configured tasks; `after-export` |
  | `runTask(name)` | Find one task and force it. No triggers, no other tasks. |

  - Built-in tasks live in `bin/tasks/<type>.js`; plugins add types through
    the [SDK](../sdk/). The first parse validates built-ins and defers
    unknown types; a strict pass after the plugins load rejects any type
    still unresolved.
  - Build and export skip a task unless its definition has `canbuild` or the
    task sets `force`. Watch eligibility is separate: `canwatch` and
    `getWatcher`.
  - A trigger runs its scripts sequentially: the project's `scripts:`
    entries first, then plugin-registered scripts with the same trigger. It
    stops at the first unsuccessful result, and so does the task loop.
    Completed work is never rolled back.
  - Export is not a build into an isolated directory. The implicit PHP task
    renders **in the source tree**, runs the HTML hooks there and writes the
    sitemap, and only then does the copy task fill the destination, filtered
    of private and source files and by `export.ignore`. It refuses
    overlapping source and destination paths (lexical or canonical) and
    empties the dedicated destination. It is not transactional and does not
    restore the old output if a later step fails.

  ## The PHP boundary

  The prepros task collects the `prepros:php` hook results once per include
  cache, hands those paths to PHP-prepros, then runs `prepros:html` as a
  waterfall over each generated page. A whole-site render also runs the
  sitemap generation and merges the diagnostics and files of both.

  PHP-prepros loads its own configuration lazily and owns a disposable
  PHP-WASM instance. It mounts the framework at `/prepros` and the source
  data under `/project`, prepares the PHP configuration and loads its
  bootstrap through `auto_prepend_file`. Mounting filters file extensions:
  it is not a mirror of the host filesystem.

  - PHP work and resets share one promise queue covering mounting, execution
    and result extraction. A reset waits behind active work, disposes the
    instance and drops the cached state. This protects the PHP boundary
    only, not whole `Project` operations.
  - PHP diagnostics go to **stderr**, never into the generated HTML. A task
    result can carry warnings, stderr and debug output next to its files.
  - The renderer copies reported outputs back to the host. PHP virtual paths
    and host paths are not interchangeable.
  - PHP-WASM also offers shared cached runtimes to direct consumers. Its
    network runtime bridges WASM sockets to Node TCP through a local proxy
    and injects Node's CA roots; libcurl's `poll()` is replaced by a version
    that yields to the event loop, which is what makes HTTPS work.

  ## Watching and serving

  - The watch engine builds a local task list without touching the
    configured one. It watches the glob base directories, filters events and
    batches callbacks per rule with a default **150 ms** debounce. A failing
    callback is contained so later batches still run; closing discards the
    pending batches and awaits the active callbacks.
  - A change to a PHP structure (a page added, moved or removed) refreshes
    the mounts, removes the obsolete known outputs and rebuilds the pages
    and the sitemap. That is a scoped cleanup, not a deletion of everything
    absent from a listing.
  - `watch()` and `serve()` both await a `build()` first unless
    `initialBuild: false`; a failed initial build rejects before any server
    or watcher exists.
  - The development server serves the generated files and reloads browsers
    with Server-Sent Events: a successful Sass rule swaps the stylesheet,
    any other successful rule reloads the page, and a failed rebuild
    triggers nothing. Private and source paths (and canonical link targets)
    are checked. JavaScript and source maps stay available in development,
    which is **different from export filtering**.
  - `serve()` waits for the watcher, cleans up the HTTP server if startup
    fails, and its `close()` closes the watchers, then the server. The
    embedder owns that handle.
</markdown>
