<?php 
$field = Fields::getInstance();
$field_name = '';
	$field_type = '';
	$field_options = '';
	$can_search = 1;
	$is_activated = 1;
	if (isset($_POST['field_name'])){
			$field_name = $_POST['field_name'];
		}
	if (isset($_POST['field_type'])){
			$field_type = $_POST['field_type'];
		}
	if (isset($_POST['field_options']) && $_POST['field_type'] == FIELD_TYPE_DROPBOX){
			$field_options = $_POST['field_options'];
		}
	if (isset($_POST['can_search'])){
			$can_search = $_POST['can_search'];
		}
	if (isset($_POST['is_activated'])){
			$is_activated = $_POST['is_activated'];
	}

if(isset($_POST['submit'])){
$errors = array();
if (empty($field_name)){$errors['name'] = __('Enter field name, please!','hotel');}
if (count($errors) == 0){
	if(!isset($_GET['field_id'])){
		if (function_exists('icl_register_string')) { 
   			icl_register_string('Additional fields', md5($field_name), $field_name);
     	}
		$args = array(
		'field_name' => $field_name, 
		'field_type' => $field_type, 
		'field_options' => $field_options, 
		'can_search' => $can_search, 
		'activated' => $is_activated );	
		$field->insert($args);			
		$message = __("Insert Field {$field_name} successful!",'hotel');			
		setcookie("message", $message, time()+3600);
		echo "<script language='javascript'>window.location = '"."edit.php?post_type=roomtype&page=my-submenu-list-custom-field-roomtype';</script>";	
		
	}
	if(isset($_GET['field_id'])){	
		$id = $_GET['field_id'];	
		if (function_exists('icl_register_string')) { 
   			icl_register_string('Additional fields', md5($field_name), $field_name);
     	}
		$field->updateField($id, 'field_name', $field_name);
		$field->updateField($id, 'field_type', $field_type);
		$field->updateField($id, 'field_options', $field_options);
		$field->updateField($id, 'can_search', $can_search);
		$field->updateField($id, 'activated', $is_activated);
		$message = "Update Field {$field_name} successful!";			
		setcookie("message", $message, time()+3600);			
		echo "<script language='javascript'>window.location = '"."edit.php?post_type=roomtype&page=my-submenu-list-custom-field-roomtype';</script>";	
		
	}
}
}
if (isset($_GET['field_id'])){
	$field_list = $field->getFields();
	$fields = $field_list[$_GET['field_id']];
}
?>
<div class="wrap">	
	<form action="" method="post" name="form_custom_field" id="form_custom_field"> <!-- submit form -->
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
				<h3><?php _e('Add Custom Field Roomtype', 'hotel');?></h3>
	
				<div class="cl"></div>
			</div>
			<div class="item" style="padding: 0 0 0 40px; height:100%;">
				<div class="content" id="page">	
					<div class="content submission" id="jobs" >
						<div style="margin-bottom: 20px;">		                    	
	                    	<input type="hidden" name="room_id" value="<?php echo $room->ID;?>"/>
	                    	<span><?php _e ('Field Name', 'hotel');?>:</span>
							<div class="inputStyle">
									<input type="text" name="field_name" value="<?php echo (isset($_GET['field_id']))?$fields['field_name']:((isset($_POST['field_name']))?$_POST['field_name']:''); ?>" style="width:200px;"/>			
							</div>
							<div class="clear"></div>
							<br/>					
	                    	
	                    	<span><?php _e ('Field Type', 'hotel');?>:</span>	
							<div class="inputStyle">
								<select  name="field_type" >
										<option value="<?php echo FIELD_TYPE_TEXT?>"><?php _e('Short Text','hotel') ?></option>
										<option value="<?php echo FIELD_TYPE_LONGTEXT?>"><?php _e('Long Text','hotel') ?></option>
										<option value="<?php echo FIELD_TYPE_DROPBOX?>"><?php _e('Drop Box','hotel') ?></option>
										<option value="<?php echo FIELD_TYPE_CHECKBOX?>"><?php _e('Check Box','hotel') ?></option>
									</select>	
									<script language="javascript">
												{
													for(var i=0;i<document.form_custom_field.field_type.length;i++){
														if(document.form_custom_field.field_type[i].value=="<?php echo  (isset($_GET['field_id']))?$fields['field_type']:((isset($_POST['field_type']))?$_POST['field_type']:FIELD_TYPE_TEXT); ?>"){
															document.form_custom_field.field_type.selectedIndex=i;
															break;
														}
													}													
												}
												</script>			
							</div>
							<div class="clear"></div>
							<br/>
							<div id="optionDiv">
							<span><?php _e ('Field Options', 'hotel');?>:</span>	
							<div class="inputStyle">
								<textarea  cols="45" rows="5" name="field_options"><?php echo (isset($_GET['field_id']))?$fields['field_options']:((isset($_POST['field_options']))?$_POST['field_options']:''); ?></textarea>					
							</div>
							<div class="clear"></div>
							<br/>
							</div>
							<span><?php _e ('Can Search', 'hotel');?>:</span>	
							<div class="inputStyle">
								<select  name="can_search" id="">
										<option value="1" <?php echo (isset($_GET['field_id']) && $fields['can_search'] == 1)?'selected="selected"':((isset($_POST['can_search']) && $_POST['can_search'] == 1 )?'selected="selected"':''); ?> ><?php _e('Yes','hotel') ?></option>
										<option value="0" <?php echo (isset($_GET['field_id']) && $fields['can_search'] == 0)?'selected="selected"':((isset($_POST['can_search']) && $_POST['can_search'] == 0 )?'selected="selected"':''); ?> > <?php _e('No','hotel') ?></option>
									</select>					
							</div>
							<div class="clear"></div>
							<br/>
							
							<span><?php _e ('Actived', 'hotel');?>:</span>	
							<div class="inputStyle">
								<select name="is_activated" id="">
										<option value="1" <?php echo (isset($_GET['field_id']) && $fields['activated'] == 1)?'selected="selected"':((isset($_POST['is_activated']) && $_POST['is_activated'] == 1 )?'selected="selected"':''); ?>><?php _e('Yes','hotel') ?></option>
										<option value="0" <?php echo (isset($_GET['field_id']) && $fields['activated'] == 0)?'selected="selected"':((isset($_POST['is_activated']) && $_POST['is_activated'] == 0 )?'selected="selected"':''); ?>> <?php _e('No','hotel') ?></option>
									</select>			
							</div>
							<div class="clear"></div>
											
						                  							
						</div>						                 
					</div>
				  </div>                                
			</div>                   
			<div class="cl"></div>
		</div>
		<div style="margin-bottom:15px;">
			<input id="submit" name="submit" style="height:35px;" type="submit" value="<?php _e ('Save', 'hotel');?>" />
		</div>
	</form> <!-- //submit form -->
	<script type="text/javascript">
		jQuery(document).ready(function(){
			jQuery.initialForm();
			jQuery('#form_custom_field select[name=field_type]').change(function(){
				jQuery.initialForm();
			});
		});
		
		jQuery.initialForm = function(){
			var val = jQuery('#form_custom_field select[name=field_type]').val(),
				optionDiv = jQuery('#optionDiv');
				if (val == '<?php echo FIELD_TYPE_DROPBOX ?>')
				{
					optionDiv.slideDown('slow');
				}
				else
				{
					optionDiv.slideUp('slow');
				}
		}
	</script>
</div>