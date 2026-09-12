<?php
$pageTitle = "Collection";
$navActive = "collection";
include 'includes/header.php';
include 'includes/navbar.php';
include 'includes/produits-data.php';
?>

<section class="section">
    <div class="container">
        <span class="eyebrow">Toutes nos pièces</span>
        <h1>La collection</h1>
        <p class="intro">Toutes nos pièces, fabriquées en France.</p>

        <div class="grid grid-4">
            <?php foreach ($produits as $id => $produit): ?>
                <?php include 'includes/produit-card.php'; ?>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>