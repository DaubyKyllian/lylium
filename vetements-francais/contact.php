<?php
require __DIR__ . '/vendor/autoload.php';
require 'includes/session.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as PHPMailerException;

$pageTitle = "Contact";

$success = false;
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if (!csrfVerifie($_POST['csrf_token'] ?? null)) $errors[] = "Session expirée, merci de renvoyer le formulaire.";
    if ($nom === '') $errors[] = "Le nom est requis.";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Email invalide.";
    if ($message === '') $errors[] = "Le message ne peut pas être vide.";

    $configPath = __DIR__ . '/includes/mail-config.php';
    if (empty($errors) && !file_exists($configPath)) {
        $errors[] = "L'envoi d'email n'est pas encore configuré (includes/mail-config.php manquant).";
    }

    if (empty($errors)) {
        $config = require $configPath;

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = $config['smtp_host'];
            $mail->SMTPAuth = true;
            $mail->Username = $config['smtp_user'];
            $mail->Password = $config['smtp_pass'];
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = $config['smtp_port'];
            $mail->CharSet = 'UTF-8';
            $mail->Timeout = 10; // sans ça, PHPMailer attend jusqu'à 5 min (SMTP bloqué/injoignable) avant d'échouer
            $mail->SMTPKeepAlive = false;

            $mail->setFrom($config['from_email'], $config['from_name']);
            $mail->addAddress($config['to_email']);
            $mail->addReplyTo($email, $nom);

            $mail->Subject = "Nouveau message de contact — $nom";
            $mail->Body = "De : $nom <$email>\n\n$message";

            $mail->send();
            $success = true;
        } catch (PHPMailerException $e) {
            $errors[] = "L'envoi a échoué : " . $mail->ErrorInfo;
        }
    }
}

include 'includes/header.php';
include 'includes/navbar.php';
?>

<section class="split-page">
    <div class="split-page-visual">
        <div class="grain-overlay" aria-hidden="true"></div>
        <div class="split-page-visual-overlay">
            <span class="logo split-page-logo">Lylium</span>

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

    <div class="split-page-panel">
        <div class="split-page-card">
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
                <?= csrfChamp() ?>
                <label for="nom">Nom</label>
                <input type="text" id="nom" name="nom" value="<?= htmlspecialchars($_POST['nom'] ?? '') ?>" required>

                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>

                <label for="message">Message</label>
                <textarea id="message" name="message" rows="6" required><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea>

                <button type="submit" class="btn">Envoyer</button>
            </form>

            <a href="index.php" class="split-page-back">← Retour à l'accueil</a>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>