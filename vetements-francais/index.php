<?php
$pageTitle = "Accueil";
$bodyClass = "page-home";
include 'includes/header.php';
include 'includes/navbar.php';
include 'includes/produits-data.php';
?>

<div class="hero-pin-wrapper" id="heroPinWrapper">
    <section class="hero-zoom" id="heroZoom">
        <div class="hero-zoom-bg" id="heroZoomBg"></div>
        <div class="hero-zoom-overlay" id="heroZoomOverlay"></div>
        <div class="hero-zoom-content" id="heroZoomContent">
            <h1>Lylium</h1>
            <p class="subtitle">Vêtements français, pensés pour durer.</p>
            <a href="/collection.php" class="btn">Découvrir la collection</a>
        </div>
        <span class="hero-scroll-hint" id="heroScrollHint">Faites défiler ↓</span>
    </section>
</div>

<section class="section section-alt">
    <div class="container two-col">
        <div>
            <h2 class="reveal">Fabriqué en France</h2>
            <p class="reveal" style="--reveal-delay:0.1s">Chaque vêtement Lylium est conçu et confectionné par des ateliers français,
               avec des matières sélectionnées pour leur qualité et leur durabilité.</p>
            <a href="/marque.php" class="btn btn-outline reveal" style="--reveal-delay:0.2s">Notre histoire</a>
        </div>
        <div class="image-placeholder reveal reveal-image" style="background-image:url('/images/ateliers.jpg');--reveal-delay:0.15s"></div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="section-heading reveal">
            <h2>Nos pièces phares</h2>
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

<section class="editorial-banner reveal">
    <div class="editorial-bg" style="background-image:url('/images/homme-hero.jpg')"></div>
    <div class="editorial-overlay"></div>
    <div class="editorial-content">
        <p class="editorial-quote">« La mode passe, le style reste. »</p>
        <a href="/lookbook.php" class="btn">Découvrir le lookbook</a>
    </div>
</section>

<?php include 'includes/footer.php'; ?>