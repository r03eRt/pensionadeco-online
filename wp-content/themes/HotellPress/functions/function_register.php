<?php
/**
**** Store all common funtions
*/
global $wp_query;
add_action('init', 'add_image_size');
	if ( function_exists( 'add_image_size' ) ) { 
		add_image_size( 'roomtype-image', 271, 105 , true); 
	}
/**
 * rezise image 
 * @param string $ipath : path image
 * @param string $tdir : thumbnail directory. Example: /images/thumbnail
 * @param int $twidth	: thumbnail width
 * @param int $theight	: thumbnail height
 * @param string $type_image: ~ $_FILES['file']['type'];
 * @param string $name_image: name resize image.
 * @return string path to resized image if success,  return false if defeat
 */
function tgt_resize_image($ipath, $tdir, $twidth, $theight, $image_type, $name_image){
	try{	
		$simg = '';
		//check image type
		$type_arr = array('image/jpg', 'image/jpeg', 'image/pjpeg');
		if (in_array($image_type, $type_arr))
		{		
			$simg = imagecreatefromjpeg($ipath);
		}
		elseif($image_type == 'image/png'){
			$simg = imagecreatefrompng($ipath);
		}
		elseif($image_type == 'image/gif'){
			$simg = imagecreatefromgif($ipath);
		}
		else return false;
		
		$currwidth = imagesx($simg);   // Current Image Width
	    $currheight = imagesy($simg);
	   /* if ($twidth == 0) $twidth = $currwidth;
	    if ($theight == 0) $theight = $theight;*/
	    
		$dimg = imageCreateTrueColor($twidth, $theight);   // Make New Image For Thumbnail
	 
	    $name_image .= rand(1, 100);
	    $name_image = sanitize_file_name($name_image);
	    imagecopyresampled ($dimg, $simg, 0, 0, 0, 0, $twidth, $theight, $currwidth, $currheight);
	   // imagecopyresized($dimg, $simg, 0, 0, 0, 0, $twidth, $theight, $currwidth, $currheight);   // Copy Resized Image To The New Image (So We Can Save It)
	    imagejpeg($dimg, TEMPLATEPATH . "$tdir/" . $name_image . '.jpg');   // Saving The Image
	}
	catch (exception $e){
		imagedestroy($simg);   // Destroying The Temporary Image
    	imagedestroy($dimg);   // Destroying The Other Temporary Image
    	return false;
	}
	
    imagedestroy($simg);   // Destroying The Temporary Image
    imagedestroy($dimg);   // Destroying The Other Temporary Image
    return $tdir . '/' . $name_image . '.jpg';
   // wp_attachment_is_image()
}

// print_r( tgt_get_version('3'));exit;
/**
 * 
 * Get current version of product from dailywp.com 
 * @param string $id
 * @param string $link default is "http://dailywp.com/dailywp/xml/versions.xml"
 * @param string $type default is "theme", have 2 value: "theme" or "plugin"
 * @return false if link not exist or no version. If succesful return array name & version of product
 */
function tgt_get_version($id, $type="theme", $link="http://dailywp.com/xml/versions.xml")
{
	$name = '';
	$version = '';
	$version = false;
	$handle = @fopen($link,'r');
	if(!$handle)
		return false;	
		
	$doc = new DOMDocument();
	
  	$doc->load( $link );
  
 	$products = $doc->getElementsByTagName( "product" );

  	foreach( $products as $product )
  	{
		$types = $product->getElementsByTagName( "type" );	  
		$ids = $product->getElementsByTagName( "id" );	  
		$versions = $product->getElementsByTagName('version');
		  
		if($types->item(0)->nodeValue==$type && $ids->item(0)->nodeValue==$id){
			$version = $versions->item(0)->nodeValue;
			$name = $product->getElementsByTagName("name")->item(0)->nodeValue;
			break;
		}
	}		
	return array ('name'=>$name, 'version'=>$version);
}
/**
 * @method tgt_get_inner_background
 * @author mr.Nhan
 * Print out the inner background
 *  
 */
function tgt_get_inner_background(){
	$curr_inner_bg = get_option('tgt_default_inner_background'); 
	if($curr_inner_bg == '')
		return '<div class="middle-inner-wrapper" style="background:#e7dfd6 url('.TEMPLATE_URL.'/images/inner-page-bg.jpg) no-repeat center top;">';
	else
		return '<div class="middle-inner-wrapper" style="background:#e7dfd6 url('.TEMPLATE_URL.$curr_inner_bg.') no-repeat center top;">';
}
/**
 * @method tgt_the_pagination
 * @author Nguyen Minh Toan
 * Print out the pagination of page
 * It's used to print out the current page, last page and the first page.
 * It will also print out the two nearest page of current page. 
 *  
 */
function tgt_the_pagination(){
	global $wp_query;
	
	$pagination = array();
	
	$paged = get_query_var('paged');	
	if ( !$paged )
		$paged = 1;

	$totalPage = absint($wp_query->max_num_pages);
	if (!$totalPage){
		$totalPage = 1;
	}
	
	$start 	= $paged - 2 > 0 			? $paged - 2 : 1;
	$end 	= $paged + 2  < $totalPage 	? $paged + 2 : $totalPage;
	
	if ($start > 1)
	{
		$pagination[] = '<a href="' . esc_url( get_pagenum_link( 1 ) ) . '">1</a>';
		if ($start > 2)
			$pagination[] = '...';
	}
	
	for ($i = $start; $i <= $end; $i++) {
		if ($i == $paged)
			$pagination[] = '<a class="selected2" href="' . esc_url( get_pagenum_link( $i ) ) . '">' . $i . '</a>';
		else 
			$pagination[] = '<a href="' . esc_url( get_pagenum_link( $i ) ) . '">' . $i . '</a>';
	}

	if ($end < $totalPage)
	{
		if ($end < $totalPage - 1)
			$pagination[] = '...';
		$pagination[] = '<a href="' . esc_url( get_pagenum_link( $totalPage ) ) . '">' . $totalPage . '</a>';
	}
	
	
	if ($totalPage >1 ) {
	?>
	<div class="pagination"> 
		<p> <?php _e('Pages: ', 'hotel') ?> </p>
		<ul>
			<?php 
				foreach ($pagination as $p) {
					echo '<li>'. $p . '</li>';
				}
			?>
		</ul>        
    </div>
	<?php 
	}
} ?>
<?php 

