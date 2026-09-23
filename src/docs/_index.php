<?php
/**
 * @title    Documentation
 * @nav      Overview
 * @section  docs
 * @type     docs
 * @abstract The reference for building a site with Kirigami, and for the four
 *           ways to drive it: the VS Code extension, the command line, the
 *           MCP server and the JavaScript API.
 */
?>

<markdown>
  New to Kirigami? The [Getting started](../start/) path walks you from an
  empty folder to a deployed site. These pages are the reference: precise
  and complete, one topic per page, with the full detail the tutorial links
  back to.

  ## Build a site

  - [Writing pages](authoring/): the PHPDOC header, data files, page types,
    built-in tags, and registering your own tags and hooks.
  - [Configuration](config/): every key of `kirigami.yaml`.
  - [PHP class library](php/): Markdown, YAML, images, caching, SEO and the
    other classes every page can call.

  ## Tools

  The same engine behind four front doors. Pick the one that fits how you
  work; they build the same site.

  - [CLI](cli/): `kiri build`, `serve`, `export` and the rest, from any
    terminal.
  - [VS Code extension](vscode/): build, export and preview from the
    editor's Command Palette and status bar.
  - [MCP server](mcp/): let an AI assistant build, validate and scaffold a
    project through the Model Context Protocol.
  - [JavaScript API](api/): load a project in your own Node code and call
    `build()`, `export()` or `serve()`.

  Every code block on this site is colored at build time by
  [`@kirigami/plugin-highlight`](../plugins/): no highlighter reaches your
  browser.
</markdown>
