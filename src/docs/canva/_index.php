<?php
/**
 * @title    Design system (canva)
 * @section  docs
 * @type     docs
 * @group    Extend
 * @position 24
 * @abstract The design tokens, Sass helpers, ready-made components and small
 *           browser scripts that Kirigami sites share.
 */
?>

<markdown>
  ## Overview

  `@kirigami/canva` is the design layer the starter templates and this very
  site are built on. It gives a site:

  - a **token set** (colours, fonts, sizes) published as CSS custom properties,
    with an optional **dark theme**;
  - an **icon system** that recolours inline SVGs at build time;
  - a few pure **Sass functions**, long-form **prose styles** and small UI
    **components**;
  - tiny **browser scripts** for the theme toggle, reveal-on-scroll and
    authoring tags.

  The `.scss` entries are compiled by the engine's `sass` task, which supplies
  the native functions the token layer relies on (`inline-file()` and the
  `font-*()` family). For the visual identity of this site, see
  [Design](../../design/).

  ```bash
  npm install @kirigami/canva
  ```

  There is no barrel export: import each entry by subpath.

  ```js
  import { create } from '@kirigami/canva/dom';
  import { documentReady } from '@kirigami/canva/helpers';
  ```

  ```scss
  @use '@kirigami/canva/utils' as *;
  @use '@kirigami/canva/conf';
  ```

  Through the engine's `sass` task, a missing subpath is retried with a
  `styles/` prefix, so `@kirigami/canva/conf` and `@kirigami/canva/styles/conf`
  are the same file. Node's own resolver needs the `styles/` segment. Outside
  Kirigami, enable Dart Sass's `NodePackageImporter` and write
  `@use 'pkg:@kirigami/canva/styles/utils'`.

  | Import | File |
  |---|---|
  | `@kirigami/canva/<name>` | `dist/scripts/<name>.js` (`dom`, `helpers`, `theme`, `observer`, `reveal`, `components/burger`) |
  | `@kirigami/canva/styles/<name>` | `dist/styles/<name>.scss` (`conf`, `utils`, `prose`, `main`, `lightswitch`) |

  ## Tokens: `conf`

  `conf` declares the palette, the typography and the icon set as `!default`
  Sass variables, then:

  - emits an `@font-face` for every entry of the `$fonts` map, with the font
  embedded in the stylesheet;
  - mirrors every token onto `:root` as a CSS custom property (`--bg`,
  `--accent`, `--font-size`, `--transition-duration`…);
  - builds one `--icon-<name>` property per icon, running the SVG through
  `apply-colors()` and `svg-url()` so icons pick up the current palette;
  - ships a minimal reset, smooth scrolling with
  `scroll-padding-top: var(--scroll-top)`, a fluid root font size, themed
  native scrollbars and `.is-busy` / `.is-working` cursor states.

  Override any token with `@forward ... with (...)` from a partial of your own:

  ```scss
  // src/styles/partials/_conf.scss
  @forward "@kirigami/canva/conf" with (
      $bg:           #f5f8f6,
      $surface:      #e7f0ea,
      $ink:          #263b30,
      $accent:       #c08a2e,
      $font-body:    "Roboto Flex",
      $font-heading: "Quicksand",
      $fonts: (
          "Roboto Flex": "assets/fonts/roboto-flex.woff2",
          "Quicksand":   "assets/fonts/quicksand.woff2",
      ),
  );
  ```

  The colour tokens you will meet everywhere are `$bg`, `$surface`,
  `$surface-2`, `$border`, `$ink`, `$ink-muted`, `$accent` and `$accent-soft`,
  plus the logo colours (`$logo-ink`, `$logo-fold-light`, `$logo-fold-mid`).

  ### Type

  The root font size is fluid and bounded on both ends:
  `clamp(var(--font-min), var(--font-resp), var(--font-base))`. `--font-resp`
  scales with the viewport, `$font-base` caps it, and `$font-min` (default 17)
  keeps a narrow screen from collapsing every `rem`.

  - A **variable font with an `ital` axis** gets two `@font-face` blocks, one
    normal and one italic, so italic text uses the font's real italic. This
    needs `@kirigami/kirigami` 3.0.0; with an older engine, a single face is
    emitted.
  - `$font-mono` / `--font-mono` defines the monospace stack once, and the
    `prose` and `main` partials use it.

  ## Dark theme

  `conf` is single-theme until you opt in with two tokens.

  | Token | Values | Effect |
  |---|---|---|
  | `$dark` | `false` (default), `true`, or a palette map | `true` enables the built-in dark palette. A map starts from that palette and overrides only the keys you give. |
  | `$theme` | `auto`, `class`, `both` (default) | `auto` follows the OS (`prefers-color-scheme`); `class` only reacts to `data-theme` on `<html>`; `both` follows the OS but lets `data-theme="light"` or `"dark"` force either way. |

  ```scss
  @forward "@kirigami/canva/conf" with (
      $bg:     #f7f8f7,
      $ink:    #2f3640,
      $accent: #c7402c,
      $dark: (
          bg:     #14181b,
          ink:    #e7ecef,
          accent: #ff6b52,
      ),
  );
  ```

  - A second copy of every palette property (and every recoloured `--icon-*`)
    is emitted for dark mode. The built-in dark palette is exposed as `$dark-*`
    variables, tunable like the light ones.
  - A short `background-color`, `background-image` and `color` transition
    (`var(--transition-duration)`, about 200 ms) eases the switch instead of
    snapping. It is skipped on first paint and under
    `prefers-reduced-motion`. A component that sets its own `transition`
    shorthand replaces it: list those properties again there.
  - A manual toggle needs `$theme: class` or `both`, because it drives
    `data-theme`. For a first paint without a flash, the managed `<head>`
    injects a small guard that reads `kirigami-theme` from `localStorage`
    before the stylesheet.

  ## Sass helpers: `utils`

  Dependency-free functions, loaded with `@use ... as *`:

  | Function | Purpose |
  |---|---|
  | `wash($bg, $base, $amount)` | Mix `$base` into `$bg`: tint or shade a colour toward the background. |
  | `hex6($c)`, `hexbin($c)` | A colour as `#rrggbb`, or `rrggbb` without the `#` (alpha dropped). |
  | `str-replace($string, $search, $replace: "")` | Recursive replace-all. |
  | `url-encode($string)` | Percent-encode `% < > # "` for use in a `url()`. |
  | `svg-url($svg)` | Wrap raw SVG markup in a `url("data:image/svg+xml,…")`. |
  | `apply-colors($svg, $colors)` | Replace each `%name%` placeholder of an SVG string with the mapped colour. |

  ## Long-form text: `prose`

  Base typography for rendered Markdown. It follows the light and dark palette
  by itself.

  ```scss
  // the ready-made wrapper class
  @use '@kirigami/canva/prose';              // defines .prose { … }

  // …or only the mixin, on a selector of your own
  @use '@kirigami/canva/prose' as prose with ($emit-class: false);
  .page-about #main { @include prose.prose($measure: 46rem); }
  ```

  The mixin is `prose($measure: 42rem, $flow: 1.5em)`: `$measure` becomes the
  `max-width` (`none` skips it) and `$flow` the vertical rhythm. At runtime,
  tune `--prose-measure`, `--prose-flow` and `--prose-radius`.

  It styles headings, lists, quotes, rules, tables, code, images and figures,
  definition lists, `<details>`, inline `mark`, `sub`, `sup` and `abbr`, and the
  GitHub-flavoured output of the `MD` class: task lists, `.markdown-alert*`
  boxes and `.footnotes`. Tables scroll inside themselves rather than widening
  the page. The code-block theme of
  [`@kirigami/plugin-highlight`](../../plugins/#highlight) layers on top.

  ## Components: `main`

  Five small patterns that kept being rewritten identically across sites. Opt
  in with `@use '@kirigami/canva/main'`.

  | Class | Purpose |
  |---|---|
  | `.breadcrumb` | A trail of links ending on the current page. Pairs with `FS::getBreadcrumb()`. |
  | `.docs-toc` | An inline quick-jump list of anchors, for a long reference page. |
  | `.table-wrap`, `.table` | A data table. Wrap it in `.table-wrap` to scroll sideways; `.table__num` sets a tabular-figure column (a version number, say). |
  | `.badge`, `.badge--muted` | A small status or licence tag: filled with the accent, or outlined and neutral. |
  | `.palette`, `.palette--compact` | A row of colour chips, one `li` per swatch, for `IMG::palette()` or `colors()` output. `--compact` caps it at 20 rem. |

  ## The light switch: `lightswitch`

  An animated theme toggle: a pill whose pin slides and morphs from a sun disc
  to a crescent moon. The morph is a real CSS `d` transition between two SVG
  paths, so the browser interpolates it point by point. It pairs with the
  `theme` script, which already keeps `data-theme-state` on the element, so
  there is no extra JavaScript.

  ```scss
  @use '@kirigami/canva/lightswitch';
  ```

  ```html
  <button type="button" data-theme-toggle class="lightswitch" aria-label="Toggle dark mode">
      <svg viewBox="0 0 55 55" aria-hidden="true">
          <path d="M55 27.5C55 42.6878 42.6878 55 27.5 55C12.3122 55 0 42.6878 0 27.5C0 12.3122 12.3122 0 27.5 0C42.6878 0 55 12.3122 55 27.5Z"/>
      </svg>
  </button>
  ```

  The path's `d` is the sun shape, kept as a fallback for browsers that can't
  animate `d` yet. Colours come from the tokens (`--surface` for the track,
  `--accent` for the pin), so there is nothing to override for a themed look.

  | Custom property | Default | Purpose |
  |---|---|---|
  | `--lightswitch-size` | `1.75rem` | Pin diameter; the track is twice as wide. |
  | `--lightswitch-bg` | `var(--surface)` | Track colour. |
  | `--lightswitch-pin` | `var(--accent)` | Pin colour. |

  ## Scripts

  ES modules for the browser (`es2022`). Each source file compiles to its own
  file, so import them one at a time. `dom`, `theme`, `observer` and `reveal`
  touch browser globals when imported: don't load them in a plain Node
  process. `helpers` can be imported in Node for `dedent`.

  ### `dom`

  | Export | Description |
  |---|---|
  | `create(tag, classname?, content?, attrs?)` | Create an element, optionally setting its class, its `innerHTML` and attributes from an object. |
  | `el.create(...)` | The same, on any `HTMLElement`, but it also appends the new element to `el`. Importing the module patches `HTMLElement.prototype`. |

  ### `helpers`

  | Export | Description |
  |---|---|
  | `busy(promise or promises)` | Put `is-busy` on `<html>` until the promises settle, then remove it. |
  | `working(promise or promises)` | The same, with `is-working`. |
  | `preloadImage(url)` | Resolves when the image has loaded (immediately if cached), rejects on error. |
  | `documentReady(cb?)` | Resolves on `DOMContentLoaded`, or at once if the document is parsed. |
  | `dedent(str)` | Strip the whitespace prefix shared by every non-blank line, keeping relative indentation; trim leading blank lines and trailing space. Pure string code, no DOM. |

  `busy` and `working` are not reference-counted across separate calls: the
  first call to finish can remove the class while another is still pending.

  ### `theme`

  A manual light and dark switch for the dark theme. It writes `data-theme` on
  `<html>` and remembers the choice in `localStorage` under `kirigami-theme`.
  Importing it re-applies the stored preference at once and, on
  `DOMContentLoaded`, wires every `[data-theme-toggle]` control.

  | Export | Description |
  |---|---|
  | `getTheme()` | The stored preference: `'auto'`, `'light'` or `'dark'` (`'auto'` when nothing is stored). |
  | `resolvedTheme()` | The theme on screen, `'auto'` resolved against `prefers-color-scheme`. |
  | `setTheme(pref)` | Store and apply a preference; `'auto'` lets the OS decide. Returns the resolved theme. |
  | `toggleTheme()` | Flip between light and dark. |
  | `initTheme()` | Re-apply the stored preference (it runs on import). |
  | `bindToggles(target = document)` | Wire the toggles under `target`; idempotent. Call it again after injecting toggles later. |

  Declarative toggles need no script of your own, only markup:

  ```html
  <button data-theme-toggle aria-label="Toggle theme">🌗</button>   <!-- flips light and dark -->
  <button data-theme-toggle="dark">Dark</button>                    <!-- forces a preference -->
  <button data-theme-toggle="light">Light</button>
  <button data-theme-toggle="auto">System</button>
  ```

  Each toggle gets `data-theme-state="light|dark"` and, on a real control,
  `aria-pressed`: style them from those. Every change, a click or an OS switch
  while in `auto`, fires a `canva:themechange` event on `window`:

  ```js
  addEventListener('canva:themechange', (e) => {
      e.detail;   // { theme: 'light' | 'dark', preference: 'auto' | 'light' | 'dark' }
  });
  ```

  If storage can't be read, `getTheme()` falls back to `auto` without
  validating arbitrary stored strings. `setTheme()` still applies the change
  when it can't save it.

  ### `reveal`

  Adds `is-in` to each `[data-reveal]` element the first time it scrolls into
  view, then stops watching it. Pair it with a rule that hides `[data-reveal]`
  and shows `.is-in`, gated on the `js` class that the managed `<head>` puts on
  `<html>`, so the content stays visible if the bundle never loads:

  ```scss
  @media (prefers-reduced-motion: no-preference) {
      .js [data-reveal]       { opacity: 0; transform: translateY(14px);
                                transition: opacity .5s ease, transform .5s ease; }
      .js [data-reveal].is-in { opacity: 1; transform: none; }
  }
  ```

  `reveal(target = document)` reveals the matching descendants of `target`
  (not `target` itself) and skips those already marked, so call it again after
  inserting content. With `prefers-reduced-motion: reduce`, or without
  `IntersectionObserver`, everything is revealed at once, and a 1.5-second
  safety timeout reveals whatever is still hidden.

  ### `observer`

  A tiny engine for **non-closing authoring tags**. Register a tag name and a
  handler: every matching tag already in the page, and every one added later,
  is handed to the handler and replaced by what it returns. This is the seam
  the plugins use for shortcuts such as `<youtube>`.

  ```js
  import { register } from '@kirigami/canva/observer';

  register('youtube', (el) => {
      const id = el.getAttribute('id') || '';
      if (!/^[\w-]{11}$/.test(id)) return;
      const iframe = document.createElement('iframe');
      iframe.src = `https://www.youtube-nocookie.com/embed/${id}`;
      iframe.loading = 'lazy';
      iframe.allowFullscreen = true;
      return iframe;
  });
  ```

  Browsers parse an unknown tag as an ordinary element, so whatever follows an
  unclosed tag ends up nested inside it. With `voidLike` (the default), those
  stray children are lifted back out as siblings before the tag is swapped.
  Set `voidLike: false` when the tag really wraps content you want to keep.

  | Export | Description |
  |---|---|
  | `register(tag, fn, options?)` | Register `fn` for `<tag>`; returns a function that removes the registration. |
  | `scan(root?)` | Sweep `root` (default `document`) by hand. Rarely needed. |
  | `start()`, `stop()` | Start or disconnect the `MutationObserver`. Handlers stay registered. |

  What `fn(el)` returns decides the outcome: an HTML string, a `Node`, a
  `NodeList` or an array replaces the tag; `null`, `false` or `""` removes it;
  `undefined` leaves it in place, for a tag that only needs a side effect.

  Good to know: handlers are synchronous (a returned Promise isn't awaited),
  and a source element is marked as seen before its handler runs, so throwing
  doesn't schedule a retry. Only child additions are observed, not attribute
  changes. Each replacement fires a `canva:observed` event on `document`.
  **The observer doesn't sanitise**: a handler that builds HTML from authoring
  attributes must validate or escape them.

  ### `components/burger`

  An empty class, a placeholder for a shared navigation toggle. It does nothing
  yet.
</markdown>
