<?php
$currency = get_option('tgt_currency');
	if ( $currency == "USD" || $currency == "AUD" || $currency == "CAD" || $currency == "NZD" || $currency == "HKD" || $currency == "SGD" ) { $currencysymbol = "$"; }
	else if ( $currency == "GBP" ) { $currencysymbol = "&pound;"; }
	else if ( $currency == "JPY" ) { $currencysymbol = "&yen;"; }
	else if ( $currency == "EUR" ) { $currencysymbol = "&euro;"; }
	else { $currencysymbol = $currency; }
$message = '';
if(!empty($_COOKIE['message']))
	$message = $_COOKIE['message']; //message from add_room.php
setcookie("message", $message, time()-3600);
//Check if the apply form has been submited
if(isset($_POST['applysubmitted']) && !empty($_POST['applysubmitted'])){
	//Get form data
	global $wpdb;
	$u_id= $_POST['post'];
	$tb_bookings = $wpdb->prefix.'bookings';
	if($_POST['actionapplytop']=="publish")
	{		
		for($i=0;$i<count($u_id);$i++)
		{
			$wpdb->update( "$tb_bookings" , array( 'status' => 'publish'), array( 'user_ID' => "$u_id[$i]" ));
			$u_data = get_userdata($u_id[$i]);
			if(get_user_meta($u_id[$i],'tgt_paycash',true) == 'yes')
			{
				$check = '';				
				$u_code = get_user_meta($u_id[$i],'tgt_customer_code',true);		
				$subject = get_option('tgt_mailsubject');
            
				$websitename = get_bloginfo('name');
				
				$u_code = get_user_meta($u_id[$i],'tgt_customer_code',true);
				$first_name = get_user_meta($u_id[$i],'first_name',true);
				$last_name = get_user_meta($u_id[$i],'last_name',true);
				
				$link_see= HOME_URL.'?action=check&code='.$u_code;

				$message_mail = get_option('tgt_mailcontent');
				$fullname = $first_name.' '.$last_name;
				$message_mail = str_replace("[buyer_name]", "".$fullname, $message_mail);

				$message_mail = str_replace("[website_name]", $websitename, $message_mail);

				$message_mail = str_replace("[booking_link]", $link_see, $message_mail);
				
				$to = $u_data->user_email;

				$to_name = $fullname ;

				$sub = $subject;

				$mes = $message_mail;

				$from = get_option( 'tgt_mailfrom' );
				$head = "From $websitename";					
				$check = wp_mail( $to, $sub, $mes, $head);
				if($check == true)
				{
					delete_user_meta($u_id[$i],'tgt_paycash');
				}
			}
			global $wpdb;
			$sql = "SELECT room_ID FROM " . $tb_bookings . " WHERE user_ID='" . $u_id[$i] . "'";
			$results = $wpdb->get_results($sql);                       
			if(!empty($results))
			{
				update_promotion_used($results[0]->room_ID);
			}
			$arg = array('booking_id'	=>	$u_id[$i],
						'customer_id'	=>	$u_id[$i],
						'date'	=>	date('Y-m-d',strtotime($u_data->user_registered)),
						'amount'	=>	get_user_meta($u_id[$i], 'tgt_total_price', true),
						'currency'	=>	$currencysymbol);
			doInsert($arg);
                        update_promotion_used($u_id[$i], 'publish');
		}
	}
	if($_POST['actionapplytop']=="pending")
	{	
		for($i=0;$i<count($u_id);$i++)
		{
			$wpdb->update( "$tb_bookings" , array( 'status' => 'pending'), array( 'user_ID' => "$u_id[$i]" ));		
		}
	}
	if($_POST['actionapplytop']=="deleted")
	{			
		for($i=0;$i<count($u_id);$i++)
		{
			$wpdb->update( "$tb_bookings" , array( 'status' => 'deleted'), array( 'user_ID' => "$u_id[$i]" ));
                        update_promotion_used($u_id[$i], 'deleted');
		}
	}	
	$message .= __("Action apply successful !","hotel");
}
$dayfilter = '';
$date_in = '';
$date_out = '';
$status = '';
if(isset($_GET['c_in']))
	$date_in = $_GET['c_in'];
if(isset($_GET['c_out']))
	$date_out= $_GET['c_out'];
if(isset($_GET['status']))
	$status  = $_GET['status'];	

$sortby = 'all';
if(isset($_POST['sort_by']) && !empty($_POST['sort_by'])){		
	if($_POST['sorttop'] != "all")
	{		
		$sortby = $_POST['sorttop'];
	}
	else
		$sortby = 'all';	
}

