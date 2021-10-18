<section class="schedule center-content">
  <h3 class="section-color"><?php the_sub_field('schedule_headline');?></h3>
  <table class="normal">
  <?php
  if( have_rows('rows') ):
      while ( have_rows('rows') ) : the_row();
        ?>

      <tr>
        <?php if( get_sub_field('type') == 'time' ) { ?>
          <td><?php the_sub_field('start_time'); ?> — <?php the_sub_field('end_time'); ?></td>
          <td><?php the_sub_field('headline'); ?><p class="mid"><?php the_sub_field('desc'); ?></p></td>
        <?php  }
        else { ?>
          <td><h6><?php the_sub_field('headline'); ?></h6></td>
          <td></td>
        <?php  } ?>
      </tr>
      <?php endwhile;
  endif;
  ?>
  </table>
</section>
