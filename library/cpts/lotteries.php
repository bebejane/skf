<?php
/**
 * @package default
 * @author mattias@konst-teknik.se
 */
class SKFCptLotteries
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
		register_post_type( 'lotteries', array(
			'label'  => "Utlottningar",
			'labels' => array(
				'name'               => "Utlottningar",
				'singular_name'      => "Utlottning",
				'add_new'            => "Nytt inlägg",
				'all_items'          => "Alla inlägg",
				'add_new_item'       => "Lägg till inlägg",
				'edit_item'          => "Redigigera inlägg",
				'new_item'           => "Nytt inlägg",
				'view_item'          => "Visa inlägg",
				'search_items'       => "Sök inlägg",
				'not_found'          => "Inget inlägg hittat",
				'not_found_in_trash' => "Inget inlägg i papperskorgen",
				'menu_name'          => "Utlottningar"
			),
			'description' => "Represents a Lottery Post",
			'public' => true,
			'exclude_from_search' => false,
			'menu_icon' => 'dashicons-awards',
			'menu_position' => 7,
			'has_archive' => true,
			'publicly_queryable' => true,
			'rewrite' =>array( 'slug' => 'utlottningar', 'with_front' => false ),
			'supports' => array(
				'title',
				'revisions',
				'thumbnail',
			)
		));
	}
}


new SKFCptLotteries();
