<?php
$pageTitle = "La marque";
$navActive = "marque";
include 'includes/header.php';
include 'includes/navbar.php';
?>

<section class="page-banner">
    <div class="grain-overlay" aria-hidden="true"></div>
    <div class="container page-banner-inner">
        <p class="breadcrumb breadcrumb--light"><a href="/index.php">Accueil</a> / La marque</p>
        <span class="eyebrow eyebrow--light">Notre histoire</span>
        <h1>La marque</h1>
        <p class="intro intro--light">Lylium est né d'une conviction simple : on peut s'habiller avec exigence
           sans participer à la surproduction.</p>
    </div>
</section>

<section class="section split-intro">
    <div class="container split-intro-grid">
        <p class="split-intro-quote reveal">Nous dessinons des pièces destinées à être portées pendant des années,
           pas seulement des saisons — et confectionnées à quelques centaines de kilomètres de chez vous, pas de
           l'autre côté du monde.</p>
        <div class="split-intro-aside reveal" style="--reveal-delay:0.15s">
            <span class="eyebrow">Depuis le début</span>
            <p>Une poignée d'ateliers partenaires, visités et connus, plutôt qu'une chaîne de sous-traitants anonymes à l'autre bout du monde.</p>
            <a href="/lookbook.php" class="link-underline">Voir le lookbook →</a>
        </div>
    </div>
</section>

<section class="section section-alt craft-strip">
    <div class="container">
        <div class="values-heading reveal">
            <span class="eyebrow">En quelques repères</span>
            <h2>Une production pensée, pas subie.</h2>
        </div>
        <ol class="craft-steps">
            <li class="craft-step reveal">
                <span class="craft-num">01</span>
                <h3>Ateliers partenaires</h3>
                <p>Chaque pièce est coupée et cousue en France, dans des ateliers que nous connaissons et visitons.</p>
            </li>
            <li class="craft-step reveal" style="--reveal-delay:0.12s">
                <span class="craft-num">02</span>
                <h3>Petites séries</h3>
                <p>Nous produisons à la demande, en quantités limitées — jamais de stock dormant à écouler.</p>
            </li>
            <li class="craft-step reveal" style="--reveal-delay:0.24s">
                <span class="craft-num">03</span>
                <h3>Matières traçables</h3>
                <p>Lin, laine et coton choisis pour leur origine et leur tenue dans le temps.</p>
            </li>
        </ol>
    </div>
</section>

<section class="quote-band">
    <div class="grain-overlay" aria-hidden="true"></div>
    <div class="quote-band-inner">
        <span class="quote-mark reveal-image reveal" aria-hidden="true">&ldquo;</span>
        <p class="quote-band-text reveal" style="--reveal-delay:0.1s">Un vêtement bien fait ne devrait jamais avoir besoin d'être remplacé.</p>
        <a href="/collection.php" class="btn hero-btn reveal" style="--reveal-delay:0.2s">Découvrir la collection</a>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
