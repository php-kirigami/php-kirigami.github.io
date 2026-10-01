<?php
/**
 * @title    struct-walker
 * @section  docs
 * @type     docs
 * @group    Extend
 * @position 22
 * @abstract The recursive YAML and JSON walker that resolves nested file
 *           references in kirigami.yaml, and turns assets into data URIs.
 */
?>

<markdown>
  ## Overview

  `@kirigami/struct-walker` reads a YAML or JSON file and walks every value of
  the result. A string that points at another YAML or JSON file is replaced
  by that file's content, recursively; with an option, a string that points
  at an asset (an image, a font, audio, video…) becomes a **data URI**. The
  engine uses it to read [`kirigami.yaml`](../config/), which is why a
  configuration can be split across files.

  ```bash
  npm install @kirigami/struct-walker
  ```

  ## Usage

  ```js
  import { walkFile } from '@kirigami/struct-walker';

  // Resolve nested YAML / JSON references only.
  const config = await walkFile('./config/main.yaml');

  // Also embed asset files as data URIs.
  const theme = await walkFile('./theme/index.yaml', true);
  ```

  ## How a value is resolved

  | The string… | Result |
  |---|---|
  | has no file extension | Kept as is. |
  | ends in `.yml`, `.yaml` or `.json`, and the file exists | Replaced by that file's parsed content (recursive). |
  | ends in a known asset extension, `resolveAssets` is `true` and the file exists | Replaced by a data URI. |
  | names a missing file, or has an unknown extension | Kept as is. |

  Every reference is resolved **relative to its own file's directory**, not
  to the root file: a file in `config/db/` that references
  `./credentials.yaml` reads `config/db/credentials.yaml`, wherever the root
  file lives.

  ```yaml
  # config/main.yaml
  app: My App
  database: ./database.yaml
  theme: ./theme/index.yaml
  ```

  ```yaml
  # config/theme/index.yaml
  logo: ./logo.svg       # becomes data:image/svg+xml;charset=utf-8,...
  font: ./font.woff2     # becomes data:font/woff2;base64,...
  ```

  Text formats such as SVG and CSS are percent-encoded; binary formats are
  base64.

  ## API

  ### `walkFile(filePath, resolveAssets = false)`

  Returns a promise of the fully resolved value (an object, array, string,
  number, boolean or `null`).

  - It rejects on a **circular** reference (`A` to `B` to `A`). Sibling
    references are fine and are loaded independently; there is no shared file
    cache.
  - It rejects on a missing or unreadable root file, invalid JSON or YAML, and
    empty YAML. A missing *nested* reference stays its original string.
  - Strings are trimmed for the lookup and extensions are compared without
    case; an unresolved string keeps its original whitespace. Object keys are
    never resolved.
  - The root file is parsed as JSON only for `.json`, otherwise as YAML.
    Nested references are limited to `.json`, `.yaml` and `.yml`, even when
    `resolveAssets` is off.

  ### `fileToDataUri(absolutePath)`

  Converts one file to a data URI. The MIME type comes from the file's magic
  bytes first, then its extension, with `application/octet-stream` as a last
  resort.

  ## Good to know

  - **No project boundary.** Absolute paths and `..` can read outside the
    starting directory, and nothing is fetched from the network. Don't walk
    untrusted files.
  - Cycle detection compares resolved path strings, not symlink targets, and
    doesn't catch cycles made by YAML aliases.
  - This is the Node side. YAML read by PHP templates goes through PHP's
    native YAML extension (`YAML::`), which has its own implicit scalar
    rules: don't infer one's behaviour from the other.
</markdown>
