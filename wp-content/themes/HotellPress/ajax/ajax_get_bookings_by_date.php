<?php


add_action('do_ajax', 'ajax_get_bookings_by_date' );

function ajax_get_bookings_by_date()
{
	
	
	
	header('HTTP/1.1 200 OK');
	header('Cache-Control: no-cache, must-revalidate');
	header('Content-type: text/html');
	?>
	
	<div id="booking_tooltip" class="booking-tooltip">
		<div class="booking-item">
			<h3>Andrew Nguyen</h3>
			<div class="booking-content">
				<b>Check in:</b> 05/07/2011 <br />
				<b>Check out:</b> 05/08/2011 <br />
				<b>Room Type:</b> Deluxe <br />
				<b>Service:</b>  <br />
			</div>
		</div>
		
		<div class="booking-item">
			<h3>Toan Nguyen</h3>
			<div class="booking-content">
				<b>Check in:</b> 05/07/2011  <br />
				<b>Check out:</b> 05/08/2011 <br />
				<b>Room Type:</b> Deluxe <br />
				<b>Service:</b>  <br />
			</div>
		</div>
	</div>
	
	<?php 
}

?>