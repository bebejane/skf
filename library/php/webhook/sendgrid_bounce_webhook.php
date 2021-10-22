<?php
require_once( __DIR__ .'/../sendgrid/sendgrid-php.php' );
use SendGrid\Mail\Mail;

function bounced_email_webhook(){
	$hook_path = '/sendgridwebhook';
	if( $_SERVER['REQUEST_URI'] != $hook_path ){
		return;
	}
	$entityBody = file_get_contents('php://input');
	$form = []; 
	parse_str($entityBody, $form);
	$sendgrid_message_id = $form['sendgrid_message_id'];
	$data = get_by_sendgrid_id($sendgrid_message_id);
	$error_email = 'fuckedup@email.com';
	$response = null;
	if($data){
		send_bounced_email($data['reply_to'], $data['post'], $error_email);
		$response = array(
			'success' => true,
			'reply-to' => $data['reply_to'],
			'sendgrid_message_id' => $sendgrid_message_id
		);
	} else {
		$response = array('success' => false);
	}	
	wp_send_json( $response, 200 );
}
function get_by_sendgrid_id($sendgrid_message_id){
	$data = null;

	if(!function_exists('get_sites')){
		return get_post_and_reply_to($sendgrid_message_id);
	}
	$subsites = get_sites(array(
		'order_by' => 'last_updated',
		'order'  => 'DESC',
		'number' => 50,
	));
	foreach( $subsites as $subsite ) {
		$subsite_id = get_object_vars($subsite)["blog_id"];
		switch_to_blog($subsite_id);
		$data = get_post_and_reply_to($sendgrid_message_id);
		if($data != null){
			return $data;
		}
	}
	return $reply_to;
}

function get_post_and_reply_to($sendgrid_message_id){

	$posts = get_posts(array(
		'post_type' => 'newsletter',
		'meta_query' => array(
			array(
				'key' => 'sendgrid_message_id',
				'value' => $sendgrid_message_id,
			)
		)
	));
	if( $posts ) {
		$reply_to = get_field('newsletter_reply_to','option');
		return  array('post' => $posts[0], 'reply_to' => $reply_to);
	}
	return null;
}

function send_bounced_email($to_email, $post, $error_email){

		$from_name = 'Bebe Jane';
		$from_email = 'bebejanedev@gmail.com';
		$text = 'Email adressen ' . $error_email .' i erat senaste utskick var ogiltig!';
		$email = new Mail();
		$email->setFrom($from_email, $from_name);
		$email->addTos([$to_email => '']);
		$email->setSubject('Ogliltig email adress.');
		$email->addContent("text/plain", $text);
		//$email->addContent("text/html", $html);
		$sendgrid = new \SendGrid(SENDGRID_API_KEY);
		$error_message = null;
		
		try {
			$response = $sendgrid->send($email);
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
			DEBUG('Bounce email sent to ' . $to_email);
			return true;
		}

}
add_action( 'init', 'bounced_email_webhook' );
?>