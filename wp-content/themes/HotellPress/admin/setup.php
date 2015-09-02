<?php
//tgt_set_default_menu();
//contain function for system setup. Ex: active, reactive, or set default for menubar
/**
 * 
 * set some default value when active template
 * create tables: $wp->bookings and $wp->rooms
 */
function tgt_theme_setup(){
	/*
	 * Start: Add more option for new version
	 */
	$check_new_option = get_option('tgt_installed_new'); 
	if(!$check_new_option){
		add_option('tgt_custom_css', '');
		add_option('tgt_custom_script', '');
		add_option('tgt_contact_page','');
		add_option('tgt_location_page','');
		add_option('tgt_max_people_per_booking');
		add_option('tgt_max_rooms_per_booking');
		add_option('tgt_tax');
		add_option('tgt_promotion_date');
		update_option('tgt_installed_new',1);
	}			
	/*
	 * End: Add more option for new version
	 */
	$flag = get_option('tgt_theme_installed'); // have 2 value 1/null  (installed or not install)
	$flag_version_2 = get_option('tgt_version_2');
	if(!$flag){ //active or reactive
		
		//Set up default values

		// Defalut Currency
		add_option('tgt_currency',"USD");
		
		//lay out default
		// Defalut Favicon
		add_option('tgt_default_favicon',"/favicon.ico");
		
		// Defalut Logo
		add_option('tgt_default_logo',"/images/logo.png");
		
		// Default Main Background
		add_option('tgt_default_background',"/images/background.jpg");
		
		// Default Inner Background
		add_option('tgt_default_inner_background',"/images/inner_bg.jpg");
		
		// Default Image Slider
		add_option('tgt_image_slider',array("i_1"=>"/images/slide.jpg",
									  "i_2"=>"/images/17.JPG",
									  "i_3"=>"/images/20.JPG",
									  "i_4"=>"/images/22.JPG",
									  "i_5"=>"/images/13.JPG"));
		
		//Manual Setting
		add_option('tgt_permit_payment',1);//setting pay permition ??
		add_option('tgt_deposit_percent',100);//setting deposit percent on total price.
		
		$success_mess = "Congratulation, your book room online successfully!\nWe recommend you to save the Transaction No.\nYou will need it if you want to change or delete your booking. ";
		add_option('tgt_booking_success',$success_mess);
		
		add_option('tgt_successmail_payment','1');// Default for send email when customer payment booking success is Enable
		
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php'); //for dbDelta function
		global $wpdb;
		
		$table_name = $wpdb->prefix . 'rooms';
		$sql = "CREATE TABLE IF NOT EXISTS " . $table_name . " (
			`ID` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
			`room_name` VARCHAR(255) DEFAULT '',
			`room_type_ID` BIGINT(20) UNSIGNED NOT NULL,
			`status` VARCHAR(20) NOT NULL,
			PRIMARY KEY id (`ID`) 
		);";	
	    dbDelta($sql); //create wp_rooms table
	    
	    $table_name = $wpdb->prefix . 'bookings';
		$sql = "CREATE TABLE IF NOT EXISTS " . $table_name . " (
			`ID` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
			`room_ID` BIGINT(20) UNSIGNED NOT NULL,
			`user_ID` BIGINT(20) UNSIGNED NOT NULL,
			`check_in` BIGINT(20) UNSIGNED NOT NULL,
			`check_out` BIGINT(20) UNSIGNED NOT NULL,
			`status` VARCHAR(20) NOT NULL,
			PRIMARY KEY id (`ID`) 
		);";	
	    dbDelta($sql); //create wp_rooms table
		
	    tgt_set_default_menu();
	    
		update_option('tgt_theme_installed', 1); //theme installed
	}
	if(!$flag_version_2){
		global $wpdb;
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php'); //for dbDelta function
		$table_name = $wpdb->prefix . 'pricing';
		$sql = "CREATE TABLE IF NOT EXISTS ". $table_name ." (
			`ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                        `time_type` varchar(20) NOT NULL,
                        `time` longtext NOT NULL,
                        `room_type_id` bigint(20) NOT NULL,
                        `new_price_change` longtext NOT NULL,
                        `priority` bigint(20) NOT NULL,
                        `date_start` date NOT NULL,
                        `disable` varchar(1) NOT NULL,
                        PRIMARY KEY (`ID`)
		);";	
	    dbDelta($sql); //create wp_rooms table
	    
		$table_name = $wpdb->prefix . 'transaction';
		$sql = "CREATE TABLE IF NOT EXISTS ". $table_name ." (
			`ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			`booking_id` BIGINT NOT NULL,
			`customer_id` BIGINT NOT NULL,			
			`date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
			`amount` DECIMAL(20, 2) NULL,
			`currency` varchar(10) NULL,
			PRIMARY KEY (`ID`)
		);";	
	    dbDelta($sql); //create wp_rooms table
		update_option('tgt_version_2', 1);
	}
}
/**
 * 
 * set default header menu, if defaults pages no exist, create auto dafault pages
 */
function tgt_set_default_menu(){
	
$pages_default = get_option('tgt_pages_default', array()); //cointain link pages default

wp_delete_nav_menu('header_menu');
wp_delete_nav_menu('footer_menu');	
$header_menu = wp_get_nav_menu_object('header_menu');
if($header_menu)$header_menu_id = $header_menu->term_id;
else $header_menu_id = wp_create_nav_menu('header_menu');// create header menu bar


$footer_menu = wp_get_nav_menu_object('footer_menu');
if($footer_menu)$footer_menu_id = $footer_menu->term_id;
else $footer_menu_id = wp_create_nav_menu('footer_menu');//create footer menu bar



$pages_default['header_menu']['id'] =  $header_menu_id;
$pages_default['footer_menu']['id'] =  $footer_menu_id;

if (get_post_status(98))
	wp_update_post(array('ID'=>98, 'post_status' => 'publish'));
if (get_post_status(99))
	wp_update_post(array('ID'=>99, 'post_status' => 'publish'));

$my_post = array(	   
	     'post_status' => 'publish',
	     'post_author' => 1,
	     'post_type' => 'page',
	 );
	 
	 //create page for footer menu
	 if(get_post_status($pages_default['footer_menu']['privacy_policy']) != "publish"){
	 	$my_post['post_title'] = "Privacy Policy";
	 	$my_post['post_content'] = 'You can edit it at Pages -> About Us in Administrator Wordpress Page. If you don\'t want to show it on MenuBar, you can edit it at Appearance ->Menus->footer_menu
If you want to delete this page, you can delete it at Pages in Wordpress Admin page, but you shouldn\'t do it. <br/>If you deleted this page, you could restore it by set default button on Layout Settings
	 	';
	 	$id = wp_insert_post($my_post); //create "Privacy Policy" page	 		
	 	$pages_default['footer_menu']['privacy_policy'] = $id;	
	 }
	 $item_id = intval($pages_default['footer_menu']['privacy_policy_id']);
	 if (!is_nav_menu_item($item_id)) $item_id = 0;
	 $pages_default['footer_menu']['privacy_policy_id'] = wp_update_nav_menu_item ($footer_menu_id,
	 	$item_id, array ('menu-item-title' => 'Privacy Policy',
		'menu-item-url' => get_permalink($pages_default['footer_menu']['privacy_policy']),
	 	'menu-item-status' => 'publish'));
	 	
	 if(get_post_status($pages_default['footer_menu']['learn_more']) != "publish"){
	 	$my_post['post_title'] = "Learn More";
	 	$my_post['post_content'] = 'You can edit it at Pages -> Learn More in Administrator Wordpress Page. 
If you want to delete this page, you can delete it at Pages in Wordpress Admin page, but you shouldn\'t do it. <br/>If you deleted this page, you could restore it by set default button on Layout Settings
	 	';
	 	$id = wp_insert_post($my_post); //create "Privacy Policy" page	 		
	 	$pages_default['footer_menu']['learn_more'] = $id;	
	 }


	 
	 if(get_post_status($pages_default['footer_menu']['terms_of_use']) != "publish"){
	 	$my_post['post_title'] = "Terms Of Use";
	 	$my_post['post_content'] = 'If you don\'t want to show it on MenuBar, you can edit it at Appearance->Menus->footer_menu
If you want to delete this page, you can delete it at Pages in Wordpress Admin page, but you shouldn\'t do it. <br/>If you deleted this page, you could restore it by set default button on Layout Settings
	 	';
	 	$id = wp_insert_post($my_post); //create "Terms Of Use" page	 		
	 	$pages_default['footer_menu']['terms_of_use'] = $id;	
	 }
	 $item_id = intval($pages_default['footer_menu']['terms_of_use_id']);
	 if (!is_nav_menu_item($item_id)) $item_id = 0;
	 $pages_default['footer_menu']['terms_of_use_id'] = wp_update_nav_menu_item ($footer_menu_id,
	 	$item_id, array ('menu-item-title' => 'Terms Of Use',
		'menu-item-url' => get_permalink($pages_default['footer_menu']['terms_of_use']),
	 	'menu-item-status' => 'publish'));							
	 
	 
	 //create pages for header menu
	 $item_id = intval($pages_default['header_menu']['home_id']);
	 if (!is_nav_menu_item($item_id)) $item_id = 0;
	 $pages_default['header_menu']['home_id'] = wp_update_nav_menu_item ($header_menu_id, 
	 	$item_id, array ('menu-item-title' => 'Home',
		'menu-item-url' => HOME_URL,
	 	'menu-item-status' => 'publish'));	 //create home link 
	 
	if(get_post_status($pages_default['header_menu']['about_us']) != 'publish'){
	 	$my_post['post_title'] = "About Us";
	 	$my_post['post_content'] = "This is About Us Page. You can edit it at Pages -&gt; About Us in Administrator Wordpress Page. It shows detail about history, founder, employee and (maybe) pictures in outdoor trips. If you don't want to show this page, you can edit it at  Apprearance-&gt;Menus-&gt;header_ menu.

<br/>If you want to delete this page, you can delete it at Pages in Wordpress Admin page, but you shouldn't do it. If you deleted this page, you could restore it by set default button on Layout Settings";
	 	$id = &wp_insert_post($my_post); //create "About Us" page
	 	$pages_default['header_menu']['about_us'] = $id;	
	 }
	 $item_id = intval($pages_default['header_menu']['about_us_id']);
	 if (!is_nav_menu_item($item_id)) $item_id = 0;
	 $pages_default['header_menu']['about_us_id'] = wp_update_nav_menu_item ($header_menu_id, 
	 	$item_id, array ('menu-item-title' => 'About Us',
		'menu-item-url' => get_permalink($pages_default['header_menu']['about_us']),
	 	'menu-item-status' => 'publish')); //create "about us" page link

	$item_id = intval($pages_default['header_menu']['roomtypes_id']);
	 if (!is_nav_menu_item($item_id)) $item_id = 0;
	$pages_default['header_menu']['roomtypes_id'] = wp_update_nav_menu_item ($header_menu_id, 
		$item_id, array ('menu-item-title' => 'Rooms',
		'menu-item-url' => tgt_get_roomtypes_link(),
	 	'menu-item-status' => 'publish')); //create rooms type link
		
	if (!is_category($pages_default['header_menu']['news_category']) && !get_cat_ID('News')){
		include_once ABSPATH . 'wp-admin/includes/taxonomy.php';
		$pages_default['header_menu']['news_category'] = wp_insert_category(array(cat_name => 'News', 'category_description' => 'This is News category'));
	}
	//print_r ($pages_default['header_menu']['news_category']);
	//exit;
	
	$item_id = intval($pages_default['header_menu']['news_category_id']);
	if (!is_nav_menu_item($item_id)) $item_id = 0;
	$pages_default['header_menu']['news_category_id'] = wp_update_nav_menu_item ($header_menu_id,
	 $item_id, array ('menu-item-title' => 'News',
		'menu-item-url' => get_category_link($pages_default['header_menu']['news_category']),
	 	'menu-item-status' => 'publish')); //create News category link
	
	
	
	//nen them vao cho nay 1 bai example cho category "News"
	if(get_post_status(99) != 'publish'){
		$my_post['post_title'] = "Location";
		$my_post['import_id'] = 99;
		$my_post['post_content'] = 'This is "Location" page. You can edit content and title this page at Pages -&gt;Location in Wordpress Admin page. You can show address hotel in Google Map if you set your address hotel at Location Settings.
<br/>If you don\'t want to show it on MenuBar, you can edit it at Appearance-&gt;Menus-&gt;header_menu
<br/>If you want to delete this page, you can delete it at Pages in Wordpress Admin page, but you shouldn\'t do it. If you deleted this page, you could restore it by set default button on Layout Settings';
		wp_update_post ($my_post); // Add "Location" page
	}
	$item_id = intval($pages_default['header_menu']['location_id']);
	if (!is_nav_menu_item($item_id)) $item_id = 0;
	$pages_default['header_menu']['location_id'] = wp_update_nav_menu_item ($header_menu_id, 
		$item_id, array ('menu-item-title' => 'Location',
		'menu-item-url' => get_permalink(99),
	 	'menu-item-status' => 'publish')); //create "Location" page link
	
	if(get_post_status($pages_default['header_menu']['services']) != 'publish'){
	 	$my_post['post_title'] = "Service";
	 	$my_post['post_content'] = 'This is Services Page. You can edit it at Pages -> Sevices in Administrator Wordpress Page. It displays many Services have in hotel ( include images).  If you don\'t want to show it on MenuBar, you can edit it at Appearance ->Menus->header_menu
<br/>If you want to delete this page, you can delete it at Pages in Wordpress Admin page, but you shouldn\'t do it. If you deleted this page, you could restore it by set default button on Layout Settings
	 	';
	 	$id = wp_insert_post($my_post); //create "Services" page
	 	$pages_default['header_menu']['services'] = $id;	
	 }
	 $item_id = intval($pages_default['header_menu']['services_id']);
	if (!is_nav_menu_item($item_id)) $item_id = 0;
	$pages_default['header_menu']['services_id'] = wp_update_nav_menu_item ($header_menu_id, 
		$item_id, array ('menu-item-title' => 'Services',
		'menu-item-url' => get_permalink($pages_default['header_menu']['services']),
	 	'menu-item-status' => 'publish')); //create "Services" page link
		
	if(get_post_status(98) != 'publish'){		
		$my_post['import_id'] = 98;
		$my_post['post_title'] = 'Contact Us';
		$my_post['post_content'] = "If you don't want to show it on MenuBar, you can edit it at Appearance-&gt;Menus-&gt;header_menu

<br/>If you want to delete this page, you can delete it at Pages in Wordpress Admin page, but you shouldn't do it. If you deleted this page, you could restore it by set default button on Layout Settings"; 	
		wp_update_post($my_post); //Add "Contact Us" page
	} 
	$item_id = intval($pages_default['header_menu']['contact_us_id']);
	if (!is_nav_menu_item($item_id)) $item_id = 0;
	$pages_default['header_menu']['contact_us_id'] = wp_update_nav_menu_item ($header_menu_id, 
		$item_id, array ('menu-item-title' => 'Contact Us',
		'menu-item-url' => get_permalink(98),
	 	'menu-item-status' => 'publish')); //create "Services" page link
	
	$pages_default['header_menu']['contact_us'] = 98;
	$pages_default['header_menu']['location'] = 99;
	
	update_option('tgt_pages_default', $pages_default); 
}