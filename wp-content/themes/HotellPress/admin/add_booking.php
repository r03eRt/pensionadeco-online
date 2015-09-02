<?php
$currency = get_option('tgt_currency');
	if ( $currency == "USD" || $currency == "AUD" || $currency == "CAD" || $currency == "NZD" || $currency == "HKD" || $currency == "SGD" ) { $currencysymbol = "$"; }
	else if ( $currency == "GBP" ) { $currencysymbol = "&pound;"; }
	else if ( $currency == "JPY" ) { $currencysymbol = "&yen;"; }
	else if ( $currency == "EUR" ) { $currencysymbol = "&euro;"; }
	else { $currencysymbol = ""; }
	
global $wpdb;      
$editbooking = '';
$uid = '';
$error 		= '';
if(!empty($_GET['editbooking']))
	$editbooking = $_GET['editbooking'];
if(!empty($_GET['uid']))
	$uid = $_GET['uid'];
if(isset($_POST['submitted']) && !empty($_POST['submitted'])){

//Check validate
if($_POST['first_name'] == '')
	$error .= __('First name field is not blank !','hotel').'<br>';
if($_POST['last_name'] == '')
	$error .= __('Last name field is not blank !','hotel').'<br>';
if($_POST['email'] == '')
	$error .= __('Email field is not blank !','hotel').'<br>';
if($_POST['phone'] == '')
	$error .= __('Phone field is not blank !','hotel').'<br>';
if($_POST['country'] == '')
	$error .= __('Country field is not blank !','hotel').'<br>';
if($_POST['state'] == '')
	$error .= __('State field is not blank !','hotel').'<br>';
if($_POST['street'] == '')
	$error .= __('Street field is not blank !','hotel').'<br>';

if($editbooking == '')
{
	if($_POST['Checkin'] == '' || $_POST['Checkin'] == '...')
		$error .= __('Please choose check in date !','hotel').'<br>';
	if($_POST['Checkout'] == '' || $_POST['Checkout'] == '...')
		$error .= __('Please choose check out date !','hotel').'<br>';
	if(strtotime($_POST['Checkout']) < strtotime($_POST['Checkin']))
		$error .= __('Check out date should be greater than check in date !','hotel').'<br>';
	if(empty($_POST['cbRoom']))
		$error .= __('Please choose room !','hotel').'<br>';
}elseif($editbooking == 'true')
{
	
	if(($_POST['Checkin'] != '' && $_POST['Checkin'] != '...') && ($_POST['Checkout'] != '' && $_POST['Checkout'] != '...') )
	{		
		if(strtotime($_POST['Checkout']) < strtotime($_POST['Checkin']))
		{
			$error .= __('Check out date should be greater than check in date !','hotel').'<br>';
		}/*elseif($_POST['Checkout'] > $_POST['Checkin'])
		{
			if(empty($_POST['cbRoom']))		
				$error .= __('Please choose room !','hotel').'<br>';
		}*/
	}
	if(($_POST['Checkin'] != '' && $_POST['Checkin'] != '...') && ($_POST['Checkout'] == '' || $_POST['Checkout'] == '...'))
	{
		$error .= __('Please choose check out date !','hotel').'<br>';
	}
	if(($_POST['Checkin'] == '' || $_POST['Checkin'] == '...') && ($_POST['Checkout'] != '' && $_POST['Checkout'] != '...'))
	{
		$error .= __('Please choose check in date !','hotel').'<br>';
	}
}

if ( $error == "" )
{
	//Customer Informations		
	$cus_title	= $_POST['s_title'];
	$first_name	= $_POST['first_name'];
	$last_name	= $_POST['last_name'];
	$email		= $_POST['email'];
	$phone		= $_POST['phone'];
	$country	= $_POST['country'];
	$state		= $_POST['state'];
	$street		= $_POST['street'];
	
	//Booking Informations
	$check_in	= $_POST['Checkin'];
	$check_out	= $_POST['Checkout'];
	$room_type 	= $_POST['room_type'];
	if(isset($_POST['cbRoom']) && !empty($_POST['cbRoom']))
		$rooms		= $_POST['cbRoom'];
}
//Process data
	if ( $error == "" ) {	
		if($editbooking != 'true')
		{					
				$wpdb->insert( "$wpdb->users", array('user_email'=> $email, 'user_registered'=> current_time('mysql')));
				
				$u_id = $wpdb->insert_id;
				
				add_user_meta($u_id,'first_name',$first_name);
				add_user_meta($u_id,'last_name',$last_name);
				add_user_meta($u_id,'tgt_customer_title',$cus_title);
				add_user_meta($u_id,'tgt_customer_phone',$phone);
				if(isset($_POST['services']) && !empty($_POST['services']))
						{
							$arr_service = array();
							foreach($_POST['services'] as $k => $v)
							{
								$arr_service[$k] = $v;
							}
							add_user_meta($u_id, 'tgt_service', $arr_service);

						}
				if(isset($_POST['total_price']) && !empty($_POST['total_price']))
				{
					add_user_meta($u_id, 'tgt_total_price',$_POST['total_price']);
				}
				$cus_add = array('country' => $country,
								 'state'   => $state,
								 'street'  => $street);
				add_user_meta($u_id,'tgt_customer_address',$cus_add);
				
				$code = md5('booking_'.$u_id);				
				add_user_meta($u_id,'tgt_customer_code',$code);
								
				$in		= explode("/", $check_in);
				$out	= explode("/", $check_out);
				
				$date_in	= mktime(0, 0, 0, $in[0],   $in[1],   $in[2]);
				$date_out	= mktime(0, 0, 0, $out[0],   $out[1],   $out[2]);
				
				for ($i=0; $i < count($rooms); $i++)
				{	
					$wpdb->insert( "$wpdb->prefix"."bookings", array('room_ID'=> $rooms[$i], 'user_ID'=>$u_id, 'check_in'=>$date_in, 'check_out'=>$date_out, 'status'=>'publish'));						
				}	
				
		       	
			      // $table = 'bookings';
			      // $sql = "SELECT ID FROM ".$wpdb->prefix.$table ." order by ID desc limit 1" ;
			       //$bookingid = $wpdb->get_results($sql);				
							 
				$arr = array(
					//'booking_id' => $book_arr[0]->ID,
					'booking_id' => $u_id,
					'customer_id' => $u_id,
					'date' => current_time('mysql'),
					'amount' => $_POST['total_price'],
					'currency' => $currencysymbol
				);				
				doInsert($arr);
				
				$message = __('Congratulation, reservation success !','hotel');			
				setcookie("message", $message, time()+3600);				
				echo "<script language='javascript'>window.location = '"."admin.php?page=my-submenu-list-booking"."'</script>";			
		}
		else if($editbooking == 'true')
		{
			$u_id = $uid;
			if(($check_in != '' && $check_in != '...') && ($check_out != '' && $check_out != '...'))
			{
				global $wpdb;
				$tb_b = $wpdb->prefix.'bookings';
				$r = $wpdb->prefix.'rooms';
				$in		= explode("/", $check_in);
				$out	= explode("/", $check_out);
				
				$date_in	= mktime(0, 0, 0, $in[0],   $in[1],   $in[2]);
				$date_out	= mktime(0, 0, 0, $out[0],   $out[1],   $out[2]);
				
				if(empty($rooms))
				{
					$q_room="SELECT r.ID, r.room_name FROM $wpdb->users u , $tb_b b , $r r , $wpdb->posts p WHERE u.ID = b.user_ID AND b.room_ID = r.ID AND r.room_type_ID = p.ID AND u.ID = '".$u_id."' ";	
					$list_rooms= $wpdb->get_results( $q_room );					
					for ($i=0; $i < count($list_rooms); $i++)
					{
						$get_room = '';
						$q_r="	SELECT r.ID  
						FROM $r r 
						WHERE r.ID = ".$list_rooms[$i]->ID."
							AND r.status='publish'
							AND r.ID NOT IN 
							( 
								SELECT DISTINCT b.room_ID FROM $tb_b b 
								WHERE b.check_in < $date_out AND b.check_out > $date_in
								AND b.status='publish' 
							)
						ORDER BY r.room_name";
						$get_room = $wpdb->get_results( $q_r );
						if(empty($get_room))
						{
							$error .= __('Your room(s) had been reservation by the other customer','hotel');
							$error .= ' '.__('from','hotel').' '.$check_in.' '.__('to','hotel').' '.$check_out.'<br>';
							break;
						}
					}
				}elseif(!empty($rooms))
				{
					for ($i=0; $i < count($rooms); $i++)
					{
						$get_room = '';
						$q_r="	SELECT r.ID, r.room_name 
						FROM $r r 
						WHERE r.ID = $rooms[$i]
							AND r.status='publish'
							AND r.ID NOT IN 
							( 
								SELECT DISTINCT b.room_ID FROM $tb_b b 
								WHERE b.check_in < $date_out AND b.check_out > $date_in
								AND b.status='publish' 
							)
						ORDER BY r.room_name";
						$get_room = $wpdb->get_results( $q_r );
						if(empty($get_room))
						{
							$error .= __('Your room(s) had been booking by the other customer','hotel');
							$error .= ' '.__('from','hotel').' '.$check_in.' '.__('to','hotel').' '.$check_out.'<br>';
							break;
						}
					}
				}
			}
			if($error == '')
			{
				global $wpdb;
				$wpdb->update( "$wpdb->users", array('user_email'=> $email), array('ID' => "$uid"));		
				
				update_user_meta($uid,'first_name',$first_name);
				update_user_meta($uid,'last_name',$last_name);
				update_user_meta($uid,'tgt_customer_title',$cus_title);
				update_user_meta($uid,'tgt_customer_phone',$phone);
				if(isset($_POST['services']) && !empty($_POST['services']))
						{
							$arr_service = array();
							foreach($_POST['services'] as $k => $v)
							{
								$arr_service[$k] = $v;
							}
							update_user_meta($uid, 'tgt_service', $arr_service);

						}
				if(isset($_POST['total_price']) && !empty($_POST['total_price']))
				{
					update_user_meta($uid, 'tgt_total_price',$_POST['total_price']);
				}
				$cus_add = array('country' => $country,
								 'state'   => $state,
								 'street'  => $street);
				update_user_meta($uid,'tgt_customer_address',$cus_add);
				if(($check_in != '' && $check_in != '...') && ($check_out != '' && $check_out != '...'))
				{
					$in		= explode("/", $check_in);
					$out	= explode("/", $check_out);
					
					$date_in	= mktime(0, 0, 0, $in[0],   $in[1],   $in[2]);
					$date_out	= mktime(0, 0, 0, $out[0],   $out[1],   $out[2]);
					if(!empty($rooms))
					{
						$wpdb->query("DELETE FROM ".$wpdb->prefix."bookings WHERE user_ID = $uid");
						for ($i=0; $i < count($rooms); $i++)
						{					
							$wpdb->insert( "$wpdb->prefix"."bookings", array('room_ID'=> $rooms[$i], 'user_ID'=>$uid, 'check_in'=>$date_in, 'check_out'=>$date_out, 'status'=>'publish'));						
						}
					}else
					{
						$wpdb->update( "$wpdb->prefix"."bookings", array('check_in'=>$date_in, 'check_out'=>$date_out), array('user_ID' => "$uid"));
					}
				}	
				$arr_edit = get_user_to_edit($uid);
				$date_sigup_edit = explode(' ' ,$arr_edit->user_registered);		
				
				$arr = array(
					'booking_id' => $uid,					
					'customer_id' => $uid,
					'date' => date('m/d/Y',strtotime($date_sigup_edit[0])),
					'amount' => $_POST['total_price'],
					'currency' => $currencysymbol
				);
				doUpdate($arr);
				
				
				$message = __('Congratulation, modified reservation success !','hotel');				
				setcookie("message", $message, time()+3600);
				echo "<script language='javascript'>window.location = '"."admin.php?page=my-submenu-list-booking"."'</script>";	
			}					
		}
	}	
}
?>
<form method="post" name="booking" id="booking" enctype="multipart/form-data" target="_self"> 
<input name="submitted" type="hidden" value="yes" /> 
<?php
global $wpdb;
$book_arr = '';
$user = '';
if(isset($_GET['uid']) && !empty($_GET['uid']))
{		
	//$uid = $_GET['uid'];		
	$tb_bookings = $wpdb->prefix.'bookings';
	$tb_rooms = $wpdb->prefix.'rooms';
	$q_s="
	SELECT b.user_ID , b.check_in , b.check_out , b.status , b.ID , p.post_title , u.user_registered 
	FROM $wpdb->users u , $tb_bookings b , $tb_rooms r , $wpdb->posts p 
	WHERE u.ID = b.user_ID 
		AND b.room_ID = r.ID 
		AND r.room_type_ID = p.ID 
		AND  b.user_ID = $uid GROUP BY u.ID ORDER BY u.user_registered DESC";	
	
	$book_arr = $wpdb->get_results( $q_s );	
	$user = get_userdata($uid);
}
?>
<div class="wrap">	
	<div class="atention">
		<strong><?php __('Problems? Questions?','hotel');?></strong><?php _e(' Contact us at ','hotel');?><em><a href="http://www.dailywp.com/support/">Support</a></em>. 
	</div>
    <br/>
    <?php
	if($error != '')
		echo "<div id=\"message\" class=\"error\"><p><strong>".$error."</strong></p></div>";
	?>	
	<div class="settings" style="margin: 0px 0px 0px 0px; padding-top: 0px 0px 0px 0px;">
		<div class="heading">
			<h3 style="padding-top:10px;"><?php _e('Reservation ','hotel');?>:</h3>
			<div class="cl"></div>
		</div>
		<div class="item" style="padding: 0 0 0 8px; height:100%;float:left;width:99%">
			<div class="content" id="page">	            
				<div class="content submission" id="jobs" >				    				          
					<div style="margin-bottom: 20px;width:300px;float:left; margin-left:30px; ">	
               	    <h3><?php _e('Customer Informations :','hotel');?></h3>
						
						<span><?php _e('Title :','hotel');?></span>
						<div class="inputStyle">
							<select name="s_title" id="s_title" style="width:100px;">
                            	<option value="Mr" selected="<?php if($_POST['s_title'] == 'Mr.') echo 'selected'; ?>" ><?php _e('Mr.','hotel'); ?></option>
                                <option value="Mrs" selected="<?php if($_POST['s_title'] == 'Mrs.') echo 'selected'; ?>" ><?php _e('Mrs.','hotel'); ?></option>
                                <option value="Ms" selected="<?php if($_POST['s_title'] == 'Ms.') echo 'selected'; ?>" ><?php _e('Ms.','hotel'); ?></option>
                            </select>
                            <script language="javascript">
							{
								for(var i=0;i<document.booking.s_title.length;i++){
								
									if(document.booking.s_title[i].value=="<?php echo get_user_meta($uid, 'tgt_customer_title', true);?>"){														
										document.booking.s_title.selectedIndex=i;
										break;
									}
								}
							}
							</script>				
						</div>
						<div class="clear"></div>                    		
						
						<span><?php _e('First Name :','hotel');?>*</span>
						<div class="inputStyle">
							<input type="text" name="first_name" id="first_name" value="<?php if(get_user_meta($uid,'first_name',true) != '') echo  get_user_meta($uid,'first_name',true); else if(isset($_POST['first_name'])) echo $_POST['first_name'];?>" style="width:300px;"/>					
						</div>
						<div class="clear"></div>
						
						<span><?php _e('Last Name :','hotel');?>*</span>
						<div class="inputStyle">
							<div class="textareaTop"></div>
							<div class="textareaMiddle">
							<input type="text" name="last_name" id="last_name" value="<?php if(get_user_meta($uid,'last_name',true) != '') echo  get_user_meta($uid,'last_name',true); else if(isset($_POST['last_name'])) echo $_POST['last_name'] ?>" style="width:300px;"/>	
							</div>
							<div class="textareaBottom"></div>					
						</div>
						<div class="clear"></div>
                        
                        <span><?php _e('Email :','hotel');?>*</span>
						<div class="inputStyle">
							<div class="textareaTop"></div>
							<div class="textareaMiddle">
							<input type="text" name="email" id="email" value="<?php if( !empty($user) && $user->user_email != '') echo $user->user_email; else if(isset($_POST['email'])) echo  $_POST['email']; ?>" style="width:300px;"/>	
							</div>
							<div class="textareaBottom"></div>					
						</div>
						<div class="clear"></div>
                        
                        <span><?php _e('Phone :','hotel');?>*</span>
						<div class="inputStyle">
							<div class="textareaTop"></div>
							<div class="textareaMiddle">
							<input type="text" name="phone" id="phone" value="<?php if(get_user_meta($uid,'tgt_customer_phone',true) != '') echo  get_user_meta($uid,'tgt_customer_phone',true); else if(isset($_POST['phone'])) echo $_POST['phone']; ?>" style="width:300px;"/>	
							</div>
							<div class="textareaBottom"></div>					
						</div>
                        <?php $cus_add = get_user_meta($uid,'tgt_customer_address',true); ?> 
						<div class="clear"></div>                        
                    <span><?php _e('Country :','hotel');?>*</span>
						<div class="inputStyle">
							<div class="textareaTop"></div>
							<div class="textareaMiddle">
