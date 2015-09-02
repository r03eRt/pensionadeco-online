<?php
add_action('do_ajax','ajax_pricing_time');

function ajax_pricing_time(){
    $response = '';
    $message = '';
    if (!empty($_POST)){
        $room_type_ids = $_POST['room_type_id'];
        $checked = $_POST['ischecked'];
        $price_input = $_POST['price'];
        $id = isset ($_POST['id']) ? $_POST['id'] : '0';
        $room_types = explode(',', $room_type_ids);
        $prices = array();
        $$price_save = array();
        if(!empty($price_input))
        {
            $temp = explode('-', $price_input);
            if(is_array($temp))
            {
                foreach ($temp as $values)
                {
                    $price_room_type = explode(',', $values);
                    $price_save[$price_room_type[0]] = array();
                    for($i=0;$i<count($price_room_type)-1;$i++)
                    {
                        $price_save[$price_room_type[0]] = array_merge($price_save[$price_room_type[0]], array($price_room_type[$i+1]));
                    }
                }
            }
        }
        
        if(!empty($room_types))
        {
            if($checked == '1')
            {
                foreach ($room_types as $room_type_id)
                {
                    $price_tmp = $price_save[$room_type_id];
                    $room_type_cap_prices = get_post_meta($room_type_id, META_ROOMTYPE_CAP_PRICE, true);
                    $room_type_name = get_the_title($room_type_id);
                    if(!empty($room_type_cap_prices))
                    {
                        $message .= '<div><b>' . $room_type_name . '</b>';
                        $message .= '<table width="50%" style="margin-left: 20px;">';
                        if($id > 0)
                        {
                            global $wpdb;
                            $sql = "SELECT new_price_change FROM " . $wpdb->prefix . 'pricing' . " WHERE ID='" . $id . "'" ;
                            $result = $wpdb->get_results($sql);                            
                            if(!empty ($result))
                            {
                                $results = explode(',', $result[0]->new_price_change);
                                foreach ($results as $r)
                                {
                                    $prices[] = $r;
                                }
                            }
                        }
                        $count = 0;
                        foreach ($room_type_cap_prices as $room_type_cap_price)
                        {
                            $p = '';
                            $price = '';
                            if(isset ($price_tmp[$count]) && $price_tmp[$count] > 0)
                                $p = $price_tmp[$count];
                            if(isset ($prices[$count]) && $prices[$count] > 0)
                                $price = $prices[$count];
                            else $price = $room_type_cap_price['price'];
                            $original_price = $room_type_cap_price['price'];
                            if(empty ($p))
                                $p = $price;
                            $message .= '&nbsp;<tr>';
                            if($count > 0)
                                $message .= '<td><b>' . ($count + 1). __( ' persons :','hotel') . '</b></td>';
                            else
                                $message .= '<td><b>' . ($count + 1 ). __( ' person :','hotel') . '</b></td>';
                            $message .= '<td><label>'.  __('Original price','hotel') .$room_type .'</label></td>';
                            $message .= '<td><input type="text" readonly="readonly" value="' . $original_price . '"></td>';
                            $message .= '<td><label>'.  __('New price', 'hotel') .$room_type .'</label></td>';
                            $message .= '<td><input type="text" value="' . $p .'" id="room_type_cap_price_' . $room_type_id . '_' . $count .  '" name="room_type_cap_price_'. $room_type_id . '[' . $count . ']"' . '</td>';
                            $message .= '</tr>';
                            $count++;
                        }
                         $message .= '</table>';
                        $message .= '</div><br><br>';
                    }
                }
            }
            else $message = '';
            $success = true;
            header('HTTP/1.1 200 OK');
            header('Content-Type: application/json');
            if ($success){
                $response = json_encode(array('success' => true, 'message' => $message));
            }
            else
                $response = json_encode(array('success' => false, 'message' => 'Failed' ));
        }
    }
    echo $response;
    exit;
}
?>