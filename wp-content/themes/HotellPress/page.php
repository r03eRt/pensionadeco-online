<?php
get_header();
apply_filters('post_gallery', '');
$contact = get_option('tgt_contact_page');
$location = get_option('tgt_location_page');
?>
<!--

<link href="http://www.google.com/uds/css/gsearch.css" rel="stylesheet" type="text/css"/>
<link href="http://gmaps-samples-v3.googlecode.com/svn/trunk/localsearch/places.css" rel="stylesheet" type="text/css"/> 
<script src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script src="http://www.google.com/uds/api?file=uds.js&amp;v=1.0&amp;key=<?php echo get_option('tgt_google_code') ?>" type="text/javascript"></script>

<script type="text/javascript">
    //<![CDATA[ 
     
    // Our global state
    var gLocalSearch;
    var gMap;
    var gInfoWindow;
    var gSelectedResults = [];
    var gCurrentResults = [];
    var gSearchForm;
   // alert('<?php echo get_option('tgt_map_address'); ?>');
	var Location;
	if('<?php echo get_option('tgt_hotel_coordinates'); ?>' != '0,0')
	    Location = "<?php echo get_option('tgt_hotel_coordinates');?>";
	else
	    Location = "<?php echo get_option('tgt_map_address');?>";
 	//Location = "52 Hoa Hong, F2, Phu Nhuan, Ho Chi Minh, Viet Nam";
    // Create our "tiny" marker icon
    var gYellowIcon = new google.maps.MarkerImage(
      "http://labs.google.com/ridefinder/images/mm_20_yellow.png",
      new google.maps.Size(12, 20),
      new google.maps.Point(0, 0),
      new google.maps.Point(6, 20));
    var gRedIcon = new google.maps.MarkerImage(
      "http://labs.google.com/ridefinder/images/mm_20_red.png",
      new google.maps.Size(12, 20),
      new google.maps.Point(0, 0),
      new google.maps.Point(6, 20));
    var gSmallShadow = new google.maps.MarkerImage(
      "http://labs.google.com/ridefinder/images/mm_20_shadow.png",
      new google.maps.Size(22, 20),
      new google.maps.Point(0, 0),
      new google.maps.Point(6, 20));
    function OnLoad() {
      gMap = new google.maps.Map(document.getElementById("map"), {
        center: new google.maps.LatLng(40.71435281518603, -74.0059730999999),
        overviewMapControl: true,
        zoom: 16,
        mapTypeId: 'roadmap'
      });
      // Create one InfoWindow to open when a marker is clicked.
      gInfoWindow = new google.maps.InfoWindow;
      google.maps.event.addListener(gInfoWindow, 'closeclick', function() {
        unselectMarkers();
      });
      // Initialize the local searcher
      gLocalSearch = new GlocalSearch();
      gLocalSearch.setSearchCompleteCallback(null, OnLocalSearch);
	 
	  doSearch(Location);
    }
 
    function unselectMarkers() {
      for (var i = 0; i < gCurrentResults.length; i++) {
        gCurrentResults[i].unselect();
      }
    }
 
    function doSearch(loc) {
      var query = loc;
      gLocalSearch.setCenterPoint(gMap.getCenter());
      gLocalSearch.execute(query);
    }
 
    function OnLocalSearch() {
      if (!gLocalSearch.results) return;
     
      for (var i = 0; i < gCurrentResults.length; i++) {
        gCurrentResults[i].marker().setMap(null);
      }
      // Close the infowindow
      gInfoWindow.close();
 
      gCurrentResults = [];
      for (var i = 0; i < gLocalSearch.results.length; i++) {
        gCurrentResults.push(new LocalResult(gLocalSearch.results[i]));
      }
 
      // Move the map to the first result
      var first = gLocalSearch.results[0];
      gMap.setCenter(new google.maps.LatLng(parseFloat(first.lat),
                                            parseFloat(first.lng)));
 
    }
    
    // A class representing a single Local Search result returned by the
    // Google AJAX Search API.
    function LocalResult(result) {
      var me = this;
      me.result_ = result;
      me.resultNode_ = me.node();
      me.marker_ = me.marker();
      google.maps.event.addDomListener(me.resultNode_, 'mouseover', function() {
        me.highlight(true);
      });
      google.maps.event.addDomListener(me.resultNode_, 'mouseout', function() {
        if (!me.selected_) me.highlight(false);
      });
      google.maps.event.addDomListener(me.resultNode_, 'click', function() {
        me.select();
      });
      //document.getElementById("searchwell").appendChild(me.resultNode_);
    }
 
    LocalResult.prototype.node = function() {
      if (this.resultNode_) return this.resultNode_;
      return this.html();
    };
 
    // Returns the GMap marker for this result, creating it with the given
    // icon if it has not already been created.
    LocalResult.prototype.marker = function() {
      var me = this;
      if (me.marker_) return me.marker_;
      var marker = me.marker_ = new google.maps.Marker({
        position: new google.maps.LatLng(parseFloat(me.result_.lat),
                                         parseFloat(me.result_.lng)),
        icon: gYellowIcon, shadow: gSmallShadow, map: gMap});
      google.maps.event.addListener(marker, "click", function() {
        me.select();
      });
      return marker;
    };
 
    // Unselect any selected markers and then highlight this result and
    // display the info window on it.
    LocalResult.prototype.select = function() {
      unselectMarkers();
      this.selected_ = true;
      this.highlight(true);
      gInfoWindow.setContent(this.html(true));
      gInfoWindow.open(gMap, this.marker());
    };
 
    LocalResult.prototype.isSelected = function() {
      return this.selected_;
    };
 
    // Remove any highlighting on this result.
    LocalResult.prototype.unselect = function() {
      this.selected_ = false;
      this.highlight(false);
    };
 
    // Returns the HTML we display for a result before it has been "saved"
    LocalResult.prototype.html = function() {
      var me = this;
      var container = document.createElement("div");
      container.className = "unselected";
      container.appendChild(me.result_.html.cloneNode(true));
      return container;
    }
 
    LocalResult.prototype.highlight = function(highlight) {
      this.marker().setOptions({icon: highlight ? gRedIcon : gYellowIcon});
      this.node().className = "unselected" + (highlight ? " red" : "");
    }
 
    GSearch.setOnLoadCallback(OnLoad);
