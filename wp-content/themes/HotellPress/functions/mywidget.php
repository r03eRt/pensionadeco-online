<?php
/***********************************************/
////////////////////////////////////////////////////////////
// NEW FUNCTIONS
////////////////////////////////////////////////////////////
// Post Format support. You can also use the legacy "gallery" or "asides" (note the plural) categories.
	add_theme_support( 'post-formats', array( 'aside', 'gallery' ) );

	// This theme uses post thumbnails
	add_theme_support( 'post-thumbnails' );

	// Add default posts and comments RSS feed links to head
	add_theme_support( 'automatic-feed-links' );
	add_image_size( 'roomtype-thumb', 271, 105 );
//register menu
register_nav_menus( array(
		'headermenu' => __( 'Header Navigation Menu', 'hotel' ),
	) );
register_nav_menus( array(
		'footermenu' => __( 'Footer Navigation Menu', 'hotel' ),
	) );
add_action('template_redirect','ajax_hotel');
function ajax_hotel()
{
global $wp_rewrite;
	if (!empty($_REQUEST['do_ajax']))
	{
		$action = $_POST['action'];
		switch ($action)
		{
			case 'ajax_testimonial':
				include_once TEMPLATEPATH . '/ajax/ajax_testimonial.php';
				break;
			case 'ajax_option':
				include_once TEMPLATEPATH . '/ajax/ajax_option.php';
				break;
                        case 'admin_pricing_time':
                            include_once TEMPLATEPATH. '/ajax/ajax_pricing_time.php';
                            break;
			case 'ajax_capability_price':
				include_once TEMPLATEPATH . '/ajax/ajax_capability_price.php';
				break;				
		}
		do_action('do_ajax');
	}
}		

//Text box widget Bottom
class Text_Box_Widget_Bottom extends WP_Widget {	
	function Text_Box_Widget_Bottom() {
		$widget_ops = array('classname' => 'widget_content', 'description' => __( 'Widget Home', 'hotel' ) );
		$this->WP_Widget('content', __( 'Widget Home', 'hotel' ), $widget_ops);
	}
	function widget($args, $instance) {
		extract($args, EXTR_SKIP);
		$title = apply_filters( 'widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);
		$content = apply_filters( 'widget_content', $instance['content'], $instance, $this->id_base );
		$sub = apply_filters( 'widget_content', $instance['titlesub'], $instance, $this->id_base );
		
		echo $before_widget;
		echo '<div class="col">';
		if($title != ""){
		echo $before_title;		
		echo '<h1>'.$title.'</h1>';
		echo $after_title;
		}
		if($sub != ""){
		echo '<p class="h2">';
		echo $sub;
		echo '</p>';
		}
		if($content !="" ){
		echo '<h4>';
		echo $content;
		echo '</h4>';
		}
		echo '</div>';
		echo $after_widget;
	}

	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['titlesub'] = strip_tags($new_instance['titlesub']);
		$instance['content'] = $new_instance['content'];
		return $instance;
	}

	function form($instance) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '','titlesub' => '', 'content' => '' ) );
		$title = strip_tags($instance['title']);
		$titlesub = strip_tags($instance['titlesub']);
		$content = $instance['content'];		

		echo '<p><label for="'. $this->get_field_id('title') .'">'.__('Title','hotel').': <input class="widefat" id="'. $this->get_field_id('title') .'" name="'. $this->get_field_name('title') .'" type="text" value="'. esc_attr($title).'" /></label></p>';
		echo '<p><label for="'. $this->get_field_id('titlesub') .'">'.__('Subject','hotel').': <input class="widefat" id="'. $this->get_field_id('titlesub') .'" name="'. $this->get_field_name('titlesub') .'" type="text" value="'. esc_attr($titlesub).'" /></label></p>';
		echo '<p><label for="'. $this->get_field_id('content') .'">'.__('Content','hotel').': <textarea class="widefat" id="'. $this->get_field_id('content').'" rows="10" cols="40" name="'. $this->get_field_name('content').'" type="text">'. $content.'</textarea></label></p>';						

	}
}


//Testimonial widget Bottom
class Testimonial_Widget_Bottom extends WP_Widget {	
	function Testimonial_Widget_Bottom() {
		$widget_ops = array('classname' => 'widget_testimonial', 'description' => __( 'Widget Testimonial put in widget home', 'hotel' ) );
		$this->WP_Widget('testimonial', __( 'Widget Testimonial', 'hotel' ), $widget_ops);
	}
	function widget($args, $instance) {
		extract($args, EXTR_SKIP);
		$title = apply_filters( 'widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);
		$sub = apply_filters( 'widget_testimonial', $instance['titlesub'], $instance, $this->id_base );
		echo $before_widget;
		echo '<div class="col">';
		if($title != ""){
		echo $before_title;		
		echo '<h1>'.$title.'</h1>';
		echo $after_title;
		}
		if($sub != "" ){
		echo '<p class="h2">';
		echo $sub;
		echo '</p>';
		}
		
		display_testimonial();
		
		echo '</div>';
		echo $after_widget;
	}

	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['titlesub'] = strip_tags($new_instance['titlesub']);
		
		return $instance;
	}

	function form($instance) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '','titlesub' => '' ) );
		$title = strip_tags($instance['title']);
		$titlesub = strip_tags($instance['titlesub']);
			

		echo '<p><label for="'. $this->get_field_id('title') .'">'.__('Title','hotel').': <input class="widefat" id="'. $this->get_field_id('title') .'" name="'. $this->get_field_name('title') .'" type="text" value="'. esc_attr($title).'" /></label></p>';
		echo '<p><label for="'. $this->get_field_id('titlesub') .'">'.__('Subject','hotel').': <input class="widefat" id="'. $this->get_field_id('titlesub') .'" name="'. $this->get_field_name('titlesub') .'" type="text" value="'. esc_attr($titlesub).'" /></label></p>';
								

	}
}

