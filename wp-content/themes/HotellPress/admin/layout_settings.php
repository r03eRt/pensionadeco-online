<?php
//echo get_option('tgt_contact_page');
//echo 'dsfsdaf sdaa'. get_option('tgt_location_page');
$errors = '';
$message = '';
if(!empty($_COOKIE['message']))
	$message = $_COOKIE['message']; //message from add_room.php
setcookie("message", $message, time()-3600);
// Default Logo
$default_favi = "/favicon.ico";

// Default Logo
$default = "/images/logo.png";

// Default Main Background
$default_main_bg = "/images/background.jpg";

// Default Inner Background
$default_in_bg = "/images/inner_bg.jpg";

// Default Image Slider
$default_img_slide = array("i_1"=>"/images/slide.jpg",
						   "i_2"=>"/images/17.JPG",
						   "i_3"=>"/images/20.JPG",
						   "i_4"=>"/images/22.JPG",
						   "i_5"=>"/images/13.JPG");
if (isset($_POST['set_default_menu'])) {
	tgt_set_default_menu();
	echo "<div id=\"message\" class=\"updated fade\"><p><strong>".__('Your settings have been saved','hotel')."</strong></p></div>";
}
if(isset($_POST['submitted']) && !empty($_POST['submitted']))
{
	//Favicon	
	if(isset($_POST['UploadFavicon']) && !empty($_POST['UploadFavicon']))
	{
		$new_favi = $_FILES['your_favi'];
		if($new_favi['name'] != '' )
		{			
			if($new_favi['error'] > 0)
			{	
				$errors .= __("Sory, We can't upload your favicon.", 'hotel')."<br>";			
			}elseif($new_favi['name'] != 'favicon.ico')
			{	
				$errors .= __("Your favicon should rename is the 'favicon.ico'.", 'hotel')."<br>";			
			}
			else
			{
				$save_to = TEMPLATEPATH;
				if(file_exists(TEMPLATEPATH . $new_favi['name']))
					unlink(TEMPLATEPATH . $new_favi['name']);
				move_uploaded_file($new_favi["tmp_name"],$save_to .'/'. $new_favi["name"]);
				update_option('tgt_default_favicon','/'.$new_favi["name"]);
			}
		}
	}
	//Logo
	if(isset($_POST['btSetClassicLogo']) && !empty($_POST['btSetClassicLogo']))
	{
		$curr_logo = get_option('tgt_default_logo');
		if(file_exists(TEMPLATEPATH . $curr_logo) && $curr_logo != '/images/logo.png')
			unlink(TEMPLATEPATH . $curr_logo);
		update_option('tgt_default_logo',$default);
	}
	if(isset($_POST['UploadLogo']) && !empty($_POST['UploadLogo']))
	{
		$new_logo = $_FILES['your_logo'];
		if($new_logo['name'] != '')
		{
			$save_to = "/images/logo";
			//$logo_results = tgt_resize_image($new_logo['tmp_name'],$save_to,277,64,$new_logo['type'],$new_logo['name']);
			move_uploaded_file($new_logo["tmp_name"],TEMPLATEPATH.$save_to .'/'. $new_logo["name"]);
			if ($logo_results === false)
			{
				$errors .= __("Sory, We can't upload your picture.", 'hotel')."<br>";
			}
			else{
				$curr_logo = get_option('tgt_default_logo');
				if(file_exists(TEMPLATEPATH . $curr_logo) && $curr_logo != '/images/logo.png')
					unlink(TEMPLATEPATH . $curr_logo);
				$curr_logo = $save_to .'/'. $new_logo["name"];
				//move_uploaded_file($companylogo,$logo_results);
				update_option('tgt_default_logo',$curr_logo);
			}
		}
	}
	// Main Background
	if(isset($_POST['btSetClassicBg']) && !empty($_POST['btSetClassicBg']))
	{
		$curr_main_bg = get_option('tgt_default_background');
		if(file_exists(TEMPLATEPATH . $curr_main_bg) && $curr_main_bg != '/images/background.jpg')
			unlink(TEMPLATEPATH . $curr_main_bg);
		update_option('tgt_default_background',$default_main_bg);
	}
	if( isset($_POST['submit_page_con']) && !empty($_POST['submit_page_con']))
	{
		$new_contact = $_POST['contact_page_id'];
		update_option('tgt_contact_page', $new_contact);
	}
	if( isset($_POST['submit_page_loc']) && !empty($_POST['submit_page_loc']))
	{
		$new_locate = $_POST['location_page_id'];
		update_option('tgt_location_page', $new_locate);
	}
	if(isset($_POST['UploadMainBackground']) && !empty($_POST['UploadMainBackground']))
	{
		$new_logo = $_FILES['your_bg'];
		if($new_logo['name'] != '')
		{
			$save_to = "/images/background/main_bg";
			$main_bg_results = tgt_resize_image($new_logo['tmp_name'],$save_to,1500,1000,$new_logo['type'],$new_logo['name']);
			if ($main_bg_results === false)
			{
				$errors .= __("Sory, We can't upload your picture.", 'hotel')."<br>";
			}
			else{
				$curr_main_bg = get_option('tgt_default_background');
				if(file_exists(TEMPLATEPATH . $curr_main_bg) && $curr_main_bg != '/images/background.jpg')
					unlink(TEMPLATEPATH . $curr_main_bg);
				$curr_main_bg = $main_bg_results;
				//move_uploaded_file($companylogo,$logo_results);
				update_option('tgt_default_background',$curr_main_bg);
			}
		}
	}
	// Inner Background
	if(isset($_POST['btSetClassicInBg']) && !empty($_POST['btSetClassicInBg']))
	{
		$curr_in_bg = get_option('tgt_default_inner_background');
		if(file_exists(TEMPLATEPATH . $curr_in_bg) && $curr_in_bg != '/images/inner_bg.jpg')
			unlink(TEMPLATEPATH . $curr_in_bg);
		update_option('tgt_default_inner_background',$default_in_bg);
	}
	if(isset($_POST['UploadInnerBackground']) && !empty($_POST['UploadInnerBackground']))
	{
		$new_in_bg = $_FILES['your_in_bg'];
		if($new_in_bg['name'] != '')
		{
			$save_to = "/images/background/inner_bg";
			$in_bg_results = tgt_resize_image($new_in_bg['tmp_name'],$save_to,960,160,$new_in_bg['type'],$new_in_bg['name']);
			if ($main_bg_results === false)
			{
				$errors .= __("Sory, We can't upload your picture.", 'hotel')."<br>";
			}
			else{
				$curr_in_bg = get_option('tgt_default_inner_background');
				if(file_exists(TEMPLATEPATH . $curr_in_bg) && $curr_in_bg != '/images/inner_bg.jpg')
					unlink(TEMPLATEPATH . $curr_in_bg);
				$curr_in_bg = $in_bg_results;
				//move_uploaded_file($companylogo,$logo_results);
				update_option('tgt_default_inner_background',$curr_in_bg);
			}
		}
	}
	// Image Slider
	if(isset($_POST['btSetClassicSlider']) && !empty($_POST['btSetClassicSlider']))
	{		
		$curr_slider = get_option('tgt_image_slider');
		if(file_exists(TEMPLATEPATH . $curr_slider['i_1']) && $curr_slider['i_1'] != '/images/slide.jpg')
			unlink(TEMPLATEPATH . $curr_slider['i_1']);
		if(file_exists(TEMPLATEPATH . $curr_slider['i_2']) && $curr_slider['i_2'] != '/images/17.JPG')
			unlink(TEMPLATEPATH . $curr_slider['i_2']);
		if(file_exists(TEMPLATEPATH . $curr_slider['i_3']) && $curr_slider['i_3'] != '/images/20.JPG')
			unlink(TEMPLATEPATH . $curr_slider['i_3']);
		if(file_exists(TEMPLATEPATH . $curr_slider['i_4']) && $curr_slider['i_4'] != '/images/22.JPG')
			unlink(TEMPLATEPATH . $curr_slider['i_4']);
		if(file_exists(TEMPLATEPATH . $curr_slider['i_5']) && $curr_slider['i_5'] != '/images/13.JPG')
			unlink(TEMPLATEPATH . $curr_slider['i_5']);
		update_option('tgt_image_slider',$default_img_slide);
	}
	if(isset($_POST['UploadImageSlider']) && !empty($_POST['UploadImageSlider']))
	{
		$pic_1 = $_FILES['your_img_1'];
		$pic_2 = $_FILES['your_img_2'];
		$pic_3 = $_FILES['your_img_3'];
		$pic_4 = $_FILES['your_img_4'];
		$pic_5 = $_FILES['your_img_5'];
		if($pic_1['name'] != '' || $pic_2['name'] != '' || $pic_3['name'] != '' || $pic_4['name'] != '' || $pic_5['name'] != '')
		{
			$curr_slider = get_option('tgt_image_slider');
			$save_to = "/images/slider/index";
			if($pic_1['name'] != '')
				$pic_1_results = tgt_resize_image($pic_1['tmp_name'],$save_to,960,320,$pic_1['type'],$pic_1['name']);
			else
				$pic_1_results = $curr_slider['i_1'];
				
			if($pic_2['name'] != '')
				$pic_2_results = tgt_resize_image($pic_2['tmp_name'],$save_to,960,320,$pic_2['type'],$pic_2['name']);
			else
				$pic_2_results = $curr_slider['i_2'];
				
			if($pic_3['name'] != '')
				$pic_3_results = tgt_resize_image($pic_3['tmp_name'],$save_to,960,320,$pic_3['type'],$pic_3['name']);
			else
				$pic_3_results = $curr_slider['i_3'];
				
			if($pic_4['name'] != '')
				$pic_4_results = tgt_resize_image($pic_4['tmp_name'],$save_to,960,320,$pic_4['type'],$pic_4['name']);
			else
				$pic_4_results = $curr_slider['i_4'];
				
			if($pic_5['name'] != '')
				$pic_5_results = tgt_resize_image($pic_5['tmp_name'],$save_to,960,320,$pic_5['type'],$pic_5['name']);
			else
				$pic_5_results = $curr_slider['i_5'];
				
			if ($pic_1_results === false || $pic_2_results === false || $pic_3_results === false || $pic_4_results === false || $pic_5_results === false)
			{
				$errors .= __("Sory, We can't upload your picture.", 'hotel')."<br>";
			}
			else{
				
				if(file_exists(TEMPLATEPATH . $curr_slider['i_1']) && $curr_slider['i_1'] != '/images/slide.jpg' && $pic_1['name'] != '')
					unlink(TEMPLATEPATH . $curr_slider['i_1']);
				if(file_exists(TEMPLATEPATH . $curr_slider['i_2']) && $curr_slider['i_2'] != '/images/17.JPG' && $pic_2['name'] != '')
					unlink(TEMPLATEPATH . $curr_slider['i_2']);
				if(file_exists(TEMPLATEPATH . $curr_slider['i_3']) && $curr_slider['i_3'] != '/images/20.JPG' && $pic_3['name'] != '')
					unlink(TEMPLATEPATH . $curr_slider['i_3']);
				if(file_exists(TEMPLATEPATH . $curr_slider['i_4']) && $curr_slider['i_4'] != '/images/22.JPG' && $pic_4['name'] != '')
					unlink(TEMPLATEPATH . $curr_slider['i_4']);
				if(file_exists(TEMPLATEPATH . $curr_slider['i_5']) && $curr_slider['i_5'] != '/images/13.JPG' && $pic_5['name'] != '')
					unlink(TEMPLATEPATH . $curr_slider['i_5']);
				$curr_img_slide = array("i_1"=>$pic_1_results,
								    "i_2"=>$pic_2_results,
								    "i_3"=>$pic_3_results,
								    "i_4"=>$pic_4_results,
								    "i_5"=>$pic_5_results);
				
				update_option('tgt_image_slider',$curr_img_slide);
			}
		}
	}	
	if($errors == '')
	{
		$message = __('Your settings have been saved !','hotel');			
		setcookie("message", $message, time()+3600);
		echo "<script language='javascript'>window.location = '"."admin.php?page=my-submenu-handle-layout-settings"."'</script>";
	}
	else if($errors != '')
		$message = $errors;		
}
?>
<div class="wrap">
	<?php the_support_panel(); ?>
	<br/>
    <?php
	if ($message) echo '<div class="updated below-h2">'.$message.'</div>';
	?>
    <form method="post" name="upload_layout" enctype="multipart/form-data" target="_self">
	<input name="submitted" type="hidden" value="yes" />	
	<div class="settings" style="margin: 0px 0px 0px 0px; padding-top: 0px 0px 0px 0px;">
		<div class="heading">
			<h3 style="padding-top:10px;"><?php _e('Favicon Setting','hotel');?></h3>
			<div class="cl"></div>
		</div>	        	
        <div class="item">
			<div class="left">
				<?php _e('Current Favicon :','hotel');?>
				<span><?php _e('Display your current favicon on theme.','hotel');?></span>
			</div>
			<div class="right">
            	<?php $curr_favi = get_option('tgt_default_favicon'); ?>  
                <img src="<?php echo TEMPLATE_URL.$curr_favi; ?>" width="32px" height="32px" title="<?php _e('Current Favicon','hotel');?>" alt=""/> 
			</div>
			<div class="clear"></div>
		</div>
        <div class="item">        
			<div class="left">
				<?php _e('Upload Favicon :','hotel');?>
				<span><?php _e('Upload your favicon you want to set on theme.','hotel');?></span>                
			</div>
			<div class="right">
				 
				 <input type="file" name="your_favi" id="your_favi" value=""  style="width:200px; border:1px solid #C7C7C7;" size="45"/>                 
				 <div style="color:#F00;font-style:italic">(<?php _e('Your favicon file should rename as favicon.ico and the size should be 32x32 pixel','hotel');?>)</div>   
				 <div style="padding-top:15px;">
				  <input id="submit_go" style="cursor:pointer" name="UploadFavicon" class="button" type="submit" value="<?php _e('Upload Favicon','hotel');?>" />
				 </div>  
			</div>
			<div class="clear"></div>       	
		</div> 
     </div><!-- // postbox -->
     <br/>
     <div class="settings" style="margin: 0px 0px 0px 0px; padding-top: 0px 0px 0px 0px;">
		<div class="heading">
			<h3 style="padding-top:10px;"><?php _e('Logo Setting','hotel');?></h3>
			<div class="cl"></div>
		</div>
		<div class="item">
			<div class="left">
				<?php _e('Current Logo :','hotel');?>
				<span><?php _e('Display your current logo in header website.','hotel');?></span>
			</div>
			<div class="right">
            	<?php $curr_logo = get_option('tgt_default_logo'); ?>
                
				<img src="<?php echo TEMPLATE_URL.$curr_logo; ?>" width="250px" height="64px" title="<?php _e('Current Logo','hotel');?>" alt=""/>                 
                    <input id="submit_go" name="btSetClassicLogo" style="cursor:pointer" class="button" type="submit" value="<?php _e('Set Classic Logo','hotel');?>" />
                
			</div>
			<div class="clear"></div>
		</div>
        <div class="item">        
			<div class="left">
				<?php _e('Upload Logo :','hotel');?>
				<span><?php _e('Upload your Logo you want to set in header website.','hotel');?></span>                
			</div>
			<div class="right">
				 
				 <input type="file" name="your_logo" id="your_logo" value=""  style="width:200px; border:1px solid #C7C7C7;" size="45"/>
				 <div style="color:#F00;font-style:italic">(<?php _e('Your Logo size should be 277x64 pixel','hotel');?>)</div>
				 <div style="padding-top:15px;">
				  <input id="submit_go" style="cursor:pointer" name="UploadLogo" class="button" type="submit" value="<?php _e('Upload Logo','hotel');?>" />
				 </div>  
			</div>
			<div class="clear"></div>       	
		</div>
	</div><!-- // postbox -->
    <br/>
    <!-- Start Page settings -->
     <div class="settings" style="margin: 0px 0px 0px 0px; padding-top: 0px 0px 0px 0px;">
		<div class="heading">
			<h3 style="padding-top:10px;"><?php _e('Page Setting','hotel');?></h3>
			<div class="cl"></div>
		</div>
		<div class="item">
			<div class="left">
				<?php _e('Contact page :','hotel');?>
				<span><?php _e('Choose a page to display as an Contact page. There is a contact from in Contact Us page for user to send email directly to website administrator.','hotel');?></span>
			</div>
			<div class="right">
				<?php
					$pages = get_pages();
					$contact_page_id = get_option( 'tgt_contact_page' ) ? get_option( 'tgt_contact_page' ):  0;
								
				?>
				<select name="contact_page_id" style=" width: auto" >
						<option value="0"><?php _e('Please choose') ?></option>
						<?php
							foreach( $pages as $page )
							{
								$class = '';
								if ( $contact_page_id == $page->ID )
								$class = ' selected="selected" ';											
								echo '<option value="'. $page->ID .'" ' . $class . '> '. $page->post_title .' </option>';
							}
						?>
				</select>	
				<div style="padding-top:15px;">
				  <input id="submit_page_con" style="cursor:pointer" class="button" type="submit" name="submit_page_con" value="<?php _e('Save','hotel');?>" />
				 </div> 
			</div>
			<div class="clear"></div>
		</div>
        <div class="item">        
			<div class="left">
				<?php _e('Location page :','hotel');?>
				<span><?php _e('Choose a page to display as an Location page. There is a place to show your hotel\'s location with visual way.','hotel');?></span>                
			</div>
			<div class="right">
				 <?php
					$pages1 = get_pages();
					$location_page_id = get_option( 'tgt_location_page' ) ? get_option( 'tgt_location_page' ):  0;
								
				?>
				<select name="location_page_id" style=" width: auto" >
					<option value="0"><?php _e('Please choose') ?></option>
						<?php
							foreach( $pages1 as $page1 )
							{
								$class = '';
								if ( $location_page_id == $page1->ID )
								$class = ' selected="selected" ';											
								echo '<option value="'. $page1->ID .'" ' . $class . '> '. $page1->post_title .' </option>';
							}
						?>
				</select>	
				<div style="padding-top:15px;">
				  <input id="submit_page_loc" style="cursor:pointer" class="button" type="submit" name="submit_page_loc" value="<?php _e('Save','hotel');?>" />
				</div> 
			</div>
			<div class="clear"></div>       	
		</div>
	</div><!-- // postbox -->
	<!-- End Page Setting -->
    <br/>
    <div class="settings" style="margin: 0px 0px 0px 0px; padding-top: 0px 0px 0px 0px;">
        <div class="heading">
            <h3 style="padding-top:10px;"><?php _e('Background Setting','hotel');?></h3>
            <div class="cl"></div>
        </div> 
        <div class="item">
			<div class="left">
				<?php _e('Current Background :','hotel');?>
				<span><?php _e('Display your current background of website.','hotel');?></span>
			</div>
			<div class="right">
            	<?php $curr_main_bg = get_option('tgt_default_background'); ?>
                
				<img src="<?php echo TEMPLATE_URL.$curr_main_bg; ?>" width="250px" height="64px" title="<?php _e('Current Background','hotel');?>" alt=""/>                 
                    <input id="submit_go" name="btSetClassicBg" style="cursor:pointer" class="button" type="submit" value="<?php _e('Set Classic Background','hotel');?>" />
                
			</div>
			<div class="clear"></div>
		</div>
        <div class="item">        
			<div class="left">
				<?php _e('Upload Background :','hotel');?>
				<span><?php _e('Upload your background you want to set for website.','hotel');?></span>                
			</div>
			<div class="right">
				 
				 <input type="file" name="your_bg" id="your_bg" value=""  style="width:200px; border:1px solid #C7C7C7;" size="45"/>
				 <div style="color:#F00;font-style:italic">(<?php _e('Your main background size should be 1500x1000 pixel','hotel');?>)</div>
				 <div style="padding-top:15px;">
				  <input id="submit_go" style="cursor:pointer" class="button" type="submit" name="UploadMainBackground" value="<?php _e('Upload Main Background','hotel');?>" />
				 </div>  
			</div>
			<div class="clear"></div>       	
		</div>
	</div><!-- // postbox -->
    <br/>
    <div class="settings" style="margin: 0px 0px 0px 0px; padding-top: 0px 0px 0px 0px;">
        <div class="heading">
            <h3 style="padding-top:10px;"><?php _e('Inner Background Setting','hotel');?></h3>
            <div class="cl"></div>
        </div>
        <div class="item">
			<div class="left">
				<?php _e('Current Inner Background :','hotel');?>
				<span><?php _e('Display your current inner background of website.','hotel');?></span>
			</div>
			<div class="right">
            	<?php $curr_inner_bg = get_option('tgt_default_inner_background'); ?>
                
				<img src="<?php echo TEMPLATE_URL.$curr_inner_bg; ?>" width="250px" height="64px" title="<?php _e('Current Inner Background','hotel');?>" alt=""/>                 
                    <input id="submit_go" name="btSetClassicInBg" style="cursor:pointer" class="button" type="submit" value="<?php _e('Set Classic Inner Background','hotel');?>" />
                
			</div>
			<div class="clear"></div>
		</div>
        <div class="item">        
			<div class="left">
				<?php _e('Upload Inner Background :','hotel');?>
				<span><?php _e('Upload your innner background you want to set for website.','hotel');?></span>                
			</div>
			<div class="right">
				 
				 <input type="file" name="your_in_bg" id="your_in_bg" value=""  style="width:200px; border:1px solid #C7C7C7;" size="45"/>
				 <div style="color:#F00;font-style:italic">(<?php _e('Your inner background size should be 960x160 pixel','hotel');?>)</div>
				 <div style="padding-top:15px;">
				  <input id="submit_go" style="cursor:pointer" name="UploadInnerBackground" class="button" type="submit" value="<?php _e('Upload Inner Background','hotel');?>" />
				 </div>  
			</div>
			<div class="clear"></div>       	
		</div> 
	</div><!-- // postbox -->
    <br/>
    <div class="settings" style="margin: 0px 0px 0px 0px; padding-top: 0px 0px 0px 0px;">
        <div class="heading">
            <h3 style="padding-top:10px;"><?php _e('Images Slider Setting','hotel');?></h3>
            <div class="cl"></div>
        </div>
        <div class="item">        
			<div class="left">
				<?php _e('Upload Image Slider :','hotel');?>
				<span><?php _e('Upload your image slider you want to set for website.','hotel');?></span>  
                <div style="color:#F00;font-style:italic;text-transform:none;">(<?php _e('Your each image slider size should be 960x320 pixel','hotel');?>)</div>     
                <div style="padding-top:15px;">
                <input id="submit_go" style="cursor:pointer" name="UploadImageSlider" class="button" type="submit" value="<?php _e('Upload Image Slider','hotel');?>" />
                <br/><br/>
                <input id="submit_go" name="btSetClassicSlider" style="cursor:pointer" class="button" type="submit" value="<?php _e('Set Classic Image Slider','hotel');?>" />
                </div>         
			</div>
			<div class="right">
				<?php $curr_slider = get_option('tgt_image_slider'); ?>
				 <img src="<?php echo TEMPLATE_URL.$curr_slider['i_1']; ?>" width="250px" height="64px" title="<?php _e('Current Image First','hotel');?>" alt=""/>
				 <br/>
				 <input type="file" name="your_img_1" id="your_img_1" value=""  style="width:200px; border:1px solid #C7C7C7;" size="45"/>                    
				 <div style="margin-bottom:5px;">
					  &nbsp;
				 </div>  
		  
				 <img src="<?php echo TEMPLATE_URL.$curr_slider['i_2']; ?>" width="250px" height="64px" title="<?php _e('Current Image Second','hotel');?>" alt=""/>
				 <br/>
				 <input type="file" name="your_img_2" id="your_img_2" value=""  style="width:200px; border:1px solid #C7C7C7;" size="45"/>                    
				 <div style="margin-bottom:5px;">
				 &nbsp;                
				 </div>  
		  
				 <img src="<?php echo TEMPLATE_URL.$curr_slider['i_3']; ?>" width="250px" height="64px" title="<?php _e('Current Image Third','hotel');?>" alt=""/>
				 <br/>
				 <input type="file" name="your_img_3" id="your_img_3" value=""  style="width:200px; border:1px solid #C7C7C7;" size="45"/>                    
				 <div style="margin-bottom:5px;">
					  &nbsp;
				 </div>  
			
				 <img src="<?php echo TEMPLATE_URL.$curr_slider['i_4']; ?>" width="250px" height="64px" title="<?php _e('Current Image Four','hotel');?>" alt=""/>
				 <br/>
				 <input type="file" name="your_img_4" id="your_img_4" value=""  style="width:200px; border:1px solid #C7C7C7;" size="45"/>                    
				 <div style="margin-bottom:5px;">
					  &nbsp;
				 </div>  
			
				 <img src="<?php echo TEMPLATE_URL.$curr_slider["i_5"]; ?>" width="250px" height="64px" title="<?php _e('Current Image Five','hotel');?>" alt=""/>
				 <br/>
				 <input type="file" name="your_img_5" id="your_img_5" value=""  style="width:200px; border:1px solid #C7C7C7;" size="45"/>                    
				 <div style="margin-bottom:15px;margin-top:20px;">
					  
				 </div>  
			</div>
			<div class="clear"></div>     	
		</div>    
    </div><!-- // postbox -->
    </form> 
    <br/>
    <form action="" method="post">
    <div class="settings" style="margin: 0px 0px 0px 0px; padding-top: 0px 0px 0px 0px;">
    <div class="heading">
        <h3 style="padding-top:10px;"><?php _e('Active Default Menu','hotel');?></h3>
        <div class="cl"></div>
    </div>  
	        <div class="item">        
				<div class="left">					
					<span><?php _e('Set default items on menu bars in your theme (header_menu & footer_menu)','hotel');?></span>                
				</div>
					<div class="right">
					  <input type="submit" class="button" name="set_default_menu" value="Set Default Menus"/>                                        
					</div>
				<div class="clear"></div>       	
			</div>		  
	</div> <!-- // postbox -->
    </form>  
</div>	