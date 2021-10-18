<?php
/*
 Template Name: Startsida
*/
?>

<?php get_header();
$excluded_posts = [];
$use_offset = 0;
?>

<?php if(get_field('start_intro')) { ?>
  <section class="desc line">
    <p class="intro"><a href="/om"><?php the_field('start_intro_text'); ?> <strong class="section-color">›</strong></a></p>
    <p class="mid line"><a href="/om">Mer om vår förening</a><br><a href="/kontakt">Kontakta oss</a>
    </p></section>
<?php } ?>

  <?php if(get_field('manual_selection')) { ?>
    <?php
      if( have_rows('big_image') ): ?>
        <section class="swiper-container big-image line">
          <div class="swiper-wrapper">
            <?php
              while ( have_rows('big_image') ) : the_row();

              $post_object = get_sub_field('post_id');

              if( $post_object ):
              	$post = $post_object;
              	setup_postdata( $post );
                $post_id = get_the_ID();
                array_push($excluded_posts, $post_id);

                include( locate_template( 'templates/overview/thumb-big-image.php', false, false ) );
                wp_reset_postdata(); // IMPORTANT - reset the $post object so the rest of the page works correctly ?>
              <?php endif;

              endwhile; ?>
          </div>
          <div class="swiper-pagination"></div>
          <div class="swiper-button-next"></div>
          <div class="swiper-button-prev"></div>
        </section>
<?php
endif;
}
else {
  $use_offset = 3;
?>

  <section class="swiper-container big-image line">
    <div class="swiper-wrapper">
      <?php
      global $post;
      $today = date('Y-m-d H:i:s');
      $posts = get_posts(array(
      'post_type'	  => array('activities', 'exhibitions', 'lotteries', 'news'),
      'posts_per_page' => 3,
      'order'          => 'ASC',
      'orderby'        => 'meta_value',
      'meta_key'       => 'start_date',
      'meta_type'      => 'DATETIME',
      'meta_query'     => array(
      array(
          'key'		=> 'end_date',
          'compare'	=> '>',
          'value'		=> $today,
        )
      )
    )
    );
      if( $posts ) {
        foreach( $posts as $post ) {
          setup_postdata( $post );
          include( locate_template( 'templates/overview/thumb-big-image.php', false, false ) );
        }
        wp_reset_postdata();
      }
      ?>
    </div>
    <div class="swiper-pagination"></div>
    <div class="swiper-button-next"></div>
    <div class="swiper-button-prev"></div>
  </section>

<?php } ?>


<section class="thumbs">
  <?php
    global $post;
    $today = date('Y-m-d H:i:s');
    $posts = get_posts(array(
    'post_type'	  => array('activities', 'exhibitions', 'lotteries', 'news'),
    'offset' => $use_offset,
    'posts_per_page' => 3,
    'order'          => 'ASC',
    'orderby'        => 'meta_value',
    'meta_key'       => 'start_date',
    'meta_type'      => 'DATETIME',
    'meta_query'     => array(
    array(
        'key'		=> 'end_date',
        'compare'	=> '>',
        'value'		=> $today,
      )
    )
  )
  );

  if( $posts ) {
    foreach( $posts as $post ) {
      setup_postdata( $post );
      include( locate_template( 'templates/overview/thumb.php', false, false ) );
    }
    wp_reset_postdata();
  }
  ?>
</section>




<?php get_footer(); ?>
