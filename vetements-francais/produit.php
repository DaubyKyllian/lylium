<?php
require 'includes/session.php';
require 'includes/db.php';
require 'includes/produits-data.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$produit = $produits[$id] ?? null;

$avisErrors = [];
$avisSuccess = false;

if ($produit && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'ajouter_avis') {
    $avisNom = trim($_POST['avis_nom'] ?? '');
    $avisNote = (int)($_POST['avis_note'] ?? 0);
    $avisCommentaire = trim($_POST['avis_commentaire'] ?? '');

    if (!csrfVerifie($_POST['csrf_token'] ?? null)) $avisErrors[] = "Session expirée, merci de renvoyer le formulaire.";
    if ($avisNom === '') $avisErrors[] = "Le nom est requis.";
    if ($avisNote < 1 || $avisNote > 5) $avisErrors[] = "Merci de choisir une note entre 1 et 5.";
    if ($avisCommentaire === '') $avisErrors[] = "Le commentaire ne peut pas être vide.";

    if (empty($avisErrors)) {
        $userId = estConnecte() ? utilisateurConnecte()['id'] : null;
        $stmt = $pdo->prepare('INSERT INTO avis (produit_id, user_id, nom, note, commentaire) VALUES (?, ?, ?, ?, ?)');
        $stmt->execute([$id, $userId, $avisNom, $avisNote, $avisCommentaire]);
        $avisSuccess = true;
    }
}

$pageTitle = $produit ? $produit['nom'] : "Produit introuvable";
include 'includes/header.php';
include 'includes/navbar.php';

$estFavori = false;
$avisListe = [];
$avisMoyenne = null;
if ($produit) {
    if (estConnecte()) {
        $user = utilisateurConnecte();
        $stmt = $pdo->prepare('SELECT id FROM favoris WHERE user_id = ? AND produit_id = ?');
        $stmt->execute([$user['id'], $id]);
        $estFavori = (bool)$stmt->fetch();
    }

    $stmt = $pdo->prepare('SELECT nom, note, commentaire, date_creation FROM avis WHERE produit_id = ? ORDER BY date_creation DESC');
    $stmt->execute([$id]);
    $avisListe = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!empty($avisListe)) {
        $avisMoyenne = array_sum(array_column($avisListe, 'note')) / count($avisListe);
    }
}

$similaires = [];
if ($produit) {
    $similaires = array_filter($produits, function ($p, $pid) use ($id, $produit) {
        return $pid !== $id && $p['categorie'] === $produit['categorie'];
    }, ARRAY_FILTER_USE_BOTH);

    if (count($similaires) < 4) {
        foreach ($produits as $pid => $p) {
            if (count($similaires) >= 4) break;
            if ($pid !== $id && !array_key_exists($pid, $similaires)) $similaires[$pid] = $p;
        }
    }

    $similaires = array_slice($similaires, 0, 4, true);
}

function etoiles($note) {
    $pleines = round($note);
    return str_repeat('★', (int)$pleines) . str_repeat('☆', 5 - (int)$pleines);
}
?>

