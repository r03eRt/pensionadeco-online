<?php
if(isset($_POST['contact_admin']))
{
	$hotel_email = get_option('tgt_hotel_email');
	if($hotel_email !='')
	{
		$header = 'From: '.$_POST['name'].'<'.$_POST['email'].'>';		
		$comment = $_POST['comments'];	
		@wp_mail($hotel_email, __('A message from customer','hotel'), $comment, $header);
	}	
}
// in Booking page
if(isset($_POST['search']) && $_POST['roomtype'] != '' && get_option('tgt_permit_payment') == 1)
{
	$services = 0;
	if(isset($_POST['go_booking_page']) && !empty($_POST['go_booking_page']))
	{
		if(isset($_POST['services']) && !empty($_POST['services']))
		{
			$default_services = get_post_meta($_POST['roomtype'],META_ROOMTYPE_SERVICES,true);
			foreach($_POST['services'] as $k => $v)
			{
				if($default_services[$v]['name'] != '')
				{
					$services += $default_services[$v]['price'];
				}
			}
		}
	}

	$paid = 0;
	$currency = get_option('tgt_currency');
	if ( $currency == "USD" || $currency == "AUD" || $currency == "CAD" || $currency == "NZD" || $currency == "HKD" || $currency == "SGD" ) { $currencysymbol = "$"; }
	else if ( $currency == "GBP" ) { $currencysymbol = "&pound;"; }
	else if ( $currency == "JPY" ) { $currencysymbol = "&yen;"; }
	else if ( $currency == "EUR" ) { $currencysymbol = "&euro;"; }
	else { $currencysymbol = $currency; }
	$room_type = get_post($_POST['roomtype']);	
	if(isset($_POST['from'],$_POST['to']) && !empty($_POST['from']) && !empty($_POST['to']))
	{
		$arrival_date = strtotime($_POST['from']);
		$departure_date = strtotime($_POST['to']);		
		$date_in	= $arrival_date;
		$date_out	= $departure_date;
	}else
	{
		$arrival_date = strtotime($_POST['arrival_date']);
		$departure_date = strtotime($_POST['departure_date']);
		$in		= explode("/", $_POST['arrival_date']);
		$out	= explode("/", $_POST['departure_date']);
		$date_in	= mktime(0, 0, 0, $in[0],   $in[1],   $in[2]);
		$date_out	= mktime(0, 0, 0, $out[0],   $out[1],   $out[2]);	
	}
	
	$day_rate =  floor(($date_out - $date_in)/86400);
	$num_adults = $_POST['num_adults'];
	//$num_kids = $_POST['num_kids'];
	$num_rooms = $_POST['num_rooms'];

	//$room_price = get_post_meta($_POST['roomtype'],'tgt_roomtype_price',true);
	$room_price = 0;
	$capability_tmp = '';
	$check_selected = '';
	$capability = get_post_meta($_POST['roomtype'],META_ROOMTYPE_CAP_PRICE,true);
	if(!empty($capability))
	{
		foreach($capability as $k => $v)
		{
			if($v['selected'] != '')
			{
				$check_selected = $k;
			}
		}
	}

	$capability = '';
	$capability_tmp = pricing_room(date('Y-m-d',$arrival_date),date('Y-m-d',($departure_date - (3600*24))),array($_POST['roomtype']));
	ksort($capability_tmp);	

	if(!empty($capability_tmp))
	{
		$total_room_price = 0;
		//$capability = $capability_tmp[$arrival_date];			
		foreach($capability_tmp as $k => $v)
		{			
			$room_price = 0;
			$room_price = $v[$check_selected]*$num_rooms;
			$total_room_price +=  $room_price;			
		}
		$price = $total_room_price;
	}	
	if($capability == '')
	{		
		$capability = get_post_meta($_POST['roomtype'],META_ROOMTYPE_CAP_PRICE,true);
//		if(!empty($capability))
//		{
//			foreach($capability as $k => $v)
//			{
//				if($v['selected'] != '')
//				{
//					$room_price = $v['price'];
//				}
//			}
//		}
//		$price = ( $room_price * $num_rooms  * $day_rate );
//		echo $room_price;
//exit;
	}
	
	$price = $price + $services;
	$deposit = get_option('tgt_deposit_percent');		
	//$price = ( $room_price * $num_rooms  * $day_rate ) + $services;
	if(isset($_POST['go_booking_page']) && !empty($_POST['go_booking_page']))
	{
		$promotion_price = 0 ;		
		$promotion_total_price = 0;
		$room_price_tmp = 0;
		$arr_tmp = '';
		
		if(!empty($_POST['number_room']))
		{
			foreach($_POST['number_room'] as  $k => $v)
			{
				$arr_tmp = explode("_",$v);
				$room_price_tmp += $arr_tmp[1];
			}
		}		
		$price =  ( $room_price_tmp   ) + $services;


		$promotion_price = get_promotion($price,$arrival_date,$_POST['promotion']);

		$promotion_total_price = $promotion_price;
		$price = $price - $promotion_total_price[0];
		if($price < 0)
			$price = 0;
	}
		
	$fields = array();
	if(get_option('tgt_room_fields',true) != '')
	{
		$fields = get_option('tgt_room_fields',true);
	}
	global 	$wpdb;
	$tb_b = $wpdb->prefix.'bookings';
	$r = $wpdb->prefix.'rooms';

	
	$q_r="	SELECT r.ID  
			FROM $r r 
			WHERE r.room_type_ID=$room_type->ID 
				AND r.status='publish'
				AND r.ID NOT IN 
				( 
					SELECT DISTINCT b.room_ID FROM $tb_b b 
					WHERE b.check_in < $date_out AND b.check_out > $date_in
					AND b.status='publish' 
				)
			ORDER BY r.room_name
			LIMIT 0, $num_rooms";
	$get_room = $wpdb->get_results( $q_r );
}else
{
	require_once TEMPLATEPATH.'/404.php';
	exit;
}
?>