<?php get_header();?>         
       
       <div class="middle-inner-wrapper" style="background:#e7dfd6 url(<?php echo TEMPLATE_URL.get_option('tgt_default_inner_background');?>) no-repeat center top;">
       
       <!--  Breadcrum  -->
       		<div class="localization">
            	<p class="site-loc"><a href="<?php echo HOME_URL;?>" style="color:white"><?php echo get_option('tgt_hotel_name') == '' ? __('Hotel','hotel') : get_option('tgt_hotel_name') ?> </a></p>
            	<p>
            	&raquo;&nbsp; <?php echo get_cat_name(get_query_var('cat')) ?>
            	</p>
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
		            	<p class="date">  <?php the_time('j F Y'); ?>  </p>
		            	
		            	<p class="h11"> <a href="<?php the_permalink() ?>" id="post-<?php the_ID()?>"> <?php the_title(); ?> </a> </p>
		            	<div class="title-comments">
							<!-- PRINT THE AUTHOR  -->
		                    <p class="by"> <?php _e(' BY ', 'hotel')?> <?php the_author() ?></p>
							<!-- PRINT THE COMMENTS-->
		                    <a href="<?php echo get_comments_link() ?>" class="comm"> <?php printf(_n(__('1 comment', 'hotel'), __('%s comments', 'hotel'), get_comments_number()) , get_comments_number()) ?> </a>
		                    <div style="clear:both;"></div>
		                </div>
		                
		                <div class="news-content">
			                <?php
			                /**
			                 * print the post
			                 */ 
			                the_content(__('more...'));
			                ?>     
		                </div>
                
               		</div>
       				
       				<?php 
       					} 
       				 } 
       				 else{
       				 ?>
       				 
       				 <div class="title">
		            	
		            	<p class="h11"> <?php _e('No Content Available!', 'hotel') ?> </p>
		            	<div class="title-comments">
		                </div>
		                
		                <div class="news-content">
		                	<p>
			                <?php
			                	_e('Sorry, but this section has no content!', 'hotel');
			                ?> 
			                </p>    
		                </div>
                
               		</div>
               		<?php  } ?>
       				
       				<!-- END CONTENT -->
                    
                    
                    <!-- PAGINATION -->
                    <?php tgt_the_pagination() ?>   
                    
                    
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
function tgt_the_category($post_id, $seperator){
	$categories = get_the_category($post_id);
	$echoString = '';
	foreach ($categories as $cat) {
		?>
		<a href="?cat=<?php echo $cat->cat_ID ?>"> 
			<?php echo $cat->name ?>
		</a>
		<?php
		echo '<span>' . $seperator . '</span>'; 
	}
}


?>
  