<div class="breadcrumb">
	<ul>
		<li><a href="/">Hem</a></li>
			<?php
			if($post_type == "page") {
				if(is_page(152)){
					echo "<li>GDPR & Cookies</li>";
				}
				else {
			 			echo "<li>" . get_the_title() . "</li>";
						}
			}

			else {
				if (is_search()) {
						echo "<li>Sök</li>";
				}

				if (is_archive()) {
					if(is_post_type_archive( 'gallery')) {
					echo "<li>" .  get_field('gallery_name','options') . "</li>";
					}
					else {
						echo "<li>" .  $post_obj->labels->name . "</li>";
					}
				}

				if (is_singular('news')) {
					echo "<li><a href='/" . $post_obj->rewrite['slug'] . "'>" . $post_obj->labels->name . "</a>";
				}

				if (is_singular(array( 'exhibitions', 'lotteries', 'activities' ))) {
					echo "<li><a href='/senaste-" . $post_obj->rewrite['slug'] . "'>" . $post_obj->labels->name . "</a>";
				}

				if (is_singular(array( 'gallery'))) {
					echo "<li><a href='/" . $post_obj->rewrite['slug'] . "'>" . get_field('gallery_name','options') . "</a>";
				}
			}
			?>
		</li>
	</ul>
</div>