/**
 * @method tgt_the_comment_pagination
 * @author Nguyen Minh Toan
 * Print out the pagination of comments
 * It's used to print out the current comments page, last comments page and the first comments page.
 * It will also print out the two nearest comments page of current comments page. 
 *  
 */
function tgt_the_comment_pagination(){
	global $wp_query;
	
	$pagination = array();
	
	$paged = get_query_var('cpage');	
	if ( !$paged )
		$paged = 1;

	$totalPage = absint($wp_query->max_num_comment_pages);
	if (!$totalPage){
		$totalPage = 1;
	}
	
	$start 	= $paged - 2 > 0 			? $paged - 2 : 1;
	$end 	= $paged + 2  < $totalPage 	? $paged + 2 : $totalPage;
	
	if ($start > 1)
	{
		$pagination[] = '<a href="' . esc_url( get_comments_pagenum_link(1)) . '">1</a>';
		if ($start > 2)
			$pagination[] = '...';
	}
	
	for ($i = $start; $i <= $end; $i++) {
		if ($i == $paged)
			$pagination[] = '<a class="selected2" href="' . esc_url( get_comments_pagenum_link( $i ) ) . '">' . $i . '</a>';
		else 
			$pagination[] = '<a href="' . esc_url( get_comments_pagenum_link( $i ) ) . '">' . $i . '</a>';
	}

	if ($end < $totalPage)
	{
		if ($end < $totalPage - 1)
			$pagination[] = '...';
		$pagination[] = '<a href="' . esc_url( get_comments_pagenum_link( $totalPage ) ) . '">' . $totalPage . '</a>';
	}
	
	if ($totalPage >1 ) {
	?>
	<div class="pagination"> 
		<p> <?php _e('Pages: ', 'hotel') ?> </p>
		<ul>
			<?php 
				foreach ($pagination as $p) {
					echo '<li>'. $p . '</li>';
				}
			?>
		</ul>        
    </div>
	<?php
} 
}

function hotel_comment($comment, $args, $depth){
	$GLOBALS['comment'] = $comment; ?>
     <div id="div-comment-<?php comment_ID() ?>" class="comment-container" style="margin-bottom:20px;">
		<div class="comment-entry" id="comment-<?php comment_ID() ?>">	
			<p> <span class="comment-author"><?php echo get_comment_author_link() ?> </span> <?php _e('says:', 'hotel') ?> <br/>
			
			<span><?php comment_time('F j, Y \a\t g:i a') ?></span><br/><br/>
			</p>
			<p>
			<?php comment_text() ?>			
			</p>	
		</div>
	</div>     
     
<?php
}

/**
 * Nguyen Minh Toan
 * Reading countries and cities infomation from a xml file
 * @param string $file file xml path
 * @return array $countries infomation or false if error occurred.
 * Format : ['Country Name'] => ['city 1', 'city 2']
 */
function objectsIntoArray($arrObjData, $arrSkipIndices = array())
{
    $arrData = array();
   
    // if input is object, convert into array
    if (is_object($arrObjData)) {
        $arrObjData = get_object_vars($arrObjData);
    }
   
    if (is_array($arrObjData)) {
        foreach ($arrObjData as $index => $value) {
            if (is_object($value) || is_array($value)) {
                $value = objectsIntoArray($value, $arrSkipIndices); // recursive call
            }
            if (in_array($index, $arrSkipIndices)) {
                continue;
            }
            $arrData[$index] = $value;
        }
    }
    return $arrData;
}
 
function tgt_get_countries(){
 $result = array();
 $xml = file_get_contents(TEMPLATEPATH.'/data/countries.xml');
 $dom = new SimpleXMLElement($xml);
 $dom = objectsIntoArray($dom);
 
 if(!empty($dom) && isset($dom['country'])) {
 
  $list_countries = $dom['country']; 
  if( !empty($list_countries) && is_array($list_countries) ) {
  
   $et_countries  = array();
 
   // Get all the cities in list
   foreach( $list_countries as $key => $value) {
    $et_countries[]  = $value['@attributes']['value'];     
   }
  }
  $result['countries']  = $et_countries;
 }
 return $result;
}

/**
 * 
 * Write countries and cities infomation into a xml file 
 * countries array format : ['Country Name'] => ['city 1', 'city 2']
 * @param string $file file Path
 * @param array $countries countries list with cities
 * @return int the number of bytes written or false if an error occurred.  
 */
function tgt_write_countries($file, $countries){
	$doc = new DOMDocument('1.0', 'utf-8');
	$doc->formatOutput = true;
	
	$root = $doc->createElement('countries');
	$doc->appendChild($root);
	
	foreach ($countries as $countryName => $country){
		
		// create node country
		$countryElement = $doc->createElement('country');
		$countryElement->setAttribute('value', $countryName);
		
		// get cities list
		foreach ($country as $cityName => $city){
			// create node city
			$cityElement = $doc->createElement('city');
			$cityElement->setAttribute('value', $city);
			
			// add city element into city element
			$countryElement->appendChild($cityElement);
		}
		
		//add country Elemente into root;
		$root->appendChild($countryElement);
	}
	
	//save to file
	return $doc->save($file);
}
/**
 * 
 * Parse countries.ini and write into a xml file
 * @param string $file file Path
 * 
 */
