<?php
require 'includes/session.php';
require 'includes/db.php';
require 'includes/produits-data.php';

// Page volontairement non référencée (pas de lien dans la navigation, pas
// d'indexation) : on n'y accède qu'en scannant le QR code collé sur la
// pièce physique.
$noIndex = true;
$pageTitle = "Certificat d'authenticité";

$code = $_GET['code'] ?? '';
$certificat = null;
if ($code !== '') {
    $stmt = $pdo->prepare('SELECT * FROM certificats_authenticite WHERE code = ?');
    $stmt->execute([$code]);
    $certificat = $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
}

$produitCertificat = $certificat ? ($produits[$certificat['produit_id']] ?? null) : null;
$appartientAAutrui = false;

if ($certificat && estConnecte()) {
    $user = utilisateurConnecte();

    if ($certificat['user_id'] === null) {
        // Personne ne l'a encore réclamée : en l'absence de tunnel de
        // commande, le premier compte connecté à scanner ce QR code devient
        // le propriétaire enregistré de la pièce.
        $stmt = $pdo->prepare('UPDATE certificats_authenticite SET user_id = ?, date_reclamation = CURRENT_TIMESTAMP WHERE id = ?');
        $stmt->execute([$user['id'], $certificat['id']]);
        $certificat['user_id'] = $user['id'];
        $certificat['date_reclamation'] = date('Y-m-d H:i:s');
    } elseif ((int)$certificat['user_id'] !== (int)$user['id']) {
        $appartientAAutrui = true;
    }
}

include 'includes/header.php';
include 'includes/navbar.php';
?>

<section class="section certificat-section">
    <div class="container narrow">
        <?php if (!$certificat || !$produitCertificat): ?>
            <div class="account-empty">
                <p>Ce code n'est associé à aucun certificat d'authenticité Lylium.</p>
                <a href="/index.php" class="btn btn-outline">Retour à l'accueil</a>
            </div>

        <?php elseif (!estConnecte()): ?>
            <div class="certificat-card reveal">
                <span class="eyebrow">Certificat d'authenticité</span>
                <h1><?= htmlspecialchars($produitCertificat['nom']) ?></h1>
                <p class="intro">Connectez-vous avec le compte utilisé pour l'achat de cette pièce afin d'accéder à son certificat.</p>
                <a href="/connexion.php?retour=<?= urlencode('/certificat.php?code=' . $code) ?>" class="btn">Se connecter</a>
            </div>

        <?php elseif ($appartientAAutrui): ?>
            <div class="account-empty">
                <p>Cette pièce est déjà enregistrée sur un autre compte.</p>
                <p class="intro">Si vous pensez qu'il s'agit d'une erreur, <a href="/contact.php" class="link-underline">contactez-nous</a>.</p>
            </div>

        <?php else: ?>
            <div class="certificat-card certificat-card--valide reveal">
                <span class="certificat-badge">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true">
                        <circle cx="12" cy="12" r="9"/>
                        <path d="M8 12.5l2.5 2.5L16 9.5"/>
                    </svg>
                    Authentique
                </span>

                <h1><?= htmlspecialchars($produitCertificat['nom']) ?></h1>
                <p class="certificat-numero">
                    N<sup>o</sup> <?= (int)$certificat['numero_serie'] ?> / <?= (int)$certificat['edition_totale'] ?>
                </p>
                <p class="intro">Cette pièce fait partie d'une édition limitée à <?= (int)$certificat['edition_totale'] ?> exemplaires, fabriquée en France par la maison Lylium.</p>

                <?php if (!empty($certificat['date_reclamation'])): ?>
                    <p class="certificat-date">Certifiée le <?= date('d/m/Y', strtotime($certificat['date_reclamation'])) ?></p>
                <?php endif; ?>

                <a href="/produit.php?id=<?= (int)$certificat['produit_id'] ?>" class="link-underline">Voir la pièce</a>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
