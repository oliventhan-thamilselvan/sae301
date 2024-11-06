<footer class="site-footer">
    <div class="footer-content">
        <!-- Logo et Nom du Site -->
        <div class="footer-logo">
            <a href="<?php echo esc_url(home_url('/')); ?>" class="footer-site-logo">
                <img src="http://localhost/rocketrivals/wp-content/uploads/2024/11/logooo.png" alt="Logo Rocket Rivals" style="width: 78px; height: 78px;">
            </a>
            <p class="footer-name">Rocket Rivals</p>
        </div>

        <!-- Menu de navigation dans le footer -->
        <nav class="footer-nav">
            <?php
            wp_nav_menu(array(
                'theme_location' => 'footer', // Utilise le même menu que dans le header
                'container' => false, // Pas de conteneur supplémentaire
                'items_wrap' => '<ul class="footer-menu">%3$s</ul>', // Structure de la liste du menu
            ));
            ?>
        </nav>

        <!-- Texte des droits d'auteur -->
        <p class="footer-copyright">Tous droits réservés.</p>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
