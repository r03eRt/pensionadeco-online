<?php
include_once TEMPLATEPATH . '/admin_processing/admin_list_promotions_processing.php';
?>
<?php
$type = isset ($_GET['type']) ? $_GET['type'] : 'all';
?>
<div class="wrap"> <!-- #wrap -->
	<div class="atention">
		<strong><?php _e('Problems? Questions?','hotel');?></strong><?php _e(' Contact us at','hotel');?> <em><a href="http://www.dailywp.com/support/">Support</a></em>.
	</div>
	<br/>
	<?php
	if (isset($message) && !empty($message)) { echo '<div class="updated below-h2">'.$message.'</div>'; }
	?>
	<br/>
	<div class="settings" style="margin: 0px;"> <!-- #settings -->            
		<div class="heading">
			<h3><?php _e('List Promotions:','hotel');?></h3>
			<div class="cl"></div>
		</div>
		<div class="item" style="padding: 15px 0pt 5px 5px;" height="100%";width="100%";> <!-- #item: list  -->
        	<div class="left" style="width:100%; text-transform: none; margin-bottom:7px; ">
                        <form action="" name="listroomtype" method="post"> <!-- form filter -->
						   <select name="actionapplytop" id="actionapplytop">
                                    <option value="activated"><?php _e('Active','hotel');?></option>
                                    <option value="inactivated"><?php _e('Deactive','hotel');?></option>
                                    <option value="delete"><?php _e('Delete','hotel');?></option>                                    
                            </select>
							<input type="submit" class="button" name="pro_apply" id="pro_apply" style="line-height:12px;cursor: pointer;" value="<?php _e('Apply','hotel');?>"/>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        </div>
							<table class="widefat post fixed" width="100%" cellpadding="0" border="0"> <!-- list rooms -->
                            <thead> <!-- hearder -->
                                <tr>
                                    <th class="check-column"><input type="checkbox" style="width:auto; height:14px;" /></th>
                                    <th width="5%" style=""><?php _e('ID','hotel');?></th>
                                    <th width="6%" style=""><?php _e('Code','hotel');?></th>
                                    <th width="15%" style=""><?php _e('Title','hotel');?></th>
                                    <th width="10%" style=""><?php _e('Promotion Name','hotel');?></th>
                                    <th width="10%" style=""><?php _e('Promotion Type','hotel');?></th>
                                    <th width="10%" style="text-align: center;"><?php _e('Amount','hotel');?></th>
                                    <th width="10%" style=""><?php _e('Usage','hotel');?></th>
                                    <th width="auto" style=""><?php _e('Start Date','hotel');?></th>
                                    <th width="auto" style=""><?php _e('End Date','hotel');?></th>
                                    <th width="auto" style="text-align: center;"><?php _e('Activated','hotel');?></th>
                                </tr>
                            </thead> <!-- //header -->
							<tbody>
					<?php
                                        $promotions = get_option(BOOKING_PROMOTION);
										if(!empty($promotions))
                                        {
                                            foreach ($promotions as $promotion)
                                            {
                                                if($type == 'saleoff' && $promotion['code'] != '')
                                                    continue;
                                                if($type == 'coupon' && empty($promotion['code']))
                                                    continue;
					?>
										<tr height="30">
                                            <th class="alternate check-column" valign="top" style="font-size:11px;">
                                                <input  type="checkbox" name="cbroomtype[]" id="cbroomtype[]" value="<?php echo $promotion['ID']; ?>" style="width:auto; height:14px;" />
                                            </th>
                                            <th class="alternate" valign="top" style="font-size:11px;"><a href="<?php echo get_bloginfo('wpurl').'/wp-admin/admin.php?page=my-submenu-promotion&id='.$promotion['ID'] ; ?>"><?php echo $promotion['ID']; ?></a>
                                            </th>
											<th class="alternate" valign="top" style="font-size:11px; font-weight:normal;"><a href="<?php echo get_bloginfo('wpurl').'/wp-admin/admin.php?page=my-submenu-promotion&id='.$promotion['ID']; ; ?>"><?php echo $promotion['code']; ?></a></th>
											<th class="alternate" valign="top" style="font-size:11px; font-weight:normal;"><a href="<?php echo get_bloginfo('wpurl').'/wp-admin/admin.php?page=my-submenu-promotion&id='.$promotion['ID']; ; ?>"><?php echo $promotion['title']; ?></a></th>
                                            <th class="alternate" valign="top" style="font-size:11px; font-weight:normal;"><?php if($promotion['code'] != '') echo __('Coupon', 'hotel'); else _e('Sale off', 'hotel') ?></th>
											<th class="alternate" valign="top" style="font-size:11px; font-weight:normal;"><?php if($promotion['promotion_type'] == '1') echo __('Percent', 'hotel') . ' (%)' ; else _e('Exact amount', 'hotel') ?></th>
											<th class="alternate" valign="top" style="font-size:11px; font-weight:normal; text-align: center;"><?php echo $promotion['amount']; ?></th>
											<th class="alternate" valign="top" style="font-size:11px; font-weight:normal;"><?php echo $promotion['used']. '/' . $promotion['quanlity'];?></th>
                                                <th class="alternate" valign="top" style="font-size:11px; font-weight:normal;"><?php echo $promotion['start_date'];?></th>
                                                <th class="alternate" valign="top" style="font-size:11px; font-weight:normal;"><?php echo $promotion['end_date'];?></th>
                                                <th class="alternate" valign="top" style="font-size:11px; font-weight:normal;text-align: center;"><?php if($promotion['activated'] == '0') _e('No','hotel'); else _e('Yes','hotel');?></th>
					</tr>
					<?php
                                            }
                                        }
                                        else
                                        {
                                        ?>	
                                        <tr><td colspan="11"><i><?php _e('No promotion has been found yet','hotel'); ?></i></td></tr>
                                        <?php } ?>
				</tbody>
				<tfoot> <!-- footer -->
                                    <tr>
                                        <th class="check-column"><input type="checkbox" style="width:auto; height:14px;" /></th>
                                        <th width="5%" style=""><?php _e('ID','hotel');?></th>
                                        <th width="10%" style=""><?php _e('Code','hotel');?></th>
                                        <th width="15%" style=""><?php _e('Title','hotel');?></th>
                                        <th width="10%" style=""><?php _e('Promotion Name','hotel');?></th>
                                        <th width="10%" style=""><?php _e('Promotion Type','hotel');?></th>
                                        <th width="10%" style="text-align: center;"><?php _e('Amount','hotel');?></th>
                                        <th width="10%" style=""><?php _e('Usage','hotel');?></th>
                                        <th width="auto" style=""><?php _e('Start Date','hotel');?></th>
                                        <th width="auto" style=""><?php _e('End Date','hotel');?></th>
                                        <th width="auto" style="text-align: center;"><?php _e('Activated','hotel');?></th>
                                    </tr>
				</tfoot> <!-- //footer -->
                            </table> <!-- //list room -->
			</form> <!-- // form sort -->
			<div class="clear"></div>
		</div> <!-- //item -->
	</div> <!-- //settings -->
</div> <!-- //end wrap -->