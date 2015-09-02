<?php	
	global $wpdb;
	$roomstb = $wpdb->prefix . 'rooms';
	$poststb = $wpdb->prefix . 'posts';
	$room = '';
	//$q = "
	//	SELECT ID, post_title AS room_type
	//	FROM $poststb
	//	WHERE post_type='roomtype' AND post_status='publish'
	//";
	//$roomtypes = $wpdb->get_results($q); //get all room type
	
	$roomtypes = get_posts(array(
									'post_type' => 'roomtype',
									'post_status' => 'publish',
									'nopaging' => true,
									'suppress_filters' => false
							));
	$errors = array();
	if (!empty($_POST['submit'])){ //if submit (new room or edit room)
		if (empty($_POST['room_type']))$errors['room_type'] = "Please choose 'room type'!";
		if (empty($_POST['room_name']))$errors['room_name'] = "Room name is not null!'";
		
		/*$id = intval ($_REQUEST['room_id']);
		$q = "SELECT $roomstb.room_name FROM $roomstb WHERE $roomstb.room_name = '".trim($_POST['room_name'])."' AND $roomstb.ID != $id";

		$result = $wpdb->get_var($q);
		if (!empty($result)) $errors['room_name'] = "Room name exist!'";*/
		
		
		if (!count($errors)){
			if ($_POST['room_available'] == 'on')		
				$status = 'publish';
			else $status = 'pending';			
	
			$q = "REPLACE INTO $roomstb
				(ID, room_name, room_type_ID, status)
				VALUES ('{$_POST['room_id']}','{$_POST['room_name']}', {$_POST['room_type']}, '$status');
			";
		
		
			$wpdb->get_var($q);
			
			
			if ($wpdb->insert_id){ //if update or insert successful
				if ($_POST['room_id'])$message = 'Update room '.$_POST['room_name'].' (room id='.$_POST['room_id'].') successful!';
				else $message = "Insert room {$_POST['room_name']} successful!";			
				setcookie("message", $message, time()+3600);			
				wp_redirect(HOME_URL . '/wp-admin/admin.php?page=my-submenu-handle-list-rooms');
				//echo "<script language='javascript'>window.location = 'admin.php?page=my-submenu-handle-list-rooms'</script>";					
			}
			else{
				if ($_POST['room_id'])$error = "Update room {$_POST['room_name']} unsuccessful!";
				else $error = "Insert room {$_POST['room_name']} unsuccessful!";				
				setcookie("error", $error, time()+3600);
				wp_redirect(HOME_URL . '/wp-admin/admin.php?page=my-submenu-handle-list-rooms');
				//echo "<script language='javascript'>window.location = 'admin.php?page=my-submenu-handle-list-rooms'</script>";
			}			
		}	
	}
	
	if (!empty($_REQUEST['room_id'])){ //if edit room 
		$rq = "SELECT * FROM $roomstb WHERE ID=" . $_REQUEST['room_id'];		
		$room = $wpdb->get_row($rq);	
	}
?>
<div class="wrap">	
	<form action="" method="post"> <!-- submit form -->
		<div class="atention">
			<strong></strong><?php _e (' Contact us at ', 'hotel');?><em><a href="http://www.dailywp.com/support/">Support</a></em>			
		</div>
		<?php
			 if (count($errors)){
				echo '<div class="error"><strong>';
				foreach ($errors as $item){
					echo "<p>$item</p>";
				}
				echo '</strong></div>';
			} 
		?>
		<br/>
		<div class="settings" style="margin: 0px 0px 0px 0px; padding-top: 0px 0px 0px 0px;">
			<div class="heading">
				<h3><?php _e('Add Room', 'hotel');?></h3>
	
				<div class="cl"></div>
			</div>
			<div class="item" style="padding: 0 0 0 40px; height:100%;">
				<div class="content" id="page">	
					<div class="content submission" id="jobs" >
						<div style="margin-bottom: 20px;">		                    	
	                    	<input type="hidden" name="room_id" value="<?php echo $room->ID;?>"/>
	                    	<span><?php _e ('Room Type :', 'hotel');?></span>
							<div class="inputStyle">
								<select name="room_type" id="room_type" style="width:200px;">
									<option value=""><?php _e ('Select ...', 'hotel');?></option>
									<?php 
									foreach($roomtypes as $roomtype){
									?>									
	                            	<option value="<?php echo $roomtype->ID;?>" 
	                            	<?php if (!empty($room) && !empty($roomtype) && $room->room_type_ID == $roomtype->ID) echo 'selected="selected"';?>>
	                            		<?php echo $roomtype->post_title;?>
	                            	</option>
	                                <?php
									} 
	                                ?>
	                            </select>				
							</div>
							<div class="clear"></div>
							<br/>					
	                    	
	                    	<span><?php _e ('Room name - Sort by anphabet. The head <br/>of list room\'s name will be given priority when booking online payment', 'hotel');?></span>
	
							<div class="inputStyle">
								<input type="text" name="room_name" id="room_name" 
								value="<?php if(!empty($room->room_name))echo $room->room_name;?>" style="width:200px;"/>					
							</div>
							<div class="clear"></div>
							<br/>
							
							<span><?php _e ('Publish :', 'hotel');?> 
							<input type="checkbox" <?php if(empty($room->status ) || $room->status=='publish') echo 'checked="checked"'; ?> name="room_available"/> </span>
							<div class="clear"></div>					
						                  							
						</div>						                 
					</div>
				  </div>                                
			</div>                   
			<div class="cl"></div>
		</div>
		<br>
		<div style="margin-bottom:15px;">
			<input id="submit" name="submit"  class="button" type="submit" value="<?php _e ('Save', 'hotel');?>" />
		</div>
	</form> <!-- //submit form -->
</div>