<aside class="related">
    <h5>Relaterat</h5>
  <ul>
    <?php
        while ( have_rows('related') ) : the_row(); ?>
            <li class="mid">
              <?php if( get_sub_field('type') == 'external' ) { ?>
              <span class="section-color icon">›</span><span class="link"><a href="<?php the_sub_field('url')?>" target="new"><?php the_sub_field('text')?></a></span>
            <?php }
            else if( get_sub_field('type') == 'internal' ) { ?>
              <span class="section-color icon">›</span><span class="link"><a href="<?php the_sub_field('internal_page')?>"><?php the_sub_field('text')?></a></span>
            <?php }
            else if( get_sub_field('type') == 'file' ) { ?>
              <span class="section-color icon">‹</span><span class="link"><a href="<?php the_sub_field('file')?>" target="new"><?php the_sub_field('text')?></a></span>
            <?php } ?>

          </li>
        <?php
      endwhile; ?>
  </ul>
</aside>