<?php if ($produit): ?>
    <section class="product-viewer">
        <div class="product-viewer-gallery">
            <?php if (!empty($produit['image'])): ?>
                <div class="product-viewer-image" style="background-image:url('<?= htmlspecialchars($produit['image']) ?>')"></div>
            <?php else: ?>
                <div class="product-viewer-image card-image--swatch" style="background-color:<?= htmlspecialchars($produit['couleur'] ?? '#e9e4da') ?>">
                    <span class="card-image-label"><?= htmlspecialchars($produit['nom']) ?></span>
                </div>
            <?php endif; ?>
        </div>

        <div class="product-viewer-info">
            <p class="breadcrumb"><a href="/index.php">Accueil</a> / <a href="/collection.php">Collection</a> / <?= htmlspecialchars($produit['nom']) ?></p>

            <h1><?= htmlspecialchars($produit['nom']) ?></h1>
            <p class="prix prix-lg"><?= (int)$produit['prix'] ?> €</p>

            <?php if ($avisMoyenne !== null): ?>
                <p class="avis-resume">
                    <span class="avis-etoiles" aria-hidden="true"><?= etoiles($avisMoyenne) ?></span>
                    <?= number_format($avisMoyenne, 1) ?>/5 · <?= count($avisListe) ?> avis
                </p>
            <?php endif; ?>

            <?php if (($produit['stock'] ?? null) === 0): ?>
                <p class="stock-epuise">Rupture de stock temporaire</p>
            <?php elseif (($produit['stock'] ?? 99) <= 5): ?>
                <p class="stock-urgence">
                    <span class="stock-urgence-dot" aria-hidden="true"></span>
                    Plus que <?= (int)$produit['stock'] ?> exemplaire<?= $produit['stock'] > 1 ? 's' : '' ?> en stock
                </p>
            <?php endif; ?>

            <p class="product-viewer-desc"><?= htmlspecialchars($produit['description']) ?></p>

            <?php if (!empty($produit['tailles'])): ?>
                <div class="taille-selecteur">
                    <span class="taille-label">Taille</span>
                    <div class="taille-options">
                        <?php foreach ($produit['tailles'] as $i => $taille): ?>
                            <label class="taille-pill">
                                <input type="radio" name="taille" value="<?= htmlspecialchars($taille) ?>" <?= $i === 0 ? 'checked' : '' ?>>
                                <span><?= htmlspecialchars($taille) ?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <div class="product-viewer-actions">
                <?php if (estConnecte()): ?>
                    <form method="post" action="favoris-toggle.php">
                        <?= csrfChamp() ?>
                        <input type="hidden" name="produit_id" value="<?= $id ?>">
                        <input type="hidden" name="retour" value="produit.php?id=<?= $id ?>">
                        <button type="submit" class="btn btn-outline">
                            <?= $estFavori ? '★ Retirer des favoris' : '☆ Ajouter aux favoris' ?>
                        </button>
                    </form>
                <?php else: ?>
                    <p><a href="connexion.php" class="link-underline">Connecte-toi</a> pour ajouter ce produit à tes favoris.</p>
                <?php endif; ?>

                <a href="contact.php" class="btn">Nous contacter pour ce produit</a>
            </div>

            <ul class="product-viewer-meta">
                <li>Fabriqué en France, en petite série</li>
                <li>Livraison offerte dès 120 €</li>
                <li>Retours gratuits sous 30 jours</li>
            </ul>
        </div>
    </section>

    <section class="section avis-section">
        <div class="container narrow">
            <h2>Avis clients</h2>

            <?php if (empty($avisListe)): ?>
                <p class="intro">Aucun avis pour le moment — soyez le premier à donner votre avis.</p>
            <?php else: ?>
                <div class="avis-liste">
                    <?php foreach ($avisListe as $avis): ?>
                        <div class="avis-item">
                            <div class="avis-item-header">
                                <span class="avis-etoiles" aria-hidden="true"><?= etoiles($avis['note']) ?></span>
                                <span class="avis-auteur"><?= htmlspecialchars($avis['nom']) ?></span>
                            </div>
                            <p><?= htmlspecialchars($avis['commentaire']) ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php if ($avisSuccess): ?>
                <p class="alert alert-success">Merci, votre avis a bien été publié.</p>
            <?php elseif (!empty($avisErrors)): ?>
                <div class="alert alert-error">
                    <?php foreach ($avisErrors as $erreur): ?>
                        <p><?= htmlspecialchars($erreur) ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form method="post" class="avis-form">
                <?= csrfChamp() ?>
                <input type="hidden" name="action" value="ajouter_avis">

                <label for="avis_nom">Nom</label>
                <input type="text" id="avis_nom" name="avis_nom" value="<?= htmlspecialchars($_POST['avis_nom'] ?? (estConnecte() ? utilisateurConnecte()['nom'] : '')) ?>" required>

                <span class="taille-label">Note</span>
                <div class="avis-note-picker">
                    <?php for ($n = 5; $n >= 1; $n--): ?>
                        <label>
                            <input type="radio" name="avis_note" value="<?= $n ?>" <?= $n == 5 ? 'checked' : '' ?>>
                            <span><?= $n ?> ★</span>
                        </label>
                    <?php endfor; ?>
                </div>

                <label for="avis_commentaire">Commentaire</label>
                <textarea id="avis_commentaire" name="avis_commentaire" rows="4" required><?= htmlspecialchars($_POST['avis_commentaire'] ?? '') ?></textarea>

                <button type="submit" class="btn btn-outline">Publier mon avis</button>
            </form>
        </div>
    </section>

    <?php if (!empty($similaires)): ?>
        <section class="section section-alt">
            <div class="container">
                <div class="section-heading reveal">
                    <div>
                        <span class="eyebrow">À découvrir aussi</span>
                        <h2>Articles similaires</h2>
                    </div>
                    <a href="/collection.php?categorie=<?= urlencode($produit['categorie']) ?>" class="link-underline">Voir toute la catégorie</a>
                </div>

                <div class="catalogue-grid">
                    <?php foreach ($similaires as $id => $produit): ?>
                        <?php include 'includes/produit-card.php'; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    <?php endif; ?>
<?php else: ?>
    <section class="section">
        <div class="container">
            <h1>Produit introuvable</h1>
            <p>Ce produit n'existe pas ou a été retiré de la collection.</p>
            <a href="collection.php" class="btn btn-outline">Retour à la collection</a>
        </div>
    </section>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>
