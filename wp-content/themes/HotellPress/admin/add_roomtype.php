<?php
$uploadfile = "/images/roomtype";
$fullsize = "/images/fullsize";
$currency = get_option('tgt_currency');
	if ( $currency == "USD" || $currency == "AUD" || $currency == "CAD" || $currency == "NZD" || $currency == "HKD" || $currency == "SGD" ) { $currencysymbol = "$"; }
	else if ( $currency == "GBP" ) { $currencysymbol = "&pound;"; }
	else if ( $currency == "JPY" ) { $currencysymbol = "&yen;"; }
	else if ( $currency == "EUR" ) { $currencysymbol = "&euro;"; }
	else { $currencysymbol = ""; }
if(isset($_GET['action']) && $_GET['action']=="remove")
	{			
			$arr_themethumbnails= get_post_meta($_GET['roomtype_id'], 'tgt_roomtype_gallery', true);
			for($i=0;$i<count($arr_themethumbnails['small']);$i++){			
		
				if($arr_themethumbnails['small'][$i] == $_GET['subthumname']){
					unset($arr_themethumbnails['small'][$i]);							
				}				
				if($arr_themethumbnails['full'][$i] == $_GET['subthumname2']){
					unset($arr_themethumbnails['full'][$i]);
				}			
			}
			$arr_themethumbnails['small'] = array_values($arr_themethumbnails['small']); 
			//var_dump($arr_themethumbnails['small']);
			$arr_themethumbnails['full'] = array_values($arr_themethumbnails['full']);
			//var_dump($arr_themethumbnails['full']);
			update_post_meta($_GET['roomtype_id'], 'tgt_roomtype_gallery', $arr_themethumbnails);	
			if(file_exists(TEMPLATEPATH .$_GET['subthumname'])){					
				unlink(TEMPLATEPATH .$_GET['subthumname']);				
				}
			if(file_exists(TEMPLATEPATH .$_GET['subthumname2'])){					
				unlink(TEMPLATEPATH .$_GET['subthumname2']);				
				}
			
			echo "<script language='javascript'>window.location = '"."admin.php?page=my-submenu-handle-add-room-type&roomtype_id=". $_GET['roomtype_id'] ."';</script>";	
		
	}
