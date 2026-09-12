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
?>

<section class="section">
    <div class="container">
        <h1>Mes favoris</h1>

        <?php if (empty($favorisIds)): ?>
            <p class="intro">Tu n'as pas encore ajouté de favoris.</p>
            <a href="collection.php" class="btn btn-outline">Voir la collection</a>
        <?php else: ?>
            <div class="grid grid-4">
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
</section>

<?php include 'includes/footer.php'; ?>