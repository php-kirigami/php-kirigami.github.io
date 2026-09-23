<?php
/**
 * @title      Writing a plugin
 * @section    plugins
 * @type       doc
 * @abstract   Package a tag/hook registration as an installable plugin —
 *             from an empty folder to something `kiri install` can find.
 * @breadcrumb true
 */
?>

<section class="section wrap">
    <div class="prose">
        <markdown>
          If you've registered a tag or a hook in a project's own
          `prepros.includes` file (see
          [Writing pages](../../docs/authoring/#registering-tags-and-hooks)),
          you already know most of this — a plugin is exactly that, moved
          into its own npm package so it can be versioned, published, and
          reused across projects instead of copy-pasted. `kiri` loads it,
          calls one function, and the rest is the same tag/hook registry.

          This page builds one from scratch: a `<badge>` tag that renders a
          small colored pill, `{% badge %}`-style but packaged. It's small
          on purpose — the three [official plugins](../) do the same thing
          at real scale, and are worth reading alongside this.
        </markdown>
    </div>
</section>

<hr class="fold">

<section class="section wrap">
    <ol class="steps">
        <li>
            <h3 id="step-1">The package shape</h3>
            <div class="prose">
                <markdown>
                A plugin is an ordinary npm package with one extra block in
                its `package.json`:

                ```json
                {
                  "name": "kirigami-plugin-badge",
                  "version": "0.1.0",
                  "type": "module",
                  "main": "index.js",
                  "kirigami": {
                    "type": "plugin",
                    "minVersion": "1.5.0",
                    "optionsSchema": "./options.schema.json"
                  },
                  "dependencies": {
                    "@kirigami/sdk": "^0.2.0"
                  }
                }
                ```

                `kiri` only accepts three naming shapes for the `name`
                field — this is how it tells a plugin apart from an
                unrelated dependency in `plugins:`:

                | Convention | Example |
                |---|---|
                | `@kirigami/plugin-*` | reserved for official plugins |
                | `<scope>/kirigami-plugin-*` | `@acme/kirigami-plugin-badge` |
                | `kirigami-plugin-*` | `kirigami-plugin-badge` (used here) |

                `kirigami.minVersion` gates the loader against a `kiri`
                too old for whatever hooks you use; `optionsSchema` is
                optional but worth having from the start — see
                [step 5](#step-5).
                </markdown>
            </div>
        </li>
        <li>
            <h3>Register on load</h3>
            <div class="prose">
                <markdown>
                `main` exports a default function. `kiri` calls it once, at
                the top of every `build`/`export`/`watch`, with the
                `options:` this project gave it in `kirigami.yaml`:

                ```js
                // index.js
                import path from 'node:path';
                import { fileURLToPath } from 'node:url';
                import { on, HOOKS } from '@kirigami/sdk';

                const dir = path.dirname(fileURLToPath(import.meta.url));

                export default function register(options = {}) {
                    on(HOOKS.PREPROS_PHP, () => path.join(dir, 'php', 'badge.php'));
                }
                ```

                `on(hook, fn)` is [`@kirigami/sdk`](https://www.npmjs.com/package/@kirigami/sdk)'s
                whole API surface, shared by every task and every plugin in
                the process. `PREPROS_PHP` tells `kiri` "mount this
                absolute PHP path and `include_once` it before any page
                renders" — the seam that lets a plugin call
                `PREPROS::registerTag()` from real PHP, the same call a
                project's own `_lib/functions.php` would make.
                </markdown>
            </div>
        </li>
        <li>
            <h3>The tag itself</h3>
            <div class="prose">
                <markdown>
                Ordinary `@kirigami/php-prepros` PHP — nothing plugin-specific
                about this file at all:

                ```php
                // php/badge.php
                PREPROS::registerTag('badge', function ($tag, $attrs, $body) {
                    $text = trim($body) ?: ($attrs['text'] ?? '');
                    if ($text === '') return '';
                    $tone = $attrs['tone'] ?? 'accent';
                    return '<span class="pg-badge pg-badge--' . htmlspecialchars($tone, ENT_QUOTES) . '">'
                         . htmlspecialchars($text, ENT_QUOTES) . '</span>';
                });
                ```

                `<badge>New</badge>` and `<badge text="Beta" tone="accent">`
                both work — `STR::replaceTags()` (what `registerTag()` runs
                on under the hood) accepts a tag with a body, self-closing,
                or bare. Full signature and the rest of the `PREPROS` API —
                `mount()`, `exportFile()`, `fstat()`, `$config` — is in the
                [PHP class library](../../docs/php/#prepros).
                </markdown>
            </div>
        </li>
        <li>
            <h3>Ship default styles</h3>
            <div class="prose">
                <markdown>
                The same `on()` call, a different hook — `SASS_AFTER`
                appends a file to every `sass` task, after the project's own
                entry (so the project's tokens are already defined when it
                compiles):

                ```js
                on(HOOKS.SASS_AFTER, () => path.join(dir, 'badge.scss'));
                ```

                ```scss
                // badge.scss
                .pg-badge {
                    display: inline-block;
                    padding: .15em .6em;
                    border-radius: 3px;
                    font-size: .78em;

                    &--accent { background: var(--accent-soft); color: var(--accent); }
                    &--muted  { background: var(--surface-2);   color: var(--ink-muted); }
                }
                ```

                Reading `var(--accent)` instead of a hard-coded color is
                what lets this render correctly in **any** project's own
                palette, light or dark, with nothing to configure —
                `@kirigami/canva`'s `conf` is what defines those tokens; see
                [plugin-embed](../#embed)'s play button for a slightly
                fancier version of the same idea.

                `ESBUILD_BEFORE`/`ESBUILD_AFTER` work identically for
                client-side JS — see `plugin-embed`'s `src/embed.js` for a
                real one, and `PREPROS_HTML` if what you need is to
                transform each page's *rendered* HTML rather than contribute
                a tag (`plugin-highlight`'s whole job runs on that hook).
                </markdown>
            </div>
        </li>
        <li>
            <h3 id="step-5">Make it configurable</h3>
            <div class="prose">
                <markdown>
                `register(options)` receives whatever this project wrote
                under this plugin's `options:` in `kirigami.yaml` — but
                that value only exists on the **JS** side. There's no
                built-in channel from a JS-side option into the PHP file
                `PREPROS_PHP` mounts; if a tag's PHP-side behavior needs to
                vary, either hard-code the default in the PHP file itself
                (simplest — every official plugin here does exactly that
                for anything PHP-side), or gate a whole *hook* on an option,
                which *is* a normal JS-side decision:

                ```js
                export default function register(options = {}) {
                    const opts = { style: true, ...options };
                    on(HOOKS.PREPROS_PHP, () => path.join(dir, 'php', 'badge.php'));
                    if (opts.style) on(HOOKS.SASS_AFTER, () => path.join(dir, 'badge.scss'));
                }
                ```

                `kiri` validates `options:` against `optionsSchema` before
                calling `register()` — point it at a small JSON Schema:

                ```json
                // options.schema.json
                {
                  "$schema": "http://json-schema.org/draft-07/schema#",
                  "type": "object",
                  "additionalProperties": false,
                  "properties": {
                    "style": { "type": "boolean", "default": true }
                  }
                }
                ```

                This is also what makes `plugins[].options` autocomplete in
                an editor once `kirigami.schema.json` `$ref`s it — see how
                the three official plugins wire that in their own repo if
                you're publishing under `@kirigami/`; a third-party plugin's
                options are still validated at build time either way.
                </markdown>
            </div>
        </li>
        <li>
            <h3>Try it</h3>
            <div class="prose">
                <markdown>
                ```yaml
                plugins:
                  - name: "kirigami-plugin-badge"
                    active: true
                    options:
                      style: true
                ```

                For local development before publishing, point at the
                folder with a `file:` dependency and `npm install` —
                exactly what every plugin on this site was verified with
                before it shipped.

                > [!NOTE]
                > If a hook you registered seems to silently never fire
                > while developing this way, check for a **second copy** of
                > `@kirigami/sdk` — e.g. an `npm install` run standalone
                > inside the plugin's own folder, which can leave it with
                > its own `node_modules/@kirigami/sdk` instead of sharing
                > the consuming project's one. The hook registry is a
                > single module-level `Map`; two copies of the module means
                > two disconnected registries, and `on()` in one is
                > invisible to `run()` in the other — no error either side,
                > it just never fires. A real `npm install` of a
                > **published** plugin doesn't hit this: npm dedupes
                > `@kirigami/sdk` to one copy in the consuming project's
                > `node_modules` in the normal case.
                </markdown>
            </div>
        </li>
    </ol>
</section>

<hr class="fold">

<section class="section wrap">
    <div class="prose">
        <markdown>
          ## Hook reference

          Every hook below is `@kirigami/sdk`'s `on(HOOKS.<NAME>, fn)`. A
          hook either **collects** (every listener's return value is
          gathered into a list — `undefined`/`null` is skipped, an array is
          flattened in) or **pipes** (each listener receives the previous
          one's output and can transform it).
        </markdown>

        <table class="table">
            <thead>
                <tr><th>Hook</th><th>Kind</th><th>Receives</th><th>Returns</th></tr>
            </thead>
            <tbody>
                <tr>
                    <td><code>SASS_BEFORE</code> / <code>SASS_AFTER</code></td>
                    <td>collect</td>
                    <td><code>{ __root, task, exportPath, config }</code></td>
                    <td>a `.scss` path, or an array of them</td>
                </tr>
                <tr>
                    <td><code>SASS_FUNCTIONS</code></td>
                    <td>collect</td>
                    <td>same as above</td>
                    <td><code>{ 'my-fn($x)': (args) => SassValue }</code></td>
                </tr>
                <tr>
                    <td><code>ESBUILD_BEFORE</code> / <code>ESBUILD_AFTER</code></td>
                    <td>collect</td>
                    <td><code>{ __root, task, exportPath, config }</code></td>
                    <td>a `.js`/`.ts` path, or an array — bundled as a side-effect import</td>
                </tr>
                <tr>
                    <td><code>ESBUILD_PLUGINS</code></td>
                    <td>collect</td>
                    <td>same as above</td>
                    <td>an esbuild plugin object, or an array</td>
                </tr>
                <tr>
                    <td><code>PREPROS_PHP</code></td>
                    <td>collect</td>
                    <td><code>{ __root, config }</code></td>
                    <td>an absolute `.php` path, or an array — mounted + <code>include_once</code>'d once, before any page renders</td>
                </tr>
                <tr>
                    <td><code>PREPROS_HTML</code></td>
                    <td>pipe</td>
                    <td><code>(html, { file, abs, exportPath, config })</code></td>
                    <td>the transformed HTML string (or nothing, to leave it untouched)</td>
                </tr>
            </tbody>
        </table>

        <markdown>
          On a signature collision with a native Sass function, the native
          one always wins. Multiple listeners on the same hook all run, in
          registration order — a project's own `prepros.includes` file and
          three plugins can all hook `PREPROS_HTML` without stepping on
          each other.

          ## Publishing

          - Package name matches one of the three conventions in
            [step 1](#step-1) — anything else, `kiri` won't load it from
            `plugins:` at all.
          - `kirigami.minVersion` set to whatever `kiri` version you
            actually tested against.
          - `kirigami.optionsSchema` present, even a trivial one — free
            build-time validation and editor completion for anyone using
            the plugin.
          - `npm publish`. `kiri install your-plugin-name` then resolves,
            installs, and prints the `plugins:` block for anyone to paste
            in — nothing else to wire up on your end.

          [`plugin-highlight`](https://github.com/php-kirigami/kirigami/tree/main/packages/plugin-highlight),
          [`plugin-extlink`](https://github.com/php-kirigami/kirigami/tree/main/packages/plugin-extlink)
          and [`plugin-embed`](https://github.com/php-kirigami/kirigami/tree/main/packages/plugin-embed)
          are real, MIT-licensed packages built exactly this way — worth
          reading end to end once this page's toy example makes sense.
        </markdown>
    </div>
</section>
