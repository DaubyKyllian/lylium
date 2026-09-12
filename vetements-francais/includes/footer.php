<section class="newsletter-band">
    <div class="grain-overlay" aria-hidden="true"></div>
    <div class="container newsletter-inner">
        <div>
            <span class="eyebrow">Newsletter</span>
            <h2>Restez informé de nos nouveautés</h2>
        </div>

        <div>
            <?php if (($_GET['newsletter'] ?? '') === 'ok'): ?>
                <p class="alert alert-success">Merci, vous êtes bien inscrit·e.</p>
            <?php elseif (($_GET['newsletter'] ?? '') === 'erreur'): ?>
                <p class="alert alert-error">Adresse invalide, merci de réessayer.</p>
            <?php else: ?>
                <form method="post" action="/newsletter.php" class="newsletter-form">
                    <?= csrfChamp() ?>
                    <input type="hidden" name="retour" value="<?= htmlspecialchars(parse_url($_SERVER['REQUEST_URI'] ?? '/index.php', PHP_URL_PATH)) ?>">
                    <input type="email" name="email" placeholder="votre@email.com" required>
                    <button type="submit" class="btn hero-btn">S'inscrire</button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</section>

<footer class="site-footer">
    <div class="container footer-grid">

        <div class="footer-brand">
            <span class="logo">Lylium</span>
            <p>Vêtements conçus et fabriqués en France, pensés pour durer.</p>

            <div class="footer-social">
                <a href="#" aria-label="Instagram">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                        <rect x="3" y="3" width="18" height="18" rx="5"/>
                        <circle cx="12" cy="12" r="4"/>
                        <circle cx="17.2" cy="6.8" r="0.6" fill="currentColor" stroke="none"/>
                    </svg>
                </a>
                <a href="#" aria-label="Pinterest">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                        <circle cx="12" cy="12" r="9"/>
                        <path d="M9.5 18c1-3.5 1.5-6 1.5-7.5a2 2 0 1 1 4 .3c0 1.5-1 4-2.2 4-1 0 -1.3-.7-1-1.7"/>
                    </svg>
                </a>
                <a href="#" aria-label="LinkedIn">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                        <rect x="3" y="3" width="18" height="18" rx="3"/>
                        <line x1="8" y1="10" x2="8" y2="16"/>
                        <circle cx="8" cy="7" r="0.6" fill="currentColor" stroke="none"/>
                        <path d="M12 16v-3.5a2 2 0 0 1 4 0V16"/>
                    </svg>
                </a>
            </div>
        </div>

        <div class="footer-col">
            <span class="footer-col-title">Collection</span>
            <a href="/categorie/homme.php">Homme</a>
            <a href="/categorie/femme.php">Femme</a>
            <a href="/categorie/enfant.php">Enfant</a>
            <a href="/collection.php">Toute la collection</a>
        </div>

        <div class="footer-col">
            <span class="footer-col-title">La maison</span>
            <a href="/marque.php">Notre histoire</a>
            <a href="/lookbook.php">Lookbook</a>
            <a href="/livraison-retours.php">Livraison &amp; retours</a>
            <a href="/contact.php">Contact</a>
        </div>

        <div class="footer-col">
            <span class="footer-col-title">Compte</span>
            <a href="/connexion.php">Connexion</a>
            <a href="/favoris.php">Mes favoris</a>
        </div>

    </div>

    <div class="container footer-bottom">
        <p class="copyright">&copy; <?= date('Y') ?> Lylium — Tous droits réservés.</p>
        <div class="footer-legal">
            <a href="#">Mentions légales</a>
            <a href="#">CGV</a>
            <a href="#">Confidentialité</a>
        </div>
    </div>
</footer>

<script src="/js/app.js"></script>
</body>
</html>