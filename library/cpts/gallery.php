<?php
/**
 * @package default
 * @author mattias@konst-teknik.se
 */
class SKFCptGallery
{
	public function __construct()
	{
		add_action( 'init', array( $this, 'register' ) );
	}

	/**
	 * Registrerar CPTn
	 */
	public function register()
	{
		register_post_type( 'gallery', array(
			'label'  => "Galleri",
			'labels' => array(
				'name'               => "Galleri",
				'singular_name'      => "Konstnär",
				'add_new'            => "Ny konstnär",
				'all_items'          => "Alla konstnärer",
				'add_new_item'       => "Lägg till konstnär",
				'edit_item'          => "Redigigera konstnär",
				'new_item'           => "Ny konstnär",
				'view_item'          => "Visa konstnär",
				'search_items'       => "Sök konstnär",
				'not_found'          => "Inga konstnärer hittade",
				'not_found_in_trash' => "Inget konstnärer i papperskorgen",
				'menu_name'          => "Galleri"
			),
			'description' => "Represents a Gallery Post",
			'public' => true,
			'exclude_from_search' => false,
			'menu_icon' => 'dashicons-format-gallery',
			'menu_position' => 7,
			'has_archive' => true,
			'publicly_queryable' => true,
			'rewrite' =>array( 'slug' => 'galleri', 'with_front' => false ),
			'supports' => array(
				'title',
				'revisions',
				'thumbnail',
			)
		));
	}
}


new SKFCptGallery();
