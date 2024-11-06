<?php
/* Template Name: Page Équipes */

get_header(); ?>

<div class="teams-container">
    <h1>Toutes les Équipes</h1>
    <br> <!-- Ligne vide pour l'espacement -->

    <?php
    // Arguments de la requête pour récupérer les équipes
    $args = array(
        'post_type' => 'equipe',  // Assurez-vous que c'est bien le type de post "équipe"
        'posts_per_page' => -1,   // Récupérer toutes les équipes
    );

    // La requête
    $teams = new WP_Query($args);

    // La boucle
    if ($teams->have_posts()) {
        echo '<div class="team-cards-container">'; // Conteneur pour les cartes d'équipes
        while ($teams->have_posts()) {
            $teams->the_post(); ?>
            
            <div class="team-card"> <!-- Carte pour chaque équipe -->
                <h2><?php the_title(); ?></h2>
                <div class="team-content"><?php the_content(); ?></div>
                
                <?php
                // Récupérer le projet lié
                $linked_project_id = get_post_meta(get_the_ID(), 'linked_project', true);
                if ($linked_project_id) {
                    $linked_project = get_post($linked_project_id);
                    if ($linked_project) {
                        echo '<p>Projet lié : <a href="' . get_permalink($linked_project_id) . '">' . esc_html($linked_project->post_title) . '</a></p>';
                    }
                }

                if (has_post_thumbnail()) {
                    the_post_thumbnail();
                }
                ?>
            </div>

        <?php }
        echo '</div>'; // Fermer le conteneur des cartes
    } else {
        echo '<p>Aucune équipe trouvée.</p>';
    }

    // Réinitialiser les données post
    wp_reset_postdata();
    ?>
</div>

<?php get_footer(); ?>
