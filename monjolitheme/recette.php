<?php
/*
Template Name: AfficheRecette
*/

 get_header(); 
$args = array(
    'post_type' => 'recette',  // Type de contenu 'recette'
    'posts_per_page' => -1,    // Nombre d'articles à afficher (-1 pour afficher tous les articles)
    'orderby' => 'title',      // Trier par titre (vous pouvez utiliser d'autres critères de tri)
    'order' => 'ASC',          // Ordre de tri (ASC pour croissant, DESC pour décroissant)
);

$recette_query = new WP_Query($args);

if ($recette_query->have_posts()) {
    while ($recette_query->have_posts()) {
        $recette_query->the_post();

        // Le contenu de la boucle d'affichage va ici
        // Vous pouvez afficher le titre, le contenu, les métadonnées, etc. de chaque article

        // Exemple d'affichage du titre de l'article:
        echo '<h2>' . get_the_title() . '</h2>';

        // Exemple d'affichage de l'image mise en avant :
        the_post_thumbnail( 'thumbnail' );
    }

    // Réinitialiser les données de la requête
    wp_reset_postdata();
} else {
    // Aucun article "recette" trouvé
    echo 'Aucune recette trouvée.';
}
 get_sidebar(); 
 get_footer(); 
?>