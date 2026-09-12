<?php
$pageTitle = "Collection";
include 'includes/header.php';
include 'includes/navbar.php';
include 'includes/produits-data.php';
?>

<section class="section">
    <div class="container">
        <h1>La collection</h1>
        <p class="intro">Toutes nos pièces, fabriquées en France.</p>

        <div class="grid grid-4">
            <?php foreach ($produits as $id => $produit): ?>
                <a href="produit.php?id=<?= $id ?>" class="card produit-card">
                    <div class="card-image" style="background-image:url('<?= htmlspecialchars($produit['image']) ?>')"></div>
                    <h3><?= htmlspecialchars($produit['nom']) ?></h3>
                    <p class="prix"><?= (int)$produit['prix'] ?> €</p>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>