<?php
$pageTitle = "Collection";
$navActive = "collection";
include 'includes/header.php';
include 'includes/navbar.php';
include 'includes/produits-data.php';

$categorieFiltre = $_GET['categorie'] ?? '';
$tri = $_GET['tri'] ?? '';

$produitsAffiches = $produits;
if (in_array($categorieFiltre, ['homme', 'femme', 'enfant'], true)) {
    $produitsAffiches = array_filter($produitsAffiches, fn($p) => $p['categorie'] === $categorieFiltre);
}

switch ($tri) {
    case 'prix_asc':
        uasort($produitsAffiches, fn($a, $b) => $a['prix'] <=> $b['prix']);
        break;
    case 'prix_desc':
        uasort($produitsAffiches, fn($a, $b) => $b['prix'] <=> $a['prix']);
        break;
    case 'nom':
        uasort($produitsAffiches, fn($a, $b) => strcmp($a['nom'], $b['nom']));
        break;
}
?>

<section class="page-banner page-banner--dark">
    <div class="grain-overlay" aria-hidden="true"></div>
    <div class="container page-banner-inner">
        <span class="eyebrow eyebrow--light">Toutes nos pièces</span>
        <h1>La collection</h1>
        <p class="intro intro--light">Toutes nos pièces, fabriquées en France.</p>
    </div>
</section>

<section class="section">
    <div class="container">

        <form method="get" class="collection-toolbar">
            <div class="toolbar-field">
                <label for="categorie">Catégorie</label>
                <select id="categorie" name="categorie">
                    <option value="" <?= $categorieFiltre === '' ? 'selected' : '' ?>>Toutes</option>
                    <option value="homme" <?= $categorieFiltre === 'homme' ? 'selected' : '' ?>>Homme</option>
                    <option value="femme" <?= $categorieFiltre === 'femme' ? 'selected' : '' ?>>Femme</option>
                    <option value="enfant" <?= $categorieFiltre === 'enfant' ? 'selected' : '' ?>>Enfant</option>
                </select>
            </div>
            <div class="toolbar-field">
                <label for="tri">Trier par</label>
                <select id="tri" name="tri">
                    <option value="" <?= $tri === '' ? 'selected' : '' ?>>Pertinence</option>
                    <option value="prix_asc" <?= $tri === 'prix_asc' ? 'selected' : '' ?>>Prix croissant</option>
                    <option value="prix_desc" <?= $tri === 'prix_desc' ? 'selected' : '' ?>>Prix décroissant</option>
                    <option value="nom" <?= $tri === 'nom' ? 'selected' : '' ?>>Nom A→Z</option>
                </select>
            </div>
            <button type="submit" class="btn btn-outline">Filtrer</button>
        </form>

        <p class="category-count">
            <?= count($produitsAffiches) ?> article<?= count($produitsAffiches) > 1 ? 's' : '' ?>
        </p>

        <?php if (empty($produitsAffiches)): ?>
            <div class="account-empty">
                <p>Aucun produit ne correspond à ce filtre.</p>
                <a href="/collection.php" class="btn btn-outline">Réinitialiser</a>
            </div>
        <?php else: ?>
            <div class="catalogue-grid">
                <?php foreach ($produitsAffiches as $id => $produit): ?>
                    <?php include 'includes/produit-card.php'; ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
