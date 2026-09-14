var lyliumReduceMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
var lyliumFinePointer = window.matchMedia && window.matchMedia('(hover: hover) and (pointer: fine)').matches;

// Fondu d'entrée + fondu doux vers la page suivante au clic sur un lien
// interne, pour que la navigation entre les pages (site multi-pages
// classique, sans routeur) paraisse continue plutôt que de couper net.
(function () {
    if (!document.documentElement.classList.contains('js-transitions')) return;

    requestAnimationFrame(function () {
        document.body.classList.add('page-loaded');
    });

    // Page restaurée depuis le cache navigateur (bouton précédent) :
    // s'assurer qu'elle n'est pas coincée en transparence.
    window.addEventListener('pageshow', function (e) {
        if (e.persisted) {
            document.body.classList.remove('page-leaving');
            document.body.classList.add('page-loaded');
        }
    });

    document.addEventListener('click', function (e) {
        if (e.defaultPrevented || e.button !== 0 || e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) return;

        var link = e.target.closest('a[href]');
        if (!link) return;
        if (link.target && link.target !== '_self') return;
        if (link.hasAttribute('download')) return;

        var url;
        try { url = new URL(link.href, window.location.href); } catch (err) { return; }
        if (url.origin !== window.location.origin) return;
        if (url.pathname === window.location.pathname && url.search === window.location.search && url.hash) return;

        e.preventDefault();
        document.body.classList.add('page-leaving');
        setTimeout(function () { window.location.href = link.href; }, 200);
    });
})();

document.addEventListener('DOMContentLoaded', function () {
    var toggle = document.getElementById('navToggle') || document.querySelector('.nav-toggle');
    var nav = document.getElementById('mainNav') || document.querySelector('.main-nav');

    if (toggle && nav) {
        toggle.addEventListener('click', function () {
            nav.classList.toggle('open');
        });
    }
});

// Bascule thème clair/sombre, mémorisée dans localStorage
(function () {
    var toggle = document.getElementById('themeToggle');
    if (!toggle) return;

    toggle.addEventListener('click', function () {
        var isDark = document.documentElement.getAttribute('data-theme') === 'dark';
        if (isDark) {
            document.documentElement.removeAttribute('data-theme');
        } else {
            document.documentElement.setAttribute('data-theme', 'dark');
        }
        try { localStorage.setItem('lylium_theme', isDark ? 'light' : 'dark'); } catch (e) {}
        document.dispatchEvent(new CustomEvent('lylium:theme-change'));
    });
})();

// Auto-soumission des filtres de la collection au changement
(function () {
    var toolbar = document.querySelector('.collection-toolbar');
    if (!toolbar) return;
    toolbar.querySelectorAll('select').forEach(function (select) {
        select.addEventListener('change', function () { toolbar.submit(); });
    });
})();

// Apparition animée des blocs ".reveal" — GSAP ScrollTrigger si disponible,
// sinon repli sur IntersectionObserver + transition CSS.
(function () {
    var elements = document.querySelectorAll('.reveal');
    if (!elements.length) return;

    if (lyliumReduceMotion) {
        elements.forEach(function (el) { el.classList.add('reveal-visible'); });
        return;
    }

    if (window.gsap && window.ScrollTrigger) {
        gsap.registerPlugin(ScrollTrigger);
        elements.forEach(function (el) {
            var delay = parseFloat(getComputedStyle(el).getPropertyValue('--reveal-delay')) || 0;
            var isImage = el.classList.contains('reveal-image');
            el.style.transition = 'none';
            gsap.fromTo(el,
                { opacity: 0, y: isImage ? 0 : 26, scale: isImage ? 1.05 : 1 },
                {
                    opacity: 1, y: 0, scale: 1, duration: .9, delay: delay, ease: 'power3.out',
                    scrollTrigger: { trigger: el, start: 'top 88%', toggleActions: 'play none none reverse' }
                }
            );
        });
        return;
    }

    if (!('IntersectionObserver' in window)) {
        elements.forEach(function (el) { el.classList.add('reveal-visible'); });
        return;
    }

    var observer = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (entry.isIntersecting) {
                entry.target.classList.add('reveal-visible');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.2, rootMargin: '0px 0px -10% 0px' });

    elements.forEach(function (el) { observer.observe(el); });
})();

