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
		$message .= "<p>Update status successful!</p>";}
	}
	if(isset($_POST['actionapplytop']) && $_POST['actionapplytop']=="pending" )
	{	
		for($i=0;$i<count($u_id);$i++){
		$wpdb->update( "$tb_posts" , array( 'post_status' => 'pending'), array( 'ID' => "$u_id[$i]" ));	
		}
		if(count($u_id)>0){
		$message .= "<p>Update status successful!</p>";}
	}
	if(isset($_POST['actionapplytop']) && $_POST['actionapplytop']=="trash" )
	{			
		for($i=0;$i<count($u_id);$i++){
		$wpdb->update( "$tb_posts" , array( 'post_status' => 'trash'), array( 'ID' => "$u_id[$i]" ));
		}
		if(count($u_id)>0){
		$message .= "<p>Update status successful!</p>";}
	}
	
}
$sortby = 'publish';

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
	$currency = get_option('tgt_currency');
	if ( $currency == "USD" || $currency == "AUD" || $currency == "CAD" || $currency == "NZD" || $currency == "HKD" || $currency == "SGD" ) { $currencysymbol = "$"; }
	else if ( $currency == "GBP" ) { $currencysymbol = "&pound;"; }
	else if ( $currency == "JPY" ) { $currencysymbol = "&yen;"; }
	else if ( $currency == "EUR" ) { $currencysymbol = "&euro;"; }
	else { $currencysymbol = ""; }
