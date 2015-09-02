<?php
	$message = '';
	$errors = '';
	if (!empty($_COOKIE['message']))
	$message = $_COOKIE['message']; //message from add_room.php
	setcookie("message", '', time()-3600);	
	if (!empty($_COOKIE['error']))$errors = $_COOKIE['error']; //error from add_room.php
	setcookie("error", '', time()-3600);	
	
	global $wpdb;
	$roomstb = $wpdb->prefix . 'rooms';
	$poststb = $wpdb->prefix . 'posts';
	
	if (!empty($_POST['apply_by'])){	//if click Apply button
		if (empty($_POST['room']))
			$rooms = '';
		else $rooms =  implode ($_POST['room'], ', ');	
		$status = $_POST['apply_var'];	
		
		$q = "UPDATE $roomstb
			SET `status` = '$status'
			WHERE ID IN ($rooms)";
		$wpdb->get_results($q);
		$message .= "<p>Update status successful!</p>";
	}
	
	if (!empty($_POST['filter_val_room_type'])) // if have roomtype id for filter
		$filter_by_room_type = "AND $roomstb.room_type_ID = " . intval($_POST['filter_val_room_type']);
	else $filter_by_room_type = '';
	
	if (isset($_POST['filter_val_status']))
		$status = $_POST['filter_val_status'];
	else $status = '';
	if (empty($status)) $status = 'publish';
	$filter_by_status = "AND $roomstb.status = '$status'";
	
	$q = "
		SELECT $roomstb.ID, $roomstb.room_name, $poststb.post_title AS room_type, $roomstb.status
		FROM $roomstb , $poststb 
		WHERE $roomstb.room_type_ID = $poststb.ID
		AND $poststb.post_status='publish'
	";
	$q .= ' ' . $filter_by_room_type;
	$q .= ' ' . $filter_by_status;

	$rooms = $wpdb->get_results($q, 'OBJECT'); // get all room	
	
	$q = "
		SELECT ID, post_title AS room_type
		FROM $poststb
		WHERE post_type='roomtype' AND post_status='publish'
	";
	$roomtypes = $wpdb->get_results($q); //get all room type
?>

<style>
	.item a {text-decoration:none;}
</style>

<div class="wrap"> <!-- #wrap -->
	<div class="atention">
		<strong><?php _e('Problems? Questions?','hotel');?></strong><?php _e(' Contact us at','hotel');?> <em><a href="http://www.dailywp.com/support/">Support</a></em>. 
	</div>
	<br/>
	<?php
	if ($message) echo '<div class="updated below-h2">'.$message.'</div>';
	elseif ($errors) echo '<div class="error">'.$errors.'</div>';
	?>
	<div class="settings" style="margin: 0px;"> <!-- #settings -->
		<div class="heading"> <!-- #header -->
			<h3 style="padding-top: 10px;"><?php _e ('List Rooms', 'hotel');?></h3>
			<div class="c1"></div>
		</div> <!-- //header -->
		<div class="item" style="padding: 15px 0pt 5px 5px;"> <!-- #item: list  -->	
			<form action="" method="post"> <!-- form filter -->		
				<div class="left" style="width:100%; text-transform: none;">
					
					<select name="apply_var">
						<option value="publish"><?php _e ('Publish', 'hotel');?></option>
						<option value="pending"><?php _e ('Pending', 'hotel');?></option>
						<option value="deleted"><?php _e ('Move to Trash', 'hotel');?></option>
					</select>
					<input type="submit"  class="button" name="apply_by" value="<?php _e('Apply', 'hotel');?>"/>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<select name="filter_val_room_type" id="filter_val_room_type" style="width:120px;">
						<option value=""><?php _e ('All roomtype','hotel');?></option>
						<?php
						foreach ($roomtypes as $roomtype)
						{ 
						?>
						<option value="<?php echo $roomtype->ID;?>" <?php if($_POST['filter_val_room_type']==$roomtype->ID) echo 'selected="selected"';?> >
							<?php echo $roomtype->room_type;?>
						</option>		
						<?php
						} 
						?>									
					</select>
					
					<select name="filter_val_status" id="filter_val_status" style="width:120px;">						
						<option value="publish" <?php if($_POST['filter_val_status']=='publish')echo 'selected="selected"';?>><?php _e('Publish','hotel');?></option>
						<option value="pending" <?php if($_POST['filter_val_status']=='pending')echo 'selected="selected"';?>><?php _e('Pending','hotel');?></option>
						<option value="deleted" <?php if($_POST['filter_val_status']=='deleted')echo 'selected="selected"';?>><?php _e('Trash','hotel');?></option>										
					</select>
					<input type="submit"  class="button" name="filter_by" style="line-height:12px;" value="<?php _e ('Filter','hotel');?>"/>
					
				</div>
				
				<table class="widefat post fixed" width="100%" cellpadding="0" border="0"> <!-- list rooms -->
					<thead> <!-- hearder -->
						<tr>
							<th class="check-column"><input type="checkbox" style="width:14px; height:14px;" /></th>
							<th width="10%" style=""><?php _e('ID','hotel');?></th>
							<th width="25%" style=""><?php _e('Room Name','hotel');?></th>
							<th width="25%" style=""><?php _e('Room Type','hotel');?></th>
							<th width="40%" style=""><?php _e('Status','hotel');?></th>
						</tr>
					</thead> <!-- //header -->
					<tbody>
						
						<?php
							foreach ($rooms as $room)
							{	 
						?>
						<tr height="30">
							<th class="alternate check-column" valign="top" style="font-size:11px;">
								<input  type="checkbox" name="room[]" value="<?php echo $room->ID;?>" style="width:14px; height:14px;" />
							</th>
							<th class="alternate" valign="top" style="font-size:11px;">							
								<?php echo $room->ID;?>						
							</th>
							<th class="alternate" valign="top" style="font-size:11px;">
								<a href="admin.php?page=my-submenu-handle-add-room&room_id=<?php echo $room->ID;?>">
									<?php echo $room->room_name;?>
								</a>
							</th>						
							<th class="alternate" valign="top" style="font-size:11px; font-weight:normal;">							
									<?php echo $room->room_type;?>							
							</th>
							<th class="alternate" valign="top" style="font-size:11px; font-weight:normal;">														
									<?php
									if ($room->status == 'publish') _e ('publish', 'hotel');
									elseif ($room->status == 'pending') _e ('pending', 'hotel');
									else _e ('trash', 'hotel');
									?>							
							</th>
						</tr>	
						<?php
							 } 
						?>					
						

					</tbody>
					<tfoot> <!-- footer -->
						<tr>
							<th class="check-column"><input type="checkbox" style="width:14px; height:14px;" /></th>
							<th style=""><?php _e('ID','hotel');?></th>
							<th style=""><?php _e('Room Name','hotel');?></th>
							<th style=""><?php _e('Room Type','hotel');?></th>
							<th style=""><?php _e('Status','hotel');?></th>
						</tr>
					</tfoot> <!-- //footer -->
				</table> <!-- //list rooms -->
				
				<div class="clear"></div>
			</form> <!-- // form sort -->
		</div> <!-- //item -->
	</div> <!-- //settings -->
</div> <!-- //end wrap -->