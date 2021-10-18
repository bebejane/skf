<div class="thumb-meta">
  <div class="arrow section-color icon">»</div>
  <div class="meta-wrapper">
  <span>
<?php
$post_type = get_post_type( get_the_ID() );
$post_obj = get_post_type_object($post_type);

if( ( is_tax('resources-category') == false ) && ( is_post_type_archive('resources') == false )) {
  if(get_field('start_date')) {
    $unix = strtotime( get_field('start_date') );
    echo date_i18n( 'j/n Y', $unix );
  }
  else {
    echo apply_filters( 'the_date', get_the_date(), get_option( 'date_format' ), '', '' );
  }
}
?></span>
<?php if(is_front_page()) { ?>
<span class="post-type"><span class="section-color bullet">•</span><? echo $post_obj->labels->name; ?></span>
<?php } ?>
<?php
    $taxonomy = strval($post_type . '-category');
    $terms = wp_get_object_terms( $post->ID, $taxonomy);
    if ( ! empty( $terms ) ) {
      if ( ! is_wp_error( $terms ) ) {
              foreach( $terms as $term ) {
                  echo "<span class='section-color bullet'>•</span>" . esc_html( $term->name );
              }
      }
    }
?>
</div>
</div>
