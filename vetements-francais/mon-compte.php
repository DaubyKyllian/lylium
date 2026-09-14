<?php
require 'includes/session.php';
exigerConnexion();
require 'includes/db.php';

$pageTitle = "Mon compte";
include 'includes/header.php';
include 'includes/navbar.php';

$user = utilisateurConnecte();

$stmt = $pdo->prepare('SELECT * FROM users WHERE id = ?');
$stmt->execute([$user['id']]);
$userData = $stmt->fetch(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare('SELECT produit_id FROM favoris WHERE user_id = ? ORDER BY date_ajout DESC LIMIT 3');
$stmt->execute([$user['id']]);
$favorisIds = $stmt->fetchAll(PDO::FETCH_COLUMN);

require 'includes/produits-data.php';

$initiale = mb_strtoupper(mb_substr($userData['nom'], 0, 1));
$membreDepuis = date('d/m/Y', strtotime($userData['date_creation']));

$userNom = $userData['nom'];
$accountActiveTab = 'presentation';
include 'includes/account-hero.php';
?>

<section class="section">
    <div class="container account-dashboard">

        <div class="account-block">
            <div class="account-block-header">
                <h2>Aperçu de mes favoris</h2>
                <a href="favoris.php" class="account-see-all">Voir tous mes favoris</a>
            </div>

            <?php if (empty($favorisIds)): ?>
                <div class="account-empty">
                    <p>Tu n'as pas encore de favoris.</p>
                    <a href="collection.php" class="btn btn-outline">Découvrir la collection</a>
                </div>
            <?php else: ?>
                <div class="catalogue-grid catalogue-grid--compact">
                    <?php foreach ($favorisIds as $id): ?>
                        <?php if (isset($produits[$id])): $produit = $produits[$id]; ?>
                            <?php include 'includes/produit-card.php'; ?>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="account-block account-profile-card">
            <h2>Mon profil</h2>
            <div class="account-avatar-lg"><?= htmlspecialchars($initiale) ?></div>
            <p class="account-profile-name"><?= htmlspecialchars($userData['nom']) ?></p>
            <p class="account-profile-email"><?= htmlspecialchars($userData['email']) ?></p>
            <p class="account-profile-since">Membre depuis le <?= $membreDepuis ?></p>
        </div>

    </div>
</section>

<?php include 'includes/footer.php'; ?>