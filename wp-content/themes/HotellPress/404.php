<?php get_header();?> 
       <div class="middle-inner-wrapper" style="background:#e7dfd6 url(<?php echo TEMPLATE_URL.get_option('tgt_default_inner_background');?>) no-repeat center top;">       
       		<div class="localization">       			
            	<p class="site-loc"><a href="<?php echo HOME_URL;?>" style="color:white"><?php echo get_option('tgt_hotel_name');?></a></p><p>&raquo;&nbsp;<?php _e ('Page not found', 'hotel'); ?></p>
  			</div>
            
         <div style="clear:both;"></div>
         
            <div class="middle-inner">
       			<div class="center-inner">
	                <div class="title">	            		 
	            		
	            		<p class="h1">
		            		<?php _e ('Page not found', 'hotel');?>
	            		</p>
	            		<h5><?php _e ('Sorry, this page not exist. Click ', 'hotel'); 
		            		printf(  '<a href="'. HOME_URL .'" style="color:blue"> %s </a>', __( 'here', D_DOMAIN )); 
		            		_e('to redirect home', 'hotel');?>
	            		</h5>
	            		<div style="clear:both;"></div>                
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
  