<?php
/**
 * @title    Tutorial · Styles & scripts
 * @section  start
 * @type     guide
 * @abstract Retheme with canva, wire up dark mode, and let the managed head
 *           handle the rest.
 */
?>

<section class="section wrap">
    <ol class="steps">
        <li>
            <h3>Retheme with a few tokens</h3>
            <div class="prose">
                <markdown>
                `styles/partials/_conf.scss` forwards `@kirigami/canva`'s
                token layer — override a handful of variables and every
                component built on them (buttons, cards, the toggle) follows:

                ```scss
                @forward "@kirigami/canva/conf" with (
                    $bg:     #faf7f2,
                    $ink:    #26221c,
                    $accent: #a8562e,
                    $dark:   true,
                    $theme:  both,
                );
                ```

                `$dark: true` turns on canva's built-in dark palette; `$theme:
                both` follows the OS by default but lets `data-theme="dark"` /
                `"light"` on `<html>` force either way.
                </markdown>
            </div>
        </li>
        <li>
            <h3>Add the toggle — no JS of your own</h3>
            <div class="prose">
                <markdown>
                In `scripts/kirigami.core.js`:

                ```js
                import "@kirigami/canva/theme";
                ```

                That import wires every `[data-theme-toggle]` on the page,
                persists the choice, and keeps controls in sync with the OS.
                Drop a button in `_layouts/header.php`:

                ```html
                <button data-theme-toggle aria-label="Toggle dark mode">🌓</button>
                ```

                No state to manage by hand — the import is the whole feature.
                </markdown>
            </div>
        </li>
        <li>
            <h3>Stop wiring the &lt;head&gt; by hand</h3>
            <div class="prose">
                <markdown>
                Once `prepros:` is set, `_layouts/header.php` doesn't need a
                `<link>` for the stylesheet or a `<script>` for the bundle —
                Kirigami injects both automatically for every `sass`/`esbuild`
                task, per page, cache-busted. It also injects a small
                theme/FOUC guard as the very first thing in `<head>`, so the
                stored preference applies before first paint. All you write:

                ```php
                <head>
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                </head>
                ```
                </markdown>
            </div>
        </li>
    </ol>
</section>

<hr class="fold">

<section class="section wrap">
    <div class="prose">
        <markdown>
        > [!NOTE]
        > Three separate mechanisms, one result: config tokens for the
        > palette, a one-line import for the toggle behaviour, and an
        > automatic head injection for the plumbing between them. None of the
        > three needs to know about the other two.
        </markdown>
    </div>
</section>
