<?php
add_action('do_ajax','ajax_capability_price_process');
function ajax_capability_price_process(){
	$response = '';
	$capability_roomtype = $_POST['capability_roomtype'];	
	$content = '';
	for ($i = 1 ; $i <=$capability_roomtype; $i++) {
		$selected = '';
		if ($i == $capability_roomtype){
			$selected = 'checked = "checked"';
		}
		$content .= '<tr>';
				$content .= '<td width="30%" align="left">'.__( 'Price for' , 'hotel' ).' '. $i .' '. __( 'person' , 'hotel') .': </td>';
				$content .= '<td align="left"> <input type="text" name="roomtype_cap_price_'.$i.'" style="width: 100px; font-size: 14px" value="" onkeypress="return EnterNumber(event)" /><input type="radio" name="select_price" style="font-size: 14px" value="'.$i.'" '.$selected.' /> </td>';				
				$content .= '</tr>';
	}
			
	
	header('HTTP/1.1 200 OK');
	header('Content-Type: application/json'); 
	
	$response = json_encode(array('success' => true, 'content' => $content,'message' => 'success')); 
	echo $response;
	exit;
}