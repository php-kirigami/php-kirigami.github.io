<?php
/**
 * @title    Ecosystem
 * @section  ecosystem
 * @type     doc
 * @abstract Every package in the monorepo, its role, and its license — plus
 *           the satellite repos around it.
 */

$packages = kirigami_packages();
?>

<section class="section wrap">
    <div class="prose">
        <p>
            Kirigami is an npm workspaces monorepo. Every package below is
            published under the <a href="https://www.npmjs.com/org/kirigami"><code>@kirigami</code></a>
            scope from <a href="https://github.com/php-kirigami/kirigami">a single repo</a>,
            and released together in dependency order — the version numbers
            below are fetched live from npm at build time, not typed by hand.
        </p>
    </div>

    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr>
                    <th>Package</th>
                    <th>Role</th>
                    <th>Version</th>
                    <th>License</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($packages as $pkg): ?>
                    <?php $version = kirigami_pkg_version($pkg['npm_pkg']); ?>
                    <tr>
                        <td>
                            <a href="https://www.npmjs.com/package/@kirigami/<?php echo $pkg['npm_pkg']; ?>">
                                <code>@kirigami/<?php echo $pkg['npm_pkg']; ?></code>
                            </a>
                        </td>
                        <td><?php echo str_htmlesc($pkg['role']); ?></td>
                        <td class="table__num">
                            <?php echo $version ? 'v' . str_htmlesc($version) : '—'; ?>
                        </td>
                        <td>
                            <?php if ($pkg['license'] === 'GPL-2.0-or-later'): ?>
                                <span class="badge badge--muted">GPL-2.0-or-later</span>
                            <?php else: ?>
                                <span class="badge">MIT</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>

<hr class="fold">

<section class="section wrap">
    <h2>Release order</h2>
    <div class="prose">
        <markdown>
          Every internal `@kirigami/*` dependency is pinned to an **exact**
          version — bumping `canva`, say, means also bumping the dep range
          of every package that depends on it (`kirigami`, `plugin-highlight`,
          `plugin-embed`, …), even if nothing else about them changed. The
          release script topologically sorts the graph and walks it in that
          order, skipping a package outright if that exact version is
          already published:

          ```
          sdk → canva → struct-walker → php-prepros → kirigami
              → plugin-highlight → plugin-extlink → plugin-embed
          ```

          For each one still needing a release: run its `build` script,
          `npm pack` it into `packs/`, `npm publish`, then move to the next.
          Once everything is out, it purges the jsDelivr cache for
          `kirigami.schema.json` and every plugin's `kirigami.optionsSchema`
          — both are served from GitHub `@main`, and an editor would
          otherwise keep the stale copy for up to 12h after a schema change.
        </markdown>
    </div>
</section>

<hr class="fold">

<section class="section wrap">
    <h2>Satellite repos</h2>
    <div class="prose">
        <p>Outside the monorepo, all under the same <code>php-kirigami</code> GitHub org:</p>
    </div>

    <div class="grid" data-reveal>
        <article class="card">
            <h3><a href="https://github.com/php-kirigami/kiribuild">kiribuild</a></h3>
            <p>The reusable GitHub Action — Node 24 + the <code>kiri</code> CLI + <code>kiri export</code>. See <a href="<?php echo $relroot; ?>docs/">the docs</a> for how it fits into a deploy workflow.</p>
        </article>
        <article class="card">
            <h3><a href="https://github.com/php-kirigami/template-default">template-default</a></h3>
            <p>The minimal starter template. <code>npx kiri create default</code>.</p>
        </article>
        <article class="card">
            <h3><a href="https://github.com/php-kirigami/template-demo">template-demo</a></h3>
            <p>A full feature tour template. <code>npx kiri create demo</code>.</p>
        </article>
        <article class="card">
            <h3><a href="https://github.com/php-kirigami/php-kirigami.github.io">php-kirigami.github.io</a></h3>
            <p>This site — the one you're reading right now, built with Kirigami itself.</p>
        </article>
    </div>
</section>

<hr class="fold">

<section class="section wrap">
    <div class="prose">
        <p class="lead lead--sm">
            <a href="<?php echo $relroot; ?>about/">More about the project &rarr;</a>
        </p>
    </div>
</section>
