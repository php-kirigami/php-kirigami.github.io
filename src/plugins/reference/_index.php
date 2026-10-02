<?php
/**
 * @title      Plugin reference
 * @section    plugins
 * @type       docs
 * @abstract   Every option, tag, cache file and style hook of the official
 *             plugins, in one place.
 * @breadcrumb true
 */
?>

<markdown>
  ## Before you start

  Every plugin is declared under `plugins:` in `kirigami.yaml`, with its options
  under `options:`. `kiri install <name>` installs it and prints the entry to
  paste.

  ```yaml
  plugins:
    - name: "@kirigami/plugin-highlight"
      active: true
      options: {}
  ```

  - Options are checked against the plugin's own schema before it loads: an
    unknown key or a bad value stops the build with a clear message. The
    schema is also wired into `kirigami.schema.json`, so VS Code completes
    and validates `options:` as you type once `name:` is set.
  - Most plugins need an **`esbuild` task** in `kirigami.yaml`, since their
    browser script is bundled into it. Without one, the build warns and skips
    the script. The ones that need it are noted below.
  - `style: false` is shared by every plugin that ships styles: it skips the
    default CSS so you can write your own on the same class names.
  - Browser-side and PHP-side work are both covered by the
    [SDK hooks](../../docs/sdk/) the plugin uses; see
    [Writing a plugin](../authoring/) to write your own.

  | Plugin | Needs `esbuild` | Needs `prepros.network` | Cache to commit |
  |---|---|---|---|
  | [highlight](#highlight) | for the copy button | no | none |
  | [extlink](#extlink) | no | yes | `_data/extlink/`, `assets/extlink/`, `assets/images/extlink/` |
  | [embed](#embed) | yes | no | none |
  | [player](#player) | yes | no | `_data/player/` |
  | [clip](#clip) | yes | no | `_data/clip/` |
  | [educ](#educ) | yes | for `<doclink>` | `_data/doclink/`, `assets/images/doclink/` |

  ## highlight

  Build-time syntax highlighting. highlight.js is a dev dependency of your
  build only: the deployed site gets class names in the HTML, the CSS that
  styles them and, with the copy button, one small script.

  | Option | Type | Default | Description |
  |---|---|---|---|
  | `languages` | list or `"all"` | 12 common languages | Languages to register. `"all"` loads the full build (about 190, slower). An unknown name fails the build. |
  | `theme` | `auto`, `dark`, `light`, `none` | `auto` | Which theme stylesheet to append. `none` skips the palette; the font and the copy button are controlled separately. |
  | `autodetect` | boolean | `true` | Guess the language of blocks that have none, among the registered ones. |
  | `embedFont` | boolean | `true` | Append the embedded JetBrains Mono `@font-face` (about 39 KB, base64). |
  | `copyButton` | boolean | `true` | A hover "Copy" button on every block. Needs an `esbuild` task. |
  | `lineNumbers` | boolean | `false` | Number the lines with a CSS counter, so copying never includes them. |
  | `tag` | boolean | `true` | Register the `<highlight lang="…">` authoring tag. |

  The default languages are PHP, JavaScript, TypeScript, Bash, JSON, YAML, CSS,
  SCSS, XML (which covers HTML), Markdown, SQL and Python. Aliases include
  `html`, `htm` and `svg` for `xml`, `js` and `jsx` for `javascript`, `ts` and
  `tsx` for `typescript`, `yml`, `sh`, `zsh`, `md` and `py`. A block whose
  language isn't registered is left as escaped, uncoloured code.

  **Control.** Skip a page with `@highlight false` (also `no`, `off`, `0`) in
  its first PHPDOC block. Skip a block by giving its `code` a `nohighlight`
  class, or by tagging it plain text: `plaintext`, `text` or `none` as the
  fence's language. One block can opt in or out of line numbers with a
  `line-numbers` or `no-line-numbers` class, or `lines="true"` / `"false"` on
  the tag.

  **The tag.** `<highlight lang="js">` is an alternative to a fence, handy in a
  PHP layout. `lang` (also `language` or `l`) is optional. A literal closing
  `</highlight>` inside the code ends the block early: use a fence for that.

  **Theming.** `auto` emits the light palette, then the dark one under
  `prefers-color-scheme` and `[data-theme="dark"]`, like the
  [design system](../../docs/canva/). With `theme: none`, include the mixin
  yourself:

  ```scss
  @use "@kirigami/plugin-highlight/highlight" as hljs;

  @include hljs.dark();                                   // a built-in palette…
  @include hljs.light((accent: #0b6bcb));                 // …with overrides…
  @include hljs.theme((                                   // …or one of your own
      bg: #10131a, surface: #171b24, surface-2: #141821, border: #2b3140,
      ink: #e8ecf1, ink-muted: #93a0b4, accent: #7cc4ff, accent-soft: #1d2b3a,
  ));
  ```

  A palette needs eight base colours (`bg`, `surface`, `surface-2`, `border`,
  `ink`, `ink-muted`, `accent`, `accent-soft`); the syntax colours are derived
  from them, and any of `keyword`, `string`, `number`, `title`, `built-in`,
  `type`, `variable`, `attr`, `symbol`, `meta` or `punctuation` can be pinned in
  the same map. The code font is `$code-font` on the same `@use`. The copy
  button's layout is always appended; its colours come from the mixin you
  include.

  **The copy button** wraps each `pre > code.hljs` in a `.hljs-copy-wrap` and
  adds a `.hljs-copy` button. It copies the displayed text minus one trailing
  newline, shows "Copied" on success, and "Press ⌘C" if the clipboard refuses,
  then resets after 1.6 seconds. It needs `navigator.clipboard.writeText`.

  **How it runs.** The plugin hooks `prepros:html` (it finds each code block,
  strips the shared indentation, and writes the highlighted markup back),
  `sass:after`, `esbuild:after` and `prepros:php`. Fenced blocks can therefore
  be indented in your source without that indentation showing up.

  ## extlink

  A preview card for an external link: title, description, image and site name,
  scraped once at build time.

  | Option | Type | Default | Description |
  |---|---|---|---|
  | `style` | boolean | `true` | Append the default `.extlink` card styles. |

  ```html
  <extlink src="https://example.com/article">
  <extlink src="https://example.com/article" title="A better title" description="…" image="https://example.com/cover.jpg" label="Example" class="featured">
  ```

  ```
  {% extlink https://example.com/article %}
  {% extlink https://example.com/article "A better title" %}
  ```

  - Any scraped field can be overridden on the tag. In Markdown, only the
    title can be overridden inline; the other overrides need the full tag.
  - With no title at all (scraped or given), the tag throws a build error
    naming the `src`: pass `title="…"`. A bad URL in the tag throws; in the
    shortcode it becomes an HTML comment.
  - The card is a link with `target="_blank"` and
    `rel="noopener noreferrer"`. A description over 160 characters is cut to
    159 plus an ellipsis, and a missing label falls back to the host without
    `www.`.
  - No browser JavaScript is involved. Needs `prepros.network: true`.

  **Cache.** Each URL is keyed by a short hash of its address.

  | File | Role |
  |---|---|
  | `_data/extlink/<hash>.json` | The scraped metadata. Read when present; no expiry. Delete it to refresh. |
  | `assets/extlink/<hash>.jpg` | The downloaded original, kept as an archive. |
  | `<image.source>/extlink/<hash>.<format>` | A 200 by 200 square crop (`assets/images/extlink/` by default). |
  | `<root>/<image.dest>/extlink/<hash>.<format>` | The published card image; its presence is the reuse check. |

  Commit them to keep them. In a clean checkout the files alone don't remove
  every network call. Changing an `image` override doesn't refresh an existing
  published image, since the key is the page URL: delete it to regenerate.

  **Styling.** `.extlink`, `.extlink__image`, `.extlink__body`,
  `.extlink__title`, `.extlink__desc` and `.extlink__site`, all themed from the
  design tokens.

  ## embed

  YouTube and Vimeo video cards, light until clicked.

  | Option | Type | Default | Description |
  |---|---|---|---|
  | `style` | boolean | `true` | Append the default `.embed` card styles. |
  | `maxWidth` | string | `"40rem"` | Cap a card's width (and, through the ratio, its height): any CSS length, or `"none"`. |
  | `forcedAspectRatio` | string | `""` | Pin every card to one shape (`"16 / 9"`, `"1 / 1"`…) instead of each video's real ratio, for a uniform grid. |

  `maxWidth` and `forcedAspectRatio` are ignored when `style` is `false`.

  ```html
  <youtube id="dQw4w9WgXcQ">
  <vimeo id="1084537">
  ```

  ```
  {% youtube dQw4w9WgXcQ %}
  {% vimeo 1084537 %}
  ```

  The tag is deliberately non-closing, like `img`. Only YouTube and Vimeo are
  implemented; another provider needs your own observer registration.

  **How it behaves in the browser**
  1. The tag is swapped at once for a `.embed` placeholder at 16:9, so the
     space is reserved.
  2. The video's oEmbed data comes from `localStorage`
     (`kirigami-embed:<provider>:<id>`) if a visit already resolved it, and is
     fetched from the provider otherwise.
  3. The thumbnail and title are patched in, and the ratio becomes the video's
     real one. Nothing is stretched: the width cap keeps a narrow video from
     looking oversized.
  4. Only a click on the play button loads the real player (`youtube-nocookie.com`
     or `player.vimeo.com`). The metadata and the thumbnail load earlier.

  **Failures.** The browser accepts YouTube ids of 10 to 12 word or hyphen
  characters and Vimeo ids made only of digits; anything else leaves the tag
  as it is. A request that fails logs to the console and leaves the play button
  usable without metadata. The plugin has no proxy, timeout or retry, and
  requests follow the browser's network and CORS rules. If `localStorage` can't
  be read (some restricted contexts), the metadata isn't fetched.

  With `style: true`, a `.generated-vars.scss` file is written in the plugin's
  install directory to carry the sizing options, which therefore has to be
  writable. **Styling:** `.embed`, `.embed__title`, `.embed__play`,
  `.embed__player`.

  ## player

  Audio players and playlists with the waveform drawn at build time.

  | Option | Type | Default | Description |
  |---|---|---|---|
  | `style` | boolean | `true` | Append the default `.player` and `.playlist` styles. |
  | `samples` | integer | `1000` | Points in the baked waveform (the SVG's width). |
  | `cover` | boolean | `true` | Extract the embedded MP3 cover art and publish it through the image pipeline. |
  | `coverSize` | integer | `240` | Edge of the generated square cover, in pixels. |

  ```html
  <player src="../audio/track.mp3">
  <player src="../audio/track.mp3" title="Live at home" artist="Me">
  <playlist src="../audio/set.m3u">
  ```

  ```
  {% player ../audio/track.mp3 %}
  {% playlist ../audio/set.m3u %}
  ```

  - `src` is relative to the page, or from the site root when it starts with
    `/`. `title` and `artist` override the file's own tags.
  - A playlist is a plain `.m3u` or `.m3u8`. Entry paths are relative to the
    playlist; `#EXTINF:<seconds>,Artist - Title` fills the title and artist of
    tracks with no tags of their own. **URL entries are skipped with a
    warning**, since only local files can be analysed. Each track is a regular
    player, and the next one starts when one ends.
  - Decoded formats: MP3, WAV, AIFF, FLAC, Ogg Vorbis, Opus, M4A/AAC and WebM,
    through `@kirigami/audiowaveform-wasm`. **ID3 tags and cover art are read
    from MP3 only.**
  - A missing or undecodable file is a build warning replaced by an HTML
    comment: it never fails the build.

  The waveform is one SVG path drawn twice: a muted copy, and an accent copy
  revealed up to the playback position with `clip-path`.

  **Cache.** `<root>/_data/player/<content-hash>.json` holds the metadata and
  the SVG for each file. The key is the file's **content**, so a fresh checkout
  (CI included) hits it. Commit the folder. Changing `samples` regenerates the
  entries. A cover found in an MP3 is written to `<image.source>/player/` and
  published like any `img asset`: a square crop in your `image.format`, for
  example `images/player/<hash>-240x240-cover.webp`.

  **Styling.** `.player`, `.player__cover`, `.player__toggle`,
  `.player__title`, `.player__artist`, `.player__wave` (with `.player__svg` and
  `.player__svg--progress`), `.player__times` and `.playlist__tracks`. The
  script sets `.is-playing` on a playing player and a `--progress` property
  from 0 to 1.

  ## clip

  Local video with an automatically chosen poster.

  | Option | Type | Default | Description |
  |---|---|---|---|
  | `style` | boolean | `true` | Append the default `.clip` styles. |
  | `posterWidth` | integer | `960` | Width of the poster in pixels; the height follows the video. |
  | `samples` | integer | `24` | Timestamps sampled across the video to choose the poster. More is slower to build; the result is cached. |

  ```html
  <clip src="../video/movie.mp4">
  <clip src="../video/movie.mp4" title="Opening night">
  <inline-clip src="../video/loop.mp4" class="hero">
  ```

  ```
  {% clip ../video/movie.mp4 %}
  {% inline-clip ../video/loop.mp4 %}
  ```

  - **Use MP4 (H.264) or WebM** so browsers can play the file. `bestframe` also
    reads Matroska, AVI, Ogg, MPEG streams and older codecs to choose a poster,
    but warns for a type most browsers won't play. A video it can't decode is
    a warning and an HTML comment, never a failed build.
  - `title` overrides the title, which otherwise comes from the video's own tag,
    then from its file name (tidied: underscores, a leading track number).
  - A **vertical** video sits in a default 16:9 box with the picture centred.
    Change that box with `--clip-portrait-ratio` and the card's maximum width
    with `--clip-max-width` (default `40rem`).
  - Without JavaScript, the play button is a plain link to the file.
  - On click, the card becomes a `video` with controls that starts playing.
    **One clip plays at a time.**

  **`<inline-clip>`** is for decoration: one `video` element that plays by
  itself, **muted, in a loop, with no controls**. It needs no JavaScript, gets
  its real width and height so the page doesn't jump, and shows the poster until
  the first frame. A vertical video keeps its own shape; cap it with
  `--inline-clip-max-width`. The script pauses a loop while it's off-screen and
  leaves it on its poster for visitors who prefer reduced motion. For the
  smoothest loop, encode it without an audio track and with the last frame
  matching the first.

  **Cache.** Choosing a poster is slow (tens of seconds for a long video), so it
  is done once. `<root>/_data/clip/<id>.json` keeps the frame time, its score,
  the duration, the size and the container tags. The `<id>` is the file's size
  plus a hash of its first and last megabyte: cheap on a huge file, and
  content-based, so CI hits the cache. Commit the folder. The chosen frame goes
  to `<image.source>/clip/<id>.jpg` and is published through the image pipeline
  (`images/clip/<id>-960w.webp`). Changing `posterWidth` or `samples`
  regenerates the entries.

  **Styling.** `.clip` (with `.clip--portrait` and `.is-playing`),
  `.clip__poster`, `.clip__play`, `.clip__icon`, `.clip__title`,
  `.clip__duration`, `.clip__video` and `.inline-clip`.

  ## educ

  Course components for Kiridoc-style sites: checklists, callout bubbles, link
  cards, colour pills, media files, quotes, tool cards and CodePen embeds. Its options and the
  components themselves are described on the [Plugins](../#educ) page.

  | Option | Type | Default | Description |
  |---|---|---|---|
  | `style` | boolean | `true` | Append the default component styles to every `sass` task. |

  A few facts that matter when you author with it:

  - `<intlink>` reads the target page's header: `@title` is the card title,
    `@abstract` (or `@description`) the description, `@label` (or `@code`) a
    small caption and `@image` (or `@ogimage`, `@meta_image`) the picture. With
    no image, the card falls back to the site's default Open Graph image
    (`seo.image`, then `seo.logo`, then the `image` or `ogimage` keys of
    `kirigami:`). The target must be a page folder, or the build fails.
  - `<doclink>` shows the host's favicon, looked up once (the icons the home page
    declares, then `/favicon.ico`, then a favicon service), saved as a 64 px
    webp and shared by every link to that host. A host with no icon gets a
    generic badge; a local `.zip` gets a zip badge and any other local link a
    file badge.
  - `<quote photo>` and `<tool image>` are cropped square (112 px and 200 px) and
    published as webp: commit `assets/images/quote/` and `assets/images/tool/`.
    A path can be relative to the page (`./x`), from the site root (`/x`), or a
    URL.
  - `<codepen>` takes `id` (the pen's hash), `user` (default `anonymous`),
    `height` in pixels (default 400) and `tab` (default `result`). The shortcode
    keeps the argument order of php-prepros' own `codepen` (id, user, height) and
    adds the tab at the end.
  - `<color>` copies its code on click and reads "Copied!", or "Copié!" when the
    page's `lang` starts with `fr`.
  - `<medialink>` takes `src` (as linked from the published page), an optional
    title as its content (default: the file name), `addr="false"` to hide the
    URL field and `class`. The shortcode is `{% medialink src Title %}`; a
    trailing `false` after a quoted title hides the URL field. The glyph follows
    the extension (image, svg, audio, video, zip, pdf, other). The URL field and
    the copied link are absolute; the button labels are French when the page's
    `lang` starts with `fr`. Without JavaScript the download button is a plain
    `download` link.
</markdown>
