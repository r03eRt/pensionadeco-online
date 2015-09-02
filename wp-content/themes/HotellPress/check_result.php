<?php get_header();?>         
       
<?php
/**
 * GET $_GET['code']
 */

if (!empty($_GET['code'])){
	$code = $_GET['code'];	
	$booking_info = tgt_get_booking_detail($code);	
}

?>
       
       <div class="middle-inner-wrapper" style="background:#e7dfd6 url(<?php echo TEMPLATE_URL.get_option('tgt_default_inner_background');?>) no-repeat center top;">
       
       		<div class="localization">
            	<p class="site-loc"> <a href="<?php echo HOME_URL;?>" style="color:white">
            		<?php echo get_option('tgt_hotel_name') == '' ? 'Hotel' : get_option('tgt_hotel_name') ?> </a> </p>
            		<p>&raquo;&nbsp; <?php _e('Booking Detail','hotel')?>
            	</p>
  			</div>
            
         <div style="clear:both;"></div>
         
            <div class="middle-inner">
       			<div class="center-inner">
	                <div class="title">
	            		
	            		<?php if (!empty($booking_info)) { ?>
	            		<p class="h1"><?php _e('Booking Detail','hotel'); ?></p> 
	            		
	            		<div class="contact-form" style="margin:15px 0;">
	                	<table width="100%">
	                    	<tbody>
	                        	<tr>
	                            	<td class="booking2"><strong> <?php _e('First Name', 'hotel') ?> :</strong></td>
	                                <td class="booking2"> <?php echo $booking_info->customer_first_name ?> </td>
	                            </tr>
	                            
	                        	<tr>
	                            	<td class="booking2"><strong> <?php _e('Last Name', 'hotel') ?> :</strong></td>
	                                <td class="booking2"> <?php echo $booking_info->customer_last_name ?> </td>
	                            </tr>
	                            
	                        	<tr>
	                            	<td class="booking2"><strong> <?php _e('Arrival Date', 'hotel') ?> :</strong></td>
	                                <td class="booking2"> <?php echo $booking_info->get_check_in() ?> </td>
	                            </tr>
	                            
	                        	<tr>
	                            	<td class="booking2"><strong> <?php _e('Departure Date', 'hotel') ?> :</strong></td>
	                                <td class="booking2"> <?php echo $booking_info->get_check_out() ?> </td>
	                            </tr>
	                            
		                        </tbody>
	                    </table>
	                          			
	                    <table class="roomslist" cellpadding="5"  style="width: 100%; margin-top: 10px;">
	                    	<tr> 
	                    		<th> <b> <?php _e('Room name','hotel'); ?> </b> </td>
	                    		<th> <b> <?php _e('Bed Type','hotel'); ?></b></td>
	                    		<th> <b> <?php _e('Pet','hotel'); ?> </b></td>
	                    		<th> <b> <?php _e('Smoking','hotel'); ?> </b></td>
	                    	</tr>
	                    	
	                    	<?php
	                    	
	                    	foreach ($booking_info->rooms as $room) {
	                    	?>
	                    	<tr>
	                    		<td class="booking2"> <?php echo $room->room_name ?>	</td>
	                    		<td class="booking2" align="center"> <?php echo $room->room_bed_type ?>	</td>
	                    		<td class="booking2" align="center"> <?php echo $room->room_pet_allowed ? __('Yes', 'hotel') : __('No', 'hotel') ?>	</td>
	                    		<td class="booking2" align="center"> <?php echo $room->room_smoking_allowed  ? __('Yes', 'hotel') : __('No', 'hotel') ?>	</td>
	                    	</tr>	
	                    	<?php 
	                    	} 
	                    	?>
	                    	
	                    </table>
                    
                	</div>
                	<?php 
	            		} else { ?>
	            			<p class="h1"> <?php _e('Code does not exist, please check again', 'hotel') ?> </p>
	            		<?php }
                	?>
	            		
	                </div>
				<a href="<?php echo tgt_get_link_print($code); ?>" target="_blank">
                <img src="<?php echo TEMPLATE_URL;?>/images/icon_print.png" />&nbsp;
				<font style="color: rgb(102, 138, 138);font:bold 11px arial;text-decoration:none;"><?php _e('Print this page','hotel'); ?></font>
				</a>
       				<!-- This is content -->
                    
                    <div style="clear:both;"></div>
        		</div>
			</div>
        
            <?php get_sidebar();?>
    	</div>
    <!-- content end -->
    <?php get_footer();?>
  