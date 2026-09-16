<?php
// Les produits sans clé 'image' (ou avec une valeur vide) affichent une pastille
// de couleur (clé 'couleur') à la place d'une photo — utilisé pour les pièces
// fictives qui n'ont pas encore de vraie photo produit.
$produits = [
    1 => [
        'nom' => 'Chemise Lin Écru', 'prix' => 89, 'categorie' => 'homme',
        'couleur' => '#e8e1d3',
        'tailles' => ['S', 'M', 'L', 'XL'],
        'description' => "Chemise en lin français, tissée dans les Hauts-de-France. Coupe droite, boutons en nacre.",
    ],
    2 => [
        'nom' => 'Pull Col Rond Laine', 'prix' => 119, 'categorie' => 'femme',
        'couleur' => '#c9beae',
        'tailles' => ['XS', 'S', 'M', 'L'],
        'description' => "Pull en laine mérinos, tricoté en Auvergne. Coloris naturel, coupe intemporelle.",
    ],
    3 => [
        'nom' => 'Pantalon Droit Coton', 'prix' => 99, 'categorie' => 'homme',
        'image' => 'images/produits/pantalon-coton.jpg',
        'tailles' => ['38', '40', '42', '44', '46'],
        'description' => "Pantalon en coton épais, taille haute, fabriqué à Lyon. Confort et tenue toute la journée.",
    ],
    4 => [
        'nom' => 'Veste Trench Beige', 'prix' => 179, 'categorie' => 'femme',
        'couleur' => '#d8cdb8',
        'tailles' => ['XS', 'S', 'M', 'L'],
        'description' => "Trench imperméable, coupé et cousu en Normandie. Doublure amovible.",
    ],
    5 => [
        'nom' => 'Polo Piqué Fleur de Lys', 'prix' => 135, 'categorie' => 'homme',
        'image' => 'images/produits/polo-homme.png',
        'tailles' => ['S', 'M', 'L', 'XL'],
        'description' => "Polo en piqué de coton peigné, teint dans un vert forêt profond. Brodé à la poitrine d'une fleur de lys dorée, signature discrète de la maison. Coupe droite ajustée, col fin structuré, finitions cousues main sur les boutonnières. Confectionné dans un atelier partenaire en France, en petite série.",
    ],
    6 => [
        'nom' => 'Pantalon Chino Sable', 'prix' => 105, 'categorie' => 'homme',
        'couleur' => '#d9c7a8',
        'tailles' => ['38', '40', '42', '44', '46'],
        'description' => "Chino en coton sergé, taille mi-haute, coupe droite. Confectionné dans un atelier partenaire du Nord.",
    ],
    7 => [
        'nom' => 'Manteau Laine Anthracite', 'prix' => 249, 'categorie' => 'homme',
        'couleur' => '#8a8f92',
        'tailles' => ['S', 'M', 'L', 'XL'],
        'description' => "Manteau en laine et cachemire, doublure intérieure matelassée. Coupe droite, longueur mi-cuisse.",
    ],
    8 => [
        'nom' => 'Chemise Popeline Bleu Ciel', 'prix' => 92, 'categorie' => 'homme',
        'couleur' => '#aebfd1',
        'tailles' => ['S', 'M', 'L', 'XL'],
        'description' => "Chemise en popeline de coton, col boutonné. Une pièce facile à associer, en toute saison.",
    ],
    9 => [
        'nom' => 'Robe Midi Lin Naturel', 'prix' => 145, 'categorie' => 'femme',
        'couleur' => '#e3d9c4',
        'tailles' => ['XS', 'S', 'M', 'L'],
        'description' => "Robe midi en lin lavé, coupe fluide, ceinture à nouer. Confectionnée en petite série en France.",
    ],
    10 => [
        'nom' => 'Jupe Plissée Camel', 'prix' => 115, 'categorie' => 'femme',
        'couleur' => '#c69a6d',
        'tailles' => ['XS', 'S', 'M', 'L'],
        'description' => "Jupe plissée en laine mélangée, taille haute élastiquée. Un intemporel réinterprété.",
    ],
    11 => [
        'nom' => 'Chemisier Soie Ivoire', 'prix' => 129, 'categorie' => 'femme',
        'couleur' => '#ece4d3',
        'tailles' => ['XS', 'S', 'M', 'L'],
        'description' => "Chemisier en soie mélangée, col lavallière amovible. Doux et fluide, pour le jour comme le soir.",
    ],
    12 => [
        'nom' => 'Pull Rayé Marine', 'prix' => 69, 'categorie' => 'enfant',
        'couleur' => '#9fb2c4',
        'tailles' => ['4 ans', '6 ans', '8 ans', '10 ans'],
        'description' => "Pull rayé en coton bio, col rond renforcé. Pensé pour résister aux lavages répétés.",
    ],
    13 => [
        'nom' => 'Salopette Coton Écru', 'prix' => 59, 'categorie' => 'enfant',
        'couleur' => '#e7dfcd',
        'tailles' => ['2 ans', '4 ans', '6 ans', '8 ans'],
        'description' => "Salopette en coton épais, bretelles ajustables, boutons-pression. Confortable et facile à enfiler.",
    ],
    14 => [
        'nom' => 'T-shirt Bio Blanc Cassé', 'prix' => 29, 'categorie' => 'enfant',
        'couleur' => '#ece7dd',
        'tailles' => ['2 ans', '4 ans', '6 ans', '8 ans', '10 ans'],
        'description' => "T-shirt en coton biologique, coupe droite. Le basique du quotidien, en petite série française.",
    ],
    15 => [
        'nom' => 'Cardigan Laine Moutarde', 'prix' => 79, 'categorie' => 'enfant',
        'couleur' => '#c99a4b',
        'tailles' => ['4 ans', '6 ans', '8 ans', '10 ans'],
        'description' => "Cardigan en laine mélangée, boutonnage devant. Une touche de couleur pour l'hiver.",
    ],
];
