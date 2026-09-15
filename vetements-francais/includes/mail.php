<?php
/**
 * Renvoie la config SMTP, ou null si l'envoi d'email n'est pas configuré.
 * Priorité : includes/mail-config.php (pratique en dev local, ignoré par
 * git) puis variables d'environnement (production). Lue à chaque requête,
 * donc toujours à jour si les variables d'environnement changent — pas de
 * fichier généré une seule fois au démarrage du conteneur à garder en sync.
 */
function mailConfig(): ?array {
    $configPath = __DIR__ . '/mail-config.php';
    if (file_exists($configPath)) {
        return require $configPath;
    }

    $smtpUser = getenv('SMTP_USER') ?: '';
    if ($smtpUser === '') {
        return null;
    }

    return [
        'smtp_host' => getenv('SMTP_HOST') ?: 'smtp.gmail.com',
        'smtp_port' => (int) (getenv('SMTP_PORT') ?: 587),
        'smtp_user' => $smtpUser,
        'smtp_pass' => getenv('SMTP_PASS') ?: '',
        'from_email' => getenv('FROM_EMAIL') ?: $smtpUser,
        'from_name' => getenv('FROM_NAME') ?: 'Site Lylium',
        'to_email' => getenv('TO_EMAIL') ?: '',
    ];
}
