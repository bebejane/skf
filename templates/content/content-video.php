<section class="video wide-content">
  <div class="caption">
    <h3 class="section-color"><?php the_sub_field('headline'); ?></h3>
    <p  ><?php the_sub_field('desc'); ?></p>
  </div>
  <div class="video-wrapper">
    <div class="video-responsive">
      <?php if( get_sub_field('source') == 'vimeo') { ?>
        <iframe title="vimeo-player" src="https://player.vimeo.com/video/<?php the_sub_field('video_id') ?>"
        width="560" height="314" frameborder="0" allowfullscreen></iframe>
      <?php }
      else { ?>
        <iframe width="560" height="315" src="https://www.youtube.com/embed/<?php the_sub_field('video_id') ?>"
           frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
      <?php } ?>
    </div>
  </div>
</section>
