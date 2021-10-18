
  <?php
  $tags = wp_get_post_terms( get_queried_object_id(), 'swirl-theme', ['fields' => 'ids'] );

  $args = [
      'post__not_in'        => array( get_queried_object_id() ),
      'posts_per_page'      => 3,
      'ignore_sticky_posts' => 1,
      'orderby'             => 'rand',
      'tax_query' => [
          [
              'taxonomy' => 'swirl-theme',
              'terms'    => $tags
          ]
      ]
  ];


  $my_query = new wp_query( $args );
  if( $my_query->have_posts() ) { ?>
      <section class="related-posts">
        <h2 class="section-color">Relaterat</h2>
          <div class="thumbs">
            <?php
            while( $my_query->have_posts() ) {
                $my_query->the_post(); ?>
                <?php get_template_part( 'templates/overview/thumb' ); ?>
              <?php }
              wp_reset_postdata();
              ?>
            </div>
          </section>
      <?php
      }
    ?>
