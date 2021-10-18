<?php
$post_thumbnail_id = get_post_thumbnail_id( $post_id );
$imgmeta = wp_get_attachment_metadata( $post_thumbnail_id );

if ($imgmeta['width'] > $imgmeta['height']) {
  $imgclass = "landscape";
} else {
  $imgclass =  "portait";
}
?>

<figure class="content-image  <?php echo $imgclass; ?>">
  <?php the_post_thumbnail('full'); ?>
  <p class="mid caption"><?php the_post_thumbnail_caption() ?></p>
</figure>
