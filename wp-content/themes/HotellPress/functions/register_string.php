<?php
if ( function_exists( 'icl_register_string' ) ) {
 global $sitepress_settings, $sitepress;
                //example
				//icl_register_string('String','string - ' . md5('i need you'), 'i need you');

$widget_content = get_option('widget_content');
	if(is_array($widget_content)){
		foreach($widget_content as $k=>$w){
			if(!empty($w) && isset($w['title'])){
				icl_register_string('Widgets', 'widget body - ' . md5(apply_filters('widget_content',$w['content'])), apply_filters('widget_content',$w['content']));
				icl_register_string('Widgets', 'widget sub - ' . md5(apply_filters('widget_content',$w['titlesub'])), apply_filters('widget_content',$w['titlesub']));
			}
		}
	}

$widget_testimonial = get_option('widget_testimonial');
	if(is_array($widget_testimonial)){
		foreach($widget_testimonial as $k=>$w){
			if(!empty($w) && isset($w['title'])){
				icl_register_string('Widgets', 'widget sub - ' . md5(apply_filters('widget_testimonial',$w['titlesub'])), apply_filters('widget_testimonial',$w['titlesub']));
			}
		}
	}
 
 
add_filter('widget_content', 'icl_sw_filters_widget_content');
add_filter('widget_content', 'icl_sw_filters_widget_content_title_sub');
add_filter('widget_testimonial', 'icl_sw_filters_widget_testimonial');

function icl_sw_filters_widget_content_title_sub($val){    
    $val = icl_t('Widgets', 'widget sub - ' . md5($val) , $val);   
    return $val;
}

function icl_sw_filters_widget_content($val){    
    $val = icl_t('Widgets', 'widget body - ' . md5($val) , $val);   
    return $val;
}
function icl_sw_filters_widget_testimonial($val){    
    $val = icl_t('Widgets', 'widget sub - ' . md5($val) , $val);    
    return $val;
}

add_action('update_option_widget_text', 'icl_st_update_text_widgets_actions2', 5, 2);  
                
function icl_st_update_text_widgets_actions2($old_options, $new_options){
    global $sitepress_settings;
    
    // remove filter for showing permalinks instead of sticky links while saving
    $GLOBALS['__disable_absolute_links_permalink_filter'] = 1;
    
    
    
			$widget_content = get_option('widget_content');				
			
                if(is_array($widget_content)){
                    foreach($widget_content as $k=>$w){
	                    if(isset($old_options[$k]['content']) && trim($old_options[$k]['content']) && $old_options[$k]['content'] != $w['content']){
			                icl_st_update_string_actions('Widgets', 'widget body - ' . md5(apply_filters('widget_content', $old_options[$k]['content'])), apply_filters('widget_content', $old_options[$k]['content']), apply_filters('widget_content', $w['content']));
			            }elseif($new_options[$k]['text'] && $old_options[$k]['text']!=$new_options[$k]['text']){
			                icl_register_string('Widgets', 'widget body - ' . md5(apply_filters('widget_content', $new_options[$k]['text'])), apply_filters('widget_content', $new_options[$k]['content']));
			            }
			            
	                    if(isset($old_options[$k]['titlesub']) && trim($old_options[$k]['titlesub']) && $old_options[$k]['titlesub'] != $w['titlesub']){
			                icl_st_update_string_actions('Widgets', 'widget body - ' . md5(apply_filters('widget_content', $old_options[$k]['titlesub'])), apply_filters('widget_content', $old_options[$k]['titlesub']), apply_filters('widget_content', $w['titlesub']));
			            }elseif($new_options[$k]['text'] && $old_options[$k]['titlesub']!=$new_options[$k]['titlesub']){
			                icl_register_string('Widgets', 'widget sub - ' . md5(apply_filters('widget_content', $new_options[$k]['titlesub'])), apply_filters('widget_content', $new_options[$k]['titlesub']));
			            }  
		                
                    }
                }

                $widget_testimonial = get_option('widget_testimonial');               
                if(is_array($widget_testimonial)){
                    foreach($widget_testimonial as $k=>$w){
	                    if(isset($old_options[$k]['titlesub']) && trim($old_options[$k]['titlesub']) && $old_options[$k]['titlesub'] != $w['titlesub']){
			                icl_st_update_string_actions('Widgets', 'widget body - ' . md5(apply_filters('widget_testimonial', $old_options[$k]['titlesub'])), apply_filters('widget_testimonial', $old_options[$k]['titlesub']), apply_filters('widget_testimonial', $w['titlesub']));
			            }elseif($new_options[$k]['titlesub'] && $old_options[$k]['titlesub']!=$new_options[$k]['titlesub']){
			                icl_register_string('Widgets', 'widget sub - ' . md5(apply_filters('widget_testimonial', $new_options[$k]['titlesub'])), apply_filters('widget_testimonial', $new_options[$k]['titlesub']));
			            }                    	
                    	                     	
                    }
                }
    
    // add back the filter for showing permalinks instead of sticky links after saving
    unset($GLOBALS['__disable_absolute_links_permalink_filter']);
}
}





?>