// Bascule 3D au survol des cartes produit / silhouettes lookbook (souris fine
// uniquement). La rotation est amortie (lerp) dans une boucle rAF continue
// plutôt que recalée à chaque événement pointermove — plus fluide, sans à-coups.
(function () {
    if (lyliumReduceMotion || !lyliumFinePointer) return;

    var targets = document.querySelectorAll('.produit-card, .feature-tile:not(.feature-tile--cta), .look-frame');
    if (!targets.length) return;

    var MAX_TILT = 9;
    var LERP = 0.16;

    targets.forEach(function (el) {
        var state = { tx: 0, ty: 0, cx: 0, cy: 0, hovering: false, raf: null };

        function step() {
            state.cx += (state.tx - state.cx) * LERP;
            state.cy += (state.ty - state.cy) * LERP;
            el.style.setProperty('--rx', state.cx.toFixed(2) + 'deg');
            el.style.setProperty('--ry', state.cy.toFixed(2) + 'deg');

            if (state.hovering || Math.abs(state.tx - state.cx) > 0.02 || Math.abs(state.ty - state.cy) > 0.02) {
                state.raf = requestAnimationFrame(step);
            } else {
                state.raf = null;
            }
        }

        function ensureLoop() {
            if (!state.raf) state.raf = requestAnimationFrame(step);
        }

        function onMove(e) {
            var rect = el.getBoundingClientRect();
            var px = (e.clientX - rect.left) / rect.width;
            var py = (e.clientY - rect.top) / rect.height;
            state.tx = (px - 0.5) * MAX_TILT * 2;
            state.ty = -(py - 0.5) * MAX_TILT * 2;
            el.style.setProperty('--mx', (px * 100).toFixed(1) + '%');
            el.style.setProperty('--my', (py * 100).toFixed(1) + '%');
            ensureLoop();
        }

        function onEnter() {
            state.hovering = true;
            el.classList.add('is-tilting');
            ensureLoop();
        }

        function onLeave() {
            state.hovering = false;
            state.tx = 0; state.ty = 0;
            el.classList.remove('is-tilting');
            ensureLoop();
        }

        el.addEventListener('pointerenter', onEnter);
        el.addEventListener('pointermove', onMove);
        el.addEventListener('pointerleave', onLeave);
    });
})();

// Intro plein écran (rideau) jouée une fois par session, passable au clic/scroll/touche
(function () {
    var intro = document.getElementById('siteIntro');
    if (!intro) return;

    if (intro.classList.contains('site-intro--skip')) return;

    var done = false;

    function markSeen() {
        try { sessionStorage.setItem('lylium_intro_seen', '1'); } catch (e) {}
    }

    function cleanup() {
        window.removeEventListener('scroll', skip);
        window.removeEventListener('click', skip);
        window.removeEventListener('keydown', skip);
        intro.removeEventListener('transitionend', remove);
    }

    function remove() {
        if (intro.parentNode) intro.parentNode.removeChild(intro);
    }

    function finish() {
        if (done) return;
        done = true;
        markSeen();
        cleanup();
        intro.classList.add('site-intro-hide');
        intro.addEventListener('transitionend', remove);
        setTimeout(remove, 1200); // filet de sécurité si transitionend ne se déclenche pas
    }

    function skip() {
        intro.classList.add('site-intro-skip-fast');
        finish();
    }

    if (lyliumReduceMotion) {
        markSeen();
        remove();
        return;
    }

    requestAnimationFrame(function () {
        intro.classList.add('site-intro-play');
    });

    window.addEventListener('scroll', skip, { passive: true, once: true });
    window.addEventListener('click', skip, { once: true });
    window.addEventListener('keydown', skip, { once: true });

    setTimeout(finish, 3100);
})();

// Navbar transparente → opaque après le hero
(function () {
    var hero = document.getElementById('hero');
    var header = document.querySelector('.site-header');
    if (!hero || !header) return;

    var ticking = false;

    function update() {
        ticking = false;
        var rect = hero.getBoundingClientRect();
        if (rect.bottom <= 80) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
    }

    function onScroll() {
        if (!ticking) {
            ticking = true;
            window.requestAnimationFrame(update);
        }
    }

    window.addEventListener('scroll', onScroll, { passive: true });
    window.addEventListener('resize', onScroll);
    update();
})();

// Pages sans hero (catégories, compte...) : header toujours opaque dès le départ
(function () {
    if (document.getElementById('hero')) return;
    var header = document.querySelector('.site-header');
    if (header) header.classList.add('scrolled');
})();
