<?php
/**
* @package default
* @author Bébé Jane
*/
require_once(__DIR__ . '/../postmark/autoload.php');
use Postmark\PostmarkClient;

class SKFCptNewsletter
{
	public function __construct()
	{
		add_action( 'init', array( $this, 'register' ) );
		add_action( 'init', array( $this, 'register_meta' ) );
		add_action( 'init', array( $this, 'register_taxonomy' ) );
		add_action( 'init', array( $this, 'register_hooks' ) );
		add_action( 'admin_notices', array( $this, 'handle_noticies' ));
	}
	/**
	* Registrerar CPTn
	*/
	public function register()
	{
		register_post_type( 'newsletter', array(
			'label'  => "Utskick",
			'labels' => array(
				'name'               => "Utskick",
				'singular_name'      => "Utskick",
				'add_new'            => "Nytt utskick",
				'all_items'          => "Alla utskick",
				'add_new_item'       => "Lägg till utskick",
				'edit_item'          => "Redigigera utskick",
				'new_item'           => "Nytt utskick",
				'view_item'          => "Visa utskick",
				'search_items'       => "Sök utskick",
				'not_found'          => "Inget utskick hittades",
				'not_found_in_trash' => "Inget utskick i papperskorgen",
				'menu_name'          => "Utskick"
			),
			'description' => "Represents a Utskick Post",
			'public' => true,
			'show_in_rest' => false,
			'exclude_from_search' => true,
			'menu_icon' => 'dashicons-email',
			'menu_position' => 9,
			'has_archive' => true,
			'rewrite' =>array( 'slug' => 'utskick', 'with_front' => false ),
			'publicly_queryable' => true,
			'supports' => array(
				'title',
				'revisions',
				'thumbnail',
			),
			)
		);
	}
	/**
	* Registrera SendGrid Message ID as meta field
	*/
	public function register_meta()
	{
		register_post_meta('newsletter', 'pm_message_ids', array( 
			'type'  => 'array',
			'single' => true
		));
	}

	public function register_taxonomy()
	{
		register_taxonomy( 'newsletter-category', 'newsletter', array(
			'label' => 'Utskick',
			'hierarchical' => true
		));
		register_taxonomy_for_object_type( 'newsletter-category', 'newsletter' );
	}

	public function register_hooks()
	{
		add_action( 'wp_after_insert_post', array( $this, 'after_insert' ), 10, 4);
		add_filter( 'acf/update_value/name=recipients', array( $this, 'parse_recipients'), 10, 4);		
		add_filter( 'acf/validate_value/name=recipients', array( $this, 'validate_recipients' ), 10, 4);
	}
	
	public function validate_recipients( $valid, $value, $field, $input_name )
	{	

		if( $valid !== true ) {
			return $valid;
		}
		$recipients = SKFCptNewsletter::extract_email_addresses($value);

		if(count($recipients) == 0){
			return __( 'Hittade inga email adresser i texten');
		}
		// Validate email addresses
		foreach( $recipients as $email ){
			if(!SKFCptNewsletter::is_email_valid($email)){
				return __( 'E-mail adress ogiltig: ' . $email);
			}
		}
		return $valid;
	}
	
	public function parse_recipients($value, $post_id, $field, $original)
	{		
		$recipients = SKFCptNewsletter::extract_email_addresses($value);
		
		// Set reciepents value to email CSV
		if(count($recipients) > 0){
			return implode(',', $recipients);
		}
		else{
			return $value;
		}
	}

	/**
	 * After post is published, send the email.
	 */
	public function after_insert($post_id, $post, $update, $post_before )
	{
		if ('newsletter' !== $post->post_type or 'publish' !== $post->post_status){
			return;
		}		
		$subject = $post->post_title;
		$recipients_field = get_field('recipients', $post->ID);
		$recipients = SKFCptNewsletter::extract_email_addresses($recipients_field);
		
		if ( $subject == '' or count($recipients) == 0) {
			$this->handle_error($post_id, 'Alla fält ar ej ifyllda!');
			return;
		}
		$success = $this->send_email($recipients, $subject, $post);
	}

