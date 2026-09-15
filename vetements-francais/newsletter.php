<?php
require 'includes/session.php';
require 'includes/db.php';
require 'includes/mail.php';

$email = trim($_POST['email'] ?? '');
$retour = $_POST['retour'] ?? '/index.php';

// Anti open-redirect : on n'accepte qu'un chemin relatif au site.
if (!str_starts_with($retour, '/') || str_starts_with($retour, '//')) {
    $retour = '/index.php';
}
$retour = strtok($retour, '?'); // on ignore d'éventuels paramètres déjà présents

$statut = 'erreur';

if (csrfVerifie($_POST['csrf_token'] ?? null) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
    try {
        $stmt = $pdo->prepare('INSERT INTO newsletter_abonnes (email) VALUES (?)');
        $stmt->execute([$email]);
    } catch (PDOException $e) {
        // Contrainte UNIQUE = déjà inscrit : on ne le révèle pas, on traite comme un succès.
    }
    $statut = 'ok';

    $config = mailConfig();
    if ($config !== null) {
        require __DIR__ . '/vendor/autoload.php';
        try {
            $mail = new PHPMailer\PHPMailer\PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = $config['smtp_host'];
            $mail->SMTPAuth = true;
            $mail->Username = $config['smtp_user'];
            $mail->Password = $config['smtp_pass'];
            $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = $config['smtp_port'];
            $mail->CharSet = 'UTF-8';
            $mail->Timeout = 10;
            $mail->SMTPKeepAlive = false;
            $mail->setFrom($config['from_email'], $config['from_name']);
            $mail->addAddress($email);
            $mail->Subject = "Bienvenue chez Lylium";
            $mail->Body = "Merci de votre inscription à la newsletter Lylium.\n\nVous serez informé·e en priorité de nos nouveautés et de nos petites séries.";
            $mail->send();
        } catch (\Throwable $e) {
            // Best-effort : l'inscription reste valide même si l'email de confirmation échoue.
        }
    }
}

header('Location: ' . $retour . '?newsletter=' . $statut);
exit;
