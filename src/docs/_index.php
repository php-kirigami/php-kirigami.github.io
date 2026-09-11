<?php
/**
 * @title    Docs
 * @section  docs
 * @abstract The shape every reference and tutorial page on this site will
 *           follow — config reference, CLI, PHP class library, plugin
 *           authoring, all still to come.
 */
?>

<section class="section wrap doc-head">
    <span class="eyebrow">Documentation</span>
    <h1><?php echo str_htmlesc($title); ?></h1>
    <p class="lead"><?php echo str_htmlesc($abstract); ?></p>
</section>

<section class="section wrap">
    <div class="prose">
        <markdown>
          This page is the **doc-page model**: an eyebrow label, an `<h1>`, an
          abstract from the page's own PHPDOC, then plain Markdown wrapped in
          `.prose`. Every reference page — config, CLI, the PHP class library,
          plugin authoring — will be built on this same shape, with a sidebar
          added once there's enough of them to need one.

          ## A real `kirigami.yaml`

          This is the config that renders the page you're reading right now:

          ```yaml
          kirigami:
            project:  Kirigami
            baseurl:  https://php-kirigami.github.io
            root:     src

          meta: {}
          jsonld: {}

          prepros:
            before: _layouts/header.php
            after:  _layouts/footer.php
            format: true

          plugins:
            - name: "@kirigami/plugin-highlight"
              active: true
          ```

          Fenced code blocks like the one above are colored **at build time** by
          [`@kirigami/plugin-highlight`](https://github.com/php-kirigami/kirigami/tree/main/packages/plugin-highlight)
          — this page ships zero bytes of highlight.js to your browser. Hover a
          block for the copy button.

          > [!NOTE]
          > The full configuration reference, the CLI command list, and the PHP
          > class library land in a later phase of the site. For now, the
          > complete reference lives in the monorepo README, linked below.

          The full reference: [github.com/php-kirigami/kirigami](https://github.com/php-kirigami/kirigami#readme).

          And PHP renders normally alongside the Markdown, same as anywhere else
          on the site — every page opens with a PHPDOC block like this one, then
          plain PHP:

          ```php
          /**
          * @title    Example
          * @abstract Every page starts with a PHPDOC block like this one.
          */

          echo str_htmlesc($title);
          ```
        </markdown>

        <p><a href="<?php echo $relroot; ?>">&larr; Back home</a></p>
    </div>
</section>