<?php
       $country = tgt_get_countries();      
       ?>
                            <select name="country" id="country" >
                            <?php
       foreach ($country['countries'] as $k=>$v)
       {
       ?>
                             <option value="<?php echo $v; ?>" <?php if($cus_add['country'] == $v)
                                        echo 'selected="selected"';
                                   if( isset($_POST['country']) && $_POST['country']== $k)
                                        echo 'selected="selected"'; ?>><?php echo $v; ?>
                             </option>
                            <?php
       }
       ?>           
                            </select>							
							</div>
							<div class="textareaBottom"></div>					
						</div>
						<div class="clear"></div>                        
                        <span><?php _e('City/State :','hotel');?>*</span>
                        <div class="inputStyle">
                            <div class="textareaTop"></div>
                            <div class="textareaMiddle" id="state">
                            <input type="text" name="state" id="state" value="<?php if($cus_add['state'] != '') echo  $cus_add['state']; else if(isset($_POST['state']))  echo $_POST['state']; ?>" style="width:300px;"/>	
                            </div>
                            <div class="textareaBottom"></div>					
                        </div>
                        <div class="clear"></div>                        
                        <span><?php _e('Street :','hotel');?>*</span>
						<div class="inputStyle">
							<div class="textareaTop"></div>
							<div class="textareaMiddle">
							<input type="text" name="street" id="street" value="<?php if($cus_add['street'] != '') echo $cus_add['street']; else if(isset($_POST['street'])) echo $_POST['street']; ?>" style="width:300px;"/>	
							</div>
							<div class="textareaBottom"></div>					
						</div>
						<div class="clear"></div>  
					</div>	               
