<ul class="collaborators">
  <?php

  if( have_rows('collaborators') ):
      while ( have_rows('collaborators') ) : the_row(); ?>

          <li>
              <a href="<?php the_sub_field('url'); ?>">
              <?php
              $image = get_sub_field('logo');
              $size = 'small';
              if( $image ) {
                  echo wp_get_attachment_image( $image, $size );
              }
            ?>
            </a>
        </li>

      <?php
      endwhile;
  endif;

  ?>
</ul>
