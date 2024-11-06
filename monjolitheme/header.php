<!DOCTYPE html> 
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Ajout de la balise viewport pour la responsivité -->
    <title><?php the_title(); ?></title>
    <link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet"> <!-- Polices Google -->
    <style>
        .site-logo img {
            width: 78px;
            height: 78px;
        }
    </style>
    <?php wp_head(); ?>
</head>
<body>
    <div class="wrap">
        <header class="site-header">
            <div class="header-content">
                <!-- Remplacement du titre par le logo -->
                <a href="<?php echo esc_url(home_url('/')); ?>" class="site-logo">
                    <img src="http://localhost/rocketrivals/wp-content/uploads/2024/11/logooo.png" alt="Logo Rocket Rivals">
                </a>
                <h2 class="site-description"><?php bloginfo('description'); ?></h2>
            </div>
            
            <!-- Bouton hamburger -->
            <button class="menu-toggle" onclick="toggleMenu()">☰</button>

            <!-- Navigation principale -->
            <nav class="main-nav">
                <?php 
                wp_nav_menu( 
                    array( 
                        'theme_location' => 'main',
                        'container' => false, // Supprime le conteneur nav par défaut
                        'items_wrap' => '<ul class="menu">%3$s</ul>' // Ajoute une classe pour les éléments du menu
                    ) 
                ); 
                ?>
            </nav>
        </header>

        <!-- Script JavaScript -->
        <script>
        function toggleMenu() {
            document.querySelector('.menu-toggle').classList.toggle('open');
            document.querySelector('.main-nav').classList.toggle('open');
        }
        </script>
