<?php
require '../includes/produits-data.php';

$pageTitle = "Homme";
$bodyClass = "page-homme";
include '../includes/header.php';
include '../includes/navbar.php';

$produitsFiltres = array_filter($produits, fn($p) => $p['categorie'] === 'homme');
?>

<section class="section category-title-block">
    <div class="container">
        <p class="breadcrumb"><a href="/index.php">Accueil</a> / Homme</p>
        <h1>Homme</h1>
        <p class="intro">La collection homme, fabriquée en France.</p>
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
            <div class="grid grid-4">
                <?php foreach ($produitsFiltres as $id => $produit): ?>
                    <a href="/produit.php?id=<?= $id ?>" class="card produit-card">
                        <div class="card-image" style="background-image:url('<?= htmlspecialchars($produit['image']) ?>')"></div>
                        <h3><?= htmlspecialchars($produit['nom']) ?></h3>
                        <p class="prix"><?= (int)$produit['prix'] ?> €</p>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </div>
</section>

<?php include '../includes/footer.php'; ?>