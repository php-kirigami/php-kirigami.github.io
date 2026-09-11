<?php
/**
 * @title      Config
 * @section    docs
 * @position   1
 * @abstract   Everything kirigami.yaml can hold — the kirigami:, meta:,
 *             jsonld:, prepros:, image:, plugins:, esbuild:, sass:,
 *             export:, scripts: and tasks: blocks.
 * @breadcrumb true
 */
?>

<section class="section wrap doc-head">
    <?php echo kirigami_breadcrumb_nav($title, $relroot); ?>
    <span class="eyebrow">Documentation</span>
    <h1><?php echo str_htmlesc($title); ?></h1>
    <p class="lead"><?php echo str_htmlesc($abstract); ?></p>
</section>

<section class="section wrap">
    <div class="prose">
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

          ## meta

          Opt-in `<head>` SEO / social metadata — `<title>`, description,
          Open Graph, Twitter Card, canonical, favicon. A **top-level**
          block, sibling of `kirigami:`. An empty `meta: {}` is enough to
          turn injection on for every page; values are then derived from the
          `kirigami:` keys above and each page's PHPDOC (`@title`,
          `@description`, `@image`, `@robots`, …), with a tag your layout
          already writes by hand detected and skipped.

          ```yaml
          meta:
            twitter:     "@myhandle"
            themeColor:  "#0b7285"
          ```

          | Key | Type | Notes |
          |---|---|---|
          | `auto` | bool | Inject automatically. Default `true` once the block exists; `auto: false` (or `meta: false`) keeps the values without injecting — call `META::tags()` by hand instead. |
          | `titleFormat` / `titleFormatHome` | string | `<title>` templates. Tokens `{title}`, `{project}`, `{tagline}`. Defaults `{title} — {project}` / `{project} — {tagline}`. |
          | `description` / `keywords` | string / string[] | Fallback for pages with no `@description` / `@keywords`. |
          | `robots` | string \| `false` | Default `index, follow`. |
          | `language` | string | BCP-47 tag → `<meta name="language">` + `og:locale`. |
          | `generator` | string \| `false` | Default `Kirigami`. |
          | `author` / `designer` | string | `<meta name="author">` / a `designer` tag. |
          | `themeColor` | string | `<meta name="theme-color">`. |
          | `image` | string | Default `og:image` / `twitter:image`. Unset here, it falls back to the `kirigami:` block's own loose `image` / `ogimage` key, then `jsonld.image` / `jsonld.logo` — handy for a single sitewide default set once, outside the `meta:` block. |
          | `ogType` | string | Default `website`. |
          | `twitterCard` | string | Default `summary_large_image`. |
          | `twitter` | string \| map | Handle for `twitter:site` / `twitter:creator`. |
          | `canonical` | bool | Emit `<link rel="canonical">`. Default `true`. |
          | `favicon` / `appleTouchIcon` / `humans` | string \| bool | A path sets it; `true` forces the default file; omitted, the default is auto-detected on disk; `false` disables it. |

          Per-page overrides live in the PHPDOC block: `@meta false` skips a
          page entirely; `@meta_title`, `@meta_description`, `@meta_image`,
          `@meta_robots`, `@meta_type`, `@canonical` override the generic
          tag. See [`META`](../php/#meta) for the manual builders.

          ## jsonld

          Opt-in schema.org JSON-LD — the companion to `meta:`. Same switch:
          a top-level `jsonld: {}` block turns on an automatic JSON-LD block
          in the page's head, derived from the same `kirigami:` keys.

          ```yaml
          jsonld:
            type: ProfessionalService   # @type for the main entity
            lang: en-CA                  # inLanguage on WebSite / WebPage
            logo: assets/logo.png        # absolute, or relative to baseurl
            search: https://example.com/?q={search_term_string}
          ```

          | Key | Type | Notes |
          |---|---|---|
          | `auto` | bool | Same switch as `meta.auto`. |
          | `type` | string | `Organization`, `ProfessionalService`, `LocalBusiness`, … |
          | `name` / `url` / `description` | string | Default to `project` / `baseurl` / `description`. |
          | `logo` / `image` | string | `image` defaults to `logo`. |
          | `sameAs` | string[] | Profile URLs. |
          | `address` | map | `PostalAddress` properties. |
          | `person` | string \| map | The `#person` node — a string is just the name. |
          | `search` | string | Sitelinks `SearchAction` URL template; must contain `{search_term_string}`. |

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
          ```

          `includes` is where you register custom tags, Markdown shortcodes
          and hooks — this site's own [`_lib/functions.php`](https://github.com/php-kirigami/php-kirigami.github.io/blob/main/src/_lib/functions.php)
          is exactly that. See [Writing pages](../authoring/) for the render
          pipeline these plug into.

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
    </div>
</section>