function tgt_parse_ini(){
	// parse file countries.ini
	$ini_array = parse_ini_file ("countries.ini");
	echo '<pre>';
	$result = array();
	// translate format to correct format of XML file
	foreach ($ini_array as $country) {
		$result[$country] = array();
	}
	// write it into countries.xml
	$result['Viet Nam'] = array('Ho Chi Minh city', 'Ha Noi');
	tgt_write_countries(TEMPLATEPATH . '/data/countries.xml', $result);
}

function tgt_include_index_js(){
	?>
	<script type="text/javascript" src="<?php echo TEMPLATE_URL?>/js/calendar.js"></script> 
	<script type="text/javascript" src="<?php echo TEMPLATE_URL?>/js/jquery.autocomplete.min.js"></script> 
	<script type="text/javascript" src="<?php echo TEMPLATE_URL?>/js/jquery.timers.js"></script>	
	<script type="text/javascript" src="<?php echo TEMPLATE_URL?>/js/jquery.datePicker-2.1.2.js"></script>
	<?php 
}

/*
 *add shortcode gallery 
 *
 */
add_filter('post_gallery', 'post_gallery_d');
function post_gallery_d(){
    $a = get_the_content();
    if( strpos( $a, 'link="file"') != false){
        add_filter( 'wp_get_attachment_link' , 'add_lighbox_rel' );
        function add_lighbox_rel( $attachment_link ) {
            $attachment_link = str_replace( 'a href' , 'a rel="lightbox-mygallery" href' , $attachment_link );
            return $attachment_link;
    	}
    }
}

/**
 * Echo the support panel for admin site
 */
function the_support_panel()
{ ?>
	<div class="atention">
		<strong><?php _e('Problems? Questions?','hotel');?></strong><?php _e(' Contact us at ','hotel');?><em><a href="http://www.dailywp.com/community/">Support</a></em>. 
	</div>
	<?php
}

