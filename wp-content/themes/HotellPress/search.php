<?php
$check_in = '';
$check_out = '';

if (!empty($_POST['arrival_date'])){
	$check_in = strtotime($_POST['arrival_date']);
	$check_out = strtotime($_POST['departure_date']);
}
else{
	$check_in = strtotime($_POST['from']);
	$check_out = strtotime($_POST['to']);
}
global $wp_query, $sitepress, $post;
// get lang
if ( get_option('tgt_using_wpml') && method_exists( $sitepress , 'get_current_language' ) )
{
	$curr_lang = $sitepress->get_current_language();
}

?>
<?php get_header();?>       
    <div class="middle-inner-wrapper" style="background:#e7dfd6 url(<?php echo TEMPLATE_URL . get_option('tgt_default_inner_background');?>) no-repeat center top;">
   
		<div class="localization">
			<p class="site-loc"><a href="<?php echo HOME_URL;?>" style="color:white"><?php echo get_option('tgt_hotel_name');?></a></p><p>&raquo;&nbsp;<?php _e ('Check rooms avalible', 'hotel');?></p>
		</div>
		
		<div style="clear:both;"></div>
	 
		<div class="middle-inner">
			<div class="center-inner">
                <div class="title">
	            	<p class="h1"><?php _e ('Booking', 'hotel');?></p>
	                
	                <div class="contact-form" style="margin:15px 0;">
	                	<table width="100%">
	                    	<tbody>
	                        	<tr>
	                            	<td class="booking2"><strong><?php _e ('Arrival/Check-in', 'hotel');?></strong></td>
	                                <td class="booking2"><?php if(!empty($check_in)) echo date ('d M y', $check_in); else _e('Empty','hotel');?></td>
	                            </tr>
	                            
	                            <tr>
	                            	<td class="booking2"><strong><?php _e ('Departure/Check-out', 'hotel');?></strong></td>
	                                <td class="booking2"><?php if(!empty($check_out)) echo date ('d M y', $check_out); else _e('Empty','hotel');?></td>
	                            </tr>
	                            
	                            <!--<tr>
	                            	<td class="booking2"><strong><?php _e ('Number Rooms', 'hotel');?></strong></td>
	                                <td class="booking2"><?php echo $num_rooms;?></td>
	                            </tr>
	                            
	                            <tr>
	                            	<td class="booking2"><strong><?php _e ('Occupancy', 'hotel');?></strong></td>
	                                <td class="booking2"><?php echo $num_adults .__ (' Adult(s)', 'hotel');  _e (' and  ', 'hotel'); echo $num_kids . __(' Kid(s)', 'hotel'); ?></td>
	                            </tr>-->
	                            <tr>
	                            	<td class="booking2"><strong><?php _e ('Day(s)', 'hotel');?></strong></td>
	                                <td class="booking2"><?php echo floor(($check_out - $check_in)/86400);?></td>
	                            </tr>
	                            <!--<tr>
	                            	<td class="booking2"><strong><?php _e ('Guests per Room', 'hotel');?></strong></td>
	                                <td class="booking2"><?php echo $num_kids + $num_adults;?></td>
	                            </tr>	-->                            
	                            
	                        </tbody>
	                    </table>
	                    
	                    <div id="search" style="margin-top:15px; float:right;">  
                            <div id="search_left"></div>        
                                <div id="search_center">
                                   <a href="#dialog" name="modal"><?php _e('Change Date','hotel'); ?></a>
                                 </div>
                            <div id="search_right" style="background: url('<?php echo TEMPLATE_URL;?>/images/search-btnright4.png')"></div>
	                   </div>	                    
	                </div>
	                
	                <div style="clear:both;"></div>
	                <?php if (!have_posts()) echo '<p style="width: 250px; font-weight: normal; font-size: 15px; color: rgb(255, 0, 0);">'.__('Sorry, Not rooms available with your conditions. Please change date! ', 'hotel') . '</p>';?>
	                <?php
					if(get_option('tgt_permit_payment') == '1'){
						$payment_method = get_option('tgt_payment_method',true);
						if($payment_method['paypal'] == '1' || $payment_method['2checkout'] == '1' || $payment_method['direct'] == '1')
						{
							$permit_payment = array ('link'=>tgt_get_booking_option(), 'text'=>__('Book Now', 'hotel'));
						}else
						{
							$permit_payment = array ('link'=>tgt_get_contact_link(), 'text'=>__('Contact Us', 'hotel'));
						}
					}	           
	                else{
	                	$permit_payment = array ('link'=>tgt_get_contact_link(), 'text'=>__('Contact Us', 'hotel'));
	                }
	                while (have_posts()){	                	
	               		the_post();
								$id = intval( $post->ID );
								
								$tran_room = $post;
								// get lang
								if ( get_option('tgt_using_wpml') && method_exists( $sitepress , 'get_current_language' ) )
								{
									//$curr_lang = $sitepress->get_current_language();
									$tran_id = icl_object_id( $post->ID, 'roomtype', true, $curr_lang );
									$tran_room = get_post( $tran_id );
								}							

								$excerpt = apply_filters( 'the_excerpt', $tran_room->post_content );
								$excerpt = str_replace(']]>', ']]&gt;', $excerpt);
								$excerpt = wp_html_excerpt($excerpt, 252) . '...';
								
								$img_id = get_post_thumbnail_id( get_the_ID() ); 
								$img =  wp_get_attachment_image_src($img_id );
						
	               		//$img = get_metadata('post', get_the_ID(), 'tgt_roomtype_thumbnail', true);
	               		$img = $img[0];
	               		if (empty($img))$img = TEMPLATE_URL . '/images/room-box-bg.jpg';	            
	                ?>
	                
	                <div class="contact-form" style="margin:15px 0; background-color:#3C1910; float:left;"> <!-- detail one roomtype -->
	                	<div class="detail">
	                    	<table>
	                        	<tbody>
	                            	<tr>
	                                	<td class="detail2">
	                                		<img style="float:left;" src="<?php echo $img;?>" alt=""
	                                		height="75px" width="100px"/>
	                                	</td>
	                                    
	                                    <td class="detail2" style="width: 510px;">
		                                    <a target="_blank"  href="<?php echo get_permalink($id);?>"><?php echo $tran_room->post_title;?></a><br/><br/>
		                                    <p><?php  echo $excerpt;?> </p>
	                                     </td>
	                                </tr>
	                            </tbody>
	                        </table>
	                    </div>	                    
	                    <div class="reservations" style="margin-bottom:10px;">
	                        <div class="reservations_left"></div>
	                        <div class="reservations_center">
	                        	<form action="<?php echo $permit_payment['link']?>" method="post">
	                        		<?php
	                        		if(get_option('tgt_permit_payment')){
		                        		foreach ($_POST as $key=>$val){
		                        			echo "<input type='hidden' name='$key' value='$val'/> \n";
		                        		} 
		                        	?>
		                        		<input type="hidden" name="roomtype" value="<?php echo $id;?>"/>
		                        	<?php
	                        		}
	                        		?>	                        		
	                        		<input name="booking" type="submit" value="<?php echo $permit_payment['text'];?>" class="button" />
	                        	</form>
	                        </div>
	                        <div class="reservations_right"></div>
	                    </div>
	                    
	                    <div class="reservations" style="margin-bottom:10px; float:left; margin-left:10px;">
	                        <div class="reservations_left"></div>
	                        <div class="reservations_center">
		                        <a style="color:#fff;" target="_blank"  href="<?php echo get_permalink($id);?>">
		                       		<?php _e ('Detail', 'hotel');?>
		                        </a>
	                        </div>
	                        <div class="reservations_right"></div>
	                    </div>
	
	                </div>  <!-- //detail one roomtype -->         
	                
	                <?php
	                 } 
	                ?>          
	              

                </div>
        	</div>
		</div>
		
		<?php get_sidebar();?>
	    <div class="bottom">
			<!--<img src="<?php echo TEMPLATE_URL;?>/images/inner-page-bottom.jpg" alt="inner_page_bottom_image"/>-->
	    </div>
   
	</div>
<!-- content end -->
<?php get_footer();?>
  