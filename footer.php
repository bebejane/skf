  </main>

  <footer>
    <section class="thick-line line">
    <div>
      <h5>Adress</h5>
      <p class="mid">
        <?php the_field('footer_address','options')?>
      </p>
      </div>
      <div>
      <h5>Kontakt</h5>
      <p class="mid">
        <?php the_field('footer_contact','options')?>
        <a href="mailto:<?php the_field('footer_contact_email','options')?>"><?php the_field('footer_contact_name','options')?></a>
      </p>
      </div>
      <div>
      <h5>Följ oss</h5>
      <p class="mid">
        <?php
        if( have_rows('footer_social','options') ):
          while( have_rows('footer_social','options') ) : the_row();
            echo "<a href='" . get_sub_field('url') . "'>" . get_sub_field('service') . "</a> ";
          endwhile;
        endif;
        ?>
      </p>
      </div>
      <div>
      <h5>Hemsida</h5>
      <p class="mid">
        Vår hemsida är skapad med hjälp av <a href="https://sverigeskonstforeningar.nu/">Sveriges Konstföreningar</a>.
      </p>
      </div>

      <div class="last-line">
        <p class="mid">© Konstverken på hemsidan är skyddade enligt upphovsrättslagen. <a href="/juridik">Info om GDRP & Cookies ›</a></p>
      </div>

      <div>
        <p class="mid">© <?php
        if(is_multisite()) {
          global $blog_id;
          $current_blog_details = get_blog_details( array( 'blog_id' => $blog_id ) );
          echo $current_blog_details->blogname;
        }
        ?></p>
      </div>

    </section>
  </footer>

  </div>
  </div>
  </div>

<?php get_template_part( 'templates/content/content-fullscreen-image' ); ?>


		<?php // all js scripts are loaded in library/bones.php ?>
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script>
        var mySwiper = new Swiper ('.swiper-container', {
          autoHeight: true,
          loop: true,
          init: true,

          pagination: {
            el: '.swiper-pagination',
          },

          navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
          },
        })

        mySwiper.on('slideChange', function () {
          var activeSlide = jQuery('.swiper-slide')[mySwiper.realIndex + 1];
          var activeCaption = jQuery(activeSlide).find('.caption').text();
          jQuery('.swiper-footer .caption').text(activeCaption);
        });


    </script>
		<?php wp_footer(); ?>
	</body>

</html>
