<?php
/**
 * @title    Tutorial · Pages & layout
 * @section  start
 * @type     guide
 * @abstract Three real pages, one shared header and footer, and the PHPDOC
 *           block that drives them.
 */
?>

<section class="section wrap">
    <ol class="steps">
        <li>
            <h3>Add two more pages</h3>
            <div class="prose">
                <markdown>
                Studio Plié needs a home page (already there), a notes
                section, and a gallery:

                ```
                src/
                ├── _index.php          → /
                ├── notes/_index.php    → /notes/
                └── atelier/_index.php  → /atelier/
                ```

                Each starts the same way — a PHPDOC block, then markup:

                ```php
                /**
                 * @title    Atelier
                 * @section  atelier
                 * @abstract Where the folding happens.
                 */

                echo '<h1>' . str_htmlesc($title) . '</h1>';
                echo '<p>' . str_htmlesc($abstract) . '</p>';
                ```

                Every `@key value` in that docblock becomes a `$key` variable
                — in the page itself, **and** in `before`/`after`. Define
                whatever keys make sense; `@title`, `@section` and `@abstract`
                are just this project's own convention, not reserved words.
                </markdown>
            </div>
        </li>
        <li>
            <h3>Wire up navigation in the header</h3>
            <div class="prose">
                <markdown>
                `src/_layouts/header.php` runs before every page (it's
                `prepros.before` in `kirigami.yaml`), so it's the natural
                place for a nav built from a plain PHP array:

                ```php
                $nav = [
                    ''         => ['Home',   'home'],
                    'notes/'   => ['Notes',  'notes'],
                    'atelier/' => ['Atelier','atelier'],
                ];
                ```

                ```php
                foreach ($nav as $path => [$label, $key]) {
                    $current = ($section ?: 'home') === $key;
                    echo '<a href="' . $relroot . $path . '"'
                       . ($current ? ' aria-current="page"' : '')
                       . '>' . $label . '</a>';
                }
                ```

                `$relroot` is injected by Kirigami on every render — the
                relative path from the current page back to `kirigami.root` —
                so these links work whether the current page is `/` or three
                folders deep.
                </markdown>
            </div>
        </li>
        <li>
            <h3>Close the layout in the footer</h3>
            <div class="prose">
                <markdown>
                `src/_layouts/footer.php` is `prepros.after` — it closes
                whatever `<main>`/`<body>` the header opened. Nothing new here
                yet; it stays as the starter template left it until
                [Part 4](<?php echo $relroot; ?>start/tutorial/4-styles-scripts/)
                re-themes it.
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
        > There's no router, no template inheritance system, no special
        > "layout" concept beyond two plain PHP includes run before and after
        > the page body. That's deliberate — it's the same PHP you already
        > know, just running once at build time instead of on every request.
        </markdown>
    </div>
</section>
