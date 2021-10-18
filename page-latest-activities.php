<?php
/*
 Template Name: Senaste Aktiviteter
 Template Post Type: page
*/
?>

<?php get_header(); ?>

<?php
  global $post;
  $currentAuthor=$post->post_author;

  $today = date('Y-m-d H:i');
  $posts = get_posts(array(
  'post_type'	  => 'activities',
  'posts_per_page' => -1,
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

if( $posts ) { ?>
  <h1 class="section-color">Aktuella aktiviteter</h1>
  <section class="thumbs">
    <?php
    foreach( $posts as $post ) {
      setup_postdata( $post );
      include( locate_template( 'templates/overview/thumb.php', false, false ) );
    }
    wp_reset_postdata(); ?>
  </section>
  <?php
  }
?>

<?php
  $today = date('Y-m-d H:i');
  $amountOfPosts = 3;
  $postCounter = 0;
  $posts = get_posts(array(
  'post_type'	  => 'activities',
  'posts_per_page' => $amountOfPosts,
  'order'          => 'DESC',
  'orderby'        => 'meta_value',
  'meta_key'       => 'start_date',
  'meta_type'      => 'DATETIME',
  'meta_query'     => array(
  array(
      'key'		=> 'end_date',
      'compare'	=> '<',
      'value'		=> $today,
    )
  )
)
);

if( $posts ) { ?>
  <h1 class="section-color">Tidigare aktiviteter</h1>
  <section class="thumbs">
    <?php
    foreach( $posts as $post ) {
      setup_postdata( $post );
      include( locate_template( 'templates/overview/thumb.php', false, false ) );
      $postCounter++;

      if($postCounter == $amountOfPosts ) { ?>

        <section class="start-headline line center">
          <a href="/aktiviteter">Visa alla aktiviteter</a>
        </section>

        <?php
      }
    }
    wp_reset_postdata(); ?>
  </section>
  <?php
}
?>

<?php get_footer(); ?>
