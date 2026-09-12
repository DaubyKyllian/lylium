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
    <div class="hero-grain" aria-hidden="true"></div>
    <div class="hero-content" id="heroContent">
        <h1>Lylium</h1>
        <p class="subtitle">Vêtements français, pensés pour durer.</p>
        <a href="/collection.php" class="btn hero-btn">Découvrir la collection</a>
    </div>
    <div class="hero-marquee" aria-hidden="true">
        <div class="hero-marquee-track">
            <span>Fabriqué en France</span><span>✦</span>
            <span>Matières nobles</span><span>✦</span>
            <span>Éditions limitées</span><span>✦</span>
            <span>Fabriqué en France</span><span>✦</span>
            <span>Matières nobles</span><span>✦</span>
            <span>Éditions limitées</span><span>✦</span>
        </div>
    </div>
    <span class="hero-scroll-hint" id="heroScrollHint">Faites défiler ↓</span>
</section>

<section class="section section-alt manifesto">
    <div class="container narrow manifesto-inner">
        <span class="eyebrow reveal">Notre engagement</span>
        <p class="manifesto-statement reveal" style="--reveal-delay:0.08s">Chaque pièce Lylium est conçue et confectionnée par des ateliers français,
           pour traverser les saisons — pas seulement les tendances.</p>
        <a href="/marque.php" class="link-underline reveal" style="--reveal-delay:0.18s">Découvrir notre histoire</a>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="section-heading reveal">
            <div>
                <span class="eyebrow">La sélection</span>
                <h2>Nos pièces phares</h2>
            </div>
            <a href="/collection.php" class="link-underline">Voir toute la collection</a>
        </div>

        <div class="grid grid-3 featured-grid">
            <a href="produit.php?id=5" class="card produit-card reveal">
                <div class="card-image featured-image" style="--img:url('/<?= htmlspecialchars($produits[5]['image']) ?>')"></div>
                <h3><?= htmlspecialchars($produits[5]['nom']) ?></h3>
                <p class="prix"><?= (int)$produits[5]['prix'] ?> €</p>
            </a>
            <a href="produit.php?id=3" class="card produit-card reveal" style="--reveal-delay:0.1s">
                <div class="card-image featured-image" style="--img:url('/<?= htmlspecialchars($produits[3]['image']) ?>')"></div>
                <h3><?= htmlspecialchars($produits[3]['nom']) ?></h3>
                <p class="prix"><?= (int)$produits[3]['prix'] ?> €</p>
            </a>
            <a href="/collection.php" class="produit-cta reveal" style="--reveal-delay:0.2s">
                <span class="produit-cta-label">Voir toute<br>la collection</span>
                <span class="produit-cta-arrow" aria-hidden="true">→</span>
            </a>
        </div>
    </div>
</section>

<section class="section section-alt values-section">
    <div class="container">
        <div class="values-heading reveal">
            <span class="eyebrow">Pourquoi Lylium</span>
            <h2>Une exigence à chaque étape.</h2>
        </div>
        <div class="values-grid">
            <div class="value-item reveal">
                <span class="value-index">01</span>
                <h3>Matières nobles</h3>
                <p>Lin, laine mérinos, coton peigné — choisis pour leur tenue dans le temps, pas pour leur prix.</p>
            </div>
            <div class="value-item reveal" style="--reveal-delay:0.12s">
                <span class="value-index">02</span>
                <h3>Ateliers français</h3>
                <p>Chaque pièce est coupée et cousue dans un atelier partenaire, en petite série.</p>
            </div>
            <div class="value-item reveal" style="--reveal-delay:0.24s">
                <span class="value-index">03</span>
                <h3>Pensé pour durer</h3>
                <p>Des coupes intemporelles, loin des collections jetables et des tendances éphémères.</p>
            </div>
        </div>
    </div>
</section>

<section class="editorial-banner">
    <div class="hero-grain" aria-hidden="true"></div>
    <div class="editorial-content">
        <div class="editorial-rule reveal"></div>
        <p class="editorial-quote reveal" style="--reveal-delay:0.1s">« La mode passe, le style reste. »</p>
        <a href="/lookbook.php" class="btn hero-btn reveal" style="--reveal-delay:0.2s">Découvrir le lookbook</a>
    </div>
</section>

<?php include 'includes/footer.php'; ?>