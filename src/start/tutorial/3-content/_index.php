<?php
/**
 * @title    Tutorial · Content
 * @section  start
 * @type     guide
 * @abstract Markdown in a page, a YAML data file for a listing, and the
 *           annotation that renders a page as pure Markdown.
 */
?>

<section class="section wrap">
    <ol class="steps">
        <li>
            <h3>Write prose with the markdown tag</h3>
            <div class="prose">
                <markdown>
                In `atelier/_index.php`, drop Markdown straight into the page
                — it's converted to HTML after PHP runs, so PHP variables and
                Markdown mix freely. Wrap the paragraph between an opening and
                a closing tag named `markdown`, the same way every paragraph
                on this tutorial page is written — view this page's source to
                see the real thing, since showing the tag's own angle brackets
                here would trip the very tag it's demonstrating.

                Add a `prose` attribute on the opening tag to wrap the output
                in `.prose` — this site's own `@kirigami/canva`-based
                typography for long-form content.
                </markdown>
            </div>
        </li>
        <li>
            <h3>List notes from a YAML file</h3>
            <div class="prose">
                <markdown>
                `notes/_notes.yaml`:

                ```yaml
                - title: The first pleat
                  date: 2026-08-02
                  excerpt: Where the studio's name actually comes from.
                - title: Paper weights, compared
                  date: 2026-08-19
                  excerpt: 80gsm folds crisp; 120gsm holds a curve.
                ```

                `notes/_index.php` points a PHPDOC annotation at it, and gets
                the parsed data back as a plain array of objects — no manual
                `file_get_contents()` / `yaml_parse()`:

                ```php
                /**
                 * @title Notes
                 * @posts _notes.yaml
                 */
                ```

                ```php
                foreach ($posts as $post) {
                    echo '<h3>' . str_htmlesc($post->title) . '</h3>';
                    echo '<p>' . str_htmlesc($post->excerpt) . '</p>';
                }
                ```

                Any annotation value ending in `.yaml`, `.yml`, `.json` or
                `.md` that resolves to a real file is loaded and parsed
                automatically — the string becomes structured data instead.
                </markdown>
            </div>
        </li>
        <li>
            <h3>A whole page that's just Markdown</h3>
            <div class="prose">
                <markdown>
                For a single note that's pure prose, skip writing PHP at all.
                `notes/premier-pli/_premier-pli.md` holds the text; the page
                file becomes almost nothing:

                ```php
                /**
                 * @title   The first pleat
                 * @content _premier-pli.md
                 */
                ```

                `@content` renders that file's contents **as the page body**
                and skips executing the PHP file entirely — reserved for pages
                that are pure data or Markdown, wrapped by the same shared
                header/footer as everything else.
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
        > Three different shapes, same output family: `<markdown>` for prose
        > mixed with logic, an auto-loaded data file for structured listings,
        > `@content` for pages that are only ever going to be prose. Pick the
        > one that matches the page, not one convention for the whole site.
        </markdown>
    </div>
</section>
