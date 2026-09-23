<?php
/**
 * @title    Start
 * @section  start
 * @type     doc
 * @abstract Three ways in, depending on how much time you've got.
 */
?>

<section class="section wrap">
    <div class="grid" data-reveal>
        <a class="card" href="<?php echo $relroot; ?>start/install/">
            <h3>Install</h3>
            <p>One command, one dependency. Start here if you just want the
            CLI on your machine.</p>
        </a>
        <a class="card" href="<?php echo $relroot; ?>start/quickstart/">
            <h3>Quickstart</h3>
            <p>A minimal <code>kirigami.yaml</code>, one page, one command to
            watch it, one to export it. Five minutes.</p>
        </a>
        <a class="card" href="<?php echo $relroot; ?>start/tutorial/">
            <h3>Full tutorial</h3>
            <p>Build a real small site end to end — pages, content, styling,
            images, SEO, deployment — one concept per part, seven parts.</p>
        </a>
    </div>
</section>

<hr class="fold">

<section class="section wrap">
    <div class="prose">
        <p>
            Already comfortable and just need the shape of a config key or a
            CLI flag? The <a href="<?php echo $relroot; ?>docs/">docs</a> are
            growing into the full reference; until then, the
            <a href="https://github.com/php-kirigami/kirigami#readme">monorepo README</a>
            has it all.
        </p>
    </div>
</section>