if(isset($_POST['searchsubmitted']) && !empty($_POST['searchsubmitted']))
{
	global $wpdb;
	$tb_bookings = $wpdb->prefix.'bookings';
	$tb_rooms = $wpdb->prefix.'rooms';
	$q_s="
	SELECT b.user_ID , b.check_in , b.check_out , b.status , b.ID , p.post_title , r.room_type_ID, u.user_registered 
	FROM $wpdb->users u , $wpdb->usermeta um, $tb_bookings b , $tb_rooms r , $wpdb->posts p 
	WHERE u.ID = b.user_ID 
			AND u.ID = um.user_id 
			AND b.room_ID = r.ID 
			AND r.room_type_ID = p.ID 
			AND b.status IN ('publish','pending','deleted') 
			AND um.meta_key IN ('first_name','last_name') 
			AND ( um.meta_value LIKE '%".$_POST['searchtext']."%' )GROUP BY u.ID ORDER BY u.user_registered DESC";
	$post_arr= $wpdb->get_results( $q_s );
}
else 
{	
	$post_arr ='';
	
	global $wpdb;
	if($sortby == 'all'  && $status =='' && $date_out =='' && $date_in == '')
	{
		$tb_bookings = $wpdb->prefix.'bookings';
		$tb_rooms = $wpdb->prefix.'rooms';
		$q_s="
		SELECT b.user_ID , b.check_in , b.check_out , b.status , b.ID , p.post_title , r.room_type_ID, u.user_registered 
		FROM $wpdb->users u , $tb_bookings b , $tb_rooms r , $wpdb->posts p 
		WHERE u.ID = b.user_ID 
			AND b.room_ID = r.ID 
			AND r.room_type_ID = p.ID 
			AND b.status IN ('publish','pending','deleted') GROUP BY u.ID ORDER BY u.user_registered DESC";		
		$post_arr= $wpdb->get_results( $q_s );
	}
	if ($sortby != 'all' && $status =='' && $date_out =='' && $date_in == '')
	{			
		global $wpdb;
		$tb_bookings = $wpdb->prefix.'bookings';
		$tb_rooms = $wpdb->prefix.'rooms';
		$q_s="
		SELECT b.user_ID , b.check_in , b.check_out , b.status , b.ID , p.post_title , r.room_type_ID, u.user_registered 
		FROM $wpdb->users u , $tb_bookings b , $tb_rooms r , $wpdb->posts p 
		WHERE u.ID = b.user_ID 
			AND b.room_ID = r.ID 
			AND r.room_type_ID = p.ID 
			AND ( b.status = '$sortby')  GROUP BY u.ID ORDER BY u.user_registered DESC";	
		$post_arr= $wpdb->get_results( $q_s );
	}
	if($status !='' && $sortby == 'all' && $date_out =='' && $date_in == '')
	{		
		global $wpdb;
		$tb_bookings = $wpdb->prefix.'bookings';
		$tb_rooms = $wpdb->prefix.'rooms';
		$q_s="
		SELECT b.user_ID , b.check_in , b.check_out , b.status , b.ID , p.post_title , r.room_type_ID, u.user_registered 
		FROM $wpdb->users u , $tb_bookings b , $tb_rooms r , $wpdb->posts p 
		WHERE u.ID = b.user_ID 
			AND b.room_ID = r.ID 
			AND r.room_type_ID = p.ID 
			AND ( b.status = '$status') GROUP BY u.ID ORDER BY u.user_registered DESC";	
		$post_arr= $wpdb->get_results( $q_s );
	}
	if(($date_out !='' || $date_in != '') && $sortby == 'all' && $status =='')
	{		
		global $wpdb;
		$tb_bookings = $wpdb->prefix.'bookings';
		$tb_rooms = $wpdb->prefix.'rooms';
		$q_s="
		SELECT b.user_ID , b.check_in , b.check_out , b.status , b.ID , p.post_title , r.room_type_ID, u.user_registered 
		FROM $wpdb->users u , $tb_bookings b , $tb_rooms r , $wpdb->posts p 
		WHERE u.ID = b.user_ID 
			AND b.room_ID = r.ID 
			AND r.room_type_ID = p.ID 
			AND  (b.check_out = '$date_out' OR b.check_in = '$date_in' ) GROUP BY u.ID ORDER BY u.user_registered DESC";	
		$post_arr= $wpdb->get_results( $q_s );
	}
}
?>
<script src="<?php echo TEMPLATE_URL; ?>/js/paginate_profile.js" type="text/javascript" charset="utf-8"></script>
<div class="wrap">	
	<?php the_support_panel(); ?>
	<br>
    <?php
	if ($message) echo '<div class="updated below-h2">'.$message.'</div>';
	?> 
	<div class="settings" style="margin: 0px 0px 0px 0px; padding-top: 0px 0px 0px 0px;">
		<div class="heading">
			<h3 style="padding-top:10px;"><?php _e('Reservations','hotel');?></h3>
			<div class="cl"></div>
		</div>
		<div class="item" style="padding: 15px 0 5px 5px; height:100%;">
