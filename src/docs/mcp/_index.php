<?php
/**
 * @title    MCP server
 * @section  docs
 * @type     docs
 * @group    Tools
 * @position 12
 * @abstract Let an AI assistant build, validate, export and scaffold a
 *           Kirigami project through the Model Context Protocol.
 */
?>

<markdown>
  ## Overview

  `@kirigami/mcp` puts the Kirigami engine behind a
  [Model Context Protocol](https://modelcontextprotocol.io) server. An AI
  assistant that speaks MCP (Claude Code, Claude Desktop and others) can
  then build, export, validate and run scripts in your project the way the
  terminal does, and read structured results instead of parsing console
  output.

  The server talks over stdio and works on the directory it is started in,
  exactly like `kiri`.

  ## Setup

  With the [CLI](../cli/) installed in the project, no separate install is
  needed: point your MCP client at `kiri mcp`, started from the project
  folder.

  ```bash
  npx kiri mcp
  ```

  Or run the package directly from the client's configuration:

  ```json
  {
    "mcpServers": {
      "kirigami": {
        "command": "npx",
        "args": ["-y", "@kirigami/mcp"],
        "cwd": "/path/to/your/kirigami/project"
      }
    }
  }
  ```

  The working-directory key depends on the client; what matters is that
  the server starts in the folder that holds `kirigami.yaml`. It also
  starts in an empty folder, where the assistant can scaffold a new
  project first.

  ## Tools

  | Tool | What it does |
  |---|---|
  | `kirigami_about` | A compact manifest of the project (package metadata, docs, tasks and scripts), to orient the assistant. |
  | `kirigami_config` | The resolved `kirigami.yaml` and the active plugins. |
  | `kirigami_validate` | Validate `kirigami.yaml` against the schema. |
  | `kirigami_build` | Same as `kiri build`. |
  | `kirigami_export` | Same as `kiri export`, with an optional output path. |
  | `kirigami_list_tasks` / `kirigami_run_task` | List the build tasks, run exactly one. |
  | `kirigami_list_scripts` / `kirigami_run` | List `scripts/*.php`, run one with arguments. |
  | `kirigami_list_templates` / `kirigami_create_project` | List the official templates, scaffold a project from one. Never overwrites files. |
  | `kirigami_site_blueprint` | Suggest a site tree, configuration and an example page. |
  | `kirigami_search_docs` / `kirigami_doc_hints` | Search the project's documentation, or suggest the pages for a topic. |

  Results are JSON: an operation reports `success: false` in its payload
  rather than as a protocol error, so the assistant sees the diagnostics.

  There is no `serve` or `watch` tool: a long-running server doesn't fit a
  request/response protocol. Run [`kiri serve`](../cli/) or the
  [VS Code extension](../vscode/) for a live preview.

  ## Trust

  `kirigami_run` and `kirigami_run_task` execute whatever the project's
  scripts and tasks do. Point the server only at projects you trust.
</markdown>
