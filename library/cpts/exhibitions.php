<?php
/**
 *
 * @package default
 * @author mattias@konst-teknik.se
 */
class SKFCptExhibitions
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
		register_post_type( 'exhibitions', array(
			'label'  => "Utställningar",
			'labels' => array(
				'name'               => "Utställningar",
				'singular_name'      => "Utställning",
				'add_new'            => "Ny utställning",
				'all_items'          => "Alla utställningar",
				'add_new_item'       => "Lägg till ny utställning",
				'edit_item'          => "Redigera utställning",
				'new_item'           => "Ny utställning",
				'view_item'          => "Visa utställning",
				'search_items'       => "Sök utställning",
				'not_found'          => "Ingen utställning hittad",
				'not_found_in_trash' => "Ingen utställning i papperskorgen",
				'menu_name'          => "Utställningar"
			),
			'description' => "Represents a Exhibition Post",
			'public' => true,
			'exclude_from_search' => false,
			'menu_icon' => 'dashicons-format-image',
			'menu_position' => 6,
			'has_archive' => true,
			'publicly_queryable' => true,
			'rewrite' =>array( 'slug' => 'utstallningar', 'with_front' => false ),
			'hierarchical' => true,
			'supports' => array(
				'title',
				'revisions',
				'thumbnail',
			)
		));
	}
}


new SKFCptExhibitions();
