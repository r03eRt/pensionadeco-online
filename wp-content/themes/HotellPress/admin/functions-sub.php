<?php
//redirect to admin dashboard after admin login
/**
 * redirect to dashboard when login to admin slide
 */
add_action( 'login_redirect', 'hook_login_redirect_tgt');
function hook_login_redirect_tgt( $redirect ) {
	if ($redirect == get_admin_url())
		return 'wp-admin/admin.php?page=my-submenu-handle-dashboard';
	
	return $redirect;
}

/******************* Admin Site *******************/
function mytheme_hotel_settings_add_admin() {	
//Setup system (active, reactive template, set default menubar)
require_once TEMPLATEPATH . '/admin/setup.php';
tgt_theme_setup(); // setup default value, create tables
//session_start();
add_menu_page('Hotel page', 'Hotel', '', 'my-top-level-handle', 'hotel_settings','','3');
add_submenu_page( 'my-top-level-handle', __ ('Dashboard', 'hotel'), __ ('Dashboard', 'hotel'), 'edit_posts', 'my-submenu-handle-dashboard', 'mytheme_admin_dashboard');
add_submenu_page( 'my-top-level-handle', __ ('Layout Settings', 'hotel'), __ ('Layout Settings', 'hotel'), 'edit_posts', 'my-submenu-handle-layout-settings', 'mytheme_admin_layout_settings');
add_submenu_page( 'my-top-level-handle', __ ('Location Settings', 'hotel'), __ ('Location Settings', 'hotel'), 'edit_posts', 'my-submenu-handle-location-settings', 'mytheme_admin_location_settings');
add_submenu_page( 'my-top-level-handle', __ ('Global Settings', 'hotel'), __ ('Global Settings', 'hotel'), 'edit_posts', 'my-submenu-handle-settings', 'mytheme_admin_settings');
//add_submenu_page( 'my-top-level-handle', __ ('Add Room Type', 'hotel'), __ ('Add Room Type', 'hotel'), 'edit_posts', 'my-submenu-handle-add-room-type', 'mytheme_admin_add_room_type');
//add_submenu_page( 'my-top-level-handle', __ ('List Room Types', 'hotel'), __ ('List Room Types', 'hotel'), 'edit_posts', 'my-submenu-handle-list-room-types', 'mytheme_admin_list_room_types');
add_submenu_page( 'my-top-level-handle', __ ('New Room', 'hotel'), __ ('New Room', 'hotel'), 'edit_posts', 'my-submenu-handle-add-room', 'mytheme_admin_add_room');
add_submenu_page( 'my-top-level-handle', __ ('Rooms List', 'hotel'), __ ('Rooms', 'hotel'), 'edit_posts', 'my-submenu-handle-list-rooms', 'mytheme_admin_list_rooms');
add_submenu_page( 'my-top-level-handle', __ ('New Reservation', 'hotel'), __ ('New Reservation', 'hotel'), 'edit_posts', 'my-submenu-handle-add-booking', 'mytheme_admin_add_booking');		
add_submenu_page( 'my-top-level-handle', __ ('Reservations List', 'hotel'), __ ('Reservations', 'hotel'), 'edit_posts', 'my-submenu-list-booking', 'mytheme_admin_list_bookings');
add_submenu_page( 'my-top-level-handle', __ ('Reservation Calendar', 'hotel'), __ ('Reservations Calendar', 'hotel'), 'edit_posts', 'reservations', 'admin_reservations');
add_submenu_page( 'my-top-level-handle', __ ('Testimonials', 'hotel'), __ ('Testimonials', 'hotel'), 'edit_posts', 'my-submenu-manager-testimonial', 'mytheme_admin_manager_testimonial');
add_submenu_page( 'my-top-level-handle', __ ('Services', 'hotel'), __ ('Services', 'hotel'), 'edit_posts', 'my-submenu-manager-option', 'mytheme_admin_manager_option');


add_submenu_page( 'edit.php?post_type=roomtype', __ ('Additional Fields', 'hotel'), __ ('Additional Fields', 'hotel'), 'edit_posts', 'my-submenu-list-custom-field-roomtype', 'mytheme_admin_list_custom_field_roomtype');
add_submenu_page( 'edit.php?post_type=roomtype', __ ('Add Field', 'hotel'), __ ('Add Field', 'hotel'), 'edit_posts', 'my-submenu-custom-field-roomtype', 'mytheme_admin_custom_field_roomtype');


add_submenu_page( 'my-top-level-handle', __ ('New Pricing', 'hotel'), __ ('Pricing', 'hotel'), 'edit_posts', 'my-submenu-pricing', 'mytheme_admin_pricing');
add_submenu_page( 'my-top-level-handle', __ ('Pricings List', 'hotel'), __ ('List Pricings', 'hotel'), 'edit_posts', 'my-submenu-list-pricing', 'mytheme_admin_list_pricings');
add_submenu_page( 'my-top-level-handle', __ ('Transactions Log', 'hotel'), __ ('Transactions Log', 'hotel'), 'edit_posts', 'my-submenu-transaction-log', 'mytheme_admin_transaction_log');
add_submenu_page( 'my-top-level-handle', __ ('Promotion', 'hotel'), __ ('Promotion', 'hotel'), 'edit_posts', 'my-submenu-promotion', 'mytheme_admin_promotion');
add_submenu_page( 'my-top-level-handle', __ ('List Promotions', 'hotel'), __ ('List Promotions', 'hotel'), 'edit_posts', 'my-submenu-list-promotions', 'mytheme_admin_list_promotions');
//add_submenu_page( 'my-top-level-handle', __ ('Languages', 'hotel'), __ ('Languages', 'hotel'), 'edit_posts', 'languages', 'mytheme_admin_languages');

}
add_action('admin_menu', 'mytheme_hotel_settings_add_admin');


