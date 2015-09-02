<?php
add_action('posts_request', 'hook_posts_request');
function hook_posts_request ($q){
	global $wpdb;
	$r = $wpdb->prefix . 'rooms'; // $r ~ wp_rooms 
	$p = $wpdb->prefix . 'posts'; // $p ~ wp_posts
	$b = $wpdb->prefix . 'bookings'; // $b ~ wp_bookings
	$pm = $wpdb->prefix . 'postmeta'; //$pm ~ wp_postmeta
	
	if (is_search() && strpos($q, 'nav_menu_item') === false ){
		if (empty($_POST['search'])) {
			require_once TEMPLATEPATH . '/404.php';
			exit;
		}		
		$num_adults = intval($_POST['num_adults']); //adults number per room
		//$num_chid = intval($_POST['num_kids']); //chidrens number per room		
		//
		//$pet_q = "OR	(meta_key='tgt_roomtype_permit_pet')";//if no select (enable/disable) smoking
		//$smoking_q = "OR	(meta_key='tgt_roomtype_permit_smoking') "; //if no select (enable/disable) pet
		//if (empty($_POST['pet_enable']) !== empty($_POST['pet_disable'])){
		//	if (!empty($_POST['pet_enable'])) $pet_q = "OR	(meta_key='tgt_roomtype_permit_pet' AND meta_value = 1) ";
		//	elseif(!empty($_POST['pet_disable']))
		//	  $pet_q = "OR	(meta_key='tgt_roomtype_permit_pet' AND meta_value = 0) ";		
		//}
		//if(empty($_POST['smoking_enable']) !== empty($_POST['smoking_disable'])){
		//	if (!empty($_POST['smoking_enable'])) $smoking_q = "OR	(meta_key='tgt_roomtype_permit_smoking' AND meta_value = 1) ";
		//	elseif (!empty($_POST['smoking_disable']))
		//	  $smoking_q = "OR	(meta_key='tgt_roomtype_permit_smoking' AND meta_value = 0) ";
		//}		
		//$conditions_q = $pet_q . $smoking_q;
		$fields = array();
		if(get_option('tgt_room_fields',true) != '')
		{
			$fields = get_option('tgt_room_fields',true);
		}
		$q = "
			SELECT ID FROM $p
	
			JOIN $pm
			ON post_id=ID
			WHERE post_status='publish'";
		if(!empty($fields))
		{
			foreach($_POST as $k=>$v)
			{
				if(is_numeric($k) && !empty($v))
				{
					$q .= "AND ID IN(";
					$q .= "SELECT p_$k.ID FROM $p p_$k JOIN $pm pm_$k ON pm_$k.post_id = p_$k.ID
								WHERE p_$k.post_status='publish'";
					$q .=  "	AND pm_$k.meta_key = 'tgt_meta_roomtype_field_$k' AND pm_$k.meta_value LIKE '%$v%')";
				}
			}
		}	
		$q .= "
			AND ID IN (
				SELECT p_cab.ID FROM $p p_cab JOIN $pm pm_cab ON pm_cab.post_id = p_cab.ID
				WHERE p_cab.post_status='publish'
					AND pm_cab.meta_key = '".META_ROOMTYPE_CAPABILITY."' AND pm_cab.meta_value >= $num_adults
			)
			AND post_type='roomtype'			
			GROUP BY ID	";
		
		/*HAVING COUNT(*) = 4 vi chua co field tgt_roomtype_chidren_number neu co phai la 4*/
		/*echo $q;	
		echo "<pre>";
		print_r ($wpdb->get_col($q));exit;*/
		
		$roomtypes = implode(', ', $wpdb->get_col($q));
				
		$num_room = intval($_POST['num_rooms']);
		if (isset($_POST['arrival_date'])){
			$check_in = strtotime($_POST['arrival_date']);
			$check_out = strtotime($_POST['departure_date']);
		}
		else{
			$check_in = strtotime($_POST['from']);
			$check_out = strtotime($_POST['to']);
		}

		//$roomtypes = '132, 133, 134' ;
		/*AND $p.ID IN ($roomtypes)*/
		
		$q = "
			SELECT SQL_CALC_FOUND_ROWS $p.* FROM $p
		
			JOIN $r 
			ON $p.ID=$r.room_type_ID AND $r.status='publish'
			
			WHERE  $p.post_status='publish' AND $p.ID IN ($roomtypes)
			AND 
			$r.ID NOT IN
			(
				SELECT DISTINCT $b.room_ID
				FROM $b
				WHERE $b.check_in < $check_out
				AND $b.check_out > $check_in
				AND $b.status='publish'
			)
			AND $check_out > $check_in
			
			GROUP BY $r.room_type_ID
			HAVING  COUNT(*) >= $num_room
			";
	}
	
	return $q;
} 

