<?php
$currentPage = 'index';
$pageTitle = 'NeuraScale | Création de sites web et solutions digitales';
$pageDescription = 'NeuraScale crée des sites web professionnels, prépare vos domaines, structure votre présence en ligne et développe des solutions digitales adaptées à votre activité.';

require __DIR__ . '/../includes/header.php';

$heroText = "Chez NeuraScale, je conçois des sites web professionnels, rapides et pensés pour convertir. J’interviens sur la création de sites vitrines, la préparation technique du projet, la réservation et la configuration de noms de domaine, la structuration de contenus, l’optimisation de la présence en ligne, l’automatisation de certaines tâches métier, ainsi que le développement de solutions sur mesure. Un développeur ne crée pas seulement des pages : il peut aussi améliorer vos processus, connecter vos outils, sécuriser votre présence digitale et construire une base solide pour faire évoluer votre activité.";
?>

<section class="hero section">
    <div class="container hero-grid">
        <div class="hero-content">
            <span class="eyebrow">Agence digitale · PHP · France</span>
            <h1>Un site professionnel qui inspire confiance et soutient votre croissance.</h1>

            <div class="typing-box">
                <p id="typing-text" data-text="<?= e($heroText); ?>"></p>
                <span class="typing-cursor"></span>
            </div>

            <div class="hero-actions">
                <a class="button button-primary" href="/pages/contact.php">Demander un devis</a>
                <a class="button button-secondary" href="/pages/services.php">Découvrir mes services</a>
            </div>
        </div>

        <div class="hero-card">
            <div class="card-glow"></div>
            <div class="service-preview">
                <span class="mini-label">Ce que je peux mettre en place</span>
                <ul>
                    <li>Création de site vitrine professionnel</li>
                    <li>Préparation et configuration du domaine</li>
                    <li>Structure technique propre et évolutive</li>
                    <li>Pages orientées conversion</li>
                    <li>Automatisation de tâches et formulaires</li>
                    <li>Développement de solutions métiers</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<section class="section section-light">
    <div class="container">
        <div class="section-heading">
            <span class="eyebrow">Positionnement</span>
            <h2>Des services concrets pour une présence digitale crédible</h2>
            <p>
                Mon objectif est simple : construire un site utile, professionnel et prêt à servir
                de base solide pour votre activité, votre communication et votre acquisition de clients.
            </p>
        </div>

        <div class="cards-grid">
            <article class="info-card">
                <h3>Création de sites web</h3>
                <p>
                    Sites vitrines modernes, rapides, responsives et structurés pour présenter
                    clairement vos services et rassurer vos prospects.
                </p>
            </article>

            <article class="info-card">
                <h3>Domaines et mise en ligne</h3>
                <p>
                    Réservation de nom de domaine, préparation technique, configuration de l’hébergement
                    et mise en production propre.
                </p>
            </article>

            <article class="info-card">
                <h3>Automatisation</h3>
                <p>
                    Intégration de formulaires, scénarios automatiques, workflows simples et connexions
                    entre outils pour gagner du temps.
                </p>
            </article>

            <article class="info-card">
                <h3>Développement sur mesure</h3>
                <p>
                    Un développeur peut aller plus loin qu’un simple site : outils internes, logique métier,
                    pages dynamiques, traitement de données, optimisation de processus.
                </p>
            </article>
        </div>
    </div>
</section>

<section class="section">
    <div class="container cta-panel">
        <div>
            <span class="eyebrow">Projet</span>
            <h2>Vous avez besoin d’un site sérieux, clair et prêt à évoluer ?</h2>
            <p>
                Je peux vous accompagner de l’idée initiale jusqu’à la mise en ligne,
                avec une approche simple, structurée et professionnelle.
            </p>
        </div>

        <div class="cta-actions">
            <a class="button button-primary" href="/pages/contact.php">Parler de votre projet</a>
            <a class="button button-secondary" href="/pages/processus.php">Voir le processus</a>
        </div>
    </div>
</section>

<?php require __DIR__ . '/../includes/footer.php'; ?>