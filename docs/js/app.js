document.addEventListener('DOMContentLoaded', function () {
    var toggle = document.getElementById('navToggle');
    var nav = document.getElementById('mainNav');

    if (toggle && nav) {
        toggle.addEventListener('click', function () {
            nav.classList.toggle('open');
        });
    }
});

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

// Hero épinglé : zoom du fond + fondu du texte pendant le scroll, navbar transparente → opaque
(function () {
    var wrapper = document.getElementById('heroPinWrapper');
    var heroBg = document.getElementById('heroZoomBg');
    var heroContent = document.getElementById('heroZoomContent');
    var heroOverlay = document.getElementById('heroZoomOverlay');
    var hint = document.getElementById('heroScrollHint');
    var header = document.querySelector('.site-header');
    if (!wrapper || !heroBg || !header) return;

    var maxScale = 1.6;
    var maxBlur = 4; // px, au pic du zoom
    var maxBrightness = 0.25; // assombrissement additionnel au pic du zoom
    var ticking = false;

    // Ease-out cubic : le zoom démarre vite puis ralentit en fin de scroll.
    function easeOutCubic(x) {
        return 1 - Math.pow(1 - x, 3);
    }

    function update() {
        ticking = false;

        var rect = wrapper.getBoundingClientRect();
        var wrapperHeight = wrapper.offsetHeight;
        var viewportHeight = window.innerHeight;
        var scrollable = wrapperHeight - viewportHeight;
        var scrolled = -rect.top;
        var rawProgress = Math.min(Math.max(scrolled / scrollable, 0), 1);
        var progress = easeOutCubic(rawProgress);

        var scale = 1 + progress * (maxScale - 1);
        var blur = progress * maxBlur;
        var brightness = 1 - progress * maxBrightness;
        heroBg.style.transform = 'scale(' + scale + ')';
        heroBg.style.filter = 'blur(' + blur.toFixed(2) + 'px) brightness(' + brightness.toFixed(3) + ')';

        if (heroOverlay) {
            heroOverlay.style.opacity = 0.55 + progress * 0.45;
        }

        var textOpacity = 1 - Math.min(rawProgress / 0.5, 1);
        if (heroContent) {
            heroContent.style.opacity = textOpacity;
            heroContent.style.pointerEvents = textOpacity < 0.05 ? 'none' : 'auto';
        }
        if (hint) hint.style.opacity = textOpacity;

        if (rawProgress > 0.45) {
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