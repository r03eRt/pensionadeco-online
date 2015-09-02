<?php 
/*
Template Name: Contact Page
*/
get_header(); ?>

<?php	
	$isSentMail = 0;
	
	if(!empty($_POST['customer_name']))
	{			
		$comment = $_POST['comments'];				
		$hotel_email = get_option('tgt_hotel_email');
		
		$comment = stripslashes($comment);
		$comment = $comment."\n\n\n"."----------------------------------------------"."\n\n";
		$comment = $comment."From: ".$_POST['customer_name']."\n";
		$comment = $comment."Email: ".$_POST['email']."\n";
		$comment = $comment."Telephone: ".$_POST['phone']."\n";

		if($hotel_email !='')
		{
			$exist_domain= 1;
			$exp = "^[a-z\'0-9]+([._-][a-z\'0-9]+)*@([a-z0-9]+([._-][a-z0-9]+))+$";
			
			if(eregi($exp,$hotel_email))
			{
				$exist_domain = 1;
			}
			else
			{				
				$exist_domain = 0;
			}   
			if($exist_domain != 0)
			{
				$header = 'From: '.$_POST['customer_name'].' <'.$_POST['email'].'> ';
				@wp_mail($hotel_email, 'A message from customer', $comment, $header);
				$isSentMail = 1;				
			}
			else 
			{
				$isSentMail = -1;
			} 			
		}
		else 
		{
			$isSentMail = -1;
		}	
	}
