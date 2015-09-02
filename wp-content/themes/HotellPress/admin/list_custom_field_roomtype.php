<?php
$field = Fields::getInstance();
if(isset($_COOKIE['message'])){	
	$message = $_COOKIE['message']; 
	setcookie("message", $message, time()-3600);	
}
	if(isset($_POST['applysubmitted']) && !isset($_GET['delete-field'])){	
	$u_id = array();
	if(isset($_POST['cbroomtype'])){
	$u_id= $_POST['cbroomtype'];	
	}
	
	if(isset($_POST['actionapplytop']) && $_POST['actionapplytop']=="activate" )
	{		
		for($i=0;$i<count($u_id);$i++){
			$field->updateField($u_id[$i],'activated',1);			
		}
		if(count($u_id)>0){
		$message .= __("Update activated successful!",'hotel');}
	}
	if(isset($_POST['actionapplytop']) && $_POST['actionapplytop']=="deactivate" )
	{	
		for($i=0;$i<count($u_id);$i++){
			$field->updateField($u_id[$i],'activated',0);	
		}
		if(count($u_id)>0){
		$message .= __("Update activated successful!",'hotel');}
	}
	if(isset($_POST['actionapplytop']) && $_POST['actionapplytop']=="can" )
	{	
		for($i=0;$i<count($u_id);$i++){
			$field->updateField($u_id[$i],'can_search',1);	
		}
		if(count($u_id)>0){
		$message .= __("Update can search successful!",'hotel');}
	}
	if(isset($_POST['actionapplytop']) && $_POST['actionapplytop']=="cannot" )
	{	
		for($i=0;$i<count($u_id);$i++){
			$field->updateField($u_id[$i],'can_search',0);	
		}
		if(count($u_id)>0){
		$message .= __("Update can search successful!",'hotel');}
	}
	if(isset($_POST['actionapplytop']) && $_POST['actionapplytop']=="delete" )
	{			
		for($i=0;$i<count($u_id);$i++){
			$field->deleteField($u_id[$i]);		
		}
		if(count($u_id)>0){
		$message .= __("Delete successful!",'hotel');}
	}
	
	}
	if (isset($_GET['delete-field']) && isset($_GET['field_id'])){
		$field->deleteField($_GET['field_id']);
		$message .= __("Delete successful!",'hotel');
	}	

