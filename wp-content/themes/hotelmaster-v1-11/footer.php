	<?php global $theme_option; ?>
	<div class="clear" ></div>
	</div><!-- content wrapper -->

	<?php 
		// page style
		global $gdlr_post_option;
		if( empty($gdlr_post_option) || empty($gdlr_post_option['page-style']) ||
			  $gdlr_post_option['page-style'] == 'normal' || 
			  $gdlr_post_option['page-style'] == 'no-header'){ 
	?>	
	<footer class="footer-wrapper" >
		<?php if( $theme_option['show-footer'] != 'disable' ){ ?>
		<div class="footer-container container">
			<?php 	
				$i = 1;
				$theme_option['footer-layout'] = empty($theme_option['footer-layout'])? '1': $theme_option['footer-layout'];
				$gdlr_footer_layout = array(
					'1'=>array('twelve columns'),
					'2'=>array('three columns', 'three columns', 'three columns', 'three columns'),
					'3'=>array('three columns', 'three columns', 'six columns',),
					'4'=>array('four columns', 'four columns', 'four columns'),
					'5'=>array('four columns', 'four columns', 'eight columns'),
					'6'=>array('eight columns', 'four columns', 'four columns'),
				);
			?>
			<?php foreach( $gdlr_footer_layout[$theme_option['footer-layout']] as $footer_class ){ ?>
				<div class="footer-column <?php echo esc_attr($footer_class); ?>" id="footer-widget-<?php echo esc_attr($i); ?>" >
					<?php dynamic_sidebar('Footer ' . $i); ?>
				</div>
			<?php $i++; ?>
			<?php } ?>
			<div class="clear"></div>
		</div>
		<?php } ?>
		
		<?php if( $theme_option['show-copyright'] != 'disable' ){ ?>
		<div class="copyright-wrapper">
			<div class="copyright-container container">
				<div class="copyright-left">
					<?php if( !empty($theme_option['copyright-left-text']) ) echo gdlr_text_filter(gdlr_escape_string($theme_option['copyright-left-text'])); ?>
				</div>
				<div class="copyright-right">
					<?php if( !empty($theme_option['copyright-right-text']) ) echo gdlr_text_filter(gdlr_escape_string($theme_option['copyright-right-text'])); ?>
				</div>
				<div class="clear"></div>
			</div>
		</div>
		<?php } ?>
	</footer>
	<?php } // page style ?>
</div> <!-- body-wrapper -->
<?php wp_footer(); ?>
<style>
	#ui-datepicker-div{
		min-width: 250px;
		position: absolute;
    top: 859px;
    left: 754.125px;
    z-index: 90;
	}
	.ui-datepicker-calendar{
	}


	th{
	/* padding: 13px 0px; */
    font-size: 16px;
    font-weight: normal;
	}

	 td {
	    padding: 10px 1px!important;
	     margin: 2px 2px!important;
	    border-bottom-width: 1px;
	    border-bottom-style: solid;
	     background-color: #353535;
	}
	.ui-datepicker-today{
		background: #7B7B7B;
	}
	.ui-datepicker-today a,.ui-datepicker-today a:hover{
		color: #353535;
		background: #7B7B7B;
	}

	.ui-datepicker-current-day{
		background:#4A4A4A;
	}
	.ui-datepicker-current-day a,.ui-datepicker-current-day a:hover{
		color: #c1c1c1;
		background: #4A4A4A;
	}

	.ui-datepicker-header .ui-datepicker-next{
		float:right;		 margin: 8px;
			    cursor:pointer;


		   
	}

	.ui-datepicker-header .ui-datepicker-prev{
		    float: left;
		 margin: 8px;
		 	    cursor:pointer;

	}
	.ui-datepicker-calendar  thead tr th{
	padding: 6px 0px;
	}
	.ui-datepicker-header .ui-datepicker-title{
		 padding: 8px;
	    text-align: center;
	}
   

   /**navbar**/
   .gdlr-navigation-wrapper .gdlr-main-menu > li > a {
    	font-size: 13px;
    	padding: 0px 20px 20px 20px;
    	margin: 0px;
    	text-transform: uppercase;
	}
		.effect:hover{
			transition: transform 1s linear;
			transform: scale(1);
		}	


	.effect:hover{
			transition: transform 1s linear;
    		transform: scale(1.2);
	}

	

</style>
  <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script>
jQuery(document).ready(function() { 		
		jQuery( "#datepicker" ).datepicker({ dateFormat: 'dd M yy' });
		jQuery.datepicker.setDefaults( jQuery.datepicker.regional[ "en" ] );
		
		
});
</script>

<style>
	@media only screen and (max-width: 767px) {
		.titulo{
			width: 100%!important;
		}

		.search-llegada{
			width: 100%!important;
			line-height: 42px!important;

		}
		.search-llegada label{
			width: 95%;
			float:left;

		}
		.search-llegada input{
			width: 95%;
			float:left;

		}

		.search-huespedes{
			width: 100%!important;
			line-height: 42px!important;

		}
		.search-huespedes label{
			width: 95%;
			float:left;

		}
		.search-huespedes input{
			width: 95%;
			float:left;

		}


		.search-submit{
			width: 100%!important;
		}
	}
	

</style>

</body>
</html>

