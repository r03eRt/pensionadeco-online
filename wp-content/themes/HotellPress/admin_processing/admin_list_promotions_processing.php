<?php
$message = '';
if(isset ($_POST['pro_apply']))
{
    $cbroomtype = isset ($_POST['cbroomtype']) ? $_POST['cbroomtype'] : '';
    $active = isset ($_POST['actionapplytop']) ? $_POST['actionapplytop'] : '';   
    if(!empty ($active) && !empty($cbroomtype))
    {
        foreach ($cbroomtype as $id)
        {
            $message = apply_action($active, $id);
        }
    }
}
elseif($_POST['pro_filter'])
{    
    $active = isset ($_POST['actionfilter']) ? $_POST['actionfilter'] : '';
    if($active != 'all')
        wp_redirect(get_bloginfo('wpurl'). '/wp-admin/admin.php?page=my-submenu-list-promotions&type=' . $active);
    else
        wp_redirect(get_bloginfo('wpurl'). '/wp-admin/admin.php?page=my-submenu-list-promotions');
    exit;
}
function apply_action($type, $id)
{
    $promotions = get_option(BOOKING_PROMOTION);    
    if(!empty($promotions))
    {
        switch ($type)
        {
            case 'delete':
                foreach ($promotions as $key => $promotion)
                {
                    if($promotion['ID'] == $id)
                    {
                        unset($promotions[$key]);
                    }
                }
                break;
            case 'activated':
                foreach ($promotions as $key => $promotion)
                {
                    if($promotion['ID'] == $id)
                    {
                        $promotions[$key]['activated'] = '1';
                    }
                }
                break;
            case 'inactivated':
                foreach ($promotions as $key => $promotion)
                {
                    if($promotion['ID'] == $id)
                    {
                        $promotions[$key]['activated'] = '0';
                    }
                }
                break;                
        }
        update_option(BOOKING_PROMOTION, $promotions);
        $message = __('Your settings have been saved','hotel');
    }
    return $message;
}
?>