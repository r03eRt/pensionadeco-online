<?php get_header();?>  
<?php
global $wpdb;
echo tgt_get_inner_background();
require_once dirname( __FILE__ ) . '/functions/form_process.php';
$is_tax = '';
if(get_post_meta($_POST['roomtype'],META_ROOMTYPE_USE_TAX,true) == 'yes')
{
	$is_tax = get_post_meta($_POST['roomtype'],META_ROOMTYPE_USE_TAX,true);
	$tax_info = get_option('tgt_tax');
	$tax_fee = 0;
	if($tax_info['type'] == 'percent')
	{
	   $tax_fee = $price * ($tax_info['amount']/100);
	   $price = $price + $tax_fee;
	   $show_tax = $tax_info['amount'].'%';
	}elseif($tax_info['type'] == 'exact_amount')
	{
	   $tax_fee = $currencysymbol.$tax_info['amount'];
	   $price = $price + $tax_info['amount'];
	   $show_tax = $tax_fee;
	}	
}

$paid = ($deposit/100)*$price;
$paid = round($paid,2);
if(count($get_room)< $num_rooms)
{
?>     
        <div class="localization">
            <p class="site-loc">
	            <a href="<?php echo HOME_URL;?>" style="color:white">
	            	<?php echo get_option('tgt_hotel_name');?>
	            </a>
            </p>
            <p>&raquo;&nbsp;<?php _e('Booking payment','hotel'); ?></p>
        </div>
         <div style="clear:both;"></div>         
            <div class="middle-inner">
       			<div class="center-inner">
                <div class="title">                
                    <p class="h1"><?php _e('Booking','hotel'); ?></p>
             		<font color="#FF0000" style="font-size:14px;"><?php _e('Booking has a problem, please try again', 'hotel'); ?></font>
                    <div style="clear:left;"></div>                        
                    <div id="search" style="margin-top:15px; float:left; margin-left:0;">  
                        <div id="search_left"></div>       
                                <div id="search_center"><a href="#dialog" name="modal"><?php _e('Back','hotel'); ?></a></div>
                        <div id="search_right"></div>
                    </div>
         		</div>
                </div>
             </div>         
<?php	
}else {
require_once dirname( __FILE__ ) . '/booking_sub.php';
$payment_method = get_option('tgt_payment_method',true);

//echo '<pre>';
//print_r($payment_method);
//echo '</pre>';

$guest_in_room = $num_adults;
$fields = array();
if(get_option('tgt_room_fields',true) != '')
{
	$fields = get_option('tgt_room_fields',true);
}
?> 

       		<div class="localization">
            	<p class="site-loc">
            		<a href="<?php echo HOME_URL;?>" style="color:white">
	            		<?php echo get_option('tgt_hotel_name');?>
	            	</a>
            	</p><p>&raquo;&nbsp;<?php _e('Booking payment','hotel'); ?></p>
  			</div>
         <div style="clear:both;"></div>         
            <div class="middle-inner">
       			<div class="center-inner">
                <div class="title">                
            	<p class="h1"><?php _e('Booking','hotel'); ?></p>
                
                <div class="contact-form" style="margin:15px 0;">
                	<table width="100%">
                    	<tbody>
                        	<tr>
                            	<td class="booking2"><strong><?php _e('Arrival/Check-in','hotel'); ?></strong></td>
                                <td class="booking2"><?php echo date('d M y',$arrival_date); ?></td>
                            </tr>
                            
                            <tr>
                            	<td class="booking2"><strong><?php _e('Departure/Check-out','hotel'); ?></strong></td>
                                <td class="booking2"><?php echo date('d M y',$departure_date); ?></td>
                            </tr>
                            
                            <tr>
                            	<td class="booking2"><strong><?php _e('Rooms Type','hotel'); ?></strong></td>
                                <td class="booking2"><?php echo $room_type->post_title; ?></td>
                            </tr>
                            
                            <tr>
                            	<td class="booking2"><strong><?php _e('Number Rooms','hotel'); ?></strong></td>
                                <td class="booking2"><?php echo $num_rooms; ?></td>
                            </tr>
                            
                            <tr>
                            	<td class="booking2"><strong><?php _e('Occupancy','hotel'); ?></strong></td>
                                <td class="booking2"><?php echo $guest_in_room.'&nbsp;'.__('Adult(s)','hotel'); ?></td>
                            </tr>
                            <?php
							/*
							 * Custom fields which user selected of room type
							 */
							if(!empty($fields))
							{
								foreach($_POST as $k=>$v)
								{									
									if(is_numeric($k) && !empty($v))
									{
							?>
										<tr>
											<td class="booking2"><strong><?php echo $fields[1]['field_name']; ?></strong></td>
											<td class="booking2"><?php echo $_POST[$k]; ?></td>
										</tr>
							<?php
									}
								}
							}
							?>
                            <tr>
                            	<td class="booking2"><strong><?php _e('Day(s)','hotel'); ?></strong></td>
                                <td class="booking2"><?php echo $day_rate; ?></td>
                            </tr>                            
                            <tr>
                            	<td class="booking2"><strong><?php _e('Price room(s)','hotel'); ?></strong></td>
                                <td class="booking2">
									<?php
									if(!empty($_POST['number_room']))
									{
										$room_price = 0;
										$arr_tmp = array();										
										foreach($_POST['number_room'] as $k => $v)
										{
											$v = explode('_',$v);
											$arr_tmp[] = $currencysymbol.$v[1];
											$room_price += $v[1];
										}
										$arr_tmp = implode(' + ',$arr_tmp);										
									}
									echo $currencysymbol.$room_price;
									if(!empty($arr_tmp))
									{
										echo ' ( '.$arr_tmp.' )';
									}									
									?>									
									<?php
									if(get_option('tgt_allow_alternal_currency',true) == '1')
									{
										//echo tgt_calculate_alternal_price($room_price);
									}
									?>
								</td>
                            </tr>
                            
							<?php
							/*
							 * Services information which user selected
							 */							
							if(isset($_POST['go_booking_page']) && !empty($_POST['go_booking_page']))
							{
								if(isset($_POST['services']) && !empty($_POST['services']))
								{
									$default_services = get_post_meta($_POST['roomtype'],META_ROOMTYPE_SERVICES,true);
									foreach($_POST['services'] as $k => $v)
									{
										if($default_services[$v]['name'] != '')
										{
							?>
											<tr>
												<td class="booking2">
													<strong>
														<?php echo $default_services[$v]['name']; ?>
													</strong>
												</td>
												<td class="booking2">
													<?php
													echo $currencysymbol.$default_services[$v]['price'].'&nbsp;';
													if(get_option('tgt_allow_alternal_currency',true) == '1')
													{
														//echo tgt_calculate_alternal_price($default_services[$v]['price']);
													}
													?>
												</td>
											</tr>
							<?php											
										}
									}
								}
							}
							?>

							<?php
							if($promotion_total_price[0] != 0 && !empty($promotion_total_price))
							{
							?>
								<tr>
									<td class="booking2"><strong><?php _e('Promotion','hotel'); ?></strong></td>
									<td class="booking2"><?php echo $currencysymbol.round($promotion_total_price[0],2); ?></td>
								</tr>
							<?php
							}
							?>	
							
							<?php
							if(get_post_meta($_POST['roomtype'],META_ROOMTYPE_USE_TAX,true) == 'yes')
							{
							?>
							<tr>
                            	<td class="booking2"><strong><?php _e('TAX','hotel'); ?></strong></td>
                                <td class="booking2"><?php echo $show_tax; ?></td>
                            </tr>
							<?php
							}
							?>
							
							<tr>
                            	<td class="booking2"><strong><?php _e('Deposit','hotel'); ?></strong></td>
                                <td class="booking2"><?php echo $deposit.'%'; ?></td>
                            </tr>
							
                            <tr>
                            	<td class="booking2" style="color:#955845;"><strong><?php _e('Total Charges','hotel'); ?></strong></td>
                                <td class="booking2" style="color:#955845;">
									<strong>
										<?php echo $currencysymbol.$paid; ?>										
										<?php
										if(get_option('tgt_allow_alternal_currency',true) == '1')
										{
											echo tgt_calculate_alternal_price($paid);
										}
										?>
									</strong>
								</td>
                            </tr>
                        </tbody>
                    </table>
                    
                    <div id="search" style="margin-top:15px; float:right;">  
                        <div id="search_left"></div>       
                            <div id="search_center"><a href="#dialog" name="modal"><?php _e('Change Date','hotel'); ?></a></div>
                        <div id="search_right" style="background: url('<?php echo TEMPLATE_URL;?>/images/search-btnright4.png')"></div>   
                    </div>
                </div>
                
                <div style="clear:both;"></div>    
                
                <div class="contact-form">
                	<p class="h1" style="color:#282323; padding-bottom:10px; font:35px Georgia;"><?php _e('Traveller Details','hotel'); ?></p>
                    <!-- start fill info -->
                    <div id="fill_info" style="display:">
                	<form method="post" name="form_fill_info" id="form_fill_info" action="" > 
                    
                    <div style="width:290px; float:left;">   
                    	<div class="input-content">
           					<p><?php _e('Title :','hotel');?></p>
                        	<select class="select2" name="s_title" id="s_title">
											<option value="Mr."><?php _e('Mr.','hotel'); ?></option>
											<option value="Mrs."><?php _e('Mrs.','hotel'); ?></option>
											<option value="Ms."><?php _e('Ms.','hotel'); ?></option>
							</select>
                        </div>              
                        <div class="input-content">
            				<p><?php _e('First Name :','hotel');?>*</p>
  							<input class="input" type="text"  name="first_name" id="first_name" size="15" />
                            <p id="first_err" style="width:auto; font-weight:normal; font-size:12px; color:#FF0000;"></p>
                        </div>  
                        <div class="input-content">
            				<p><?php _e('Phone :','hotel');?>*</p>
  							<input class="input" type="text" name="phone" id="phone" size="15" />
                            <p id="phone_err" style="width:auto; font-weight:normal; font-size:12px; color:#FF0000;"></p>
                        </div>  
                        <div class="input-content">
                            <p><?php _e('City/State :','hotel');?>*</p>
                            <input class="input" type="text" name="state" id="state" size="15" />
                            <p id="state_err" style="width:auto; font-weight:normal; font-size:12px; color:#FF0000;"></p>
                        </div>                                              
                    </div>
                     <div style="width:290px; float:left;">   
                     	<div class="input-content">
            				<p><?php _e('Email :','hotel');?>*</p>
  							<input class="input" type="text" name="email" id="email" size="15" />
                            <p id="email_err" style="width:auto; font-weight:normal; font-size:12px; color:#FF0000;"></p>
                        </div> 
                        <div class="input-content">
            				<p><?php _e('Last Name :','hotel');?>*</p>
  							<input class="input" type="text" name="last_name" id="last_name" size="15" />
                            <p id="last_err" style="width:auto; font-weight:normal; font-size:12px; color:#FF0000;"></p>
                        </div>
<?php
      $country = tgt_get_countries();
      $def_country = get_option('tgt_hotel_country');
      ?>
                        <div class="input-content">
                <p><?php _e('Country :','hotel');?>*</p>                           
                            <select name="country" id="country" class="select2" >
                            <?php
       foreach ($country['countries'] as $k=>$v)
       {
       ?>
                             <option value="<?php echo $v; ?>"
 
<?php if($def_country == $v) echo 'selected="selected"' ?>><?php echo $v; ?></option>
                            <?php
       }
       ?>
                            </select>                        
                        </div>

                                         
                        <div class="input-content">
            				<p><?php _e('Street :','hotel');?>*</p>
  							<input class="input" type="text" name="street" id="street" size="15" />
                            <p id="street_err" style="width:auto; font-weight:normal; font-size:12px; color:#FF0000;"></p>
                        </div>                        
					</div>   
                    <div class="input-content" style="float:left; width:600px;">
						<?php
						$display = 'none;';
						if(($payment_method['paypal'] == '1' || $payment_method['2checkout'] == '1') && $payment_method['direct'] == '1')
						{
							$display = '';
						}elseif($payment_method['paypal'] == '1' && $payment_method['2checkout'] == '1')
						{
							$display = '';
						}
						?>
						<p style="font-size:12px; font-weight:normal;display:<?php echo $display; ?>">
						<?php
						if($payment_method['paypal'] == '1' || $payment_method['2checkout'] == '1' || $payment_method['direct'] == '1')
						{
						?>
							<?php
							if($payment_method['paypal'] == '1' || $payment_method['2checkout'] == '1')
							{
								if($payment_method['paypal'] == '1' || $payment_method['2checkout'] == '1')
								{
							?>
								<input type="radio" name="purchase_agree" checked="checked" id="purchase_agree" value="1"/>&nbsp;<?php _e('I agree to purchase online.','hotel'); ?>						
								&nbsp;&nbsp;&nbsp;&nbsp;
								<?php
								}
								if($payment_method['paypal'] == '1' && $payment_method['2checkout'] == '1')
								{
								?>								
									<select name="payment_service" id="payment_service" class="select2" style="width: 120px; margin-right: 0px; margin-bottom: 0px; padding-top: 0px; padding-bottom: 2px;">
										<option value="paypal"><?php _e('Paypal','hotel'); ?></option>
										<option value="2checkout"><?php _e('2Checkout','hotel'); ?></option>
									</select>								
								<?php
								}elseif($payment_method['paypal'] == '1' && $payment_method['2checkout'] == '0')
								{
								?>
									<input type="hidden" name="payment_service" id="payment_service" value="paypal" />
								<?php
								}elseif($payment_method['paypal'] == '0' && $payment_method['2checkout'] == '1')
								{
								?>
									<input type="hidden" name="payment_service" id="payment_service" value="2checkout" />
							<?php
								}
								echo '<br>';
							}
							if($payment_method['direct'] == '1')
							{
								$input_type = 'radio';
								$input_label = __('I agree to purchase by cash.','hotel');
								if($payment_method['paypal'] != '1' && $payment_method['2checkout'] != '1')
								{
									$input_type = 'hidden';
									$input_label = '';
								}
							?>												
							<input type="radio" name="purchase_agree" id="purchase_agree" value="0"/>&nbsp;<?php echo $input_label; ?>
							<?php
							}
							?>
						</p>
						<?php
						}elseif($payment_method['paypal'] == '0' && $payment_method['2checkout'] == '0' && $payment_method['direct'] == '1')
						{
						?>
							<input type="hidden" name="purchase_agree" id="purchase_agree" value="0" />
						<?php
						}
						?>						
                        	<input style="float:left; margin-right:10px;margin-bottom: 15px;" type="checkbox" id="check_agree"  name="check_agree"> <p style="font-size:12px; font-weight:normal;">
<?php _e('Yes, I checked the all information and agree to booking online.','hotel'); ?></p>
                        </div> 
                        <div style="clear:left;"></div>                        
                   		 <div id="search" style="margin-top:0; float:left; margin-left:0;">
							 <div id="search_left"></div>     
								<?php
								$term_link = tgt_get_link_term();
								$term_name = __('Terms &amp; Conditions','hotel');
								if ( get_option('tgt_using_wpml') && method_exists( $sitepress , 'get_current_language' ) )
								{
									$curr_lang = $sitepress->get_current_language();
									$pages = get_option('tgt_pages_default');
									$tran_id = icl_object_id( $pages['footer_menu']['terms_of_use'], 'page', true, $curr_lang );
									$tran_term = get_post( $tran_id );
									$term_link = get_permalink( $tran_term->ID );
									$term_name = $tran_term->post_title;
								}							
								?>      
                                <div id="search_center"><a target="_blank" href="<?php echo $term_link; ?>"><?php echo $term_name; ?></a></div>	
                            <div id="search_right" style="background: url('<?php echo TEMPLATE_URL;?>/images/search-btnright4.png')"></div>                            
                        </div>
                        
                        <div id="search" style="margin-top:0; margin-left:0; float:right;">  
                            <div id="search_left"></div>        
                                    <div id="search_center">
                                    <input type="button" name="submit_continue" onclick="fill_data('fill_info');" value="<?php _e('Continue','hotel');?>" id="butt_save" class="png" />
                                    </div>
                            <div id="search_right"></div>
                        </div>
                
					</form>                    
                </div>
                <div style="clear:both;"></div>
                </div>
                <!-- end fill info -->
                <!-- start list info -->
                <div id="list_info" style="display:none"> 				                
                <div class="contact-form" style="margin:15px 0;">
                	<table width="100%">
                    	<tbody>
                        	<tr>
                            	<td class="booking2" width="47%"><strong><?php _e('Customer','hotel'); ?></strong></td>
                                <td class="booking2"><div id="show_full_name"></div></td>
                            </tr>
                            
                            <tr>
                            	<td class="booking2"><strong><?php _e('Email','hotel'); ?></strong></td>
                                <td class="booking2"><div id="show_email"></div></td>
                            </tr>
                            
                            <tr>
                            	<td class="booking2"><strong><?php _e('Phone','hotel'); ?></strong></td>
                                <td class="booking2"><div id="show_phone"></div></td>
                            </tr>
                            
                            <tr>
                            	<td class="booking2"><strong><?php _e('Country','hotel'); ?></strong></td>
                                <td class="booking2"><div id="show_country"></div></td>
                            </tr>
                            
                            <tr>
                            	<td class="booking2"><strong><?php _e('State','hotel'); ?></strong></td>
                                <td class="booking2"><div id="show_state"></div></td>
                            </tr>                            
                           
                            <tr>
                            	<td class="booking2"><strong><?php _e('Street','hotel'); ?></strong></td>
                                <td class="booking2"><div id="show_street"></div></td>
                            </tr>
							
							<tr id="show_payment_parent">
                            	<td class="booking2" style="color:#955845;"><strong><?php _e('Payment Method','hotel'); ?></strong></td>
                                <td class="booking2" style="color:#955845;"><strong><div id="show_payment"></div></strong></td>
                            </tr>
                            
                        </tbody>
                    </table>
				<?php 				
                if($paid == 0 || get_option('tgt_permit_payment') == 0) 
                { 
                ?>
                    <form method="POST" name="submit_payment_free" id="submit_payment_free" action="<?php echo tgt_free_payment_link (); ?>" >
                    <input type="hidden" name="pay_free" value="yes">
                    <input type="hidden" name="u_id_free" id="u_id_free">
                    <input type="hidden" name="room_type_free" id="room_type_free" value="<?php echo $room_type->ID; ?>">
                    <input type="hidden" name="num_rooms_free" id="num_rooms_free" value="<?php echo $num_rooms; ?>">
                    <input type="hidden" name="date_in_free" id="date_in_free" value="<?php echo $date_in; ?>">
                    <input type="hidden" name="date_out_free" id="date_out_free" value="<?php echo $date_out; ?>">
					<input type="hidden" name="promotion" id="promotion" value="<?php echo $promotion_price[1]; ?>" />
					<?php
					/*
					 * Services information which user selected
					 */							
					if(isset($_POST['go_booking_page']) && !empty($_POST['go_booking_page']))
					{
						if(isset($_POST['services']) && !empty($_POST['services']))
						{
							$default_services = get_post_meta($_POST['roomtype'],META_ROOMTYPE_SERVICES,true);
							foreach($_POST['services'] as $k => $v)
							{
								if($default_services[$v]['name'] != '')
								{
					?>
									<input type="hidden" name="service_name[<?php echo $default_services[$v]['name']; ?>]" value="<?php echo $currencysymbol.$default_services[$v]['price'] ?>" />									
					<?php											
								}
							}
						}
					}
					?>
					
                    <div style="clear:left;"></div>                        
                    <div id="search" style="margin-top:15px; float:left; margin-left:0;">  
                        <div id="search_left"></div>       
                                <div id="search_center"><a href="#dialog" name="modal"><?php _e('Change Date','hotel'); ?></a></div>
                        <div id="search_right"></div>
                    </div>
                    <div id="search" style="margin-top:15px; float:right;">  
                        <div id="search_left"></div>        
                                <div id="search_center">
                                <input type="submit" name="submit_confirm" value="<?php _e('Confirm','hotel');?>" id="butt_save" class="png" />
                                </div>
                        <div id="search_right"></div>
                    </div>
                    </form>
                <?php
				}else if(get_option('tgt_permit_payment') == 1 && $paid >0)
				{					
				?>
					<div id="paypal_info">
						<form method="POST" name="submit_payment" id="submit_payment" action="<?php echo tgt_get_payment_submit(); ?>" >
						<input type="hidden" name="payment_page" id="payment_page"  value="" />
						<input type="hidden" name="payment_type" id="payment_type"  value="" />
						<input type="hidden" name="item_number" id="paypal_item_number" />    
						<input type="hidden" name="invoice" id="paypal_invoice" />
						<input type="hidden" name="promotion" id="promotion" value="<?php echo $promotion_price[1]; ?>" />
						<input type="hidden" name="u_id" id="u_id" />
							
						<?php
						/*
						 * Services information which user selected
						 */							
						if(isset($_POST['go_booking_page']) && !empty($_POST['go_booking_page']))
						{
							if(isset($_POST['services']) && !empty($_POST['services']))
							{
								$default_services = get_post_meta($_POST['roomtype'],META_ROOMTYPE_SERVICES,true);
								foreach($_POST['services'] as $k => $v)
								{
									if($default_services[$v]['name'] != '')
									{
						?>
										<input type="hidden" name="service_name[<?php echo $default_services[$v]['name']; ?>]" value="<?php echo $currencysymbol.$default_services[$v]['price'] ?>" />									
						<?php											
									}
								}
							}
						}
						?>	
							
						<div style="clear:left;"></div>                        
						<div id="search" style="margin-top:15px; float:left; margin-left:0;">  
							<div id="search_left"></div>       
									<div id="search_center"><a href="#dialog" name="modal"><?php _e('Change Date','hotel'); ?></a></div>
							<div id="search_right"></div>
						</div>
						<div id="search" style="margin-top:15px; float:right;">  
							<div id="search_left"></div>        
									<div id="search_center">
									<input type="submit" name="submit_continue" value="<?php _e('Confirm & Pay','hotel');?>" id="butt_save" class="png" />
									</div>
							<div id="search_right"></div>
						</div>
						</form>
					</div>
                <?php
				}
				?>
                </div>                
                <div style="clear:both;"></div>
                </div>
                <!-- end list info -->
                </div>
        		</div>
			</div>
<?php } ?>
            <?php get_sidebar();?>
            <div style="clear:left;"></div>      
    </div>
<!-- content end -->
<?php get_footer();?>