function display_testimonial(){
				wp_reset_query(); 
				query_posts("post_type='testimonial'&posts_per_page=-1&post_status='publish'");				
					global $post;	
									
					if ( have_posts() ) { 
						
						echo '<ul id="slider1" class="testimonial">';
						while ( have_posts() ) { the_post();	
					
					 echo '<li><p>"'.$post->post_content.'"</p>';
					 echo '<p class="testimonial-author">'.$post->post_title.'</p></li>';
					
					} 
					
					echo '</ul>';
					} 
				
				
				
				?>
				<script type="text/javascript" src="<?php echo TEMPLATE_URL?>/js/jquery.bxSlider.js"></script>
				<script type="text/javascript">
					jQuery(document).ready(function(){						
						jQuery('#slider1').bxSlider({
							mode: 'fade',
							captions: true,
							auto: true,
							controls: false,
							pause:10000
						});
					});
				</script>
				<a name="modal2" href="#dialog_testimonial"><?php _e('Give Testimonial','hotel'); ?></a>			
				
				 <div id="boxes">
				 <form name="form_give_testimonial" id="form_give_testimonial"  action="" method="post">
				<div id="dialog_testimonial" class="window" style="width:0px;height:0px;"> 
					
					<div style="clear:both;"></div>				
					
					<div class="modal-check"> <!-- #modal-check-->					
						<div id="contact-container">
								<div class='contact-top'></div>
								<div class='contact-content'>
									<h2 class='contact-title'><?php _e('Give Testimonial','hotel'); ?>:</h2>
									
									<div class='contact-error' id="contact-error" style="display:none;" ></div>									
										<label for='contact-name'>*<?php _e('Name','hotel'); ?>:</label>
										<input type="text" name="b_name" id="b_name"  class='contact-input'  tabindex='1001' /><br />
										<label for='contact-email'><?php _e('Email','hotel'); ?>:</label>
										<input type="text" name="b_email" id="b_email" class='contact-input'  tabindex='1002' /><br />										
							  			<label for='contact-message'>*<?php _e('Message','hotel'); ?>:</label>
										<textarea id='b_message' class='contact-input' name='b_message' style="" cols='40' rows='10' tabindex='1004'></textarea><br />			
										<label>&nbsp;</label>
										<button type='button' id='b_send' name='b_send' onclick="submit_testimonial_form()" class='contact-send contact-button' tabindex='1004'><?php _e('Send','hotel');?></button>
										<button type='button' id="cancel" class='contact-cancel contact-button simplemodal-close' tabindex='1005'><?php _e('Cancel','hotel'); ?></button>
										<br/>			
									
								</div>
								<div class='contact-bottom'></div>
						</div> 
					</div> <!-- //modal-check-->
				</div> <!-- //dialog-->
				<div id="mask"></div>
				</form>
            </div> <!-- //check room avalible form -->
            <script type="text/javascript">				
			function submit_testimonial_form(){		
				var re = new RegExp("^[a-zA-Z0-9_]+@[a-zA-Z-0-9\\-]+\\.[a-zA-Z0-9\\-\\.]+$");  
				jQuery("#contact-error").html('');
				jQuery("#contact-error").hide();
				if(jQuery('#b_name').val() == ''){
					jQuery("#contact-error").fadeIn(1000);
					jQuery("#contact-error").html('<?php _e('Error: Enter text Your Name, please!', 'hotel');?>');
					jQuery("#b_name").focus();  
				}				
				else if(jQuery('#b_email').val() != '' && !re.test(jQuery('#b_email').val())){
					jQuery("#contact-error").fadeIn(1000);
					jQuery("#contact-error").html('<?php _e('Error: Enter invalid email, please!', 'hotel');?>');
					jQuery("#b_email").focus();  
				}else if(jQuery('#b_message').val() == ''){
					jQuery("#contact-error").fadeIn(1000);
					jQuery("#contact-error").html('<?php _e('Error: Enter text message, please!', 'hotel');?>');
					jQuery("#b_message").focus();  
				}
				else{
					save_action(jQuery('#b_name').val(),jQuery('#b_email').val(),jQuery('#b_message').val() );
				}
			}

			function save_action(name, email, message)
			{
			
			    jQuery.ajax({          
			        type: 'post',
			        url: "<?php echo HOME_URL . '/?do_ajax=ajax_testimonial_process'; ?>", 
			        data: {
			            action: 'ajax_testimonial',
			            name: '' + name,
			            email: '' + email,
			            message: '' + message			                    
			            },			                 
				        success: function(data){					                
				            	jQuery('#mask').hide();
				        		jQuery('.window').hide();
				            	alert("<?php _e('Send successful!','hotel'); ?>"); 	
				           
				          }
			       });
			}		
			
			</script>
            <?php 
			
}

