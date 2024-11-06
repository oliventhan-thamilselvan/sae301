<?php get_header(); ?>
<?php if (have_posts()) : ?>
   <?php while (have_posts()) : the_post(); ?>
       <article class="equipe">
           <?php the_post_thumbnail( 'large' ); ?>
           <h1 class="title">
           <?php the_title(); ?>
           </h1>
           <div class="content">
               <?php the_content(); ?>
           </div>
       </article>
   <?php endwhile; ?>
<?php endif; ?>
<?php get_footer(); ?>