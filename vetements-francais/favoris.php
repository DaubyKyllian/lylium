<?php
require 'includes/session.php';
exigerConnexion();
require 'includes/db.php';
require 'includes/produits-data.php';

$pageTitle = "Mes favoris";
include 'includes/header.php';
include 'includes/navbar.php';

$user = utilisateurConnecte();

$stmt = $pdo->prepare('SELECT produit_id FROM favoris WHERE user_id = ? ORDER BY date_ajout DESC');
$stmt->execute([$user['id']]);
$favorisIds = $stmt->fetchAll(PDO::FETCH_COLUMN);

$userNom = $user['nom'];
$accountActiveTab = 'favoris';
include 'includes/account-hero.php';
?>

<section class="section">
    <div class="container">
        <?php if (empty($favorisIds)): ?>
            <div class="account-empty">
                <p>Tu n'as pas encore ajouté de favoris.</p>
                <a href="collection.php" class="btn btn-outline">Voir la collection</a>
            </div>
        <?php else: ?>
            <div class="catalogue-grid">
                <?php foreach ($favorisIds as $id): ?>
                    <?php if (isset($produits[$id])): $produit = $produits[$id]; ?>
                        <?php include 'includes/produit-card.php'; ?>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include 'includes/footer.php'; ?>