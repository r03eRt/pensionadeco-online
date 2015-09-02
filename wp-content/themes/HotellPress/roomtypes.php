<?php
/*
Template Name: Roomtypes
 */
global $wp_query;
get_header();?>
       <div class="middle-inner-wrapper" style="background:#e7dfd6 url(<?php echo TEMPLATE_URL.get_option('tgt_default_inner_background');?>) no-repeat center top;">
       
       		<div class="localization">
            	<p class="site-loc"><a href="<?php echo HOME_URL;?>" style="color:white"> <?php echo get_option('tgt_hotel_name'); ?></a></p><p>&raquo;&nbsp;<?php _e ('Rooms', 'hotel');?></p>
  			</div>            
         <div style="clear:both;"></div>         
            <div class="middle-inner">
       			<div class="center-inner">               
       				<!-- This is content -->
                    <?php								
						global $post;
						query_posts("post_type=roomtype&orderby=name");
						if ( have_posts() ) {		
							?>
						<div class="title">
	            		<p class="h1"><?php the_title();
		            		
		            		?></p> 
						</div>
						<?php $i=0; while ( have_posts() ) { the_post(); $i++; 
						 //$thumbnail_id = get_post_thumbnail_id($post->ID);
						// $link_image = wp_get_attachment_image_src( $thumbnail_id, 'roomtype-image' );
						//$link_thumbnail =  wp_get_attachment_image_src($thumbnail_id);	
						if(has_post_thumbnail()) {
							$thumbnail_id = get_post_thumbnail_id($post->ID);
							$link_image = wp_get_attachment_image_src( $thumbnail_id, 'roomtype-image' );
						} else {	
						  $args = array(
									'post_type' => 'attachment',
									'numberposts' => -1,			
									'post_parent' => $post->ID,	
									'post_mime_type' => 'image'
									); 
							$attachments = get_posts($args);
							$link_image = wp_get_attachment_image_src( $attachments[0]->ID, 'roomtype-image' );	
						}			
						?>						 
						<?php if($i%2!=0){  ?>
						
						<div class="room-left">
							<div class="room-box" align="center">			
									
								<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
								<?php if ($link_image[0] != ''){ ?>
								<img src="<?php echo $link_image[0]; ?>" />
								<?php } ?>
								</a>
							</div>
							<a class="room-link" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
						
						</div>
						<?php } else{ ?>
						<div class="room-right">
							<div class="room-box" align="center">
								<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
								<?php if ($link_image[0] != ''){ ?>
								<img src="<?php echo $link_image[0]; ?>" style="margin-top:3px;" />
								<?php } ?>
								</a>
							</div>
							<a class="room-link" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
						</div>
						
						<?php } };
						}else{ ?>
						<div class="title">		            	
		            	<p class="h11"> <?php _e('No Rooms Available!', 'hotel') ?> </p>
		            	<div class="title-comments">
		                </div>
		                
		                <div class="news-content">
		                	<p>
			                <?php
			                	_e('Sorry, but this section has no rooms!', 'hotel');
			                ?> 
			                </p>    
		                </div>
                
               		</div>
						<?php }	?>
						<?php wp_reset_query(); ?>
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