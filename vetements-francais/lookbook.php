<?php
require 'includes/produits-data.php';

$pageTitle = "Lookbook";
include 'includes/header.php';
include 'includes/navbar.php';
?>

<section class="section category-title-block">
    <div class="container">
        <p class="breadcrumb"><a href="/index.php">Accueil</a> / Lookbook</p>
        <span class="eyebrow">Lookbook</span>
        <h1>Silhouettes</h1>
        <p class="intro">Quelques pièces, plusieurs façons de les porter — pas de collection capsule,
           juste des vêtements qui s'associent naturellement.</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="look-diptych">
            <a href="produit.php?id=5" class="look-frame reveal reveal-image">
                <div class="look-image" style="--img:url('/<?= htmlspecialchars($produits[5]['image']) ?>')"></div>
            </a>
            <a href="produit.php?id=3" class="look-frame reveal reveal-image" style="--reveal-delay:0.12s">
                <div class="look-image" style="--img:url('/<?= htmlspecialchars($produits[3]['image']) ?>')"></div>
            </a>
        </div>
        <p class="look-caption reveal" style="--reveal-delay:0.2s">
            Le vestiaire essentiel — <a href="produit.php?id=5" class="link-underline"><?= htmlspecialchars($produits[5]['nom']) ?></a>
            porté avec le <a href="produit.php?id=3" class="link-underline"><?= htmlspecialchars($produits[3]['nom']) ?></a>.
        </p>
    </div>
</section>

<section class="section section-alt">
    <div class="container">
        <div class="values-heading reveal">
            <span class="eyebrow">Notre approche du style</span>
            <h2>Composer, plutôt qu'accumuler.</h2>
        </div>
        <div class="values-grid">
            <div class="value-item reveal">
                <span class="value-index">01</span>
                <h3>Superposer sobrement</h3>
                <p>Une matière à la fois, jamais plus de deux textures dans une même tenue.</p>
            </div>
            <div class="value-item reveal" style="--reveal-delay:0.12s">
                <span class="value-index">02</span>
                <h3>Miser sur les fondamentaux</h3>
                <p>Des pièces pensées pour se marier entre elles, saison après saison.</p>
            </div>
            <div class="value-item reveal" style="--reveal-delay:0.24s">
                <span class="value-index">03</span>
                <h3>Un totem par silhouette</h3>
                <p>Toujours un détail signature qui distingue la tenue, jamais plus.</p>
            </div>
        </div>
    </div>
</section>

<section class="editorial-banner">
    <div class="grain-overlay" aria-hidden="true"></div>
    <div class="editorial-content">
        <div class="editorial-rule reveal"></div>
        <p class="editorial-quote reveal" style="--reveal-delay:0.1s">« Le style ne s'achète pas en une saison, il se construit. »</p>
        <a href="/collection.php" class="btn hero-btn reveal" style="--reveal-delay:0.2s">Voir toute la collection</a>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
