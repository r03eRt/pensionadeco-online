	

	<div id="phone" >
		<div class="icon">
			<i class="fa fa-phone"></i>
		</div>
		<div class="text">
  			Contacto  <span style="color:white;font-weight: bold;">24/7</span><br> 91 531 73 89
		</div>
		
	</div>
	<style>
	#phone{
		float:right;margin-top: 20px;width:15%;
	}
	#phone .icon{
		float:left;width:30%;
	}
	#phone .icon i{
		font-size:38px;padding-right:5px
	}
	#phone .text{
		float:left;width:70%;font-size:12px
	}

	@media only screen and (max-width: 959px){
		#phone{
			float:right;margin-top: 0px;width:15%;    margin-right: 10px;
		}
	}

	@media only screen and (max-width: 767px){

		#phone{
			display:none;
		}

		.gdlr-logo {
		    max-width: 70%;
		}
	}

	.lubalin{
		    font-family: "Lubalin"!important;
	}


	
	.gdlr-header-inner .gdlr-fixed-header  .gdlr-navigation-wrapper {
	    margin: 0px 15px;
	    float: right;
	}
	</style>

<?php 
	global $theme_option;

	echo '<div class="gdlr-navigation-wrapper" >';

	// navigation
	if( has_nav_menu('main_menu') ){
		if( class_exists('gdlr_menu_walker') ){
			echo '<nav class="gdlr-navigation" id="gdlr-main-navigation" role="navigation">';
			wp_nav_menu( array(
				'theme_location'=>'main_menu', 
				'container'=> '', 
				'menu_class'=> 'sf-menu gdlr-main-menu',
				'walker'=> new gdlr_menu_walker() 
			) );
		}else{
			echo '<nav class="gdlr-navigation" role="navigation">';
			wp_nav_menu( array('theme_location'=>'main_menu') );
		}
		//gdlr_get_woocommerce_nav();
		echo '</nav>'; // gdlr-navigation
	}
?>	



<!--<span class="gdlr-menu-search-button-sep">•</span>
<i class="fa fa-search icon-search gdlr-menu-search-button" id="gdlr-menu-search-button" ></i>-->

	
<?php	
	echo '<div class="gdlr-navigation-gimmick" id="gdlr-navigation-gimmick"></div>';	
	echo '<div class="clear"></div>';
	echo '</div>'; // gdlr-navigation-wrapper
?>

