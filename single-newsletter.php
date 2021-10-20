<?php

// Check rows exists.
if( have_rows('sections') ):

    // Loop through rows.
    while( have_rows('sections') ) : the_row();

        // Load sub field value.
        the_sub_field('headline');

        the_sub_field('text');

        the_sub_field('url');
    
        // Do something...

    // End loop.
    endwhile;

  endif;

?>