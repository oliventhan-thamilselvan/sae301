<?php
/* Template Name: Page Projets */

get_header(); ?>

<div class="projects-container">
    <h1>Tous les Projets</h1>

    <div class="project-grid"> <!-- Ajout d'un conteneur de grille -->
        <?php
        // Arguments de la requête pour récupérer les projets
        $args = array(
            'post_type' => 'projet',  // Assurez-vous que c'est bien le type de post "projet"
            'posts_per_page' => -1,   // Récupérer tous les projets
        );

        // La requête
        $projects = new WP_Query($args);

        // La boucle
        if ($projects->have_posts()) {
            while ($projects->have_posts()) {
                $projects->the_post(); ?>
                
                <div class="project-item">
                    <h2><?php the_title(); ?></h2>
                    <div class="project-content"><?php the_content(); ?></div>

                    <?php
                    // Récupérer toutes les équipes liées à ce projet
                    $linked_teams = get_posts(array(
                        'post_type' => 'equipe',
                        'meta_query' => array(
                            array(
                                'key' => 'linked_project',
                                'value' => get_the_ID(),  // ID du projet courant
                                'compare' => '='
                            )
                        )
                    ));

// Vérifier s'il y a des équipes liées
if ($linked_teams) {
    echo '<h3>Équipes liées :</h3><ul class="linked-teams">'; // Ajout de la classe ici
    foreach ($linked_teams as $team) {
        echo '<li>';
        echo '<a href="' . get_permalink($team->ID) . '" class="linked-team">' . esc_html($team->post_title) . '</a>'; // Ajout de la classe ici

        // Affichage de la description de l'équipe
        $team_description = apply_filters('the_content', $team->post_content); // Récupérer et appliquer les filtres de contenu
        echo '<div class="team-description">' . $team_description . '</div>'; // Affiche la description
        echo '</li>';
    }
    echo '</ul>';
} else {
    echo '<p>Aucune équipe liée à ce projet.</p>';
}

                    // Lien vers la page de projet sélectionné
                    echo '<p><a href="' . get_permalink() . '?project_id=' . get_the_ID() . '">Voir les détails du projet</a></p>';

                    if (has_post_thumbnail()) {
                        the_post_thumbnail();
                    }
                    ?>
                </div>

            <?php }
        } else {
            echo '<p>Aucun projet trouvé.</p>';
        }

        // Réinitialiser les données post
        wp_reset_postdata();
        ?>
    </div> <!-- Fin du conteneur de grille -->
</div>

<?php get_footer(); ?>
