<?php require_once __DIR__ . '/session.php'; ?>

<header class="site-header">

    <div class="top-bar">
        <div class="top-bar-track">
            <span>Livraison offerte en France dès 120&nbsp;€</span>
            <span>·</span>
            <span>Fabriqué et confectionné en France</span>
            <span>·</span>
            <span>Retours gratuits sous 30 jours</span>
            <span>·</span>
        </div>
    </div>

    <nav class="navbar">

        <a href="index.php" class="logo">
            LYLIUM
        </a>

        <div class="category-nav">
            <a href="categorie/homme.php" class="<?= ($navActive ?? '') === 'homme' ? 'active' : '' ?>">Homme</a>
            <a href="categorie/femme.php" class="<?= ($navActive ?? '') === 'femme' ? 'active' : '' ?>">Femme</a>
            <a href="categorie/enfant.php" class="<?= ($navActive ?? '') === 'enfant' ? 'active' : '' ?>">Enfant</a>
        </div>

        <div class="main-nav">
            <a href="collection.php" class="<?= ($navActive ?? '') === 'collection' ? 'active' : '' ?>">Collection</a>
            <a href="marque.php" class="<?= ($navActive ?? '') === 'marque' ? 'active' : '' ?>">La marque</a>
            <a href="recherche.php" class="icon-link" aria-label="Recherche">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round">
                    <circle cx="11" cy="11" r="7"/>
                    <line x1="21" y1="21" x2="16.5" y2="16.5"/>
                </svg>
            </a>
            <a href="contact.php" class="icon-link" aria-label="Contact">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 4h16v14H8l-4 4V4z"/>
                </svg>
            </a>

            <?php if (estConnecte()): ?>
                <a href="favoris.php" class="icon-link" aria-label="Mes favoris">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.6l-1-1a5.5 5.5 0 0 0-7.8 7.8l1 1L12 21l7.8-7.6 1-1a5.5 5.5 0 0 0 0-7.8z"/>
                    </svg>
                </a>
                <a href="mon-compte.php" class="icon-link" aria-label="Mon compte">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="8" r="4"/>
                        <path d="M4 20c0-4.4 3.6-8 8-8s8 3.6 8 8"/>
                    </svg>
                </a>
            <?php else: ?>
                <a href="connexion.php" class="icon-link" aria-label="Connexion">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="8" r="4"/>
                        <path d="M4 20c0-4.4 3.6-8 8-8s8 3.6 8 8"/>
                    </svg>
                </a>
            <?php endif; ?>

            <button type="button" class="theme-toggle icon-link" id="themeToggle" aria-label="Basculer le thème clair/sombre">
                <svg class="icon-sun" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round">
                    <circle cx="12" cy="12" r="4.5"/>
                    <path d="M12 2.5v2.5M12 19v2.5M4.6 4.6l1.8 1.8M17.6 17.6l1.8 1.8M2.5 12H5M19 12h2.5M4.6 19.4l1.8-1.8M17.6 6.4l1.8-1.8"/>
                </svg>
                <svg class="icon-moon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 14.5A8.5 8.5 0 1 1 9.5 4a6.5 6.5 0 0 0 10.5 10.5z"/>
                </svg>
            </button>
        </div>



        <button class="nav-toggle">
            ☰
        </button>

    </nav>

</header>