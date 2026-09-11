<?php
/**
 * @title      Examples
 * @section    examples
 * @abstract   A cookbook — short, copy-pasteable recipes for things a real
 *             project needs, not a repeat of the reference.
 */
?>

<section class="section wrap doc-head">
    <span class="eyebrow">Examples</span>
    <h1><?php echo str_htmlesc($title); ?></h1>
    <p class="lead"><?php echo str_htmlesc($abstract); ?></p>
</section>

<section class="section wrap">
    <div class="prose">
        <markdown>
          [Docs](../docs/) is the exhaustive reference; the
          [tutorial](../start/tutorial/) is a guided walk. This page sits
          between the two: real, working snippets for specific things — no
          new concepts, just the pieces from Docs combined the way an actual
          project ends up using them. Two of the recipes below are actually
          registered on this site's own [`_lib/functions.php`](https://github.com/php-kirigami/php-kirigami.github.io/blob/main/src/_lib/functions.php)
          and demoed live, right where the code says they are.
        </markdown>
    </div>
</section>

<hr class="fold">

<section class="section wrap" id="seo">
    <div class="doc-head">
        <span class="eyebrow">Config only</span>
        <h2>SEO and social cards, in two lines</h2>
    </div>

    <div class="prose">
        <markdown>
          No `<title>`, no Open Graph tags to hand-write. One block in
          `kirigami.yaml` — the unified `seo:` surface — opts every page into
          `<title>`, description, keywords, Open Graph, Twitter Card,
          canonical link, and (nested inside it, its own independent opt-in)
          JSON-LD — derived from the `kirigami:` block plus each page's own
          PHPDOC:

          ```yaml
          seo:
            jsonld: {}
          ```

          A page overrides just what it needs from its own header —
          `@meta_description`, `@meta_image`, `@og_type`, … — without
          touching the other pages. Full reference:
          [`seo`](../docs/config/#seo) / [`seo.jsonld`](../docs/config/#seojsonld).
          This site runs on exactly this, nothing hand-written in
          `_layouts/header.php`.
        </markdown>
    </div>
</section>

<hr class="fold">

<section class="section wrap" id="badge">
    <div class="doc-head">
        <span class="eyebrow">Live — registered on this page</span>
        <h2>A one-off Markdown shortcode</h2>
    </div>

    <div class="prose">
        <markdown>
          A project doesn't need a published plugin for a shortcode it only
          uses itself — `MD::registerPlugin()` straight from
          `prepros.includes` is enough. This one wraps
          [canva's `.badge`](../design/#shared-components):

          ```php
          // _lib/functions.php
          md_register_plugin('badge', function (array $args, string $body): string {
              $tone = ($args[0] ?? 'default') === 'muted' ? ' badge--muted' : '';
              $text = implode(' ', array_slice($args, 1));
              if ($text === '') return '<!-- badge: missing text -->';
              return '<span class="badge' . $tone . '">' . str_htmlesc($text) . '</span>';
          });
          ```

          Used right here, in this page's own Markdown:

          ```
          {% badge default Stable %} {% badge muted "Coming soon" %}
          ```
        </markdown>

        <div class="demo">
            <markdown>
              {% badge default Stable %} {% badge muted "Coming soon" %}
            </markdown>
        </div>

        <markdown>
          `{% %}` plugins work inside `<markdown>` blocks, `.md` data files,
          and anywhere else `MD::toHtml()` runs. Full mechanics, including
          the default shortcodes Kirigami ships:
          [Writing pages → MD plugins](../docs/authoring/#md-plugins).
        </markdown>
    </div>
</section>

<hr class="fold">

<section class="section wrap" id="shortcut">
    <div class="doc-head">
        <span class="eyebrow">Live — registered on this page</span>
        <h2>A one-off HTML tag</h2>
    </div>

    <div class="prose">
        <markdown>
          Same idea via `PREPROS::registerTag()` — an authoring tag that
          expands at render time, for markup instead of `{% %}` shortcodes.
          This one turns a `+`-separated key combo into a row of `<kbd>`:

          ```php
          // _lib/functions.php
          register_tag('shortcut', function (string $tag, array $attrs, string $body): string {
              $keys = array_filter(array_map('trim', explode('+', $attrs['keys'] ?? '')));
              if (!$keys) return '<!-- shortcut: missing keys attribute -->';
              return implode('', array_map(fn($k) => '<kbd>' . str_htmlesc($k) . '</kbd>', $keys));
          });
          ```

          Used right here:

          ```html
          <shortcut keys="Ctrl+K">
          ```
        </markdown>

        <p class="demo"><shortcut keys="Ctrl+K"></p>

        <markdown>
          `PREPROS::registerTag()` is also how a real plugin — not just a
          one-off in `prepros.includes` — adds its own tag; see
          [`<extlink>`](../plugins/#extlink) or
          [Writing a plugin](../plugins/authoring/) for the packaged version
          of the same mechanism.
        </markdown>
    </div>
</section>

<hr class="fold">

<section class="section wrap" id="page-list">
    <div class="doc-head">
        <span class="eyebrow">FS::getChildren()</span>
        <h2>A page list that maintains itself</h2>
    </div>

    <div class="prose">
        <markdown>
          A hub page that auto-lists its own sub-pages, in `@position`
          order, straight from their PHPDOC — no array to keep in sync when
          a page is added or removed. This is exactly how
          [Docs](../docs/) builds its own card grid:

          ```php
          $sections = fs_get_children();

          foreach ($sections as $s) {
              $href = kirigami_page_href($s, $relroot);
              echo '<a class="card" href="' . str_htmlesc($href) . '">'
                 . '<h3>' . str_htmlesc($s->title ?? '') . '</h3>'
                 . '<p>' . str_htmlesc($s->abstract ?? '') . '</p>'
                 . '</a>';
          }
          ```

          `kirigami_page_href()` is this site's own two-line helper turning
          a child's `->file` into a relative URL — see it in
          [`_lib/functions.php`](https://github.com/php-kirigami/php-kirigami.github.io/blob/main/src/_lib/functions.php).
          Full signature: [`FS`](../docs/php/#fs).
        </markdown>
    </div>
</section>

<hr class="fold">

<section class="section wrap" id="gallery">
    <div class="doc-head">
        <span class="eyebrow">IMG::asset() + a data file</span>
        <h2>An image gallery from a YAML list</h2>
    </div>

    <div class="prose">
        <markdown>
          Auto-loaded data files turn a PHPDOC annotation into structured
          data — see [Auto-loaded data files](../docs/authoring/#auto-loaded-data-files).
          Pair one with `IMG::asset()` for a gallery with no hand-written
          `<img>` tags:

          ```yaml
          # _photos.yaml
          - file:    elephant.jpg
            caption: An elephant, cropped square
          - file:    another.jpg
            caption: Another shot, same treatment
          ```

          ```php
          /**
           * @photos _photos.yaml
           */

          foreach ($photos as $p) {
              $src = IMG::asset($p->file, 400, 400, true);
              echo '<figure><img src="' . str_htmlesc($src) . '" alt="' . str_htmlesc($p->caption) . '">'
                 . '<figcaption>' . str_htmlesc($p->caption) . '</figcaption></figure>';
          }
          ```

          Same `IMG::asset()` call this site's own live demo uses — two
          sizes cropped from one source photo, plus `IMG::palette()`
          swatches from it: [Docs → PHP → IMG](../docs/php/#img).
        </markdown>
    </div>
</section>

<hr class="fold">

<section class="section wrap" id="img-asset-shortcode">
    <div class="doc-head">
        <span class="eyebrow">Live — the default Markdown plugin</span>
        <h2>The same thing, one line, inside Markdown</h2>
    </div>

    <div class="prose">
        <markdown>
          For a single image dropped straight into prose — no PHPDOC
          annotation, no loop — `{% img-asset %}` is the same `IMG::asset()`
          call as a Markdown shortcode, shipped by default (no plugin to
          install):

          ```
          {% img-asset male-african-bush-elephant.jpg 320 180 cover %}
          ```
        </markdown>

        <div class="demo">
            <markdown>
              {% img-asset male-african-bush-elephant.jpg 320 180 cover %}
            </markdown>
        </div>

        <markdown>
          Positional args: `path [width [height [cover]]]` — a missing or
          unresolvable path degrades to an HTML comment instead of failing
          the build. Full mechanics: [Writing pages → MD plugins](../docs/authoring/#md-plugins).
        </markdown>
    </div>
</section>

<hr class="fold">

<section class="section wrap" id="rich-cards">
    <div class="doc-head">
        <span class="eyebrow">Two official plugins</span>
        <h2>Rich cards without hand-rolled scraping</h2>
    </div>

    <div class="prose">
        <markdown>
          Link previews and video embeds both look like small scraping
          projects until you actually need caching, image cropping, and an
          oEmbed lookup that doesn't block the build. Two plugins do the
          work:

          ```html
          <extlink src="https://github.com/php-kirigami/kirigami">

          <youtube id="jNQXAC9IVRw">
          <vimeo id="1084537">
          ```

          `<extlink>` scrapes once at build time and caches to disk —
          `<youtube>`/`<vimeo>` resolve client-side, cached in
          `localStorage`, nothing fetched until the visitor clicks play. Both
          are demoed live, with the real cards, on [Plugins](../plugins/#extlink).
        </markdown>
    </div>
</section>
