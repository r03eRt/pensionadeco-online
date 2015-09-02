<?php
$data = array();
$time = array();
$errors = array();
$room_types_cap_price = array();
$message = '';
$id = isset ($_GET['id']) ? $_GET['id'] : '0';

if(isset ($_POST['save_pricing']))
{    
    $data['time_type'] = isset ($_POST['time']) ? $_POST['time'] : '';
    if(!empty ($data['time_type']))
    {
        switch ($data['time_type'])
        {
            case 'weekly':
                $time['monday'] = isset ($_POST['monday']) ? $_POST['monday'] : '0';
                $time['tuesday'] = isset ($_POST['tuesday']) ? $_POST['tuesday'] : '0';
                $time['wednesday'] = isset ($_POST['wednesday']) ? $_POST['wednesday'] : '0';
                $time['thusday'] = isset ($_POST['thusday']) ? $_POST['thusday'] : '0';
                $time['friday'] = isset ($_POST['friday']) ? $_POST['friday'] : '0';
                $time['saturday'] = isset ($_POST['saturday']) ? $_POST['saturday'] : '0';
                $time['sunday'] = isset ($_POST['sunday']) ? $_POST['sunday'] : '0';
                break;
            case 'monthly':
                $time = $_POST['monthly'];
                break;
            case 'yearly':
                $time['from_month'] = isset ($_POST['from_month']) ? $_POST['from_month'] : '';
                $time['from_day'] = isset ($_POST['from_day']) ? $_POST['from_day'] : '';
                $time['to_month'] = isset ($_POST['to_month']) ? $_POST['to_month'] : '';
                $time['to_day'] = isset ($_POST['to_day']) ? $_POST['to_day'] : '';
                break;
        }        
    }
    $data['romm_type'] = isset ($_POST['check_room_type']) ? $_POST['check_room_type'] : '';
    $data['priority'] = isset ($_POST['priority']) ? $_POST['priority'] : '0';    
    $data['disable_pricing'] = isset ($_POST['disable_pricing']) ? $_POST['disable_pricing'] : '0';
    $data['date_start'] = isset ($_POST['date_start']) ? $_POST['date_start'] : '';
    $room_types_changed = isset ($_POST['room_types_changed']) ? $_POST['room_types_changed'] : '';
    $data['room_types_changed_price'] = array();      
    if(!empty($room_types_changed))
    {        
        foreach ($room_types_changed as $room_type_id)
        {
            if(!empty ($room_type_id) || $room_type_id > 0)
            {
                $data['room_types_changed_price'][$room_type_id] = isset ($_POST['room_type_cap_price_' . $room_type_id]) ? $_POST['room_type_cap_price_' . $room_type_id] : '';
                if(!empty ($data['room_types_changed_price'][$room_type_id]) && is_array($data['room_types_changed_price'][$room_type_id]))
                {
                    $count = 1;
                    $error_data = 0;
                    foreach ($data['room_types_changed_price'][$room_type_id] as $each_price)
                    {
                        if(empty($each_price))
                        {
                            if($count > 1)
                                $errors[] = __('ERROR: Please enter price for ', 'hotel') . get_the_title($room_type_id) . ' room ' . $count . __(' persons ', 'hotel');
                            else
                                $errors[] = __('ERROR: Please enter price for ', 'hotel') . get_the_title($room_type_id) . ' room ' . $count . __(' person ', 'hotel');
                            $count++;
                            $error_data += 1;
                        }
                    }
                    if($error_data == 0)
                    {
                        $room_types_cap_price[$room_type_id] = implode(',', $data['room_types_changed_price'][$room_type_id]);
                    }
                }
            }
        }
    }
    
    if(empty ($time) || validate_time($time, $data['time_type']) == '0')
        $errors[] = __('ERROR: Please select a specific time.', 'hotel');
//    if(validate_time($time, $data['time_type']) == '0')
//        $errors[] = __('ERROR: From Time must less than To Time','hotel');
    if(empty ($data['romm_type']))
        $errors[] = __('ERROR: Please select at least one room type.', 'hotel');
    if(empty ($data['priority']))
        $errors[] = __('ERROR: Please select a priority.', 'hotel');
//     if($data['disable_pricing'] != '0' || $data['disable_pricing'] != '1')
//        $errors[] = __('ERROR: Please select disable value.', 'hotel');
    if(empty ($data['date_start']))
        $errors[] = __('ERROR: Please enter start date.', 'hotel');
   
    // insert to database    
    if(empty ($errors))
    {
        $date = date('Y-m-d', strtotime($data['date_start']));
        $data['date_start'] = $date;
        $str_time = compute_time($time, $data['time_type']);
        global $wpdb;
        $table_name = $wpdb->prefix . 'pricing';
        $result = 0;
        $sql = '';
//         $sql = "INSERT INTO `" . $table_name . "` (`time_type`,`time`,`room_type_id`,`new_price_change`,`priority`,`date_start`, `disable`)
//                        VALUES('" . $data['time_type'] . "','". $str_time ."','" . $room_type_id . "','" . $room_types_cap_price[$room_type_id] . "','" . $data['priority'] . "','" . $data['date_start'] . "','" . $data['disable_pricing']. "')";
       
       
        foreach ($room_types_changed as $room_type_id)
        {
            if(isset ($room_types_cap_price[$room_type_id]) && !empty ($room_types_cap_price[$room_type_id]))
            {
                if($id > 0)
                {                    
                    $sql = "UPDATE `" . $table_name . "` SET `time_type` = '" . $data['time_type'] . "', `time` = '". $str_time ."' ,`room_type_id` = '".$room_type_id."',`new_price_change` = '".$room_types_cap_price[$room_type_id]."',`priority` = '".$data['priority']."',`date_start`='".$data['date_start']."', `disable`='" .$data['disable_pricing']. "' WHERE ID='" . $id ."'";
                    $result = $wpdb->query($sql);
                    $result = 1;
                }
                else
                {
                    $sql = "INSERT INTO `" . $table_name . "` (`time_type`,`time`,`room_type_id`,`new_price_change`,`priority`,`date_start`, `disable`)
                        VALUES('" . $data['time_type'] . "','". $str_time ."','" . $room_type_id . "','" . $room_types_cap_price[$room_type_id] . "','" . $data['priority'] . "','" . $data['date_start'] . "','" . $data['disable_pricing']. "')";
                    $result += $wpdb->query($sql);
                }
            }
        }
        if($result > 0)
        {
            $message = __('Your settings have been saved !','hotel');
            wp_redirect( HOME_URL . '/wp-admin/admin.php?page=my-submenu-list-pricing&message=success');
        }
        else $message = __('Sorry, can not save your settings!','hotel');
    }
}
function compute_time($time = array(), $time_type='')
{
    $result = '';
    if(!empty ($time_type))
    {
        switch ($time_type)
        {
            case 'weekly':
                $times = array();
                if(isset ($time['monday']) && $time['monday'] == '1')
                   $times[] = 'Monday';
                if(isset ($time['tuesday']) && $time['tuesday'] == '1')
                    $times[] = 'Tuesday';
                if(isset ($time['wednesday']) && $time['wednesday'] == '1')
                    $times[] = 'Wednesday';
                if(isset ($time['thusday']) && $time['thusday'] == '1')
                    $times[] = 'Thursday';
                if(isset ($time['friday']) && $time['friday'] == '1')
                    $times[] = 'Friday';
                if(isset ($time['saturday']) && $time['saturday'] == '1')
                    $times[] = 'Saturday';
                if(isset ($time['sunday']) && $time['sunday'] == '1')
                    $times[] = 'Sunday';
                if(!empty($times))
                    $result = implode (',', $times);
                break;
            case 'monthly':
                if(!empty($time))
                    $result = implode (',', $time);               
                break;
            case 'yearly':
                $yearly = array();
                if(isset ($time['from_month']) && isset ($time['from_day']) && isset ($time['to_month']) && isset ($time['to_day']))
                {
                    if($time['from_month'] == $time['to_month'])
                    {
                        for($day= $time['from_day']; $day <= $time['to_day']; $day++)
                        {
                            $yearly[] = $time['from_month'] . '-' . $day;
                        }
                    }
                    else
                    {
                        for($day= $time['from_day']; $day <= 31; $day++)
                        {
                            $yearly[] = $time['from_month'] . '-' . $day;
                        }
                        for($month = $time['from_month'] + 1; $month < $time['to_month'];$month++)
                        {
                            for($day= 1; $day <= 31; $day++)
                            {
                                $yearly[] = $month . '-' . $day;
                            }
                        }
                        for($day= 1;$day <= $time['to_day']; $day++)
                        {
                            $yearly[] = $time['to_month'] . '-' . $day;
                        }
                    }
                }
                $result = implode(',', $yearly);
                break;
        }
    }
    return $result;
}
function pricing_list_room_type($room_types)
{
    $list_room_type = '';
    if(!empty ($room_types))
    {
        foreach ($room_types as $room_type)
        {
            $list_room_type .= $room_type . ',';
        }
    }
    return $list_room_type;
}
function validate_time($time = array(), $time_type = '')
{
    $result = '0';
    if(!empty($time_type))
    {
        switch ($time_type)
        {
            case 'weekly':
                if(isset ($time['monday']) && $time['monday'] == '1')
                    $result = '1';
                if(isset ($time['tuesday']) && $time['tuesday'] == '1')
                    $result = '1';
                if(isset ($time['wednesday']) && $time['wednesday'] == '1')
                    $result = '1';
                if(isset ($time['thusday']) && $time['thusday'] == '1')
                    $result = '1';
                if(isset ($time['friday']) && $time['friday'] == '1')
                    $result = '1';
                if(isset ($time['saturday']) && $time['saturday'] == '1')
                    $result = '1';
                if(isset ($time['sunday']) && $time['sunday'] == '1')
                    $result = '1';
                break;
            case 'monthly':
                if(isset ($time) && !empty ($time))
                {
                    $result = '1';
                }
                break;
            case 'yearly':
                if(isset ($time['from_month']) && isset ($time['from_day']) && isset ($time['to_month']) && isset ($time['to_day']))
                {
                    if($time['from_month'] =='-1' || $time['from_day'] == '-1' || $time['to_month'] == '-1' || $time['to_day'] == '-1')
                        $result = '0';
                    else
                    {
                        $from_month = $time['from_month'];
                        $from_day = $time['from_day'];
                        $to_month = $time['to_month'];
                        $to_day = $time['from_day'];
                        if($from_month > $to_month)
                            $result = '0';
                        elseif($from_month == $to_month && $from_day > $to_day)
                            $result = '0';
                        else $result = '1';
                    }
                }
                break;
        }
    }
    return $result;
}
?>
