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
?>

<section class="account-hero">
    <div class="grain-overlay" aria-hidden="true"></div>
    <div class="account-hero-overlay">
        <div class="container">
            <div class="account-hero-top">
                <h1>Nous sommes heureux de vous revoir, <?= htmlspecialchars($userData['nom']) ?></h1>
                <a href="deconnexion.php" class="account-logout-link">Se déconnecter</a>
            </div>

            <nav class="account-tabs">
                <a href="mon-compte.php" class="active">Présentation</a>
                <a href="favoris.php">Mes favoris</a>
                <span class="account-tab-disabled">Commandes</span>
                <span class="account-tab-disabled">Adresses</span>
            </nav>
        </div>
    </div>
</section>

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
                <div class="grid grid-3">
                    <?php foreach ($favorisIds as $id): ?>
                        <?php if (isset($produits[$id])): $produit = $produits[$id]; ?>
                            <a href="produit.php?id=<?= $id ?>" class="card produit-card">
                                <div class="card-image" style="background-image:url('<?= htmlspecialchars($produit['image']) ?>')"></div>
                                <h3><?= htmlspecialchars($produit['nom']) ?></h3>
                                <p class="prix"><?= (int)$produit['prix'] ?> €</p>
                            </a>
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