//URL widget RSS
class Text_Box_Widget_RSS extends WP_Widget {
	function Text_Box_Widget_RSS() {
		$widget_ops = array('classname' => 'widget_text_box_RSS', 'description' => __( 'Enter Link RSS put widget social ', 'hotel' ));
		$this->WP_Widget('text_box_RSS', __('URL RSS','hotel'), $widget_ops);
	}
	function widget($args, $instance) {
		extract($args, EXTR_SKIP);
		$title = apply_filters( 'widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);
		echo $before_title;				
		echo $after_title;
		echo $before_widget;
		echo '<div class="rss">';
		echo '<a href="';
		if($title != "" ){		
		echo $title;		
		}
		echo '">';	
		
		echo '    </a></div>';		
		echo $after_widget;
	}

	function update($new_instance, $old_instance) {
		$instance = $old_instance;		
		$instance['title'] = strip_tags($new_instance['title']);		
		return $instance;
	}

	function form($instance) {
		$instance = wp_parse_args( (array) $instance, array('title' => '' ) );		
		$title = strip_tags($instance['title']);			
		echo '<p><label for="'. $this->get_field_id('title') .'">'.__('URL RSS','hotel').': <input class="widefat" id="'. $this->get_field_id('title') .'" name="'. $this->get_field_name('title') .'" type="text" value="'. esc_attr($title).'" /></label></p>';
		
	}
}

//URL widget TWITTER
class Text_Box_Widget_TWITTER extends WP_Widget {
	function Text_Box_Widget_TWITTER() {
		$widget_ops = array('classname' => 'widget_text_box_TWITTER', 'description' => __('Enter Link TWITTER put widget social','hotel') );
		$this->WP_Widget('text_box_TWITTER', __('URL TWITTER','hotel'), $widget_ops);
	}
	function widget($args, $instance) {
		extract($args, EXTR_SKIP);
		$title = apply_filters( 'widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);
		echo $before_title;				
		echo $after_title;
		echo $before_widget;
		echo '<div class="follow-sidebar">';
		echo '<a href="';
		if($title != ""){		
		echo $title;		
		}
		echo '">';
		
		echo '    </a></div>';		
		echo $after_widget;
	}

	function update($new_instance, $old_instance) {
		$instance = $old_instance;		
		$instance['title'] = strip_tags($new_instance['title']);		
		return $instance;
	}

	function form($instance) {
		$instance = wp_parse_args( (array) $instance, array('title' => '' ) );		
		$title = strip_tags($instance['title']);			
		echo '<p><label for="'. $this->get_field_id('title') .'">'.__('URL TWITTER','hotel').': <input class="widefat" id="'. $this->get_field_id('title') .'" name="'. $this->get_field_name('title') .'" type="text" value="'. esc_attr($title).'" /></label></p>';
		
	}
}

//URL widget FaceBook
class Text_Box_Widget_Facebook extends WP_Widget {
	function Text_Box_Widget_Facebook() {
		$widget_ops = array('classname' => 'widget_text_box_Facebook', 'description' => __('Enter Link Facebook put widget social','hotel') );
		$this->WP_Widget('text_box_Facebook', __('URL Facebook','hotel'), $widget_ops);
	}
	function widget($args, $instance) {
		extract($args, EXTR_SKIP);
		$title = apply_filters( 'widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);
		echo $before_title;				
		echo $after_title;
		echo $before_widget;
		echo '<div class="facebook">';
		echo '<a href="';
		if($title != "" ){		
		echo $title;		
		}
		echo '">';
		
		echo '    </a></div>';		
		echo $after_widget;
	}

	function update($new_instance, $old_instance) {
		$instance = $old_instance;		
		$instance['title'] = strip_tags($new_instance['title']);		
		return $instance;
	}

	function form($instance) {
		$instance = wp_parse_args( (array) $instance, array('title' => '' ) );		
		$title = strip_tags($instance['title']);			
		echo '<p><label for="'. $this->get_field_id('title') .'">'.__('URL Facebook','hotel').': <input class="widefat" id="'. $this->get_field_id('title') .'" name="'. $this->get_field_name('title') .'" type="text" value="'. esc_attr($title).'" /></label></p>';
		
	}
}


function hotel_widgets_init() {	
	
	register_sidebar( array(
		'name' => __( 'Widget Home Area', 'hotel' ),
		'id' => 'bottom-widget-area',
		'description' => __( 'The widget home area', 'hotel'),
		'before_widget' => '',
		'after_widget' => ''
	) );

	register_sidebar( array(
		'name' => __( 'Social Widget Area', 'hotel' ),
		'id' => 'sidebar_right_top',
		'description' => __( 'The widget social area', 'hotel'),
		'before_widget' => '',
		'after_widget' => '',
		'before_title' => '',
		'after_title' => ''
	) );
	register_sidebar( array(
		'name' => __( 'Right Sidebar Widget Area', 'hotel' ),
		'id' => 'sidebar_right_content',
		'description' => __( 'The right sidebar widget area', 'hotel'),
		'before_widget' => '',
		'after_widget' => '',
		'before_title' => '<p class="sidebar-title">',
		'after_title' => '</p>'
	) );
	
	register_widget('Text_Box_Widget_RSS');
	register_widget('Text_Box_Widget_TWITTER');
	register_widget('Text_Box_Widget_Facebook');
	register_widget('Text_Box_Widget_Bottom');
	register_widget('Testimonial_Widget_Bottom');		
	//register_sidebar_widget(__('Footer default','jobpress'), 'footer_default');
	
}
add_action( 'widgets_init', 'hotel_widgets_init' );


function my_custom_init() 
{ 
$labels = array(
    'name' => _x('Room Type', 'Post type general name'),
    'singular_name' => _x('Room Type', 'Post type singular name'),
    'add_new' => _x('Add New', 'Room'),
    'add_new_item' => __('Add New Room Type'),
    'edit_item' => __('Edit room type'),
    'new_item' => __('New room type'),
    'view_item' => __('View room type'),
    'search_items' => __('Search room type'),
    'not_found' =>  __('No room type found'),
    'not_found_in_trash' => __('No room type found in Trash'), 
    'parent_item_colon' => ''    ,	 
  ); 
  $args = array(
    'labels' =>  $labels,
    'public' => true,
    'publicly_queryable' => true,
    'show_ui' => true,	
    'query_var' => true,
    'rewrite' => true,    
    'capability_type' => 'post',
    'hierarchical' => true,
    'menu_position' => 4,  	
    'supports' => array('title','editor','thumbnail'),
	 'menu_icon' => TEMPLATE_URL . '/images/settings/hotel_icon.png' 
  	
  ); 
  register_post_type('roomtype',$args);

  $labels = array(
    'name' => _x('Testimonial', 'Post type general name'),
    'singular_name' => _x('Testimonial', 'Post type singular name'),
    'add_new' => _x('Add New', 'testimonial'),
    'add_new_item' => __('Add New testimonial'),
    'edit_item' => __('Edit testimonial'),
    'new_item' => __('New testimonial'),
    'view_item' => __('View testimonial'),
    'search_items' => __('Search testimonial'),
    'not_found' =>  __('No testimonial found'),
    'not_found_in_trash' => __('No testimonial found in Trash'), 
    'parent_item_colon' => ''    
  ); 
  $args = array(
    'labels' =>  $labels,
    'public' => true,
    'publicly_queryable' => true,
    'show_ui' => false, 
    'query_var' => true,
    'rewrite' => true,    
    'capability_type' => 'post',
    'hierarchical' => true,
    'menu_position' => null,  	
    'supports' => array('title','editor')  	
  ); 
  register_post_type('testimonial',$args);   
  
  /*$labels = array(
    'name' => _x('Local Events', 'Post type general name'),
    'singular_name' => _x('Local Event', 'Post type singular name'),
    'add_new' => _x('Add New', 'event'),
    'add_new_item' => __('Add New Local Event'),
    'edit_item' => __('Edit Event'),
    'new_item' => __('New Event'),
    'view_item' => __('View Event'),
    'search_items' => __('Search Event'),
    'not_found' =>  __('No local event has found'),
    'not_found_in_trash' => __('No local event has found in Trash'), 
    'parent_item_colon' => ''    
  ); 
  $args = array(
    'labels' =>  $labels,
    'public' => true,
    'publicly_queryable' => true,
    'show_ui' => true, 
    'query_var' => true,
    'rewrite' => true,    
    'capability_type' => 'post',
    'hierarchical' => true,
    'menu_position' => 5,  	
    'supports' => array('title','editor')  	
  ); 
  register_post_type('event',$args);   */
  
  flush_rewrite_rules();
  
  /*--------- Switch language---------*/
	if ( !isset( $_COOKIE['default_lang'] ) )
	{
		//if ( false != get_option ('tgt_default_language')  )
			$_COOKIE['default_lang'] = get_option ('tgt_default_language');
		//else 
	}
	
	// action change language
	if ( isset( $_GET['switch_lang'] ) )
	{
		tgt_change_hotel_lang($_GET['switch_lang']);
	}
	
	// get current language from WPML
	global $sitepress;
	$langs = get_option ('tgt_wpml');
	if ( method_exists( $sitepress, 'get_current_language' ) && 
		get_option('tgt_using_wpml') &&
		$_COOKIE['default_lang'] != $sitepress->get_current_language() )
	{
		$disp_lang = $sitepress->get_current_language();
		load_textdomain ('hotel', TEMPLATEPATH . "/lang/". $langs[$disp_lang] . '.mo');		
	}
	else
	{		
		load_textdomain ('hotel', TEMPLATEPATH . "/lang/". $_COOKIE['default_lang'] . '.mo');
	}
	
	// load language	
	//load_textdomain ('hotel', TEMPLATEPATH . "/lang/". $langs[$_COOKIE['default_lang']] . '.mo');
	//var_dump( $_COOKIE['default_lang'] );
	
	/*--------- SCRIPT register ---------*/
	wp_register_script( 'hotel-jquery'			, TEMPLATE_URL . '/js/jquery.js', null, '1.5.8' );
	wp_register_script( 'jquery-ui-datepicker', TEMPLATE_URL . '/js/jquery.ui.datepicker.js', null, '1.0' );
	wp_register_script( 'jquery-ui-calendar'	, TEMPLATE_URL . '/js/jquery.ui.calendar.js', null, '1.0' );
	wp_register_script( 'fullcalendar'			, TEMPLATE_URL . '/js/fullcalendar.js', null, '1.0' );
	wp_register_script( 'admin-script'			, TEMPLATE_URL . '/js/admin.script.js', null, '1.0' );
	wp_register_style( 'humanity-jquery-ui' 	, TEMPLATE_URL . '/css/humanity/jquery-ui.css', false , '1.0.0' );
	wp_register_style( 'style-admin' 			, TEMPLATE_URL . '/css/style-admin.css' , false , '1.0.0' );
	wp_register_style( 'fullcalendar' 			, TEMPLATE_URL . '/css/fullcalendar.css' , false , '1.0.0' );
}
add_action('init', 'my_custom_init');

/*================================================================================================*/
add_action("admin_init",  'custom_meta_box' );
add_action('save_post', 'save_meta_box');
function custom_meta_box()
{
	add_meta_box("roomtype-detail-information", __('Roomtype Detail','hotel'),  "display_detail_meta_box", "roomtype", "normal", "high");
	add_meta_box("roomtype-custom-fields", __('Roomtype information ','hotel'),  "display_meta_box", "roomtype", "normal", "high");
	add_meta_box("roomtype-services", __('Services ','hotel'),  "display_services", "roomtype", "side", "low");
	//add_meta_box( "event-detail", __('Event Detail', 'hotel'), "display_detail_meta_box", "event", "normal", "high");
	//add_meta_box( "event-info", $title, $callback, $page)
}

	
function display_detail_meta_box($post)
	{
		$post_id = $post->ID;	
		if ( $post->post_type == 'roomtype')
		{
		?>
		<div class="hotel_meta_box">			
			<table width="100%">			
				<tr>
					<td width="30%" align="right"></td>
					<td><input type="checkbox" name="use_tax_roomtype" id="use_tax_roomtype"  <?php echo (get_post_meta($post_id, META_ROOMTYPE_USE_TAX,true) == 'yes')?'Checked':''; ?> /> <label for=""><?php  _e( 'Has Tax' , 'hotel' ) ?></label></td>
				</tr>			
				<tr>
					<td width="30%" align="right"><label for=""><?php  _e( 'Capability' , 'hotel' ) ?> : </label></td>				
					<td>
					<select id = "capability_roomtype" name = "capability_roomtype" onchange="cap_price()">
						<option value='1' <?php echo (get_post_meta($post_id, META_ROOMTYPE_CAPABILITY, true) == 1)?'selected="selected"':'';  ?>><?php _e('1','hotel'); ?></option>
						<option value='2' <?php echo (get_post_meta($post_id, META_ROOMTYPE_CAPABILITY, true) == 2)?'selected="selected"':'';  ?>><?php _e('2','hotel'); ?></option>
						<option value='3' <?php echo (get_post_meta($post_id, META_ROOMTYPE_CAPABILITY, true) == 3)?'selected="selected"':'';  ?>><?php _e('3','hotel'); ?></option>
						<option value='4' <?php echo (get_post_meta($post_id, META_ROOMTYPE_CAPABILITY, true) == 4)?'selected="selected"':'';  ?>><?php _e('4','hotel'); ?></option>
						<option value='5' <?php echo (get_post_meta($post_id, META_ROOMTYPE_CAPABILITY, true) == 5)?'selected="selected"':'';  ?>><?php _e('5','hotel'); ?></option>
						<option value='6' <?php echo (get_post_meta($post_id, META_ROOMTYPE_CAPABILITY, true) == 6)?'selected="selected"':'';  ?>><?php _e('6','hotel'); ?></option>
						<option value='7' <?php echo (get_post_meta($post_id, META_ROOMTYPE_CAPABILITY, true) == 7)?'selected="selected"':'';  ?>><?php _e('7','hotel'); ?></option>
						<option value='8' <?php echo (get_post_meta($post_id, META_ROOMTYPE_CAPABILITY, true) == 8)?'selected="selected"':'';  ?>><?php _e('8','hotel'); ?></option>
						<option value='9' <?php echo (get_post_meta($post_id, META_ROOMTYPE_CAPABILITY, true) == 9)?'selected="selected"':'';  ?>><?php _e('9','hotel'); ?></option>
						<option value='10' <?php echo (get_post_meta($post_id, META_ROOMTYPE_CAPABILITY, true) == 10)?'selected="selected"':'';  ?>><?php _e('10','hotel'); ?></option>					
					</select>				
					</td>
				
				</tr>				
			</table>
			<table width="100%" id = "display_cap_price">
				<?php if(get_post_meta($post_id, META_ROOMTYPE_CAPABILITY, true) > 0){ 
					$arr_price = get_post_meta($post_id, META_ROOMTYPE_CAP_PRICE,true);
					for ($i = 1; $i <= get_post_meta($post_id, META_ROOMTYPE_CAPABILITY, true) ; $i++) {
						$selected = '';
						if ( is_array($arr_price) && $arr_price[$i]['selected'] == $i ){
								$selected = 'checked ="checked"';
						}
						$price_room = 0;
						if (is_array($arr_price) && !empty($arr_price) ){
							$price_room =  $arr_price[$i]['price'];
						}
						echo '<tr>';
								echo '<td width="30%" align="right"><label for="price">'.__( 'Price for' , 'hotel' ).' '. $i .' '. __( 'person' , 'hotel') .': </label></td>';
								echo '<td align="left"> <input type="text" name="roomtype_cap_price_'.$i.'" value="'.$price_room.'" onkeypress="return EnterNumber(event)" /><input  type="radio" name="select_price" style="font-size: 14px" value="'.$i.'" '.$selected.' /> <label for="default_price">' . __('Use as default price', 'hotel') . '</label></td>';
								
							echo '</tr>';
					}
				}else{
					echo '<tr>';
								echo '<td width="30%" align="right"><label for="price">'.__( 'Price for' , 'hotel' ).' '. __('1','hotel') .' '. __( 'person' , 'hotel') .': </label></td>';
								echo '<td align="left"> <input type="text" name="roomtype_cap_price_1" value="" onkeypress="return EnterNumber(event)" /><input  type="radio" name="select_price" style="font-size: 14px" value="1" checked ="checked"  /> <label for="default_price">' . __('Use as default price', 'hotel') . '</label> </td>';
								
							echo '</tr>';
				}
			
				?>
			</table>
		</div>
		<script type="text/javascript">	
			function EnterNumber(e)
			{
				var keynum;
				var keychar;
				if(window.event) // IE
					{
					keynum = e.keyCode;
					}
				else if(e.which) // Netscape/Firefox/Opera
					{
					keynum = e.which;
					}			
				if(keynum == 8){
					var numcheck = new RegExp("^[^a-z^A-Z]");			
				}else{
					var numcheck = new RegExp("^[0-9.,]");
				}
				
				keychar = String.fromCharCode(keynum);	
				return numcheck.test(keychar);
				
			}
			function cap_price(){   
		    	 
				if (jQuery('#capability_roomtype').val() == ''){					
				
				}else{
				
					var num = parseInt( jQuery('#capability_roomtype').val() );
						field = '',
						count = jQuery('#display_cap_price').find('tr').length;
					
					if ( num > count  )
					{
						for( i = 1 ; i <= num - count; i++ )
						{
							index = i + count;
							field += '<tr>';
							field += '<td width="30%" align="right"><label for="price"><?php _e('Price for','hotel') ?> ' + index + ' <?php _e('Person') ?></label></td>';
							field += '<td align="left">' +
										'<input type="text" onkeypress="return EnterNumber(event)" name="roomtype_cap_price_' + index + '" />'+
										'<input type="radio" name="select_price" value="' + index + '"/><label for="default_price"> <?php _e('Use as default Price') ?></label>' +
										'</td>';
							field += '</tr>';
						}
						if ( field != '' )
							jQuery('#display_cap_price').append(field);
					}
					else if( num < count  )
					{
						for( i = 1 ; i <= (count - num); i++)
						{
							jQuery('#display_cap_price').find('tr').last().remove();
						}
					}
					//display_cap_price(jQuery('#capability_roomtype').val(),jQuery('#price_roomtype').val());				
				
				}
			}
			function display_cap_price(capability_roomtype, price_roomtype){				
				jQuery.ajax({
					type: 'post',
					url: "<?php echo HOME_URL.'/?do_ajax=ajax_capability_price_process'; ?>",
					data: {
						action: 'ajax_capability_price',
						capability_roomtype: '' + capability_roomtype,
						price_roomtype: '' + price_roomtype
						},						
						success: function(data){							
							jQuery('#display_cap_price').html(data.content);
						}	
					
				});
				
				
				
			}
			
		</script>
		<?php 
		}
		/*if ( $post->post_type == 'event')
		{*/ ?>
		
		<!--<link href="<?php echo TEMPLATE_URL; ?>/css/humanity/jquery-ui.css" type="text/css" rel="stylesheet"></link>	
		<script src="<?php echo TEMPLATE_URL; ?>/js/jquery.js" type="text/javascript"></script>	
		<script src="<?php echo TEMPLATE_URL; ?>/js/jquery.ui.datepicker.js" type="text/javascript"></script>
		<script type="text/javascript">
			$(document).ready(function(){
				$('.start-date-pick').datepicker({minDate: new Date() } );
				});
		</script>
		<script type="text/javascript">	   
			function Number(e)
			{
				var keynum;
				var keychar;
				if(window.event) // IE
					{
					keynum = e.keyCode;
					}
				else if(e.which) // Netscape/Firefox/Opera
					{
					keynum = e.which;
					}
				if(keynum == 8)
					var numcheck = new RegExp("^[^a-z^A-Z]");
				else
					var numcheck = new RegExp("^[0-9+-_ ]");
				keychar = String.fromCharCode(keynum);	
				return numcheck.test(keychar);
			}
		</script>
		<table width="100%">
		-->
		<?php 
			/*$sta = get_post_meta($post_id, META_EVENT_START);
			if ( ! empty( $sta) ){
				$start = strtotime($sta[0]);
				$start_date = date(get_option('date_format'), $start);
				if( strpos($sta[0], ':') !== false){
					$start_hour = date(get_option('time_format'), $start);
				}
			}
			
			$end = get_post_meta($post_id, META_EVENT_END);
			if( ! empty ( $end ) ) {
				$end_t = strtotime($end[0]);
				$end_date = date(get_option('date_format'), $end_t);
				if( strpos($end[0], ':') !== false){
					$end_hour = date(get_option('time_format'), $end_t);
				}
			}
			
			$locate = get_post_meta($post_id, META_EVENT_LOCATION);*/
			?>
			<!--<tr>
				<td align="right"><?php  _e( 'Start Date' , 'hotel' ) ?> : </td>
				<td> 
				   <input type="text" readonly="readonly" id="start-date" class="check start-date-pick" name="start-date" style="width: 100%; font-size: 17px" value="<?php if(isset($start_date) && $start_date != '') echo $start_date; else echo ''; ?>"  />	                </td>
				<td align="right"><?php  _e( 'At Time' , 'hotel' ) ?> : </td>
				<td>
					 <input type="text" name="start_event_hour" style="width: 200px; font-size: 17px" value="<?php if(isset($start_hour) && $start_hour != '') echo $start_hour; ?>" onkeypress="return Number(event)"/> 				
				</td>
            </tr>
			<tr>
				<td align="right"><?php  _e( 'End Date' , 'hotel' ) ?> : </td>
				<td>
					 <input type="text" readonly="readonly" id="end-date" class="check start-date-pick" name="end-date" style="width: 100%; font-size: 17px" value="<?php if(isset($end_date) && $end_date != '') echo $end_date; else echo ''; ?>" /> 								</td>
				<td align="right"><?php  _e( 'At Time' , 'hotel' ) ?> : </td>
				<td>
					 <input type="text" name="end_event_hour" style="width: 200px; font-size: 17px" value="<?php if(isset($end_hour) && $end_hour != '') echo $end_hour;  ?>" onkeypress="return Number(event)"/> 				
				</td>
			</tr>
			<tr>
				<td align="right"><?php  _e( 'Location' , 'hotel' ) ?> : </td>
				<td colspan="3"><input type="text" name="location" style="width: 100%; font-size: 17px" value="<?php if(isset($locate[0]) && $locate[0] != '') echo $locate[0];  ?>"/> 	</td>
			</tr>
					
		</table>

	--><?php //} 

	}

	
	function display_meta_box($post)
	{
		$field = Fields::getInstance();
		$field_list = $field->getActivatedFields();
		$post_id = $post->ID;
		?>
		
		<table width="100%">
			<?php foreach ($field_list as $key => $fields ){ ?>
			
				<?php if ($fields['field_type'] == FIELD_TYPE_TEXT){ ?>
				<tr>
					<td width="30%" align="right"><?php echo $fields['field_name'] ?> : </td>
					<td> <input type="text" name="<?php echo META_ROOMTYPE_FIELD.$key ?>" style="width: 200px; font-size: 17px" value="<?php echo get_post_meta($post_id, META_ROOMTYPE_FIELD.$key,true); ?>"/> </td>
				</tr>			
				<?php } ?>	
				<?php if ($fields['field_type'] == FIELD_TYPE_LONGTEXT){ ?>
				<tr>
					<td width="30%" align="right"><?php echo $fields['field_name'] ?> : </td>
					<td> <textarea rows="5" name="<?php echo META_ROOMTYPE_FIELD.$key ?>"  cols="30"><?php echo get_post_meta($post_id, META_ROOMTYPE_FIELD.$key,true); ?></textarea> </td>
				</tr>			
				<?php } ?>
				
				<?php if ($fields['field_type'] == FIELD_TYPE_DROPBOX){ 
				$str_option = explode(',', $fields['field_options']);
					?>
				<tr>
					<td width="30%" align="right"><?php echo $fields['field_name'] ?> : </td>
					<td> 
					<select  name="<?php echo META_ROOMTYPE_FIELD.$key ?>" >
                            
                                                        	<option value=""><?php _e('Select','hotel'); ?></option>
                                                            <?php  
                                                   			 foreach ($str_option as $option_field){
																echo '<option value="'.$option_field.'"';																	
																		if(get_post_meta($post_id, META_ROOMTYPE_FIELD.$key,true)== $option_field){																		
																			echo 'selected ="selected" ';
																		}															
																echo '>'.$option_field.'</option>';
                                                   			 }
                                                            ?>                                                      	                                                          
                                                         
                                                    	</select>   
					
					</td>
				</tr>			
				<?php } ?>
				<?php if ($fields['field_type'] == FIELD_TYPE_CHECKBOX){ ?>
				<tr>
					<td width="30%" align="right"></td>
					<td> <input type="checkbox" name="<?php echo META_ROOMTYPE_FIELD.$key ?>" <?php echo (get_post_meta($post_id, META_ROOMTYPE_FIELD.$key,true) == 'yes')?'Checked':''; ?> /> <?php echo $fields['field_name'] ?></td>
				</tr>			
				<?php } ?>
				
			
			<?php } ?>	
		</table>
		
		<?php
	}
	
