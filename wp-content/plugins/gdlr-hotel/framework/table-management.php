<?php
	/*	
	*	Goodlayers Table Management File
	*/
	
	// create new table upon plugin activation
	if( !function_exists('gdlr_hotel_create_booking_table') ){
		function gdlr_hotel_create_booking_table(){
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			global $wpdb;
			
			// for online course
			$table_name = $wpdb->prefix . 'gdlr_hotel_booking';
			$sql = "CREATE TABLE $table_name (
				id bigint(20) unsigned NOT NULL auto_increment,
				payment_id bigint(20) unsigned NOT NULL,
				room_id bigint(20) unsigned DEFAULT NULL,
				start_date datetime DEFAULT NULL,
				end_date datetime DEFAULT NULL,
				PRIMARY KEY (id)
			);";
			dbDelta( $sql );
			
			// for payment transaction
			$table_name = $wpdb->prefix . 'gdlr_hotel_payment';
			$sql = "CREATE TABLE $table_name (
				id bigint(20) unsigned NOT NULL auto_increment,
				total_price decimal(19,4) DEFAULT NULL,
				pay_amount decimal(19,4) DEFAULT NULL,
				booking_data longtext DEFAULT NULL,
				contact_info longtext DEFAULT NULL,
				payment_info longtext DEFAULT NULL,
				payment_status varchar(20) DEFAULT NULL,
				payment_date datetime DEFAULT NULL,
				customer_code varchar(20) DEFAULT NULL,
				read_status varchar(20) DEFAULT NULL,
				PRIMARY KEY (id)
			);";
			dbDelta( $sql );	

			$hotel_option = get_option('gdlr_hotel_option', array());
			if(empty($hotel_option)){
				update_option('gdlr_hotel_option', unserialize('a:25:{s:12:"booking-slug";s:7:"booking";s:20:"transaction-per-page";s:2:"30";s:20:"booking-money-format";s:7:"$NUMBER";s:18:"booking-vat-amount";s:1:"8";s:22:"booking-deposit-amount";s:2:"20";s:14:"payment-method";s:7:"contact";s:22:"booking-thumbnail-size";s:15:"small-grid-size";s:17:"booking-num-fetch";s:1:"5";s:19:"booking-num-excerpt";s:2:"34";s:14:"mail-recipient";s:0:"";s:24:"booking-complete-contact";s:153:"<span style="margin-right: 20px;"><i class="fa fa-phone" ></i> +11-2233-442</span> <span><i class="fa fa-envelope"></i> sales@hotelmastertheme.com</span>";s:19:"booking-code-prefix";s:4:"GDLR";s:19:"room-thumbnail-size";s:19:"post-thumbnail-size";s:22:"paypal-recipient-email";s:17:"testmail@test.com";s:17:"paypal-action-url";s:37:"https://www.paypal.com/cgi-bin/webscr";s:20:"paypal-currency-code";s:3:"USD";s:17:"stripe-secret-key";s:0:"";s:22:"stripe-publishable-key";s:0:"";s:20:"stripe-currency-code";s:3:"usd";s:19:"paymill-private-key";s:0:"";s:18:"paymill-public-key";s:0:"";s:21:"paymill-currency-code";s:3:"usd";s:16:"authorize-api-id";s:0:"";s:25:"authorize-transaction-key";s:0:"";s:18:"authorize-md5-hash";s:0:"";}'));
			}
		}	
	}

?>