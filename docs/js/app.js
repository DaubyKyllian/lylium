document.addEventListener('DOMContentLoaded', function () {
    var toggle = document.getElementById('navToggle');
    var nav = document.getElementById('mainNav');

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

// Apparition animée des blocs ".reveal" (ex: transition hero → section suivante)
(function () {
    var elements = document.querySelectorAll('.reveal');
    if (!elements.length) return;

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

// Intro plein écran (rideau) jouée une fois par session, passable au clic/scroll/touche
(function () {
    var intro = document.getElementById('siteIntro');
    if (!intro) return;

    if (intro.classList.contains('site-intro--skip')) return;

    var reduceMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
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

    if (reduceMotion) {
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