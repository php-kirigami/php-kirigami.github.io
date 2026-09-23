<?php
/**
 * @title      Writing pages
 * @section    docs
 * @type       docs
 * @group      Build a site
 * @position   1
 * @abstract   The PHPDOC header, auto-loaded data files, built-in tags,
 *             and the hooks a plugin registers into.
 */
?>

    <markdown>
      ## Project structure

      Inside `kirigami.root`:

      - A file `_name.php` is a **page source**; it compiles to
        `name.html` in the same directory (leading `_` stripped).
        `_index.php` → `index.html`, exactly like this page.
      - A directory whose name starts with `_` (`_layouts/`, `_lib/`) is
        **skipped** during directory-wide builds — use it for partials,
        layouts, includes, data.
      - Data files (`.yaml`, `.yml`, `.json`, `.md`) are not compiled;
        they're loaded by pages via PHPDOC annotations, below.

      ## PHPDOC header

      Every page starts with a docblock. Each `@key value` becomes a PHP
      variable (`$key`), available in the page **and** in the `before` /
      `after` includes.

      ```php
      /**
       * @name     about
       * @title    About us
       * @abstract A short description of this page.
       */

      echo '<h1>' . str_htmlesc($title) . '</h1>';
      echo '<p>' . str_htmlesc($abstract) . '</p>';
      ```

      Define any keys you want — `before` / `after` typically read
      `$title`, `$description`, etc. to build `<head>` metas (though with
      the `seo:` block on, you rarely need to by hand — see the
      [config reference](../config/#seo)).

      ## Auto-loaded data files

      When an annotation value ends in `.yaml`, `.yml`, `.json`, or
      `.md` **and** resolves to a file relative to the page's own
      directory, it's parsed and injected as structured data instead of
      a plain string:

      | Extension | Becomes |
      |---|---|
      | `.yaml` / `.yml` | `stdClass` (or an array of `stdClass` for sequences), via `YAML::parseFile()` |
      | `.json` | `json_decode()` result |
      | `.md` | HTML string, via `MD::toHtml()` |

      ```php
      /**
       * @name     medias
       * @articles _articles.yaml
       */

      foreach ($articles as $a) {
          echo '<a href="' . str_htmlesc($a->url) . '">' . str_htmlesc($a->title) . '</a>';
      }
      ```

      With `prepros.network: true`, a value starting with `http://` /
      `https://` is fetched and parsed the same way — this page's own
      hub, and `/ecosystem/`, use exactly that (via `curl_get_contents()`
      + [`CACHE`](../php/#cache), not the auto-load shorthand, since they
      need a TTL).

      ## Content and indent

      - **`@content`** — if a `content` variable resolves to a
        non-empty value (typically an auto-loaded `.md` / `.yaml` /
        `.json` annotation), it's used **as-is** as the page body, and
        the PHP file is **not executed** for output. Good for pure
        data/markdown pages wrapped by a shared layout.
      - **`@indent N`** — prefixes every line of the rendered body with
        `N` spaces before `before` / `after` wrap it, keeping generated
        HTML readable inside an indented layout.

      ```php
      /**
       * @name    changelog
       * @title   Changelog
       * @content _changelog.md
       * @indent  4
       */
      ```

      ## Page types

      When several pages share the same chrome (a page head, a sidebar,
      prev/next links), put it in a **page type** instead of copying it
      into each page. Declare the type under `prepros.types`, with a
      `before` and/or `after` file:

      ```yaml
      prepros:
        before: _layouts/header.php
        after:  _layouts/footer.php
        types:
          article:
            before: _layouts/types/article.before.php
            after:  _layouts/types/article.after.php
      ```

      A page opts in with `@type`, and then only writes its own content:

      ```php
      /**
       * @title    Folding a crane
       * @abstract Thirty folds, one sheet, no cuts.
       * @type     article
       */
      ```

      The page is assembled as global `before` → type `before` → body →
      type `after` → global `after`. The type's files see the same
      variables as the page (`$title`, `$abstract`, `$relroot`, …), so
      they can print the heading, a breadcrumb or navigation built from
      them. A page has one type at most; a page without `@type`, or with
      an unknown one, gets the global wrap only.

      This documentation is an example: every page here is `@type docs`,
      whose layout draws the sidebar, the page head and the "On this page"
      box, so a docs page is just its Markdown.

      ## Variables in scope

      Injected by `PREPROS::render()`: every loose key from the
      `kirigami:` block (`$project`, `$baseurl`, `$author`, …), every
      PHPDOC annotation on the current page, plus `$relroot` (relative
      path from the page's directory back to `kirigami.root` — use it
      for any asset URL that has to work at any depth) and `$absurl`
      (the page's absolute URL path, including any subfolder in
      `baseurl` — safe as an `href` / `src` root).

      Render pipeline, per page: resolve PHPDOC + auto-load data →
      `page_info` hook → `pre_render` hook (raw source) → include
      `before` + body (or `@content`) + `after` → process registered
      tags → `post_render` hook → `HTML::format()` if `format: true` →
      write `.html`.

      ## Built-in tags

      Processed **after** PHP runs, on the assembled HTML:

      - `<markdown> … </markdown>` — converts its body from Markdown to
        HTML, stripping common leading indentation first. Every
        registered MD plugin (below) works inside it. Add `prose`
        (`<markdown prose>`) to wrap the output in `.prose`
        (`@kirigami/canva`'s prose partial) — `class` / `id` on the tag
        land on that wrapper. This whole page is one such block.
      - `<img asset="path/in/assets-images.jpg" width="450" height="300" cover>` —
        resolves through the image autogenerator, calling `IMG::asset()`
        with the same parameters (`asset` → `$path`; `width` / `height`
        optional ints; `cover` presence = `true`, crop to fill) and
        swapping `asset` for the generated `src`. Any other attribute
        (`alt`, `class`, `loading`, …) passes straight through. Empty or
        missing `asset` leaves the tag untouched.

      One authoring gotcha with `<markdown>`: nesting a *literal*
      `<markdown>` / `</markdown>` example inside a real `<markdown>`
      block breaks the tag's non-greedy pairing. Describe it in prose or
      point at "view source" instead — and never type a real PHP opening
      tag as example text, short-echo form included: PHP doesn't know
      it's sitting inside a fenced code example, and runs it for real.
      Every snippet on this page is written the way it has to be
      because of that rule — statements only, no opening tag shown.

      ## Registering tags and hooks

      Register in a `prepros.includes` file — this site's own
      [`_lib/functions.php`](https://github.com/php-kirigami/php-kirigami.github.io/blob/main/src/_lib/functions.php)
      is exactly that; it's where `kirigami_page_href()` and
      `kirigami_breadcrumb_nav()`, used across this `/docs/` section,
      live.

      ```php
      PREPROS::registerTag('gallery', function (string $tag, array $attrs, string $body): string {
          // ... return HTML
      });

      PREPROS::registerHook('post_render', function (string $html): string {
          return str_replace('{{build_date}}', date('Y-m-d'), $html);
      });
      ```

      | Hook | Fires | `$data` | Return |
      |---|---|---|---|
      | `page_info` | after PHPDOC parse, before render | `[$filePath, $pageObject]` | `$pageObject` |
      | `pre_render` | before PHP execution | raw source `string` | `string` |
      | `post_render` | after tag processing, before `HTML::format()` | assembled HTML `string` | `string` |

      Multiple callbacks per hook run in registration order, chained.
      The full `PREPROS` API — `mount()`, `exportFile()`, `fstat()`,
      `backtraceFile()`, `$config` — is in the
      [PHP class library](../php/#prepros).

      ## MD plugins

      `MD::registerPlugin('video', fn(array $args, string $body): string => …)` —
      works inside `<markdown>` blocks, `.md` data files, and any
      `MD::toHtml()` call. Kirigami ships a handful by default
      (`md.plugins.php`): `{% callout info|success|warning|danger ["Title"] content %}`,
      `{% img-asset path [w [h [cover]]] %}`, `{% codepen <id> [user h] %}`,
      `{% checklist ["Title"] … %}` — inline, or block form with the
      body on following lines ending in `%}`. `{% youtube <id> %}` /
      `{% vimeo <id> %}` moved into
      [`@kirigami/plugin-embed`](https://www.npmjs.com/package/@kirigami/plugin-embed) —
      not a default anymore, since resolving the id into a real card
      (oEmbed lookup, cover thumbnail, play button) needs the plugin's
      client-side script, not just a Markdown shortcut.

      ## Sass hooks

      A plugin **package**, listed under `plugins:` in
      `kirigami.yaml`, can contribute to `sass` tasks via
      `@kirigami/sdk`:

      ```js
      import { on, HOOKS } from '@kirigami/sdk';
      on(HOOKS.SASS_BEFORE,    (ctx) => '/abs/path/to/before.scss');
      on(HOOKS.SASS_AFTER,     (ctx) => '/abs/path/to/after.scss');
      on(HOOKS.SASS_FUNCTIONS, (ctx) => ({ 'my-fn($x)': (args) => /* SassValue */ }));
      ```

      `ctx` is `{ __root, task, exportPath, config }`. On a signature
      collision with a native Sass function, the native one wins. This
      is exactly how `@kirigami/plugin-highlight` appends its code
      theme after every project's own styles, and how `esbuild:before` /
      `esbuild:after` / `esbuild:plugins` mirror the same idea for JS
      bundles, plus `prepros:html` — a waterfall hook transforming each
      rendered page's HTML (what plugin-highlight's own colouring pass
      runs on).
    </markdown>
