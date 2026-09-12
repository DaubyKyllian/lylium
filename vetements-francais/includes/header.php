<?php

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
        })();
    </script>
</head>

<body<?= !empty($bodyClass) ? ' class="' . htmlspecialchars($bodyClass) . '"' : '' ?>>