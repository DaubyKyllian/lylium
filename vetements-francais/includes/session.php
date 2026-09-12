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