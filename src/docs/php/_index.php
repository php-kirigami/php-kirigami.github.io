<?php
/**
 * @title      PHP class library
 * @section    docs
 * @position   4
 * @abstract   Every autoloaded class available in pages, before/after
 *             includes, prepros.includes, and kiri run scripts.
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
    <ul class="docs-toc">
        <li><a href="#prepros">PREPROS</a></li>
        <li><a href="#md">MD</a></li>
        <li><a href="#html">HTML</a></li>
        <li><a href="#yaml">YAML</a></li>
        <li><a href="#schema">SCHEMA</a></li>
        <li><a href="#ld">LD</a></li>
        <li><a href="#meta">META</a></li>
        <li><a href="#cache">CACHE</a></li>
        <li><a href="#img">IMG</a></li>
        <li><a href="#fs">FS</a></li>
        <li><a href="#str">STR</a></li>
        <li><a href="#arr">ARR</a></li>
        <li><a href="#curl">CURL</a></li>
        <li><a href="#scraper">SCRAPER</a></li>
        <li><a href="#obf">OBF</a></li>
        <li><a href="#std">STD</a></li>
        <li><a href="#aliases">Aliases</a></li>
    </ul>

    <div class="prose">
        <markdown>
          All classes below are autoloaded — no `require` anywhere. Every
          static method also has a plain-function alias (`aliases` at the
          bottom) — the two are the same API, pick whichever reads better in
          a given page.

          ## PREPROS

          The render engine itself.

          ```php
          PREPROS::$config                                  // stdClass: ->data = kirigami: block, ->image, ->before/after/format/network
          PREPROS::registerTag(string $tag, callable $cb)    // $cb($fullTag, array $attrs, string $body): string
          PREPROS::registerHook(string $hook, callable $cb)  // hooks: page_info, pre_render, post_render
          PREPROS::mount(string|array $globPatterns)         // mount extra local files (any extension) into the WASM FS
          PREPROS::exportFile(string|array $absPath)         // mark a file as build output
          PREPROS::getExportedFiles(): string[]
          PREPROS::fstat(string $path)                       // stat a file in the WASM FS, or false
          PREPROS::backtraceFile()                           // path of the page currently rendering
          ```

          See [Writing pages](../authoring/#registering-tags-and-hooks) for
          the render pipeline these hooks fire in.

          ## MD

          Markdown → HTML.

          ```php
          $html = MD::toHtml(string $markdown): string;
          MD::registerPlugin(string $name, callable $cb);   // $cb(array $args, string $body): string
          MD::unregisterPlugin(string $name);
          MD::getRegisteredPlugins(): string[];
          MD::registerEmoji(string $shortcode, string $char);
          ```

          Supports GFM (tables with alignment, task lists, alerts, strikethrough,
          autolinks), ATX + Setext headings with an auto `id` (what every
          anchor link on this page targets), fenced code with a language
          class, footnotes, definition lists, emoji shortcodes, hard line
          breaks, and allowlist-sanitized inline HTML. External links get
          `target="_blank" rel="noopener noreferrer"`; images get
          `loading="lazy"`.

          ## HTML

          ```php
          HTML::format(string $html): string;   // PHP 8.4 Dom\HTMLDocument (Lexbor), 4-space indent
          ```

          Runs automatically when `prepros.format: true`. Handles inline
          elements, `<script>`, `<style>`; writes boolean HTML5 attributes
          without a value; keeps SVG/MathML foreign-content casing
          (`viewBox`, `linearGradient`, …) intact.

          ## YAML

          ```php
          YAML::parse(string $yaml, bool $assoc = false): mixed;
          YAML::parseFile(string $path, bool $assoc = false): mixed;
          YAML::loadFile(string $path, bool $assoc = false): mixed;   // parseFile + recursively inline nested .yaml/.yml/.json refs
          ```

          A zero-dependency parser: scalars, quoted strings + escapes, block
          scalars (`|`, `>`, with chomping), multi-line plain scalars,
          nested maps/sequences, inline `[a, b]` / `{k: v}`, comments,
          multi-doc `---`. Mappings are `stdClass` unless `$assoc = true`.
          `loadFile()` throws on a circular reference.

          ## SCHEMA

          A pure-PHP JSON Schema validator — no `ajv`, same as every other
          "stay lite" call in the toolchain.

          ```php
          $v = new SCHEMA(array $schema);
          $v->isValid(mixed $data): bool;      // alias: validate()
          $v->getErrors(): string[];           // "path: message" from the last run
          ```

          Draft-7-ish: `type`, `required`, `properties`, `patternProperties`,
          `additionalProperties`, `items`, `min/maxItems`, `uniqueItems`,
          `min/maxLength`, `pattern`, `minimum`/`maximum` (+ `exclusive*`),
          `min/maxProperties`, `enum`, `const`, `anyOf`/`allOf`/`oneOf`/`not`,
          `format`, local `$ref`.

          ## LD

          A schema.org JSON-LD graph builder — the engine behind the
          [`jsonld:`](../config/#jsonld) block.

          ```php
          LD::add(string|array $type, array $props = [], ?string $id = null): array   // build + register a node
          LD::node(string|array $type, array $props = []): array                       // build only, no register
          LD::push(array $node): array                                                 // register a ready-made node
          LD::ref(string $id): array                                                    // ['@id' => …]  ('#person' → the Person node)
          LD::remove(string $id): void
          LD::graph(): array
          LD::reset(): void

          LD::organization(array $overrides = []): array   // config-aware, @id #organization
          LD::person(array $overrides = []): array          // config-aware, @id #person
          LD::website(array $overrides = []): array          // config-aware, @id #website
          LD::webPage(array $overrides = []): array           // current-page-aware, @id …#webpage
          LD::breadcrumb(?array $items = null, array $overrides = []): array   // items: [['name'=>…,'url'=>…], …]
          LD::faqPage(array $qa, array $overrides = []): array                 // qa: ['Question ?' => 'Answer.', …]

          LD::address(array|string $a): array
          LD::image(string $url, ?string $id = null, ?int $w = null, ?int $h = null): array
          LD::geo(float $lat, float $lng): array
          LD::rating(int|float $value, ?int $count = null, $best = 5, $worst = 1): array
          LD::offer(array $o): array
          LD::contactPoint(array $c): array
          LD::searchAction(string $urlTemplate): array

          LD::script(bool $pretty = true): string   // <script>…</script>, and disables auto-injection
          LD::json(bool $pretty = true): string     // the document, no wrapper
          ```

          Any other schema.org type works through `LD::typeName([...])` —
          `LD::recipe([...])`, `LD::event([...])`,
          `LD::softwareApplication([...])`, and so on — via `__callStatic`.

          ```php
          LD::article([
              'headline'  => 'Announcing v2',
              'author'    => LD::ref('#person'),
              'image'     => LD::image('images/cover.webp'),
              'publisher' => LD::ref('#organization'),
          ]);
          ```

          ## META

          A `<head>` SEO / social metadata generator — the companion to
          `LD`, and the engine behind the [`meta:`](../config/#meta) block.
          Manual builders are always emitted, whether or not the config
          block is present, and are de-duplicated against whatever the page
          already writes by hand:

          ```php
          META::tag(string $name, ?string $content): void   // name= , or property= for an og:* key
          META::link(string $rel, string $href, array $attrs = []): void
          META::raw(string $html): void                       // a verbatim, already-valid tag line
          META::tags(string $html = ''): string               // the whole block, joined
          META::reset(): void
          ```

          ```php
          META::tag('twitter:image', 'https://example.com/card.png');
          META::link('icon', './favicon.svg', ['type' => 'image/svg+xml']);
          ```

          ## CACHE

          Persistent key/value store, SQLite-backed (`.cache.db`), survives
          incremental builds. Backs `SCRAPER` results and `CURL` cookie
          persistence — this site's own `kirigami_pkg_version()` (powering
          `/ecosystem/`) uses it directly for a 1-hour TTL on npm registry
          lookups.

          ```php
          CACHE::get(string $key): mixed;                  // null if missing/expired
          CACHE::set(string $key, mixed $val, int $ttl = 0): bool;   // ttl seconds, 0 = forever
          CACHE::delete(string $key): bool;
          CACHE::purge(): bool;                            // drop expired entries
          ```

          ## IMG

          The image autogenerator's engine — GD, with an Imagick fallback
          for HEIC/TIFF/BMP and vector formats (SVG/EPS/AI/PDF).

          ```php
          $img = new IMG(string $file);
          $img->width; $img->height;
          $img->resize(int $w, int $h = 0, bool $cover = false): self;   // contain by default; cover crops+fills
          $img->save(string $dest): self;                                // format from extension: jpg png gif webp avif
          $img->getRepresentativeColors(int $count = 5): string[];       // ['#rrggbb', …]

          IMG::asset(string $path, int $w = 0, int $h = 0, bool $cover = false): string;  // → generated file URL, relative to caller
          IMG::palette(string $path, int $colors = 5): string[];                          // CACHE-backed
          ```

          `asset()` resolves `$path` against `image.source`, writes into
          `image.dest` only when missing or stale, and names files
          `<name>-<W>w` / `-<H>h` / `-<W>x<H>[-cover].<format>`. The same
          call is reachable from Sass (`img-asset()`) and from markup
          (`<img asset>`) — see [config → image](../config/#image).

          ## FS

          ```php
          FS::dig(string $glob): iterable;                 // recursive glob, yields paths
          FS::getRelativePath(string $from, string $to): string;
          FS::phpFileInfo(string $file): object|false;     // parse first PHPDOC block → stdClass
          FS::getChildren(string $backtrace = ''): object[]; // render-only: child _index.php pages, sorted by @position then folder name
          FS::getBreadcrumb(string $backtrace = ''): object[]; // render-only: ancestor _index.php pages, opt-in via @breadcrumb true
          FS::rmdir(string $dir, bool $removeSelf = true): bool;
          FS::pathJoin(string ...$parts): string;          // URL-aware, resolves ..
          ```

          `getChildren()` and `getBreadcrumb()` are what builds the card
          grid and the "Docs / …" trail on every page in this section —
          `getChildren()` scans the calling page's own sub-folders for
          `_index.php`, sorted by an optional `@position` tag then name;
          `getBreadcrumb()` walks upward from the calling page, collecting
          ancestor `_index.php` pages, but only while each one — the caller
          included — carries `@breadcrumb true`. The first ancestor without
          it stops the walk and is excluded, so a trail can stop short of
          Home on purpose.

          ## STR

          ```php
          STR::htmlesc(string $s): string;
          STR::replaceTags(string $tag, string $html, callable $cb): string;   // engine behind registerTag()
          STR::parseHtmlAttributes(string $attrString): array;
          STR::trimIndent(string $s): string;
          STR::is_url(string $s): bool;
          STR::html_entities_decode(string $s): string;
          STR::shorthash(string $s): string;              // first 12 chars of sha-256
          STR::normalize(string $s): string;              // Unicode NFD + strip combining marks (é → e)
          STR::slug(string $s, string $sep = ''): string; // '' → compact id; '-' → hyphenated slug
          ```

          ## ARR

          ```php
          ARR::find_key(mixed $data, string $key): mixed;  // depth-first, first match at any depth, or null
          ```

          ## CURL

          Browser-like headers, cookie jar persisted at `.cookie.txt`. Needs
          `prepros.network: true`.

          ```php
          CURL::urlExists(string $url, ?string $mimeRegex = null): bool;   // HEAD, true on 2xx/3xx
          CURL::getInfo(string $url): array|false;
          CURL::getContents(string $url, ?string $dest = null, ?callable $onProgress = null): string|bool;
          ```

          ## SCRAPER

          Link-preview metadata — pulls JSON-LD / Open Graph / `<meta>` tags
          from a URL, cached indefinitely via `CACHE`. Needs
          `prepros.network: true`.

          ```php
          $m = SCRAPER::get(string $url): object|false;   // ->title ->description ->image ->label ->url
          ```

          ## OBF

          Light obfuscation only — contact data on a page, that kind of
          thing. Not encryption.

          ```php
          OBF::encode(mixed $obj): string;   // JSON → base64 → ROT-13 → gzip
          OBF::decode(string $s): mixed;
          ```

          ## STD

          Internal to the build runner — useful in a `kiri run` script that
          needs to end early with a custom result.

          ```php
          STD::succeed(array|string $props = []): void;   // exit 0, JSON to stdout
          STD::error(array|string $props = []): void;     // exit 1, JSON to stderr
          ```

          ## Aliases

          `libraries/aliases.inc.php` exposes every static method above as
          a plain function, named `<lowercase class>_<snake_case method>()` —
          each carries the same docblock, so editor hover shows the full
          signature. A sample, not the full list:

          ```php
          md_to_html($md)                    // MD::toHtml()
          html_format($html)                 // HTML::format()
          yaml_load_file($path)               // YAML::loadFile()
          schema($schema)                     // new SCHEMA()  — plus schema_validate($schema, $data, $errors)
          cache_get() / cache_set() / cache_delete() / cache_purge()
          img_asset($path, $w, $h, $cover)    // IMG::asset(), URL relative to the calling file
          fs_dig() / fs_get_relative_path() / fs_get_children() / fs_get_breadcrumb()
          str_htmlesc() str_slug() str_shorthash()
          arr_find_key($data, $key)
          curl_get_contents() / curl_get_info() / curl_url_exists()
          scraper_get($url)
          obf_encode() / obf_decode()
          std_succeed() / std_error()
          meta_tag() / meta_link() / meta_raw() / meta_tags()
          ld_add() / ld_organization() / ld_article() / ld_script()
          prepros_render() prepros_mount() prepros_backtrace_file()
          register_tag() / register_hook()   // = prepros_register_tag / prepros_register_hook
          md_register_plugin() md_register_emoji()
          ```

          ## Bundled polyfill

          `ext-intl` isn't in the WASM build, so a `Normalizer` polyfill is
          autoloaded (`Normalizer::normalize()` / `isNormalized()` +
          `NFC`/`NFD`/`NFKC`/`NFKD` constants) — prefer `STR::normalize()`
          in your own code; the polyfill exists for third-party snippets
          that expect the real extension.
        </markdown>
    </div>
</section>
