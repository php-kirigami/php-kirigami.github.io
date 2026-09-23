<?php
/**
 * @title      Configuration
 * @section    docs
 * @type       docs
 * @group      Build a site
 * @position   2
 * @abstract   Everything kirigami.yaml can hold — the kirigami:, seo:,
 *             prepros:, image:, plugins:, esbuild:, sass:, export:,
 *             scripts: and tasks: blocks.
 */
?>

    <markdown>
      Everything is driven by one file, `kirigami.yaml`, at the project
      root. It's loaded through `@kirigami/struct-walker` (so nested file
      references resolve), validated against `kirigami.schema.json`, then
      checked imperatively — `kiri` throws on any unknown key, wrong
      type, or missing required property. Point your editor at the
      schema for autocompletion:

      ```yaml
      # yaml-language-server: $schema=https://cdn.jsdelivr.net/gh/php-kirigami/kirigami@main/packages/kirigami/kirigami.schema.json
      ```

      ## kirigami

      The only required top-level block.

      ```yaml
      kirigami:
        project:  My Website           # site name — CLI banner, $project
        baseurl:  https://example.com   # deployed root URL, no trailing slash
        root:     src                   # dir holding _*.php pages
        banner:   assets/banner.txt     # license banner stamped on exported js/css/html

        # Any other key here is free-form project data — it becomes a PHP
        # variable of the same name in every page, in before/after, and in
        # prepros.includes files (also readable as PREPROS::$config->data).
        author:      Jane Doe
        email:       hello@example.com
        description: A short description, handy for <meta name="description">.
        keywords:    [static site, php]
      ```

      | Key | Required | Notes |
      |---|---|---|
      | `project` | ✅ | Site name. |
      | `baseurl` | ✅ | Deployed root URL, no trailing slash. Drives the sitemap and canonical URLs. |
      | `root` | ✅ | Directory holding `_*.php` pages; every task's `entry` is relative to it. |
      | `banner` | – | A text file, its `### ### `-tokens filled from this block, stamped on every exported `.js` / `.css` / `.html`. No file set → the bundled default banner is used the same way. |

      Loose keys are free project data. This site's own header/footer
      read `$project`, `$baseurl`, `$author`, `$tagline`, `$description`
      straight out of them — no separate "site config" object to keep in
      sync.

      ## seo

      The SEO surface: `<head>` metadata (`<title>`, description, Open
      Graph, Twitter Card, canonical, favicon) and a schema.org JSON-LD
      graph, both configured by this one **top-level** block, sibling of
      `kirigami:`. An empty `seo: {}` turns both on for every page; values
      are derived from the `kirigami:` keys above and each page's PHPDOC
      (`@title`, `@description`, `@image`, `@robots`, …). A tag your
      layout already writes by hand is detected and skipped.

      ```yaml
      seo:
        twitter:    "@myhandle"
        themeColor: "#0b7285"
        lang:       en-CA                  # <meta name="language">, og:locale, JSON-LD inLanguage
        type:       ProfessionalService    # JSON-LD main entity
        logo:       assets/logo.png        # absolute, or relative to baseurl
        search:     https://example.com/?q={search_term_string}
      ```

      | Key | Type | Notes |
      |---|---|---|
      | `auto` | bool | Inject the `<head>` tags. Default `true` once the block exists; `false` keeps the values without injecting (call `META::tags()` by hand). |
      | `jsonld` | bool | Inject the JSON-LD graph. Default `true` once the block exists; `false` turns only the JSON-LD off. |
      | `titleFormat` / `titleFormatHome` | string | `<title>` templates. Tokens `{title}`, `{project}`, `{tagline}`. Defaults `{title} — {project}` / `{project} — {tagline}`. |
      | `description` / `keywords` | string / string[] | Fallback for pages with no `@description` / `@keywords`, and the JSON-LD site description and keywords. |
      | `robots` | string | `false` | Default `index, follow`. |
      | `lang` | string | BCP-47 tag → `<meta name="language">`, `og:locale` and JSON-LD `inLanguage`. Default `en`. |
      | `generator` | string | `false` | Default `Kirigami`. |
      | `author` / `designer` | string | `<meta name="author">` / a `designer` tag. |
      | `themeColor` | string | `<meta name="theme-color">`. |
      | `image` | string | Default `og:image` / `twitter:image` and JSON-LD image. Falls back to `logo`, then the `kirigami:` block's `image` / `ogimage`. |
      | `logo` | string | The organization logo (JSON-LD). |
      | `ogType` / `twitterCard` | string | Defaults `website` / `summary_large_image`. |
      | `twitter` | string | map | Handle for `twitter:site` / `twitter:creator`. |
      | `canonical` | bool | Emit `<link rel="canonical">`. Default `true`. |
      | `favicon` / `appleTouchIcon` / `humans` | string | bool | A path sets it; `true` forces the default file; omitted, the default is auto-detected on disk; `false` disables it. |
      | `type` | string | JSON-LD main entity: `Organization` (default), `ProfessionalService`, `LocalBusiness`, … |
      | `name` / `url` | string | JSON-LD main entity name and URL. Default to `project` / `baseurl`. |
      | `sameAs` | string[] | Profile URLs, merged with social links found in `kirigami:` (`github`, `facebook`, …). |
      | `email` / `telephone` / `address` | string / map | Contact details of the main entity. |
      | `person` | string | map | The `#person` node; a string is just the name. |
      | `search` | string | Sitelinks `SearchAction` URL template; must contain `{search_term_string}`. |

      Per-page overrides live in the PHPDOC block: `@meta false` skips a
      page's tags, `@ld false` its JSON-LD; `@meta_title`,
      `@meta_description`, `@meta_image`, `@meta_robots`, `@meta_type`,
      `@canonical` and `@ld_type` override the generic values. See
      [`META`](../php/#meta) for the manual builders.

      For anything schema.org doesn't cover from config alone, build a
      node by hand with [`LD`](../php/#ld) — `LD::article()`,
      `LD::faqPage()`, `LD::breadcrumb()`, or any type via
      `LD::typeName([...])`.

      ## prepros

      The PHP → HTML compiler. Present — even empty — forces the
      `prepros` task.

      ```yaml
      prepros:
        before:   _layouts/header.php   # included before every page body
        after:    _layouts/footer.php   # included after every page body
        format:   true                  # pretty-print HTML output
        head:     true                  # default true — see "Managed head" below
        network:  false                 # allow outbound HTTP(S) — remote @tags, CURL, SCRAPER
        mountext: [.svg, .webp]          # extra extensions auto-mounted into the virtual FS
        includes: [_lib/functions.php]   # PHP include_once'd before any page renders
        types:                           # page types, picked per page with @type <name>
          doc:
            before: _layouts/types/doc.before.php
            after:  _layouts/types/doc.after.php
      ```

      `includes` is where you register custom tags, Markdown shortcodes
      and hooks — this site's own [`_lib/functions.php`](https://github.com/php-kirigami/php-kirigami.github.io/blob/main/src/_lib/functions.php)
      is exactly that. See [Writing pages](../authoring/) for the render
      pipeline these plug into.

      `types` declares [page types](../authoring/#page-types): each name
      maps to a `before` and/or `after` file (relative to `kirigami.root`)
      that wraps the body of every page declaring `@type <name>`, inside
      the global `before`/`after`.

      ## image

      The image autogenerator's one config block — see
      [`IMG`](../php/#img) for the three surfaces that call into it
      (`img-asset()` in Sass, `IMG::asset()` in PHP, `<img asset>` in
      markup).

      ```yaml
      image:
        format: webp        # webp | avif        (default webp)
        source: assets/images   # rel. to cwd()       (default assets/images)
        dest:   images           # rel. to kirigami.root (default images)
      ```

      ## plugins

      Kirigami plugins, loaded via `@kirigami/sdk`.

      ```yaml
      plugins:
        - name: "@kirigami/plugin-highlight"
          active: true
          options: {}   # free-form, validated against the plugin's own schema
      ```

      Each `name` must match `@kirigami/plugin-*`, `<scope>/kirigami-plugin-*`,
      or `kirigami-plugin-*`. `kiri install <plugin>` resolves a bare name
      against those conventions, installs it, and prints the entry to
      paste here — see [`kiri install`](../cli/).

      ## esbuild and sass

      Free-form blocks, merged into every task of that type (after
      Kirigami's own defaults) — anything the underlying tool accepts.

      ```yaml
      esbuild:
        # minify: false
      sass:
        style: expanded
        # before / after: extra .scss files compiled before/after the entry
      ```

      `sass` resolves `@use` / `@forward` through Sass's
      `NodePackageImporter` plus a custom importer that also accepts an
      implicit `styles/` prefix (`@use '@kirigami/canva/conf'` →
      `@kirigami/canva/styles/conf`).

      ## export

      ```yaml
      export:
        path:   dist                    # output dir for `kiri export`
        ignore: ["*.psd", "notes/"]      # extra gitignore-style excludes
      ```

      `kiri export` copies `kirigami.root` into `path`, excluding any
      file/dir starting with `_` or `.`, `.scss` files, `.map` files,
      and non-minified `.js` files, plus every `ignore` pattern. Token
      replacements happen during the copy: `###YEAR###` and
      `###TIMESTAMP###` in `.html`, `###TODAY###` in `sitemap.xml`.

      ## scripts

      Named PHP scripts, run with `kiri run <name>` or on a build
      trigger.

      ```yaml
      scripts:
        - name: convert-images
          mount: ["assets/images/**/*.jpg"]   # extra files mounted before it runs
          trigger: before-build                # before-build | before-export | after-export
      ```

      Omit `trigger` for a script that only ever runs manually. See
      `STD::succeed()` / `STD::error()` in the [PHP class library](../php/#std)
      for ending one early with a custom result.

      ## tasks

      The ordered build pipeline, on top of the implicit `prepros` and
      `dist` tasks.

      ```yaml
      tasks:
        - { name: js-core,   type: esbuild, entry: scripts/kirigami.core.js }
        - { name: scss-core, type: sass,    entry: styles/kirigami.core.scss }
      ```

      ### Task types

      The two you actually write into `tasks:` yourself — `prepros` and
      `dist` are internal, added automatically (see above), never
      declared by hand.

      | `type` | Purpose | Required | Optional | Output |
      |---|---|---|---|---|
      | `esbuild` | Bundle + minify a JS/TS entry. Build + watch. | `name`, `type`, `entry` | `force`, `head` | `<entry>.min.js` (+ `.map` outside export) |
      | `sass` | Compile a `.scss`/`.sass` entry, re-minified with csso on export. Build + watch. | `name`, `type`, `entry` | `force`, `head` | `<entry>.min.css` (+ `.css.map` outside export) |

      ## Managed head

      Unless `prepros.head` is `false`, every rendered page's `<head>`
      is auto-wired — no need to hand-write any of it in `before`:

      - a small theme/FOUC guard as the first child of `<head>` (adds a
        `js` class, applies the stored `data-theme` before first paint);
      - a `<link rel="stylesheet">` for every `sass` task output;
      - a `<script>` (no `defer`, just before `</body>`) for every
        `esbuild` task output.

      Paths are per-page-relative and carry a `?<timestamp>` cache-bust,
      left literal at build time and only expanded on `kiri export` — so
      rebuilding never rewrites a committed page. A file already
      referenced in the page is left alone; skip a single task's tag
      with `head: false` on that task.
    </markdown>
