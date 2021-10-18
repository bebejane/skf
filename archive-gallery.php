
<?php get_header(); ?>

<h1 class="section-color"><?php the_field('gallery_name','options');?></h1>

<section class="thumbs">
<?php
    global $wp_query, $paged;

    if( get_query_var('paged') ) {
        $paged = get_query_var('paged');
    }
    else if ( get_query_var('page') ) {
        $paged = get_query_var('page');
    }
    else{
        $paged = 1;
    }

    $wp_query = null;
    $args = array(
        'post_type' => array("gallery"),
        'order'=>'ASC',
        'orderby' => 'title',
        'posts_per_page' => 30,
        'paged' => $paged
    );
    $wp_query = new WP_Query();
    $wp_query->query( $args );

    while ($wp_query->have_posts()) : $wp_query->the_post(); ?>
        <?php include( locate_template( 'templates/overview/thumb.php', false, false ) ); ?>
      <?php
    endwhile; ?>

</section>

    <section class="start-headline even line">
    <?php
        previous_posts_link('Tillbaka');
        next_posts_link('Visa fler');
        wp_reset_query();
      ?>
    </section>

<?php get_footer(); ?>
