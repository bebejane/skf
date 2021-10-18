<?php
/*
 Template Name: Kontakt
 Template Post Type: page
*/
?>

<?php get_header(); ?>

  <?php include( locate_template( 'templates/content/content.php', false, false ) ); ?>

  <h2 class="section-color"><?php the_field('headline'); ?></h2>

  <?php if(get_field('people')) {
      get_template_part( 'templates/content/content-people' );
    }
  ?>

<?php get_footer(); ?>