</script>
-->
<?php	
	$isSentMail = 0;
	
	if(!empty($_POST['contact_admin']))
	{			
		$comment = $_POST['comments'];				
		$hotel_email = get_option('tgt_hotel_email');
		
		$comment = stripslashes($comment);
		$comment = $comment."\n\n\n"."----------------------------------------------"."\n\n";
		$comment = $comment."From: ".$_POST['customer_name']."\n";
		$comment = $comment."Email: ".$_POST['email']."\n";
		$comment = $comment."Telephone: ".$_POST['phone']."\n";

		if($hotel_email !='')
		{
			$exist_domain= 1;
			$exp = "^[a-z\'0-9]+([._-][a-z\'0-9]+)*@([a-z0-9]+([._-][a-z0-9]+))+$";
			
			if(eregi($exp,$hotel_email))
			{
				$exist_domain = 1;	
			}
			else
			{				
				$exist_domain = 0;
			}   
			if($exist_domain != 0)
			{
				$header = 'From: '.$_POST['customer_name'].' <'.$_POST['email'].'> ';
				@wp_mail($hotel_email, 'A message from customer', $comment, $header);
				$isSentMail = 1;				
			}
			else 
			{
				$isSentMail = -1;
			} 			
		}
		else 
		{
			$isSentMail = -1;
		}	
	}
