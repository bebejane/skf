<aside class="event related">
  <h5>Summering</h4>
  <div>
  <h5 class="section-color">När</h5><p class="mid">
        <?php
            $unix = strtotime( get_field('start_date') );
            $startDate = date_i18n( 'j M Y', $unix );
            echo $startDate;
            $time = date_i18n( 'H:i', $unix );
            if( $time != "00:00") {
              echo ", " . $time;
            }
            $unixEnd = strtotime( get_field('end_date') );
            $endDate = date_i18n( 'j M Y', $unixEnd );
            if($startDate != $endDate) {
              echo " – " . $endDate;
              $time = date_i18n( 'H:i', $unixEnd );
              if( $time != "00:00") {
                echo ", " . $time;
              }
            }
        ?></p>

  <?php if (get_sub_field('by')) { ?>
      <h5 class="section-color">Arrangör</h5>
      <p class="mid"><?php the_sub_field('by'); ?></p>
    <?php } ?>

<?php if (get_field('place')) { ?>

  <h5 class="section-color">Var</h5>
  <p class="mid">
    <?php
    if (get_field('place')) {
      echo get_field('place') . ", ";
    }
    if (get_field('address')) {
      echo get_field('address') . ", ";
    }
    if (get_field('city')) {
      echo get_field('city');
    } ?>
  <?php if(get_field('event_url') ) {  ?>
    <h5 class="section-color"><strong class="section-color">›</strong> <a href="<?php the_field('event_url'); ?>">Läs mer</a></h5>
  <?php }
  }
   ?>
  </div>
</aside>
