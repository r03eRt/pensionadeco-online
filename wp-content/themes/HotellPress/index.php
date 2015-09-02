<?php get_header('index');?>
<?php
global $wp_query, $sitepress;

function test_func() {
	echo '<img title="gallery" class="wpGallery mceItem" _mce_src="http://localhost/wordpress/wp-includes/js/tinymce/plugins/wpgallery/img/t.gif" src="http://localhost/wordpress/wp-includes/js/tinymce/plugins/wpgallery/img/t.gif" _moz_resizing="true">';
}
add_shortcode('test', 'test_func');?>
	<div id="slideshow">
    		<div id="features"> 
				<ul id="feature-links"> 
				<li> 
					<a href="#feature-1" id="feature-link-1" title="Image 1" class="feature-link active"></a>				</li> 
				<li> 
					<a href="#feature-2" id="feature-link-2" title="Image 2" class="feature-link"></a>				</li> 
				<li> 
					<a href="#feature-3" id="feature-link-3" title="Image 3" class="feature-link"></a>				</li> 
				<li> 
					<a href="#feature-4" id="feature-link-4" title="Image 4" class="feature-link"></a>				</li> 
				<li> 
					<a href="#feature-5" id="feature-link-5" title="Image 5" class="feature-link"></a>				</li> 
				</ul> 
 
 			<!--  Get image -->
 			<?php 
 				$image = get_option('tgt_image_slider'); 			
 				if($image['i_1'] !='') { 
 			?>
			<div style="display: block;" id="feature-1" class="feature-story active"> 
				<img src="<?php echo TEMPLATE_URL.$image['i_1'];?>" alt="Image 1" title="Image 1" class="feature-photo"/>			</div> 
 			<?php }
 				if($image['i_2'] !='') {
 			?>
			<div style="display: none;" id="feature-2" class="feature-story"> 
				<img id="image_2" src="" alt="Image 2" title="Image 2" class="feature-photo"/>			</div> 
 			<?php }
 				if($image['i_3'] !='') {
 			?>
			<div style="display: none;" id="feature-3" class="feature-story"> 
				<img id="image_3" src="" alt="Image 3" title="Image 3" class="feature-photo"/>			</div> 
 			<?php }
 				if($image['i_4'] !='') {
 			?>
			<div style="display: none;" id="feature-4" class="feature-story"> 
				<img id="image_4" src="" alt="Image 4" title="Image 4" class="feature-photo"/>			</div> 
 			<?php }
 				if($image['i_5'] !='') {
 			?>
			<div style="display: none;" id="feature-5" class="feature-story"> 
				<img id="image_5" src="" alt="Image 5" title="Image 5" class="feature-photo"/>				</div> 
			</div> 
			<?php } ?>
   	  	</div>
				
				<div style="clear:both;"></div>
                
        <div id="booking_al">
        	<form action="https://www.thebookingbutton.co.uk/properties/pensionadecodirect?utf8=✓" target="_blank" method="get" class="">
                <input type="hidden" name="locale" value="es">
                <div class="titulo">Reserva online</div>
                <div class="search-llegada">
                    <label style="margin-right: 10px;">Llegada</label>
                    <input type="date" name="start_date" class="checkin" id="checkin" value="">
                </div>
                <div class="search-huespedes">
                    <label>N. personas</label>
                    <input type="number" class="huespedes" name="number_adults" value="2">
                </div>
                <div class="search-submit">
                    <input type="submit" class="send-button" value="Consultar">
                </div>
            </form>
            <script src='http://pensionadeco.com/wp-content/themes/HotellPress/jquery.tools.min.js'></script>
            <script>
			  $(document).ready(function() {
				$(':date').dateinput({
				  format: 'd mmm yyyy',
				  min: 0,
				  max: 720,
				  speed: 'fast',
				  firstDay: 1
				});
			  });
			</script>
        </div>
				<?php
				if(get_option('tgt_permit_reservations') == '1')
				{
				?>
        <br />
				<div class="booking">	
					<div style="width:120px; float:left; margin:25px 0 0 30px;">
						<img style="float:left; margin:0; padding:0;" id="booking" src="<?php echo TEMPLATE_URL;?>/images/booking.jpg" alt="booking" />
						<p style="font:14px arial; font-weight:bold; color:#414141; float:left;">
						<a style="color:#414141;" href="#"><?php _e('BOOKING', 'hotel'); ?></a></p></div>
					<div style="width:730px; float:left;">
						<form  id="searchform_index" action="<?php echo tgt_get_page_link('search'); ?>" method="post"> 		   
							<div class="calendar">
								<input type="text" name="arrival_date"  id="start-date" class="check datepicker"
								value="<?php echo date('m/d/Y');?>"/>
								<a id="calendar"><img src="<?php echo TEMPLATE_URL;?>/images/calendar.jpg" alt="calendar"/></a>		
							</div>
							<div class="calendar date-pick">
								<input type="text" name='departure_date'  id="end-date" class="check datepicker" 
								value="<?php echo date('m/d/Y', strtotime('tomorrow'));?>"/>
								<a id="calendar"><img src="<?php echo TEMPLATE_URL;?>/images/calendar.jpg" alt="calendar" /></a>
							</div>
							
							<div class="select">
								<select name="num_adults" style="width:112px; margin-top:5px; margin-left:8px; background: transparent; border:0px;" > 
										<option value="2"><?php _e('Adults', 'hotel'); ?></option>
										<?php
										$max_ppl = get_option('tgt_max_people_per_booking') ? get_option('tgt_max_people_per_booking') : 8;
										for ( $i = 1; $i <= $max_ppl; $i++ )
										{
											echo '<option value="' . $i . '">' . $i . '</option>';
										}
										?>
									</select>
							</div>
							
              <!-- Indica el numero de apartamentos que se van a gestionar en una reserva  --->
              <input type="hidden" name="num_rooms" value="1" />
                
							 <div class="select"> 
							<select name="num_rooms" class="rooms" style="width:112px; margin-top:5px; margin-left:8px; background: transparent; border:0px;">--> 
										<option value="1"><?php _e('Number rooms','hotel'); ?></option>	  -->
										<?php
										$max_rooms = get_option('tgt_max_rooms_per_booking') ? get_option('tgt_max_rooms_per_booking') : 8;
										for ( $i = 1; $i <= $max_rooms; $i++ )
										{
											echo '<option value="' . $i . '">' . $i . '</option>';
										}
										?>
									</select> 
								</div>   
                
					
							<div class="button" style="margin-left: 59px;">  
								<div class="button_left"></div>        
								<div class="button_center">
									<input name="search" type="submit" value="<?php _e ('Search', 'hotel');?>" class="button" />
								</div>
								<div class="button_right"></div>
							</div>           
						</form> 
					</div>
<?php
if ( get_option('tgt_using_wpml') && method_exists( $sitepress , 'get_current_language' ) )
{
	$curr_lang = $sitepress->get_current_language();
	$tran_id = icl_object_id( tgt_get_learn_more_id(), 'page', true, $curr_lang );
	$tran_page = get_post( $tran_id );
								}							
?>        		
				
				</div>
				<?php
				}
				?>
        	<?php
				if ( is_active_sidebar( 'bottom-widget-area' ) ) { ?>				                                       
				<?php dynamic_sidebar( 'bottom-widget-area' ); ?>                       
									
				<?php } ?>            
				
            <div style="clear:both;"></div>	
		
		<div class="bottom">
	
		</div>

    <!-- content end -->
<?php get_footer();?>