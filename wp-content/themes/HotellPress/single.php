
<?php 

include( TEMPLATEPATH . '/comments.php' ); 
/***
 * Site: single.php
 * Author: Toan
 * Desc: for new detail view page
 * 
 */
?>

<?php get_header();?>         
       
       <div class="middle-inner-wrapper" style="background:#e7dfd6 url(<?php echo TEMPLATE_URL.get_option('tgt_default_inner_background');?>) no-repeat center top;">
       
       		<div class="localization">
            	<p class="site-loc"><a href="<?php echo HOME_URL;?>" style="color:white"><?php echo get_option('tgt_hotel_name')?> </a></p>
            	<p>&raquo;&nbsp; <?php echo tgt_the_news_title(); ?></p>
  			</div>
            
         <div style="clear:both;"></div>
         
            <div class="middle-inner">
       			<div class="center-inner">
                
       				<!-- This is content -->
				
       				<?php
       				/**
       				 * SHOW THE POST
       				 */
       				
       				if (have_posts()) {
       					while (have_posts()) {
       						the_post();
       				?>
       				
       				 <div class="title">
<!--            	<p class="date">  <?php the_time('j F Y'); ?>  </p>-->
            	
            	<p class="h1"> <?php the_title(); ?></p>
                <div class="news-content">
	                <?php
	                /**
	                 * print the post
	                 */ 
	                the_content();
	                ?>
                </div>
       			<!-- END CONTENT -->
                
                <!-- COMMENTS -->        
               	<?php comments_template('', true); ?>
               	
				<?php if ('open' == $post->comment_status) { ?>
				<?php include(TEMPLATEPATH . '/comment-form.php') ?>
				<?php }?>
               	
                <?php }
       				}
                ?>
                
                <!-- END COMMENT LIST -->
                
                </div>  <!-- Class Title -->
                    
                    <div style="clear:both;"></div>
        		</div>
			</div>
        
            <?php get_sidebar();?>
	       <div class="bottom">
	       		<!--<img src="<?php echo TEMPLATE_URL;?>/images/inner-page-bottom.jpg" alt="inner_page_bottom_image"/>-->
	       </div>
       
    	</div>
    <!-- content end -->
    <?php get_footer();?>
    
    <?php 

/**
 * 
 * use to print out the categories list
 * @param $post_id the post id that categories are belonged to
 * @param $seperator the seperator
 * @return echo the categories list
 * 
 */
function tgt_the_news_title(){
	$title_id = get_query_var('p');
	return get_the_title($title_id);
}

?>
  