?>
<div class="wrap"> <!-- #wrap -->
	<div class="atention">
		<strong><?php _e('Problems? Questions?','hotel');?></strong><?php _e(' Contact us at','hotel');?> <em><a href="http://www.dailywp.com/support/">Support</a></em>. 
	</div>
	<br/>
	<?php
	if (isset($message)){ echo '<div class="updated below-h2"><p>'.$message.'</p></div>'; }	
	?>
	<br/>
	<div class="settings" style="margin: 0px;"> <!-- #settings -->
		<div class="heading">
			<h3><?php _e('Manager Fields:','hotel');?> <a style="text-decoration: none;" class="button" href="<?php echo get_bloginfo('wpurl').'/wp-admin/edit.php?post_type=roomtype&page=my-submenu-custom-field-roomtype' ; ?>" ><?php _e('Add Field','hotel');?></a> </h3>					
			<div class="cl"></div>
		</div>
		<div class="item" style="padding: 15px 0pt 5px 5px;" height= "100%" width="100%"> <!-- #item: list  -->			
			<form action="" name="listroomtype" method="post"> <!-- form filter -->	
			<div class="left" style="width:100%; text-transform: none; margin-bottom:7px; ">
							
					
					<select name="actionapplytop" id="actionapplytop">						
						<option value="activate"><?php _e('Activate','hotel');?></option>
						<option value="deactivate"><?php _e('Deactivate','hotel');?></option>
						<option value="can"><?php _e('Can Search','hotel');?></option>
						<option value="cannot"><?php _e('Cannot Search','hotel');?></option>
						<option value="delete"><?php _e('Delete','hotel');?></option>
					</select>
					<input class="button" type="submit" name="applysubmitted" id="applysubmitted" style="line-height:12px;cursor: pointer;" value="<?php _e('Apply','hotel');?>"/>
				
			</div>
			
			<table class="widefat post fixed" width="100%" cellpadding="0" border="0"> <!-- list rooms -->
				<thead> <!-- hearder -->
					<tr>
						<th class="check-column"><input type="checkbox" style="width:14px; height:14px;" /></th>
						<th width="auto" style=""><?php _e('Name','hotel');?></th>						
						<th width="auto" style=""><?php _e('Type','hotel');?></th>						
						<th width="auto" align="center" style=""><?php _e('Can Search','hotel');?></th>
						<th width="50px" align="center" style=""><?php _e('Actived','hotel');?></th>						
					</tr>
				</thead> <!-- //header -->
				<tbody>
					<?php 
					
					$arr_field = $field->getFields();
					if(isset($arr_field) && is_array($arr_field) && !empty($arr_field)){				
					foreach ($arr_field as $key => $fields) { 	
							$can_search = ($fields['can_search'] == 1)?__('yes','hotel'):__('no','hotel');
							$activated = ($fields['activated'] == 1)?__('yes','hotel'):__('no','hotel');	
					?>					
					<tr height="30">
						<th class="alternate check-column" valign="top" style="font-size:11px;">
							<input  type="checkbox" name="cbroomtype[]" id="cbroomtype[]" value="<?php echo $key;?>" style="width:10px; height:14px;" />
						</th>
						<th class="alternate" valign="top" style="font-size:11px;">							
							<a href="edit.php?post_type=roomtype&page=my-submenu-custom-field-roomtype&field_id=<?php echo $key;?>">
								<?php echo $fields['field_name'];?>
							</a>	
							<br /><div class="row-actions">  
										<span class="edit"><a href="<?php echo get_bloginfo('wpurl').'/wp-admin/edit.php?post_type=roomtype&page=my-submenu-custom-field-roomtype&field_id='.$key ; ?>"><?php _e('Edit','hotel'); ?></a> | </span>
									    <span class="delete"><a href="javascript:del_field('<?php echo $key; ?>')"><?php _e('Delete','hotel'); ?></a></span>
							 </div>				
						</th>											
						<th class="alternate" valign="top" style="font-size:11px; font-weight:normal;">
							   <?php echo  $fields['field_type']; ?>
							   <?php if ($fields['field_type'] == FIELD_TYPE_DROPBOX){ ?>	
							  	<br />
							   (<?php echo  $fields['field_options']; ?>)	
							 	
							   <?php } ?>
						</th>	
											
						<th class="alternate" valign="top" style="font-size:11px; font-weight:normal;">
							   <?php echo $can_search; ?>	
						</th>
						<th class="alternate" valign="top" style="font-size:11px; font-weight:normal;">
							   <?php echo  $activated; ?>	
						</th>						
					</tr>	
					<?php
					} }
				 wp_reset_query(); ?>
				</tbody>
				<tfoot> <!-- footer -->				
					<tr>
						<th class="check-column"><input type="checkbox" style="width:14px; height:14px;" /></th>
						<th width="auto" style=""><?php _e('Name','hotel');?></th>						
						<th width="auto" style=""><?php _e('Type','hotel');?></th>						
						<th width="auto" align="center" style=""><?php _e('Can Search','hotel');?></th>
						<th width="50px" align="center" style=""><?php _e('Actived','hotel');?></th>					
					</tr>
					
				</tfoot> <!-- //footer -->
			</table> <!-- //list room -->
			
			
			
			</form> <!-- // form sort -->
			<div class="clear"></div>
		</div> <!-- //item -->
	</div> <!-- //settings -->
</div> <!-- //end wrap -->
<script language="javascript">
function del_field(tagid)
{	
	if(confirm("<?php _e('You are about to permanently delete the selected items. \'Cancel\' to stop, \'Ok\' to delete.','fbe'); ?>"))
	{
		window.location = '<?php echo get_bloginfo('wpurl').'/wp-admin/edit.php?post_type=roomtype&page=my-submenu-list-custom-field-roomtype&delete-field=true&field_id='; ?>' + tagid ;
	}		
}
</script>