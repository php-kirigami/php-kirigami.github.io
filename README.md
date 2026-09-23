<div align="center">

<img src="https://zmotrin.github.io/assets/kirigami/kirigami-logo-universal.svg" alt="Kirigami" width="400" />

---

# php-kirigami.github.io

The **[Kirigami](https://github.com/php-kirigami/kirigami)** project site — itself
built with Kirigami.

[![License: MIT](https://img.shields.io/badge/license-MIT-blue.svg)](./LICENSE)

</div>

---

The source lives in `src/`; `kiri export` compiles it to a dependency-free static
site for GitHub Pages.

## Develop

```console
npm install
npx kiri serve      # build, then rebuild on save with a live-reloading local server
npx kiri export     # production build into dist/
```

Pushing to `main` builds and deploys to GitHub Pages through
`.github/workflows/page.yml`. Conventions for contributors and AI assistants
are in [CLAUDE.md](CLAUDE.md); how the site was built is in
[docs/HISTORY.md](docs/HISTORY.md).