	function display_services($post)
	{
		$services = options::getInstance();
		$service_list = $services->getOptions();
		$post_id = $post->ID;		
		$arr = array();
		$arr = get_post_meta($post_id, META_ROOMTYPE_SERVICES,true);		
		?>
		
		<table width="100%">
			
				<?php
				if (isset($service_list) && is_array($service_list)){	
				foreach ($service_list as $k => $v) { ?>
					<tr> 
						<td align="right"><input type="checkbox" name="cbservices[]" id="cbservices[]"  value="<?php echo $k; ?>" style="font-size: 17px" <?php echo (isset($arr[$k]))?'checked="checked"':''; ?> /></td>
						<td> <?php echo $v['name']; ?> <input type="hidden" name="name_service_<?php echo $k; ?>" style="width:90px; font-size: 13px" value="<?php echo $v['name']; ?>" "/> </td>
						<td> <input type="text" name="price_service_<?php echo $k; ?>" style="width:90px; font-size: 13px" value="<?php echo (isset($arr[$k])) ? $arr[$k]['price'] : $v['default_price']; ?>"  onkeypress="return EnterNumber(event)"/></td>
					</tr>
				<?php } } ?>				
				
		</table>
		
		<?php
	}
	
	

function save_meta_box($post_id)
	{
		global $wpdb, $post;
		$field = Fields::getInstance();
		$field_list = $field->getActivatedFields();
		if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) 
		   return $post_id;
		