function get_pricing($checkin_date, $booking_room)
{
    $rooms = implode(',', $booking_room);
    $checkin_date = date('Y-m-d', strtotime($checkin_date));
    global $wpdb;    
    $sql = "SELECT * FROM " . $wpdb->prefix . "pricing" . " WHERE '" . $checkin_date . "' >= date_start AND room_type_id IN (" . $rooms .  ") AND disable='1' ORDER BY priority";
    $results = $wpdb->get_results($sql);
    return $results;
}
function pricing_room($from_date, $to_date, $room_types_booking)
{    
    $results = get_pricing($from_date, $room_types_booking);    
    $final_price = array();
    if(!empty($results))
    {
        //get price of room type        
        $prices = array();
        $discounts = array();
        $args = array(
            'post_status' => 'publish',
            'post_type' => 'roomtype',        	
        );
        $room_type_lists = query_posts($args);        
        if(!empty($room_type_lists))
        {
            foreach ($room_type_lists as $room_type)
            {
                $price = get_post_meta($room_type->ID, 'tgt_roomtype_price', true);
                $discount = get_post_meta($room_type->ID, 'tgt_roomtype_discount', true);
                $prices[] = array(''.$room_type->ID => $price);
                $discounts[] = array(''.$room_type->ID => $discount);
            }
        }
        foreach ($room_type_lists as $room_type)
        {
            $date_bookings = date_yearly($from_date, $to_date);            
            foreach ($date_bookings as $date_booking)
            {               
				$final_price[strtotime($date_booking)] = array();                
                foreach ($results as $result)
                {
                	$date_results = pricing_in_date($result->time_type, $result->time, $from_date, $to_date);
                    foreach ($date_results as $date_result)
                    {
                    	if(strtotime($date_result) == strtotime($date_booking))
                        {
                        	$cap_prices = explode(',', $result->new_price_change);
                            if(!empty($cap_prices))
                            {
								$i=0;
                                foreach($cap_prices as $cap_price)
                                {
                                 	if(empty($final_price[strtotime($date_booking)][++$i]))
                                       	$final_price[strtotime($date_booking)][$i] = $cap_price;
                                }
                             }
                     	}                            
                     }                        
                }
            	foreach ($results as $result)
                {
                	$date_results = pricing_in_date($result->time_type, $result->time, $from_date, $to_date);
                    if(empty($final_price[strtotime($date_booking)]))
                    {
                    	$room_type_cap_prices = get_post_meta($room_types_booking[0], META_ROOMTYPE_CAP_PRICE, true);                	
                        $i=0;
                        foreach($room_type_cap_prices as $cap_price)
                        {
                        	if(empty($final_price[strtotime($date_booking)][++$i]))
								$final_price[strtotime($date_booking)][$i] = $cap_price['price'];
                        }
                	}
                }
            }
        }
    }
    ksort($final_price);
    return $final_price;
}
function pricing_in_date($time_type, $time,$from_date, $to_date)
{
    $dates = array();
    $from_month = date('m', strtotime($from_date));
    $from_year = date('Y', strtotime($from_date));
    $from_day = date('d', strtotime($from_date));
    $to_year = date('Y', strtotime($to_date));
    $to_month = date('m', strtotime($to_date));
    $to_day = date('d', strtotime($to_date));
    if(!empty($time_type))
    {
        switch ($time_type)
        {
            case 'weekly':
                $days_of_week = explode(',', $time);
                if(!empty ($days_of_week) && is_array($days_of_week))
                {
                    foreach ($days_of_week as $day_of_week)
                    {
                        if($from_year == $to_year)
                        {
                            if($from_month == $to_month)
                            {
                                $result_date = from_month_equal_to_month($day_of_week, $from_date, $to_date);
                                if(!empty ($result_date))
                                    $dates = array_merge($dates,$result_date);
                            }
                            elseif($to_month > $from_month)
                            {
                                $result_date = from_month_less_to_month($day_of_week, $from_date, $to_date);
                                if(!empty ($result_date))
                                    $dates = array_merge($dates,$result_date);
                            }
                        }
                        if($to_year > $from_year)
                        {
                            $result_date = day_weekly_multi_year($day_of_week, $from_date, $to_date);
                            if(!empty ($result_date))
                                $dates = array_merge($dates,$result_date);
                        }
                    }
                }
                break;
            case 'monthly':
                $days = explode(',', $time);
                if(!empty($days) && is_array($days))
                {
                    foreach ($days as $day_of_month)
                    {
                        if($from_year == $to_year)
                        {
                            for($day = $from_day; $day <=31; $day++)
                            {                               
                                if($day == $day_of_month)
                                {
                                    $date = date('Y-m-d', strtotime($from_year .'-' . $from_month . '-' . $day));                                
                                    $dates = array_merge($dates,array($date));
                                }
                            }
                            for($month = $from_month + 1; $month < $to_month; $month++)
                            {
                                for($day=1; $day<=31; $day++)
                                {
                                    if($day == $day_of_month)
                                    {
                                        $date = date('Y-m-d', strtotime($from_year .'-' . $month . '-' . $day));                                   
                                        $dates = array_merge($dates,array($date));
                                    }
                                }
                            }
                            for($day=1; $day <=$to_day; $day++)
                            {
                                if($day == $day_of_month)
                                {
                                    $date = date('Y-m-d', strtotime($to_year .'-' . $to_month . '-' . $day));
                                    $dates = array_merge($dates,array($date));
                                }
                            }
                        }
                        elseif($to_year > $from_year)
                        {
                            for($day=$from_day; $day <=31; $day++)
                            {
                                if($day == $day_of_month)
                                {
                                    $date = date('Y-m-d', strtotime($from_year .'-' . $from_month . '-' . $day));
                                    $dates = array_merge($dates,array($date));
                                }
                            }
                            for($month = $from_month + 1; $month <=12; $month++)
                            {
                                if($day == $day_of_month)
                                {
                                    $date = date('Y-m-d', strtotime($from_year .'-' . $month . '-' . $day));
                                    $dates = array_merge($dates,array($date));
                                }
                            }
                            for($year = $from_year + 1; $year < $to_year; $year++)
                            {
                                for($month = 1; $month <=12; $month++)
                                {
                                    for($day=1; $day<=31; $day++)
                                    {
                                        if($day == $day_of_month)
                                        {
                                            $date = date('Y-m-d', strtotime($year .'-' . $month . '-' . $day));
                                            $dates = array_merge($dates,array($date));
                                        }
                                    }
                                }
                            }
                            for($month=1; $month<$to_month; $month++)
                            {
                                for($day=1; $day <=31; $day++)
                                {
                                    if($day == $day_of_month)
                                    {
                                        $date = date('Y-m-d', strtotime($to_year .'-' . $month . '-' . $day));
                                        $dates = array_merge($dates,array($date));
                                    }
                                }
                            }
                            for($day=1; $day <=$to_day; $day++)
                            {
                                if($day == $day_of_month)
                                {
                                    $date = date('Y-m-d', strtotime($to_year .'-' . $to_month . '-' . $day));
                                    $dates = array_merge($dates,array($date));
                                }
                            }
                        }
                    }
                }
                break;
            case 'yearly':
                $days = explode(',', $time);
                $date_result = array();
                if(!empty($days) && is_array($days))
                {
                    foreach ($days as $day_of_year)
                    {
                        if($to_year == $from_year)
                        {                            
                            $date = date('Y-m-d', strtotime($to_year . '-' . $day_of_year));                          
                            $date_result = array_merge($date_result,array($date));
                        }
                        elseif($to_year > $from_year)
                        {
                            for($year = $from_year; $year <= $to_year; $year++)
                            {
                                $date = date('Y-m-d', strtotime($year . '-' . $day_of_year));
                                $date_result = array_merge($date_result,array($date));
                            }
                        }
                    }
                }
                if(!empty($date_result))
                {
                    $dates_booking = date_yearly($from_date, $to_date);
                    foreach($date_result as $date)
                    {
                        foreach ($dates_booking as $date_booking)
                        {
                            if($date == $date_booking)
                                $dates = array_merge($dates,array($date));
                        }
                    }
                }
                break;
        }
    }
    sort($dates);
    return $dates;
}
function date_yearly($strDateFrom,$strDateTo)
{
    $dates = array();
    $iDateFrom =mktime(1,0,0,substr($strDateFrom,5,2), substr($strDateFrom,8,2),substr($strDateFrom,0,4));
    $iDateTo=mktime(1,0,0,substr($strDateTo,5,2), substr($strDateTo,8,2),substr($strDateTo,0,4));
    if ( $iDateTo >= $iDateFrom )
    {
        array_push($dates,date('Y-m-d',$iDateFrom));
        while ($iDateFrom<$iDateTo)
        {
            $iDateFrom+=86400;
            array_push($dates,date('Y-m-d',$iDateFrom));
        }
    }
    return $dates;
}
function day_of_weekly($day_of_week, $day, $month, $year)
{
    $date = '';
    if($day < 10)
        $day_str = '0' . $day;
    else $day_str = $day;
    $day_pro =  date('d', strtotime($day_of_week . " " . $year . "-" . $month . '-' . $day));
    if($day_str == $day_pro)
    {
        $date_com  = mktime(0, 0, 0, $month  ,$day_pro , $year);
        $date = date('Y-m-d',$date_com);
    }
    return $date;
}
function day_of_monthly()
{
    
}
function day_weekly_multi_year($day_of_week, $from_date, $to_date)
{
    $dates = array();
    $from_month = date('m', strtotime($from_date));
    $from_year = date('Y', strtotime($from_date));
    $from_day = date('d', strtotime($from_date));
    $to_year = date('Y', strtotime($to_date));
    $to_month = date('m', strtotime($to_date));
    $to_day = date('d', strtotime($to_date));
    if($to_year > $from_year)
    {
        // 
        for($day=$from_day; $day <=31; $day++)
        {
            $date = day_of_weekly($day_of_week, $day, $from_month, $from_year);
            if(!empty($date))
            {
                $dates = array_merge($dates,array($date));
            }
        }
        for($month= $from_month + 1; $month <= 12; $month++) // $from_year
        {
            for($day=1; $day<=31; $day++)
            {
                $date = day_of_weekly($day_of_week, $day, $month, $from_year);
                if(!empty($date))
                {
                    $dates = array_merge($dates,array($date));
                }
            }
        }
        // 
        for($year = $from_year + 1; $year < $to_year; $year++)
        {
            for($month = 1; $month <=12; $month++)
            {
                for($day = 1; $day <=31; $day++)
                {
                    $date = day_of_weekly($day_of_week, $day, $month, $year);
                    if(!empty($date))
                    {
                        $dates = array_merge($dates,array($date));
                    }
                }
            }
        }
        // 
        for($month = 1; $month < $to_month; $month++)
        {
            for($day = 1; $day <=31; $day++)
            {
                $date = day_of_weekly($day_of_week, $day, $month, $to_year);
                if(!empty($date))
                {
                    $dates = array_merge($dates,array($date));
                }
            }
        }
        for($day=1; $day<=$to_day;$day++)
        {
            $date = day_of_weekly($day_of_week, $day, $to_month, $to_year);
            if(!empty($date))
            {
                $dates = array_merge($dates,array($date));
            }
        }
    }
    return $dates;
}
function from_month_less_to_month($day_of_week, $from_date, $to_date)
{
    $dates = array();
    $from_month = date('m', strtotime($from_date));
    $from_year = date('Y', strtotime($from_date));
    $from_day = date('d', strtotime($from_date));
    $to_year = date('Y', strtotime($to_date));
    $to_month = date('m', strtotime($to_date));
    $to_day = date('d', strtotime($to_date));
    for($day = $from_day; $day <=31; $day++)
    {
        $date = day_of_weekly($day_of_week, $day, $from_month, $from_year);
        if(!empty ($date))
            $dates = array_merge($dates,array($date));
    }
    for($month = $from_month + 1; $month < $to_month;$month++)
    {
        for($day = 1; $day<=31;$day++)
        {
            $date = day_of_weekly($day_of_week, $day, $month, $from_year);
            if(!empty ($date))
            $dates = array_merge($dates,array($date));
        }
    }
    for($day=1; $day<=$to_day;$day++)
    {
        $date = day_of_weekly($day_of_week, $day, $to_month, $to_year);
        if(!empty ($date))
        $dates = array_merge($dates,array($date));
    }
    return $dates;
}
function from_month_equal_to_month($day_of_week, $from_date, $to_date)
{
    $dates = array();
    $from_month = date('m', strtotime($from_date));
    $from_year = date('Y', strtotime($from_date));
    $from_day = date('d', strtotime($from_date));
    $to_year = date('Y', strtotime($to_date));
    $to_month = date('m', strtotime($to_date));
    $to_day = date('d', strtotime($to_date));
    for($day = $from_day; $day <= $to_day;$day++)
    {
        $date = day_of_weekly($day_of_week, $day, $from_month, $from_year);
        if(!empty ($date))
            $dates = array_merge($dates,array($date));
    }
    return $dates;
}
function tgt_limit_content($str, $length) {
	$str = strip_tags($str);
	$str = explode(" ", $str);
	return implode(" " , array_slice($str, 0, $length));
}
function tgt_calculate_alternal_price($first_price) {
	if(get_option('tgt_allow_alternal_currency',true) == '1')
	{
		$alternal_array = get_option('tgt_alternal_currency');
		$alternal_price = $alternal_array['currency_rating']*$first_price;
		$symbol = $alternal_array['symbol'];
		if($alternal_array['symbol'] == '')
		{
			$symbol = $alternal_array['currency'];
		}
		if($alternal_array['position'] == '1')
		{
			$alternal_price = $symbol.$alternal_price;
		}elseif($alternal_array['position'] == '2')
		{
			$alternal_price = $symbol.' '.$alternal_price;
		}else if($alternal_array['position'] == '3')
		{
			$alternal_price = $alternal_price.$symbol;
		}else if($alternal_array['position'] == '4')
		{
			$alternal_price = $alternal_price.' '.$symbol;
		}
		return '( '.$alternal_price.' )';
	}
}
// Transaction function 
function get_transactions ( $page = 1, $customer, $booking, $date, $amount, $currency, $date_from, $date_to ) {
	global $wpdb;
	$tran_per_page = 100;
	$offset = ( $page -1 )* $tran_per_page;
	$where_date = '';
	$where_date_from = '';
	$where_date_to = '';
	$where_currency = '';
	$where_customer = '';
	
	if ( $date != '') {
		$where_date = " AND (DATE( t.date ) = DATE ('".$date."'))";
	}
	if ( $date_from != '' ){
		$where_date_from = " AND (DATE(t.date) >= '".date('Y-m-d',strtotime($date_from))."' )";
	}
	if ( $date_to != '') {
		$where_date_to = " AND ( DATE (t.date) <= '".date('Y-m-d',strtotime($date_to))."' )";
	}
	if ( $currency != '' ) {
		$where_currency = " AND t.currency LIKE '".$currency."'";
	}
	if ( $customer != ''){
		$where_customer = " AND t.customer_id = '".$customer."'";
	}
	 $all_transaction =  "SELECT t.booking_id, DATE(t.date) as date, t.amount, t.currency, t.ID, t.customer_id
                        FROM ". $wpdb->prefix . "transaction as t
                            LEFT JOIN ". $wpdb->prefix ."users as u ON t.customer_id = u.ID
                        WHERE 1=1
                            $where_date_from
                            $where_date_to
                            $where_date
                            $where_customer
                            $where_currency
                        LIMIT $offset,".$tran_per_page."
                        ";
        return $wpdb->get_results($all_transaction);       
}

