<?php
/**
 * @title      Plugins
 * @section    plugins
 * @type       doc
 * @abstract   Official Kirigami plugins — install one, or write your own.
 * @breadcrumb true
 */
?>

<section class="section wrap">
    <div class="prose">
        <markdown>
          A plugin is an npm package — `@kirigami/plugin-*`, or your own
          under the same convention — declared under `plugins:` in
          `kirigami.yaml`. It taps into the same tag/hook registry a
          project's own `prepros.includes` file does (see
          [Writing pages](../docs/authoring/#registering-tags-and-hooks)),
          just packaged and versioned for reuse across projects.

          ```bash
          kiri install highlight
          ```

          resolves a bare name against the `@kirigami/plugin-*` convention,
          installs it, and prints the `plugins:` block to paste into
          `kirigami.yaml` — see the [CLI reference](../docs/cli/) for the
          full command. Three official plugins ship today; this page
          demos each one live. The complete list, with versions fetched
          live from npm, is on [Ecosystem](../ecosystem/).
        </markdown>
    </div>
</section>

<hr class="fold">

<section class="section wrap" id="highlight">
    <div class="doc-head">
        <span class="eyebrow">Build-time</span>
        <h2>@kirigami/plugin-highlight</h2>
        <p class="lead">Syntax highlighting, resolved at build time — zero bytes of highlight.js reach the browser.</p>
    </div>

    <div class="prose">
        <markdown>
          You're already looking at the demo: every fenced code block on
          this entire site — the one above included — is colored by this
          plugin, its theme layered on via `sass:after` and tuned to match
          the site's own palette instead of a generic preset. Also
          registers a `<highlight lang="…">` authoring tag, and a hover
          copy button on every block.
        </markdown>
    </div>

    <p><a href="https://www.npmjs.com/package/@kirigami/plugin-highlight">npm</a> &middot; <a href="https://github.com/php-kirigami/kirigami/tree/main/packages/plugin-highlight">Source</a></p>
</section>

<hr class="fold">

<section class="section wrap" id="extlink">
    <div class="doc-head">
        <span class="eyebrow">Server-side, cached</span>
        <h2>@kirigami/plugin-extlink</h2>
        <p class="lead">External link preview cards — scraped once, at build time, and cached to disk.</p>
    </div>

    <div class="prose">
        <markdown>
          `<extlink src="…">` scrapes the target page's title, description,
          preview image and site name, then caches every bit of it —
          `_data/extlink/`, `assets/extlink/`, `assets/images/extlink/`,
          meant to be committed — so a later build, CI included, never
          re-crawls a URL it has already resolved:
        </markdown>
    </div>

    <extlink src="https://github.com/php-kirigami/kirigami">

    <p><a href="https://www.npmjs.com/package/@kirigami/plugin-extlink">npm</a> &middot; <a href="https://github.com/php-kirigami/kirigami/tree/main/packages/plugin-extlink">Source</a></p>
</section>

<hr class="fold">

<section class="section wrap" id="embed">
    <div class="doc-head">
        <span class="eyebrow">Client-side</span>
        <h2>@kirigami/plugin-embed</h2>
        <p class="lead">YouTube / Vimeo oEmbed cards — resolved in the visitor's own browser, nothing fetched at build time.</p>
    </div>

    <div class="prose">
        <markdown>
          `<youtube id="…">` / `<vimeo id="…">` swap themselves for a cover
          thumbnail, the video's title, and a play button the moment the
          page loads — the oEmbed lookup runs client-side, cached in
          `localStorage`, and nothing actually loads from the provider
          until that button is clicked:
        </markdown>
    </div>

    <youtube id="jNQXAC9IVRw">

    <vimeo id="1084537">

    <p><a href="https://www.npmjs.com/package/@kirigami/plugin-embed">npm</a> &middot; <a href="https://github.com/php-kirigami/kirigami/tree/main/packages/plugin-embed">Source</a></p>
</section>

<hr class="fold">

<section class="section wrap">
    <div class="prose">
        <markdown>
          ## Writing your own

          A plugin's package exports a default function; `kiri` calls it
          once, at the top of every build/export/watch, with its `options:`
          from `kirigami.yaml`. From there it's the exact same
          [`@kirigami/sdk`](https://www.npmjs.com/package/@kirigami/sdk)
          hook registry a project's own `prepros.includes` file can reach
          into. [**Writing a plugin →**](authoring/) walks through building
          one from scratch — package shape, registering a tag, shipping
          default styles, the options schema, and a couple of gotchas that
          only show up once you actually try it. The three plugins above
          are real, MIT-licensed, source-linked examples to read alongside
          it — `plugin-highlight`'s `index.js` is the shortest complete one.
        </markdown>
    </div>
</section>