		// get attachment
		// when user submit roomtype, set their first image as a feature image
		if ( isset($_POST['post_type']) && $_POST['post_type'] == 'roomtype')
		{
			$attachments = array();
			if ( !has_post_thumbnail( $post_id ) )
			{
				$attachments = &get_children( 'post_type=attachment&post_mime_type=image&post_parent='.$post_id.'&numberposts=1&order=DESC&orderby=ID' );
				//set thumbnail
				if ( !empty( $attachments ) )
				{
					$attach = reset($attachments);
					set_post_thumbnail( $post_id, $attach->ID );
				}
			}
			
			if (!is_admin())
				return $post_id;	
			
			if ( empty($_POST) || empty( $_POST['post_type'] ) || $_POST['post_type'] != 'roomtype' )
				return $post_id;				
					// add meta capability and price
					if (isset($_POST['capability_roomtype'])){
						update_post_meta($post_id, META_ROOMTYPE_CAPABILITY,$_POST['capability_roomtype'] );
						$arr_prices = array();
						for ($i = 1; $i <=$_POST['capability_roomtype'] ; $i++) {
							$arr_prices[$i]['price'] = $_POST['roomtype_cap_price_'.$i];
							if(isset($_POST['select_price']) && $_POST['select_price'] == $i){
								$arr_prices[$i]['selected'] = $_POST['select_price'];
								update_post_meta($post_id, META_ROOMTYPE_PRICE,$_POST['roomtype_cap_price_'.$i]);
							}else{
								$arr_prices[$i]['selected'] = '';
							}
						}
						update_post_meta($post_id, META_ROOMTYPE_CAP_PRICE,$arr_prices );
					}

					// add meta use tax
					
					if (isset($_POST['use_tax_roomtype'])){
						update_post_meta($post_id, META_ROOMTYPE_USE_TAX,'yes');
					}else {
						update_post_meta($post_id, META_ROOMTYPE_USE_TAX,'no');
					}
					
					// add meta services
					$cbservices_id = array();
					if(isset($_POST['cbservices'])){
						$cbservices_id= $_POST['cbservices'];	
					}					
					
					$arr_services = array();
					for($i=0;$i<count($cbservices_id);$i++){
						$arr_services[$cbservices_id[$i]]['name'] = $_POST['name_service_'.$cbservices_id[$i]];
						$arr_services[$cbservices_id[$i]]['price'] = $_POST['price_service_'.$cbservices_id[$i]];		
					}									
					update_post_meta($post_id, META_ROOMTYPE_SERVICES, $arr_services);
					
					
		
					foreach ($field_list as $key => $fields){
						if ($fields['field_type'] != FIELD_TYPE_CHECKBOX){
							if (isset($_POST[META_ROOMTYPE_FIELD.$key])){
								update_post_meta($post_id, META_ROOMTYPE_FIELD.$key, $_POST[META_ROOMTYPE_FIELD.$key]);
							}
						}else {
							if (isset($_POST[META_ROOMTYPE_FIELD.$key])){
								update_post_meta($post_id, META_ROOMTYPE_FIELD.$key, 'yes');
							}else {
								update_post_meta($post_id, META_ROOMTYPE_FIELD.$key, 'no');
							}
						}
						
					}
		}
		/*if ( isset($_POST['post_type']) && $_POST['post_type'] == 'event')
		{
			//prepare save start time
			$start = ($_POST['start-date'])? ($_POST['start-date']) : '';
			$sta_time = $_POST['start_event_hour'];
			if( strpos( $sta_time, ':') !== false ){
				$start .= ($sta_time)? ($sta_time) : '';
			}
			if( strpos( $sta_time, ':') === false && $sta_time != ''){
				$start .= $sta_time.':00';
			}
			//prepare save end time
			$end  =  ($_POST['end-date']) ? $_POST['end-date'] : '';
			$end_times = $_POST['end_event_hour'];
			if(strpos($end_times, ':') !== false) {
				$end .= ($end_times)? $end_times : '';
			}
			if( strpos( $end_times, ':') === false && $end_times != ''){
				$end .= $end_times.':00';
			}
			//prepare save location
			$location = $_POST['location'];
			
			if ( empty($_POST) || empty( $_POST['post_type'] ) || $_POST['post_type'] != 'event' )
				return $post_id;				
		
			if (isset($_POST['start-date']) && $_POST['start-date'] != ''){
				update_post_meta($post_id, META_EVENT_START, $start );
			}
			if (isset($_POST['end-date']) && $_POST['end-date'] != ''){
				update_post_meta($post_id, META_EVENT_END, $end);
			}
			if( isset( $_POST['location'])) {
				update_post_meta($post_id, META_EVENT_LOCATION, $location);
			}
		}*/
		
}
/*=============================================================================================================*/
// Custom manager roomtype --------------------------------------------------------------------
if ( !function_exists( 'array_insert' ) ) {
function array_insert(&$array, $insert, $position) {
settype($array, "array");
settype($insert, "array");
settype($position, "int");

if($position==0) {
    $array = array_merge($insert, $array);
} else {
    if($position >= (count($array)-1)) {
        $array = array_merge($array, $insert);
    } else {
        $head = array_slice($array, 0, $position);
        $tail = array_slice($array, $position);
        $array = array_merge($head, $insert, $tail);
    }
}
}}

add_filter( 'manage_posts_columns', 'hotel_add_roomtype_column');
add_action( 'manage_posts_custom_column', 'hotel_add_roomtype_value',10, 2);

if ( !function_exists( 'hotel_add_roomtype_column' ) ) {
function hotel_add_roomtype_column($cols) {
	if(isset($_GET['post_type']) && $_GET['post_type'] =='roomtype'){
	$args_col = array(
	'col_capability' => '<a href="javascript:"><span>'.__('Capability').'</span></a>',
	//'col_author' => '<a href="javascript:"><span>'.__('Author').'</span></a>',
	'col_price' => '<a href="javascript:"><span>'.__('Price').'</span></a>'	
	);		
	array_insert($cols, $args_col, 2);		
	 
	}
		return $cols;
    
}}
if ( !function_exists( 'hotel_add_roomtype_value' ) ) {
function hotel_add_roomtype_value($column_name, $post_id) {
	if(isset($_GET['post_type']) && $_GET['post_type'] =='roomtype'){				
	global $post;
		if($column_name == 'col_capability' ){
			if(get_post_meta($post->ID, META_ROOMTYPE_CAPABILITY ,true) == ''){
				echo '0';
			}else{
				echo get_post_meta($post->ID, META_ROOMTYPE_CAPABILITY,true);
			}			
		}
		if($column_name == 'col_price' ){
			
			if(get_post_meta($post_id, META_ROOMTYPE_CAPABILITY, true) > 0){ 
					$arr_price = get_post_meta($post_id, META_ROOMTYPE_CAP_PRICE,true);
					for ($i = get_post_meta($post_id, META_ROOMTYPE_CAPABILITY, true); $i >0 ; $i--) {
						
						if ( is_array($arr_price) && $arr_price[$i]['selected'] == $i ){
							if ($arr_price[$i]['price'] != ''){
								echo $arr_price[$i]['price'];
							}else {
								echo '0';
							}
						}
						
					}
			}		
			
		}
		/*if($column_name == 'col_author' ){
			$userdata = get_userdata($post->post_author);
				  echo $userdata->user_login;
		}*/
	}			
		
}}
add_filter( 'manage_pages_columns', 'hotel_add_roomtype_column');
add_action( 'manage_pages_custom_column', 'hotel_add_roomtype_value',10, 2);

// End Custom manager roomtype ***********************************

/*=============================================================================================================*/
// Local Event meta post Display --------------------------------------------------------------------
/*add_filter( 'manage_posts_columns', 'hotel_add_local_event_column');
add_action( 'manage_posts_custom_column', 'hotel_add_local_event_value',10, 2);

if ( !function_exists( 'hotel_add_local_event_column' ) ) {
function hotel_add_local_event_column($cols) {
	if(isset($_GET['post_type']) && $_GET['post_type'] =='event'){
	$args_col = array(
	'col_start' => '<a href="javascript:"><span>'.__('Start date', 'hotel').'</span></a>',
	'col_end' => '<a href="javascript:"><span>'.__('End date', 'hotel').'</span></a>',
	'col_location' => '<a href="javascript:"><span>'.__('Location', 'hotel').'</span></a>'	
	);		
	array_insert($cols, $args_col, 2);		
	 
	}
		return $cols;
}}
if ( !function_exists( 'hotel_add_local_event_value' ) ) {
function hotel_add_local_event_value($column_name, $post_id) {
	if(isset($_GET['post_type']) && $_GET['post_type'] =='event'){				
	global $post;
	$sta = get_post_meta($post_id, META_EVENT_START);
			if ( ! empty( $sta) ){
				$start = strtotime($sta[0]);
				$start_date = date(get_option('date_format'), $start);
				if( strpos($sta[0], ':') !== false){
					$start_hour = date(get_option('time_format'), $start);
				}
			}
			
			$end = get_post_meta($post_id, META_EVENT_END);
			if( ! empty ( $end ) ) {
				$end_t = strtotime($end[0]);
				$end_date = date(get_option('date_format'), $end_t);
				if( strpos($end[0], ':') !== false){
					$end_hour = date(get_option('time_format'), $end_t);
				}
			}
			
			$locate = get_post_meta($post_id, META_EVENT_LOCATION);
			
		if($column_name == 'col_start' ){
			if(get_post_meta($post->ID, META_EVENT_START ,true) != ''){
				$start_dates = $start_date;
				if( isset($start_hour) && $start_hour != '' ){ 
					$start_dates .= __(' at ', 'hotel').$start_hour;
				}
				echo $start_dates;
			}			
		}
		if($column_name == 'col_end' ){
			if(get_post_meta($post->ID, META_EVENT_END ,true) != ''){
				$end_dates = $end_date;
				if( isset($end_hour) && $end_hour != '' ){ 
					$end_dates .= __(' at ', 'hotel').$end_hour;
				}
				echo $end_dates;
			}	
		}
		if($column_name == 'col_location' ){
			if(get_post_meta($post->ID, META_EVENT_LOCATION ,true) != ''){
				echo $locate[0];
			}	
		}
	}			
		
}}
add_filter( 'manage_pages_columns', 'hotel_add_local_event_column');
add_action( 'manage_pages_custom_column', 'hotel_add_local_event_value',10, 2);
*/
// End local event display---------------------------------------------------
?>