<style>
.item a {text-decoration:none;}
</style>
			<div class="left" style="width:100%; text-transform: none;">
<div style="float:left; margin-bottom:7px; width:100%;">

<form method="post" name="sort_booking">
<div style="float:left;" style="float:left; padding-right:10px; margin-top:3px;">

<select name="actionapplytop" id="actionapplytop">
	<option value="bulkactions"><?php _e('Bulk Actions','hotel');?></option>
	<option value="publish"><?php _e('Publish','hotel');?></option>
	<option value="pending"><?php _e('Pending','hotel');?></option>
	<option value="deleted"><?php _e('Move to trash','hotel');?></option>
</select>
&nbsp;
<input type="submit" name="applysubmitted"  class="button"  style="line-height:12px;cursor:pointer;" value="<?php _e('Apply','hotel');?>"/>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<select name="sorttop" id="sorttop" style="width:120px;">
	<option value="all"><?php _e('All','hotel');?></option>
    <option value="publish"><?php _e('Publish','hotel');?></option>
    <option value="pending"><?php _e('Pending','hotel');?></option>
    <option value="deleted"><?php _e('Trash','hotel');?></option>										
</select>
<input type="submit" name="sort_by" id="sort_by"  class="button"  style="line-height:12px;cursor:pointer;" value="<?php _e('Sort by','hotel');?>"/>
<script language="javascript">
{
	for(var i=0;i<document.sort_booking.sorttop.length;i++){
		if(document.sort_booking.sorttop[i].value=="<?php echo  $sortby; ?>"){
			document.sort_booking.sorttop.selectedIndex=i;
			break;
		}
	}													
}
</script>
</div>

<div style="float:right; padding-right:10px;">
<input type="submit"  class="button" style="line-height:12px;cursor:pointer;" name="searchsubmitted" value="<?php _e('Search','hotel');?>"/>
</div>
<div style="float:right; padding-right:10px;">
<input type="text" name="searchtext" id="searchtext" onclick="this.value='';" style="line-height:12px; width:225px;" value="<?php _e('Search customer name ...','hotel');?>"/>
</div>

</div>




