
<a href="<?php echo get_permalink() ?>" class="thumb">

  <figure style="background-image:url('<?php echo get_template_directory_uri();?>/library/images/no-image.svg')">
    <div class="content">
      <?php the_post_thumbnail('medium'); ?>
    </div>
  </figure>

  <article>
    <div class="title">
      <h5>
      <?php  include( locate_template( 'templates/overview/thumb-meta.php', false, false ) ); ?>
        </h5>
      <h4 class="section-color"><?php the_title(); ?></h4>
      <div class="mid">
        <?php the_field('intro'); ?>
      </div>
    </div>
  </article>
</a>
