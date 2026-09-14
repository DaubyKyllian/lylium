<?php
require 'includes/produits-data.php';

$pageTitle = "Recherche";
include 'includes/header.php';
include 'includes/navbar.php';

$q = trim($_GET['q'] ?? '');
$resultats = [];

if ($q !== '') {
    foreach ($produits as $id => $produit) {
        if (mb_stripos($produit['nom'], $q) !== false || mb_stripos($produit['description'], $q) !== false) {
            $resultats[$id] = $produit;
        }
    }
}
?>

<section class="page-banner page-banner--dark">
    <div class="grain-overlay" aria-hidden="true"></div>
    <div class="container page-banner-inner">
        <span class="eyebrow eyebrow--light">Recherche</span>
        <h1>Que cherchez-vous ?</h1>
        <form method="get" class="search-form">
            <input type="search" name="q" value="<?= htmlspecialchars($q) ?>" placeholder="Un nom, une matière, une couleur…" autofocus>
            <button type="submit" aria-label="Rechercher">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round">
                    <circle cx="11" cy="11" r="7"/>
                    <line x1="21" y1="21" x2="16.5" y2="16.5"/>
                </svg>
            </button>
        </form>
    </div>
</section>

<section class="section">
    <div class="container">
        <?php if ($q === ''): ?>
            <p class="intro">Tapez un mot-clé pour explorer la collection.</p>
        <?php elseif (empty($resultats)): ?>
            <div class="account-empty">
                <p>Aucun résultat pour « <?= htmlspecialchars($q) ?> ».</p>
                <a href="/collection.php" class="btn btn-outline">Voir toute la collection</a>
            </div>
        <?php else: ?>
            <p class="category-count">
                <?= count($resultats) ?> résultat<?= count($resultats) > 1 ? 's' : '' ?> pour « <?= htmlspecialchars($q) ?> »
            </p>
            <div class="catalogue-grid">
                <?php foreach ($resultats as $id => $produit): ?>
                    <?php include 'includes/produit-card.php'; ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
