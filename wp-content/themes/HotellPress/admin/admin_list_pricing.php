<?php
include_once TEMPLATEPATH . '/admin_processing/admin_list_pricing_processing.php';
?>
<div class="wrap"> <!-- #wrap -->
	<div class="atention">
		<strong><?php _e('Problems? Questions?','hotel');?></strong><?php _e(' Contact us at','hotel');?> <em><a href="http://www.dailywp.com/support/">Support</a></em>.
	</div>
	<br/>
	<?php
	$success = isset($_GET['message']) ? $_GET['message'] : '';
	if(!empty($success))
	{
		$success = __('Your settings have been saved !','hotel');
		echo '<div class="updated below-h2">'. $success .'</div>';
	}
	if (isset($message) && !empty($message)) { echo '<div class="updated below-h2">'.$message.'</div>'; }
	?>
	<br/>
	<div class="settings" style="margin: 0px;"> <!-- #settings -->            
		<div class="heading">
			<h3><?php _e('List Pricings:','hotel');?></h3>
			<div class="cl"></div>
		</div>
		<div class="item" style="padding: 15px 0pt 5px 5px;" height="100%";width="100%";> <!-- #item: list  -->
                    <div class="left" style="width:100%; text-transform: none; margin-bottom:7px; ">
                        <form action="" name="listroomtype" method="post"> <!-- form filter -->

                            <select name="actionapplytop" id="actionapplytop">
                                    <option value="enable"><?php _e('Active','hotel');?></option>
                                    <option value="disable"><?php _e('Deactive','hotel');?></option>
                                    <option value="delete"><?php _e('Delete','hotel');?></option>                                    
                            </select>
                            <input type="submit" class="button" name="applysubmitted" id="applysubmitted" style="line-height:12px;cursor: pointer;" value="<?php _e('Apply','hotel');?>"/>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<!--					<input type="submit" name="sort_by" id="sort_by"  style="line-height:12px;cursor: pointer;" value="<?php _e('View','hotel');?>"/>
					<script language="javascript">
                                        {
                                            for(var i=0;i<document.listroomtype.sorttop.length;i++){
                                                if(document.listroomtype.sorttop[i].value=="<?php echo  $sortby; ?>"){
                                                    document.listroomtype.sorttop.selectedIndex=i;
                                                    break;
                                                }
                                            }
                                        }
                                        </script>-->
			</div>

			<table class="widefat post fixed" width="100%" cellpadding="0" border="0"> <!-- list rooms -->
				<thead> <!-- hearder -->
					<tr>
						<th class="check-column"><input type="checkbox" style="width:auto; height:14px;" /></th>
						<th width="5%" style=""><?php _e('ID','hotel');?></th>
                                                <th width="10%" style=""><?php _e('Time Type','hotel');?></th>
						<th width="20%" style=""><?php _e('Time','hotel');?></th>
						<th width="15%" style=""><?php _e('Room Type','hotel');?></th>
						<th width="10%" style=""><?php _e('New Price','hotel');?></th>
						<th width="10%" style=""><?php _e('Priority','hotel');?></th>
						<th width="auto" style=""><?php _e('Start Date','hotel');?></th>
						<th width="auto" style="text-align: center;"><?php _e('Activated','hotel');?></th>
					</tr>
				</thead> <!-- //header -->
				<tbody>
					<?php
                                        $room_type_name = get_room_type_name();                                       
                                        global $wpdb;
                                        $sql = "SELECT * FROM " . $wpdb->prefix . "pricing ORDER BY ID DESC";
                                        $prices = $wpdb->get_results($sql);                                        
					if(!empty($prices))
                                        {
                                            foreach ($prices as $price)
                                            {
					?>
					<tr height="30">
                                            <th class="alternate check-column" valign="top" style="font-size:11px;">
                                                <input  type="checkbox" name="cbroomtype[]" id="cbroomtype[]" value="<?php echo $price->ID; ?>" style="width:auto; height:14px;" />
                                            </th>
                                            <th class="alternate" valign="top" style="font-size:11px;"><a href="<?php echo get_bloginfo('wpurl').'/wp-admin/admin.php?page=my-submenu-pricing&id='.$price->ID ; ?>"><?php echo $price->ID; ?></a>
                                            </th>
						<th class="alternate" valign="top" style="font-size:11px; font-weight:normal;"><a href="<?php echo get_bloginfo('wpurl').'/wp-admin/admin.php?page=my-submenu-pricing&id='.$price->ID ; ?>"><?php echo $price->time_type; ?></a></th>
						<th class="alternate" valign="top" style="font-size:11px; font-weight:normal;"> <?php echo get_pricing_time($price->time, $price->time_type);//echo $price->time; ?></th>
						<th class="alternate" valign="top" style="font-size:11px; font-weight:normal;"><?php echo $room_type_name[$price->room_type_id]; ?></th>
						<th class="alternate" valign="top" style="font-size:11px; font-weight:normal;"><?php echo $price->new_price_change; ?></th>
						<th class="alternate" valign="top" style="font-size:11px; font-weight:normal;"><?php echo get_priority($price->priority);?></th>
                                                <th class="alternate" valign="top" style="font-size:11px; font-weight:normal;"><?php echo $price->date_start?></th>
                                                <th class="alternate" valign="top" style="font-size:11px; font-weight:normal;text-align: center;"><?php if($price->disable == '0') _e('No','hotel'); else _e('Yes','hotel');?></th>
					</tr>
					<?php
                                            }
                                        }
                                        else
                                        {
                                        ?>
                                        <tr><td colspan="9"><i><?php _e('No price has been found yet','hotel'); ?></i></td></tr>
                                        <?php } ?>                                        					
				</tbody>
				<tfoot> <!-- footer -->
                                    <tr>
                                        <th class="check-column"><input type="checkbox" style="width:14px; height:14px;" /></th>
                                        <th width="auto" style=""><?php _e('ID','hotel');?></th>
                                        <th width="auto" style=""><?php _e('Time Type','hotel');?></th>
                                        <th width="auto" style=""><?php _e('Time','hotel');?></th>
                                        <th width="auto" style=""><?php _e('Room Type','hotel');?></th>
                                        <th width="auto" style=""><?php _e('New Price','hotel');?></th>
                                        <th width="auto" style=""><?php _e('Priority','hotel');?></th>
                                        <th width="auto" style=""><?php _e('Start Date','hotel');?></th>
                                        <th width="auto" style="text-align: center;"><?php _e('Activated','hotel');?></th>
                                    </tr>
				</tfoot> <!-- //footer -->
                            </table> <!-- //list room -->
			</form> <!-- // form sort -->
			<div class="clear"></div>
		</div> <!-- //item -->
	</div> <!-- //settings -->