if(isset($_POST['submit_go']) && !isset($_GET['roomtype_id'])){
$errors = array();
if (isset($_POST['title']) && $_POST['title']=="" ){$errors['title'] = __('Enter room type name, please!','hotel');}
if (isset($_POST['description']) && $_POST['description']=="" ){$errors['description'] = __('Enter description, please!','hotel');}
if (isset($_POST['personnumber']) && $_POST['personnumber']=="" ){$errors['personnumber'] = __('Enter person in rooms, please!','hotel');}
if (isset($_POST['kidsnumber']) && $_POST['kidsnumber'] == "" ){$errors['kidsnumber'] = __('Enter kids in rooms, please!','hotel');}
if (count($errors)==0){
$post_id = wp_insert_post( array(						
		'post_title'	=> $_POST['title'],
		'post_content'	=> $_POST['description'],
		'post_type' => 'roomtype',
		'post_status' => 'publish'
	) );
	$mainimage	= $_FILES['mainimage'];	
	if($mainimage['tmp_name'] != "")
				{	
					$image_results = array();
					$simg = imagecreatefromjpeg($mainimage['tmp_name']);
					$image_results['small'] = tgt_resize_image($mainimage['tmp_name'],$uploadfile,271,105,$mainimage['type'],$mainimage['name']);	
					if(imagesx($simg)<850){
					$image_results['full'] = tgt_resize_image($mainimage['tmp_name'],$fullsize,imagesx($simg),imagesy($simg),$mainimage['type'],$mainimage['name']);
					}else{
					$image_results['full'] = tgt_resize_image($mainimage['tmp_name'],$fullsize,850,850*imagesy($simg)/imagesx($simg),$mainimage['type'],$mainimage['name']);
					}					
					add_post_meta($post_id, 'tgt_roomtype_thumbnail', $image_results);
				}				
			// Upload thumbnails						
			$themethumbnails = array();			
			for($i=0; $i < count($_FILES['themethumbnails']['name']); $i++)
			{
				if($_FILES['themethumbnails']['name'][$i]!="" && substr($_FILES['themethumbnails']['type'][$i], 0, 6) == "image/")
				{
					if(file_exists($fullsize . $_FILES['themethumbnails']['name'][$i]))
					{
						$stringF= basename($_FILES['themethumbnails']['name'][$i]);
						$stringL= "".rand("000001", "999999");
						$length= strlen($stringF);
						$temp1= substr($stringF,0,$length-4);
						$temp2= substr($stringF,$length-4,$length);
						$file_name= $temp1.$stringL.$temp2;
					}
					else{
						$file_name= basename($_FILES['themethumbnails']['name'][$i]);
					}
					$simg = imagecreatefromjpeg($_FILES['themethumbnails']['tmp_name'][$i]);
					$themethumbnails['small'][]= tgt_resize_image($_FILES['themethumbnails']['tmp_name'][$i],$uploadfile,100,40,$_FILES['themethumbnails']['type'][$i],$file_name);	
					if(imagesx($simg)<850){
					$themethumbnails['full'][]= tgt_resize_image($_FILES['themethumbnails']['tmp_name'][$i],$fullsize,imagesx($simg),imagesy($simg),$_FILES['themethumbnails']['type'][$i],$file_name);	
					}else{
					$themethumbnails['full'][]= tgt_resize_image($_FILES['themethumbnails']['tmp_name'][$i],$fullsize,850,850*imagesy($simg)/imagesx($simg),$_FILES['themethumbnails']['type'][$i],$file_name);	
					}
				}
			}
			
			add_post_meta($post_id, 'tgt_roomtype_gallery', $themethumbnails);	
			add_post_meta($post_id, 'tgt_roomtype_person_number', $_POST['personnumber']);
			add_post_meta($post_id, 'tgt_roomtype_kids_number', $_POST['kidsnumber']);
			add_post_meta($post_id, 'tgt_roomtype_bed_name', $_POST['bedname']);
			add_post_meta($post_id, 'tgt_roomtype_permit_pet', $_POST['permitpet']);
			add_post_meta($post_id, 'tgt_roomtype_permit_smoking', $_POST['permitsmoking']);
			add_post_meta($post_id, 'tgt_roomtype_price', intval($_POST['price']));
			add_post_meta($post_id, 'tgt_roomtype_discount', intval($_POST['discount']));
			$message = "Insert room type {$_POST['title']} successful!";			
				setcookie("message", $message, time()+3600);			
				echo "<script language='javascript'>window.location = '"."admin.php?page=my-submenu-handle-list-room-types';</script>";	
	}
}

