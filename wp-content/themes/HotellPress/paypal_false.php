<?php get_header();?>  
<?php echo tgt_get_inner_background(); ?>
        <div class="localization">
            <p class="site-loc"><a href="<?php echo HOME_URL;?>" style="color:white"> <?php echo get_option('tgt_hotel_name');?></a></p><p>&raquo;&nbsp;<?php _e('Payment Result','hotel'); ?></p>
        </div>
         <div style="clear:both;"></div>         
            <div class="middle-inner">
       			<div class="center-inner">
                <div class="title">                
                    <p class="h1"><?php _e('Payment False','hotel'); ?></p>
             		<font color="#C42B2B" style="font-size:14px; font-family: Arial, sans-serif"><?php _e('Your transaction has been false, please try again'); ?></font>
                    <div style="clear:left;"></div>                        
                    <div id="search" style="margin-top:15px; float:left; margin-left:0;">  
                        <div id="search_left"></div>       
                                <div id="search_center"><a href="#dialog" name="modal"><?php _e('Back','hotel'); ?></a></div>
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
