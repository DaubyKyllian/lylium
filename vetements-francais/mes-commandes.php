<?php
require 'includes/session.php';
exigerConnexion();
require 'includes/db.php';

$pageTitle = "Mes commandes";
include 'includes/header.php';
include 'includes/navbar.php';

$user = utilisateurConnecte();

$stmt = $pdo->prepare('SELECT * FROM commandes WHERE user_id = ? ORDER BY date_creation DESC');
$stmt->execute([$user['id']]);
$commandes = $stmt->fetchAll(PDO::FETCH_ASSOC);

$userNom = $user['nom'];
$accountActiveTab = 'commandes';
include 'includes/account-hero.php';

$statutsLabels = [
    'en_attente' => 'En attente',
    'expediee' => 'Expédiée',
    'livree' => 'Livrée',
    'annulee' => 'Annulée',
];
?>

<section class="section">
    <div class="container">
        <?php if (empty($commandes)): ?>
            <div class="account-empty">
                <p>Tu n'as pas encore passé de commande.</p>
                <a href="collection.php" class="btn btn-outline">Découvrir la collection</a>
            </div>
        <?php else: ?>
            <div class="commandes-liste">
                <?php foreach ($commandes as $commande): ?>
                    <div class="commande-item">
                        <div>
                            <p class="commande-numero">Commande #<?= (int)$commande['id'] ?></p>
                            <p class="commande-date"><?= date('d/m/Y', strtotime($commande['date_creation'])) ?></p>
                        </div>
                        <span class="commande-statut commande-statut--<?= htmlspecialchars($commande['statut']) ?>">
                            <?= htmlspecialchars($statutsLabels[$commande['statut']] ?? $commande['statut']) ?>
                        </span>
                        <p class="commande-total"><?= (int)$commande['total'] ?> €</p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
