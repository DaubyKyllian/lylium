#!/bin/sh
set -e

# La config SMTP est lue directement depuis les variables d'environnement
# par includes/mail.php à chaque requête (voir ce fichier) : pas besoin de
# générer includes/mail-config.php ici.

mkdir -p /var/www/html/database
chown -R www-data:www-data /var/www/html/database

exec apache2-foreground
