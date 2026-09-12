<?php
$pageTitle = "Connexion";
include 'includes/header.php';
include 'includes/navbar.php';
?>

<section class="login-section">
    <div class="login-visual">
        <div class="grain-overlay" aria-hidden="true"></div>
        <div class="login-visual-overlay">
            <span class="logo login-visual-logo">Lylium</span>
            <p class="login-visual-quote">« S'habiller avec élégance, sans sacrifier la traçabilité. »</p>
        </div>
    </div>

    <div class="login-panel">
        <div class="login-card">
            <h1>Bon retour</h1>
            <p class="intro">Connecte-toi pour retrouver tes favoris et suivre tes commandes.</p>

            <?php if (isset($_GET['erreur'])): ?>
                <p class="alert alert-error">La connexion a échoué, réessaie.</p>
            <?php endif; ?>

            <div class="google-btn-wrap">
                <div id="g_id_onload"
                     data-client_id="719069643955-0tmmeldb5facd0ido72gd5utgivr82rk.apps.googleusercontent.com"
                     data-login_uri="/auth/google-callback.php"
                     data-auto_prompt="false">
                </div>
                <div class="g_id_signin"
                     data-type="standard"
                     data-shape="pill"
                     data-theme="outline"
                     data-size="large"
                     data-text="continue_with"
                     data-logo_alignment="left">
                </div>
            </div>

            <p class="login-footnote">
                En continuant, tu acceptes nos conditions d'utilisation.
            </p>

            <a href="index.php" class="login-back">← Retour à l'accueil</a>
        </div>
    </div>
</section>

<script src="https://accounts.google.com/gsi/client" async defer></script>

<?php include 'includes/footer.php'; ?>