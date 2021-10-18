
<a href="<?php echo get_permalink() ?>" class="thumb-calendar">
  <article>
    <div class="title">
      <h2 class="section-color">
        <?php if( get_field('alt_headline') ) {
        the_field('alt_headline');
        }
        else {
          the_title();
      } ?></h2>
      <p class="mid">
        <?php
        $post_type = get_post_type( get_the_ID() );
        $post_obj = get_post_type_object($post_type);

        if( ( is_tax('resources-category') == false ) && ( is_post_type_archive('resources') == false )) {
          if(get_field('start_date')) {
            $unix = strtotime( get_field('start_date') );
            echo date_i18n( 'j/n Y', $unix );
          }
          else {
            echo apply_filters( 'the_date', get_the_date(), get_option( 'date_format' ), '', '' );  }
        }
        ?>
        <span class="post-type"><span class="section-color"> • </span><? echo $post_obj->labels->name; ?></span>
      </p>
      <div>
        <p>
        <?php
          if( have_rows('layout') ):
            while ( have_rows('layout') ) : the_row();
                  if( get_row_layout() == 'intro' ):
                    $excerpt = wp_trim_words( get_sub_field('intro' ), $num_words = 30, $more = '...' );
                    echo $excerpt;
                endif;
              endwhile;
          endif; ?>
        </p>
      </div>
    </div>
  </article>
</a>
