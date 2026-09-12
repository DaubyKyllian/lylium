<?php
require __DIR__ . '/../includes/session.php';
require __DIR__ . '/../includes/db.php';

$idToken = $_POST['credential'] ?? null;

if (!$idToken) {
    header('Location: ../connexion.php?erreur=1');
    exit;
}

// Vérification du token directement auprès de Google
$verifyUrl = 'https://oauth2.googleapis.com/tokeninfo?id_token=' . urlencode($idToken);
$response = @file_get_contents($verifyUrl);
$payload = $response ? json_decode($response, true) : null;

$clientId = '719069643955-0tmmeldb5facd0ido72gd5utgivr82rk.apps.googleusercontent.com';

if (!$payload || ($payload['aud'] ?? '') !== $clientId) {
    header('Location: ../connexion.php?erreur=1');
    exit;
}

$googleId = $payload['sub'];
$email = $payload['email'];
$nom = $payload['name'] ?? $email;

$stmt = $pdo->prepare('SELECT * FROM users WHERE google_id = ?');
$stmt->execute([$googleId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    $stmt = $pdo->prepare('INSERT INTO users (email, nom, google_id) VALUES (?, ?, ?)');
    $stmt->execute([$email, $nom, $googleId]);
    $userId = $pdo->lastInsertId();
} else {
    $userId = $user['id'];
}

$_SESSION['user_id'] = $userId;
$_SESSION['user_nom'] = $nom;

header('Location: ../mon-compte.php');
exit;