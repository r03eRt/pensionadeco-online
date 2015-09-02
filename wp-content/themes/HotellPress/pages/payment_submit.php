<?php get_header();
?> 
	 <div class="middle-inner-wrapper" style="background:#e7dfd6 url(<?php echo TEMPLATE_URL.get_option('tgt_default_inner_background');?>) no-repeat center top;">       
		  <div class="localization">       			
			  <p class="site-loc"><a href="<?php echo HOME_URL;?>" style="color:white"><?php echo get_option('tgt_hotel_name');?></a></p><p>&raquo;&nbsp;<?php _e ('Page not found', 'hotel'); ?></p>
		  </div>
		  
		  <div style="clear:both;"></div>
	   
			   <div class="middle-inner">
				   <div class="center-inner">
						 <div class="title">
						 <?php
						 if(isset($_POST['payment_type']) && !empty($_POST['payment_type']))
						 {
						      $service = $_POST['service_name'];   
						      $uid = $_POST['u_id'];
						      if(!empty($service)){
							       $arr_service = array();
							       foreach($service as $k => $v)
							       {
									$arr_service[$k] = $v;
							       }
							       update_user_meta($uid, 'tgt_service', $arr_service, true);
						      }
						      $paid = get_user_meta($uid,'tgt_total_price',true);
						      $promotion = $_POST['promotion'];
						      update_user_meta($uid,META_USER_PROMOTION,$promotion);
						      if($_POST['payment_type'] == 'paypal')
							  {
						 ?>
							  <h5><?php _e ('The system is redirecting to Paypal automatically. Please waitting.', 'hotel');?></h5>
							  <div style="clear:both;"></div>
							  <form name="paymentform" method="post" id="paymentform" action="<?php echo $_POST['payment_page']; ?>">
								   <input type="hidden" name="return" id="paypal_return" value="<?php echo get_user_meta($uid,'tgt_paypal_return',true); ?>" />
								   <input type="hidden" name="cancel_return" id="paypal_cancel_return"  value="<?php echo get_user_meta($uid,'tgt_paypal_cancel_return',true); ?>" />  
								   <input type="hidden" name="cmd" value="_ext-enter"/>
								   <input type="hidden" name="cmd" value="_ext-enter"/>
								   <input type="hidden" name="redirect_cmd" value="_xclick"/>
								   <input type="hidden" name="business" value="<?php echo get_option('tgt_paypal_email'); ?>"/>
								   <input type="hidden" name="rm" value="2"/>            
								   <input type="hidden" name="currency_code" value="<?php echo get_option('tgt_currency'); ?>"/>           
								   <input type="hidden" name="quantity" value="1"/>            
								   <input type="hidden" name="item_name" value="<?php bloginfo('name'); ?> Booking Submission"/>
								   <input type="hidden" name="amount" id="amount" value="<?php echo $paid;?>"/>
								   <input type="hidden" name="cbt" value="<?php _e( 'Click here to confirm your transaction &rarr;', 'hotel' );?>"/>
							  </form>
						 <?php
							  }elseif($_POST['payment_type'] == '2checkout')
							  {
						 ?>
							  <h5><?php _e ('The system is redirecting to 2Checkout automatically. Please waitting.', 'hotel');?></h5>
							  <div style="clear:both;"></div>
						 <?php
								   $product_id = get_option('tgt_2checkout_product_id',true);
								   $product_price = get_option('tgt_2checkout_product_price',true);
								   $product_name = get_option('tgt_2checkout_product_name',true);
								   $product_description = get_option('tgt_2checkout_product_description',true);
								   
								   $extfields = get_user_meta($uid,'tgt_user_information',true);
								   
								   $products = array(
									   array( 'id' => $product_id, 'quantity' => '1', 'price' => $product_price, 'name' => $product_name,  'description' => $product_description )									 
								   );
								   $op = array(
										'seller'				=>		get_option('tgt_seller_id',true),	
										'secret_key'			=>		get_option('tgt_2checkout_secret_key',true),	  
										'successful_url' 		=> 		HOME_URL.'/?action=payment_success&payment_method=2checkout',	
										'test_mod'				=>		0,	// 1 or 0
										'test_email'			=>		'',//trieubv@yw.vn
										'submit'				=>		'',
										'total'					=>		$paid,
										'order_id'				=>		$uid,
										'form'					=>		'paymentform'
								   );
								   $pay = DC2Checkout::getInstance();
								   $pay->setData( $products, $op, $extfields);
								   $pay->generalHTML();
							  }
						 }
						 ?>
						 </div>
				   </div>      
			   <div style = "clear:both;"></div>
			  </div>
			  <?php get_sidebar();?>
			  <div class="bottom">
					<!--<img src="<?php echo TEMPLATE_URL;?>/images/inner-page-bottom.jpg" alt="inner_page_bottom_image"/>-->
			  </div>
		  </div>
<?php get_footer();?>
<script type="text/javascript">
document.paymentform.submit();
</script>