<table class="widefat post fixed" width="100%" cellpadding="0" id="listing_room" border="0">
<thead>
<tr>
<th class="check-column"><input type="checkbox" style="width:14px; height:14px;" /></th>
<th width="16%" style=""><?php _e('Customer','hotel');?></th>
<th width="14%" style=""><?php _e('Rooms','hotel');?></th>
<th width="14%" style=""><?php _e('Services','hotel');?></th>
<th width="14%" style=""><?php _e('Type','hotel');?></th>
<th width="12%" style=""><?php _e('Check In','hotel');?></th>
<th width="12%" style=""><?php _e('Check Out','hotel');?></th>
<th width="12%" style=""><?php _e('Book Date','hotel');?></th>
<th width="8%" style=""><?php _e('Total','hotel');?></th>
<th width="10%" style=""><?php _e('Status','hotel');?></th>
</tr>
</thead>
<tbody>
<?php
$userdata = "";						
if ( !empty($post_arr) ) 
{
	for($n=0; $n<count($post_arr); $n++ )
	{	
		$selected_service = get_user_meta($post_arr[$n]->user_ID, 'tgt_service',true); 	
		//$room_service = get_post_meta($post_arr[$n]->room_type_ID,META_ROOMTYPE_SERVICES,true);
		
			echo '<tr height="30">';	
			echo '<th class="'.($n%2==0?'alternate':'').' check-column" valign="top" style="font-size:11px;"><input  type="checkbox" name="post[]" value="'.$post_arr[$n]->user_ID.'" style="width:14px; height:14px;" /></th>';
			$c_title = get_user_meta($post_arr[$n]->user_ID,'tgt_customer_title',true);	
			$c_f_name = get_user_meta($post_arr[$n]->user_ID,'first_name',true);
			$c_l_name = get_user_meta($post_arr[$n]->user_ID,'last_name',true);
			echo '<th class="'.($n%2==0?'alternate':'').'" valign="top" style="font-size:11px;">'.'<a href="admin.php?page=my-submenu-handle-add-booking&editbooking=true&uid='.$post_arr[$n]->user_ID.'&pid='.$post_arr[$n]->room_type_ID.'">'.$c_title.' '.$c_f_name.' '.$c_l_name.'</a>'.'</th>';
			$room_name = '';
			$q_room="SELECT r.room_name FROM $wpdb->users u , $tb_bookings b , $tb_rooms r , $wpdb->posts p WHERE u.ID = b.user_ID AND b.room_ID = r.ID AND r.room_type_ID = p.ID AND u.ID = '".$post_arr[$n]->user_ID."' ";	
			$list_rooms= $wpdb->get_results( $q_room );
			foreach($list_rooms as $k=>$v)
			{
				$room_name .= $v->room_name.', ';
			}			
			echo '<th class="'.($n%2==0?'alternate':'').'" valign="top" style="font-size:11px; font-weight:normal;">'.trim($room_name,', ').'</th>';		
			$service_name = '';
			if (isset($selected_service) && is_array($selected_service)){
				foreach($selected_service as $k=>$v)
				{	
					
						$service_name .= $k.', ';
					
				}
			}
			
			echo '<th class="'.($n%2==0?'alternate':'').'" valign="top" style="font-size:11px; font-weight:normal;">'.trim($service_name,', ').'</th>';
			
			echo '<th class="'.($n%2==0?'alternate':'').'" valign="top" style="font-size:11px; font-weight:normal;">'.$post_arr[$n]->post_title.'</th>';	
					
			echo '<th class="'.($n%2==0?'alternate':'').'" valign="top" style="font-size:11px; font-weight:normal;">'.'<a style="color:#555555;" href="admin.php?page=my-submenu-list-booking&c_in='.$post_arr[$n]->check_in.'">'.date('m-d-Y',$post_arr[$n]->check_in).'</th>';		
			
			echo '<th class="'.($n%2==0?'alternate':'').'" valign="top" style="font-size:11px; font-weight:normal;">'.'<a style="color:#555555;" href="admin.php?page=my-submenu-list-booking&c_out='.$post_arr[$n]->check_out.'">'.date('m-d-Y',$post_arr[$n]->check_out).'</a></th>';			
			
			$date_sigup = explode(' ' ,$post_arr[$n]->user_registered);
			echo '<th class="'.($n%2==0?'alternate':'').'" valign="top" style="font-size:11px; font-weight:normal;">'.date('m-d-Y',strtotime($date_sigup[0])).'</th>';
			
			$total_price = $currencysymbol . 0;
			if (get_user_meta($post_arr[$n]->user_ID, 'tgt_total_price', true) != ''){
				$total_price = $currencysymbol . get_user_meta($post_arr[$n]->user_ID, 'tgt_total_price', true);
			}
			
			echo '<th class="'.($n%2==0?'alternate':'').'" valign="top" style="font-size:11px; font-weight:normal;">'.$total_price.'</th>';
			
			$sta = $post_arr[$n]->status;
			if($post_arr[$n]->status == 'deleted')
				$sta = 'trash';			
			echo '<th class="'.($n%2==0?'alternate':'').'" valign="top" style="font-size:11px; font-style:italic;">'.'<a style="color:#555555;" href="admin.php?page=my-submenu-list-booking&status='.$post_arr[$n]->status.'">'.$sta.'</a>'.'</th>';
				
			echo '</tr>';
	}
}else{
	echo '<tr height="30">';
	echo '<td >'.''.'</td>';
	echo '<td>'.__('No reservation has been found','hotel').'</td>';
	echo '<td >'.''.'</td>';
	echo '<td >'.''.'</td>';
	echo '<td >'.''.'</td>';
	echo '<td >'.''.'</td>';
	echo '<td >'.''.'</td>';
	echo '<td >'.''.'</td>';
	echo '</tr>';	
}
?>
</tbody>
<tfoot>
<tr>
<th class="check-column"><input type="checkbox" style="width:14px; height:14px;" /></th>
<th style=""><?php _e('Customer','hotel');?></th>
<th style=""><?php _e('Rooms','hotel');?></th>
<th style=""><?php _e('Services','hotel');?></th>
<th style=""><?php _e('Type','hotel');?></th>
<th style=""><?php _e('Check In','hotel');?></th>
<th style=""><?php _e('Check Out','hotel');?></th>
<th style=""><?php _e('Book Date','hotel');?></th>
<th style=""><?php _e('Total','hotel');?></th>
<th style=""><?php _e('Status','hotel');?></th>
</tr>
</tfoot>
</table>
<div style="float:left; margin-top:7px; width:100%;">

<div style="float:right; margin-top:10px;">
<div class="pg-content" id="pageNavPosition"></div>
<?php //echo $page_div_str;?>
</div>
</div>
</form>
</div>
<div class="clear"></div>
</div>
</div>
</div>
<script type="text/javascript">
var pager = new Pager('listing_room', 20); 
pager.init(); 
pager.showPageNav('pager', 'pageNavPosition'); 
pager.showPage(1);
</script>