//contain query for data
/**
 * edit query for "check rooms avalible" - search.php
 */
/*add_action ('posts_where', 'hook_posts_where');
function hook_posts_where($where){
	if(is_search()){
		$where = "AND post_type='post' AND post_status='publish'";	
	}
	
	return $where;
}
*/
/**
 * 
 * Query booking data along with user, room infomation from booking code
 * @param string $code: Booking code for query
 * @return array of booking_info: infomation of query data
 */
function tgt_get_booking_detail($code){
	global $wpdb;
	
	$prefix = $wpdb->prefix;
	$result = new booking_info();
	/**
	 * define table
	 */
	$table_users 		= $prefix . 'users';
	$table_usermeta 	= $prefix . 'usermeta';
	$table_booking 		= $prefix . 'bookings';
	$table_posts 		= $prefix . 'posts';
	$table_postmeta 	= $prefix . 'postmeta';
	$table_rooms 		= $prefix . 'rooms';	
	
	$publish_status 	= 'publish';
	 	
	/**
	 * - first, find the user that own the code.
	 * - right after got the user id, get user infomation
	 * - last, we get infomation of rooms that user has booked
	 */

	
	/**
	 * Step 1: get user id 
	 */
	// Getting the code
	$queryString = "SELECT user_id FROM {$table_usermeta} AS user_meta " .
					"WHERE (user_meta.meta_key = 'tgt_customer_code') 
					AND (user_meta.meta_value = '{$code}')";
	
	//get the user id
	$queryResult = $wpdb->get_results($queryString, OBJECT);
	$user_id = $queryResult[0]->user_id;
	
	if (empty($user_id))
		return null;
	
	// add user_id into result
	$result->customer_id = $user_id; 
	
	/**
	 * Step 2: get user infomation
	 */
	//get the user infomation
	$queryString = "SELECT users.user_email, 
						user_meta.meta_key, 
						user_meta.meta_value, 
						booking.check_out, 
						booking.check_in
						
					FROM {$table_users} AS users , 
						{$table_usermeta} AS user_meta, 
						{$table_booking} AS booking
						
					WHERE users.ID = $user_id 
					AND user_meta.user_id = users.ID 
					AND users.ID = booking.user_ID 
					AND booking.status = '{$publish_status}'";
	$queryResult = $wpdb->get_results($queryString, OBJECT);
	
	// add customer email into result
	$result->customer_email = $queryResult[0]->user_email;
	$result->check_in = $queryResult[0]->check_in;
	$result->check_out = $queryResult[0]->check_out;
	foreach ($queryResult as $row) {
		switch ($row->meta_key){
			case 'first_name':
				$result->customer_first_name = $row->meta_value; 
				break;
			case 'last_name': 
				$result->customer_last_name = $row->meta_value;
				break;
			case 'tgt_customer_title': 
				$result->customer_title = $row->meta_value;
				break;
			case 'tgt_customer_phone': 
				$result->customer_phone = $row->meta_value;
				break;
			case 'tgt_customer_address': 
				$result->customer_address = $row->meta_value;
				break;
			case 'tgt_customer_code': 
				$result->customer_code = $row->meta_value;
				break;
		}
	}
	
	
	/**
	 * last step, get the rooms infomation
	 */
	
	$queryString = "SELECT rooms.ID as room_id, 
						rooms.room_name, 
						posts.post_content, 
						posts.post_title, 
						posts.ID as room_type_id, 
						post_meta.meta_key, 
						post_meta.meta_value
						
					FROM {$table_rooms} AS rooms, 
						{$table_posts} AS posts, 
						{$table_booking} AS bookings, 
						{$table_postmeta} AS post_meta
						
					WHERE (bookings.user_ID = {$user_id}) 
					AND (rooms.room_type_ID = posts.ID) 
					AND (bookings.room_ID = rooms.ID)
					AND (post_meta.post_id = posts.ID) 
					AND (rooms.status = '{$publish_status}')
					
					ORDER BY rooms.ID asc";
	$queryResult = $wpdb->get_results($queryString, OBJECT);
	//get rooms 
	$rooms = array();
	$room_id = -1;
	foreach ($queryResult as $row) {
		if ($room_id != $row->room_id)
		{
			$room_id = $row->room_id;
			$room = new room_booking_info();
			$rooms[] = $room;
			$room->room_id 		= $row->room_id;
			$room->room_name 	= $row->room_name;
			$room->room_desc 	= $row->post_content;
			$room->room_type 	= $row->post_title;
			$room->room_type_id = $row->room_type_id;
		}
		switch ($row->meta_key){
			case 'tgt_roomtype_person_number';
				$room->room_capability = $row->meta_value;
				break;
			case 'tgt_roomtype_bed_name';
				$room->room_bed_type = $row->meta_value;
				break;
			case 'tgt_roomtype_permit_pet';
				$room->room_pet_allowed = $row->meta_value;
				break;
			case 'tgt_roomtype_permit_smoking';
				$room->room_smoking_allowed = $row->meta_value;
				break;
			case 'tgt_roomtype_discount';
				$room->room_discount = $row->meta_value;
				break;
		}
	}
	
	// set rooms infomation to result
	$result->rooms = $rooms;
	
	return $result;	
}

