
<a href="<?php echo get_permalink() ?>" class="thumb thumb-headline">
  <figure style="background-image:url('<?php echo get_template_directory_uri();?>/library/images/no-image.svg)')">
    <div class="content">
      <?php the_post_thumbnail('full'); ?>
    </div>
  </figure>

  <article>
    <div class="title">
      <h5><?php  include( locate_template( 'templates/overview/thumb-meta.php', false, false ) ); ?></h5>
      <h2 class="section-color">
        <?php
          if (get_sub_field('headline')) {
            the_sub_field('headline');
          }
          else {
            the_title();
          }
          ?>
      </h2>
    </div>
  </article>
</a>
