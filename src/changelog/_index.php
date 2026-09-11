<?php
/**
 * @title      Changelog
 * @section    changelog
 * @abstract   What shipped, and when — across the whole ecosystem.
 */
?>

<section class="section wrap doc-head">
    <span class="eyebrow">Changelog</span>
    <h1><?php echo str_htmlesc($title); ?></h1>
    <p class="lead"><?php echo str_htmlesc($abstract); ?></p>
</section>

<section class="section wrap">
    <div class="prose">
        <markdown>
          A grouped, human-written summary — not a raw commit log. Every
          package also keeps its own detailed "What's new" section in its
          README, linked below each entry; [Ecosystem](../ecosystem/) always
          shows the current version of everything, fetched live from npm.

          ## September 11, 2026

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

          New on this site: [Plugins](../plugins/), with a live demo of all
          three official plugins, and
          [Writing a plugin](../plugins/authoring/), a hands-on tutorial —
          this page too.

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
