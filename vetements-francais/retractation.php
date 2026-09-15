<?php
require __DIR__ . '/vendor/autoload.php';
require 'includes/session.php';
require 'includes/mail.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as PHPMailerException;

$pageTitle = "Formulaire de rétractation";

$success = false;
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $produits = trim($_POST['produits'] ?? '');
    $numeroCommande = trim($_POST['numero_commande'] ?? '');
    $commandeLe = trim($_POST['commande_le'] ?? '');
    $recuLe = trim($_POST['recu_le'] ?? '');
    $nom = trim($_POST['nom'] ?? '');
    $adresse = trim($_POST['adresse'] ?? '');
    $email = trim($_POST['email'] ?? '');

    if (!csrfVerifie($_POST['csrf_token'] ?? null)) $errors[] = "Session expirée, merci de renvoyer le formulaire.";
    if ($produits === '') $errors[] = "Merci d'indiquer le ou les produits concernés.";
    if ($numeroCommande === '') $errors[] = "Le numéro de commande est requis.";
    if ($nom === '') $errors[] = "Le nom est requis.";
    if ($adresse === '') $errors[] = "L'adresse est requise.";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Email invalide.";

    $config = mailConfig();
    if (empty($errors) && $config === null) {
        $errors[] = "L'envoi n'est pas encore configuré (variables SMTP manquantes).";
    }

    if (empty($errors)) {
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
            $mail->Timeout = 10;
            $mail->SMTPKeepAlive = false;

            $mail->setFrom($config['from_email'], $config['from_name']);
            $mail->addAddress($config['to_email']);
            $mail->addReplyTo($email, $nom);

            $mail->Subject = "Notification de rétractation — $nom (commande $numeroCommande)";
            $mail->Body = "Déclaration de rétractation reçue via le site.\n\n"
                . "Produit(s) concerné(s) : $produits\n"
                . "Numéro de commande : $numeroCommande\n"
                . "Commandé le : $commandeLe\n"
                . "Reçu le : $recuLe\n"
                . "Nom du consommateur : $nom\n"
                . "Adresse du consommateur : $adresse\n"
                . "Email du consommateur : $email\n"
                . "Date de la déclaration : " . date('d/m/Y');

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

<section class="page-banner">
    <div class="container page-banner-inner">
        <p class="breadcrumb"><a href="/index.php">Accueil</a> / Formulaire de rétractation</p>
        <span class="eyebrow">Informations légales</span>
        <h1>Formulaire de rétractation</h1>
    </div>
</section>

<section class="section">
    <div class="container narrow legal-content">
        <p class="alert alert-error">
            ⚠️ Modèle à compléter avant mise en ligne réelle : les informations entre crochets
            (coordonnées LYLIUM, médiateur de la consommation, etc.) doivent être remplacées par les données
            exactes de la société avant toute publication du site.
        </p>

        <p>Conformément aux dispositions légales applicables, le consommateur dispose, sauf exceptions prévues par la loi, d'un délai de quatorze (14) jours à compter de la réception du produit pour exercer son droit de rétractation.</p>
        <p>Le consommateur peut exercer ce droit en adressant à LYLIUM une déclaration dénuée d'ambiguïté exprimant sa volonté de se rétracter. Le formulaire ci-dessous peut être utilisé à cet effet.</p>

        <p>
            À l'attention de :<br>
            [Dénomination sociale LYLIUM]<br>
            [Adresse complète]<br>
            [Adresse e-mail]<br>
            [Téléphone]
        </p>
    </div>
</section>

<section class="section section-alt">
    <div class="container narrow">
        <h2>Je notifie ma rétractation</h2>
        <p class="intro">Je vous notifie par la présente ma rétractation du contrat portant sur la vente du produit ci-dessous.</p>

        <?php if ($success): ?>
            <p class="alert alert-success">Merci, votre déclaration de rétractation a bien été envoyée. Vous recevrez une confirmation par e-mail.</p>
        <?php elseif (!empty($errors)): ?>
            <div class="alert alert-error">
                <?php foreach ($errors as $erreur): ?>
                    <p><?= htmlspecialchars($erreur) ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="post" class="contact-form">
            <?= csrfChamp() ?>

            <label for="produits">Produit(s) concerné(s)</label>
            <textarea id="produits" name="produits" rows="2" required><?= htmlspecialchars($_POST['produits'] ?? '') ?></textarea>

            <label for="numero_commande">Numéro de commande</label>
            <input type="text" id="numero_commande" name="numero_commande" value="<?= htmlspecialchars($_POST['numero_commande'] ?? '') ?>" required>

            <label for="commande_le">Commandé le</label>
            <input type="date" id="commande_le" name="commande_le" value="<?= htmlspecialchars($_POST['commande_le'] ?? '') ?>">

            <label for="recu_le">Reçu le</label>
            <input type="date" id="recu_le" name="recu_le" value="<?= htmlspecialchars($_POST['recu_le'] ?? '') ?>">

            <label for="nom">Nom du consommateur</label>
            <input type="text" id="nom" name="nom" value="<?= htmlspecialchars($_POST['nom'] ?? '') ?>" required>

            <label for="adresse">Adresse du consommateur</label>
            <textarea id="adresse" name="adresse" rows="2" required><?= htmlspecialchars($_POST['adresse'] ?? '') ?></textarea>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>

            <p class="login-footnote">La signature n'est nécessaire qu'en cas de notification sur papier ; en soumettant ce formulaire, vous confirmez votre volonté de vous rétracter à la date d'envoi.</p>

            <button type="submit" class="btn">Envoyer ma déclaration de rétractation</button>
        </form>
    </div>
</section>

<section class="section">
    <div class="container narrow legal-content">
        <h2>Modalités de retour</h2>
        <p>Après avoir exercé son droit de rétractation, le consommateur doit retourner le ou les produits concernés dans les conditions indiquées dans la <a href="/politique-livraison-retours.php" class="link-underline">politique de livraison, retours et remboursements</a> de LYLIUM.</p>
        <p>Les produits doivent être manipulés avec les précautions nécessaires afin de permettre leur vérification.</p>
        <p>Les modalités concernant les frais de retour, l'adresse de retour et les délais de remboursement sont précisées dans la politique de livraison, retours et remboursements.</p>

        <h2>Exceptions au droit de rétractation</h2>
        <p>Le droit de rétractation ne s'applique pas aux produits qui entrent dans les exceptions prévues par la législation applicable.</p>
        <p>Notamment, lorsque les conditions légales sont réunies, les produits confectionnés selon les spécifications du consommateur ou nettement personnalisés peuvent être exclus du droit de rétractation.</p>
        <p>LYLIUM informera clairement le consommateur lorsqu'un produit est concerné par une telle exception.</p>

        <h2>Médiation de la consommation</h2>
        <p>En cas de litige, le consommateur doit en premier lieu adresser une réclamation écrite au service client de LYLIUM afin de rechercher une solution amiable.</p>
        <p>LYLIUM communiquera au consommateur les coordonnées du médiateur de la consommation dont elle relève, conformément aux obligations légales applicables.</p>
        <p>Le recours à la médiation est gratuit pour le consommateur, sous réserve du respect des conditions de recevabilité applicables.</p>
        <p>
            Médiateur de la consommation :<br>
            [Nom du médiateur]<br>
            [Adresse]<br>
            [Site internet]<br>
            [Coordonnées / modalités de saisine]
        </p>

        <h2>Contact service client</h2>
        <p>Pour toute question concernant une commande, un retour, un remboursement ou une réclamation, contactez-nous via la <a href="/contact.php" class="link-underline">page de contact</a>, ou directement :</p>
        <p>
            E-mail : [adresse e-mail]<br>
            Téléphone : [numéro]<br>
            Adresse postale : [Adresse complète]
        </p>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
