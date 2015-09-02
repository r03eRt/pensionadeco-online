<?php 
/*
Template Name: Location Page
*/
get_header();?> 

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
  	<style type="text/css">
<!--
.style3 {font-size: 18px}
.style4 {font-size: 12px}
-->
	</style>       
       <div class="middle-inner-wrapper" style="background:#e7dfd6 url(<?php echo TEMPLATE_URL;?><?php echo get_option('tgt_default_inner_background')?>) no-repeat center top;">
       
       		<div class="localization">
            	<p class="site-loc"><a href="<?php echo HOME_URL;?>" style="color:white"> <?php echo get_option('tgt_hotel_name');?></a></p><p>&raquo;&nbsp;<?php the_title();?></p>
  			</div>
            
         <div style="clear:both;"></div>
         
            <div class="middle-inner">
       			<div class="center-inner">
					<div class="title">
					<?php the_post();?>
					<p class="h1"><?php the_title();?></p>
					
						<div class="news-content" style="margin:15px 0;line-height:18px; font-weight:normal; border-bottom:0px">
							<?php the_content();?> 
						</div>
						<div class="contact-form" style="font-family: Arial, sans-serif; font-size: small;" onLoad="javascript:doSearch()">
							<div style="margin-left:5px;">
								<div style="width: 600px; margin-bottom: 20px;">
									<div id="map" style="height: 382px; border: 1px solid #979797;"></div>
								</div>
							</div>
						</div>
						
					</div>
        		</div>
			</div>
        
            <?php get_sidebar();?>
    	</div>
    <!-- content end -->
    <?php get_footer();?>