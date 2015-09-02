<?php
@ini_set('display_errors', 0);
global $wpdb;
// default
$currentlink = HOME_URL . '/wp-admin/admin.php?page=reservations';
$from 	  = strtotime('-100 day') ;
$to 		  = strtotime('+300 day');
$roomtypes_filter = array();

//icl_object_id(ID, type, return_original_if_missing, language_code)

$roomtypes = get_posts( array(
										'post_type' => 'roomtype',
										'post_status' => 'publish',
										'suppress_filters' => false,
										'nopaging' => true
										) );
/**
 * Filter
 */
// get from date filter
if ( !empty( $_GET['from'] ) )
	$from = strtotime( $_GET['from'] );
	
// get to date filter
if ( !empty( $_GET['to'] ) )
	$to = strtotime( $_GET['to'] );

// get room types
if ( !empty( $_GET['roomtypes'] ) )
	$roomtypes_filter = $_GET['roomtypes'];	

// query booking
$tbooking 	= $wpdb->prefix . 'bookings';
$trooms 		= $wpdb->prefix . 'rooms';
$troomtype 	= $wpdb->posts;
$sql 			= "SELECT b.*, r.room_name, rt.post_title FROM  {$tbooking} b
					LEFT JOIN {$trooms} r ON b.room_ID = r.ID
					LEFT JOIN {$troomtype} rt ON r.room_type_ID = rt.ID
					WHERE b.check_in >= $from AND b.check_out <= $to AND b.status = 'publish' ";

if ( !empty($roomtypes_filter) )
{
	$sql .= ' AND r.room_type_ID IN  (' . implode(',', $roomtypes_filter) . ' ) ';
}

$results = $wpdb->get_results ($wpdb->prepare ($sql));

// generate bookings list
$bookings = array();
foreach ( $results as $result )
{
	if ( !isset ( $bookings[ $result->user_ID ] ) )
		$bookings[ $result->user_ID ] = array(
														  'check_in' => date ('Y-m-d', $result->check_in ) . ' 12:00:00',
														  'check_out' => date ('Y-m-d', $result->check_out ) . ' 12:00:00',
														  'room_type' => $result->post_title ,
														  'rooms' =>array( array (
																		'ID' => $result->room_ID,
																		'name' => $result->room_name
																				  ))
														  );
	else
		$bookings[ $result->user_ID ][ 'rooms' ][] = array (
																		'ID' => $result->room_ID,
																		'name' => $result->room_name
																		);
}

function generateBookingsJS($bookings)
{
	$count = 0;
	$json = '[';
	foreach( (array) $bookings as $key => $booking )
	{
		if ( $count != 0 )
			$json .= ' , ';
		$json .= ' { id: '. $key .' , ';
		$json .= ' title: "'. $booking[ 'room_type' ] .'" , ';
		$json .= ' start: "'. date( 'c', strtotime ( $booking[ 'check_in' ] )).'" , ';
		$json .= ' end: "'. date( 'c', strtotime ( $booking[ 'check_out' ])) .'" , ';
		$json .= ' allDay: false , ';
		$json .= ' url: "' . HOME_URL . '/wp-admin/admin.php?page=my-submenu-handle-add-booking&editbooking=true&uid=' . $key .'" }';
		$count++;
	}
	$json .= '];';
	return $json;
}


?>

<div class="wrap">
	<div class="atention">
		<strong></strong> Contact us at <em><a href="http://www.dailywp.com/support/">Support</a></em>. 
	</div>
		<div class="heading">
			<h2><?php _e ( 'Reservations Calendar' , 'hotel' )  ?></h2>
			<div class="cl"></div>
		</div>
		<div class="item metabox-holder">
			<div id="filter_calendar" class="postbox">
				<h3 class="hndle"> <strong><?php _e('Filter', 'hotel') ?></strong> </h3>
				<div class="inside">
					<form action="<?php $currentlink?>" method="get">
						<input type="hidden" name="page" value="<?php echo 'reservations'?>" />
						<p class="filter-item-field">
							<label for="" class="howto">
								<span><?php _e('From', 'hotel') ?></span>
								<input type="text" name="from" id="from_filter" class="filter_date" value="<?php echo $_GET['from'] ? $_GET['from'] : '' ?>" />
							</label>
						</p>
						<p class="filter-item-field">
							<label for="" class="howto">
								<span><?php _e('To', 'hotel') ?></span>
								<input type="text" name="to" id="to_filter" class="filter_date" value="<?php echo $_GET['to'] ? $_GET['to'] : '' ?>" />
							</label>
						</p>
						<p class="filter-item-field">
							<label for="" class="howto">
								<span><?php _e('Room Type', 'hotel') ?></span>
								<div style="clear: both; padding-left: 10px; font-style: normal; color: #353535;">
									<?php foreach ($roomtypes as $rt)
									{
										$checked = '';
										if ( empty ( $roomtypes_filter ) || in_array( $rt->ID, $roomtypes_filter ) )
											$checked = 'checked="checked"';
										?>
										<input type="checkbox" name="roomtypes[]" value="<?php echo $rt->ID ?>" <?php echo $checked ?>/> <?php echo $rt->post_title ?> <br />
										<?php
									}
									
									?>
								</div>
							</label>
						</p>
						<div class="clear"></div>
						<p class="filter-item-field">
							<input type="submit" value="Filter" class="button-primary" />
						</p>
					</form>
				</div>
			</div>
			<div id="res_calendar_container" class="postbox">
				<h3><?php _e( 'Calendar' ,'hotel')  ?></h3>
				<div id="res_calendar"></div>
			</div>
			<div id="calendar_desc" style="display :none">
				<?php
				foreach( $bookings as $key => $booking )
				{
					$customer = get_user_meta( $key , 'tgt_customer_title' , true)	. ' ' .	get_user_meta( $key , 'first_name' , true) . ' ' . get_user_meta( $key , 'last_name' , true)		
				?>
					<div id="booking_<?php echo $key ?>" class="booking-tooltip">
						<h4> <?php echo $booking[ 'room_type' ] ?> </h4>
						<p><b> <?php _e('Check in', 'hotel') ?>:</b> <?php echo date('M d,Y', strtotime( $booking[ 'check_in' ] ) ) ?> </p>
						<p><b> <?php _e('Check out', 'hotel') ?>: </b> <?php echo date('M d,Y', strtotime( $booking[ 'check_out' ] ) ) ?> </p>
						<p><b> <?php _e('Customer', 'hotel') ?>: </b> <?php echo $customer ?> </p>
					</div>
				<?php
				}
				
				?>				
				
			</div>

		</div>
</div>

<script type="text/javascript">
	
	jQuery(document).ready(function($){
		//generate tooltip		
		
		//prepare data
		var date = new Date();
		var d = date.getDate();
		var m = date.getMonth();
		var y = date.getFullYear();
		
		events = <?php echo generateBookingsJS($bookings); ?>
		// generate calendar
		$('#res_calendar').eventCalendar(events);
		
		datepicker_initialize();
	});
	
	function datepicker_initialize()
	{
		$('.filter_date').datepicker();
		
		$('#from_filter').change(function(){
			var current = $(this);
			alert(current.val())
			$('#to_filter').datepicker("option", 'minDate' , current.val() );
		});
		
		$('#to_filter').change(function(){
			var current = $(this);
			$('#from_filter').datepicker("option", 'maxDate' , current.val() );
		})
	}
	
</script>