
<?php
/**
 * GET $_GET['code']
 */
var_dump ( $_GET['code'] );

if (!empty($_GET['code'])){
	$code = $_GET['code'];	
	$booking_info = tgt_get_booking_detail($code);	
}

?>       

       
       		
	            		
<?php if (!empty($booking_info)) { ?>
<p style="#282323;font-family:Georgia,Arial,Helvetica,sans-serif;font-size:35px;font-weight:normal;" align="center"><?php _e('Booking Detail','hotel'); ?></p>             		

<table width="20%">	
		<tr>
			<td><strong> <?php _e('First Name', 'hotel') ?> :</strong></td>
			<td> <?php echo $booking_info->customer_first_name ?> </td>
		</tr>
		
		<tr>
			<td><strong> <?php _e('Last Name', 'hotel') ?> :</strong></td>
			<td> <?php echo $booking_info->customer_last_name ?> </td>
		</tr>
		
		<tr>
			<td><strong> <?php _e('Arrival Date', 'hotel') ?> :</strong></td>
			<td> <?php echo $booking_info->get_check_in() ?> </td>
		</tr>
		
		<tr>
			<td><strong> <?php _e('Departure Date', 'hotel') ?> :</strong></td>
			<td> <?php echo $booking_info->get_check_out() ?> </td>
		</tr>
</table>
<br/>
<table  width="50%"  border="1" cellpadding="0.5" cellspacing="0.5">
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
		<td align="center"> <?php echo $room->room_name ?>	</td>
		<td align="center"> <?php echo $room->room_bed_type ?>	</td>
		<td align="center"> <?php echo $room->room_pet_allowed ? __('Yes', 'hotel') : __('No', 'hotel') ?>	</td>
		<td align="center"> <?php echo $room->room_smoking_allowed  ? __('Yes', 'hotel') : __('No', 'hotel') ?>	</td>
	</tr>	
	<?php 
	} 
	?>
	
</table>

</div>
<?php 
} else { ?>
	<p style="#282323;font-family:Georgia,Arial,Helvetica,sans-serif;font-size:35px;font-weight:normal;"> <?php _e('Code does not exist, please check again', 'hotel') ?> </p>
<?php }
?>	            	
<!-- This is content -->                  
<script type="text/javascript">
window.print();
</script>