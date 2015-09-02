<?php
// permalink
add_filter( 'rewrite_rules_array',	'insert_new_rules' );
add_filter( 'query_vars',				'new_query_var' );
add_action( 'wp_loaded',				'flush_rules' );

// flush_rules() if our rules are not yet included
function flush_rules(){
	$rules = get_option( 'rewrite_rules' );
	if ( ! isset( $rules['(roomtypes)'] ) ) {
		global $wp_rewrite;
	   	$wp_rewrite->flush_rules();
	}
}

// Adding a new rule
function insert_new_rules( $rules )
{
	$newrules = array();
	$newrules['(check)/(\s*)$'] 			= 'index.php?pagename=$matches[1]&code=$matches[2]';
	$newrules['(roomtypes)'] 				= 'index.php?pagename=$matches[1]';
	$newrules['(print)'] 					= 'index.php?pagename=$matches[1]';
	$newrules['(booking_payment)'] 					= 'index.php?action=$matches[1]';
	$newrules['(booking_option)$'] 					= 'index.php?action=$matches[1]';
	$newrules['(payment_success)'] 					= 'index.php?action=$matches[1]';
	$newrules['(payment_false)'] 					= 'index.php?action=$matches[1]';
	$newrules['(payment_submit)'] 					= 'index.php?action=$matches[1]';
	return $newrules + $rules;
}

// Adding the id var so that WP recognizes it
function new_query_var( $vars )
{
	array_push($vars, 'id');
	array_push($vars, 'code');
	array_push($vars, 'action');

   return $vars;
}

//for redirect, return link and setting permalink wordpress -->
add_action ('template_redirect', 'hook_template_redirect');
function hook_template_redirect(){
	global $wp_rewrite, $wp_query;
	$action = '';
	$pages = array('check', 'roomtypes', 'print', 'booking_payment', 'booking_option', 'payment_success', 'payment_false', 'payment_submit');
	//echo '<pre>';
	//print_r($wp_query);
	//echo '</pre>';
	
	if ( isset( $_GET['action'] ) && $_GET['action'] == 'ajax' )
	{
		$action = $_GET[ 'action' ];
	}
	else
	{
		if ( $wp_rewrite->using_permalinks() )
		{
			$pagename = get_query_var('action');
			$action = empty( $pagename ) ? $_GET[ 'action' ] : $pagename ;
		}
		else {
			$action = $_GET[ 'action' ];
		}
	}
	if ( !empty($action ) && ( in_array( $action, $pages ) ) )
	{
		switch ($action)
		{
			case 'check':
				require_once TEMPLATEPATH . '/check_result.php';
				exit;
			case 'roomtypes':
				require_once TEMPLATEPATH . '/roomtypes.php';
				exit;
			case 'booking_payment':
				require_once TEMPLATEPATH . '/booking.php';
				exit;
			case 'booking_option':
				require_once TEMPLATEPATH . '/booking-option.php';
				exit;
			case 'payment_success':
				require_once TEMPLATEPATH . '/paypal_success.php';
				exit;
			case 'payment_false':
				require_once TEMPLATEPATH . '/paypal_false.php';
				exit;
			case 'print':
				require_once TEMPLATEPATH . '/print.php';
				exit;
			case 'payment_submit':
				require_once TEMPLATEPATH . '/pages/payment_submit.php';
				exit;
			case 'ajax':
				if ( isset ($_REQUEST['action_name'])  &&
					 file_exists ( TEMPLATEPATH . DIRECTORY_SEPARATOR . 'ajax' . DIRECTORY_SEPARATOR . $_REQUEST['action_name'] . '.php' ) )
				{
					require_once TEMPLATEPATH . DIRECTORY_SEPARATOR . 'ajax' . DIRECTORY_SEPARATOR . $_REQUEST['action_name'] . '.php';
					do_action('do_ajax', $_REQUEST['action_name']);
				}
				exit;
		}	
	}
	
	//if (isset($_GET['action']))
	//{
	//	if ($_GET['action'] == 'check'){		
	//		require_once TEMPLATEPATH . '/check_result.php';
	//		exit;
	//	}
	//	elseif ($_GET['action'] == 'roomtypes'){
	//		require_once TEMPLATEPATH . '/roomtypes.php';
	//		exit;
	//	}
	//	elseif ($_GET['action'] == 'booking_payment'){
	//		require_once TEMPLATEPATH . '/booking.php';
	//		exit;
	//	}
	//	elseif ($_GET['action'] == 'booking_option'){
	//		require_once TEMPLATEPATH . '/booking-option.php';
	//		exit;
	//	}
	//	else if ($_GET['action'] == 'Tpaypal') {
	//		require_once TEMPLATEPATH.'/paypal_success.php';
	//		exit;
	//	}	
	//	else if ($_GET['action'] == 'Fpaypal') {
	//		require_once TEMPLATEPATH.'/paypal_false.php';
	//		exit;
	//	}
	//	else if ($_GET['action'] == 'print') {
	//		require_once TEMPLATEPATH.'/print.php';
	//		exit;
	//	}
	//	// ajax
	//	else if ($_GET['action'] == 'ajax')
	//	{
	//		if ( isset ($_REQUEST['action_name'])  &&
	//			 file_exists ( TEMPLATEPATH . DIRECTORY_SEPARATOR . 'ajax' . DIRECTORY_SEPARATOR . $_REQUEST['action_name'] . '.php' ) )
	//		{
	//			require_once TEMPLATEPATH . DIRECTORY_SEPARATOR . 'ajax' . DIRECTORY_SEPARATOR . $_REQUEST['action_name'] . '.php';
	//			do_action('do_ajax', $_REQUEST['action_name']);
	//		}
	//		exit;
	//	}
	//}
	
	
	
}
function tgt_get_link_term(){
	$pages = get_option('tgt_pages_default');	
	return get_permalink($pages['footer_menu']['terms_of_use']);
}

function tgt_get_learn_more_link(){
	return get_permalink( tgt_get_learn_more_id() );
}

function tgt_get_learn_more_id(){
	$pages = get_option('tgt_pages_default');	
	return $pages['footer_menu']['learn_more'];
}

function tgt_get_booking_link (){
	//return HOME_URL . '/?action=booking_payment';
	return tgt_get_page_link('booking_payment');
}

function tgt_free_payment_link (){
	//return HOME_URL.'/?action=payment_success';
	return tgt_get_page_link('payment_success');
}

function tgt_get_roomtypes_link(){
	//return HOME_URL . '/?action=roomtypes';
	return tgt_get_page_link('roomtypes');
}

function tgt_get_contact_link(){
	$pages = get_option('tgt_pages_default');	
	return get_permalink($pages['header_menu']['contact_us']);
}

function tgt_get_location_link(){
	$pages = get_option('tgt_pages_default');	
	return get_permalink($pages['header_menu']['location']);
}
// New version
function tgt_get_link_print($code){
	return HOME_URL . '/?action=print&code='.$code;
}
function tgt_get_booking_option (){
	return tgt_get_page_link('booking_option');
}
function tgt_get_payment_submit (){
	return tgt_get_page_link('payment_submit');
}