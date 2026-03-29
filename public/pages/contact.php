<?php
$currentPage = 'contact';
$pageTitle = 'Contact | NeuraScale';
$pageDescription = 'Contactez NeuraScale pour discuter de votre site web ou de votre projet digital.';

require __DIR__ . '/../../includes/header.php';
?>

<section class="page-hero section">
    <div class="container narrow">
        <span class="eyebrow">Contact</span>
        <h1>Parlons de votre projet</h1>
        <p>
            Vous avez besoin d’un site professionnel, d’une refonte, d’une automatisation
            ou d’une solution sur mesure ? Prenons contact.
        </p>
    </div>
</section>

<section class="section section-light">
    <div class="container contact-layout">
        <div class="contact-card">
            <h2>Coordonnées</h2>
            <p><strong>Email :</strong> <?= e($site['email']); ?></p>
            <p><strong>Téléphone :</strong> <?= e($site['phone']); ?></p>
            <p><strong>Ville :</strong> <?= e($site['city']); ?></p>
        </div>

        <div class="contact-card">
            <h2>Formulaire</h2>
            <form method="post" action="#">
                <div class="form-group">
                    <label for="name">Nom</label>
                    <input id="name" name="name" type="text" placeholder="Votre nom">
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input id="email" name="email" type="email" placeholder="Votre email">
                </div>

                <div class="form-group">
                    <label for="message">Message</label>
                    <textarea id="message" name="message" rows="6" placeholder="Décrivez votre projet"></textarea>
                </div>

                <button type="submit" class="button button-primary">Envoyer la demande</button>
            </form>
        </div>
    </div>
</section>

<?php require __DIR__ . '/../../includes/footer.php'; ?>