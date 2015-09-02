<?php
if(isset($_COOKIE['message'])){	
	$message = $_COOKIE['message']; 
	setcookie("message", $message, time()-3600);	
	}
	if(isset($_POST['applysubmitted'])){
	//Get form data
	global $wpdb;
	$u_id = array();
	if(isset($_POST['cbroomtype'])){
	$u_id= $_POST['cbroomtype'];	
	}
	$tb_posts = $wpdb->prefix.'posts';
	if(isset($_POST['actionapplytop']) && $_POST['actionapplytop']=="publish" )
	{		
		for($i=0;$i<count($u_id);$i++){
		$wpdb->update( "$tb_posts" , array( 'post_status' => 'publish'), array( 'ID' => "$u_id[$i]" ));	
		}
		if(count($u_id)>0){
		$message .= __("Update status successful!",'hotel');}
	}
	if(isset($_POST['actionapplytop']) && $_POST['actionapplytop']=="pending" )
	{	
		for($i=0;$i<count($u_id);$i++){
		$wpdb->update( "$tb_posts" , array( 'post_status' => 'pending'), array( 'ID' => "$u_id[$i]" ));	
		}
		if(count($u_id)>0){
		$message .= __("Update status successful!",'hotel');}
	}
	if(isset($_POST['actionapplytop']) && $_POST['actionapplytop']=="trash" )
	{			
		for($i=0;$i<count($u_id);$i++){
		$wpdb->update( "$tb_posts" , array( 'post_status' => 'trash'), array( 'ID' => "$u_id[$i]" ));
		}
		if(count($u_id)>0){
		$message .= __("Update status successful!",'hotel');}
	}
	if(isset($_POST['actionapplytop']) && $_POST['actionapplytop']=="delete" )
	{			
		for($i=0;$i<count($u_id);$i++){		
			wp_delete_post($u_id[$i]);
		}
		if(count($u_id)>0){
		$message .= __("Delete successful!",'hotel');}
	}

}
$sortby = '';

if(isset($_POST['sort_by'])){	
	
	if(isset($_POST['sorttop']) && $_POST['sorttop']=="publish" )
	{		
		$sortby = 'publish';
	}
	if(isset($_POST['sorttop']) && $_POST['sorttop']=="pending" )
	{	
		$sortby = 'pending';
	}
	if(isset($_POST['sorttop']) && $_POST['sorttop']=="trash" )
	{			
		$sortby = 'trash';
	}
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
			<h3><?php _e('Manager Testimonial:','hotel');?></h3>					
			<div class="cl"></div>
		</div>
		<div class="item" style="padding: 15px 0pt 5px 5px;" height= "100%" width="100%"> <!-- #item: list  -->			
			<form action="" name="listroomtype" method="post"> <!-- form filter -->	
			<div class="left" style="width:100%; text-transform: none; margin-bottom:7px; ">
							
					
					<select name="actionapplytop" id="actionapplytop">						
						<option value="publish"><?php _e('Publish','hotel');?></option>
						<option value="pending"><?php _e('Pending','hotel');?></option>
						<option value="trash"><?php _e('Move to Trash','hotel');?></option>
						<option value="delete"><?php _e('Delete','hotel');?></option>
					</select>
					<input type="submit" class="button" name="applysubmitted" id="applysubmitted" value="<?php _e('Apply','hotel');?>"/>
				
				
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				
					<select name="sorttop" id="sorttop" style="width:120px;">
						<option value="publish"><?php _e('Publish','hotel');?></option>
						<option value="pending"><?php _e('Pending','hotel');?></option>
						<option value="trash"><?php _e('Trash','hotel');?></option>										
					</select>
					<input type="submit" name="sort_by"  class="button" id="sort_by"  value="<?php _e('View','hotel');?>"/>
					<script language="javascript">
												{
													for(var i=0;i<document.listroomtype.sorttop.length;i++){
														if(document.listroomtype.sorttop[i].value=="<?php echo  $sortby; ?>"){
															document.listroomtype.sorttop.selectedIndex=i;
															break;
														}
													}													
												}
												</script>
			</div>
			
			<table class="widefat post fixed" width="100%" cellpadding="0" border="0"> <!-- list rooms -->
				<thead> <!-- hearder -->
					<tr>
						<th class="check-column"><input type="checkbox" style="width:14px; height:14px;" /></th>
						<th width="auto" style=""><?php _e('Name','hotel');?></th>						
						<th width="auto" style=""><?php _e('Content','hotel');?></th>
						<th width="160px" style=""><?php _e('Email','hotel');?></th>
						<th width="100px" style=""><?php _e('Status','hotel');?></th>						
					</tr>
				</thead> <!-- //header -->
				<tbody>
					<?php 
						
					query_posts("post_type='testimonial'&posts_per_page=-1&post_status=".$sortby);				
					global $post;					
					if ( have_posts() ) { while ( have_posts() ) { the_post();	
								
					?>					
					<tr height="30">
						<th class="alternate check-column" valign="top" style="font-size:11px;">
							<input  type="checkbox" name="cbroomtype[]" id="cbroomtype[]" value="<?php echo $post->ID;?>" style="width:10px; height:14px;" />
						</th>
						<th class="alternate" valign="top" style="font-size:11px;">							
							<a href="javascript:void(0)">
								<?php echo $post->post_title;?>
							</a>					
						</th>											
						<th class="alternate" valign="top" style="font-size:11px; font-weight:normal;">
							   <?php echo  $post->post_content; ?>	
						</th>
						<th class="alternate" valign="top" style="font-size:11px; font-weight:normal;">
							   <?php echo  get_post_meta($post->ID, 'tgt_email_testimonial', true); ?>	
						</th>	
						<th class="alternate" valign="top" style="font-size:11px; font-weight:normal;">
							   <?php echo  $post->post_status; ?>	
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
						<th width="auto" style=""><?php _e('Content','hotel');?></th>
						<th width="160px" style=""><?php _e('Email','hotel');?></th>	
						<th width="100px" style=""><?php _e('Status','hotel');?></th>					
					</tr>
					
				</tfoot> <!-- //footer -->
			</table> <!-- //list room -->
			
			
			
			</form> <!-- // form sort -->
			<div class="clear"></div>
		</div> <!-- //item -->
	</div> <!-- //settings -->
</div> <!-- //end wrap -->