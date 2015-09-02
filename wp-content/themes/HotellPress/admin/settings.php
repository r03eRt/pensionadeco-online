<?php
$message = '';
if(!empty($_COOKIE['message']))
	$message = $_COOKIE['message']; //message from add_room.php
setcookie("message", $message, time()-3600);
if(isset($_POST['submitted']) && !empty($_POST['submitted']))
{
	//var_dump( $_POST['tgt_languages_name'] );die();
	$pay_permit = $_POST['permit_payment'];
	$paypal_email = $_POST['paypal_email'];
	$deposit = $_POST['deposit'];
	if($_POST['deposit'] == '')
		$deposit = 0;
	$success_mess = $_POST['successmessage'];
	if (function_exists('icl_register_string')) {
   		icl_register_string('Success Message', md5($success_mess), $success_mess);
     	}
	
	$success_mess_cash = $_POST['successmessage_cash'];
	if (function_exists('icl_register_string')) {
   		icl_register_string('Cash Success Message', md5($success_mess_cash), $success_mess_cash);
     	}
	
	$lang = $_POST['def_lang'];
	$hotel_name = $_POST['hotel_name'];
	$hotel_email = $_POST['hotel_email'];
	$hotel_country = $_POST['country'];
	$hotel_state = $_POST['state'];
	$hotel_address = $_POST['address'];
	$hotel_phone = array('p_1' => $_POST['phone1'],
						 'p_2' => $_POST['phone2'],
						 'p_3' => $_POST['phone3']);
	$currency = $_POST['currency'];
	
	$successmail_payment = $_POST['successmail'];
	$mailsubject = $_POST['mailsubject'];			
	$mailcontent = $_POST['mailcontent'];
	$mailfrom = $_POST['mailfrom'];
	$payment_method = array('paypal' => '0', 'direct' => '0', '2checkout' => '0');
        if(isset ($_POST['paypal_method'])) $payment_method['paypal'] = $_POST['paypal_method'];
        if(isset ($_POST['ditrect_method'])) $payment_method['direct'] = $_POST['ditrect_method'];
		if(isset ($_POST['2checkout_method'])) $payment_method['2checkout'] = $_POST['2checkout_method'];   
        update_option('tgt_payment_method', $payment_method);
        update_option('tgt_permit_payment',$pay_permit);
	update_option('tgt_permit_reservations',$_POST['permit_reservations']);
	$custom_script = $_POST['custom_script'];
	$custom_css = $_POST['custom_css'];
	$max_adults = $_POST['max_adults'];
	$max_rooms = $_POST['max_rooms'];
	$tax = array ( 'amount' => $_POST['amount_tax'],
				   'type'   => $_POST['type_tax']
				);
	$promo = $_POST['promotion'];
	
	update_option('tgt_permit_payment',$pay_permit);

	update_option('tgt_currency',$currency);
	update_option('tgt_paypal_email',$paypal_email);
	update_option('tgt_deposit_percent',$deposit);
	update_option('tgt_booking_success',$success_mess);
	update_option('tgt_default_language',$lang);
	update_option('tgt_hotel_name',$hotel_name);
	update_option('tgt_hotel_email',$hotel_email);
	update_option('tgt_hotel_country',$hotel_country);
	update_option('tgt_hotel_state',$hotel_state);
	update_option('tgt_hotel_street',$hotel_address);
	update_option('tgt_hotel_phone',$hotel_phone);
	update_option('tgt_successmail_payment',$successmail_payment);
	update_option("tgt_mailsubject", $mailsubject);
	update_option("tgt_mailcontent", $mailcontent);
	update_option("tgt_mailfrom", $mailfrom);
	update_option('tgt_custom_script', $custom_script);
	update_option('tgt_custom_css', $custom_css);
	update_option('tgt_cash_booking_success',$success_mess_cash);
	update_option('tgt_max_people_per_booking', $max_adults);
	update_option('tgt_max_rooms_per_booking', $max_rooms);
	update_option('tgt_tax', $tax);
	update_option('tgt_promotion_date', $promo);
	
	/*---- WPML ---*/
	//$using_wpml = $_POST['tgt_using_wpml']
	update_option('tgt_using_wpml', $_POST['tgt_using_wpml']);
	update_option('tgt_can_change_language', $_POST['tgt_can_change_language']);
	if ( isset ( $_POST['tgt_wpml'] ) )
		update_option('tgt_wpml', $_POST['tgt_wpml']);
		
	if ( isset ( $_POST['tgt_languages_name'] ) )
		update_option('tgt_languages_name', $_POST['tgt_languages_name']);
	
	/*
	 * 2Checkout payment processing
	 */
	update_option('tgt_seller_id', $_POST['seller_id']);
	update_option('tgt_2checkout_secret_key', $_POST['2checkout_secret_key']);
	update_option('tgt_2checkout_product_id', $_POST['2checkout_product_id']);
	update_option('tgt_2checkout_product_price', $_POST['2checkout_product_price']);
	update_option('tgt_2checkout_product_name', $_POST['2checkout_product_name']);
	update_option('tgt_2checkout_product_description', $_POST['2checkout_product_description']);
	/*
	 * Alternal currency processing
	 */
	update_option('tgt_allow_alternal_currency',$_POST['allow_alternal_currency']);
	$position_sample = $_POST['alternal_symbol']."500";
	if($_POST['position'] == '2')
	{
		$position_sample = $_POST['alternal_symbol'].' 500';
	}else if($_POST['position'] == '3')
	{
		$position_sample = '500'.$_POST['alternal_symbol'];
	}else if($_POST['position'] == '4')
	{
		$position_sample = '500 '.$_POST['alternal_symbol'];
	}
	$alternal_array = array('currency' => $_POST['alternal_currency'],
							'symbol' => $_POST['alternal_symbol'],
							'position' => $_POST['position'],
							'sample_position' => $position_sample,
							'currency_rating' => $_POST['currency_rating']
							);
	update_option('tgt_alternal_currency',$alternal_array);
	
	$message = __('Your settings have been saved !','hotel');			
	setcookie("message", $message, time()+3600);	
	echo "<script language='javascript'>window.location = '"."admin.php?page=my-submenu-handle-settings"."'</script>";		
}
// get all currency datas
$arr_currency = parse_ini_file(TEMPLATEPATH.'/data/default_curreny.ini');
?>
<script language="javascript">
jQuery(document).ready(function() {		
	var str = '<?php echo get_option('tgt_permit_payment'); ?>';
	if(str == 1)		
		jQuery('#att_permit').show();
	else if(str == 0)
		jQuery('#att_permit').hide();
	jQuery('#permit_payment').change(function() {	
		jQuery("#permit_payment :selected").each(function(i, selected) {
			str = jQuery(selected).val();	
		});			
		if(str == 1)		
			jQuery('#att_permit').show();
		else if(str == 0)
			jQuery('#att_permit').hide();		
	});		
});	</script>
<div class="wrap">
	<?php the_support_panel(); ?>
	<br>
    <?php
	if ($message) echo '<div class="updated below-h2">'.$message.'</div>';
	?>
	
