<?php
/**
 * En-tête commun aux pages du compte (compte, favoris, commandes).
 * Attend dans le scope appelant :
 *   $userNom            string  nom affiché dans le message de bienvenue
 *   $accountActiveTab    string  'presentation' | 'favoris' | 'commandes'
 */
?>
<section class="page-banner page-banner--dark account-hero">
    <div class="grain-overlay" aria-hidden="true"></div>
    <div class="account-hero-overlay">
        <div class="container">
            <div class="account-hero-top">
                <h1>Nous sommes heureux de vous revoir, <?= htmlspecialchars($userNom) ?></h1>
                <a href="deconnexion.php" class="account-logout-link">Se déconnecter</a>
            </div>

            <nav class="account-tabs">
                <a href="mon-compte.php" class="<?= $accountActiveTab === 'presentation' ? 'active' : '' ?>">Présentation</a>
                <a href="favoris.php" class="<?= $accountActiveTab === 'favoris' ? 'active' : '' ?>">Mes favoris</a>
                <a href="mes-commandes.php" class="<?= $accountActiveTab === 'commandes' ? 'active' : '' ?>">Commandes</a>
                <span class="account-tab-disabled">Adresses</span>
            </nav>
        </div>
    </div>
</section>
