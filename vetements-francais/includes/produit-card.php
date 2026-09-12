<?php
/**
 * Carte produit réutilisable. Attend dans le scope appelant :
 *   $id       int    identifiant du produit (clé dans $produits)
 *   $produit  array  ses données (nom, prix, image? , couleur?)
 * Affiche une vraie photo si $produit['image'] est renseignée,
 * sinon une pastille de couleur (repère visuel en attendant la photo).
 */
?>
<a href="/produit.php?id=<?= $id ?>" class="card produit-card">
    <?php if (!empty($produit['image'])): ?>
        <div class="card-image" style="background-image:url('<?= htmlspecialchars($produit['image']) ?>')"></div>
    <?php else: ?>
        <div class="card-image card-image--swatch" style="background-color:<?= htmlspecialchars($produit['couleur'] ?? '#e9e4da') ?>">
            <span class="card-image-label"><?= htmlspecialchars($produit['nom']) ?></span>
        </div>
    <?php endif; ?>
    <h3><?= htmlspecialchars($produit['nom']) ?></h3>
    <p class="prix"><?= (int)$produit['prix'] ?> €</p>
</a>