/**
 * 
 * Object Booking detail contain all booking infomation
 * @property $customer_id
 * @property $customer_first_name
 * @property $customer_last_name
 * @property $customer_title
 * @property $customer_phone
 * @property $customer_code
 * @property $check_in
 * @property $check_out
 * @property $rooms array of rooms detail
 * @method get_check_in
 * @method get_check_out
 * @method get_full_name
 */
class booking_info{
	var $customer_id;
	var $customer_first_name;
	var $customer_last_name;
	var $customer_title;
	var $customer_phone;
	var $customer_email;
	var $customer_code;
	var $check_in;
	var $check_out;
	var $rooms; //array of rooms 
	
	function get_check_in($format = ''){
		$fm = empty($format) ? 'j/m/Y' : $format;
		return date($fm, $this->check_in);
	}
	
	function get_check_out($format = ''){
		$fm = empty($format) ? 'j/m/Y' : $format;
		return date($fm, $this->check_out);
	}
	
	function get_full_name(){
		return ($this->customer_title . ' ' . $this->customer_first_name . ' '  . $this->customer_last_name);
	}
}

/**
 * 
 * Object Room Detail contain room detail that belong to an booking 
 * @author Information
 * @property $room_id 
 * @property $room_name
 * @property $room_type
 * @property $room_type_id 
 * @property $room_desc description of the room
 * @property $room_capability max capability of the room
 * @property $room_bed_type king or twin ...
 * @property $room_pet_allowed 
 * @property $room_smoking_allowed
 * @property $room_discount
 */
class room_booking_info{
	var $room_id;
	var $room_name;
	var $room_type;
	var $room_type_id;
	var $room_desc;
	var $room_capability;
	var $room_bed_type;
	var $room_pet_allowed;
	var $room_smoking_allowed;
	var $room_discount;	
}