/// Insert Transaction table -----------------------------------------
function doInsert($arr)
{
  	if(!empty($arr))
    {
       if( $arr['booking_id'] == '' || $arr['customer_id'] == '' || $arr['date'] == '' || $arr['amount'] == '' || $arr['currency'] == '')
          return false;
            
       global $wpdb;
       $new_trans ='';
       $flag = 0;
       $table = 'transaction'; 
       $sql = "SELECT * FROM ".$wpdb->prefix.$table;
       $transactions = $wpdb->get_results($sql);
       foreach ( $transactions as $transaction ){  
	       	if( $arr['customer_id'] ==  $transaction->customer_id) {
	       		$flag = 1;
	       	}
       }
       if( $flag == 0) {
       	   $new_trans = $wpdb->insert( $wpdb->prefix.$table,$arr); 
       }    
       if($new_trans == '')
           return false;
       else if($new_trans != '')
          return true;
      }else
         return false;
}

// update Transaction log
function doUpdate ($arr) {
	if ( !empty ($arr) ){
	   if( $arr['booking_id'] == '' || $arr['customer_id'] == '' || $arr['date'] == '' || $arr['amount'] == '' || $arr['currency'] == '')
          return false;
       global $wpdb;
       $new_trans ='';
       $table = 'transaction';
       $sql = "SELECT * FROM ".$wpdb->prefix.$table;
       $transactions = $wpdb->get_results($sql);
       foreach ( $transactions as $transaction ){
	       if( $arr['customer_id'] ==  $transaction->customer_id) {
	       	$q = "UPDATE ".$wpdb->prefix.$table." SET customer_id = '".$arr['customer_id']."',"
	       																	."booking_id = '".$arr['booking_id']."',"
	       																	."date = '".date('Y-m-d',strtotime($arr['date']))."',"
	       																	."amount = '".$arr['amount']."',"
	       																	."currency = '".$arr['currency']
	       					."' WHERE ID = '".$transaction->ID."'";
	       		$result = $wpdb->query($q);
	       }
       }
       if ( isset ($result) && $result == 0){
       	  return false;
       }
       else if (isset ($result) && $result == 1){
       	  return true;
       }
	}else 
		return false;
}
// delete transaction data
function doDelete($arr_id)
    {
        if(!empty($arr_id))
        {
            global $wpdb;
            $table = 'transaction';             
            $result = '';
            if(is_array($arr_id) && count($arr_id) > 0)
            {                
                foreach($arr_id as $k=>$v)
                {
                    $result = $wpdb->query("DELETE FROM ".$wpdb->prefix.$table." WHERE ID = ($v + 0)");	
                }               
            }else
                $result = $wpdb->query("DELETE FROM ".$wpdb->prefix.$table." WHERE ID = ($arr_id + 0)");
            if($result == 0)    
                return false;
            else if($result == 1)
                return true;
        }else
            return false;
    }