?>	


       	<div class="middle-inner-wrapper" style="background:#e7dfd6 url(<?php echo TEMPLATE_URL.get_option('tgt_default_inner_background');?>) no-repeat center top;">
       
       		<div class="localization">       		 		
            	<p class="site-loc"> <a href="<?php echo HOME_URL;?>" style="color:white"> <?php echo get_option('tgt_hotel_name');?></p><p>&raquo;&nbsp;<?php echo the_title();?> </a> </p>
  			</div>
            
		<div style="clear:both;"></div>
            <div class="middle-inner">
       			<div class="center-inner">
	                <div class="title">
	            		<p class="h1">
	            		<?php echo the_title();?>
	            		</p> 
	                </div>
                	<a href="<?php echo tgt_get_location_link();?>">
                	<div class= "address"><p><i>
					<?php  echo get_option('tgt_hotel_street'); ?><br />
                	<?php echo get_option('tgt_hotel_state');?></i><br />
                	<b><font style="color:rgb(71,62,62);">                	     	
                	<?php echo get_option('tgt_hotel_country');?></font>
                	 </b>
                	</p> 
                	</div></a>
                	          	
                		<div class="phone-nr">
                		<?php  $phone = get_option('tgt_hotel_phone'); ?>
                		<?php              		
                		 if($phone['p_1']!=null) { ?>                			                		               	
                			<div class="nr">
                				<p><?php  echo $phone['p_1'];?></p>
                			</div>
                		<?php }?>
                		<?php              		
                		 if($phone['p_2']!=null) { ?>                			                		               	
                			<div class="nr">
                				<p><?php  echo $phone['p_2'];?></p>
                			</div>
                		<?php }?>
                		<?php              		
                		 if($phone['p_3']!=null) { ?>                			                		               	
                			<div class="nr">
                				<p><?php  echo $phone['p_3'];?></p>
                			</div>
                		<?php }?>
                	</div>                                			
                	<div style="clear: both;"></div>                	
                	<?php 
	                   	
	                   	if($isSentMail == 1)
	                   	{	
	                ?>
	                <div class="contact-form"> 
		                <p id="send_mail_error" style="width: auto; font-weight: bold; font-size: 12px; color: rgb(255, 0, 0);">
		                	<?php 	
		                			$hotel_name = get_option('tgt_hotel_name'); 
		                			if($hotel_name == "")
		                				$hotel_name = "hotel";
		                			echo  $hotel_name;
		                			_e(' received your email. We will reply as soon as. Thank you.', 'hotel');
		             		?>
		             	</p>
		             </div>	
		             <?php } ?> 
	                <?php 
	                	if($isSentMail == 0)
	                	{
	                ?>
	                	<div class="contact-form"> 
			        	<p id="send_mail_error" style="width: auto; font-weight: bold; font-size: 12px; color: rgb(255, 0, 0);"></p>						
						</div> 
	                <?php }	                   		                       		
	                   	if($isSentMail == -1)
	                   	{
	                ?>
	                	<div class="contact-form"> 
			        	<p id="send_mail_error" style="width: auto; font-weight: bold; font-size: 12px; color: rgb(255, 0, 0);">
						<?php 
							_e ('Can not send your e-mail to ', 'hotel');
							$hotel_name = get_option('tgt_hotel_name');
							if($hotel_name == "")
								$hotel_name = "hotel";
							echo $hotel_name;
						?>
						</p>
						</div> 
	                <?php }	?>                
			            
                	<div class="contact-form"> 
                	<div style="clear: both;"></div>                	
                		<form action="" method="post" onsubmit="return checkInput();">
                		<table border="0">
  	             		<tr><td>
                			<input type="hidden" name="page_id" value="<?php echo get_the_ID();?>" /> 
                			<div class="input-content">
                			<p><?php _e('Your Name(*):','hotel'); ?></p>
                				<input class="input" type="text" name="customer_name" size="15"  />
                			</div>
                		</td><td>	                			
                			<div class="input-content">
            				<p><?php _e('E-mail Address(*):','hotel'); ?> </p>
                        	<input class="input" type="text" name="email" size="15" />    
	                        </div>
	                      </td></tr>
	                      <tr>
	                      	<td><p id="name_error" style="width: auto; font-weight: normal; font-size: 12px; color: rgb(255, 0, 0);"></p>
	                      	</td>
	                      	<td>
	                      		<p id="email_error" style="width: auto; font-weight: normal; font-size: 12px; color: rgb(255, 0, 0);"> </p>
	                      	</td>
	                      </tr>
	                      <tr><td>
	                        	<div class="input-content">
	           					<p><?php _e('Phone Number(*):', 'hotel'); ?></p>
	                        	<input class="input" type="text" name="phone" size="15" /> 
                         </div>
	                      </td><td></td>
	                      </tr>
	                      <tr><td colspan="2">
	                      <p id="phone_error" style="width: auto; font-weight: normal; font-size: 12px; color: rgb(255, 0, 0);" > </p>
	                      </td></tr>
	                      <tr><td colspan= "2">                  
	                        <div class="input-content">
		                        <p><?php _e('Message(*):', 'hotel'); ?></p>
		                        <textarea id = "cus_message" class="input-textarea" name="comments" rows="5" cols="50" ></textarea>                       
	                        </div>
	                      </td></tr>
	                      <tr>
	                      <td colspan="2">
	                      	<p id="comment_error" style="width: auto; font-weight: normal; font-size: 12px; color: rgb(255, 0, 0);"></p>
	                      </td>
	                      </tr> 
	                      <tr><td colspan="2">
	                        <div style="clear:left;"></div>
	                        
	                        <div style="margin-top: 15px; float: left; margin-left: 0pt;" class="button">  
								<div class="button_left"></div>        
									<div class="button_center">
										<input type="submit" value="<?php _e ('Send Message', 'hotel');?>" class="button" name="contact_admin" >
									</div>
								<div class="button_right"></div>
							</div>
	                     </td></tr>
	                     </table>   
	                        <div style="clear:left;"></div>
						</form>						
                	</div>                	
                    <div style="clear:both;"></div>
        		</div>
			</div>			
            <?php get_sidebar();?>
    	</div>   	
    <!-- content end -->
<?php get_footer();?>    

<script type="text/javascript">
	var customer_name_empty = "<?php _e('* Enter your name.', 'hotel'); ?>";
	var email_empty = "<?php _e('* Enter your e-mail.', 'hotel'); ?>";
	var email_invalid = "<?php _e('* Email invalid.', 'hotel'); ?>";
	var phone_empty = "<?php _e('* Enter your phone number.', 'hotel'); ?>";
	var phone_invalid = "<?php _e('* Phone number invalid.', 'hotel'); ?>";
	var message_empty = "<?php _e('* Enter your message.', 'hotel'); ?>";
</script>    