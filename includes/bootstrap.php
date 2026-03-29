<?php

/* Chargement de la configuration principale du site */
$site = require __DIR__ . '/../config/site.php';

/* Fonction de sécurisation pour l'affichage HTML */
function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

/* Fonction pour gérer l'état actif du menu */
function isActive(string $page, string $currentPage): string
{
    return $page === $currentPage ? 'active' : '';
}