function display_number($numrows=0)
{
	if(strlen($numrows)<=3)
		return $numrows;
	$numrows_str= "";
	$numrows_arr= array();
	if($numrows>0)
	{
		for($k=0; $k<strlen($numrows); $k++)
			$numrows_arr[]= substr($numrows,$k,1);
		$i= 0;
		for($k=count($numrows_arr); $k>=0; $k--)
		{
			if($i!=0 && $i%3==0)
				$numrows_str.= $numrows_arr[$k]."|".","."|";
			else
				$numrows_str.= $numrows_arr[$k]."|";
			$i++;
		}
	}
	$numrows_arr_tmp= explode("|",substr($numrows_str,1,(strlen($numrows_str)-1)));
	$numrows_arr= array();
	for($k=count($numrows_arr_tmp)-1; $k>=0; $k--)
		$numrows_arr[]= $numrows_arr_tmp[$k];
	return implode("",$numrows_arr);
}

function hotel_title($sep, $display = true, $seplocation = 'right' )
{
	global $wp_rewrite, $wp_query;
	$action = '';
	$new_title = '';
	
	if ( isset( $_GET['action'] ) && $_GET['action'] == 'ajax' )
		$action = $_GET[ 'action' ];
	else
	{
		if ( $wp_rewrite->using_permalinks() )
		{
			$action = get_query_var('action');
		}
		else {
			$action = $_GET[ 'action' ];
		}
	}
	if ( !empty($action) )
	{
	
		switch ( $action )
		{
			case 'check' :
				$new_title = __('Check your reservation','hotel');
				break;
			case 'photo-gallery' :
				$new_title = __('Photo gallery','hotel');
				break;
			case 'location' :
				$new_title = __('Hotel location','hotel');
				break;
			case 'service' :
				$new_title = __('Hotel services','hotel');
				break;
			case 'contact-us' :
				$new_title = __('Contact us','hotel');
				break;				
			case 'roomtypes' :
				$new_title = __('Room Types','hotel');
				break;
			case 'booking_payment':
				$new_title = __('Payment','hotel');
				break;
			case 'payment_submit':
				$new_title = __('Payment submit','hotel');
				break;
			case 'booking_option':
				$new_title = __('Reservation Option','hotel');
				break;
			case 'Tpaypal':
				$new_title = __('Payment Success','hotel');
				break;
			case 'Fpaypal':
				$new_title = __('Payment Failed','hotel');
				break;
		}
		// generate title
		//$title = apply_filters( 'wp_title', $new_title, $sep , $seplocation);
		if ( 'right' == $seplocation )
			$new_title .= ' ' . $sep . ' ';
		else
			$new_title = $sep . ' ' . $new_title;
			
		// send it out
		echo $new_title;
	}
	else
	{
		wp_title($sep, $display, $seplocation);
	}
}

