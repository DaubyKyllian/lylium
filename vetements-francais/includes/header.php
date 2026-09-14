<?php

require_once __DIR__ . '/session.php';

$siteName = "Lylium";
$pageTitle = $pageTitle ?? "L'élégance à la française au quotidien";

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <base href="/">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= htmlspecialchars($pageTitle) ?> — <?= htmlspecialchars($siteName) ?></title>

    <meta name="description" content="<?= htmlspecialchars($siteName) ?>, vêtements conçus et fabriqués en France.">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;1,500&family=Inter:wght@300;400;500;600&display=swap">

    <link rel="stylesheet" href="css/style.css">
    <script>
        (function () {
            try {
                var stored = localStorage.getItem('lylium_theme');
                var preferDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
                if (stored === 'dark' || (!stored && preferDark)) {
                    document.documentElement.setAttribute('data-theme', 'dark');
                }
            } catch (e) {}
            if (!window.matchMedia || !window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
                document.documentElement.classList.add('js-transitions');
            }
        })();
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js" defer></script>
</head>

<body<?= !empty($bodyClass) ? ' class="' . htmlspecialchars($bodyClass) . '"' : '' ?>>