?>
<div class="wrap"> <!-- #wrap -->
	<div class="atention">
		<strong><?php _e('Problems? Questions?','hotel');?></strong><?php _e(' Contact us at','hotel');?> <em><a href="http://www.dailywp.com/support/">Support</a></em>. 
	</div>
	<br/>
	<?php
	if (isset($message)){ echo '<div class="updated below-h2">'.$message.'</div>'; }	
	?>
	<br/>
	<div class="settings" style="margin: 0px;"> <!-- #settings -->
		<div class="heading">
			<h3><?php _e('List Room Type:','hotel');?></h3>					
			<div class="cl"></div>
		</div>
		<div class="item" style="padding: 15px 0pt 5px 5px;" height="100%";width="100%";> <!-- #item: list  -->			
			<div class="left" style="width:100%; text-transform: none; margin-bottom:7px; ">
				<form action="" name="listroomtype" method="post"> <!-- form filter -->				
					
					<select name="actionapplytop" id="actionapplytop">						
						<option value="publish"><?php _e('Publish','hotel');?></option>
						<option value="pending"><?php _e('Pending','hotel');?></option>
						<option value="trash"><?php _e('Move to Trash','hotel');?></option>
					</select>
					<input type="submit" name="applysubmitted" id="applysubmitted" style="line-height:12px;cursor: pointer;" value="<?php _e('Apply','hotel');?>"/>
				
				
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				
					<select name="sorttop" id="sorttop" style="width:120px;">
						<option value="publish"><?php _e('Publish','hotel');?></option>
						<option value="pending"><?php _e('Pending','hotel');?></option>
						<option value="trash"><?php _e('Trash','hotel');?></option>										
					</select>
					<input type="submit" name="sort_by" id="sort_by"  style="line-height:12px;cursor: pointer;" value="<?php _e('View','hotel');?>"/>
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
						<th width="auto" style=""><?php _e('Room Type','hotel');?></th>						
						<th width="auto" style=""><?php _e('Bed Name','hotel');?></th>
						<th width="60px" style=""><?php _e('Person/ Room','hotel');?></th>
						<th width="40px" style=""><?php _e('Kids/ Room','hotel');?></th>
						<th width="25px" style=""><?php _e('Pet','hotel');?></th>
						<th width="60px" style=""><?php _e('Smoking','hotel');?></th>
						<th width="auto" style=""><?php _e('Price','hotel');?></th>
						<th width="60px" style=""><?php _e('Discount','hotel');?></th>
						<th width="auto" style=""><?php _e('Total Price','hotel');?></th>
						<th width="50px" style=""><?php _e('Status','hotel');?></th>
					</tr>
				</thead> <!-- //header -->
				<tbody>
					<?php 
					global $query_string;	
					query_posts($query_string . "post_type=roomtype&posts_per_page=-1&post_status=".$sortby);				
					global $post;					
					if ( have_posts() ) : while ( have_posts() ) : the_post();	
					$price = get_post_meta($post->ID, 'tgt_roomtype_price', true);
					$discount = get_post_meta($post->ID, 'tgt_roomtype_discount', true);
					$total = $price;
					if($discount > 0){
					$total = $price*(1-$discount/100);
					}					
					?>					
					<tr height="30">
						<th class="alternate check-column" valign="top" style="font-size:11px;">
							<input  type="checkbox" name="cbroomtype[]" id="cbroomtype[]" value="<?php echo $post->ID;?>" style="width:10px; height:14px;" />
						</th>
						<th class="alternate" valign="top" style="font-size:11px;">							
							<a href="admin.php?page=my-submenu-handle-add-room-type&roomtype_id=<?php echo $post->ID;?>">
								<?php echo $post->post_title;?>
							</a>					
						</th>											
						<th class="alternate" valign="top" style="font-size:11px; font-weight:normal;">
							   <?php echo  get_post_meta($post->ID, 'tgt_roomtype_bed_name', true); ?>	
						</th>
						<th class="alternate" valign="top" style="font-size:11px; font-weight:normal;">
							   <?php echo  get_post_meta($post->ID, 'tgt_roomtype_person_number', true); ?>	
						</th>
						<th class="alternate" valign="top" style="font-size:11px; font-weight:normal;">
							   <?php echo  get_post_meta($post->ID, 'tgt_roomtype_kids_number', true); ?>	
						</th>
						<th class="alternate" valign="top" style="font-size:11px; font-weight:normal;">
							   <?php 
							   if(get_post_meta($post->ID, 'tgt_roomtype_permit_pet', true)==1){
									_e('Yes','hotel');
								}
								else{
									_e('No','hotel');
								}
							   ?>	
						</th>
						<th class="alternate" valign="top" style="font-size:11px; font-weight:normal;">
							   <?php 							  
							   if(get_post_meta($post->ID, 'tgt_roomtype_permit_smoking', true)==1){
									_e('Yes','hotel');
								}
								else{
									_e('No','hotel');
								}
							   ?>				   
						</th>
						<th class="alternate" valign="top" style="font-size:11px; font-weight:normal;">
							   <?php 
							   if(get_post_meta($post->ID, 'tgt_roomtype_price', true)>0){
									echo  get_post_meta($post->ID, 'tgt_roomtype_price', true)." ".$currencysymbol;
								}
								else{
									echo "0 ".$currencysymbol;
								}							   
							    ?>	
						</th>
						<th class="alternate" valign="top" style="font-size:11px; font-weight:normal;">
							   <?php echo  get_post_meta($post->ID, 'tgt_roomtype_discount', true)."%"; ?>	
						</th>
						<th class="alternate" valign="top" style="font-size:11px; font-weight:normal;">
							   <?php 
							   if(get_post_meta($post->ID, 'tgt_roomtype_price', true)>0){
									echo  $total." ".$currencysymbol;
								}
								else{
									echo "0 ".$currencysymbol;
								}	
							    ?>	
						</th>
						<th class="alternate" valign="top" style="font-size:11px; font-weight:normal;">
							   <?php echo  $sortby; ?>	
						</th>
					</tr>	
					<?php
						endwhile;
						endif;?>	
						<?php wp_reset_query(); ?>
				</tbody>
				<tfoot> <!-- footer -->				
					<tr>
						<th class="check-column"><input type="checkbox" style="width:14px; height:14px;" /></th>
						<th width="auto" style=""><?php _e('Room Type','hotel');?></th>						
						<th width="auto" style=""><?php _e('Bed Name','hotel');?></th>
						<th width="60px" style=""><?php _e('Person/ Room','hotel');?></th>
						<th width="40px" style=""><?php _e('Kids/ Room','hotel');?></th>
						<th width="25px" style=""><?php _e('Pet','hotel');?></th>
						<th width="60px" style=""><?php _e('Smoking','hotel');?></th>
						<th width="auto" style=""><?php _e('Price','hotel');?></th>
						<th width="60px" style=""><?php _e('Discount','hotel');?></th>
						<th width="auto" style=""><?php _e('Total Price','hotel');?></th>
						<th width="50px" style=""><?php _e('Status','hotel');?></th>
					</tr>
					
				</tfoot> <!-- //footer -->
			</table> <!-- //list room -->
			
			</form> <!-- // form sort -->
			<div class="clear"></div>
		</div> <!-- //item -->
	</div> <!-- //settings -->
</div> <!-- //end wrap -->