function hotel_style() {
		?>
		<meta name="url" content="<?php bloginfo('wpurl') ?>" />
		<link rel="stylesheet" href="<?php echo TEMPLATE_URL ?>/css/humanity/jquery-ui.css" />
		<link rel="stylesheet" href="<?php echo TEMPLATE_URL ?>/css/style-admin.css" />
		<link rel="stylesheet" href="<?php echo TEMPLATE_URL ?>/css/fullcalendar.css" />
			
		<?php 
}

function hotel_script()
{
	if ( isset($_GET['page']) )
	{
		wp_register_script( 'jquery-ui-datepicker-hotel', TEMPLATE_URL . '/js/jquery.ui.datepicker.js' );
		wp_enqueue_style( 'fullcalendar');
		wp_enqueue_style( 'humanity-jquery-ui' );
		wp_enqueue_style( 'style-admin');
		if($_GET['page']=="reservations"){
			wp_enqueue_script('hotel-jquery');
		}
		//wp_enqueue_script('jquery-ui-datepicker');
		wp_enqueue_script('jquery-ui-datepicker-hotel');
		wp_enqueue_script('jquery-ui-calendar');
		wp_enqueue_script('fullcalendar');
		wp_enqueue_script('admin-script');
	}
}	
add_action('admin_enqueue_scripts', 'hotel_script');

function mytheme_admin_dashboard()
{
    require_once dirname( __FILE__ ) . '/dashboard.php';
}
function mytheme_admin_layout_settings()
{
    require_once dirname( __FILE__ ) . '/layout_settings.php';
}
function mytheme_admin_location_settings()
{
    require_once dirname( __FILE__ ) . '/location_settings.php';
}
function mytheme_admin_settings()
{
    require_once dirname( __FILE__ ) . '/settings.php';
}
function mytheme_admin_add_room_type()
{
    require_once dirname( __FILE__ ) . '/add_roomtype.php';
}
function mytheme_admin_list_room_types()
{
    require_once dirname( __FILE__ ) . '/list_roomtypes.php';
}
function mytheme_admin_add_room()
{
    require_once dirname( __FILE__ ) . '/add_room.php';
}
function mytheme_admin_list_rooms()
{
    require_once dirname( __FILE__ ) . '/list_rooms.php';
}
function mytheme_admin_add_booking()
{
    require_once dirname( __FILE__ ) . '/add_booking.php';
}
function mytheme_admin_list_bookings()
{
    require_once dirname( __FILE__ ) . '/list_bookings.php';
}
function mytheme_admin_manager_testimonial()
{
    require_once dirname( __FILE__ ) . '/manager_testimonial.php';
}
function mytheme_admin_custom_field_roomtype()
{
    require_once dirname( __FILE__ ) . '/add_custom_field_roomtype.php';
}

function mytheme_admin_transaction_log()
{
	require_once dirname( __FILE__ ) . '/transaction_log.php';
}

function mytheme_admin_pricing()
{
    require_once dirname( __FILE__ ) . '/pricing.php';
}

function mytheme_admin_list_custom_field_roomtype()
{
    require_once dirname( __FILE__ ) . '/list_custom_field_roomtype.php';
}

function admin_reservations()
{
	require_once dirname( __FILE__ ) . '/reservations.php';
}
function mytheme_admin_list_pricings()
{
    require_once dirname( __FILE__ ) . '/admin_list_pricing.php';
}
function mytheme_admin_manager_option()
{
	require_once dirname( __FILE__ ) . '/manager_services.php';
}
function mytheme_admin_promotion()
{
	require_once dirname( __FILE__ ) . '/admin_promotion.php';
}
function mytheme_admin_list_promotions()
{
	require_once dirname( __FILE__ ) . '/admin_list_promotion.php';
}
function mytheme_admin_languages()
{
	require_once dirname( __FILE__ ) . '/admin_languages.php';
}
?>