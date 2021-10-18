<?php
/*
 Template Name: Senaste Utställningar
 Template Post Type: page
*/
?>

<?php get_header(); ?>

<?php
  global $post;
  $currentAuthor=$post->post_author;
  $today = date('Y-m-d H:i');

  $posts = get_posts(array(
  'post_type'	  => 'exhibitions',
  'posts_per_page' => -1,
  'order'          => 'DESC',
  'meta_key'       => 'start_date',
  'orderby'        => 'meta_value',
  'meta_query'		=> array(
		'relation' => 'AND',
    array(
        'key'		=> 'end_date',
        'compare'	=> '>=',
        'value'		=> $today,
        'type' => 'DATETIME',
      ),
      array(
          'key'		=> 'start_date',
          'compare'	=> '<=',
          'value'		=> $today,
          'type' => 'DATETIME',
        )
	)
)
);

if( $posts ) { ?>
  <h1 class="section-color">Aktuella utställningar</h1>
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
  global $post;
  $currentAuthor=$post->post_author;
  $today = date('Y-m-d H:i');

  $posts = get_posts(array(
  'post_type'	  => 'exhibitions',
  'posts_per_page' => -1,
  'order'          => 'ASC',
  'orderby'        => 'meta_value',
  'meta_key'       => 'start_date',
  'meta_type'      => 'DATETIME',
  'meta_query'     => array(
  array(
      'key'		=> 'start_date',
      'compare'	=> '>',
      'value'		=> $today,
    )
  )
)
);

if( $posts ) { ?>
  <h1 class="section-color">Kommande utställningar</h1>
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
  'post_type'	  => 'exhibitions',
  'posts_per_page' => $amountOfPosts,
  'order'          => 'DESC',
  'orderby'        => 'meta_value',
  'meta_key'       => 'start_date',
  'meta_type'      => 'DATETIME',
  'meta_query'		=> array(
		'relation' => 'AND',
    array(
        'key'		=> 'end_date',
        'compare'	=> '<',
        'value'		=> $today,
      ),
      array(
          'key'		=> 'start_date',
          'compare'	=> '<',
          'value'		=> $today,
        )
	)
)
);

if( $posts ) { ?>
  <h1 class="section-color">Tidigare utställningar</h1>
  <section class="thumbs">
    <?php
    foreach( $posts as $post ) {
      setup_postdata( $post );
      include( locate_template( 'templates/overview/thumb.php', false, false ) );
      $postCounter++;

      if($postCounter == $amountOfPosts ) { ?>

        <section class="start-headline line center">
          <a href="/utstallningar">Visa alla utställningar</a>
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