function tgt_get_link($link = '')
{
	global $wp_rewrite;
	$arr_links = array(
				'check' => array(
									  'normal' => '?action=check',
									  'permalink' => '/check'
									  ),
				'roomtypes' => array(
									  'normal' => '?action=roomtypes',
									  'permalink' => '/roomtypes'
									  ),
				'booking_payment' => array(
									  'normal' => '?action=booking_payment',
									  'permalink' => '/booking_payment'
									  ),
				'Tpaypal' => array(
									  'normal' => '?action=Tpaypal',
									  'permalink' => '/Tpaypal'
									  ),
				'Fpaypal' => array(
									  'normal' => '?action=Fpaypal',
									  'permalink' => '/Fpaypal'
									  ),
				'print' => array(
									  'normal' => '?action=print',
									  'permalink' => '/print'
									  ),
				);
	
	if ( $wp_rewrite->using_permalinks() )
		return HOME_URL . $arr_links[$link]['permalink'];
	else
		return HOME_URL . $arr_links[$link]['normal'];
}

function get_promotion($price, $from_date, $coupon_code='')
{
    $final_price = 0;
    $promotion_date = get_option(PROMOTION_DATE);
    $promotions = get_option(BOOKING_PROMOTION);
    $coupons = array();
    $saleoffs = array();
    $coupon_id = 0;
    $saleoff_id = 0;
    $date_now = strtotime(date('Y-m-d'));
    if(!empty($promotions))
    {
        foreach ($promotions as $promotion)
        {
            if($promotion_date == '0')
            {
                if(!empty($promotion['code']) && strtotime($promotion['start_date']) <= $date_now && $date_now <= strtotime($promotion['end_date']) && $promotion['activated'] == '1') // coupon
                    $coupons[] = $promotion;
                elseif(empty($promotion['code']) && strtotime($promotion['start_date']) <= $date_now && $date_now <= strtotime($promotion['end_date'])  && $promotion['activated'] == '1') // saleoff
                    $saleoffs[] = $promotion;
            }
            elseif($promotion_date == '1')
            {
                if(!empty($from_date))
                {
//                    $date_checkin = strtotime ($from_date);
                    $date_checkin = $from_date;
                    if(!empty($promotion['code']) && strtotime($promotion['start_date']) <= $date_checkin && $date_checkin <= strtotime($promotion['end_date']) && $promotion['activated'] == '1') // coupon
                        $coupons[] = $promotion;
                    elseif(empty($promotion['code']) && strtotime($promotion['start_date']) <= $date_checkin && $date_checkin <= strtotime($promotion['end_date'])  && $promotion['activated'] == '1') // saleoff
                        $saleoffs[] = $promotion;
                }
            }
        }
    }
    if(!empty($coupons) && !empty ($coupon_code))
    {
        foreach ($coupons as $coupon)
        {
            if($coupon['promotion_type'] == '1' && $coupon['code'] == $coupon_code && $coupon['used'] < $coupon['quanlity']) // percent
            {
                $final_price += $price * $coupon['amount'] / 100;                  
                $coupon_id = $coupon['ID'];
                break;
            }
            elseif($coupon['promotion_type'] == '2'  && $coupon['code'] == $coupon_code && $coupon['used'] < $coupon['quanlity']) // exact amount
            {
                $final_price += $coupon['amount'];
                $coupon_id = $coupon['ID'];
                break;
            }
        }
    }
    if(!empty($saleoffs))
    {
        $saleoff = end($saleoffs);

        if($saleoff['promotion_type'] == '1' && $saleoff['used'] < $saleoff['quanlity']) // percent
        {
            $final_price += $price * $saleoff['amount'] / 100;
            $saleoff_id = $saleoff['ID'];
        }
        elseif($saleoff['promotion_type'] == '2' && $saleoff['used'] < $saleoff['quanlity']) // exact amount
        {
            $final_price += $saleoff['amount'];
            $saleoff_id = $saleoff['ID'];
        }
    }
    if($final_price < 0) $final_price = 0;
    $result = array();
    $result[] = $final_price;
    $result[] = implode(',', array($coupon_id, $saleoff_id));
    return $result;
}
function update_promotion_used($user_id, $status='')
{
    $promotions = get_option(BOOKING_PROMOTION);
    $promotion_used = get_user_meta($user_id, META_USER_PROMOTION, true);
    if(is_array($promotion_used))
        $promotion_used = implode (',', $promotion_used);
    if(!empty($status) && $status == 'publish' && !empty($promotions) && !empty($promotion_used))
    {
        $user_promotions = explode(',', $promotion_used);
        if(!empty ($user_promotions) && is_array($user_promotions))
        {
            foreach ($user_promotions as $user_promotion_id)
            {
                foreach ($promotions as $key=>$promotion)
                {
                    if($promotion['ID'] == $user_promotion_id)
                    {
                        $promotions[$key]['used'] += 1;
                        if($promotions[$key]['used'] > $promotions[$key]['quanlity']) $promotions[$key]['used'] = $promotions[$key]['quanlity'];
                        $flag = 1;
                    }
                }
            }
            if($flag == 1)
            {
                update_option(BOOKING_PROMOTION, $promotions);
                update_user_meta($user_id, META_USER_PROMOTION, '');
            }
        }
    }
}

