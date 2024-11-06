<?php
/* Template Name: Page Projets */

get_header(); ?>

<div class="projects-container">
    <h1>Tous les Projets</h1>
    
    <div class="project-grid"> <!-- Conteneur de grille pour organiser les cartes -->
        <?php
        $args = array(
            'post_type' => 'projet',
            'posts_per_page' => -1,
        );

        $projects = new WP_Query($args);

        if ($projects->have_posts()) {
            while ($projects->have_posts()) {
                $projects->the_post(); ?>
                
                <div class="project-item">
                    <h2><?php the_title(); ?></h2>
                    <div class="project-content"><?php the_content(); ?></div>
                    <?php if (has_post_thumbnail()) {
                        the_post_thumbnail();
                    } ?>
                </div>

            <?php }
        } else {
            echo '<p>Aucun projet trouvé.</p>';
        }

        wp_reset_postdata();
        ?>
    </div> <!-- Fin du conteneur de grille -->
</div>

<?php get_footer(); ?>
