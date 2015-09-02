<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />

<link href="<?php bloginfo('template_directory')?>/favicon.ico" rel="shortcut icon" type="image/x-icon" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

<link href="<?php echo TEMPLATE_URL;?>/style.css" type="text/css" rel="stylesheet" media="all" />
<link href="<?php echo TEMPLATE_URL;?>/css/master.css" type="text/css" rel="stylesheet" media="all" />
<link href="<?php echo TEMPLATE_URL;?>/css/jquery.lightbox.css" type="text/css" rel="stylesheet" media="all" />  
<link href="<?php echo TEMPLATE_URL;?>/css/humanity/jquery-ui.css" type="text/css" rel="stylesheet" media="all" />
<script type="text/javascript" src="<?php echo TEMPLATE_URL?>/js/jquery.js"></script> 
<script type="text/javascript" src="<?php echo TEMPLATE_URL?>/js/jquery.ui.datepicker.js"></script> 
<script type="text/javascript" src="<?php echo TEMPLATE_URL?>/js/cufon-yui.js"></script>
<script type="text/javascript" src="<?php echo TEMPLATE_URL?>/js/neue.js"></script>
<script type="text/javascript" src="<?php echo get_option('tgt_custom_script');?>"></script>
	<script type="text/javascript"> 
		Cufon.replace('h1', { fontFamily: 'Neue' });
	</script>
<script type="text/javascript">
	var img_link1 = "<?php echo TEMPLATE_URL.'/';?>";
</script>				
<script src="<?php echo TEMPLATE_URL;?>/js/jquery.lightbox.js" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo TEMPLATE_URL?>/js/js.js"></script> 
<script type="text/javascript" src="<?php echo TEMPLATE_URL?>/js/modal-js.js"></script>
<?php 
add_action('wp_footer', 'run_script');
function run_script(){ ?>
	<script src="<?php echo TEMPLATE_URL;?>/js/validation.js" type="text/javascript"></script>	
	<script type="text/javascript" src="<?php echo TEMPLATE_URL?>/js/fade_image_gallery.js"></script>	
	<?php //tgt_include_index_js() ?>
	
	<script type="text/javascript">
		<?php  
			$image = get_option('tgt_image_slider');
			if($image !=null)
			{			
		?>
		jQuery('#image_2').attr("src","<?php echo TEMPLATE_URL.$image['i_2'];?>"); 
		jQuery('#image_3').attr("src","<?php echo TEMPLATE_URL.$image['i_3'];?>");
		jQuery('#image_4').attr("src","<?php echo TEMPLATE_URL.$image['i_4'];?>");
		jQuery('#image_5').attr("src","<?php echo TEMPLATE_URL.$image['i_5'];?>");
		<?php } ?>
	</script>
	
	<script type="text/javascript">
			document.body.style.backgroundImage = "url(<?php echo TEMPLATE_URL.get_option('tgt_default_background'); ?>)";				
	</script>
	
<?php 
}
?>

<style type="text/css">
img, div, a, input { behavior: url(iepngfix.htc) }
</style>
 
<?php
	echo '<style type="text/css">';
	echo get_option('tgt_custom_css');
	echo '</style>';
?>
 
 
	 <!--[if IE 6]>
		<link rel="stylesheet" type="text/css" media="screen" href="http://www.bookingadvisor.com/templates/css/ie-23.css" />
	 <![endif]--> 
	 <!--[if IE 7]>
		<link rel="stylesheet" type="text/css" media="screen" href="http://www.bookingadvisor.com/templates/css/ie7-19.css" />
	 <![endif]-->	
 
     <!--[if IE 8]>
        <link rel="stylesheet" type="text/css" media="screen" href="http://www.bookingadvisor.com/templates/css/ie8-18.css" />
     <![endif]--> 	
	 
 	<!--[if IE 6]>
		<script type="text/javascript" src="js/jquery.bgiframe.min.js"></script>
  	<![endif]--> 


<?php wp_head();?>
</head>

<?php $curr_main_bg = get_option('tgt_default_background'); 
if($curr_main_bg == '')
{
	echo '<body>';
}else 
{
?>
<body>
<?php } ?>
 <!-- header start -->
        <div id="header">
        <div class="logo">
        <?php
		$logo = get_option('tgt_default_logo');
		if($logo != '')
		{
		?>
        <a href="<?php echo home_url() ?>">
        <img src="<?php echo TEMPLATE_URL.$logo; ?>" alt="<?php _e('Hotel Logo','hotel'); ?>"/>
        </a>
        <?php
		}else
		{
		?>
        	<a href="<?php echo home_url() ?>">
	        	<img src="<?php echo TEMPLATE_URL; ?>/images/logo.png" alt="<?php _e('Hotel Logo','hotel'); ?>">	        	
        	</a>
         <?php
		}
		 ?>
        </div>
             
        
        <div class="phone">
            <div id="phonenr">
	            <p class="h3"><?php _e('24/7 Contact', 'hotel') // Contact goes here?></p>
	            <p class="h5"><?php 
	            $phone = get_option('tgt_hotel_phone');
	            echo $phone['p_1']; // Phone number 
	            ?></p>
            </div>
        	<div id="img">
        		<img src="<?php echo TEMPLATE_URL;?>/images/phone.jpg" alt="<?php _e('phone','hotel')?>"/>
            </div>
        </div>
        <div style="clear:both;"></div>
    </div>
    <!-- header end -->
    
    <!-- content start -->
    <div id="content">
		<div id="language_switcher">
			<?php			
			if ( '1' == get_option('tgt_can_change_language') ) 
				get_language_switcher(); ?>
			
		</div>
    	<div id="menu"> 
			<?php wp_nav_menu( array( 'container_class' => 'menu-header', 'theme_location' => 'headermenu', 'menu' => 'header_menu' ) ); ?>
            <?php
			if(get_option('tgt_permit_reservations') == '1')
			{
			?>
			<div class="reservations">
            	<div class="reservations_left"></div>
            	<div class="reservations_center"><a href="#dialog" class="reservation-button" name="modal"><?php _e ('Reservations', 'hotel');?></a></div>
                <div class="reservations_right"></div>
            </div>
            <?php
			}
			?>
            <div id="boxes"> <!-- check room avalible form-->
				<div id="dialog" class="window"> <!-- dialog-->
					<div class="online-book">
						<img src="<?php echo TEMPLATE_URL;?>/images/online-book.jpg" alt="<?php _e('Online Booking','hotel'); ?>"/></div>
					<div id="close">
						<a href="#"class="close"><img src="<?php echo TEMPLATE_URL;?>/images/modal-close.jpg" alt="<?php _e('close','hotel'); ?>"/></a>                
					</div>
					<div style="clear:both;"></div>				
					
					<div class="modal-check"> <!-- #modal-check-->					
						<?php get_search_form();?>
					</div> <!-- //modal-check-->
				</div> <!-- //dialog-->
            </div> <!-- //check room avalible form -->
				
				
		</div>
		
		<div style="clear:both;"></div>