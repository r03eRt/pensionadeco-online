<?php
include_once TEMPLATEPATH . '/admin_processing/admin_transaction_process.php';
?>
<script type="text/javascript">
	jQuery(document).ready(function($){
		$('#from_date').datepicker();
		$('#to_date').datepicker();	
		
		$('#from_date').datepicker( "option" , "maxDate", $('#to_date').val());
		$('#to_date').datepicker("option" , "minDate", $('#from_date').val());		

		$('#from_date').change(function(){
			$('#to_date').datepicker( "option" , "minDate", $('#from_date').val());
		});
		$('#to_date').change(function(){
			$('#from_date').datepicker( "option" , "maxDate", $('#to_date').val());	
		});
	});
</script>
<div class="wrap"> <!-- #wrap -->
	<?php the_support_panel(); ?>
	<br>
    <?php
	if ($message) echo '<div class="updated below-h2">'.$message.'</div>';
	?>

	<div class="settings" style="margin: 0px;"> <!-- #settings -->
		<div class="heading">
			<h3><?php _e('List Transactions ','hotel');
				if(isset($_GET['customer']) || isset($_GET['date']) || isset($_GET['currency']) || isset($_GET['booking']) || isset($_GET['amount']))
				{
				?>
				<a href="<?php echo HOME_URL?>/wp-admin/admin.php?page=my-submenu-transaction-log" class="button-secondary"><?php _e('Back','hotel'); ?></a>
				<?php
				}
				?>
			</h3>					
			<div class="cl"></div>
		</div>
		<div class="item" style="padding: 15px 0pt 5px 5px; height=100%; width=100%;"> <!-- #item: list  -->			
			<div class="left" style="width:100%; text-transform: none; margin-bottom:7px; ">
				<form <?php echo $link;?> action="" name="list_transaction" method="post" > <!-- form filter -->
					<div class="alignleft actions"> 
						<select name="action1">
							<option selected="selected" value="-1"><?php _e( 'Bulk Actions', 'hotel' );?></option>					
							<option value="delete1"><?php _e('Delete', 'hotel' );?></option>					
						</select>
						<input type="submit" class="button-secondary" id="doaction1" name="doaction1" onclick="return confirm_delete();" value="<?php _e( 'Apply', 'hotel' );?>">
						&nbsp;&nbsp;				
					</div>
				 	 <div>			
						<label><?php _e('From Date:','hotel')?> </label>
						<input type="text" id="from_date" name="date_from" value="<?php if(isset($_POST['date_from'])) echo $_POST['date_from']; else echo '...'; ?>" style="width: 100px">
						
						<label><?php _e('To Date:','hotel')?> </label>
						<input type="text" id="to_date" name="date_to" value="<?php if(isset($_POST['date_to'])) echo $_POST['date_to']; else echo '...'; ?>" style="width: 100px">
						
						<input type="submit" class="button-secondary" id="doaction" name="calculate" value="<?php _e('Search','hotel')?>">
				     </div>
			    	 <br>
					<table class="widefat post fixed" width="100%" cellpadding="0" border="0"> <!-- list rooms -->
						<thead> <!-- hearder -->
							<tr style = "height=30px; width=100%; ">
								<th class="check-column"><input type="checkbox" style="width:14px; height:14px;" /></th>
								<th width="40%" style=""><?php _e('Customer','hotel');?></th>						
								<th width="15%" style=""><?php _e('Booking','hotel');?></th>
								<th width="15%" style=""><?php _e('Booking Date','hotel');?></th>
								<th width="15%" style=""><?php _e('Amount','hotel');?></th>
								<th width="15%" style=""><?php _e('Currency','hotel');?></th>
							</tr>
						</thead> <!-- //header -->
						<tbody>
							<?php
								if(!empty($list_all) && count($list_all))
								{
									/*echo '<pre>';
									print_r ($list_all);
									echo '</pre>';*/
									$amount = 0;
									for($i = $start; ($i < count($list_all) && $i < ( $start + $items_per_page ) ) ; $i++ )
									{
										$first_name = get_user_meta($list_all[$i]->customer_id, 'first_name');
										$last_name = get_user_meta($list_all[$i]->customer_id, 'last_name');
										$amount = $amount + $list_all[$i]->amount;
										$currency = $list_all[$i]->currency;
							?>
										
							<tr height="30px;" >
								<th class="alternate check-column" valign="top" style="font-size:11px;">
									<input  type="checkbox" name="tid[]" id="tid[]" value="<?php echo $list_all[$i]->ID;?>" style="width:10px; height:14px;" />
								</th>
								<th class="alternate" valign="top" style="font-size:11px;">							
									<a href="<?php echo HOME_URL?>/wp-admin/admin.php?page=my-submenu-transaction-log&customer=<?php echo $list_all[$i]->customer_id;?>">
										<?php echo $first_name[0].' '.$last_name[0];?>
									</a>
										<span class="row-actions">							
											<span class="trash">
												<a onclick="return confirm_delete();" href="<?php echo HOME_URL?>/wp-admin/admin.php?page=my-submenu-transaction-log&amp;tid=<?php echo $list_all[$i]->ID;?>&amp;action=delete" class="submitdelete">
													<?php _e('Delete','ce'); ?>
												</a>
											</span>
										</span>					
								</th>											
								<th class="alternate" valign="top" style="font-size:11px; font-weight:normal;">
									<a href="<?php echo HOME_URL?>/wp-admin/admin.php?page=my-submenu-handle-add-booking&editbooking=true&uid=<?php echo $list_all[$i]->customer_id; ?>">
										<?php echo $list_all[$i]->booking_id; ?>
									</a>	
								</th>
								<th class="alternate" valign="top" style="font-size:11px; font-weight:normal;">
									<a href="<?php echo HOME_URL?>/wp-admin/admin.php?page=my-submenu-transaction-log&date=<?php echo $list_all[$i]->date; ?>">
										<?php echo date(get_option('date_format'), strtotime($list_all[$i]->date)); ?>
									</a>	
								</th>
								<th class="alternate" valign="top" style="font-size:11px; font-weight:normal;">
										<?php echo $list_all[$i]->amount; ?>
								</th>
								<th class="alternate" valign="top" style="font-size:11px; font-weight:normal;">
									<a href="<?php echo HOME_URL?>/wp-admin/admin.php?page=my-submenu-transaction-log&currency=<?php echo $list_all[$i]->currency; ?>">
										<?php echo $list_all[$i]->currency; ?>
									</a>	
								</th>
							</tr>	
							<?php
									}
								}
								else
								{
							?>
								<tr><td colspan="8" ><i><?php _e('No transaction has been found yet', 'hotel') ?></i></td></tr>
							<?php
								}
							?>
						</tbody>
					</table> <!-- //list room -->
					<div style="margin-left:650px;">
					<?php if ( isset($currency) && $currency != '' && isset($amount) && $amount != '' ){
						_e('Total Amount', 'hotel'); ?>: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<?php echo $currency.' '.$amount;
					} 
					?>
					</div>
					<div class="tablenav">
						<!-- #selects: filter + bulk action -->
						<div class="alignleft actions"> 
							<select name="action">
								<option selected="selected" value="-1"><?php _e( 'Bulk Actions', 'hotel' );?></option>					
									<option value="delete"><?php _e('Delete', 'hotel' );?></option>					
							</select>
							<input type="submit" class="button-secondary" id="doaction" name="doaction" onclick="return confirm_delete();" value="<?php _e( 'Apply', 'hotel' );?>">				
						</div>
								
						<!-- page navigation -->
						<div class="tablenav-pages"> 
							<?php echo $page_div_str; ?>
						</div>
					<!-- //page navigation -->			
					</div>
				</form> <!-- // form sort -->
			</div>
			<div class="clear"></div>
		</div> <!-- //item -->
	</div> <!-- //settings -->
</div> <!-- //end wrap -->

<script type="text/javascript">
function confirm_delete()
{
	if(confirm('<?php _e('Are you sure want to delete the transaction(s) ?','hotel'); ?>') == true)
	{
		return true;
	}else
		return false;
}
</script>