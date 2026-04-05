<?php

declare(strict_types=1);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();

$currentPage = 'contact';
$pageTitle = 'Contact | NeuraScale';
$pageDescription = 'Contactez NeuraScale pour discuter de votre site web ou de votre projet digital.';

/**
 * Nettoie et sécurise les données saisies par l'utilisateur.
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
 * Génération du jeton CSRF pour prévenir les attaques de type Cross-Site Request Forgery.
 */
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/../../includes/header.php';

/**
 * Récupération des paramètres de configuration depuis l'environnement.
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
 * Variables pour l'affichage des informations de contact.
 */
$siteEmail = is_string($site['email'] ?? null) ? trim($site['email']) : 'contact@neurascale.fr';
$sitePhone = is_string($site['phone'] ?? null) ? trim($site['phone']) : '';
$siteCity  = is_string($site['city'] ?? null) ? trim($site['city']) : 'Reims';

$formData = ['name' => '', 'email' => '', 'message' => ''];
$errors = [];
$success = false;

/**
 * Traitement du formulaire lors de la soumission.
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formData['name'] = clean_input($_POST['name'] ?? '', 120);
    $formData['email'] = clean_input($_POST['email'] ?? '', 180);
    $formData['message'] = clean_input($_POST['message'] ?? '', 3000);

    $csrfToken = $_POST['csrf_token'] ?? '';
    $honeypot = $_POST['website'] ?? '';

    // Vérification du pot de miel (anti-spam)
    if (!empty($honeypot)) {
        $errors[] = 'Soumission invalide.';
    }

    // Vérification de la validité du jeton CSRF
    if (!is_string($csrfToken) || empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $csrfToken)) {
        $errors[] = 'Session invalide. Veuillez rafraîchir la page.';
    }

    // Validation des champs obligatoires
    if ($formData['name'] === '') $errors[] = 'Le nom est obligatoire.';
    if ($formData['email'] === '' || !filter_var($formData['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Une adresse email valide est requise.';
    }
    if ($formData['message'] === '') $errors[] = 'Le message ne peut pas être vide.';

    /**
     * Envoi de l'email via PHPMailer si aucune erreur n'est détectée.
     */
    if (empty($errors)) {
        try {
            $mail = new PHPMailer(true);

            // Configuration du serveur SMTP
            $mail->isSMTP();
            $mail->Host       = $smtpHost;
            $mail->SMTPAuth   = true;
            $mail->Username   = $smtpUsername;
            $mail->Password   = $smtpPassword;
            $mail->CharSet    = 'UTF-8';

            // Gestion dynamique du chiffrement (SSL pour le port 465, TLS pour le 587)
            if ($smtpEncryption === 'tls') {
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            } else {
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            }
            $mail->Port = $smtpPort;

            // Définition de l'expéditeur (ton adresse Zimbra) et du destinataire
            $mail->setFrom($mailFromAddress, $mailFromName);
            $mail->addAddress($mailToAddress);

            // L'adresse de réponse sera celle du client
            $mail->addReplyTo($formData['email'], $formData['name']);

            // Contenu de l'email
            $mail->isHTML(false);
            $mail->Subject = 'Nouveau message de contact - NeuraScale';
            $mail->Body    = "Nom : {$formData['name']}\n" .
                "Email : {$formData['email']}\n\n" .
                "Message :\n{$formData['message']}";

            $mail->send();
            $success = true;

            // Réinitialisation des données après succès
            $formData = ['name' => '', 'email' => '', 'message' => ''];
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        } catch (Exception $e) {
            $errors[] = "Erreur lors de l'envoi : " . $mail->ErrorInfo;
            error_log("Erreur SMTP : " . $e->getMessage());
        }
    }
}
?>

<section class="page-hero section">
    <div class="container narrow">
        <span class="eyebrow">Contact</span>
        <h1>Parlons de votre projet</h1>
    </div>
</section>

<section class="section section-light">
    <div class="container contact-layout">
        <div class="contact-card">
            <h2>Coordonnées</h2>
            <p><strong>Email :</strong> <?= htmlspecialchars($siteEmail); ?></p>
        </div>

        <div class="contact-card">
            <?php if ($success): ?>
                <div class="alert alert-success">Message envoyé avec succès !</div>
            <?php endif; ?>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-error"><?= implode('<br>', array_map('htmlspecialchars', $errors)); ?></div>
            <?php endif; ?>

            <form method="post">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']); ?>">
                <div style="display:none;"><input name="website" type="text"></div>

                <div class="form-group">
                    <label for="name">Nom</label>
                    <input id="name" name="name" type="text" value="<?= htmlspecialchars($formData['name']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input id="email" name="email" type="email" value="<?= htmlspecialchars($formData['email']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="message">Message</label>
                    <textarea id="message" name="message" rows="6" required><?= htmlspecialchars($formData['message']); ?></textarea>
                </div>
                <button type="submit" class="button button-primary">Envoyer</button>
            </form>
        </div>
    </div>
</section>

<?php require __DIR__ . '/../../includes/footer.php'; ?>