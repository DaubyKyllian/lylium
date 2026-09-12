<?php
// Connexion à la base SQLite. Crée le fichier et les tables au premier appel.

$dbPath = __DIR__ . '/../database/lylium.sqlite';
$dbIsNew = !file_exists($dbPath);

try {
    $pdo = new PDO('sqlite:' . $dbPath);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec('PRAGMA foreign_keys = ON;');

    if ($dbIsNew) {
        $schema = file_get_contents(__DIR__ . '/../database/schema.sql');
        $pdo->exec($schema);
    }
} catch (PDOException $e) {
    die('Erreur base de données : ' . $e->getMessage());
}