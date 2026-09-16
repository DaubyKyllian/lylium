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

    // Avis de démonstration : injectés une seule fois (table vide au tout
    // premier lancement), pour que les fiches produit affichent de vrais
    // avis clients plutôt qu'un état vide. N'écrase jamais les avis réels
    // postés ensuite depuis le site.
    if ((int)$pdo->query('SELECT COUNT(*) FROM avis')->fetchColumn() === 0) {
        $avisSeed = [
            [5, 'Camille R.', 5, "Le polo est encore plus beau en vrai, la broderie fleur de lys est très soignée. Coupe parfaite en M.", '2026-08-02 10:15:00'],
            [5, 'Antoine D.', 4, "Belle matière, se porte très bien. Léger bémol sur le délai de livraison mais le produit vaut l'attente.", '2026-08-21 18:40:00'],
            [1, 'Sophie L.', 5, "Chemise en lin superbe, on sent la qualité du tissu français. Je recommande sans hésiter.", '2026-07-14 09:05:00'],
            [1, 'Marc B.', 4, "Très jolie coupe, taille juste — prenez une taille au-dessus si vous hésitez.", '2026-09-01 14:22:00'],
            [3, 'Julie P.', 5, "Le coton est épais et de belle tenue, parfait pour un usage quotidien.", '2026-08-10 11:50:00'],
            [3, 'Nicolas T.', 3, "Bon pantalon dans l'ensemble, mais la teinte est un peu plus claire que sur les photos.", '2026-08-28 16:10:00'],
            [7, 'Élise M.', 5, "Manteau magnifique et très chaud, la doublure matelassée fait toute la différence en hiver.", '2026-09-05 08:30:00'],
            [9, 'Charlotte V.', 5, "Robe fluide et agréable à porter, coupe flatteuse. Le lin lavé est très doux.", '2026-07-30 13:12:00'],
            [9, 'Hugo F.', 4, "Achetée pour ma femme, elle est ravie. Jolie couleur naturelle qui se marie avec tout.", '2026-09-08 19:05:00'],
            [2, 'Anne S.', 5, "Pull en laine mérinos très confortable, ne gratte pas du tout. Superbe qualité.", '2026-08-16 10:00:00'],
            [4, 'Thomas G.', 4, "Trench élégant et bien coupé, la doublure amovible est un vrai plus pour les saisons.", '2026-07-22 15:45:00'],
            [11, 'Léa C.', 5, "Chemisier en soie superbe, tombe très bien. Le col lavallière apporte une touche chic.", '2026-08-05 12:20:00'],
            [12, 'Pauline K.', 5, "Pull rayé de très bonne qualité, résiste bien aux lavages. Mon fils l'adore.", '2026-09-10 09:40:00'],
        ];
        $stmt = $pdo->prepare('INSERT INTO avis (produit_id, nom, note, commentaire, date_creation) VALUES (?, ?, ?, ?, ?)');
        foreach ($avisSeed as $avis) {
            $stmt->execute($avis);
        }
    }
} catch (PDOException $e) {
    die('Erreur base de données : ' . $e->getMessage());
}