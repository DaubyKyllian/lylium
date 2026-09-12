<?php
$pageTitle = "Contact";

$success = false;
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if ($nom === '') $errors[] = "Le nom est requis.";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Email invalide.";
    if ($message === '') $errors[] = "Le message ne peut pas être vide.";

    if (empty($errors)) {
        // mail("contact@lylium.fr", "Nouveau message - $nom", $message, "From: $email");
        $success = true;
    }
}

include 'includes/header.php';
include 'includes/navbar.php';
?>

<section class="contact-section">
    <div class="contact-visual">
        <div class="contact-visual-overlay">
            <span class="logo contact-visual-logo">Lylium</span>

            <div class="contact-info">
                <div class="contact-info-item">
                    <span class="contact-info-label">Adresse</span>
                    <p>12 rue des Ateliers, 59000 Lille</p>
                </div>
                <div class="contact-info-item">
                    <span class="contact-info-label">Email</span>
                    <p>contact@lylium.fr</p>
                </div>
                <div class="contact-info-item">
                    <span class="contact-info-label">Horaires</span>
                    <p>Lun–Ven, 9h–18h</p>
                </div>
            </div>
        </div>
    </div>

    <div class="contact-panel">
        <div class="contact-card">
            <h1>Nous contacter</h1>
            <p class="intro">Une question sur un produit, une commande ou la marque ? Écrivez-nous.</p>

            <?php if ($success): ?>
                <p class="alert alert-success">Merci, votre message a bien été envoyé.</p>
            <?php elseif (!empty($errors)): ?>
                <div class="alert alert-error">
                    <?php foreach ($errors as $erreur): ?>
                        <p><?= htmlspecialchars($erreur) ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form method="post" class="contact-form">
                <label for="nom">Nom</label>
                <input type="text" id="nom" name="nom" value="<?= htmlspecialchars($_POST['nom'] ?? '') ?>" required>

                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>

                <label for="message">Message</label>
                <textarea id="message" name="message" rows="6" required><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea>

                <button type="submit" class="btn">Envoyer</button>
            </form>

            <a href="index.php" class="login-back">← Retour à l'accueil</a>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>