</div> <!-- //end wrap -->
<?php
function get_priority($id)
{
    $priority = '';
    switch ($id)
    {
        case '1':
            $priority = __('Very high','hotel');
            break;
        case '2':
            $priority = __('High','hotel');
            break;
        case '3':
            $priority = __('Normal','hotel');
            break;
        case '4':
            $priority = __('Low','hotel');
            break;
        case '5':
            $priority = __('Very low','hotel');
            break;
    }
    return $priority;
}
function get_room_type_name()
{
    $room_type_name = array();
    $args = array(
            'post_status' => 'publish',
            'post_type' => 'roomtype',
            'posts_per_page' => -1,
            'order' => 'ASC'
            );
    $room_type_lists = query_posts($args);
    if(!empty($room_type_lists))
    {
        foreach ($room_type_lists as $room_type)
        {
            $room_type_name[$room_type->ID] = $room_type->post_title;
        }
    }
    return $room_type_name;
}
function get_pricing_time($time, $time_type)
{
    $result = '';
    switch ($time_type)
    {
        case 'weekly':
            $result = $time;
            break;
        case 'monthly':
            $result = $time;
            break;
        case 'yearly':
            $year = date('Y');
            $times = explode(',', $time);
            $t = reset($times);
            $from_time = explode('-', $t);           
            $result .= __('From ','hotel') . $from_time[0] . '/' . $from_time[1];
            $t = end($times);
            $to_time = explode('-', $t);
            $result .= __(' to ','hotel') . $to_time[0] . '/' . $to_time[1];
            break;
    }
    return $result;
}
?>