<?php
/**
 * Handles bounced emails from SendGrid. Relays bounced email addresses to the Reply-to address
 * specifiled in settings for SKF theme. 
 * API Endpoint https://konstforeningar.se/sendgrid/bounce (POST)
 * @author Bébé Jane
*/

require_once( __DIR__ .'/sendgrid-php.php' );
use SendGrid\Mail\Mail;

add_action( 'rest_api_init', function () {
  register_rest_route( 'sendgrid', '/bounce', array(
    'methods' => 'POST',
    'callback' => 'bounced_email_webhook'
  ));
});

function bounced_email_webhook(){

	DEBUG('bounce web hook');
	$entityBody = file_get_contents('php://input');
	$payload = parse_payload_data($entityBody);
	$data = get_by_sendgrid_id($payload['sg_message_id']);
	
	if($data and $payload){
		send_bounced_email($data, $payload['error_email']);
		return wp_send_json(array(
			'success' => true,
			'reply-to' => $data['reply_to'],
			'sg_message_id' => $payload['sg_message_id'],
			'error_email' => $payload['error_email']
		));
		DEBUG('success sending bounce to ' . $data['reply_to']);
	} else {
		DEBUG('something is wrong');
		DEBUG($data);
		DEBUG($payload);
		wp_send_json( array('success' => false), 200 );
	}	
}

function get_by_sendgrid_id($sg_message_id){
	$data = null;

	if(!function_exists('get_sites')){
		return get_post_and_reply_to($sg_message_id);
	}
	$subsites = get_sites(array(
		'order_by' => 'last_updated',
		'order'  => 'DESC',
		'number' => 50,
	));
	foreach( $subsites as $subsite ) {
		$subsite_id = get_object_vars($subsite)["blog_id"];
		switch_to_blog($subsite_id);
		$data = get_post_and_reply_to($sg_message_id);
		if($data != null){
			return $data;
		}
	}
	return $data;
}

function get_post_and_reply_to($sg_message_id){

	$from_name = 'Sveriges Konstföreningar';

	$posts = get_posts(array(
		'post_type' => 'newsletter',
		'meta_query' => array(
			array(
				'key' => 'sg_message_id',
				'value' => $sg_message_id,
			)
		)
	));
	if( $posts ) {
		if(function_exists('get_blog_details')){
    	$blog = get_blog_details();
			$from_name = $blog->blogname;
		}
		$reply_to = get_field('newsletter_reply_to','option');
		return  array('post' => $posts[0], 'reply_to' => $reply_to, 'from_name' => $from_name);
	}
	return null;
}

function parse_payload_data($data) {
	$json = json_decode($data, true);
	DEBUG($json);
	if(!$json){
		return null;
	}
	$payload = $json[0];
	$sg_message_id = explode('.', $payload['sg_message_id'])[0];
	return array(
		'sg_message_id' => $sg_message_id,
		'reason' => $payload['reason'],
		'error_email' => $payload['email']
	);
}

function send_bounced_email($data, $error_email){

	$reply_to = $data['reply_to'];
	$from_name = $data['from_name'];
	$post = $data['post'];

	$text = 'Det uppstod ett fel med erat senaste utskick "' . $post->post_title .'".\n\nDet gick inte att leverera meddelandet till följande e-mail adress:  ' . $error_email .'';
	$html = '<p>Det uppstod ett fel med erat senaste utskick "<b>' . $post->post_title .'</b></p>".<p>Det gick inte att leverera meddelandet till följande e-mail adress:  <b>' . $error_email .'</b></p>';

	$email = new Mail();
	$email->setFrom(SENDGRID_EMAIL, $from_name);
	$email->addTos([$reply_to => '']);
	$email->setReplyTo($reply_to);
	$email->setSubject('Utskick: Ogliltig email adress');
	$email->addContent("text/plain", $text);
	$email->addContent("text/html", $html);
	$sendgrid = new \SendGrid(SENDGRID_API_KEY);
	$error_message = null;
	
	try {
		$response = $sendgrid->send($email);
		$body = json_decode($response->body());
		if($body and $body->errors and count($body->errors) and $body->errors[0]->message){
			$error_message = $body->errors[0]->message;
		}
	} catch (Exception $e) {
		$error_message = $e->getMessage();
	}

	if($error_message){
		DEBUG('ERROR sending email');
		DEBUG($error_message);
		return false;
	} else {
		DEBUG('Bounce email sent to ' . $reply_to . ', error email=' . $error_email);
		return true;
	}
}

?>