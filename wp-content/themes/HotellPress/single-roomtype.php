<?php get_header();?> 
       <?php 
	   global $post;
	   the_post(); 
	 
		$currency = get_option('tgt_currency');
		if ( $currency == "USD" || $currency == "AUD" || $currency == "CAD" || $currency == "NZD" || $currency == "HKD" || $currency == "SGD" ) { $currencysymbol = "$"; }
		else if ( $currency == "GBP" ) { $currencysymbol = "&pound;"; }
		else if ( $currency == "JPY" ) { $currencysymbol = "&yen;"; }
		else if ( $currency == "EUR" ) { $currencysymbol = "&euro;"; }
		else { $currencysymbol = ""; }
	   ?>	
       <div class="middle-inner-wrapper" style="background:#e7dfd6 url(<?php echo TEMPLATE_URL.get_option('tgt_default_inner_background');?>) no-repeat center top;">
       
       		<div class="localization">
            	<p class="site-loc"><a href="<?php echo HOME_URL;?>" style="color:white"> <?php echo get_option('tgt_hotel_name'); ?></a></p>
            	<p><a href="<?php echo tgt_get_roomtypes_link();?>" style="color:white"> &raquo;&nbsp;<?php _e('Rooms','hotel'); ?></a></p>
            	<p>&raquo;&nbsp;<?php the_title(); ?></p>
  			</div>
            
         <div style="clear:both;"></div>
         
           <div class="middle-inner">
       		 <div class="center-inner">
                <div class="title">
            	<p class="h1"><?php the_title(); ?></p>
                
                <div class="contact-form" style="margin:15px 0;">
                	<div class="contentdetail">					
						<?php  the_content(); ?>
					</div>				
               
		
                </div>
				<div class="contact-form" style="margin:15px 0;">
                	<table width="100%">
                    	<tbody>
                        	<tr>
                            	<td class="booking2"><strong><?php _e ('Capability', 'hotel');?></strong></td>
                                <td class="booking2"><?php echo get_post_meta($post->ID, META_ROOMTYPE_CAPABILITY, true); ?></td>
                            </tr>
                            
                            <tr>
                            	<td class="booking2"><strong><?php _e ('Price', 'hotel');?></strong></td>
                                <td class="booking2"><?php echo $currencysymbol.get_post_meta($post->ID, META_ROOMTYPE_PRICE, true); ?></td>
                            </tr>
                            <?php 
                            $field = Fields::getInstance();
							$field_list = $field->getActivatedFields(); 
							if (!empty($field_list) && is_array($field_list) ){
							foreach ($field_list as $key => $fields){
							?>
                            <tr>
                            	<td class="booking2"><strong>
								<?php
								    if (function_exists('icl_register_string')) {
										echo icl_t('Additional fields',md5($fields['field_name']), $fields['field_name']);
  									}else {
  										echo $fields['field_name'];
  									}
								?>
								</strong></td>
                            	<?php if ($fields['field_type'] == FIELD_TYPE_CHECKBOX){ ?>
                            			<td class="booking2"><?php 
										if(get_post_meta($post->ID, META_ROOMTYPE_FIELD.$key, true)== 'yes'){
											_e ('Allow', 'hotel');
										}
										else{
											_e ('Not allow', 'hotel');
										}								
										?></td>
                            	<?php }else { ?>                            	
                                	<td class="booking2"><?php echo get_post_meta($post->ID, META_ROOMTYPE_FIELD.$key, true); ?></td>
                                <?php } ?>
                            </tr>
                            <?php } } ?>
                            
                           
                        </tbody>
                    </table>
                </div>
                <script type="text/javascript" src="<?php echo TEMPLATE_URL; ?>/js/jquery.jcarousel.min.js"></script>
                <link href="<?php echo TEMPLATE_URL;?>/css/skin.css" type="text/css" rel="stylesheet" media="all" />
                <script type="text/javascript">
	              jQuery(document).ready(function() {
	                  jQuery('#mycarousel').jcarousel();
	              });
                </script>
                         
                <div class="contact-form">
                <?php 
                 $files = get_children("post_parent=$post->ID&post_type=attachment&post_mime_type=image&orderby=menu_order&order=ASC"); 
                                     		
						if($files){	
						echo '<ul';
						echo ( count($files) > 5 )?' id="mycarousel" class="jcarousel-skin-tango" ':'' ;
						echo '>';  
						foreach ($files as $k => $v){						
							$image_thumbnail = wp_get_attachment_image_src($v->ID);
														
							echo '<li class="thumbnail">';
							?>
                    			<a rel="lightbox-mygallery" href="<?php echo $v->guid; ?>"  title="<?php echo $v->post_title; ?>" ><img  src="<?php echo $image_thumbnail[0]; ?>" width="100px" height="40px" alt=""/></a>
                   			
                   			<?php 
                   			echo  '</li>'; 
						} 						
						echo '</ul>';
						}
                ?>
               
					
				</div>
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
  