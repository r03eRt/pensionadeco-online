<?php
$data = array();
$errors = array();
$is_coupon = '';
$message = '';
$id = isset ($_GET['id']) ? $_GET['id'] : '0';
if(isset ($_POST['save_promotion']))
{
    $pre_promotions = get_option(BOOKING_PROMOTION);
    if(empty ($pre_promotions))
    {
        $new_promotyion_id = 1;
        $pre_promotions = array();
    }
    else $new_promotyion_id = count($pre_promotions) + 1;
    $data['ID'] = $new_promotyion_id;
    $is_coupon = isset ($_POST['promotion']) ? $_POST['promotion'] : '0';
    if($is_coupon == '1')
        $data['code'] = isset ($_POST['pro_code']) ? $_POST['pro_code'] : '';
    else
        $data['code'] = '';
    $data['title'] = isset ($_POST['pro_title']) ? $_POST['pro_title'] : '';
    $data['title'] = stripslashes(trim($data['title']));
    $data['description'] = isset ($_POST['pro_description']) ? $_POST['pro_description'] : '';
    $data['description'] = stripslashes(trim($data['description']));
    $data['promotion_type'] = isset ($_POST['promotion_type']) ? $_POST['promotion_type'] : '';
    $data['amount'] = isset ($_POST['pro_amount']) ? $_POST['pro_amount'] : '';
    $data['used'] = 0;
    $data['quanlity'] = isset ($_POST['pro_quanlity']) ? $_POST['pro_quanlity'] : '';
    $data['start_date'] = isset ($_POST['start_date']) ? $_POST['start_date'] : '';
    $data['end_date'] = isset ($_POST['end_date']) ? $_POST['end_date'] : '';
    $data['activated'] = isset ($_POST['activated']) ? $_POST['activated'] : '';

    // Check errors
    if($is_coupon == '1' && empty ($data['code']))
        $errors[] = __('ERROR: Please enter coupon code.', 'hotel');
    if(empty ($data['title']))
        $errors[] = __('ERROR: Please enter tilte.', 'hotel');
    if(empty ($data['promotion_type']) || $data['promotion_type'] == '0')
        $errors[] = __('ERROR: Please select promotion amount type.', 'hotel');
    if(empty ($data['amount']))
        $errors[] = __('ERROR: Please enter promotion amount.', 'hotel');
    elseif($data['promotion_type'] == '1' && ($data['amount'] > 100 || $data['amount'] < 0))
        $errors[] = __('ERROR: Promotion amount should be less than 100 and equal or more than 0.', 'hotel');
    if(empty ($data['quanlity']))
        $errors[] = __('ERROR: Please enter promotion usage.', 'hotel');
    elseif(!is_numeric($data['quanlity']))
        $errors[] = __('ERROR: Promotion quanlity should be a number.', 'hotel');
    if(empty($data['start_date']))
        $errors[] = __('ERROR: Please select a start date.', 'hotel');
    elseif(empty($data['end_date']))
        $errors[] = __('ERROR: Please select a end date.', 'hotel');
    elseif(strtotime($data['start_date']) > strtotime($data['end_date']))
        $errors[] = __('ERROR: Start date must less than end date.', 'hotel');
    
    // Save if no error
    if(empty ($errors))
    {        
        if($id > 0)
        {
            foreach ($pre_promotions as $key => $promotion)
            {
                if($promotion['ID'] == $id)
                {
                    $data['ID'] = $id;
                    $data['used'] = $promotion['used'];
                    $pre_promotions[$key] = $data;
                }
            }
        }
        else
        {
            $pre_promotions[] = $data;            
        }
        update_option(BOOKING_PROMOTION, $pre_promotions);
        $message = __('Your settings have been saved', 'hotel');
        wp_redirect(get_bloginfo('wpurl'). '/wp-admin/admin.php?page=my-submenu-list-promotions');
    }
}
?>
