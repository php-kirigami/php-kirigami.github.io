<?php
/**
 * @title      Changelog
 * @section    changelog
 * @type       doc
 * @abstract   What shipped, and when — across the whole ecosystem.
 */
?>

<section class="section wrap">
    <div class="prose">
        <markdown>
          A grouped, human-written summary — not a raw commit log. Every
          package also keeps its own detailed "What's new" section in its
          README, linked below each entry; [Ecosystem](../ecosystem/) always
          shows the current version of everything, fetched live from npm.

          ## September 11, 2026

          **[@kirigami/php-prepros](https://www.npmjs.com/package/@kirigami/php-prepros)**
          `1.9.3` → `2.0.0` — **breaking:** the separate `meta:` / `jsonld:`
          top-level blocks are merged into one unified `seo:` block, `jsonld`
          nested inside it — one place, one mental model for a project's
          whole SEO/social surface, still independently toggleable (a
          project can have META's tags without JSON-LD, or vice versa).
          Migration is a rename: `meta:` → `seo:`, and `jsonld:`'s content
          moves under it as `seo.jsonld:`. This site's own `kirigami.yaml`
          made the same move, [Docs → Config](../docs/config/#seo) is
          updated, and the [SEO tutorial step](../start/tutorial/6-seo/)
          reflects it. **[@kirigami/kirigami](https://www.npmjs.com/package/@kirigami/kirigami)**
          `1.5.7` → `2.0.0` alongside it — also breaking, since the bundled
          `kirigami.schema.json` now rejects the old `meta:`/`jsonld:` keys
          outright.

          **[@kirigami/plugin-embed](https://www.npmjs.com/package/@kirigami/plugin-embed)**
          `0.1.0` → `0.1.1` — new plugin: `<youtube id="…">` /
          `<vimeo id="…">` turn into a real video card (cover thumbnail,
          title, play button), resolved entirely in the visitor's browser —
          no build-time network call, the oEmbed lookup is cached in
          `localStorage`. `0.1.1` floors the card's aspect-ratio at 16∶9, so
          a narrower source video (4∶3, portrait, a Short) no longer
          produces a card that dominates the page next to normal widescreen
          ones — the real player still shows at its true ratio once
          clicked, pillarboxed rather than stretched. Live demo on
          [Plugins](../plugins/#embed).

          **[@kirigami/plugin-extlink](https://www.npmjs.com/package/@kirigami/plugin-extlink)**
          `0.1.0` → `0.1.1` — new plugin: `<extlink src="…">` external link
          preview cards, scraped once at build time and cached to disk
          (`_data/extlink/`, `assets/images/extlink/`) so a rebuild —
          CI included — never re-crawls a URL it has already resolved.
          `0.1.1` also archives the untouched, full-resolution download to
          `assets/extlink/`. Live demo on [Plugins](../plugins/#extlink).

          **[@kirigami/php-prepros](https://www.npmjs.com/package/@kirigami/php-prepros)**
          `1.7.2` → `1.9.1` — the default Markdown plugin `{% img-asset %}`
          (same pipeline as `<img asset>`); `{% youtube %}` removed (its
          job — a real oEmbed-backed card instead of a plain iframe — moved
          to `plugin-embed`, above); a build-crashing bug fixed where
          `{% img-asset %}` on an unresolvable path threw instead of
          degrading gracefully, discovered while writing this site's own
          [plugin-authoring tutorial](../plugins/authoring/).

          **[@kirigami/kirigami](https://www.npmjs.com/package/@kirigami/kirigami)**
          `1.5.0` → `1.5.3` — dependency bumps tracking the `php-prepros`
          fixes above; its own README brought current (`kiri install` was
          completely undocumented; the CI example still showed
          `kiribuild@v1`).

          **[@kirigami/canva](https://www.npmjs.com/package/@kirigami/canva)**
          `2.5.0` — `styles/main` is no longer a stub: `.breadcrumb`,
          `.docs-toc`, `.table`, `.badge`, `.palette` — five small
          components every Kirigami site kept re-implementing from scratch,
          lifted into one place. Plus themed native scrollbars, following
          the palette in dark mode instead of defaulting to white.

          **[@kirigami/kirigami](https://www.npmjs.com/package/@kirigami/kirigami)**
          `1.5.3` → `1.5.6` — two commands that hadn't been called out here
          yet: **`kiri serve`**, everything `kiri watch` does plus a local
          dev server with browser hot-reload (a `sass`-only change hot-swaps
          the stylesheet in place instead of a full page reload), and
          **`kiri install <plugin>`**, which installs a plugin and prints
          the exact `plugins:` block to paste into `kirigami.yaml`, built
          from the plugin's own option schema. `1.5.6` also replaces a raw
          `EADDRINUSE` crash with a direct "try a different port" message.
          Full reference: [Docs → CLI](../docs/cli/).

          **[@kirigami/php-prepros](https://www.npmjs.com/package/@kirigami/php-prepros)**
          `1.9.1` → `1.9.3` — fixed a real Markdown bug: a soft-wrapped
          continuation line inside a list item (no marker of its own) closed
          the list early instead of extending the item — the same fix that
          let [Roadmap](../roadmap/) go back to natural wrapped prose instead
          of one line per bullet.

          **[@kirigami/plugin-highlight](https://www.npmjs.com/package/@kirigami/plugin-highlight)**
          `0.1.1` → `0.1.6` — two real bugs fixed: an unknown `languages:`
          name now fails the build immediately instead of warning once and
          silently leaving that language unhighlighted forever; `copyButton`
          no longer renders a real, styled, non-functional button on a
          project with no `esbuild` task available to bundle its click
          handler. Also fixed, alongside **[@kirigami/canva](https://www.npmjs.com/package/@kirigami/canva)**
          `2.5.0` → `2.5.2`: a CSS specificity bug where `styles/prose`'s
          own code resets could out-rank plugin-highlight's theme in light
          mode, visible as a highlighted block's font/size reflowing on
          every theme toggle.

          **[@kirigami/plugin-embed](https://www.npmjs.com/package/@kirigami/plugin-embed)**
          `0.1.1` → `0.1.2` — replaced the 16∶9 aspect-ratio floor with a
          `maxWidth` cap (default `40rem`) plus the video's real aspect
          ratio, and a `forcedAspectRatio` option to pin every card in a
          grid to one uniform shape.

          **[@kirigami/plugin-extlink](https://www.npmjs.com/package/@kirigami/plugin-extlink)**
          `0.1.1` → `0.1.2` — added the `{% extlink URL ["title"] %}`
          Markdown shortcut, alongside the existing `<extlink src="…">` tag.

          New on this site: [Plugins](../plugins/), with a live demo of all
          three official plugins,
          [Writing a plugin](../plugins/authoring/), a hands-on tutorial,
          this page, and a real `og:image` — set once, sitewide, via the
          `kirigami:` block's `image` key rather than a per-page afterthought
          (a page still overrides it with its own `@meta_image`).

          ## September 10, 2026

          **[@kirigami/sdk](https://www.npmjs.com/package/@kirigami/sdk)**
          `0.2.0`, **[@kirigami/canva](https://www.npmjs.com/package/@kirigami/canva)**
          `2.4.0`, **[@kirigami/php-prepros](https://www.npmjs.com/package/@kirigami/php-prepros)**
          `1.7.0` → `1.7.1`, **[@kirigami/kirigami](https://www.npmjs.com/package/@kirigami/kirigami)**
          `1.4.0` → `1.4.1`, **[@kirigami/plugin-highlight](https://www.npmjs.com/package/@kirigami/plugin-highlight)**
          `0.1.1` — the `META` class (`<head>` SEO/social metadata,
          opt-in via a `meta:` block, companion to `LD`'s JSON-LD); canva
          `styles/prose` and the `reveal` script, both lifting code every
          starter template carried inline; and the `###TIMESTAMP###` fix
          that had been quietly rewriting every committed preview page on
          every CI run. Second same-day release fixed `kiri build`/`export`/
          `run --help` crashing "Config file not found" outside a real
          project, and trimmed every CLI command's help text to one-liners.

          ---

          Older history lives in each package's own README — start with
          [php-prepros](https://www.npmjs.com/package/@kirigami/php-prepros)
          and [kirigami](https://www.npmjs.com/package/@kirigami/kirigami),
          which carry the longest "What's new" sections.
        </markdown>
    </div>
</section>