	public function send_email($recipients, $subject, $post)
	{	
		$post_id = $post->ID;
		$from_name = 'Sveriges Konstforeningar';
		$reply_to = get_field('newsletter_reply_to','option');
		$pm_message_id = null;
		
		if(!defined('POSTMARK_API_KEY') || !defined('POSTMARK_EMAIL')){
			return $this->handle_error($post_id, 'Inställningar i wordpress saknas för SendGrid!');
		}
		if(!$reply_to){
			return $this->handle_error($post_id, 'Inställningar for utskick saknas!');
		}
		if(in_array($reply_to, $recipients)){
			return $this->handle_error($post_id, 'Det gâr ej att skicka till samma address du använder som Reply-to adress. Ta bort ' . $reply_to .  ' frân listan för att skicka meddelandet.');
		}
		if(count($recipients) > 1000){
			return $this->handle_error($post_id, 'Du kan ej skicka till mer än 1000 personer!');
		}

		$bcc = array();
		for ($i = 0; $i < count($recipients); $i++){
			if($recipients[$i] != POSTMARK_EMAIL){
				array_push($bcc, $recipients[$i]);
			}
		}
		
		// Get HTML content of post from url

		$text = file_get_contents(get_permalink($post_id) . '?content_type=text');
		$html = file_get_contents(get_permalink($post_id) . '?content_type=html');

		if(!$html){
			$this->handle_error($post_id, 'Utskicket är tomt!');
			return false;
		}

		if(function_exists('get_blog_details')){
    	$blog = get_blog_details();
			$from_name = $blog->blogname;
		}
		$client = new PostmarkClient(POSTMARK_API_KEY);
		$error_message = null;

		$chunk_recipients = array_chunk($bcc, 50);
		$pm_message_ids = array();

		try {

			for ($i = 0; $i < count($chunk_recipients); $i++) { 
				$response = $client->sendEmail(POSTMARK_EMAIL, $reply_to, $subject, $html, $text, null, true, $reply_to, null, implode(',', $chunk_recipients[$i]));
				array_push($pm_message_ids, $response->MessageID);
			}

		} catch (Exception $e) {
			$error_message = $e->getMessage();
		}
		if($error_message != null){
			$this->handle_error($post_id, $error_message);
			return false;
		}
		if(count($pm_message_ids) > 0){
			update_post_meta($post_id, 'pm_message_ids', $pm_message_ids);
		}

		DEBUG('Sent newssletter. From: ' . $from_name . ' ' . POSTMARK_EMAIL);
		DEBUG('PostMarkIDs');
		DEBUG($pm_message_ids);
		
		$this->send_notice('success', 'Utskick skickades till ' . count($bcc) . ' ' . (count($bcc) == 1 ? 'medlem' : 'medlemmar'));
		return true;
	}
	public static function extract_email_addresses($text)
	{
		$pattern	=	"/(?:[a-z0-9!#$%&'*+=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+=?^_`{|}~-]+)*|\"(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21\x23-\x5b\x5d-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])*\")@(?:(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?|\[(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?|[a-z0-9-]*[a-z0-9]:(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21-\x5a\x53-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])+)\])/";
		preg_match_all($pattern, $text, $matches);
		return $matches[0];
	}
	public static function is_email_valid($email)
	{
		// Check for single letter domain ending...
		if( strlen($email) >= 2 and $email[strlen($email)-2] == '.') 
			return false;
		return filter_var($email, FILTER_VALIDATE_EMAIL);
	}

	public function handle_error($post_id, $message)
	{
		$this->send_notice('error', $message);
		wp_update_post(array('ID'=>$post_id, 'post_status' => 'draft'));
		return false;
	}
	
	public function send_notice($type, $message)
	{
		set_transient(get_current_user_id().'newsletter-'.$type, $message);		
	}

	public function handle_noticies()
	{
		$error = get_transient( get_current_user_id().'newsletter-error' );
		$success = get_transient( get_current_user_id().'newsletter-success' );
		
		if(!$error and !$success) 
			return;
		
		$type = $error ? 'error' : 'success';
		$class = 'notice notice-' . $type . ' is-dismissible';
		delete_transient( get_current_user_id().'newsletter-' . $type );
		printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $error ? 'Error: ' . $error : $success ) ); 
	}
}
new SKFCptNewsletter();

