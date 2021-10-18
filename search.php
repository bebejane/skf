<?php get_header(); ?>

<h1 class="section-color">Sökresultat</h1>

  <secion class="thumbs">
      <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
      <?php  include( locate_template( 'templates/overview/thumb-search.php', false, false ) ); ?>
	      <?php endwhile; ?>
      <?php else : ?>

        Tyvärr, vi hittar inget svar.

	    <?php endif; ?>
    </section>

<?php get_footer(); ?>
