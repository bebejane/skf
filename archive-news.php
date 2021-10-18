<?php get_header(); ?>

<h1 class="section-color">Nyheter</h1>
<section class="thumbs">
  <?php while ( have_posts() ) : the_post(); ?>
    <?php include( locate_template( 'templates/overview/thumb-text.php', false, false ) ); ?>
  <?php endwhile; ?>
</section>

<?php
if ($wp_query->max_num_pages > 1) : ?>
  <section class="start-headline even line">
    <?php
      previous_posts_link('Tillbaka');
      next_posts_link('Visa fler');
      wp_reset_query();
    ?>
  </section>
<?php endif; ?>

<?php get_footer(); ?>
