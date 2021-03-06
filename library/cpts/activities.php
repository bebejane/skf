<?php
/**
 * @package default
 * @author mattias@konst-teknik.se
 */
class SKFCptActivities
{
	const WAITING_LIST_LABEL = 'Väntelista';

	public function __construct()
	{
		add_action( 'init', array( $this, 'register' ) );
		add_action( 'init', array( $this, 'register_taxonomy' ) );
		add_action( 'init', array( $this, 'register_wpforms_hooks' ) );
	}

	/**
	 * Registrerar CPTn
	 */
	public function register()
	{
		register_post_type( 'activities', array(
			'label'  => "Aktiteter",
			'labels' => array(
				'name'               => "Aktiviteter",
				'singular_name'      => "Aktivitet",
				'add_new'            => "Ny aktivitet",
				'all_items'          => "Alla aktiviteter",
				'add_new_item'       => "Lägg till aktivitet",
				'edit_item'          => "Redigigera aktivitet",
				'new_item'           => "Ny aktivitet",
				'view_item'          => "Visa aktivitet",
				'search_items'       => "Sök aktivitet",
				'not_found'          => "Ingen aktivitet hittad",
				'not_found_in_trash' => "Ingen aktivitet i papperskorgen",
				'menu_name'          => "Aktiviteter"
			),
			'description' => "Represents a Activity Post",
			'public' => true,
			'exclude_from_search' => false,
			'menu_icon' => 'dashicons-format-chat',
			'menu_position' => 5,
			'has_archive' => true,
			'rewrite' =>array( 'slug' => 'aktiviteter', 'with_front' => false ),
			'publicly_queryable' => true,
			'supports' => array(
				'title',
				'revisions',
				'thumbnail',
			),
		)
		);
	}

	public function register_taxonomy()
	{
		register_taxonomy( 'activities-category', 'activities', array(
			'label' => 'Kategorier',
			'hierarchical' => true
		));
		register_taxonomy_for_object_type( 'activities-category', 'activities' );
	}

	/**
	 * Registrerar Actions/Filters for WPform
	 */
	public function register_wpforms_hooks(){
		add_action( 'wpforms_process', array( $this, 'pre_save'), 10, 3);
		add_filter( 'wpforms_process_filter', array( $this, 'pre_filter'), 10, 3);
		add_filter( 'wpforms_frontend_confirmation_message', array( $this, 'pre_confirm'), 10, 4);
	}

	/**
	 * Kollar om aktiviteten ar full, markerar att anmalan ar pa vantelistan
	 */
	public function pre_filter($fields, $entry, $form_data)
	{	
		
		if (!SKFCptActivities::is_activity($form_data)){
			return $fields;
		}
		// Add post_id to form
		$post_field_id = SKFCptActivities::get_form_field_id($form_data, 'post_id');
		$fields[$post_field_id]['value_raw'] = $entry['post_id'];	
		$fields[$post_field_id]['value'] = $entry['post_id'];	

		if (SKFCptActivities::is_max_no_people($form_data, $entry['post_id'])){
			$field_id = SKFCptActivities::get_form_field_id($form_data, self::WAITING_LIST_LABEL);
			$fields[$field_id]['value_raw'] = 'Ja';	
			$fields[$field_id]['value'] = 'Ja';	
			debug('activities: put on waitinglist' );
			debug($entry);
		}
		
		return $fields;
	}

	public function pre_save($fields, $entry, $form_data)
	{
		debug('activities: pre_save');
		$email = SKFCptActivities::get_form_field_value($fields, 'Epost');
		$email_field_id = SKFCptActivities::get_form_field_id($form_data, 'Epost');
		
		if (!SKFCptActivities::is_activity($form_data)){
			debug('activities: pre_save - is not activity');
			return true;
		}
		/* Disable check
		if (SKFCptActivities::is_registered($form_data, $entry['post_id'], $email)){
			debug('activities: already registered ' . $email);
			wpforms()->process->errors[$form_data['id']][$email_field_id] = 'E-post adressen är redan registerad för den här aktiviteten';
			return false;
		}
		*/
		if (SKFCptActivities::is_max_no_people($form_data, $entry['post_id'])){			
			debug('activities: max_no_people');
		}
		return true;  
	}
	/**
	 * Modifierar success meddelande om aktiviteten ar full
	 */
	public function pre_confirm($message, $form_data, $fields, $entry_id )
	{

		if (!SKFCptActivities::is_activity($form_data)){
			return $message;
		}
		
		$field_id = SKFCptActivities::get_form_field_id($form_data, self::WAITING_LIST_LABEL);
		if($fields[$field_id]['value'] == 'Ja'){
			$message = $message . ' Tyvärr är aktiviteten redan fullbesatt men du har registrerats på väntelistan.';
			debug('activities: activity is full');
			debug($field_id);
			debug($form_data);
		}

		return $message;
	}
	/**
	 * @return Check if activity is full
	 */
	public static function is_max_no_people($form_data, $post_id)
	{
		$entries = wpforms()->entry->get_entries(array('form_id' => $form_data['id'], 'number' => 10000));	
		$max_no_people = get_post_meta( $post_id, 'max_no_people', true);
		$post_field_id = SKFCptActivities::get_form_field_id($form_data, 'post_id');
		$count = 0;

		foreach ($entries as $entry ){
			$fields = json_decode($entry->fields, true);	
			if($fields and isset($fields[$post_field_id]) and $fields[$post_field_id]['value'] == $post_id){
				$count += 1;
			}
		}

		if(!empty($max_no_people) and ($count+1) > $max_no_people){
			return true;	
		}
		return false;  		
	}
	/**
	 * @return Check if user already registered
	 */
	public static function is_registered($form_data, $post_id, $email)
	{
		$entries = wpforms()->entry->get_entries(array('form_id' => $form_data['id'], 'number' => 10000));
		$post_field_id = SKFCptActivities::get_form_field_id($form_data, 'post_id');
		$email_field_id = SKFCptActivities::get_form_field_id($form_data, 'Epost');
		$count = 0;
		
		foreach ($entries as $entry ){
			$fields = json_decode($entry->fields, true);
			if($fields and isset($fields[$post_field_id]) and $fields[$post_field_id]['value'] == $post_id and $fields[$email_field_id]['value'] == $email){
				return true;
			}
		}
		return false;  		
	}
	/**
	 * @return Om formularat ar aktivitets formular
	 */
	public static function is_activity($form_data)
	{
		$participate_id = get_field('participate_id','option');
		if(!empty($participate_id) and $participate_id == $form_data['id']){
			return true;
		}
		return false;
	}
	/**
	 * @return form_id for label
	 */
	public static function get_form_field_id($form_data, $label)
	{
		return array_key_first(array_filter($form_data['fields'], function($obj) use ($label){
			if($obj['label'] == $label){ 
				return true; 
			}
			return false;
		}));
	}

	/**
	 * @return value from entries
	 */
	public static function get_form_field_value($fields, $name)
	{
		foreach ($fields as $field ){
			if($field['name'] == $name){
				return $field['value'];
			}
		}
		return null;
	}
}

new SKFCptActivities();
