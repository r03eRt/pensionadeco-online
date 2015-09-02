<?php 
get_header();
global $sitepress;
?>  
<?php echo tgt_get_inner_background(); ?>
        <div class="localization">
            <p class="site-loc"><a href="<?php echo HOME_URL;?>" style="color:white"><?php echo get_option('tgt_hotel_name');?></a></p><p>&raquo;&nbsp;<?php _e('Payment Result','hotel'); ?></p>
        </div>
         <div style="clear:both;"></div>         
            <div class="middle-inner">
       			<div class="center-inner">
                <div class="title">     
				<?php
				$currency = get_option('tgt_currency');
				$pay = DC2Checkout::getInstance();
				$validateIPN = $pay->validateIPN(); 
				if ( $currency == "USD" || $currency == "AUD" || $currency == "CAD" || $currency == "NZD" || $currency == "HKD" || $currency == "SGD" ) { $currencysymbol = "$"; }
				else if ( $currency == "GBP" ) { $currencysymbol = "&pound;"; }
				else if ( $currency == "JPY" ) { $currencysymbol = "&yen;"; }
				else if ( $currency == "EUR" ) { $currencysymbol = "&euro;"; }
				else { $currencysymbol = $currency; }
				global $wpdb;
				$tb_b = $wpdb->prefix.'bookings';
				$r = $wpdb->prefix.'rooms';
				if(isset($_POST['payment_method']) && $_POST['payment_method'] == 'cash')
				{
					$room_type = $_POST['room_type'];
					$num_rooms = $_POST['num_rooms'];
					$date_in = $_POST['date_in'];
					$date_out = $_POST['date_out'];					
				}else
				{
					$room_type = $_GET['room_type'];
					$num_rooms = $_GET['num_rooms'];
					$date_in   = $_GET['date_in'];
					$date_out   = $_GET['date_out'];					
				}
				$err = '';
				$q_r= "SELECT r.ID  
						FROM $r r 
						WHERE r.room_type_ID=$room_type 
							AND r.status='publish'						
					
							AND r.ID NOT IN 
							( 
								SELECT DISTINCT b.room_ID FROM $tb_b b 
								WHERE b.check_in < $date_out AND b.check_out > $date_in
								AND b.status='publish' 
							)
						ORDER BY r.room_name
						LIMIT 0, $num_rooms";
				
				$room = $wpdb->get_results( $q_r );
				if(count($room) < $num_rooms)
				{
					$err = __('Booking process was false, please contact with hotel and try again','hotel');
				}
				$currency = get_option('tgt_currency');
				$u_id = $_POST['u_id_free'];
				if(isset($_GET['u_id']) && !empty($_GET['u_id']) && empty($_POST['u_id']))
					$u_id = $_GET['u_id'];
				elseif(isset($_POST['u_id']) && !empty($_POST['u_id']) && empty($_GET['u_id']))
					$u_id = $_POST['u_id'];		
                if ( ( (isset($_POST['payment_status']) && $_POST['payment_status'] == "Completed" ) || ( isset($_POST['payment_status']) && $_POST['payment_status'] == "Pending" ) || (isset($_POST['pay_free']) && $_POST['pay_free'] == 'yes') || (isset($_POST['payment_method']) && $_POST['payment_method'] == 'cash') && $err == '' ) || ($validateIPN == true) ) {  
					$q_r = '';
					$code = md5('booking_'.$u_id);				
					
					if(isset($_POST['pay_free']) && $_POST['pay_free'] == 'yes')
					{						
						$room_type = $_POST['room_type_free'];
						$num_rooms = $_POST['num_rooms_free'];
						$date_in = $_POST['date_in_free'];
						$date_out = $_POST['date_out_free'];
						$promotion = $_POST['promotion'];
						$u_data = get_userdata($u_id);
						$u_code = get_user_meta($u_id,'tgt_customer_code',true);			
						update_user_meta($u_id, 'tgt_transaction_no', $u_id, true);
						update_user_meta($u_id,META_USER_PROMOTION,$promotion);
						if(!empty($u_data))
							update_user_meta($u_id, 'tgt_payer_email', $u_data->user_email, true);					
						
					}else
					{										
						$u_data = get_userdata($u_id);
						if(isset($_POST['payment_method']) && $_POST['payment_method'] == 'cash')
						{
							$room_type = $_POST['room_type'];
							$num_rooms = $_POST['num_rooms'];
							$date_in = $_POST['date_in'];
							$date_out = $_POST['date_out'];
							$u_data = get_userdata($u_id);
							$u_code = get_user_meta($u_id,'tgt_customer_code',true);
							update_user_meta($u_id, 'tgt_paycash', 'yes', true);
							update_user_meta($u_id, 'tgt_transaction_no', $u_id, true);
							if(!empty($u_data))
								update_user_meta($u_id, 'tgt_payer_email', $u_data->user_email, true);
							$promotion = $_POST['promotion'];
							update_user_meta($u_id,META_USER_PROMOTION,$promotion);
						}elseif(isset($_GET['payment_method']) && $_GET['payment_method'] == '2checkout')
						{
							$room_type = $_POST['room_type'];
							$num_rooms = $_POST['num_rooms'];
							$date_in = $_POST['date_in'];
							$date_out = $_POST['date_out'];
						}else
						{
							$room_type = $_GET['room_type'];
							$num_rooms = $_GET['num_rooms'];
							$date_in   = $_GET['date_in'];
							$date_out   = $_GET['date_out'];
							$verify_sign = $_POST['verify_sign'];
							$mail = $_POST['payer_email'];
							$mail = str_replace("%40", "@", $mail);      
							update_user_meta($u_id, 'tgt_transaction_no', $verify_sign, true);
							update_user_meta($u_id, 'tgt_payer_email', $mail, true);
						}
					}
					
					$tb_b = $wpdb->prefix.'bookings';
					$r = $wpdb->prefix.'rooms';
					$status = 'publish';
					if(isset($_POST['payment_method']) && $_POST['payment_method'] == 'cash')
					{
						$status = 'pending'; 
					}
					
					$q_r= "SELECT r.ID  
							FROM $r r 
							WHERE r.room_type_ID=$room_type 
								AND r.status='publish'						
						
								AND r.ID NOT IN 
								( 
									SELECT DISTINCT b.room_ID FROM $tb_b b 
									WHERE b.check_in < $date_out AND b.check_out > $date_in
									AND b.status='publish' 
								)
							ORDER BY r.room_name
							LIMIT 0, $num_rooms";
					
					$room = $wpdb->get_results( $q_r );					
					if(count($room) == $num_rooms && get_user_meta($u_id,'tgt_customer_code',true) == '')
					{
						for ($i=0; $i < count($room); $i++)
						{	
							$wpdb->insert( "$wpdb->prefix"."bookings", array('room_ID'=> $room[$i]->ID, 'user_ID'=>$u_id, 'check_in'=>$date_in, 'check_out'=>$date_out, 'status'=>$status));						
						}
						if(isset($_GET['total_price']) && $status == 'publish')
							$total_price = $_GET['total_price'];
						if(isset($_POST['total_price']) && ($status == 'pending' || $_GET['payment_method'] == '2checkout') )					
							$total_price = $_POST['total_price'];							
										
						if($total_price > 0 && $_POST['payment_method'] != 'cash' )
						{
							$user_data_tmp = get_userdata($u_id);
							$arg = array('booking_id'	=>	$u_id,
										 'customer_id'	=>	$u_id,
										 'date'	=>	date('Y-m-d',strtotime($user_data_tmp->user_registered)),
										 'amount'	=>	$total_price,
										 'currency'	=>	$currencysymbol);
							doInsert($arg);
						}
						update_user_meta($u_id, 'tgt_total_price',$total_price, true);						
					}
					else if(count($room) < $num_rooms)
						$err = __('Booking process was false, please contact with hotel and try again','hotel');						
					if($err == '')
					{
						/*
						* Add the services which users selected into user_meta
						*/
						if(isset($_POST['service_name']) && !empty($_POST['service_name']))
						{
							$arr_service = array();
							foreach($_POST['service_name'] as $k => $v)
							{
								$arr_service[$k] = $v;
							}
							update_user_meta($u_id, 'tgt_service', $arr_service, true);
						}
						update_user_meta($u_id,'tgt_customer_code',$code);
                ?>				
						<p class="h1"><?php _e('Booking Successful','hotel'); ?></p>
                        <font color="#FF0000" style="font-size:14px;"><?php _e('Your booking has been successfully !','hotel'); ?>
        
                        <p><?php _e('Transaction No!','hotel'); ?>: <?php echo $u_id; ?></p>
                        </font>
                        <p style="font-size:14px;">
                        <?php
						if($status == 'publish')
						{
							if(function_exists('icl_register_string')) 					
							{									
								echo icl_t('Success Message',md5(get_option('tgt_booking_success')), get_option('tgt_booking_success'));
							}else {
								echo get_option('tgt_booking_success');
							}
                            update_promotion_used($u_id, 'publish');
							
						}elseif($status == 'pending')
						{
							if(function_exists('icl_register_string')) 					
							{									
								echo icl_t('Cash Success Message',md5(get_option('tgt_cash_booking_success')), get_option('tgt_cash_booking_success'));
							}else {
								echo get_option('tgt_cash_booking_success');
							}
						}
						?></p>				
        
                        <?php	
                            if (get_user_meta($u_id,'tgt_customer_code',true) == '' && (get_option('tgt_successmail_payment') == "1" ||get_option('tgt_successmail_payment') == "") && $status == 'publish') {
                            $subject = get_option('tgt_mailsubject');
            
                            $websitename = get_bloginfo('name');
                            
                            $u_code = get_user_meta($u_id,'tgt_customer_code',true);
                            $first_name = get_user_meta($u_id,'first_name',true);
                            $last_name = get_user_meta($u_id,'last_name',true);
                            
                            $link_see= HOME_URL.'?action=check&code='.$u_code;
            
                            $message = get_option('tgt_mailcontent');
                            $fullname = $first_name.' '.$last_name;
                            $message = str_replace("[buyer_name]", "".$fullname, $message);
            
                            $message = str_replace("[website_name]", $websitename, $message);
            
                            $message = str_replace("[booking_link]", $link_see, $message);
            				
                            $to = $u_data->user_email;
            
                            $to_name = $fullname ;
            
                            $sub = $subject;
            
                            $mes = $message;
            
                            $from = get_option( 'tgt_mailfrom' );
                            $head = "From $websitename";	            
            
                            /*$headers = 'From: '.$websitename.' <'.$from.'>' . "\r\n" .
            
                                'Reply-To: '.$to_name.' <'.$to.'>' . "\r\n" .
            
                                'X-Mailer: PHP/' . phpversion();*/
                            
                             wp_mail( $to, $sub, $mes, $head);

                             //send mail for admin when pay successful
            				$to_admin = get_option( 'tgt_hotel_email' );
            				$sub_admin = __('Customer booking successfully','hotel');
            				
            				$mes_admin = __("There was a customer booking success!", 'hotel').'<br/>'.__('Email', 'hotel').':'.$to.'<br/>'.__('Details', 'hotel').':'
            						.$link_see.'<br/>'.__('Edit information', 'hotel').':'.WP_URL.' /wp-admin/admin.php?page=my-submenu-handle-add-booking&editbooking=true&uid='.$u_id ;
            				wp_mail($to_admin, $sub_admin, $mes_admin, $head);
                            }	
					}else if($err != '')
					{
				?>
                		<p class="h1"><?php _e('Booking Error','hotel'); ?></p>
                		<font color="#FF0000" style="font-size:14px;"><?php _e('Error','hotel'); ?>!
        
                        <p><?php _e('Transaction No!','hotel'); ?>: <?php echo $u_id; ?></p>
                        </font>
                        <p style="font-size:14px;">
                        <?php echo $err; ?></p>	 
                <?php
					}
                } 
				else { 
				?>	        
                	<p class="h1"><?php _e('Booking Error','hotel'); ?></p>
                    <font color="#C43535" style="font-size:14px;font-family: Arial, sans-serif"><?php _e('There is an error occurred','hotel'); ?>!
        
                    <p><?php _e('Transaction No','hotel'); ?>: <?php echo $u_id; ?></p>
                    </font>
                    <p style="font-size:14px; font-family: Arial, sans-serif">
                    <?php _e('Something went wrong, please try again','hotel'); ?>.</p>	
                <?php 
                    } 
                ?>
                
                <div style="clear:left;"></div>                        
                    <div id="search" style="margin-top:15px; float:left; margin-left:0;">  
                        <div id="search_left"></div>    
							<?php
							$home_link = HOME_URL;
							if ( get_option('tgt_using_wpml') && method_exists( $sitepress , 'get_current_language' ) )
							{
								$curr_lang = $sitepress->get_current_language();
								$home_link = $home_link.'/?lang='.$curr_lang;
								
							}
							?>	
                                <div id="search_center"><a href="<?php echo $home_link; ?>" ><?php _e('Home','hotel'); ?></a></div>
                        <div id="search_right"></div>
                    </div>
         		</div>
                </div>
             </div>     
            <?php get_sidebar();?>
            <div style="clear:left;"></div>      
    </div>
<!-- content end -->
<?php get_footer();?>
