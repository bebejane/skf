<section class="gallery">
  <div class="swiper-container">
    <div class="swiper-wrapper">
      <?php
      $images = get_field('gallery');
      $size = 'medium_large';
      if( $images ): ?>
              <?php foreach( $images as $image_id ): ?>
                  <figure class="swiper-slide">
                    <div class="content">
                      <?php echo wp_get_attachment_image( $image_id, $size ); ?>
                    </div>
                    <div class="caption"><?php echo wp_get_attachment_caption( $image_id); ?>&nbsp;</div>
                  </figure>
              <?php endforeach; ?>
      <?php endif; ?>
     </div>
  </div>
  <div class="swiper-footer">
    <div class="swiper-button-prev"></div>
    <div class="swiper-button-next"></div>
    <div class="caption">&nbsp;</div>

  </div>
</section>
