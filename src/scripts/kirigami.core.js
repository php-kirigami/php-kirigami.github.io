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

// "On this page": highlights the section being read — the last h2 that has
// scrolled past a line a quarter down the viewport, or the last one once the
// page bottom is reached (a short final section would never get there).
ready(() => {
    const links = [...document.querySelectorAll('.toc a[href^="#"]')];
    const heads = links.map((a) => document.getElementById(decodeURIComponent(a.hash.slice(1))));
    if (!links.length || heads.includes(null)) return;

    let active = null;
    const mark = () => {
        const bottom = Math.ceil(scrollY + innerHeight) >= document.documentElement.scrollHeight - 2;
        let current = heads[0];
        for (const h of heads) if (h.getBoundingClientRect().top <= innerHeight * 0.25) current = h;
        if (bottom) current = heads[heads.length - 1];
        if (current === active) return;
        active = current;
        links.forEach((a, i) => {
            const on = heads[i] === current;
            a.toggleAttribute('aria-current', on);
            if (on) a.scrollIntoView({ block: 'nearest' });
        });
    };
    addEventListener('scroll', mark, { passive: true });
    addEventListener('resize', mark);
    mark();
});
