<?php get_header(); ?>
<?php if (have_posts()) : ?>
  <?php while (have_posts()) : the_post(); ?>
      <article class="projet">
          <?php the_post_thumbnail( 'large' ); ?>
          <h1 class="title">
          <?php the_title(); ?>
          </h1>
          <div class="nutrition">
          <ul>
          <li> sucre : <?php the_field("sucre"); ?></li>
          <li> graisse : <?php the_field("graisse"); ?></li>
          <li> protéine : <?php the_field("proteine"); ?></li>
          </ul>
          </div>
          <?php the_terms( $post->ID, 'nature', 'Nauture : ' ); ?><br>
          <?php the_terms( $post->ID, 'couleur', 'Couleur : ' ); ?><br>
          <div class="content">
              <?php the_content(); ?>
          </div>
      </article>
  <?php endwhile; ?>
<?php endif; ?>
<?php get_footer(); ?>


