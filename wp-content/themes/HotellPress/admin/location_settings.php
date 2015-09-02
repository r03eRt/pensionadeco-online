<?php
if(isset($_POST['submitted']) && !empty($_POST['submitted']))
{
	$google_code = $_POST['google_code'];
	$address_map = $_POST['google_address'];
	if($_POST['langtitude'] != '')
		$langtitude = $_POST['langtitude'];
	else
		$langtitude = 0;
		
	if($_POST['longtitude'] != '')
		$longtitude = $_POST['longtitude'];
	else
		$longtitude = 0;
	$coordinates = $langtitude.','.$longtitude;
	
	update_option('tgt_google_code',$google_code);
	update_option('tgt_map_address',$address_map);
	update_option('tgt_hotel_coordinates',$coordinates);
	$message = __('Your settings have been saved','hotel');
}
?>
<div class="wrap">	
	<?php the_support_panel(); ?>
	<br>
    <?php
	if ($message) echo '<div class="updated below-h2">'.$message.'</div>';
	?>
<form method="post" name="location_setting" enctype="multipart/form-data" target="_self">
<input name="submitted" type="hidden" value="<?php _e('yes','hotel');?>" />
	<div class="settings" style="margin: 0px 0px 0px 0px; padding-top: 0px 0px 0px 0px;">
		<div class="heading">
			<h3 style="padding-top:10px;"><?php _e('Location Settings','hotel');?></h3>
            <input type="submit" name="Submit" value="&raquo;" class="submit" />
			<div class="cl"></div>
			
		</div>        		
		<div class="item">
			<div class="left">
				<?php _e('Google Map Code :','hotel');?>
				<span><?php _e('Fill google map code when you sign up on google.','hotel');?></span>
			</div>
			<div class="right">
				<input type="text" name="google_code" id="google_code" value="<?php echo get_option('tgt_google_code'); ?>" />
			</div>
			<div class="clear"></div>
		</div>  
        <div class="item">
			<div class="left">
				<?php _e('Hotel Address On Map :','hotel');?>
				<span><?php _e('Fill hotel address to display on google map.','hotel');?></span>
			</div>
			<div class="right">
				<input type="text" name="google_address" id="google_address" value="<?php echo get_option('tgt_map_address'); ?>" />
			</div>
			<div class="clear"></div>
		</div>         
        <?php
        $coordinates = get_option('tgt_hotel_coordinates ');
		$coordinates_arr = explode(',',$coordinates);
		?>
        <div class="item">
			<div class="left">
				<?php _e('Latitude Of Hotel :','hotel');?>
				<span><?php _e('Fill latitude of hotel to display hotel on google map.','hotel');?></span>
			</div>
			<div class="right">
				<input type="text" name="langtitude" id="langtitude" value="<?php if(!empty($coordinates_arr[0])) echo $coordinates_arr[0];  else if(isset($_POST['langtitude'])) echo $_POST['langtitude']; ?>" />
			</div>
			<div class="clear"></div>
		</div>
        <div class="item">
			<div class="left">
				<?php _e('Longitude Of Hotel :','hotel');?>
				<span><?php _e('Fill longitude of hotel to display hotel on google map.','hotel');?></span>
			</div>
			<div class="right">
				<input type="text" name="longtitude" id="longtitude" value="<?php if(!empty($coordinates_arr[1])) echo $coordinates_arr[1]; else if(isset($_POST['longtitude'])) echo $_POST['longtitude']; ?>" />
			</div>
			<div class="clear"></div>
		</div> 
	</div> <!-- // postbox -->
</form>
</div>	