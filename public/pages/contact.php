<?php

declare(strict_types=1);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();

$currentPage = 'contact';
$pageTitle = 'Contact | NeuraScale';
$pageDescription = 'Contactez NeuraScale pour discuter de votre site web ou de votre projet digital.';



/**
 * Nettoie une chaîne reçue depuis le formulaire.
 */
function clean_input(mixed $value, int $maxLength = 2000): string
{
    if (!is_string($value)) {
        return '';
    }

    $value = trim($value);
    $value = strip_tags($value);

    if (mb_strlen($value) > $maxLength) {
        $value = mb_substr($value, 0, $maxLength);
    }

    return $value;
}

/**
 * Génère un jeton CSRF si nécessaire.
 */
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/../../includes/header.php';

/**
 * Configuration SMTP depuis les variables d'environnement.
 */
$smtpHost = $_ENV['SMTP_HOST'] ?? '';
$smtpPort = (int) ($_ENV['SMTP_PORT'] ?? 465);
$smtpEncryption = $_ENV['SMTP_ENCRYPTION'] ?? 'ssl';
$smtpUsername = $_ENV['SMTP_USERNAME'] ?? '';
$smtpPassword = $_ENV['SMTP_PASSWORD'] ?? '';
$mailFromAddress = $_ENV['MAIL_FROM_ADDRESS'] ?? 'contact@neurascale.fr';
$mailFromName = $_ENV['MAIL_FROM_NAME'] ?? 'NeuraScale';
$mailToAddress = $_ENV['MAIL_TO_ADDRESS'] ?? 'contact@neurascale.fr';

/**
 * Sécurisation des données du site.
 * On utilise uniquement le tableau $site pour éviter les incohérences.
 */
$siteEmail = is_string($site['email'] ?? null) ? trim($site['email']) : 'contact@neurascale.fr';
$sitePhone = is_string($site['phone'] ?? null) ? trim($site['phone']) : '';
$siteCity  = is_string($site['city'] ?? null) ? trim($site['city']) : 'Reims';

$formData = [
    'name' => '',
    'email' => '',
    'message' => '',
];

$errors = [];
$success = false;

/**
 * Gestion du formulaire de contact.
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formData['name'] = clean_input($_POST['name'] ?? '', 120);
    $formData['email'] = clean_input($_POST['email'] ?? '', 180);
    $formData['message'] = clean_input($_POST['message'] ?? '', 3000);

    $csrfToken = $_POST['csrf_token'] ?? '';
    $honeypot = $_POST['website'] ?? '';

    /**
     * Vérification anti-spam basique.
     */
    if (!empty($honeypot)) {
        $errors[] = 'Soumission invalide.';
    }

    /**
     * Vérification du jeton CSRF.
     */
    if (
        !is_string($csrfToken) ||
        empty($_SESSION['csrf_token']) ||
        !hash_equals($_SESSION['csrf_token'], $csrfToken)
    ) {
        $errors[] = 'La session du formulaire est invalide. Veuillez réessayer.';
    }

    /**
     * Validation des champs.
     */
    if ($formData['name'] === '') {
        $errors[] = 'Veuillez renseigner votre nom.';
    }

    if (
        $formData['email'] === '' ||
        filter_var($formData['email'], FILTER_VALIDATE_EMAIL) === false
    ) {
        $errors[] = 'Veuillez renseigner une adresse email valide.';
    }

    if ($formData['message'] === '') {
        $errors[] = 'Veuillez décrire votre projet.';
    }

    /**
     * Envoi de l'email uniquement si la validation est correcte.
     */
    if ($errors === []) {
        try {
            /**
             * Initialisation du client SMTP.
             */
            $mail = new PHPMailer(true);

            /**
             * Configuration SMTP.
             */
            $mail->isSMTP();
            $mail->Host = $smtpHost;
            $mail->SMTPAuth = true;
            $mail->Username = $smtpUsername;
            $mail->Password = $smtpPassword;
            $mail->CharSet = 'UTF-8';

            /**
             * Choix du chiffrement selon la configuration.
             */
            if ($smtpEncryption === 'tls') {
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            } else {
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            }

            $mail->Port = $smtpPort;

            /**
             * Expéditeur et destinataire.
             */
            $mail->setFrom($mailFromAddress, $mailFromName);
            $mail->addAddress($mailToAddress);
            $mail->addReplyTo($formData['email'], $formData['name']);

            /**
             * Contenu du message.
             */
            $mail->isHTML(false);
            $mail->Subject = 'Nouvelle demande depuis NeuraScale';
            $mail->Body = implode("\n", [
                'Nouvelle demande reçue depuis le site NeuraScale.',
                '',
                'Nom : ' . $formData['name'],
                'Email : ' . $formData['email'],
                '',
                'Message :',
                $formData['message'],
            ]);

            $mail->send();

            $success = true;

            /**
             * Réinitialisation du formulaire après succès.
             */
            $formData = [
                'name' => '',
                'email' => '',
                'message' => '',
            ];

            /**
             * Régénération du jeton CSRF après soumission réussie.
             */
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        } catch (\Exception $exception) {
            $errors[] = 'Une erreur est survenue lors de l’envoi du message.';
            error_log('Erreur SMTP contact.php : ' . $mail->ErrorInfo);
        }
    }

    if ($sent) {
        $success = true;

        /**
         * Réinitialisation du formulaire après succès.
         */
        $formData = [
            'name' => '',
            'email' => '',
            'message' => '',
        ];

        /**
         * Régénération du jeton CSRF après soumission réussie.
         */
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    } else {
        $errors[] = 'Une erreur est survenue lors de l’envoi du message.';
        error_log('Échec mail() depuis contact.php vers: ' . $to);
    }
}

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
            <p><strong>Email :</strong> <?= e($siteEmail); ?></p>
            <p><strong>Téléphone :</strong> <?= e($sitePhone); ?></p>
            <p><strong>Ville :</strong> <?= e($siteCity); ?></p>
        </div>

        <div class="contact-card">
            <h2>Formulaire</h2>

            <?php if ($success): ?>
                <div class="alert alert-success">
                    <strong>Message envoyé avec succès.</strong><br>
                    Nous avons bien reçu votre demande.<br>
                    Vous recevrez une réponse sous 72 heures.
                </div>
            <?php endif; ?>

            <?php if ($errors !== []): ?>
                <div class="alert alert-error">
                    <?php foreach ($errors as $error): ?>
                        <p><?= e($error); ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form method="post" action="">
                <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token']); ?>">

                <div style="display:none;">
                    <label for="website">Website</label>
                    <input id="website" name="website" type="text" value="">
                </div>

                <div class="form-group">
                    <label for="name">Nom</label>
                    <input
                        id="name"
                        name="name"
                        type="text"
                        placeholder="Votre nom"
                        value="<?= e($formData['name']); ?>"
                        required>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input
                        id="email"
                        name="email"
                        type="email"
                        placeholder="Votre email"
                        value="<?= e($formData['email']); ?>"
                        required>
                </div>

                <div class="form-group">
                    <label for="message">Message</label>
                    <textarea
                        id="message"
                        name="message"
                        rows="6"
                        placeholder="Décrivez votre projet"
                        required><?= e($formData['message']); ?></textarea>
                </div>

                <button type="submit" class="button button-primary">Envoyer la demande</button>
            </form>
        </div>
    </div>
</section>

<?php require __DIR__ . '/../../includes/footer.php'; ?>