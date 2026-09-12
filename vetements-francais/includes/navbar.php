<?php require_once __DIR__ . '/session.php'; ?>

<header class="site-header">

    <nav class="navbar">

        <a href="index.php" class="logo">
            LYLIUM
        </a>

        <div class="category-nav">
            <a href="categorie/homme.php">Homme</a>
            <a href="categorie/femme.php">Femme</a>
            <a href="categorie/enfant.php">Enfant</a>
        </div>

        <div class="main-nav">
            <a href="collection.php">Collection</a>
            <a href="marque.php">La marque</a>
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
        </div>



        <button class="nav-toggle">
            ☰
        </button>

    </nav>

</header>