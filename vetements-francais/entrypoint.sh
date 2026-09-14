#!/bin/sh
set -e

mkdir -p /var/www/html/database
chown -R www-data:www-data /var/www/html/database

if [ ! -f /var/www/html/includes/mail-config.php ]; then
    cat > /var/www/html/includes/mail-config.php <<PHP
<?php
return [
    'smtp_host' => getenv('SMTP_HOST') ?: 'smtp.gmail.com',
    'smtp_port' => (int) (getenv('SMTP_PORT') ?: 587),
    'smtp_user' => getenv('SMTP_USER') ?: '',
    'smtp_pass' => getenv('SMTP_PASS') ?: '',
    'from_email' => getenv('FROM_EMAIL') ?: getenv('SMTP_USER') ?: '',
    'from_name' => getenv('FROM_NAME') ?: 'Site Lylium',
    'to_email' => getenv('TO_EMAIL') ?: '',
];
PHP
    chown www-data:www-data /var/www/html/includes/mail-config.php
fi

exec apache2-foreground
