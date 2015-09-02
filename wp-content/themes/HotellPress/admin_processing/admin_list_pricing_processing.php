<?php
$message = '';
if(isset ($_POST['applysubmitted']))
{
    global $wpdb;
    $action = isset ($_POST['actionapplytop']) ? $_POST['actionapplytop'] : '';
    $cbroomtype = isset ($_POST['cbroomtype']) ? $_POST['cbroomtype'] : '';    
    $sql = '';
    if(!empty($action) && !empty($cbroomtype))
    {
        
        $ids = implode(',', $cbroomtype);
       
        switch ($action)
        {
            case 'delete';
                $sql = "DELETE FROM " . $wpdb->prefix . 'pricing' . " WHERE ID IN(" . $ids . ")";
                $message = __('You have been successfully deleted.', 'hotel');
                break;
            case 'disable':
                $sql = "UPDATE " . $wpdb->prefix . 'pricing' . " SET disable='0' WHERE ID IN(" . $ids . ")";
                $message = __('You have been successfully updated.', 'hotel');
                break;
            case 'enable':
                $sql = "UPDATE " . $wpdb->prefix . 'pricing' . " SET disable='1' WHERE ID IN(" . $ids . ")";
                $message = __('You have been successfully updated.', 'hotel');
                break;
        }        
        $wpdb->query($sql);        
    }
}
?>