if(isset($_POST['submit_go']) && isset($_GET['roomtype_id'])){
$errors = array();
if (isset($_POST['title']) && $_POST['title'] =="" ){$errors['title'] = __('Enter room type name, please!','hotel');}
if (isset($_POST['description']) && $_POST['description']=="" ){$errors['description'] = __('Enter description, please!','hotel');}
if (isset($_POST['personnumber']) && $_POST['personnumber'] == "" ){$errors['personnumber'] = __('Enter person in rooms, please!','hotel');}
if (isset($_POST['kidsnumber']) && $_POST['kidsnumber'] == "" ){$errors['kidsnumber'] = __('Enter kids in rooms, please!','hotel');}
if (count($errors)==0){
	wp_update_post( array(
		'ID'			=> $_GET['roomtype_id'],					
		'post_title'	=> $_POST['title'],
		'post_content'	=> $_POST['description'],
		'post_type' => 'roomtype',
		'post_status' => 'publish'
	) );	
	$post_id = $_GET['roomtype_id'];
	$mainimage	= $_FILES['mainimage'];
	
	if(!empty($mainimage['tmp_name'] ))
				{	
					$image_results = array();
					$str_tmp= get_post_meta($post_id, 'tgt_roomtype_thumbnail', true);					
					
					if(file_exists(TEMPLATEPATH .$str_tmp['small'])){
					unlink(TEMPLATEPATH .$str_tmp['small']);	
					}
					if(file_exists(TEMPLATEPATH .$str_tmp['full'])){
					unlink(TEMPLATEPATH .$str_tmp['full']);	
					}
					$simg = imagecreatefromjpeg($mainimage['tmp_name']);
					$image_results['small'] = tgt_resize_image($mainimage['tmp_name'],$uploadfile,271,105,$mainimage['type'],$mainimage['name']);	
					if(imagesx($simg)<850){
					$image_results['full'] = tgt_resize_image($mainimage['tmp_name'],$fullsize,imagesx($simg),imagesy($simg),$mainimage['type'],$mainimage['name']);										
					}else{
					$image_results['full'] = tgt_resize_image($mainimage['tmp_name'],$fullsize,850,850*imagesy($simg)/imagesx($simg),$mainimage['type'],$mainimage['name']);	
					}
					update_post_meta($post_id, 'tgt_roomtype_thumbnail', $image_results);					
				}	
			// Upload thumbnails
			$str_old_themethumbnails= get_post_meta($post_id, 'tgt_roomtype_gallery', true);			
			$themethumbnails = array();			
			$j = count($str_old_themethumbnails['small']);
			$h = count($_FILES['themethumbnails']['name']) + $j;			
			for($i=$j ; $i < $h; $i++)
			{
				if($_FILES['themethumbnails']['name'][$i-$j]!="" && substr($_FILES['themethumbnails']['type'][$i-$j], 0, 6) == "image/")
				{
					if(file_exists($fullsize . $_FILES['themethumbnails']['name'][$i-$j]))
					{
						$stringF= basename($_FILES['themethumbnails']['name'][$i-$j]);
						$stringL= "".rand("000001", "999999");
						$length= strlen($stringF);
						$temp1= substr($stringF,0,$length-4);
						$temp2= substr($stringF,$length-4,$length);
						$file_name= $temp1.$stringL.$temp2;
					}
					else{
						$file_name= basename($_FILES['themethumbnails']['name'][$i-$j]);
					}
					$simg = imagecreatefromjpeg($_FILES['themethumbnails']['tmp_name'][$i-$j]);
					$themethumbnails['small'][$i]= tgt_resize_image($_FILES['themethumbnails']['tmp_name'][$i-$j],$uploadfile,100,40,$_FILES['themethumbnails']['type'][$i-$j],$file_name);	
					if(!empty($str_old_themethumbnails['small'])){
					array_push($str_old_themethumbnails['small'],$themethumbnails['small'][$i]);
					}
					else{
					$str_old_themethumbnails['small'][$i] = $themethumbnails['small'][$i];
					}					
					if(imagesx($simg)<850){
					$themethumbnails['full'][$i]= tgt_resize_image($_FILES['themethumbnails']['tmp_name'][$i-$j],$fullsize,imagesx($simg),imagesy($simg),$_FILES['themethumbnails']['type'][$i-$j],$file_name);	
					}
					else{
					$themethumbnails['full'][$i]= tgt_resize_image($_FILES['themethumbnails']['tmp_name'][$i-$j],$fullsize,850,850*imagesy($simg)/imagesx($simg),$_FILES['themethumbnails']['type'][$i-$j],$file_name);	
					}
					if(!empty($str_old_themethumbnails['full'])){
					array_push($str_old_themethumbnails['full'],$themethumbnails['full'][$i]);
					}
					else{
					$str_old_themethumbnails['full'][$i] = $themethumbnails['full'][$i];
					}									
				}
			}			
	update_post_meta($post_id, 'tgt_roomtype_gallery', $str_old_themethumbnails);	
	update_post_meta($post_id, 'tgt_roomtype_person_number', $_POST['personnumber']);
	update_post_meta($post_id, 'tgt_roomtype_kids_number', $_POST['kidsnumber']);
	update_post_meta($post_id, 'tgt_roomtype_bed_name', $_POST['bedname']);
	update_post_meta($post_id, 'tgt_roomtype_permit_pet', $_POST['permitpet']);
	update_post_meta($post_id, 'tgt_roomtype_permit_smoking', $_POST['permitsmoking']);
	update_post_meta($post_id, 'tgt_roomtype_price', intval($_POST['price']));
	update_post_meta($post_id, 'tgt_roomtype_discount', intval($_POST['discount']));	
	$message = "Update room type {$_POST['title']} successful!";			
	setcookie("message", $message, time()+3600);
	echo "<script language='javascript'>window.location = '"."admin.php?page=my-submenu-handle-list-room-types';</script>";	
	
   }
}

