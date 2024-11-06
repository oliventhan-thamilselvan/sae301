<?php get_header(); ?>

<div class="loop">
    <p class="title">Articles avec le tag : <?php single_tag_title(); ?></p>

    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
    <div class="post">
        <h3 class="post-title">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </h3>
        <p class="post-info">
            Posté le <?php the_date(); ?> dans <?php the_category(', '); ?> par <?php the_author(); ?>.
        </p>
        <div class="post-content">
            <?php the_content(); ?>
        </div>
    </div>
    <?php endwhile; else : ?>
    <p class="">kikkkkkkpôdzkpojkdézjipdz^jizéjipfjof.</p>
    <?php endif; ?>
</div>

<?php get_sidebar(); ?>
<?php get_footer(); ?>