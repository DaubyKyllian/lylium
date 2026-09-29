<?php
// Les produits sans clé 'image' (ou avec une valeur vide) affichent une pastille
// de couleur (clé 'couleur') à la place d'une photo — utilisé pour les pièces
// fictives qui n'ont pas encore de vraie photo produit.
$produits = [
    5 => [
        'nom' => 'Polo Piqué Fleur de Lys', 'prix' => 135, 'categorie' => 'homme', 'stock' => 3000,
        'image' => 'images/produits/polo-homme.png',
        'tailles' => ['S', 'M', 'L', 'XL'],
        'description' => "Polo en piqué de coton peigné, teint dans un vert forêt profond. Brodé à la poitrine d'une fleur de lys dorée, signature discrète de la maison. Coupe droite ajustée, col fin structuré, finitions cousues main sur les boutonnières. Confectionné dans un atelier partenaire en France, en petite série.",
    ],
];
