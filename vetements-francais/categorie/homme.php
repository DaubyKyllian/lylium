<?php
require '../includes/produits-data.php';

$pageTitle = "Homme";
$navActive = "homme";
include '../includes/header.php';
include '../includes/navbar.php';

$produitsFiltres = array_filter($produits, fn($p) => $p['categorie'] === 'homme');
?>

<section class="page-banner page-banner--dark">
    <div class="grain-overlay" aria-hidden="true"></div>
    <div class="container page-banner-inner">
        <p class="breadcrumb breadcrumb--light"><a href="/index.php">Accueil</a> / Homme</p>
        <span class="eyebrow eyebrow--light">Collection</span>
        <h1>Homme</h1>
        <p class="intro intro--light">La collection homme, fabriquée en France.</p>
    </div>
</section>

<section class="section">
    <div class="container">

        <div class="category-toolbar">
            <p class="category-count">
                <?= count($produitsFiltres) ?> article<?= count($produitsFiltres) > 1 ? 's' : '' ?>
            </p>
        </div>

        <?php if (empty($produitsFiltres)): ?>
            <div class="account-empty">
                <p>Aucun produit dans cette catégorie pour le moment.</p>
                <a href="/collection.php" class="btn btn-outline">Voir toute la collection</a>
            </div>
        <?php else: ?>
            <div class="catalogue-grid">
                <?php foreach ($produitsFiltres as $id => $produit): ?>
                    <?php include '../includes/produit-card.php'; ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </div>
</section>

<?php include '../includes/footer.php'; ?>
