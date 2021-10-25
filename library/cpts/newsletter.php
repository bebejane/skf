<?php
/**
* @package default
* @author Bébé Jane
*/
require_once( __DIR__ .'/../sendgrid/sendgrid-php.php' );
use SendGrid\Mail\Mail;

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
				'not_found'          => "Ingen utskick hittad",
				'not_found_in_trash' => "Ingen utskick i papperskorgen",
				'menu_name'          => "Utskick"
			),
			'description' => "Represents a Utskick Post",
			'public' => true,
			'exclude_from_search' => false,
			'menu_icon' => 'dashicons-email',
			'menu_position' => 9,
			'has_archive' => true,
			'rewrite' =>array( 'slug' => 'utskick', 'with_front' => false ),
			'publicly_queryable' => true,
			'supports' => array(
				'title',
				'revisions',
				'thumbnail',
				'author'
			),
			)
		);
	}
	/**
	* Registrera SendGrid Message ID as meta field
	*/
	public function register_meta()
	{
		register_post_meta('newsletter', 'sg_message_id', array( 
			'type' => 'string',
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
		$reply_to = get_field('newsletter_reply_to','option');
		$sg_message_id = null;
		
		if(!SENDGRID_API_KEY || !SENDGRID_EMAIL){
			$this->handle_error($post_id, 'Inställningar i wordpress saknas för SendGrid!');
			return false;
		}
			
		if(!$reply_to){
			$this->handle_error($post_id, 'Inställningar for utskick saknas!');
			return false;
		}

		$bcc = array();
		for ($i = 0; $i < count($recipients); $i++){
			if($recipients[$i] != SENDGRID_EMAIL)
				$bcc[$recipients[$i]] = '';
		}

		$text = file_get_contents(get_permalink($post_id) . '?text_content=true');
		$html = file_get_contents(get_permalink($post_id) . '?html_content=true');

		if(!$html){
			$this->handle_error($post_id, 'Utskicket är tomt!');
			return false;
		}

		$from_name = 'Sveriges Konstforeningar';
		
		if(function_exists('get_blog_details')){
    	$blog = get_blog_details( array( 'blog_id' => $blog_id ) );
			$from_name = $blog->blogname;
		}

		$email = new Mail();
		$email->setFrom(SENDGRID_EMAIL, $from_name);
		$email->addTos([SENDGRID_EMAIL => $from_name]);
		$email->setReplyTo($reply_to);
		$email->addBccs($bcc);
		$email->setSubject($subject);
		$email->addContent("text/plain", $text);
		$email->addContent("text/html", $html);
		$sendgrid = new \SendGrid(SENDGRID_API_KEY);
		$error_message = null;
		
		try {
			$response = $sendgrid->send($email);
			$body = json_decode($response->body());
			$headers = $response->headers();

			foreach ($headers as $header){
				if(strpos($header, 'X-Message-Id', 0) !== false){
					$sg_message_id = str_replace('X-Message-Id: ', '', $header);
				}
			}

			if($body and $body->errors and count($body->errors) and $body->errors[0]->message){
				$error_message = $body->errors[0]->message;
			}
		} catch (Exception $e) {
			$error_message = $e->getMessage();
		}
		
		if($error_message != null){
			$this->handle_error($post_id, $error_message);
			return false;
		}
		if($sg_message_id){
			update_post_meta($post_id, 'sg_message_id', $sg_message_id);
		}

		DEBUG('Sent newssletter. From: ' . $from_name . ' ' . SENDGRID_EMAIL);
		DEBUG('Recipients');
		DEBUG($recipients);
		DEBUG('SendGridID: ' . $sg_message_id);
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
		if(is_array($email) || is_numeric($email) || is_bool($email) || is_float($email) || is_file($email) || is_dir($email) || is_int($email))
			return false;
		else {
			$email=trim(strtolower($email));
			$pattern = '/^(?!(?:(?:\\x22?\\x5C[\\x00-\\x7E]\\x22?)|(?:\\x22?[^\\x5C\\x22]\\x22?)){255,})(?!(?:(?:\\x22?\\x5C[\\x00-\\x7E]\\x22?)|(?:\\x22?[^\\x5C\\x22]\\x22?)){65,}@)(?:(?:[\\x21\\x23-\\x27\\x2A\\x2B\\x2D\\x2F-\\x39\\x3D\\x3F\\x5E-\\x7E]+)|(?:\\x22(?:[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F\\x21\\x23-\\x5B\\x5D-\\x7F]|(?:\\x5C[\\x00-\\x7F]))*\\x22))(?:\\.(?:(?:[\\x21\\x23-\\x27\\x2A\\x2B\\x2D\\x2F-\\x39\\x3D\\x3F\\x5E-\\x7E]+)|(?:\\x22(?:[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F\\x21\\x23-\\x5B\\x5D-\\x7F]|(?:\\x5C[\\x00-\\x7F]))*\\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-+[a-z0-9]+)*\\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-+[a-z0-9]+)*)|(?:\\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\\]))$/iD';
			return (preg_match($pattern, $email) === 1) ? $email : false;
		}
	}
	public function handle_error($post_id, $message)
	{
		$this->send_notice('error', $message);
		wp_update_post(array('ID'=>$post_id, 'post_status' => 'draft'));
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

