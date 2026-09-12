<?php
$pageTitle = "Livraison & retours";
include 'includes/header.php';
include 'includes/navbar.php';

$faq = [
    [
        'q' => 'Quels sont les délais et frais de livraison ?',
        'r' => "Comptez 3 à 5 jours ouvrés en France métropolitaine. La livraison est offerte dès 120 € d'achat, 6,90 € en dessous.",
    ],
    [
        'q' => 'Puis-je retourner un article ?',
        'r' => "Oui, sous 30 jours à compter de la réception, dans son état d'origine et non porté. Le retour est gratuit depuis la France métropolitaine.",
    ],
    [
        'q' => 'Comment choisir ma taille ?',
        'r' => "Nos coupes sont ajustées et fidèles aux tailles françaises standards. En cas de doute entre deux tailles, nous conseillons de prendre la taille au-dessus.",
    ],
    [
        'q' => 'Comment entretenir mes vêtements Lylium ?',
        'r' => "Lavage à 30°C, à l'envers, sans essorage excessif. Nos matières naturelles préfèrent le séchage à l'air libre à celui en machine.",
    ],
    [
        'q' => 'Quels moyens de paiement acceptez-vous ?',
        'r' => "Le paiement en ligne sécurisé arrive prochainement directement sur le site. En attendant, contactez-nous pour finaliser une commande.",
    ],
];
?>

<section class="section category-title-block">
    <div class="container">
        <p class="breadcrumb"><a href="/index.php">Accueil</a> / Livraison &amp; retours</p>
        <span class="eyebrow">Questions fréquentes</span>
        <h1>Livraison &amp; retours</h1>
        <p class="intro">Tout ce qu'il faut savoir avant et après votre commande.</p>
    </div>
</section>

<section class="section">
    <div class="container narrow">
        <div class="faq-list">
            <?php foreach ($faq as $item): ?>
                <details class="faq-item">
                    <summary><?= htmlspecialchars($item['q']) ?></summary>
                    <p><?= htmlspecialchars($item['r']) ?></p>
                </details>
            <?php endforeach; ?>
        </div>

        <p class="faq-contact">
            Une autre question ? <a href="/contact.php" class="link-underline">Contactez-nous</a>.
        </p>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
