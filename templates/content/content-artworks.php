<section class="artworks">
  <?php
    if( have_rows('artworks') ):
      while ( have_rows('artworks') ) : the_row(); ?>
      <div class="artwork">
          <figure>
            <div class="content">
              <?php
              $image = get_sub_field('image');
              $size = 'small';
                if( $image ) {
                    echo wp_get_attachment_image( $image, $size );
                }
              ?>
            </div>
          </figure>
          <p>
            <span class="strong section-color">
            <strong><?php the_sub_field('title'); ?></strong>
          </span><span class="by"> av</span> <?php the_sub_field('artists'); ?>
          </p>
          <div class="artwork-meta">
          <p>
            <span><?php the_sub_field('details'); ?></span>
            <?php if (get_sub_field('value')) { ?>
              <span>värde <?php the_sub_field('value'); ?> kr</span>
            <?php } ?>
            <?php if (get_sub_field('link')) { ?>
              <br><a href="<?php the_sub_field('link'); ?>">Visa hemsida / CV</a>
            <?php } ?>
          </p>
          </div>
        </div>
        <?php
      endwhile;
  endif;
  ?>
</section>
