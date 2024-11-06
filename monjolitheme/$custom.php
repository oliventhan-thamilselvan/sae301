<div class="custom">
<?php
/*
Template Name: MaCustomDePage
*/
?>
<?php get_header(); ?>
<div class="main page">
<?php if (have_posts()) : ?>
<?php while (have_posts()) : the_post(); ?>
<div class="post">
<h1 class="post-title"><?php the_title(); ?></h1>
<div class="post-content">
<h1> Bilan de notre Site </h1>
<p>Posts : <strong><?php echo wp_count_posts()->publish; ?></strong></p>
<p>Pages : <strong><?php echo wp_count_posts('page')->publish; ?></strong></p>
<p>Commentaires visibles : <strong><?php echo wp_count_comments()->approved; ?></strong></p>
<?php the_content(); ?>
</div>
</div>
<?php endwhile; ?>
<?php endif; ?>
</div>
<?php get_sidebar(); ?>
<?php get_footer(); ?>
</div>