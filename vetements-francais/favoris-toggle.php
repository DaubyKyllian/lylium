<?php
require 'includes/session.php';
exigerConnexion();
require 'includes/db.php';

$produitId = isset($_POST['produit_id']) ? (int)$_POST['produit_id'] : 0;
$user = utilisateurConnecte();

if ($produitId > 0 && csrfVerifie($_POST['csrf_token'] ?? null)) {
    $stmt = $pdo->prepare('SELECT id FROM favoris WHERE user_id = ? AND produit_id = ?');
    $stmt->execute([$user['id'], $produitId]);
    $existant = $stmt->fetch();

    if ($existant) {
        $stmt = $pdo->prepare('DELETE FROM favoris WHERE id = ?');
        $stmt->execute([$existant['id']]);
    } else {
        $stmt = $pdo->prepare('INSERT INTO favoris (user_id, produit_id) VALUES (?, ?)');
        $stmt->execute([$user['id'], $produitId]);
    }
}

$retour = $_POST['retour'] ?? 'collection.php';
header('Location: ' . $retour);
exit;