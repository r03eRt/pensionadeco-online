<?php
$ht_version = get_theme_data(TEMPLATEPATH . '/style.css');
//$new_version = tgt_get_version(4);//get version of hotel
$xmlstr = wp_remote_fopen('http://www.dailywp.com/xml/versions.xml');
if($xmlstr != '')
{
	$xml = new SimpleXMLElement($xmlstr);
	$update = '';
	foreach($xml as $product) 
	{
		if($product->name==$ht_version['Name'])	
			break;
	}
	if($product->version != $ht_version['Version'])	
		$update= 1;
}
global $wpdb;
$count_room_type = $wpdb->get_var("SELECT COUNT(p.ID) FROM $wpdb->posts p WHERE  p.post_type = 'roomtype' AND p.post_status = 'publish'");

$count_room = $wpdb->get_var("SELECT COUNT(r.ID) FROM ".$wpdb->prefix."rooms r WHERE r.status='publish'");

$count_bookings = $wpdb->get_var("SELECT COUNT(*) FROM ".$wpdb->prefix."bookings WHERE `status`='publish'");

$tgt_rss_feed = 'http://www.dailywp.com/feed/';
?>
<div class="wrap">
        <div class="icon32" id="icon-themes"><br/></div>
        <h2><?php _e('Hotel Dashboard', 'hotel') ?></h2>        

        <div class="dash-left metabox-holder" style="width: 100%">
		<div class="postbox">
			<div class="statsico"></div>
			<h3 class="hndle"><span><?php _e('Hotel Info', 'hotel') ?></span></h3>
                <div class="preloader-container">
                    <div class="insider" id="boxy">    
                        <ul>
                            <li>
								<?php echo $ht_version['Name']; ?>: <strong><?php echo $ht_version['Version']; ?></strong>
                            </li>
                        </ul> 
                        <ul>
                            <li>
							<?php echo $product->name." ".$product->version;?> is available!
							<?php if($update==1):?>
                            <a href="http://www.dailywp.com/member/member.php" target="_blank">
                            	<?php _e('Please update now','jobpress');?> 
                            </a>
							<?php endif;?>
                            </li>
                        </ul>
                        <ul>
                            <li><?php _e('Total Room Types', 'hotel'); ?>: <strong><a href="admin.php?page=my-submenu-handle-list-room-types"><?php echo $count_room_type; ?></a></strong> </li>
                        </ul> 
                        <ul>
                            <li><?php _e('Total Rooms', 'hotel'); ?>: <strong><a href="admin.php?page=my-submenu-handle-list-rooms"><?php echo $count_room; ?></a></strong> </li>
                        </ul> 
                        <ul>
                            <li><?php _e('Total Bookings', 'hotel'); ?>: <strong><a href="admin.php?page=my-submenu-list-booking"><?php echo $count_bookings; ?></a></strong> </li>
                        </ul>  
                        <ul>
                            <li><?php _e('Product Support', 'hotel'); ?>: <a href="http://dailywp.com"><?php _e('Technical','jobpress');?></a></li>
                        </ul>
                    </div>
                </div>
		</div> <!-- postbox end -->

		<div class="postbox">
			<div class="newspaperico"></div><a target="_new" href="<?php echo $tgt_rss_feed ?>"><div class="rssico"></div></a>
			<h3 class="hndle" id="poststuff"><span><?php _e('Latest News', 'hotel') ?></span></h3>
             <div class="preloader-container">
		<div class="insider" id="boxy">
			<?php 
			wp_widget_rss_output($tgt_rss_feed, array('items' => 5, 'show_author' => 0, 'show_date' => 1, 'show_summary' => 1)); 
			?>
		</div> <!-- inside end -->
             </div>
		</div> <!-- postbox end -->
	</div> <!-- dash-left end -->
    
	
</div> <!-- /wrap -->
