<?php
$folder_str_all= dirname( __FILE__ );  
$template_directory= str_replace("\\", "/", $folder_str_all);
$find= "/wp-content/themes/";
$pos= strpos($template_directory, $find);
define("FOLDER_STR_2", substr($template_directory, $pos+strlen($find)));

$sub_link= "";
$arr_sub_link= explode("/",FOLDER_STR_2);

if(count($arr_sub_link)-2>0)
{
for($i=0; $i<count($arr_sub_link)-2; $i++)
	$sub_link.= "../";
}

require( '../../../../'.$sub_link.'wp-load.php' );
if(isset($_GET['action']) && !empty($_GET['action']) && $_GET['action'] == 'fill_info')
{	
	global $wpdb;
	$currency = get_option('tgt_currency');
	$title	= $_GET['title'];
	$email	= $_GET['email'];
	$phone	= $_GET['phone'];
	$f_name = $_GET['f_name'];
	$l_name = $_GET['l_name'];
	$country= $_GET['country'];
	$state	= $_GET['state'];
	$street	= $_GET['street'];	
	$room_type = $_GET['room_type'];
	$date_in= $_GET['date_in'];
	$date_out=$_GET['date_out'];
	$num_rooms = $_GET['num_rooms'];
	$agree_purchase = $_GET['agree_purchase'];
	$payment_method = 'Cash';
	if($agree_purchase == '1')
		$payment_method = 'Purchase online';
	$wpdb->insert( "$wpdb->users", array('user_email'=> $email, 'user_registered'=> current_time('mysql')));
				
	$u_id = $wpdb->insert_id;
	
	$link_return = HOME_URL.'/?action=payment_success&room_type='.$room_type.'&num_rooms='.$num_rooms.'&date_in='.$date_in.'&date_out='.$date_out.'&total_price='.$_GET['paid'].' '.$currency.'&u_id='.$u_id;
	$link_cancel_return = HOME_URL.'/?action=payment_false&ero=Ero&u_id='.$u_id;
	
	add_user_meta($u_id,'first_name',$f_name);
	add_user_meta($u_id,'last_name',$l_name);
	add_user_meta($u_id,'tgt_customer_title',$title);
	add_user_meta($u_id,'tgt_customer_phone',$phone);
	add_user_meta($u_id,'tgt_payment_method',$payment_method);
	add_user_meta($u_id, 'tgt_total_price',$_GET['paid'], true);// Add toltal price for tmp
	update_user_meta($u_id,'tgt_paypal_return',$link_return);
	update_user_meta($u_id,'tgt_paypal_cancel_return',$link_cancel_return);
	
	$user_info = array(
						'u_id'  => $u_id,
						'room_type'=> $room_type,
						'num_rooms'=> $num_rooms,
						'date_in'=>  $date_in,
						'date_out'=> $date_out,
						'total_price' => $_GET['paid']
					);
	update_user_meta($u_id,'tgt_user_information',$user_info);
	
	$cus_add = array('country' => $country,
					 'state'   => $state,
					 'street'  => $street);
	add_user_meta($u_id,'tgt_customer_address',$cus_add);
	
	
	$tb_b = $wpdb->prefix.'bookings';
	$r = $wpdb->prefix.'rooms';	
	$q_r="	SELECT r.ID  
			FROM $r r 
			WHERE r.room_type_ID=$room_type 
				AND r.status='publish'
				AND r.ID NOT IN 
				( 
					SELECT DISTINCT b.room_ID FROM $tb_b b 
					WHERE b.check_in < $date_out AND b.check_out > $date_in
					AND b.status='publish' 
				)
			ORDER BY r.room_name
			LIMIT 0, $num_rooms";				
	$room = $wpdb->get_results( $q_r );
	if(count($room) == $num_rooms)
	{		
			echo $u_id;
	}
	else if(count($room) < $num_rooms)
		echo 'error';
}
?>