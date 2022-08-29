

<h1 class="section-color"><?php
if( get_field('alt_headline') ) {
  the_field('alt_headline');
  }
  else {
    the_title();
}
?></h1>

<div class="left">
<?php

  if(get_field('has_gallery') && get_field('gallery') && count(get_field('gallery')) > 1 ) {
    get_template_part( 'templates/content/content-gallery' );
  }
  else if( get_the_post_thumbnail()) {
    get_template_part( 'templates/content/content-thumbnail' );
  }

  if(get_field('intro')) {
    get_template_part( 'templates/content/content-intro' );
  }

  if(get_field('text')) {
    get_template_part( 'templates/content/content-text' );
  }

  if(get_field('has_video')) {
    if( have_rows('videos') ):
    while( have_rows('videos') ) : the_row();
      get_template_part( 'templates/content/content-video' );
    endwhile;
  endif;
  }
?>
</div>

<div class="right">
<?php
  if (!is_singular('news')) {
    if(get_field('start_date')) {
      get_template_part( 'templates/content/content-event' );
    }
  }

  if ( ( is_page_template( 'page-member.php' ) && get_field('show_form') ) ) { ?>
    <aside class="related">
      <h5>Bli medlem</h5>
      <?php
      if(get_field('member_id','option')) {
        $form_id = get_field('member_id','option');
        echo do_shortcode( '[wpforms id="' . $form_id . '"]');
        }
      else {
        echo do_shortcode( '[wpforms id="34"]');
      }
       ?>
    </aside>
  <?php }

  if( ( is_singular( 'activities' ) && get_field('show_form') )) { ?>
    <aside class="related">
      <h5>Anmälan</h5>
      <?php
      if(get_field('participate_id','option')) {
        $form_id = get_field('participate_id','option');
        echo do_shortcode( '[wpforms id="' . $form_id . '"]');
        }
      else {
        echo do_shortcode( '[wpforms id="35"]');
      } ?>
    </aside>
  <?php

  }

  if(have_rows('related')) {
    get_template_part( 'templates/content/content-related' );
  }
?>
</div>

<?php if(get_field('artworks')) {
  get_template_part( 'templates/content/content-artworks' );
  }
?>
