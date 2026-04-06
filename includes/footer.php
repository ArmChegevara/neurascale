<?php
?>
</main>

<footer class="site-footer">
    <div class="container footer-grid">
        <div>
            <h3>NeuraScale</h3>
            <p>
                Création de sites web professionnels, automatisation de processus
                et accompagnement digital pour les entreprises en France.
            </p>
        </div>

        <div>
            <h4>Navigation</h4>
            <ul class="footer-links">
                <li><a href="/index.php">Accueil</a></li>
                <li><a href="/pages/services.php">Services</a></li>
                <li><a href="/pages/projets.php">Projets</a></li>
                <li><a href="/pages/automatisation.php">Automatisation</a></li>
                <li><a href="/pages/processus.php">Processus</a></li>
                <li><a href="/pages/contact.php">Contact</a></li>
            </ul>
        </div>

        <div>
            <h4>Contact</h4>
            <p>Email : <?= e($site['email']); ?></p>
            <p>Téléphone : <?= e($site['phone']); ?></p>
            <div class="social-icons">
                <a href="https://www.google.com/maps/..." target="_blank">
                    <a href="https://facebook.com/..." target="_blank">
                        <a href="https://instagram.com/..." target="_blank">
                            <a href="https://youtube.com/..." target="_blank">
            </div>
        </div>
    </div>
    <div class="container footer-bottom">
        <p>© <?= date('Y'); ?> <?= e($site['site_name']); ?> — Tous droits réservés.</p>
    </div>
</footer>

<script>
    /* Effet d'écriture lettre par lettre pour le texte principal */
    document.addEventListener('DOMContentLoaded', function() {
        const typingElement = document.getElementById('typing-text');

        if (!typingElement) {
            return;
        }

        const text = typingElement.getAttribute('data-text') || '';
        let index = 0;
        typingElement.textContent = '';

        function typeLetter() {
            if (index < text.length) {
                typingElement.textContent += text.charAt(index);
                index++;
                setTimeout(typeLetter, 22);
            }
        }

        typeLetter();
    });
</script>
</body>

</html>