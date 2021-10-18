
<a href="<?php echo get_permalink() ?>" class="thumb thumb-text">
  <article>
    <div class="title">
      <h2 class="section-color"><?php the_title(); ?></h2>
      <h5><?php echo get_the_date(); ?></h5>
      <div class="intro">
        <?php the_field('intro'); ?>
      </div>
    </div>
  </article>
</a>
