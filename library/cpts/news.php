<?php
/**
 * @package default
 * @author mattias@konst-teknik.se
 */
class SKFCptNews
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
		register_post_type( 'news', array(
			'label'  => "Nyheter",
			'labels' => array(
				'name'               => "Nyheter",
				'singular_name'      => "Nyhet",
				'add_new'            => "Ny nyhet",
				'all_items'          => "Alla nyheter",
				'add_new_item'       => "Lägg till nyhet",
				'edit_item'          => "Redigigera nyhet",
				'new_item'           => "Ny nyhet",
				'view_item'          => "Visa nyhet",
				'search_items'       => "Sök nyhet",
				'not_found'          => "Ingen nyhet hittad",
				'not_found_in_trash' => "Ingen nyhet i papperskorgen",
				'menu_name'          => "Nyheter"
			),
			'description' => "Represents a News Post",
			'public' => true,
			'exclude_from_search' => false,
			'menu_icon' => 'dashicons-megaphone',
			'menu_position' => 8,
			'has_archive' => true,
			'publicly_queryable' => true,
			'rewrite' => array( 'slug' => 'nyheter', 'with_front' => false, 'pages' => true, 'feeds' => true, ),
			'supports' => array(
				'title',
				'revisions',
				'thumbnail',
			)
		));
	}
}


new SKFCptNews();
