<ul class="menu">
	<?php
		if( wp_count_posts('exhibitions')->publish > 0 ) {
			echo "<li><a href='/senaste-utstallningar'>Utställningar</a></li>";
		}
		if( wp_count_posts('activities')->publish > 0 ) {
			echo "<li><a href='/senaste-aktiviteter'>Aktiviteter</a></li>";
		}
		if( wp_count_posts('news')->publish > 0 ) {
			echo "<li><a href='/nyheter'>Nyheter</a></li>";
		}
		if( wp_count_posts('lotteries')->publish > 0 ) {
			echo "<li><a href='/senaste-utlottningar'>Utlottningar</a></li>";
		}
		if( wp_count_posts('gallery')->publish > 0 ) {
			echo "<li><a href='/galleri'>" . get_field('gallery_name','options') . "</a></li>";
		}
	?>
	<li><a href="/medlem">Medlem</a></li>
	<li><a href="/om">Om</a></li>
	<li><a href="/kontakt">Kontakt</a></li>
</ul>