?>


<div class="wrap">	
	<div class="atention">
		<strong><?php __('Problems? Questions?','hotel');?></strong><?php _e(' Contact us at ','hotel');?><em><a href="http://www.dailywp.com/support/">Support</a></em>. 
	</div>
	<br/>
	<?php
		 if (isset($errors) && count($errors)>0){
			echo '<div class="error"><strong>';
			foreach ($errors as $item){
				echo "<p>$item</p>";
			}
			echo '</strong></div>';
		} 
	?>
	<br/>
	<div class="settings" style="margin: 0px 0px 0px 0px; padding-top: 0px 0px 0px 0px;">
		<div class="heading">
			<h3><?php _e('Add Room Type:','hotel');?></h3>					
			<div class="cl"></div>
		</div>
		<div class="item" style="padding: 0 0 0 40px; height:100%;">
			<div class="content" id="page">	
				<div class="content submission" id="jobs" >   	
				<form method="post" name="addroomtype" enctype="multipart/form-data" target="_self">            
					<input name="submitted" type="hidden" value="yes" />
					<div style="margin-bottom: 20px;">
						<?php
						global $query_string;		
						global $post;
						if (isset($_GET['roomtype_id'])){								
								query_posts('post_type=roomtype&p='.$_GET['roomtype_id']);							
						}						
						if(have_posts()){ the_post(); }	
						?>
						<span><?php _e('Image ','hotel');?></span>
						<div class="inputStyle">
							<input type="file" style="width:310px" size="40" name="mainimage" id="mainimage" value="" />				
						</div>
						<div class="clear"></div>
						<input type="hidden" name="titlesss" id="titlesss" value="<?php if(have_posts()){ echo $post->post_title;} else { if(isset($_POST['title'])) echo $_POST['title']; } ?>" style="width:300px;"/>	
						<span><?php _e('Room Type Name ','hotel');?>(*):</span>
						<div class="inputStyle">							
							<input type="text" name="title" id="title" value="<?php if($_POST['title']){ echo $_POST['title'];} else { if(have_posts()) { echo $post->post_title; } } ?>" style="width:300px;"/>				
						</div>
						<div class="clear"></div>										
						
						<span><?php _e('Bed Type:','hotel');?></span>
						<div class="inputStyle">
							<input type="text" name="bedname" id="bedname" value="<?php if(isset($_POST['bedname'])){ echo $_POST['bedname'];}else{ if(have_posts()){ echo  get_post_meta($post->ID, 'tgt_roomtype_bed_name', true); } } ?>" style="width:300px;"/>					
						</div>
						<div class="clear"></div>
						
						<span><?php _e('Description ','hotel');?>(*):</span>
						<style type="text/css">
							textarea {
								width: 100%;
								heught: 200px;
							}
						</style>
						<?php 
							if(!empty($_POST['description'])) 
								$exist_des = trim($_POST['description']);
							elseif (have_posts()) 
								$exist_des = $post->post_content;
								
							wp_tiny_mce(false);
							wp_enqueue_script('page');
							wp_enqueue_script('editor');
							do_action('admin_print_scripts');
							wp_enqueue_style('thickbox');
							wp_enqueue_script('thickbox');
							add_thickbox();
							add_action( 'admin_head', 'wp_tiny_mce' );
							wp_enqueue_script('media-upload');
							wp_enqueue_script('word-count');
							the_editor( $exist_des , 'description', '', false);						
						?>			
						
						<div class="clear"></div>
						
						<span><?php _e('Allow Pet:','hotel');?></span>
						<div class="inputStyle">								
							<select name="permitpet" style="width:100px;">	
								<option value="1"><?php _e('Yes','jobpress');?></option>
								<option value="0"><?php _e('No','jobpress');?></option>
							</select>
						</div>
						<div class="clear"></div>
						<script language="javascript">
												{
													for(var i=0;i<document.addroomtype.permitpet.length;i++){
														if(document.addroomtype.permitpet[i].value=="<?php if(have_posts()){  echo  get_post_meta($post->ID, 'tgt_roomtype_permit_pet', true); } ?>"){
															document.addroomtype.permitpet.selectedIndex=i;
															break;
														}
													}													
												}
												</script>
						<span><?php _e('Allow Smoking:','hotel');?></span>
						<div class="inputStyle">
							<select name="permitsmoking" style="width:100px">	
								<option value="1"><?php _e('Yes','jobpress');?></option>
								<option value="0"><?php _e('No','jobpress');?></option>
							</select>				
						</div>
						<div class="clear"></div>
						<script language="javascript">
												{
													for(var i=0;i<document.addroomtype.permitsmoking.length;i++){
														if(document.addroomtype.permitsmoking[i].value=="<?php if(have_posts()){ echo  get_post_meta($post->ID, 'tgt_roomtype_permit_smoking', true); } ?>"){
															document.addroomtype.permitsmoking.selectedIndex=i;
															break;
														}
													}													
												}
												</script>
						
						
						<span><?php _e('Person/Room ','hotel');?>(*):</span>
						<div class="inputStyle">
							<input type="text" name="personnumber" id="personnumber" value="<?php if(isset($_POST['personnumber'])){echo $_POST['personnumber'];}else{ if(have_posts()){ echo get_post_meta($post->ID, 'tgt_roomtype_person_number', true);} } ?>" style="width:100px;" onkeypress="return NumberYear(event)" /> <a href="#" style="text-decoration: none;" class="hintanchor" onMouseover="showhint('Should be number!', this, event, '120px')">[?]</a>				
						</div>
						<div class="clear"></div>
						<span><?php _e('Kids/Room ','hotel');?>(*):</span>
						<div class="inputStyle">
							<input type="text" name="kidsnumber" id="kidsnumber" value="<?php if(isset($_POST['kidsnumber'])){echo $_POST['kidsnumber'];}else{ if(have_posts()){ echo get_post_meta($post->ID, 'tgt_roomtype_kids_number', true);} } ?>" style="width:100px;" onkeypress="return NumberYear(event)" /> <a href="#" style="text-decoration: none;" class="hintanchor" onMouseover="showhint('Should be number!', this, event, '120px')">[?]</a>				
						</div>
						<div class="clear"></div>
						<span><?php _e('Price per night:','hotel');?></span>
						<div class="inputStyle">								
							<input type="text" name="price" id="price" value="<?php if(isset($_POST['price'])){echo $_POST['price'];}else{ if(have_posts()){ echo get_post_meta($post->ID, 'tgt_roomtype_price', true);} } ?>" style="width:100px;" onkeypress="return NumberFloat(event)" title="Should be number" /><?php echo "(".$currencysymbol.")"; ?><a href="#" style="text-decoration: none;" class="hintanchor" onMouseover="showhint('Should be number!', this, event, '120px')">[?]</a>	
						</div>
						<div class="clear"></div>
						
						<span><?php _e('Discount:','hotel');?></span>
						<div class="inputStyle">
							<input type="text" name="discount" id="discount" value="<?php if(isset($_POST['discount'])){echo $_POST['discount'];}else{ if(have_posts()){ echo get_post_meta($post->ID, 'tgt_roomtype_discount', true);} }?>" style="width:100px;" onkeypress="return NumberFloat(event)" />(%)<a href="#" style="text-decoration: none;" class="hintanchor" onMouseover="showhint('Should be number!', this, event, '120px')">[?]</a>				
						</div>
						<div class="clear"></div>
							

		<div class="" style="margin:0; padding:0 0 0 0px;">
			<span><?php _e('More images:','hotel');?></span>
			<div style="font-size:11px;">
			<?php
			if(isset($_GET['roomtype_id'])){
			$themethumbnails = get_post_meta($_GET['roomtype_id'], 'tgt_roomtype_gallery', true);					
				
			for($i=0;$i<count($themethumbnails['small']);$i++){						
				echo '<div>+&nbsp;<a>'. TEMPLATE_URL . $themethumbnails['small'][$i] .'</a>&rarr;&nbsp;<a style="color:#FF0000;" href="admin.php?page=my-submenu-handle-add-room-type&amp;roomtype_id='.$post->ID.'&amp;action=remove&amp;subthumname='. htmlspecialchars(str_replace("http://", "_ptth_", $themethumbnails['small'][$i])) .'&amp;subthumname2='. htmlspecialchars(str_replace("http://", "_ptth_", $themethumbnails['full'][$i])) .'">Remove</a></div>';
			}				
			}
			?>
			</div>
			<div id="attachments" style="width:875px;">

			<div id="attachment_0"><div style="float:left;"><input size="90" style="width:550px;" name="themethumbnails[]" id="themethumbnails[]" type="file" onchange="showAddAtt(0);"/></div><div style="float:left;" id="control_0"></div></div></div>
					<div class="clear"></div>
					</div>		
					</div>	
				</div>
			  </div>                                
		</div>                   
		<div class="cl"></div>
	</div>
	<div style="margin-bottom:15px;">
		<input id="submit_go" name="submit_go" onclick="return checks()" style="height:35px;" type="submit" value="<?php _e('Save','hotel');?>" />
	</div>