<form method="post" name="location_setting" target="_self">
<input name="submitted" type="hidden" value="<?php _e('yes','hotel');?>" />
	<div class="settings" style="margin: 0px 0px 0px 0px; padding-top: 0px 0px 0px 0px;">
		<div class="heading">
			<h3 style="padding-top:10px;"><?php _e('Global Settings','hotel');?></h3>            
			<div class="cl"></div>
			
		</div>        	
        <div class="item">
			<div class="left">
			<?php _e('Allow Reservations?','hotel');?>
				<span><?php _e(" If you choose yes, the reservations button will be displayed. If you choose no, the reservations button will be disappeared and users cannot search available room for booking.",'hotel');?></span>
			</div>
			<div class="right">
				<select name="permit_reservations" id="permit_reservations">	
					<option value="1"<?php if(get_option('tgt_permit_reservations') == "1") { echo ' selected="selected"'; } ?>><?php _e('Yes','hotel');?></option>
					<option value="0"<?php if(get_option('tgt_permit_reservations') == "0") { echo ' selected="selected"'; } ?>><?php _e('No','hotel');?></option>
				</select>
			</div>
			<div class="clear"></div>
		</div>	
		
		<div class="item">
			<div class="left">
			<?php _e('Maximum Adults','hotel');?>
				<span><?php _e('The number of person will be displayed in reservation box. This is the maximum adults can book in once time( it should be a number).','hotel');?></span>
			</div>
			<div class="right">
				<input type="text" name="max_adults" id="max_adults" value="<?php if (get_option('tgt_max_people_per_booking') == '') { echo '';  } else { echo get_option('tgt_max_people_per_booking'); } ?>" onkeypress="return numberPrice(event);" />
			</div>
			<div class="clear"></div>
		</div>	
		
		<div class="item">
			<div class="left">
			<?php _e('Maximum Rooms','hotel');?>
				<span><?php _e('The number of rooms will be displayed in reservation box. This is the maximum rooms can book in once time ( it should be a number).','hotel');?></span>
			</div>
			<div class="right">
				<input type="text" name="max_rooms" id="max_rooms" value="<?php if (get_option('tgt_max_rooms_per_booking') == '') { echo '';  } else { echo get_option('tgt_max_rooms_per_booking'); } ?>" onkeypress="return numberPrice(event);" />				
			</div>
			<div class="clear"></div>
		</div>	
		
		<div class="item">
			<div class="left">
			<?php _e('Paid Submission?','hotel');?>
				<span><?php _e('If you choose Enable, your users will need to pay if they want to book rooms online. If you choose Disable, the booking online will be closed.','hotel');?></span>
			</div>
			<div class="right">
				<select name="permit_payment" id="permit_payment">	
					<option value="1"<?php if(get_option('tgt_permit_payment') == "1") { echo ' selected="selected"'; } ?>><?php _e('Enable','hotel');?></option>
					<option value="0"<?php if(get_option('tgt_permit_payment') == "0") { echo ' selected="selected"'; } ?>><?php _e('Disable','hotel');?></option>
				</select>
			</div>
			<div class="clear"></div>
		</div>
                <div class="item">
			<div class="left">
				<?php _e('Payment Methods :','hotel');?>
                <span><?php _e('Enter your Paypal mail address. You need to be registered at PayPal to ask for a Submission Price.','hotel');?></span>
			</div>
                 <?php $payment_method = get_option('tgt_payment_method');
                 ?>
                 <div class="right">
                    <input type="checkbox" name="paypal_method" id="paypal_method" value="1" style="width: 20px;" <?php if(isset ($payment_method['paypal']) && $payment_method['paypal'] == '1') echo ' checked';?> /> <?php _e('Paypal','hotel');?>
					<br />
                    <input type="checkbox" name="ditrect_method" id="ditrect_method" value="1" style="width: 20px;" <?php if(isset ($payment_method['direct']) && $payment_method['direct'] == '1') echo ' checked';?> /> <?php _e('Direct payment','hotel');?>
					<br />
                    <input type="checkbox" name="2checkout_method" id="2checkout_method" value="1" style="width: 20px;" <?php if(isset ($payment_method['2checkout']) && $payment_method['2checkout'] == '1') echo ' checked';?> /> <?php _e('2Checkout payment','hotel');?>
				 </div>
			<div class="clear"></div>
		</div>
        <div class="item" >
			<div class="left">
				<?php _e('Currency','hotel');?>
				<span><?php _e('You are able to choose from 18 currencies.','hotel');?></span>
			</div>
			<div class="right">
				<select name="currency" id="currency">							
					<?php					
					if($arr_currency != '')
					{
						foreach ($arr_currency as $k=>$v)
						{
							echo '<option value="'.$k.'"';
							if(get_option('tgt_currency',true) == $k)
								echo 'selected="selected"';
							echo '>'.$v.'</option>';
						}
					}
					?>	
				</select>
			</div>
			<div class="clear"></div>
		</div>
		
        <div class="item">
			<div class="left">
				<?php _e('Deposit Percent :','hotel');?>		
                <span><?php _e('The percent base on total price customer must pay when they book rooms online.','hotel');?></span>		
			</div>
			<div class="right">
				<input type="text" name="deposit" id="deposit" value="<?php echo get_option('tgt_deposit_percent'); ?>" />
			</div>
			<div class="clear"></div>
		</div>
        <div class="item">
			<div class="left">
				<?php _e('Paypal Email :','hotel');?>		
                <span><?php _e('Enter your Paypal mail address. You need to be registered at PayPal to ask for a Submission Price.','hotel');?></span>		
			</div>
			<div class="right">
				<input type="text" name="paypal_email" id="paypal_email" value="<?php echo get_option('tgt_paypal_email'); ?>" />
			</div>
			<div class="clear"></div>
		</div>
		
		<!-- Start 2Checkout Fields -->
		<div class="item">
			<div class="left">
				<?php _e('The Seller ID :','hotel');?>		
                <span><?php _e('Enter your seller ID from the 2checkout site.','hotel');?></span>		
			</div>
			<div class="right">
				<input type="text" name="seller_id" id="seller_id" value="<?php echo get_option('tgt_seller_id'); ?>" />
			</div>
			<div class="clear"></div>
		</div>
		<div class="item">
			<div class="left">
				<?php _e('The 2Checkout secret key :','hotel');?>		
                <span><?php _e('Enter the secret key from the 2checkout site.','hotel');?></span>		
			</div>
			<div class="right">
				<input type="text" name="2checkout_secret_key" id="2checkout_secret_key" value="<?php echo get_option('tgt_2checkout_secret_key'); ?>" />
			</div>
			<div class="clear"></div>
		</div>
		<div class="item">
			<div class="left">
				<?php _e('The 2Checkout Product ID :','hotel');?>		
                <span><?php _e('Enter the product ID from the 2checkout site.','hotel');?></span>		
			</div>
			<div class="right">
				<input type="text" name="2checkout_product_id" id="2checkout_product_id" value="<?php echo get_option('tgt_2checkout_product_id'); ?>" />
			</div>
			<div class="clear"></div>
		</div>
		<div class="item">
			<div class="left">
				<?php _e('The 2Checkout Product Price :','hotel');?>		
                <span><?php _e("Enter the product's price from the 2checkout site.","hotel");?></span>		
			</div>
			<div class="right">
				<input type="text" name="2checkout_product_price" id="2checkout_product_price" value="<?php echo get_option('tgt_2checkout_product_price'); ?>" />
			</div>
			<div class="clear"></div>
		</div>
		<div class="item">
			<div class="left">
				<?php _e('The 2Checkout Product Name :','hotel');?>		
                <span><?php _e("Enter the product's name from the 2checkout site.","hotel");?></span>		
			</div>
			<div class="right">
				<input type="text" name="2checkout_product_name" id="2checkout_product_name" value="<?php echo get_option('tgt_2checkout_product_name'); ?>" />
			</div>
			<div class="clear"></div>
		</div>
		<div class="item">
			<div class="left">
				<?php _e('The 2Checkout Product Description :','hotel');?>		
                <span><?php _e("Enter the product's description from the 2checkout site.","hotel");?></span>		
			</div>
			<div class="right">
				<input type="text" name="2checkout_product_description" id="2checkout_product_description" value="<?php echo get_option('tgt_2checkout_product_description'); ?>" />
			</div>
			<div class="clear"></div>
		</div>
		<!-- End 2Checkout Fields -->
		
        <div class="item">
			<div class="left">
				<?php _e('Booking Success Message :','hotel');?>
				<span><?php _e('This message is displayed when customers booking room online successfully.','hotel');?></span>
			</div>
			<div class="right">
				<textarea name="successmessage" id="successmessage"><?php if(get_option('tgt_booking_success') == NULL) {  echo "Congratulation, your book room online successfully!\nWe recommend you to save the Transaction No.\nYou will need it if you want to change or delete your booking. "; } else { echo get_option('tgt_booking_success'); } ?></textarea>
			</div>
			<div class="clear"></div>
		</div>
		
		<div class="item">
			<div class="left">
				<?php _e('Cash Success Message :','hotel');?>
				<span><?php _e('This message is displayed when customers booking room by cash payment successfully.','hotel');?></span>
			</div>
			<div class="right">
				<textarea name="successmessage_cash" id="successmessage_cash"><?php if(get_option('tgt_cash_booking_success') == NULL) {  echo "Congratulation, your booking had been successfully!\nAnd your booking will be published when you transfer the cash to the bank account with series number is 1111-1111-1111-1111 at {Bank Name} bank."; } else { echo get_option('tgt_cash_booking_success'); } ?></textarea>
			</div>
			<div class="clear"></div>
		</div>
		
      <div class="item">
			<div class="left">
				<?php _e('Languages','hotel');?>
				<span><?php _e('Setting up your site\'s languages','hotel');?></span>
			</div>
			<div class="right">
            <?php
				$languages 							= get_available_languages(TEMPLATEPATH.'/lang');	
				$curr_lang 							= get_option('tgt_default_language');
				$change_language_permission 	= get_option('tgt_can_change_language');
			?>
				<p>
					<input type="hidden" name="tgt_can_change_language" value="0" />
					<input type="checkbox" name="tgt_can_change_language" value="1" <?php echo $change_language_permission ? 'checked="checked"' : '' ?> />
					<label for=""><?php _e('Visitor can switch language in the front-end','hotel') ?></label>
				</p>
            <p>
					<label for=""><?php _e('Default Language:','hotel') ?></label>
					<select name="def_lang" id="def_lang">
						<?php
						for($i=0;$i<count($languages);$i++)
						{
						?>
							<option value="<?php echo $languages[$i]; ?>" <?php if($curr_lang==$languages[$i]) { echo 'selected="selected"'; } ?>>
						<?php 
							echo $languages[$i];				
							echo '</option>';
						}
						?>
					</select>
				</p>			
			</div>
			<div class="clear"></div>
		</div>
		
		<div class="item">
			<?php 
				$using_wpml = get_option('tgt_using_wpml');
				$wpml_langs = array();
			?>
			<div class="left">
				<?php _e('WPML plugin :','hotel');?>
				<span><?php printf( __('HotelPress recommend you using <b>WPML</b> plugin to display your website in multi-languages. <br />Once you install <b>WPML</b>, you can using WPML to display website in multi-lang with default languages feature of HotelPress. <br />Read more about WPML at %s ','hotel') , '<a href="wpml.org">here</a>' ) ;?></span>
			</div>
			<div class="right">
				<table id="wpml_list">
					<tr>
						<td colspan="2">
							<input type="hidden" name="tgt_using_wpml" value="0" />
							<input type="checkbox" name="tgt_using_wpml" value="1" <?php echo $using_wpml ? 'checked="checked"' : '' ?> />
							<?php _e( ' using WPML (if you don\'t use WPML plugin, HotelPress will display default multilanguage by default) ' , 'hotel' ) ?>	
						</td>
					</tr>
					<?php if ( function_exists( 'icl_get_languages' ) && $using_wpml ) {
							$wpml_langs 		= icl_get_languages();
							$option_wpml 		= get_option('tgt_wpml');
							
							echo '<tr><td colspan="2">' . __('Link <b>WPML</b> languages to your languages files:') . '</td></tr>';
							foreach( $wpml_langs as $code => $lang ) {
							?>							
							<tr>
								<td valign="middle" width="100"> <?php echo $lang['translated_name'] ?>  </td>
								<td>
									<?php 
										//echo $lang . ' - ' . $option_wpml[$code]; ?>
									<select name="tgt_wpml[<?php echo $code ?>]">										
										<?php
										foreach( $languages as $item )
										{
											if ( $item == $option_wpml[$code] )
												echo '<option value="' . $item . '" selected="selected">' . $item . '</option>';
											else
												echo '<option value="' . $item . '" >' . $item . '</option>';
										}
										?>
									</select>
								</td>
							</tr>
					<?php } } // end if
					else {
						$languages_name 	= get_option('tgt_languages_name');
						echo '<tr><td colspan="2">' . __('Choose display name for your language file:') . '</td></tr>';							
						//var_dump( get_option('tgt_languages_name') );
						// read flags
						$path = TEMPLATEPATH . '/images/flags';
						$dirhandle = opendir($path);
						$flags = array();
						while (false !== ($file = readdir($dirhandle))) {
							if ( $file != '.' && $file != '..' && !is_dir( $path . '/' .$file ) )
								$flags[] = $file;
						}
						
						foreach ( $languages as $item ) {							
							//$name = $item;
							$name = empty ( $languages_name[$item]['name'] ) ? $item : $languages_name[$item]['name'];
							$itemflag = empty ( $languages_name[$item]['flag'] ) ? 'en.png' : $languages_name[$item]['flag'];
						?>
							<tr>
								<td valign="middle" width="100"> <?php echo $item ?> </td>
								<td>
									<input type="text" name="tgt_languages_name[<?php echo $item ?>][name]" value="<?php echo $name?>"/>
									&nbsp;&nbsp;
									<select class="select_flag" name="tgt_languages_name[<?php echo $item ?>][flag]" style="background: url(<?php echo TEMPLATE_URL . '/images/flags/' . $itemflag ?>) no-repeat 5px center #ffffff; padding-left: 27px; margin-left: 2px;" >
										<?php foreach ($flags as $flag) {
											$flaglink = TEMPLATE_URL . '/images/flags/' . $flag;
											$flagname = substr( $flag , 0, 2 );
											?>
											<option value="<?php echo $flag ?>" <?php echo $itemflag == $flag ? 'selected="selected"' : '' ?> style="background: url(<?php echo $flaglink ?>) no-repeat  5px center ; padding-left: 27px;">
												<?php echo $flagname ?>
											</option>											
										<?php } ?>
									</select>
									<script type="text/javascript">
										jQuery(document).ready(function($){
											$('select.select_flag').change(function(){
												var current = $(this),
													select_item = current.find('option:selected');
												current.css('background-image', select_item.css('background-image') );
											});
										});
									</script>
								</td>
							</tr>
							
					<?php } // end foreach
					} // end else ?>
				</table>
			</div>
			<div class="clear"></div>
		</div>
		
		<div class="item">
			<div class="left">
				<?php _e('Hotel Name :','hotel');?>				
			</div>
			<div class="right">
				<input type="text" name="hotel_name" id="hotel_name" value="<?php echo get_option('tgt_hotel_name'); ?>" />
			</div>
			<div class="clear"></div>
		</div>  
        <div class="item">
			<div class="left">
				<?php _e('Contact Hotel Email :','hotel');?>
			</div>
			<div class="right">
				<input type="text" name="hotel_email" id="hotel_email" value="<?php echo get_option('tgt_hotel_email'); ?>" />
			</div>
			<div class="clear"></div>
		</div>         
        <div class="item">
			<div class="left">
				<?php _e('Hotel Country :','hotel');?>				
			</div>
			<div class="right">   
            	<?php 
				$country = tgt_get_countries();
				?>
				<select name="country" id="country" >
				<?php
				foreach ($country['countries'] as $k=>$v)
				{
				?>
				<option value="<?php echo $v; ?>" <?php if(get_option('tgt_hotel_country') == $v) echo 'selected="selected"' ?>><?php echo $v; ?></option>
				<?php
				}
				?>
				</select>  
			</div>
			<div class="clear"></div>
		</div>
        <div class="item">
			<div class="left">
				<?php _e('Hotel State :','hotel');?>				
			</div>
			<div class="right">
            	<input type="text" name="state" id="state" value="<?php if(get_option('tgt_hotel_state') != '') echo  get_option('tgt_hotel_state'); else if(isset($_POST['state']))  echo $_POST['state']; ?>" style="width:300px;"/>
			</div>			
			<div class="clear"></div>
		</div>  
        <div class="item">
			<div class="left">
				<?php _e('Hotel Street :','hotel');?>				
			</div>
			<div class="right">
				<input type="text" name="address" id="address" value="<?php echo get_option('tgt_hotel_street'); ?>" />
			</div>
			<div class="clear"></div>
		</div>      
        <?php 
		$contact_phone = get_option('tgt_hotel_phone');		
		?>
        <div class="item">
			<div class="left">
				<?php _e('Contact Phone 1 :','hotel');?>				
			</div>
			<div class="right">
				<input type="text" name="phone1" id="phone1" value="<?php echo $contact_phone['p_1']; ?>" />
			</div>
			<div class="clear"></div>
		</div>
        <div class="item">
			<div class="left">
				<?php _e('Contact Phone 2 :','hotel');?>				
			</div>
			<div class="right">
				<input type="text" name="phone2" id="phone2" value="<?php echo $contact_phone['p_2']; ?>" />
			</div>
			<div class="clear"></div>
		</div>
        <div class="item">
			<div class="left">
				<?php _e('Contact Phone 3 :','hotel');?>				
			</div>
			<div class="right">
				<input type="text" name="phone3" id="phone3" value="<?php echo $contact_phone['p_3']; ?>" />
			</div>
			<div class="clear"></div>
		</div>
        <div class="item">
			<div class="left">
				<?php _e('Success Mail','hotel');?>
				<span><?php _e('This email is sent when a customer paid booking successful.','hotel');?></span>
			</div>
			<div class="right">
                <label for="successmail"><?php _e('Enable / Disable it:','hotel');?></label>
                    <select name="successmail" id="successmail">		
                        <option value="1"<?php if(get_option('tgt_successmail_payment') == "1") { echo ' selected="selected"'; } ?>><?php _e('Enable','hotel');?></option>
                        <option value="0"<?php if(get_option('tgt_successmail_payment') == "0") { echo ' selected="selected"'; } ?>><?php _e('Disable','hotel');?></option>
                    </select>
                    <small><?php _e('mail() function need to be activated. Contact your host before activating this feature.','jobpress');?></small>
            </div>
            <div class="clear"></div>
        </div>
        <div class="item" style="display:<?php if(get_option('tgt_successmail_payment')=='0') echo 'none'; ?>">
			<div class="left">
				<?php _e('Success Mail','hotel');?>
				<span><?php _e('This email is sent when a customer paid booking successful.','hotel');?></span>
			</div>
			<div class="right">				
				<label for="mailsubject"><?php _e('Subject','hotel');?></label><br />
				<input type="text" name="mailsubject" id="mailsubject" value="<?php if ( get_option('tgt_mailsubject') == '' ) { echo 'Notice Posted!'; } else { echo get_option('tgt_mailsubject'); } ?>">
				<p></p>
				<label for="mailcontent"><?php _e('Content','hotel');?></label><br />
				<textarea name="mailcontent" id="mailcontent"><?php if (get_option('tgt_mailcontent') == '') { echo "Hello [buyer_name]! \nThank you for paid your booking room on [website_name]. You can find your booking in here: [booking_link].";  } else { echo get_option('tgt_mailcontent'); } ?></textarea>
				<br />
				<p></p>
				<label for="mailfrom"><?php _e('From','hotel');?></label><br />
				<input name="mailfrom" id="mailfrom" type="text" value="<?php if (get_option('tgt_mailfrom') == '') { echo "[website_name]"; } else { echo get_option('tgt_mailfrom'); } ?>"/>				
			</div>
			<div class="clear"></div>
		</div>
		
		<!-- Custom CSS -->
		<div class="item" >
			<div class="left">
				<?php _e('Custom CSS','hotel');?>
				<span><?php _e('You can change display with your CSS, you can edit it at there. It will be effective your site without change our source code.','hotel');?></span>
			</div>
			<div class="right">		
				<label for="custom_css"><?php _e('Your CSS','hotel');?></label><br />
				<textarea style="height:200px;" name="custom_css" id="custom_css"><?php if (get_option('tgt_custom_css') == '') { echo '';  } else { echo get_option('tgt_custom_css'); } ?></textarea>
				<br />
			</div>
			<div class="clear"></div>
		</div>
		<!-- END CUSTOM CSS -->
		
		<!-- CUSTOM SCRIPT -->
		<div class="item">
			<div class="left">
				<?php _e('Custom Script','hotel');?>
				<span><?php printf(__("The scripts you add here will be served after the HTML on every page of your site. You can insert into analytical tools for your website such as %s or %d , ...etc.",'hotel'), '<a href="http://www.google.com/analytics/ target="_blank" >Google Analytics</a>', '<a href="http://piwik.org/" target="_blank">Piwik</a>');?></span>
			</div>
			<div class="right" >	
				<label for="custom_script"><?php _e('Scripts','hotel');?></label><br />
				<textarea style="height:200px;" name="custom_script" id="custom_script"> <?php if (get_option('tgt_custom_script') == '') { echo ""; } else { echo stripslashes( get_option('tgt_custom_script') ); } ?></textarea>				
			</div>
			<div class="clear"></div>
		</div>
	</div> <!-- // postbox -->
	
	<!-- CURRENCY--->
	<br/>
    <div class="settings" style="margin: 0px 0px 0px 0px; padding-top: 0px 0px 0px 0px;">
		<?php
		$alternal_currency = get_option('tgt_alternal_currency',true);
		?>
        <div class="heading">
            <h3 style="padding-top:10px;"><?php _e('Diplay Currency','hotel');?></h3>
            <div class="cl"></div>
        </div>
        <div class="item">
			<div class="left">
				<?php _e('Allow alternative currency','hotel');?> :
				<span><?php _e('Display alternative currency beside default payment currency','hotel');?></span>
			</div>
			<div class="right">
            	<select name="allow_alternal_currency">					
					<option value="1" <?php if(get_option('tgt_allow_alternal_currency',true) == '1') { echo 'selected="selected"';} ?>><?php _e('Yes','hotel'); ?></option>
					<option value="0" <?php if(get_option('tgt_allow_alternal_currency',true) == '0') { echo 'selected="selected"';} ?>><?php _e('No','hotel'); ?></option>
				</select>
			</div>
			<div class="clear"></div>
		</div>
      <div class="item">        
			<div class="left">
				<?php _e('Currency','hotel');?> :
				<span><?php _e('Alternative currency','hotel');?></span>                
			</div>
			<div class="right">
				<select name="alternal_currency">					
					<?php					
					if($arr_currency != '')
					{
						foreach ($arr_currency as $k=>$v)
						{
							echo '<option value="'.$k.'"';
							if($alternal_currency['currency'] == $k)
								echo 'selected="selected"';
							echo '>'.$v.'</option>';
						}
					}
					?>								
				</select>
			</div>
			<div class="clear"></div>       	
		</div>
		<div class="item">        
			<div class="left">
				<?php _e('Currency Rate','hotel');?> :
				<span><?php _e('Currency rate is exchange rate between your currency and default currency. It is the rate at which one default currency will be exchanged for your currency','hotel');?></span>                
			</div>
			<div  class="right">
				<input type="text" name="currency_rating" id="currency_rating" value="<?php echo ($alternal_currency['currency_rating'] != '')?$alternal_currency['currency_rating']:''; ?>" style="width: 90px;"/>
			</div>
			
			<div class="clear"></div>       	
		</div> 
      <div class="item">        
			<div class="left">
				<?php _e('Symbol','hotel');?> :
				<span><?php _e('Symbol of alternative currency','hotel');?></span>                
			</div>
			<div  class="right">
				<input type="text" name="alternal_symbol" id="alternal_symbol" value="<?php echo ($alternal_currency['symbol'] != '')?$alternal_currency['symbol']:''; ?>" style="width: 60px;" maxlength="1"/>
			</div>
			
			<div class="clear"></div>       	
		</div> 
      <div class="item">        
			<div class="left">
				<?php _e('Symbol position','hotel');?> :
				<span><?php _e('Display position of currency','hotel');?></span>                
			</div>
			<div class="right">
				<select name="position" id="se_position">
					<option value="1" <?php echo ($alternal_currency['position'] == '1')?'selected="selected"':''; ?>><?php _e('Before amount','hotel'); ?></option>
					<option value="2" <?php echo ($alternal_currency['position'] == '2')?'selected="selected"':''; ?>><?php _e('Before amount and have a space','hotel'); ?></option>
					<option value="3" <?php echo ($alternal_currency['position'] == '3')?'selected="selected"':''; ?>><?php _e('After amount','hotel'); ?></option>
					<option value="4" <?php echo ($alternal_currency['position'] == '4')?'selected="selected"':''; ?>><?php _e('After amount and have a space','hotel'); ?></option>
				</select>
				<p class="small-desc"><i><?php _e('Sample','hotel'); ?>:</i>
					<span id="position_sample">
						<?php echo (!empty($alternal_currency['sample_position']))?$alternal_currency['sample_position']:'$500'; ?>
					</span>
				</p>
			</div>			
			<div class="clear"></div>       	
		</div> 
	</div><!-- // postbox -->
	<br/>
	<!-- Tax setting -->
    <div class="settings" style="margin: 0px 0px 0px 0px; padding-top: 0px 0px 0px 0px;">
        <div class="heading">
            <h3 style="padding-top:10px;"><?php _e('Tax/Services & Promotion','hotel');?></h3>
            <div class="cl"></div>
        </div>
        <?php 
        	$taxs = get_option('tgt_tax');
        ?>
        <div class="item">
			<div class="left">
				<?php _e('Amount','hotel');?> :
				<span><?php _e('This is surcharge beside default payment for reservation','hotel');?></span>
			</div>
			<div class="right">
            	<input type="text" name="amount_tax" id="amount_tax" value="<?php if ( isset( $taxs) && !empty($taxs) ) echo $taxs['amount'];?>" style="width:150px;" onkeypress="return numberPrice(event);"/>
           </div>
			<div class="clear"></div>
		</div>
      <div class="item">        
			<div class="left">
				<?php _e('Type','hotel');?> :
				<span><?php _e('There are two types of surcharge: Exact Amount( directly surcharge ) or Percent( depend on the total payment ). If you choose \'Percent\', please don\'t input \'Amount\' over than 100','hotel');?></span>                
			</div>
			<div class="right">
				<select name="type_tax">					
					<option value="exact_amount" <?php if ( isset( $taxs) && !empty($taxs) && $taxs['type'] == 'exact_amount') { echo 'selected="selected"';} ?>><?php _e('Exact Amount','hotel'); ?></option>
					<option value="percent" <?php if ( isset( $taxs) && !empty($taxs) && $taxs['type'] == 'percent' ) { echo 'selected="selected"';} ?>><?php _e('Percent (%)','hotel'); ?></option>
				</select>
			</div>
			<div class="clear"></div>       	
		</div>
		<?php $promotion = get_option('tgt_promotion_date');?>
		 <div class="item">        
			<div class="left">
				<?php _e('Promotion date','hotel');?> :
				<span><?php _e(' Promotion date is the date your hotel starts to be applied promotion. You can choose at booking or check in date ','hotel');?></span>                
			</div>
			<div class="right" >
				<select name="promotion" style="width:120px;">					
					<option value="0" <?php if ( isset( $promotion) && !empty($promotion) && $promotion[0] == '0') { echo 'selected="selected"';} ?>><?php _e('At Booking Date','hotel'); ?></option>
					<option value="1" <?php if ( isset( $promotion) && !empty($promotion) && $promotion[0] == '1' ) { echo 'selected="selected"';} ?>><?php _e('At Checkin Date','hotel'); ?></option>
				</select>
			</div>
			<div class="clear"></div>       	
		</div>
	</div><!-- // postbox -->
	<br/>
        <input type="submit" class="button" name="Submit" style="cursor:pointer;" value="<?php _e('Save Change &raquo;','hotel');?>" />
</form>
</div>
<script type="text/javascript">
jQuery(document).ready(function(){
	var check_position = '';
	var symbol = '$500';
	jQuery('#se_position').change(function(){
		check_position = jQuery('#se_position').val();
		if(jQuery('#alternal_symbol').val() != '')
		{
			symbol = jQuery('#alternal_symbol').val();			
		}
		if(check_position == '1')
		{
			jQuery('#position_sample').html(symbol + '500');
		}else if(check_position == '2')
		{
			jQuery('#position_sample').html(symbol + ' 500');
		}else if(check_position == '3')
		{
			jQuery('#position_sample').html('500' + symbol);
		}else if(check_position == '4')
		{
			jQuery('#position_sample').html('500 ' + symbol);
		}
	});
});

function numberPrice(event)
{
    if( event.which!=8 && event.which!=0 && event.which!=46 && (event.which<48 || event.which>57) && (event.which > 96 || event.which < 105) )
    {
        return false;
    }
    if (event.keyCode !=8 && event.keyCode!=0 && event.keyCode!=46 && (event.keyCode<48 || event.keyCode>57) && (event.keyCode > 96 || event.keyCode < 105))
    {
        return false;
    }
    return true;
}
</script>