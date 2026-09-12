<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function estConnecte() {
    return isset($_SESSION['user_id']);
}

function utilisateurConnecte() {
    return estConnecte() ? ['id' => $_SESSION['user_id'], 'nom' => $_SESSION['user_nom']] : null;
}

function exigerConnexion() {
    if (!estConnecte()) {
        header('Location: connexion.php');
        exit;
    }
}

// Protection CSRF : un jeton par session, à inclure en champ caché dans chaque
// formulaire POST et à vérifier avec csrfVerifie() côté traitement.
function csrfToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrfChamp() {
    return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars(csrfToken()) . '">';
}

function csrfVerifie($token) {
    return !empty($_SESSION['csrf_token']) && is_string($token) && hash_equals($_SESSION['csrf_token'], $token);
}