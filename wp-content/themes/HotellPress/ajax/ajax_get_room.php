<?php
$folder_str_all= dirname( __FILE__ );  
$template_directory= str_replace("\\", "/", $folder_str_all);
$find= "/wp-content/themes/";
$pos= strpos($template_directory, $find);
define("FOLDER_STR_1", substr($template_directory, $pos+strlen($find)));

$sub_link= "";
$arr_sub_link= explode("/",FOLDER_STR_1);

if(count($arr_sub_link)-2>0)
{
for($i=0; $i<count($arr_sub_link)-2; $i++)
	$sub_link.= "../";
}

require( '../../../../'.$sub_link.'wp-load.php' );
$room_type = '';
$room_type	= $_GET['type_id'];
$u_id = '';
$u_id = $_GET['u_id'];
if($_GET['date_in'] != '...')
{
	$in		= explode("/", $_GET['date_in']);
	$date_in	= mktime(0, 0, 0, $in[0],   $in[1],   $in[2]);
}
if($_GET['date_out'] != '...')
{
	$out	= explode("/", $_GET['date_out']);
	$date_out	= mktime(0, 0, 0, $out[0],   $out[1],   $out[2]);
}
$val = '';
$tb_b = $wpdb->prefix.'bookings';
if($date_in != '' && $date_out != '')
	$where_more = "AND r.ID NOT IN 
					(				
				
						SELECT DISTINCT b.room_ID FROM $tb_b b 
						WHERE b.check_in < $date_out AND b.check_out > $date_in
						AND b.status='publish' 
					)";
else
	$where_more = 'AND 1=1';
if($room_type != '')
{
	/*if($room_type == 'all')
	{
		$n = $wpdb->prefix.'rooms';
		$q = "SELECT * FROM $n r WHERE r.status = 'publish' $where_more ORDER BY r.room_name ASC";							
		$rooms = $wpdb->get_results($q, 'OBJECT');
		$check = 0;
		if($rooms != '')
		{
			foreach($rooms as $k=>$v)
			{
				$check ++;
				$val .= return_data($v->ID,$v->room_name,$check);
			}	
		}
	}*/
	if($room_type != 'select')
	{
		$n = $wpdb->prefix.'rooms';
		$tb_bookings = $wpdb->prefix.'bookings';
		$sql_room_id = '';
		$q_room="SELECT r.ID FROM $wpdb->users u , $tb_bookings b , $n r , $wpdb->posts p WHERE u.ID = b.user_ID AND b.room_ID = r.ID AND r.room_type_ID = p.ID AND u.ID = '".$u_id."' ";	
		$list_rooms= $wpdb->get_results( $q_room );
		if (is_array($list_rooms) && !empty($list_rooms) ){
		foreach($list_rooms as $k=>$v)
		{
			$room_id .= $v->ID.',';
		}
		}
		$room_id = trim($room_id,',');
		if($room_id != '')
		{
			$sql_room_id = "OR (r.ID IN($room_id) AND r.status = 'publish' AND r.room_type_ID = $room_type)";
		}
		$q = "SELECT DISTINCT r.ID, r.room_name FROM $n r WHERE r.status = 'publish' AND r.room_type_ID = $room_type $where_more $sql_room_id ORDER BY r.room_name ASC";							

		$rooms = $wpdb->get_results($q);	
		if($rooms != '')
		{				
				$val .= return_data($rooms,$room_id);
		}
		else if($rooms == '')
		{
			$val = '';
		}		
	}
	else if($room_type == 'select')
	{
		$val = 'nothing';
	}
}
echo $val;
function return_data($rooms,$room_id)
{
	
	$data = '';
	
		$data .= '<span>'. __('Choose Room :','hotel').'*</span><br />';
		if(is_array($rooms) && !empty($rooms)){		
		foreach($rooms as $k=>$v)
		{
			$room_id_arr = explode(',',$room_id);			
			$data .= '<label>';
			$data .= '<input type="checkbox" name="cbRoom[]" id="cbRoom" value="'.$v->ID.'" ';
			if(count($room_id_arr) > 0)
			{
				for($i=0; $i<count($room_id_arr); $i++)
				{
					if($room_id_arr[$i] == $v->ID)
					{
						$data .= 'checked="checked"';			
					}
				}
			}
			$data .= ' />';
			$data .= $v->room_name;
			$data .= '</label>&nbsp;';
			if($count == 5)
				$data .= '<br>';
		}
		}else{
			$data .= '<font color="red"><em>'. __('Empty Room !','hotel') .'</em></font>';
		}
	
	if(get_option('tgt_room_option',true) != '' && !empty($rooms))
	{
			 $room_service = get_post_meta($_GET['type_id'],META_ROOMTYPE_SERVICES,true);
			 $selected_service = get_user_meta($_GET['u_id'], 'tgt_service',true); 
			$currency = get_option('tgt_currency');
			if ( $currency == "USD" || $currency == "AUD" || $currency == "CAD" || $currency == "NZD" || $currency == "HKD" || $currency == "SGD" ) { $currencysymbol = "$"; }
			else if ( $currency == "GBP" ) { $currencysymbol = "&pound;"; }
			else if ( $currency == "JPY" ) { $currencysymbol = "&yen;"; }
			else if ( $currency == "EUR" ) { $currencysymbol = "&euro;"; }
	else { $currencysymbol = ""; }	
		$data .= '<div class="services">';
			 $data .= '<br /><span>'. __('Services','hotel').':</span>';
			if (is_array($room_service) && !empty($room_service) ){
			 foreach($room_service as $k => $v)
			 {
			 										 
				  $data .= '<p>';
					  $data .= '<input type="checkbox" name="services['.$v['name'].']" value="'. $v['price'] .'"';
					 if(count($selected_service) > 0)
					{
						if (is_array($selected_service) && !empty($selected_service)){
						foreach ($selected_service as $ks => $vs)
						{							
							if($ks == $v['name'])
							{
								$data .= 'checked="checked"';			
							}
						}
						}
					}
							  
					  $data .= '/>';
					  $data .= '<label for="service_1">';
					  
					   $data .= $v['name'].' ('.$currencysymbol.$v['price'].')';
					  
					  $data .= '</label>';
				  $data .= '</p>';
			 
			 }
			}else {
				$data .= '<br /><font color="red"><em>'. __('Not Services !','hotel') .'</em></font>';
			}
			 										 
		$data .= '</div>';
		
		}
			
		
	return $data;
}
?>