<?php
require 'includes/session.php';
require 'includes/db.php';
require 'includes/produits-data.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$produit = $produits[$id] ?? null;

$pageTitle = $produit ? $produit['nom'] : "Produit introuvable";
include 'includes/header.php';
include 'includes/navbar.php';

$estFavori = false;
if ($produit && estConnecte()) {
    $user = utilisateurConnecte();
    $stmt = $pdo->prepare('SELECT id FROM favoris WHERE user_id = ? AND produit_id = ?');
    $stmt->execute([$user['id'], $id]);
    $estFavori = (bool)$stmt->fetch();
}
?>

<section class="section">
    <div class="container">
        <?php if ($produit): ?>
            <div class="two-col produit-fiche">
                <div class="image-placeholder large" style="background-image:url('<?= htmlspecialchars($produit['image']) ?>')"></div>
                <div>
                    <h1><?= htmlspecialchars($produit['nom']) ?></h1>
                    <p class="prix prix-lg"><?= (int)$produit['prix'] ?> €</p>
                    <p><?= htmlspecialchars($produit['description']) ?></p>

                    <?php if (estConnecte()): ?>
                        <form method="post" action="favoris-toggle.php">
                            <input type="hidden" name="produit_id" value="<?= $id ?>">
                            <input type="hidden" name="retour" value="produit.php?id=<?= $id ?>">
                            <button type="submit" class="btn btn-outline">
                                <?= $estFavori ? '★ Retirer des favoris' : '☆ Ajouter aux favoris' ?>
                            </button>
                        </form>
                    <?php else: ?>
                        <p><a href="connexion.php">Connecte-toi</a> pour ajouter ce produit à tes favoris.</p>
                    <?php endif; ?>

                    <a href="contact.php" class="btn">Nous contacter pour ce produit</a>
                </div>
            </div>
        <?php else: ?>
            <h1>Produit introuvable</h1>
            <p>Ce produit n'existe pas ou a été retiré de la collection.</p>
            <a href="collection.php" class="btn btn-outline">Retour à la collection</a>
        <?php endif; ?>
    </div>
</section>

<?php include 'includes/footer.php'; ?>