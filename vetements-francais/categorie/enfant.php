<?php
require '../includes/produits-data.php';

$pageTitle = "Enfant";
$navActive = "enfant";
include '../includes/header.php';
include '../includes/navbar.php';

$produit = $produits[5];
?>

<section class="page-banner">
    <div class="container page-banner-inner">
        <p class="breadcrumb"><a href="/index.php">Accueil</a> / Enfant</p>
        <span class="eyebrow">Collection</span>
        <h1>Enfant</h1>
        <p class="intro">La collection enfant, fabriquée en France.</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="category-feature-single">
            <a href="/produit.php?id=5" class="produit-card">
                <div class="card-image card-image--photo">
                    <img src="<?= htmlspecialchars($produit['image']) ?>" alt="<?= htmlspecialchars($produit['nom']) ?>" loading="lazy" decoding="async" onload="this.classList.add('is-loaded')">
                </div>
                <h3><?= htmlspecialchars($produit['nom']) ?></h3>
                <p class="prix"><?= (int)$produit['prix'] ?> €</p>
            </a>
        </div>
    </div>
</section>

<?php include '../includes/footer.php'; ?>
