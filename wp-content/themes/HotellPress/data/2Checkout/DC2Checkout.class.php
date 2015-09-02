<?php
class DC2Checkout extends DCPayment{
	private static $_instance;
	private function __construct(){ }
	private function __clone() {}
	
	public static function getInstance(){
		if ( ! self::$_instance instanceof self)
			self::$_instance = new self();
		return self::$_instance;
	}
	/**
	 * (non-PHPdoc)
	 * @see DCPayment::setData()
	 * array $products( //contain data for products. Include: id, name, description, price, quantity, 
	 	* array( 'id' => $id, 'quantity' => $quantity, 'name' => $name, 'price' => $price, 'description' => $description ),  //product 1	
		* array( 'id' => $id, 'quantity' => $quantity, 'name' => $name, 'price' => $price, 'description' => $description )  //product 2
	 * )
	 * 
	 * array $options(
	 	* 'seller'				=>		$sid,				//required, Your 2Checkout vendor account number.
	 	* 'total'  				=>		$total,				//required, The total amount to be billed, in decimal form, without a currency symbol or comma. (8 characters, decimal, 2 characters: Example: 99999999.99)
	 	* 'secret_key'			=>		$secret_key,		//required, The secret word is set by yourself on the [Account]->[Site Management] page, default is 'tango'	\
	 	* 'order_id'			=>		$cart_order_id,		//required, A unique order ID from your program. (128 characters max) 
	 	* 'successful_url' 		=> 		$rsuccessful_url,	//option, url is redirected after payment succesful
	 	* 'test_mod'			=>		$test_mod,			//option, 1 or 0
	 	* 'test_email'			=>		$test_email,		//option, email is received $_REQUEST when Payment gateway response, default is admin_email
	 	* 'submit				=>		'',					//option, html for submit button
	 	* 'form'				=>		'twoCheckout'
	 * )
	 * 
	 * array $extFields(  //extend fields allow add hidden fields custom payment process
	 	* 'id_type'				=>		$id_type, 		//should be passed in once during the purchase and will need to have its value set to 1	
	 	* 'fixed'				=>		$fixed,			//Y to remove the Continue Shopping button and lock the quantity fields. 
	 	* 'lang'				=>		$lang,			//
	 	* 'return_url'			=>		$return_url,	//Used to control where the Continue Shopping button will send the customer when clicked, 255 characters max
	 	* 'merchant_order_id'	=>		$merchant_order_id, //Specify your order number with this parameter. It will also be included in the confirmation emails to yourself and the customer. (50 characters max)
	 	* 'pay_method'			=>		$pay_method,	//CC for Credit Card, AL for Acculynk PIN-debit, PPI for PayPal. This will set the default selection on the payment method step during the checkout process
	 	* 'skip_landing'		=>		$skip_landing,	//If set to 1 it will skip the order review page of the purchase routine. 
	 	* 'card_holder_name'	=>		$card_holder_name,//Card holder’s name. (128 characters max) The card holder’s name can also be populated using the first_name, middle_initial, and last_name parameters.
	 	* 'street_address'		=>		$street_address,//Card holder’s street address. (64 characters max) 
	 	* 'street_address2'		=>		$street_address2,//The second line for the street address, typically suburb or apartment number information. (64 characters max)
	 	* 'city'				=>		$city,			//Card holder’s city. (64 characters max) 
	 	* 'state'				=>		$state,			//Card holder’s state. (64 characters max) 
	 	* 'zip'					=>		$zip,			//Card holder’s zip or postal code. (16 characters max)
	 	* 'country'				=>		$country,		//Card holder’s country. (64 characters max) 
	 	* 'email'				=>		$email,			//Card holder’s email address. (64 characters max) 
	 	* 'phone'				=>		$phone, 		//Card holder’s phone number. (16 characters max)
	 	* 'phone_extension'		=>		$phone_extension,//Card holder’s phone extension. (9 characters max)
	 	* 
	 	* 'ship_name'			=>		$ship_name,		//Recipient's name. (128 characters max) 
	 	* 'ship_street_address'	=>		$ship_street_address, //Recipient's street address. (64 characters max) 
	 	* 'ship_street_address2'=>		$ship_street_address2,//The second line for the street address, typically suburb or apartment number information. (64 characters max)
	 	* 'ship_city'			=>		$ship_city,		//Recipient's city. (64 characters max) 
	 	* 'ship_state'			=>		$ship_state, 	//Recipient's state. (64 characters max) 
	 	* 'ship_zip'			=>		$ship_zip,		//Recipient's zip or postal code. (16 characters max)
	 	* 'ship_country'		=>		$ship_country,	//Recipient's country. (64 characters max)  
	 * )
	 */
	public function setData( $products, $options, $extFields = array() ){
		
		if ( !is_array($products) ) {
			parent::addError('no_product', 'no items in products array');
		}
		
		//products
		$i = 1;
		foreach ($products as $val){
			if (!empty( $val['id'] ))
				if( !empty( $val['quantity'] ) ) parent::addField('c_prod_'.$i, trim( $val['id'] ) . ',' . intval( $val['quantity'] ) );
				else parent::addField('c_prod_'.$i, trim( $val['id'] ));
			else parent::addError( 'no_product_id_' . $i, 'No product ID > ' . $i );		
				
			if (!empty( $val['name'] ))parent::addField( 'c_name_'.$i, trim( $val['name'] ) );
			
			if (!empty( $val['price'] ))parent::addField( 'c_price_'.$i, number_format( $val['price'], 2, '.', '' ) );
			else parent::addError( 'no_price_' . $i, 'No price ID > ' . $i );
				
			if (!empty( $val['description'] ))parent::addField('c_description_'.$i, trim( $val['description'] ));
			
			$i++;
		}
		
		//options
		$defaultOps = array(
			'seller'				=>		'',			//Your 2Checkout vendor account number.  
		 	'successful_url' 		=> 		get_bloginfo( 'wpurl' ),	//url is redirected after payment succesful
			'total'					=>		0,
			'order_id'				=>		'',
		 	'test_mod'				=>		0,			// 1 or 0
		 	'test_email'			=>		get_option( 'admin_email' ),	//email is received $_REQUEST when Payment gateway response, default is admin_email
		 	'secret_key'			=>		'tango',
			'submit'				=>		'',			//html of submit button
			'form'				=>		'twoCheckout'
		);
		
		$options = wp_parse_args( $options, $defaultOps );
		
		$seller =  intval( $options['seller'] );
		$successful_url = trim( $options['successful_url'] );
		$total = number_format( $options['total'], 2, '.', '');
		$order_id = trim( $options['order_id'] );
		$test_mod = empty( $options['test_mod'] ) === false;
		$test_email = trim( $options['test_email'] );
		$secret_key = trim( $options['secret_key'] );
		$submit = trim( $options['submit'] );
		$form = trim( $options['form'] );
		
		
		if ( $seller == 0) parent::addError('no_vendor_id', 'no vendor id');
		if ( $total <= 0 ) parent::addError('total_must_larger_0', 'total must larger 0');
		if ( empty( $order_id ) ) parent::addError('order_id_is_required', 'Order id is required');
		if ( empty( $secret_key ) ) parent::addError('serect_key_is_required', 'Serect key is required');
		
		$options = wp_parse_args( $options, $defaultOps );
		parent::addOption( 'action', 'https://www.2checkout.com/checkout/purchase' );
		parent::addOption( 'submit', $submit );		
		parent::addField( 'sid', $seller );
		parent::addField( 'x_receipt_link_url', $successful_url );
		parent::addField( 'total', $total );
		parent::addOption('test_mod', $test_mod );
		if ( $test_mod ) parent::addField( 'demo', 'Y' );		
		parent::addOption( 'test_email', $test_email );		
		parent::addOption('secret_key', $secret_key );
		parent::addOption( 'submit', $submit );
		parent::addField( 'cart_order_id', $order_id );
		parent::addOption( 'form', $form );
		
		//extension fields
		$extDefault = array(
			'id_type'		=>		1,
//			'return_url'	=>		'',
			'fixed'			=>		'Y',
		);
		$extFields = wp_parse_args( $extFields,  $extDefault);
		
		$fields = parent::getFields();
		$fields = wp_parse_args( $extFields, $fields );
		parent::setFields( $fields );
	}
	/**
	 * 
	 * Check IPN Notification
	 * @param array $extChecks: contain key/value to check customs fields is response from Gateway
	 * @return true if successful. If not, return array
	 */
	public function validateIPN( $extChecks = array() ){
		$errors = array();
		if ( empty( $_POST['sid'] ) || empty( $_POST['key'] ) || empty( $_POST['order_number'] ) )
			$errors['request_not_from_2checkout'] =  'Request not from 2Checkout';
	
		$vendorNumber   =	$_POST['sid'];
        $orderNumber    = 	$_POST['order_number'];
        $orderTotal     =  	$_POST['total'] ;
        // If demo mode, the order number must be forced to 1
        $test = parent::getOption( 'test_mod' );
        if( !empty( $test ) )
	      $orderNumber = "1";
	      
        // Calculate md5 hash as 2co formula: md5(secret_word + vendor_number + order_number + total)
        $key = strtoupper(md5( parent::getOption( 'secret_key' ) . $vendorNumber . $orderNumber . $orderTotal));
        // verify if the key is accurate
        if( $_POST['key'] != $key ) 
        	$errors['md5_not_match'] =  'Key not match';
       
        if ( $_POST['demo'] == 'Y'  && empty( $test ) )
        	$errors['request_is_test_mod'] = 'Request is test mod';
        
        if ( ! empty( $test ) ){
        	$email = parent::getOption( 'test_email' );
	        $sub = 'Not from 2Checkout request';
	        $err_count = count( $errors );
	        if ( $err_count == 0 ) $sub = 'Payment Successful';
	        $mes = print_r( $_POST, true );
	        $mes =  "POST:\n" . $mes . "\nERRORS:\n";
	        $mes .= print_r( $errors, true );
	        wp_mail( $email, $sub, $mes );
        }
        
        if ( count( $errors ) > 0 ){   				
        	return $errors;
        }
        return true;
	}
}