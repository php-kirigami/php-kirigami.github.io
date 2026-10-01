<?php
/**
 * @title    Kiri Studio
 * @section  studio
 * @abstract The desktop app that lets the owner of a Kirigami site edit it and
 *           publish it: no Git, no code, one Publish button.
 */
?>

<section class="hero wrap">
    <p class="eyebrow">For the people the site is built for</p>
    <h1>Your client edits. You stay out of it.</h1>
    <p class="lead">
        Kiri Studio is a desktop app for site owners. They sign in with GitHub
        once, change their text, fill in forms, drop in pictures, watch the
        real page update, and click <strong>Publish</strong>.
    </p>
    <p>
        <a class="btn" href="https://github.com/php-kirigami/kiri-studio/releases">Download Kiri Studio</a>
        <a class="btn btn--ghost" href="<?php echo $relroot; ?>docs/studio/">Set up a site for it</a>
    </p>
</section>

<section class="wrap">
    <figure class="shot">
        <img asset="features/studio.png" width="1200" alt="Kiri Studio: the page list, a Markdown page with its header form, and the live preview" loading="lazy">
        <figcaption>Pages by name on the left, the page's header as a form, the real page on the right.</figcaption>
    </figure>
</section>

<section class="section wrap">
    <h2 class="section-title">The whole journey, and nothing more</h2>

    <div class="grid grid--shots" data-reveal>
        <card kicker="1" title="Sign in once" image="features/studio-signin.png" alt="The welcome screen with a Sign in with GitHub button">
            One "Sign in with GitHub" button. The app opens the browser with the
            code already copied; the owner approves and never sees it again.
        </card>
        <card kicker="2" title="Open the site" image="features/studio-sites.png" alt="The list of websites the owner can edit">
            The site appears directly (a picker only shows for more than one),
            and keeps itself in sync while they read.
        </card>
        <card kicker="3" title="Edit by name" image="features/studio-app.png" alt="The page list and a page's header form">
            Pages and sections are listed by their plain names: "Team", "Blog
            posts". Text goes in a Markdown editor, data in a form-like editor
            checked against a JSON Schema. There is no Save button: every
            change is kept as a draft.
        </card>
        <card kicker="4" title="See the real page" image="features/studio-preview.png" alt="The live preview of a page next to the editor">
            A live preview beside the editor renders the page with the site's
            own Kirigami engine, as it will look once online.
        </card>
        <card kicker="5" title="Drop pictures in" image="features/studio-media.png" alt="The image manager, with a grid of thumbnails">
            Pictures and documents go in a manager. Big photos are resized for
            them, and each one can copy the code that inserts it into a page.
        </card>
        <card kicker="6" title="Publish" image="features/studio-publish.png" alt="A page being published, with the progress in the status bar">
            One button. "Publishing…", then "Your site is online", with a link.
            The site's usual GitHub Pages workflow does the rest.
        </card>
    </div>
</section>

<hr class="fold">

<section class="section wrap">
    <h2 class="section-title">What owners can edit</h2>

    <div class="grid" data-reveal>
        <card title="Text">
            Markdown pages, with the page header (title, date, abstract, page
            layout) shown as a form above the text instead of raw tags.
        </card>
        <card title="Structured data">
            YAML and JSON files, with schema-aware help and smart indentation.
            A file's schema is found the way VS Code finds it.
        </card>
        <card title="Images">
            Drop pictures in; they are downscaled before they are committed.
            Copy or insert the Markdown for them, or set one as a page's image.
        </card>
        <card title="Documents, audio, video">
            PDFs and other files go in a document manager, and audio and video
            play inside the app. Large files can go through Git LFS.
        </card>
        <card title="Blog-style collections">
            Add and delete posts or tree-shaped pages, with default header tags
            filled in for each new one.
        </card>
        <card title="Per-page media">
            Optionally, each page gets its own images, videos and downloads
            next to it, with a panel to manage them.
        </card>
    </div>

    <figure class="shot">
        <img asset="features/studio-media.png" width="1200" alt="Kiri Studio's image manager: a grid of thumbnails with New folder and Add images buttons" loading="lazy">
        <figcaption>The image manager: drop pictures in, big photos are resized for you, and each one can copy the code that inserts it.</figcaption>
    </figure>
</section>

<hr class="fold">

<section class="section wrap">
    <h2 class="section-title">You decide what is editable</h2>
    <div class="prose">
        <markdown>
        The maintainer controls it in the site's own `kirigami.yaml`, versioned
        with the site, not in the app. Without anything, the content that
        pages load and the site's images are editable; everything else stays
        out of sight.

        ```yaml
        studio:
          files: src/documents          # a document manager (PDF, …)
          exclude: [src/features/data/_stats.json]
          labels:
            src/features/data/_articles.yaml: Articles
        ```

        The build ignores that block, so a site works the same with or without
        Studio. Every key is documented in the [configuration
        reference](../docs/config/#studio) and in [Kiri Studio for
        maintainers](../docs/studio/).
        </markdown>
    </div>
</section>

<hr class="fold">

<section class="section wrap">
    <h2 class="section-title">Safe by design</h2>
    <ul class="reqs">
        <li><strong>No Git for the owner.</strong> The app talks to the GitHub API: no <code>git</code> binary, no repository on their disk.</li>
        <li><strong>All or nothing.</strong> A publish is one commit, applied by a single branch update. If someone published in between, the app syncs and rebuilds on the new head.</li>
        <li><strong>Only what is allowed.</strong> The sign-in only reaches repositories where the Kiri Studio GitHub App is installed <em>and</em> the owner can write.</li>
        <li><strong>Plain language.</strong> No commit, branch or conflict. Interface in English and French.</li>
        <li><strong>Windows, macOS and Linux.</strong> Installers update themselves on Windows and Linux (AppImage); they are not code-signed yet, so Windows warns on first install and macOS needs right-click, Open.</li>
    </ul>
    <p class="lead lead--sm">
        Kiri Studio is in early development. Source and releases:
        <a href="https://github.com/php-kirigami/kiri-studio">php-kirigami/kiri-studio</a>.
    </p>
</section>