function get_language_switcher()
{
	global $sitepress, $post;
	// get languages
	$using_wpml = get_option('tgt_using_wpml');
	$language_links = array();
	if ( $using_wpml && method_exists( $sitepress, 'language_url' ) )
	{
		$langs = icl_get_languages( 'skip_missing=0&orderby=code&order=asc' );
		foreach( $langs as $code => $lang )
		{
			$link = '';			
			$default = $sitepress->get_default_language();
			parse_str( $_SERVER['QUERY_STRING'] , $queries);
			$queries['lang'] = $code;
			
			
			if ($default == $code)
				unset( $queries['lang'] );
			$link = http_build_query( $queries );
			
			$base_url = '';
			
			$page_id = icl_object_id($post->ID, 'page', true, $code );
			
			if ( is_page() || is_single() ) {
				$link = get_permalink( $page_id );
			}
			else {
				$base_url =  empty( $_SERVER['REDIRECT_URL'] ) ? HOME_URL : $_SERVER['REDIRECT_URL'] ;
				if ( !empty($link) )
					$link = $base_url . "?" . $link;
				else
					$link = $base_url ;
			}
			
			$language_links[$code] = array(
													'tranlated_name' => $lang['translated_name'],
													'native_name' => $lang['native_name'],
													'flag_link' => $sitepress->get_flag_url($code),
													'link' => $link, //$sitepress->language_url($code),
													'code' => $sitepress->get_language_code($lang['native_name'])
													);
		}
	}
	else
	{
		$path 			= TEMPLATEPATH.'/lang';
		$langs 			= get_available_languages( $path );
		$langs_name 	= get_option('tgt_languages_name');
		foreach( (array)$langs_name as $lang => $lang_arr )
		{
			$language_links[$lang] = array(
				'tranlated_name' => $lang_arr['name'],
				'native_name' => $lang_arr['name'],
				'flag_link' => TEMPLATE_URL . '/images/flags/' .  $lang_arr['flag'],
				'link' => HOME_URL . '?switch_lang=' . $lang
			);
		}
	}
	?>
	
	<ul>
		<?php foreach( $language_links as $code => $link ) { ?>
			<li>
				<a href="<?php echo $link['link'] ?>">
					<?php
						if ( $link['flag_link'] )
							echo '<img src="'. $link['flag_link'] .'" alt="'. $link['tranlated_name'] .'" title="'. $link['tranlated_name'] .'" />';
						else
							echo $link['tranlated_name'];
					?>
				</a>
			</li>
		<?php } ?>
	</ul>
	
	<?php
	
	//echo '<pre>';
	//print_r( $language_links );
	//echo '</pre>';
}

function menu_test()
{	
	//var_dump($page);
		
	//wp_get_nav_menu_object('header_menu');
	//$menu = wp_get_nav_menu_items('header_menu');
	//
	//wp_update_nav_menu_item(  )
	//
	//echo '<pre>';
	//print_r ( $menu );
	//echo '</pre>';
	//wp_update_nav_menu_item(  )
}

function tgt_change_hotel_lang($lang)
{
	$path = TEMPLATEPATH . '/lang/';
	$fullpath = $path . $lang .'.mo';
	
	if ( file_exists( $fullpath ) )
	{
		$_COOKIE['default_lang'] = $lang;
	}
}

/**
 * get link with language 
 */
function tgt_get_page_link($link)
{
	global $wp_rewrite;
	$lang = empty( $_GET['lang'] )? "" :  'lang=' . $_GET['lang'];
	$return = array(
						'check' => array(
												'normal' => HOME_URL . '/?action=check',
												'permalink' => HOME_URL . '/check'
												),
						'booking_payment' => array(
												'normal' => HOME_URL . '/?action=booking_payment',
												'permalink' => HOME_URL . '/booking_payment'
												),
						'booking_option' => array(
												'normal' => HOME_URL . '/?action=booking_option',
												'permalink' => HOME_URL . '/booking_option'
												),
						'payment_success' => array(
												'normal' => HOME_URL . '/?action=payment_success',
												'permalink' => HOME_URL . '/payment_success'
												),
						'payment_false' => array(
												'normal' => HOME_URL . '/?action=payment_false',
												'permalink' => HOME_URL . '/payment_false'
												),
						'print' => array(
												'normal' => HOME_URL . '/?action=print',
												'permalink' => HOME_URL . '/print'
												),
						'payment_submit' => array(
												'normal' => HOME_URL . '/?action=payment_submit',
												'permalink' => HOME_URL . '/payment_submit'
												),
						'search' => array(
												'normal'	=> HOME_URL . '/?s=Check-Rooms',
												'permalink'	=> HOME_URL . '/?s=Check-Rooms',
												),
						'roomtypes' => array(
												'normal'	=> HOME_URL . '/?action=roomtypes',
												'permalink'	=> HOME_URL . '/roomtypes',
												)
						);
	//var_dump($link );
	if ( isset($return[$link] ) )
	{
		if ( $wp_rewrite->using_permalinks() && $link != 'search' )
			if ( empty($lang) )
				return $return[$link]['permalink'];
			else 
				return $return[$link]['permalink'] . '?' . $lang;
		else
			if ( empty($lang) )
				return $return[$link]['normal'];
			else 
				return $return[$link]['normal'] . '&' . $lang;
	}
	return false;
}