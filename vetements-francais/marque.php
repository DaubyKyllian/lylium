<?php
$pageTitle = "La marque";
$navActive = "marque";
include 'includes/header.php';
include 'includes/navbar.php';
?>

<section class="section category-title-block">
    <div class="container">
        <p class="breadcrumb"><a href="/index.php">Accueil</a> / La marque</p>
        <span class="eyebrow">Notre histoire</span>
        <h1>La marque</h1>
        <p class="intro">Lylium est né d'une conviction simple : on peut s'habiller avec exigence
           sans participer à la surproduction.</p>
    </div>
</section>

<section class="section section-alt manifesto">
    <div class="container narrow manifesto-inner">
        <p class="manifesto-statement reveal">Nous dessinons des pièces destinées à être portées
           pendant des années, pas seulement des saisons — et confectionnées à quelques centaines
           de kilomètres de chez vous, pas de l'autre côté du monde.</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="values-heading reveal">
            <span class="eyebrow">En quelques repères</span>
            <h2>Une production pensée, pas subie.</h2>
        </div>
        <div class="values-grid">
            <div class="value-item reveal">
                <span class="value-index">01</span>
                <h3>Ateliers partenaires</h3>
                <p>Chaque pièce est coupée et cousue en France, dans des ateliers que nous connaissons et visitons.</p>
            </div>
            <div class="value-item reveal" style="--reveal-delay:0.12s">
                <span class="value-index">02</span>
                <h3>Petites séries</h3>
                <p>Nous produisons à la demande, en quantités limitées — jamais de stock dormant à écouler.</p>
            </div>
            <div class="value-item reveal" style="--reveal-delay:0.24s">
                <span class="value-index">03</span>
                <h3>Matières traçables</h3>
                <p>Lin, laine et coton choisis pour leur origine et leur tenue dans le temps.</p>
            </div>
        </div>
    </div>
</section>

<section class="editorial-banner">
    <div class="grain-overlay" aria-hidden="true"></div>
    <div class="editorial-content">
        <div class="editorial-rule reveal"></div>
        <p class="editorial-quote reveal" style="--reveal-delay:0.1s">« Un vêtement bien fait ne devrait jamais avoir besoin d'être remplacé. »</p>
        <a href="/collection.php" class="btn hero-btn reveal" style="--reveal-delay:0.2s">Découvrir la collection</a>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
