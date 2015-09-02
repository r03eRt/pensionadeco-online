<?php
add_action('do_ajax','ajax_testimonial_process');

function ajax_testimonial_process(){
	global $wpdb;
	$response = '';	
	$name = $_POST['name'];
	$email = $_POST['email'];	
	$message = $_POST['message'];	
	

	
		$post_id = wp_insert_post( array(						
					'post_title'	=> $name,
					'post_content'	=> $message,
					'post_type' => 'testimonial',
					'post_status' => 'pending'
				) );
		add_post_meta($post_id, 'tgt_email_testimonial', $email);
		
	
	header('HTTP/1.1 200 OK');
	header('Content-Type: application/json'); 
 	
	$response = json_encode(array('success' => true,'message' => 'success' ));  
	
	echo $response;   
	exit;
}
?>