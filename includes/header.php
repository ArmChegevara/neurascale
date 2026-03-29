<?php
require __DIR__ . '/bootstrap.php';
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle ?? $site['site_name']); ?></title>
    <meta name="description" content="<?= e($pageDescription ?? $site['tagline']); ?>">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>

<body>
    <header class="site-header">
        <div class="container header-inner">
            <a href="/index.php" class="brand">
                <img src="/assets/images/logo.png" alt="NeuraScale" class="logo-img">
            </a>

            <nav class="main-nav" aria-label="Navigation principale">
                <a class="<?= isActive('index', $currentPage ?? ''); ?>" href="/index.php">Accueil</a>
                <a class="<?= isActive('services', $currentPage ?? ''); ?>" href="/pages/services.php">Services</a>
                <a class="<?= isActive('projets', $currentPage ?? ''); ?>" href="/pages/projets.php">Projets</a>
                <a class="<?= isActive('automatisation', $currentPage ?? ''); ?>" href="/pages/automatisation.php">Automatisation</a>
                <a class="<?= isActive('processus', $currentPage ?? ''); ?>" href="/pages/processus.php">Processus</a>
                <a class="<?= isActive('contact', $currentPage ?? ''); ?>" href="/pages/contact.php">Contact</a>
            </nav>

            <a href="/pages/contact.php" class="button button-primary header-cta">Demander un devis</a>
        </div>
    </header>

    <main></main>