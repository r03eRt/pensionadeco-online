<?php
add_action('do_ajax','ajax_option_process');

function ajax_option_process(){
	global $wpdb;
	$response = '';	
	$name = $_POST['name'];
	if (function_exists('icl_register_string')) { 
   		icl_register_string('Services', md5($name), $name);
    }
	$default_price = $_POST['default_price'];	
	$option_list = options::getInstance();
	$args = array(
		'name' => $name, 
		'default_price' => $default_price
		);	
		$option_list->insert($args);	
		$content = '';
	 
           
          		$arr_options = $option_list->getOptions();          		
					if(isset($arr_options) && is_array($arr_options) && !empty($arr_options)){				
					foreach ($arr_options as $key => $list_options) { 
          	  
            $content .= '<tr id="content-type" class="">';
            $content .= '<th class="check-column" scope="row">';
             $content .= '<input type="checkbox" value="'. $key.'" name="cbkeys[]"/>';
              $content .= '</th>';
                $content .= '<td class="name column-name">';
                  $content .= '<strong>';
                      $content .= '<a class="row-title" title="Edit &ldquo;'. $list_options['name'].'&rdquo;" href="'. get_bloginfo('wpurl').'/wp-admin/admin.php?page=admin-product-manager-handle&edit-option=true&id-edit='.$key.'">'. $list_options['name'].'</a></strong><br />';
                    $content .= '<div class="row-actions">';                     
                     
                    $content .= '<span class="edit"><a href="'.get_bloginfo('wpurl').'/wp-admin/admin.php?page=my-submenu-manager-option&edit-option=true&id-edit='.$key.'">'.__('Edit','hotel').'</a> | </span>';                    
                    
                    $content .= '<span class="delete"><a href="javascript:del_option('. $key.')">'. __('Delete','hotel').'</a></span>';
                  $content .= '</div>';
                $content .= '</td>';
               
                $content .= '<td class="manage-column column-description">';
                    $content .=  $list_options['default_price']; 
                $content .= '</td>';
            $content .= '</tr>';
           } } 
	
	
	
	header('HTTP/1.1 200 OK');
	header('Content-Type: application/json'); 
 	
	$response = json_encode(array('success' => true, 'content' => $content,'message' => 'success'));  
	
	echo $response;   
	exit;
}
?>