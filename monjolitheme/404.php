<?php get_header(); ?>

<div class="error-404">
    <h1 class="error-title">Oh non ! Erreur 404</h1>
    <p class="error-description">Nous sommes désolés, mais la page que vous recherchez semble introuvable.</p>
    <img src="<?php echo get_template_directory_uri(); ?>/images/404-image.jpg" alt="Erreur 404" class="error-image">
    <p class="error-suggestions">Voici quelques suggestions pour vous aider :</p>
    <ul class="error-suggestions-list">
        <li>Revenez à la <a href="<?php echo home_url(); ?>">page d'accueil</a>.</li>
        <li>Utilisez la barre de recherche pour trouver ce que vous cherchez.</li>
        <li>Contactez-nous si vous pensez qu'il s'agit d'une erreur.</li>
    </ul>
</div>

<?php get_footer(); ?>