</div>
<script type="text/javascript">      
   function NumbersPhone(e)
	{
	var keynum;
	var keychar;
	var numcheck = new RegExp("^[^a-z^A-Z]");

	if(window.event) // IE
		{
		keynum = e.keyCode;
		}
	else if(e.which) // Netscape/Firefox/Opera
		{
		keynum = e.which;
		}
	keychar = String.fromCharCode(keynum);	
	return numcheck.test(keychar);
	}
    function NumberYear(e)
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
		var numcheck = new RegExp("^[0-9]");
	keychar = String.fromCharCode(keynum);	
	return numcheck.test(keychar);
	}
	function NumberFloat(e)
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
		var numcheck = new RegExp("^[0-9.,]");
	keychar = String.fromCharCode(keynum);	
	return numcheck.test(keychar);
	}	
</script>


<style type="text/css">

#hintbox{ /*CSS for pop up hint box */
position:absolute;
top: 0;
background-color: lightyellow;
width: 150px; /*Default width of hint.*/ 
padding: 3px;
border:1px solid black;
font:normal 11px Verdana;
line-height:18px;
z-index:100;
border-right: 3px solid black;
border-bottom: 3px solid black;
visibility: hidden;
}

.hintanchor{ /*CSS for link that shows hint onmouseover*/
font-weight: bold;
color: navy;
margin: 3px 8px;
}

