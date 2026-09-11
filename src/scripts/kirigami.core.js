/**
 * Bundled by the `js-core` esbuild task → scripts/kirigami.core.min.js
 *
 * Progressive enhancement only — the site works with JavaScript disabled.
 */

// Theme toggle: wires every [data-theme-toggle] control, persists the choice
// under `kirigami-theme`, keeps controls in sync (incl. with OS changes in
// auto mode), and fires `canva:themechange` on window. The managed <head>
// injects the FOUC guard that reads the same key before first paint.
import "@kirigami/canva/theme";

// Reveal on scroll: adds `is-in` to each [data-reveal] as it enters the
// viewport. The CSS half (hide until `.is-in`, gated on `.js`) lives in
// styles/partials/_main.scss.
import "@kirigami/canva/reveal";

const ready = (fn) =>
    document.readyState === 'loading'
        ? document.addEventListener('DOMContentLoaded', fn, { once: true })
        : fn();

ready(() => {
    // ── Mobile nav ────────────────────────────────────────────────
    const navToggle = document.querySelector('.nav-toggle');
    const nav = document.getElementById('site-nav');
    navToggle?.addEventListener('click', () => {
        const open = nav.toggleAttribute('data-open');
        navToggle.setAttribute('aria-expanded', String(open));
    });
});
