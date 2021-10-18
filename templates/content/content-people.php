<ul class="people mid content">
  <?php

  if( have_rows('people') ):
      while ( have_rows('people') ) : the_row(); ?>

          <li>
            <?php if(get_field('show_people')) { ?>
                <figure style="background-image:url(<?php echo get_template_directory_uri();?>/library/images/no-portrait.svg)">
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
          <?php } ?>
          <p>
          <span class="strong section-color"><?php the_sub_field('name'); ?></span><br>
          <?php the_sub_field('title'); ?><br>
          <?php if( get_sub_field('phone') ) {
            echo "Tel. " . get_sub_field('phone') . "<br>";
          }
           if(get_sub_field('cell')) {
            echo "Mob. " . get_sub_field('cell');
          } ?>
          <span class="email"><a href="mailto:<?php the_sub_field('email'); ?>"><?php the_sub_field('email'); ?></a></span><br>
          </p>
          <?php echo "<p>" . get_sub_field('text') . "</p>"; ?>
        </li>

      <?php
      endwhile;
  endif;

  ?>
</ul>