<script type="text/javascript">
function get_room(type_id)
{	
	
	var date_in = document.getElementById('start-date').value;
	var date_out = document.getElementById('end-date').value;	
	if(date_in == '...' || date_out == '...'){	
		document.getElementById('room_type').value = 'select';
		alert('<?php _e('Please choose check in date and check out date first !','hotel') ;?>'); 
	}else{
		
		if (window.XMLHttpRequest)
		{// code for IE7+, Firefox, Chrome, Opera, Safari
			xmlhttp=new XMLHttpRequest();
		}
		else
		{// code for IE6, IE5
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange=function()
		{
			if (xmlhttp.readyState==4 && xmlhttp.status==200)
			{					
				var res = xmlhttp.responseText;	
				
				if(res != '' && res != 'nothing')
				{	
					document.getElementById("room").innerHTML = res;
					document.getElementById('show_room').style.display = '';
				}else if(res =='')
				{
					document.getElementById("room").innerHTML = '<font color="red"><em><?php _e('Empty Room !','hotel');?></em></font>';
					document.getElementById('show_room').style.display = '';
				}else if(res =='nothing')
				{
					document.getElementById('show_room').style.display = 'none';
				}
			}
		};			
		var queryString = "?type_id="+ type_id + "&date_in=" + date_in + "&date_out=" + date_out + "&u_id=<?php echo $uid; ?>" ;	
		
		xmlhttp.open("GET", "<?php echo TEMPLATE_URL. "/ajax/ajax_get_room.php"?>"+queryString, true);
		xmlhttp.send(); 
	}
}
	
</script> 
                    <div style="margin-bottom: 20px;width:660px;float:left;margin-left:30px;">	
                    	<h3><?php _e('Reservation Informations: ','hotel');?>*</h3>	
                        <?php
						if($editbooking == 'true') 
						{
							$in = '';
							$out = '';
							if(!empty($book_arr))
							{
								$in = date('m/d/Y',$book_arr[0]->check_in);
								$out= date('m/d/Y',$book_arr[0]->check_out);
							}						
						?>
						<?php $selected_service = get_user_meta($book_arr[0]->user_ID, 'tgt_service',true); 	
						$room_service = get_post_meta($_GET['pid'],META_ROOMTYPE_SERVICES,true);					
						
						
						
						?>
                    	<div style="float:left;">
                        <table style="width:660px;" class="widefat post">
                        	<thead>
                            	<tr>
                                	<th>
                                    <?php _e('Room(s)','hotel'); ?>
                                    </th>
                                    <?php if (!empty($selected_service) && is_array($selected_service)){ ?>
                                    <th>
                                    <?php _e('Service(s)','hotel'); ?>
                                    </th>
                                    <?php } ?>
                                    <th>
                                    <?php _e('Room Type','hotel'); ?>
                                    </th>
                                    <th>
                                    <?php _e('Check In','hotel'); ?>
                                    </th>
                                    <th>
                                    <?php _e('Check Out','hotel'); ?>
                                    </th>
                                    <th>
                                    <?php _e('Book Date','hotel'); ?>
                                    </th>
                                </tr>
                                <?php if(!empty($book_arr)) { ?>
                                <tr>
                                	<td>
                                    <?php
                                    $room_name = '';
									$q_room="SELECT r.room_name FROM $wpdb->users u , $tb_bookings b , $tb_rooms r , $wpdb->posts p WHERE u.ID = b.user_ID AND b.room_ID = r.ID AND r.room_type_ID = p.ID AND u.ID = '".$book_arr[0]->user_ID."' ";	
									$list_rooms= $wpdb->get_results( $q_room );
									foreach($list_rooms as $k=>$v)
									{
										$room_name .= $v->room_name.', ';
									}
									echo trim($room_name,', ');
									?>
                                    </td>
                                    <?php if (!empty($selected_service) && is_array($selected_service)){ ?>
                                    <td>
                                    <?php
                                   
									foreach($selected_service as $k=>$v)
									{
										$service_name .= $room_service[$v]['name'].', ';
									}
									echo trim($service_name,', ');
									?>
                                    </td>
                                    <?php } ?>
                                    <td>
                                    <?php echo $book_arr[0]->post_title; ?>
                                    </td>
                                    <td>
                                    <?php echo $in; ?>
                                    </td>
                                    <td>
                                    <?php echo $out; ?>
                                    </td>
                                    <td>
                                    <?php 
									$date_sigup = explode(' ' ,$book_arr[0]->user_registered);
									echo date('m/d/Y',strtotime($date_sigup[0]));
									?>
                                    </td>
                                </tr>
                                <tr>
                                	<td style="background-color:#9FC" colspan="2">
                                    <?php _e('Paid Total','hotel'); ?>:
                                    <?php
                                    $total = get_user_meta($book_arr[0]->user_ID,'tgt_total_price',true);									
									if(!empty($total))
										echo $total .$currencysymbol;
									else
										echo '0 '.$currencysymbol;
									?>
                                    </td>
                                    <td colspan="3">
                                    
                                    </td>
                                </tr>
                                <?php } ?>
                            </thead>
                        </table>
                        </div>  
						<div style="float:left;">
                    	<?php		
							echo '<h3>'.__('Change reservation','hotel').':</h3>';
							echo '<div>';
						}
						?>
                        
						
						<script type="text/javascript">
							jQuery(document).ready(function($){
								$('.start-date-pick').datepicker({minDate: new Date() } );
								});
						</script>
                    	<div style="float:left;">
                            <span><?php _e('Check In :','hotel');?>*</span>
                            <div class="inputStyle">
                                <div class="textareaTop"></div>
                                <div class="textareaMiddle">
                                <input type="text" readonly="readonly" id="start-date" class="check start-date-pick" name="Checkin" value="<?php if(isset($_POST['Checkin']) && $_POST['Checkin'] != '') echo $_POST['Checkin']; else echo '...'; ?>"  style="width:200px;"/>	
                                </div>
                                <div class="textareaBottom"></div>					
                            </div>
                        </div>
                        						
                        <div style="float:left;margin-left:10px;">
                            <span><?php _e('Check Out :','hotel');?>*</span>
                            <div class="inputStyle">
                                <div class="textareaTop"></div>
                                <div class="textareaMiddle">
                                <input type="text" readonly="readonly" id="end-date" class="check start-date-pick" name="Checkout" value="<?php if(isset($_POST['Checkout']) && $_POST['Checkout'] != '') echo $_POST['Checkout']; else echo '...'; ?>"  style="width:200px;"/>
                                </div>
                                <div class="textareaBottom"></div>					
                            </div>
                        </div>                        
						<div class="clear"></div>  		
                        <?php
						$args = array(							
							'post_status' => 'publish',								
							'post_type' => 'roomtype',
							'posts_per_page' => -1,
							'order' => 'ASC'
							);
						$post_arr= query_posts($args);	
						$q_s="SELECT DISTINCT r.room_type_ID FROM ".$wpdb->prefix."bookings b , ".$wpdb->prefix."rooms r WHERE b.user_ID = '$uid' AND b.room_ID = r.ID";
						$room_arr = $wpdb->get_results( $q_s );
						?>  				
                        <span><?php _e('Room Type :','hotel');?></span>
						<div class="inputStyle">
							<div class="textareaTop"></div>
							<div class="textareaMiddle" id="block">                            	
								<select name="room_type" id="room_type" onclick="get_room(this.value);" onchange="get_room(this.value);"  style="width:auto;" >
                                	<option value="select"><?php _e('Select ...','hotel'); ?></option>    	                          	<?php 
									if ( !empty($post_arr) ) 
									{ 
										foreach ($post_arr as $k=>$v)
										{
											echo '<option value="'.$v->ID.'"';
											
											echo '>';
											echo $v->post_title; 
											if (get_post_meta($v->ID, META_ROOMTYPE_PRICE,true)>0){
											echo ' ( '.$currencysymbol . get_post_meta($v->ID, META_ROOMTYPE_PRICE,true).' )';
											}
											echo '</option>';
										}
									}
									?> 										
                                </select>                               
                                
					    </div>
							<div class="textareaBottom"></div>					
						</div>
						<div class="clear"></div>                          
                        <div id="show_room" style="display: none;">                            
                            <div class="inputStyle">
                                <div class="textareaTop"></div>
                                <div class="textareaMiddle" id="room">                                                                   
                                </div>
                                <div class="textareaBottom"></div>					
                            </div>
                            <div class="clear"></div>                     
                    	</div>
                    	 <span><?php _e('Total Price','hotel');?> :</span>
                    	 <div class="inputStyle">
                                <div class="textareaTop"></div>
                                <div class="textareaMiddle">  
                                <input type="text" name="total_price" id="total_price" value="<?php echo (isset($_POST['total_price']))?$_POST['total_price']:get_user_meta($uid, 'tgt_total_price',true); ?>" /> <?php echo $currencysymbol; ?>                                                                 
                                </div>
                                <div class="textareaBottom"></div>					
                            </div>
                           
                    </div>	            	                 
				</div>
			  </div>                                
		</div>                   
		<div class="cl"></div>
	</div>
	<br>	
</div>
<div>
	<input id="submit_go" style="cursor:pointer;" class="button" type="submit" value="<?php echo ($editbooking == 'true')?__('Update','hotel'):__('Save','hotel'); ?>" />
</div>
</div>
</form>