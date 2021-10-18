  <a class="swiper-slide" href="<?php the_permalink();?>">
    <article>
    <figure>
      <div class="content">
        <?php the_post_thumbnail('medium_large'); ?>
      </div>
    </figure>
    <div class="text">
      <h5>
        <?php include( locate_template( 'templates/overview/thumb-meta.php', false, false ) ); ?>
      </h5>
      <h1 class="section-color"><?php
      $title = get_the_title();
      $excerpt = wp_trim_words( $title, $num_words = 5, $more = '...' );
      if (get_sub_field('ny_rubrik')) {
        $title = get_sub_field('headline');
        $excerpt = $title;
      }
      echo $excerpt;
      ?></h1>
      <p><?php
          $excerpt = wp_trim_words( get_field('intro' ), $num_words = 20, $more = '...' );
          echo $excerpt;
           ?></p>
    </div>
    </article>
  </a>
