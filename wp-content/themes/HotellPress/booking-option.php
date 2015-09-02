<?php
global $wpdb, $sitepress, $post;
require_once dirname( __FILE__ ) . '/functions/form_process.php';
$room_info = get_postdata($_POST['roomtype']);
$is_tax = '';
$tax_fee = 0;
if(get_post_meta($room_info['ID'],META_ROOMTYPE_USE_TAX,true) == 'yes')
{
	 $is_tax = get_post_meta($room_info['ID'],META_ROOMTYPE_USE_TAX,true);
	 $tax_info = get_option('tgt_tax');
	 $tax_fee = 0;
	 $show_tax = '';
	 if($tax_info['type'] == 'percent')
	 {		  
		  $tax_fee = $paid * ($tax_info['amount']/100);
	 }elseif($tax_info['type'] == 'exact_amount')
	 {
		  $tax_fee = $currencysymbol.$tax_info['amount'];		  
	 }		
}


$guest_in_room = $num_adults;
$fields = array();
if(get_option('tgt_room_fields',true) != '')
{
	$fields = get_option('tgt_room_fields',true);
}
?>
<?php
get_header();
echo tgt_get_inner_background();
?>         
       
       <div class="middle-inner-wrapper" style="background:#e7dfd6 url(<?php echo TEMPLATE_URL.get_option('tgt_default_inner_background');?>) no-repeat center top;">
       
       		<div class="localization">
            	<p class="site-loc"><a href="<?php echo HOME_URL;?>" style="color:white"><?php echo get_option('tgt_hotel_name')?> </a></p>
            	<p>&raquo;&nbsp; <?php _e('Booking Options','hotel'); ?></p>
  			</div>
            
         <div style="clear:both;"></div>
         
            <div class="middle-inner">
       			<div class="center-inner">
                
       				<!-- This is content -->
       				 <div class="title">
						<!-- <p class="date">  <?php the_time('j F Y'); ?>  </p>-->
						
						<p class="h1"> <?php echo $room_type->post_title; ?></p>						
						
						
						
						<div style="margin: 15px 0pt;" class="contact-form">
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
										<td class="booking2"><strong><?php _e('Number Rooms','hotel'); ?></strong></td>
										<td class="booking2"><?php echo $num_rooms; ?></td>
								   </tr>
								   
								   <tr>
										<td class="booking2"><strong><?php _e('Occupancy','hotel'); ?></strong></td>
										<td class="booking2"><?php echo $guest_in_room.'&nbsp;'.__('Adult(s)','hotel'); ?></td>
								   </tr>
								   
								   <?php
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
	                        </tbody>
						 </table>						
						 <div class="clear"></div>
						
						 <div class="contact-form option-box">
							  <div class="box-header">
								  <?php echo $room_type->post_title; ?>
							  </div>
							  <form action="<?php echo tgt_get_booking_link();?>" method="post">	
							  <div class="box-content">
								   <?php
									$tran_id = $room_info['ID'];
									 if ( method_exists ( $sitepress, 'get_current_language' ) )
										  $tran_id = icl_object_id( $room_info['ID'] , 'roomtype', true, $sitepress->get_current_language );
									 
									 $post = get_post($tran_id);
									 setup_postdata($post);
								   
								   $img_id = get_post_thumbnail_id($room_info['ID']); 
								   $img =  wp_get_attachment_image_src($img_id );
								  
								   $img = $img[0];								   
								   if (!$img)$img = TEMPLATE_URL .'/images/image_small.gif';	       
								   ?>
								   <a target="_blank" href="<?php echo get_permalink($room_info['ID']); ?>" title="<?php echo $room_type->post_title; ?>">
								   <img style="float:left;" src="<?php echo $img;?>" alt="<?php echo $room_type->post_title; ?>" height="75px" width="100px"/>
								   </a>
								   <div class="bcontent">
										<p>
										  <?php //the_excerpt() ?>
										  <?php echo tgt_limit_content($post->post_content, 34); ?>
											 <a target="_blank" style="color:#7F7F7F;" href="<?php echo get_permalink($tran_id); ?>" title="<?php _e('See more','hotel'); ?>">[...]</a>
										</p>
										<div class="options">
											 <h3><?php _e('Rooms','hotel'); ?>:</h3>
											 <table width="100%" cellpadding="5">
											 <?php											 
											 $count_room = 0;
											 ksort($capability);
											
											 for($i=0; $i<count($get_room); $i++)
											 {												 
											 ?>
												  <tr class="option-title">
													   <td width="30%"><?php _e('Room','hotel'); ?>&nbsp;<?php echo $count_room = $i+1; ?>&nbsp;(<?php echo $room_type->post_title; ?>): </td>
													   <td width="70%">
															
															<select name="number_room[<?php echo $i; ?>]">
																 <?php
																 if(!empty($capability))
																 {
																	  
																	  $price_person = 0;
																	  foreach($capability as $k => $v)
																	  {
																		   if(!empty($capability_tmp))
																		   {
																				//$price_person = $v;
																				$total_room_price = 0;
																				//$capability = $capability_tmp[$arrival_date];			
																				foreach($capability_tmp as $k_tmp => $v_tmp)
																				{																						 
																					 $room_price = 0;
																					 $room_price = $v_tmp[$k];
																					 $total_room_price +=  $room_price;																					
																				}
																				$price_person = $total_room_price;
																		   }
																		   else
																		   {
																				$price_person = $v['price']  * $day_rate;
																		   }
																 ?>
																	  <option value="<?php echo $k.'_'.$price_person; ?>" <?php if($check_selected == $k) { echo 'selected="selected"'; } ?> >
																		   <?php
																		   echo $k.' ';
																		   if($k == 1)
																				_e('Person','hotel');
																		   else
																				_e('Persons','hotel');
																		   ?>
																		   (
																		   <?php echo $currencysymbol.$price_person; ?>
																		   )
																	  </option>
																 <?php																		   
																	  }
																 }
																 ?>																
															</select>
													   </td>
												  </tr>
											 <?php
											 }
											 ?>												  
											 </table>											 
											 <?php
											 for($i=0; $i<count($get_room); $i++)
											 {
											 ?>
											 <br>
											 <table width="100%">
												  <tr >
													   <td width="25%" style="border: 1px solid #4f3626;" align="center"><?php _e('Room','hotel'); ?>&nbsp;<?php echo $count_room = $i+1; ?>&nbsp;(<?php echo $room_type->post_title; ?>): </td>
													   <?php
													   if(!empty($capability))
													   {
															foreach($capability as $k => $v)
															{
																
													   ?>
																 <td style="border: 1px solid #4f3626;" align="center">
																	  <?php
																	  echo $k.' ';
																	  if($k == 1)
																		   _e('Person','hotel');
																	  else
																		   _e('Persons','hotel');
																	  ?>																
																 </td>															
													   <?php																		   
															}
													   }
													   ?>			
												  </tr>
												  <?php
												 
												  for($j = $arrival_date; $j < $departure_date; $j += (3600*24))
												  {
												  ?>
													   <tr>
															<td width="25%" style="border: 1px solid #4f3626;" align="center">
																 <?php echo date('d M y',$j); ?>
															</td>
													   <?php
													   if(!empty($capability))
													   {																	  
															
															foreach($capability as $k => $v)
															{
																 $price_person = 0;
																 if(!empty($capability_tmp))
																 {

																	  //$price_person = $v;
																	  $total_room_price = 0;
																	  //$capability = $capability_tmp[$arrival_date];			
																	  foreach($capability_tmp as $k_tmp => $v_tmp)
																	  {
																		   if($k_tmp == $j )
																		   {
																				$room_price = 0;
																				$room_price = $v_tmp[$k];
																				$total_room_price =  $room_price;
																		   }
																	  }
																	  $price_person = $total_room_price;
																	  
																 }
																 else
																 {
																	  $price_person = $v['price'];
																 }
													   ?>
																 <td style="border: 1px solid #4f3626;" align="center">
																	  <?php
																	  echo $currencysymbol.$price_person;
																	  ?>																
																 </td>															
													   <?php																		   
															}
													   }
													   ?>	
													   </tr>
												  <?php
												  }
												  ?>
											 </table>											
											 <?php
											 }
											 ?>
										</div>
										<?php
										if(get_option('tgt_room_option',true) != '')
										{
											 $room_service = get_post_meta($room_info['ID'],META_ROOMTYPE_SERVICES,true);
										?>										
											 <?php
											 if(!empty($room_service))
											 {
											 ?>
											 <div class="services">
												  <h3><?php _e('Services','hotel'); ?>:</h3>
											 <?php
												  foreach($room_service as $k => $v)
												  {
											 ?>											 
													   <p>
														   <input type="checkbox" name="services[]" value="<?php echo $k; ?>"/>
														   <label for="service_1">
															<?php
															if (function_exists('icl_register_string')) {
												  				echo icl_t('Services',md5($v['name']), $v['name']);
  															}else {
  																echo $v['name'];
  															}
															 echo ' ('.$currencysymbol.$v['price'].')';
															?>
														   </label>
													   </p>
											 <?php
												  }
											 ?>
											 </div>
											 <?php
											 }											
										}
										?>
										<div class="services">
											 <h3><?php _e('Coupon Code','hotel'); ?>:</h3>
											 <input type="text" name="promotion" value="" />
										</div>
										<input type="hidden" name="tax_fee" value="<?php echo $tax_fee; ?>" />										
										<?php
										/*
										?>
										<h3 class="total-payment">
										
											 <?php _e('Total','hotel'); ?>:
											 <span>											 
												  <?php echo $currencysymbol; ?>
												  <span id="show_total">
													   <?php echo $paid; ?>
												  </span>
											 <?php
											 _e('and','hotel');
											 if($tax_info['type'] == 'percent')
											 {
												  echo '&nbsp;'.$tax_info['amount'].'% '.__('TAX','hotel');
											 }elseif($tax_info['type'] == 'exact_amount')
											 {
												  echo '&nbsp;'.$currencysymbol.$tax_info['amount'].'&nbsp;'.__('TAX','hotel');
											 }
											 ?>										
											 </span>
										</h3>
										<?php
										*/
										?>
									</div>
							  </div>
						 </div>
	                    
						 <div id="search" style="margin-top:15px; float:left; margin-left:0;">  
							<div id="search_left"></div>       
									<div id="search_center"><a href="#dialog" name="modal"><?php _e('Change Date','hotel'); ?></a></div>
							<div id="search_right"></div>
						 </div>
						 										 
						 <?php
						 if(get_option('tgt_permit_payment')){
							 foreach ($_POST as $key=>$val){
								 echo "<input type='hidden' name='$key' value='$val'/> \n";
							 } 
						 ?>
							 <input type="hidden" name="roomtype" value="<?php echo $room_info['ID'];?>"/>
						 <?php
						 }
						 ?>	
						 <div id="search" style="margin-top:15px; float:right;">  
							  <div id="search_left"></div>        
							  <div id="search_center">
							  <input type="submit" name="go_booking_page" value="<?php _e('Continue','hotel');?>" class="png" />
							  </div>
							  <div id="search_right"></div>
						 </div>
						 </form>							 
	                </div>
						
						<!-- END CONTENT -->                
                
					</div>  <!-- Class Title -->
                    
                    <div style="clear:both;"></div>
			   </div>
		  </div>
        
		  <?php get_sidebar();?>
		  <div class="bottom">
			  <!--<img src="<?php echo TEMPLATE_URL;?>/images/inner-page-bottom.jpg" alt="inner_page_bottom_image"/>-->
		  </div>
       
		  </div>
<script type="text/javascript">
//function update_total_price_selected_room(value)
//{
//	 var price_each_room = value.split("_");
//	 var total_price_tmp = document.getElementById('total_tmp').value;
//	 alert(document.getElementById('number_room').value);
//}
</script>
    <!-- content end -->
<?php get_footer();?>
  