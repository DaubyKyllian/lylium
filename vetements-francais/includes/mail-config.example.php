<?php
// Copie ce fichier en "mail-config.php" (à côté) et remplis tes vraies valeurs.
// mail-config.php est ignoré par git : il ne contient que des secrets locaux.

return [
    'smtp_host' => 'smtp.gmail.com',
    'smtp_port' => 587,
    'smtp_user' => 'TON_ADRESSE_EXPEDITRICE@gmail.com',
    'smtp_pass' => 'xxxx xxxx xxxx xxxx', // mot de passe d'application Google (16 caractères)
    'from_email' => 'TON_ADRESSE_EXPEDITRICE@gmail.com',
    'from_name' => 'Site Lylium',
    'to_email' => 'stagnir2000@gmail.com',
];