?>	


       <div class="middle-inner-wrapper" style="background:#e7dfd6 url(<?php echo TEMPLATE_URL.get_option('tgt_default_inner_background');?>) no-repeat center top;">       
       		<div class="localization">       			
            	<p class="site-loc"><a href="<?php echo HOME_URL;?>" style="color:white"> <?php echo get_option('tgt_hotel_name');?></a></p><p>&raquo;&nbsp;<?php the_title();?> </p>
  			</div>
            
         <div style="clear:both;"></div>
         
            <div class="middle-inner">
       			<div class="center-inner">
	                <div class="title">	            		 
	            		<?php 
	            			if(have_posts())
	            			{
	            				the_post();
	            				$id = get_the_ID();
	            			}	            			
	            		?>
	            		<p class="h1">
		            		<?php the_title();
		            		
		            		?>
	            		</p>
	            		<div class="news-content page-content">
	                          <?php 
	                               the_content();
	                          ?>
	                     </div> 
	            		
                          <?php
                              if( $id == $contact && 1==0) {
                           ?>
                            <a href="<?php echo tgt_get_location_link();?>">
			                	<div class= "address"><p><i>
								<?php  echo get_option('tgt_hotel_street'); ?><br />
			                	<?php echo get_option('tgt_hotel_state');?></i><br />
			                	<b><font style="color:rgb(71,62,62);">                	     	
			                	<?php echo get_option('tgt_hotel_country');?></font>
			                	 </b>
			                	</p> 
			                	</div>
		                	</a>
		                	          	
		                		<div class="phone-nr">
		                		<?php  $phone = get_option('tgt_hotel_phone'); ?>
		                		<?php              		
		                		 if($phone['p_1']!=null) { ?>                			                		               	
		                			<div class="nr">
		                				<p><?php  echo $phone['p_1'];?></p>
		                			</div>
		                		<?php }?>
		                		<?php              		
		                		 if($phone['p_2']!=null) { ?>                			                		               	
		                			<div class="nr">
		                				<p><?php  echo $phone['p_2'];?></p>
		                			</div>
		                		<?php }?>
		                		<?php              		
		                		 if($phone['p_3']!=null) { ?>                			                		               	
		                			<div class="nr">
		                				<p><?php  echo $phone['p_3'];?></p>
		                			</div>
		                		<?php }?>
		                	</div>                                			
		                	<div style="clear: both;"></div>                	
		                	<?php 
			                   	
			                   	if($isSentMail == 1)
			                   	{	
			                ?>
			                <div class="contact-form"> 
				                <p id="send_mail_error" style="width: auto; font-weight: bold; font-size: 12px; color: rgb(255, 0, 0);">
				                	<?php 	
				                			$hotel_name = get_option('tgt_hotel_name'); 
				                			if($hotel_name == "")
				                				$hotel_name = "hotel";
				                			echo  $hotel_name;
				                			_e(' received your email. We will reply as soon as. Thank you.', 'hotel');
				             		?>
				             	</p>
				             </div>	
				             <?php } ?> 
			                <?php 
			                	if($isSentMail == 0)
			                	{
			                ?>
			                	<div class="contact-form"> 
					        	<p id="send_mail_error" style="width: auto; font-weight: bold; font-size: 12px; color: rgb(255, 0, 0);"></p>						
								</div> 
			                <?php }	                   		                       		
			                   	if($isSentMail == -1)
			                   	{
			                ?>
			                	<div class="contact-form"> 
					        	<p id="send_mail_error" style="width: auto; font-weight: bold; font-size: 12px; color: rgb(255, 0, 0);">
								<?php 
									_e ('Can not send your e-mail to ', 'hotel');
									$hotel_name = get_option('tgt_hotel_name');
									if($hotel_name == "")
										$hotel_name = "hotel";
									echo $hotel_name;
								?>
								</p>
								</div> 
			                <?php }	?>                
					            
		                  <div class="contact-form"> 
		                	<div style="clear: both;"></div>                	
		                		<form action="" method="post" onsubmit="return checkInput();">
		                		<table border="0">
		  	             		<tr><td>
		                			<input type="hidden" name="page_id" value="<?php echo $id;?>" /> 
		                			<div class="input-content">
		                			<p><?php _e('Your Name(*):','hotel'); ?></p>
		                				<input class="input" type="text" name="customer_name" size="15"  />
		                			</div>
		                		</td><td>	                			
		                			<div class="input-content">
		            				<p><?php _e('E-mail Address(*):','hotel'); ?> </p>
		                        	<input class="input" type="text" name="email" size="15" />    
			                        </div>
			                      </td></tr>
			                      <tr>
			                      	<td><p id="name_error" style="width: auto; font-weight: normal; font-size: 12px; color: rgb(255, 0, 0);"></p>
			                      	</td>
			                      	<td>
			                      		<p id="email_error" style="width: auto; font-weight: normal; font-size: 12px; color: rgb(255, 0, 0);"> </p>
			                      	</td>
			                      </tr>
			                      <tr><td>
			                        	<div class="input-content">
			           					<p><?php _e('Phone Number(*):', 'hotel'); ?></p>
			                        	<input class="input" type="text" name="phone" size="15" /> 
		                         </div>
			                      </td><td></td>
			                      </tr>
			                      <tr><td colspan="2">
			                      <p id="phone_error" style="width: auto; font-weight: normal; font-size: 12px; color: rgb(255, 0, 0);" > </p>
			                      </td></tr>
			                      <tr><td colspan= "2">                  
			                        <div class="input-content">
				                        <p><?php _e('Message(*):', 'hotel'); ?></p>
				                        <textarea id = "cus_message" class="input-textarea" name="comments" rows="5" cols="50" ></textarea>                       
			                        </div>
			                      </td></tr>
			                      <tr>
			                      <td colspan="2">
			                      	<p id="comment_error" style="width: auto; font-weight: normal; font-size: 12px; color: rgb(255, 0, 0);"></p>
			                      </td>
			                      </tr> 
			                      <tr><td colspan="2">
			                        <div style="clear:left;"></div>
			                        
			                        <div style="margin-top: 15px; float: left; margin-left: 0pt;" class="button">  
										<div class="button_left"></div>        
											<div class="button_center">
												<input type="submit" value="<?php _e ('Send Message', 'hotel');?>" class="button" name="contact_admin" >
											</div>
										<div class="button_right"></div>
									</div>
			                     </td></tr>
			                     </table>   
			                        <div style="clear:left;"></div>
								</form>						
		                	</div> 
		                	    
                			<?php 
                                }
                                elseif ( $id == $location && 1==0 ){
                                
                                ?>
                                <!--<div class="news-content" style="margin:15px 0;line-height:18px; font-weight:normal; border-bottom:0px">
                					<?php //the_content();?> 
               					</div>
                				--><div style="clear:both;"></div>	      
                				<div class="contact-form" style="font-family: Arial, sans-serif; font-size: small;" onLoad="javascript:doSearch()">
									<div style="margin-left:5px;">
										<div style="width: 600px; margin-bottom: 20px;">
										  <div id="map" style="height: 382px; border: 1px solid #979797;"></div>
										</div>
									</div>
								</div>
								<?php 
                                }
                                ?>
                                <script type="text/javascript">
                                    var img_link1 = "<?php echo get_bloginfo('template_directory');?>";
                                </script>
                        </div>
	            		<div style="clear:both;"></div>                
        			</div>
				</div>      
            
            <?php get_sidebar();?>
            <div class="bottom">
	       		<!--<img src="<?php echo TEMPLATE_URL;?>/images/inner-page-bottom.jpg" alt="inner_page_bottom_image"/>-->
	       </div>
         </div> 
    <?php get_footer();?>    
<script type="text/javascript">
	var customer_name_empty = "<?php _e('* Enter your name.', 'hotel'); ?>";
	var email_empty = "<?php _e('* Enter your e-mail.', 'hotel'); ?>";
	var email_invalid = "<?php _e('* Email invalid.', 'hotel'); ?>";
	var phone_empty = "<?php _e('* Enter your phone number.', 'hotel'); ?>";
	var phone_invalid = "<?php _e('* Phone number invalid.', 'hotel'); ?>";
	var message_empty = "<?php _e('* Enter your message.', 'hotel'); ?>";
</script>    