</style>

<script type="text/javascript">

/***********************************************
* Show Hint script- © Dynamic Drive (www.dynamicdrive.com)
* This notice MUST stay intact for legal use
* Visit http://www.dynamicdrive.com/ for this script and 100s more.
***********************************************/
		
var horizontal_offset="9px" //horizontal offset of hint box from anchor link

/////No further editting needed

var vertical_offset="0" //horizontal offset of hint box from anchor link. No need to change.
var ie=document.all
var ns6=document.getElementById&&!document.all

function getposOffset(what, offsettype){
var totaloffset=(offsettype=="left")? what.offsetLeft : what.offsetTop;
var parentEl=what.offsetParent;
while (parentEl!=null){
totaloffset=(offsettype=="left")? totaloffset+parentEl.offsetLeft : totaloffset+parentEl.offsetTop;
parentEl=parentEl.offsetParent;
}
return totaloffset;
}

function iecompattest(){
return (document.compatMode && document.compatMode!="BackCompat")? document.documentElement : document.body
}

function clearbrowseredge(obj, whichedge){
var edgeoffset=(whichedge=="rightedge")? parseInt(horizontal_offset)*-1 : parseInt(vertical_offset)*-1
if (whichedge=="rightedge"){
var windowedge=ie && !window.opera? iecompattest().scrollLeft+iecompattest().clientWidth-30 : window.pageXOffset+window.innerWidth-40
dropmenuobj.contentmeasure=dropmenuobj.offsetWidth
if (windowedge-dropmenuobj.x < dropmenuobj.contentmeasure)
edgeoffset=dropmenuobj.contentmeasure+obj.offsetWidth+parseInt(horizontal_offset)
}
else{
var windowedge=ie && !window.opera? iecompattest().scrollTop+iecompattest().clientHeight-15 : window.pageYOffset+window.innerHeight-18
dropmenuobj.contentmeasure=dropmenuobj.offsetHeight
if (windowedge-dropmenuobj.y < dropmenuobj.contentmeasure)
edgeoffset=dropmenuobj.contentmeasure-obj.offsetHeight
}
return edgeoffset
}

