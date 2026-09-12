<?php
// Connexion à la base SQLite. Crée le fichier et les tables au premier appel.

$dbPath = __DIR__ . '/../database/lylium.sqlite';

try {
    $pdo = new PDO('sqlite:' . $dbPath);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec('PRAGMA foreign_keys = ON;');

    // Toujours rejoue : chaque instruction est "CREATE TABLE IF NOT EXISTS",
    // donc sans risque sur une base existante (permet d'ajouter de nouvelles
    // tables au fil du temps sans étape de migration manuelle).
    $schema = file_get_contents(__DIR__ . '/../database/schema.sql');
    $pdo->exec($schema);
} catch (PDOException $e) {
    die('Erreur base de données : ' . $e->getMessage());
}