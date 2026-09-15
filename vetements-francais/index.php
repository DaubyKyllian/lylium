<?php
$pageTitle = "Accueil";
$bodyClass = "page-home";
include 'includes/header.php';
include 'includes/navbar.php';
include 'includes/produits-data.php';
?>

<div class="site-intro" id="siteIntro">
    <div class="site-intro-rule"></div>
    <div class="site-intro-logo" aria-hidden="true">
        <span>L</span><span>Y</span><span>L</span><span>I</span><span>U</span><span>M</span>
    </div>
    <p class="site-intro-tagline">Maison française — depuis toujours pensée pour durer</p>
</div>
<script>
    (function () {
        try {
            if (sessionStorage.getItem('lylium_intro_seen')) {
                document.getElementById('siteIntro').classList.add('site-intro--skip');
            }
        } catch (e) {}
    })();
</script>

<section class="hero" id="hero">
    <div class="hero-scrim" aria-hidden="true"></div>
    <div class="grain-overlay" aria-hidden="true"></div>

    <div class="hero-frame container">
        <div class="hero-top-row">
            <span class="hero-ref">Lylium — depuis toujours</span>
        </div>

        <h1 class="hero-title">LYLIUM</h1>
        <p class="hero-subtitle">Un emblème<br>Une histoire<br><em>Réécrite</em></p>

        <div class="hero-bottom-row">
            <a href="/collection.php" class="btn hero-btn">Découvrir la collection</a>
        </div>
    </div>

    <div class="hero-ticker" aria-hidden="true">
        <div class="hero-ticker-track">
            <span class="hero-ticker-set">
                <span>Fabriqué en France</span><span>✦</span>
                <span>Matières nobles</span><span>✦</span>
                <span>Éditions limitées</span><span>✦</span>
                <span>Fabriqué en France</span><span>✦</span>
                <span>Matières nobles</span><span>✦</span>
                <span>Éditions limitées</span><span>✦</span>
            </span>
            <span class="hero-ticker-set">
                <span>Fabriqué en France</span><span>✦</span>
                <span>Matières nobles</span><span>✦</span>
                <span>Éditions limitées</span><span>✦</span>
                <span>Fabriqué en France</span><span>✦</span>
                <span>Matières nobles</span><span>✦</span>
                <span>Éditions limitées</span><span>✦</span>
            </span>
        </div>
    </div>
    <span class="hero-scroll-hint" id="heroScrollHint">Faites défiler ↓</span>
</section>

<section class="section split-intro">
    <div class="container split-intro-grid">
        <p class="split-intro-quote reveal">Chaque pièce Lylium est conçue et confectionnée par des ateliers français, pour traverser les saisons — pas seulement les tendances.</p>
        <div class="split-intro-aside reveal" style="--reveal-delay:0.15s">
            <span class="eyebrow">Notre engagement</span>
            <p>Nous travaillons en petites séries avec une poignée d'ateliers partenaires, du Nord à l'Auvergne, choisis pour leur savoir-faire plutôt que pour leurs prix.</p>
            <a href="/marque.php" class="link-underline">Découvrir notre histoire →</a>
        </div>
    </div>
</section>

<section class="section section-alt">
    <div class="container">
        <div class="section-heading reveal">
            <div>
                <span class="eyebrow">La sélection</span>
                <h2>Nos pièces phares</h2>
            </div>
            <a href="/collection.php" class="link-underline">Voir toute la collection</a>
        </div>

        <div class="feature-grid">
            <a href="produit.php?id=5" class="feature-tile feature-tile--hero reveal">
                <div class="feature-tile-image" style="--img:url('/<?= htmlspecialchars($produits[5]['image']) ?>')"></div>
                <div class="feature-tile-meta">
                    <h3><?= htmlspecialchars($produits[5]['nom']) ?></h3>
                    <p class="prix"><?= (int)$produits[5]['prix'] ?> €</p>
                </div>
            </a>
            <a href="produit.php?id=3" class="feature-tile reveal" style="--reveal-delay:0.1s">
                <div class="feature-tile-image" style="--img:url('/<?= htmlspecialchars($produits[3]['image']) ?>')"></div>
                <div class="feature-tile-meta">
                    <h3><?= htmlspecialchars($produits[3]['nom']) ?></h3>
                    <p class="prix"><?= (int)$produits[3]['prix'] ?> €</p>
                </div>
            </a>
            <a href="/collection.php" class="feature-tile feature-tile--cta reveal" style="--reveal-delay:0.2s">
                <span class="feature-tile-cta-label">Voir toute<br>la collection</span>
                <span class="feature-tile-cta-arrow" aria-hidden="true">→</span>
            </a>
        </div>
    </div>
</section>

<section class="section craft-strip">
    <div class="container">
        <div class="values-heading reveal">
            <span class="eyebrow">Pourquoi Lylium</span>
            <h2>Une exigence à chaque étape.</h2>
        </div>
        <ol class="craft-steps">
            <li class="craft-step reveal">
                <span class="craft-num">01</span>
                <h3>Matières nobles</h3>
                <p>Lin, laine mérinos, coton peigné — choisis pour leur tenue dans le temps, pas pour leur prix.</p>
            </li>
            <li class="craft-step reveal" style="--reveal-delay:0.1s">
                <span class="craft-num">02</span>
                <h3>Ateliers français</h3>
                <p>Chaque pièce est coupée et cousue dans un atelier partenaire, en petite série.</p>
            </li>
            <li class="craft-step reveal" style="--reveal-delay:0.2s">
                <span class="craft-num">03</span>
                <h3>Contrôle qualité</h3>
                <p>Chaque finition est vérifiée à la main avant expédition, sans exception.</p>
            </li>
            <li class="craft-step reveal" style="--reveal-delay:0.3s">
                <span class="craft-num">04</span>
                <h3>Pensé pour durer</h3>
                <p>Des coupes intemporelles, loin des collections jetables et des tendances éphémères.</p>
            </li>
        </ol>
    </div>
</section>

<section class="quote-band">
    <div class="grain-overlay" aria-hidden="true"></div>
    <div class="quote-band-inner">
        <span class="quote-mark reveal-image reveal" aria-hidden="true">&ldquo;</span>
        <p class="quote-band-text reveal" style="--reveal-delay:0.1s">L'élégance ne se réinvente pas, elle se réécrit.</p>
        <a href="/lookbook.php" class="btn hero-btn reveal" style="--reveal-delay:0.2s">Découvrir le lookbook</a>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