function showhint(menucontents, obj, e, tipwidth){
if ((ie||ns6) && document.getElementById("hintbox")){
dropmenuobj=document.getElementById("hintbox")
dropmenuobj.innerHTML=menucontents
dropmenuobj.style.left=dropmenuobj.style.top=-500
if (tipwidth!=""){
dropmenuobj.widthobj=dropmenuobj.style
dropmenuobj.widthobj.width=tipwidth
}
dropmenuobj.x=getposOffset(obj, "left")
dropmenuobj.y=getposOffset(obj, "top")
dropmenuobj.style.left=dropmenuobj.x-clearbrowseredge(obj, "rightedge")+obj.offsetWidth+"px"
dropmenuobj.style.top=dropmenuobj.y-clearbrowseredge(obj, "bottomedge")+"px"
dropmenuobj.style.visibility="visible"
obj.onmouseout=hidetip
}
}

function hidetip(e){
dropmenuobj.style.visibility="hidden"
dropmenuobj.style.left="-500px"
}

function createhintbox(){
var divblock=document.createElement("div")
divblock.setAttribute("id", "hintbox")
document.body.appendChild(divblock)
}

if (window.addEventListener)
window.addEventListener("load", createhintbox, false)
else if (window.attachEvent)
window.attachEvent("onload", createhintbox)
else if (document.getElementById)
window.onload=createhintbox

</script>
<script language="javascript">
var id_attachment= 1;
var count_attachment= 1;
function addAttachment()
{
	var myElement = document.createElement("div");
	myElement.id= "attachment_"+id_attachment;
	myElement.innerHTML="<div style=\"float:left;\"><input size=\"90\" style=\"width:550px;\" name=\"themethumbnails[]\" id=\"themethumbnails[]\" type=\"file\" onchange=\"showAddAtt("+id_attachment+");\"/></div><div style=\"float:left;\" id=\"control_"+id_attachment+"\"></div>";
	document.getElementById("attachments").appendChild(myElement);
	document.getElementById("control_"+(id_attachment-1)+"").innerHTML= "<input name=\"removeattachment\" id=\"removeattachment\" type=\"button\" value=\"Remove\" style=\"height:21px; margin:1px 0 0 1px; padding:0;\" onclick=\"removeAttachment("+(id_attachment-1)+")\"/>";

	id_attachment++;
	count_attachment++;
}

function removeAttachment(attachment)
{
	document.getElementById("attachments").removeChild(document.getElementById("attachment_"+attachment+""));
	count_attachment--;
}


function showAddAtt(c_attachment)
{
	if((document.getElementById("attachments").innerHTML.split("showAddAtt").length-1) == (count_attachment))
	{
		var check= true;
		for(k=0; k<document.getElementsByName('themethumbnails[]').length; k++)
		{
			check= checkFileImageType(document.getElementsByName('themethumbnails[]')[k].value);
			if(check==false)
				break;
		}
		if(check)
			document.getElementById("control_"+c_attachment+"").innerHTML= "<input type=\"button\" value=\"Add\" style=\"height:21px; margin:1px 0 0 1px; padding:0;\" onclick=\"addAttachment()\"/>";
	}
	else
	{
		var check= true;
		for(k=0; k<document.getElementsByName('themethumbnails[]').length; k++)
		{
			check= checkFileImageType(document.getElementsByName('themethumbnails[]')[k].value);
			if(check==false)
				break;
		}
		if(check)
			document.getElementById("control_"+c_attachment+"").innerHTML= "<input type=\"button\" value=\"Add\" style=\"height:21px; margin:1px 0 0 1px; padding:0;\" onclick=\"addAttachment()\"/>";
	}

}

function checkFileImageType(filename)
{
	filename= filename.toLowerCase();
	if(filename.lastIndexOf(".bmp")==-1 && filename.lastIndexOf(".jpg")==-1 && filename.lastIndexOf(".jpeg")==-1 && filename.lastIndexOf(".jpe")==-1 && filename.lastIndexOf(".gif")==-1 && filename.lastIndexOf(".png")==-1)
	{
		alert("You must select an image file!"); 
		return false;
	}

	return true;
} 
</script>
</form>
	

