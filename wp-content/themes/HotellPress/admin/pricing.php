<?php
include_once TEMPLATEPATH . '/admin_processing/admin_pricing_processing.php';
?>
<?php
$id = isset ($_GET['id']) ? $_GET['id'] : '';
if($id > 0)
{
    include_once TEMPLATEPATH . '/admin/admin_pricing_edit.php';
    exit;
}
$args = array(
        'post_status' => 'publish',
        'post_type' => 'roomtype',
        'posts_per_page' => -1,
        'order' => 'ASC'
        );
$room_type_lists = query_posts($args);
$price_save = '';
if(isset ($data['room_types_changed_price']) && !empty($data['room_types_changed_price']) && is_array($data['room_types_changed_price']))
{
    $prices_input = array();
    foreach($data['room_types_changed_price'] as $key=> $values)
    {
        $prices_tmp = array($key);
        if(!empty($values) && is_array($values))
        {
            foreach ($values as $price)
            {
                $prices_tmp = array_merge($prices_tmp, array($price));
            }
        }
        if(isset ($prices_tmp) && !empty ($prices_tmp))
        {
            $prices_input[] = implode(',', $prices_tmp);
        }

    }
    $price_save = implode('-', $prices_input);
}
?>
<div class="wrap">
    <?php the_support_panel();  ?>
    <br/>
    <form method="post" name="pricing" enctype="multipart/form-data" target="_self">
    <div class="settings" style="margin: 0px 0px 0px 0px; padding-top: 0px 0px 0px 0px;">
        <?php
            if(isset ($errors) && !empty ($errors))
            {
                echo '<div class="error"><strong>';
                foreach ($errors as $error)
                {
                    echo "<p>$error</p>";
                }
                echo '</strong></div>';
            }
            elseif(!empty ($message))
            {
                echo '<div class="updated below-h2">';
                echo $message;
                echo '</div>';               
            }
        ?>
        <div class="heading">            
            <h3><?php _e('Pricing','hotel');?></h3>
            <div class="cl"></div>
            
        </div>
        <div class="item" style="padding: 0 0 0 20px; height:100%;">
            <div class="content" id="page">
                <div class="content submission" id="jobs" >
                    <!--  time -->
                    <div style="margin-bottom: 20px;">
                         <div class="pricing-option">
                            <h3><?php _e('Time','ce');?></h3>
                            <input type="radio" name="time" class="choose-picker" id="weekly" value="weekly" checked><span>&nbsp;<?php _e('Weekly','hotel');?></span>&nbsp;&nbsp;&nbsp;&nbsp;
                            <input type="radio" name="time" class="choose-picker" id="monthly" value="monthly" <?php if(isset ($data['time_type']) && $data['time_type']=='monthly') echo 'checked'; ?>> <span>&nbsp;<?php _e('Monthly','hotel');?></span>&nbsp;&nbsp;&nbsp;&nbsp;
                            <input type="radio" name="time" class="choose-picker" id="yearly" value="yearly" <?php if(isset ($data['time_type']) && $data['time_type']=='yearly') echo 'checked'; ?>> <span>&nbsp;<?php _e('Yearly','hotel');?></span>
                            <br><br>
                            
                            <div id="weekly_picker" class="time-picker selected" >
                                <input type="checkbox" class="daypicker" name="monday" value="1" <?php if(isset ($time['monday']) && $time['monday'] == '1') echo 'checked'; ?> /> <label class="pickerblock <?php if(isset ($time['monday']) && $time['monday'] == '1') echo 'checked'; ?>" for="monday" >MON</label>
                                <input type="checkbox" class="daypicker" name="tuesday" value="1" <?php if(isset ($time['tuesday']) && $time['tuesday'] == '1') echo 'checked'; ?> /> <label class="pickerblock <?php if(isset ($time['tuesday']) && $time['tuesday'] == '1') echo 'checked'; ?>" for="tuesday">TUE</label>
                                <input type="checkbox" class="daypicker" name="wednesday" value="1" <?php if(isset ($time['wednesday']) && $time['wednesday'] == '1') echo 'checked'; ?> /> <label class="pickerblock <?php if(isset ($time['wednesday']) && $time['wednesday'] == '1') echo 'checked'; ?>" for="wednesday">WED</label>
                                <input type="checkbox" class="daypicker" name="thusday" value="1" <?php if(isset ($time['thusday']) && $time['thusday'] == '1') echo 'checked'; ?> /> <label class="pickerblock <?php if(isset ($time['thusday']) && $time['thusday'] == '1') echo 'checked'; ?>" for="thusday">THU</label>
                                <input type="checkbox" class="daypicker" name="friday" value="1" <?php if(isset ($time['friday']) && $time['friday'] == '1') echo 'checked'; ?> /> <label class="pickerblock <?php if(isset ($time['friday']) && $time['friday'] == '1') echo 'checked'; ?>" for="friday">FRI</label>
                                <input type="checkbox" class="daypicker" name="saturday" value="1" <?php if(isset ($time['saturday']) && $time['saturday'] == '1') echo 'checked'; ?>/> <label class="pickerblock <?php if(isset ($time['saturday']) && $time['saturday'] == '1') echo 'checked'; ?>" for="saturday">SAT</label>
                                <input type="checkbox" class="daypicker" name="sunday" value="1" <?php if(isset ($time['sunday']) && $time['sunday'] == '1') echo 'checked'; ?> /> <label class="pickerblock <?php if(isset ($time['sunday']) && $time['sunday'] == '1') echo 'checked'; ?>" for="sunday">SUN</label>
                            </div>
                            <div id="monthly_picker" class="time-picker" style="width: 70%">
                                <?php
                                for( $i = 1; $i <= 31 ; $i++  )
                                { ?>
                                <input type="checkbox" class="daypicker" name="monthly[<?php echo $i ?>]" value="<?php echo $i ?>" <?php if(isset ($time[$i]) && $time[$i] == $i) echo 'checked'; ?>/> <label class="pickerblock <?php if(isset ($time[$i]) && $time[$i] == $i) echo 'checked'; ?>" for="monthly[<?php echo $i ?>]"><?php echo $i ?></label>
                                    <?php 
                                }
                                ?>                                
                            </div>
                            <div id="yearly_picker" class="time-picker">
                                <table>
                                    <tr>
                                        <td>
                                            <label for="fromdate" style="margin-left: 2px;"><?php _e('FROM','hotel');?></label><br />
                                            <?php $months = array(__('January','hotel'),__('February','hotel'), __('March','hotel'), __('April','hotel'), __('May','hotel'), __('June','hotel'), __('July','hotel'), __('August','hotel'), __('September','hotel'), __('October','hotel'), __('November','hotel'), __('December','hotel')); ?>
                                            <select name="from_month">
                                                <option value="-1"><?php _e('Month') ?></option>
                                                <?php
                                                foreach( $months as $num => $value )
                                                {
                                                    $selected = "";
                                                    if(isset ($time['from_month']) && $time['from_month']== ($num + 1))
                                                        $selected = "selected";
                                                    echo '<option value="' . ($num + 1) . '" '. $selected .'>' . $value . '</option>';
                                                } ?>
                                            </select>
                                            <select name="from_day">
                                                <option value="-1"><?php _e('Day','hotel');?></option>
                                                <?php for ( $i = 1; $i <= 31; $i++ )
                                                {
                                                    $selected = "";
                                                    if(isset ($time['from_day']) && $time['from_day']== $i)
                                                        $selected = "selected";
                                                    echo '<option value="' . $i . '"' . $selected . '>' . $i . '</option>';
                                                }  ?>
                                            </select>
                                        </td>
                                        <td style="padding-left: 25px;">
                                            <label for="fromdate" style="margin-left: 2px;"><?php _e('TO','hotel');?></label><br />
                                            <?php //$months = array('January','February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'); ?>
                                            <select name="to_month">
                                                <option value="-1"><?php _e('Month', 'hotel') ?></option>
                                                <?php
                                                foreach( $months as $num => $value )
                                                {
                                                    $selected = "";
                                                    if(isset ($time['to_month']) && $time['to_month']== ($num + 1))
                                                        $selected = "selected";
                                                    echo '<option value="' . ($num + 1) . '"' . $selected . '>' . $value . '</option>';
                                                } ?>
                                            </select>
                                            <select name="to_day">
                                                <option value="-1"><?php _e('Day','hotel'); ?></option>
                                                <?php for ( $i = 1; $i <= 31; $i++ )
                                                {
                                                    $selected = "";
                                                    if(isset ($time['to_day']) && $time['to_day']== $i)
                                                        $selected = "selected";
                                                    echo '<option value="' . $i . '"' . $selected . '>' . $i . '</option>';
                                                }  ?>
                                            </select>
                                        </td>
                                    </tr>
                                </table>
                            </div><br>
                            <div class="pricing-option">
                            <h3><?php _e('Select Room Type','hotel');?></h3>
                            <?php
                                if(!empty($room_type_lists))
                                {
                                    echo '<table width="70%">';
                                    $i = 0;
                                    $num_column = 3;
                                    $column_width = (int)(70/$num_column);
                                    foreach ($room_type_lists as $key => $room_type)
                                    {
                                        if($i % $num_column == 0)
                                        {
                                            echo '<tr>';
                                        }
                                        $checked = '';
                                        if(isset ($data['romm_type'][$i]) && $data['romm_type'][$i] == $room_type->ID)
                                             $checked = ' checked';
                                        echo '<td width="' . $column_width . '%"><input class="check_room_type" type="checkbox" name="check_room_type['.$i.']" id="check_room_type_' . $i . '" value="' . $room_type->ID . '"' . $checked . '><span>&nbsp;' . $room_type->post_title . '</span></td>';
                                        $i++;
                                        if($i % $num_column == 0 && $i > 0)
                                            echo '</tr>';
                                    }
                                    echo '</table><br>';
                                    echo '<input class="button" type="button" name="room_type_checkall" id="room_type_checkall" value="' . __("Check all","hotel") . '">';echo '&nbsp;';
                                    echo '<input class="button" type="button" name="room_type_uncheckall" id="room_type_uncheckall" value="' . __("Uncheck all","hotel") . '">';
                                }
                            ?>
                        </div>
                        <?php
                            foreach ($room_type_lists as $key => $room_type)
                            {
                                echo '<div class="table_change_pricing" id="table_change_pricing_' . $room_type->ID . '"></div>';
                                echo '<input type="hidden" name="room_types_changed[' . $key . ']" id="room_type_changed_' . $room_type->ID . '" value="">';
                            }
                        ?>
                            
                            <div class="pricing-option">
                            <h3><?php _e('Change type','hotel');?></h3>
                            <div class="pricing-field">
                                <label class="plabel"><?php _e('Priority','hotel'); ?></label>
                                <div class="pvalue">
                                    <select name="priority" style="width: 150px;">
                                        <option value="0"><?php _e('Select a priority... ','hotel'); ?></option>
                                        <option value="1" <?php if($data['priority'] == '1') echo 'selected'; ?>><?php _e('Very high','hotel'); ?></option>
                                        <option value="2" <?php if($data['priority'] == '2') echo 'selected'; ?>><?php _e('High','hotel'); ?></option>
                                        <option value="3" <?php if($data['priority'] == '3') echo 'selected'; ?>><?php _e('Normal','hotel'); ?></option>
                                        <option value="4" <?php if($data['priority'] == '4') echo 'selected'; ?>><?php _e('Low','hotel'); ?></option>
                                        <option value="5" <?php if($data['priority'] == '5') echo 'selected'; ?>><?php _e('Very low','hotel'); ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="clear"></div>
                        </div>
                        
                        <div class="pricing-option">
                            <div class="pricing-field">
                                <label class="plabel"><?php _e('Date start','hotel'); ?></label>
                                <div class="pvalue">
                                    <input type="text" class="check_room_type" name="date_start" id="date_start" value="<?php if(isset ($data['date_start'])) echo $data['date_start']; ?>" readonly="readonly"/>
                                    <a href="" onclick="return false;"><input type="hidden" id="start_date_image" /></a>
                                </div>
                            </div>
                            <div class="clear"></div>
                        </div>
                        
                        <div class="pricing-option">
                            <div class="pricing-field">
                                <label class="plabel"><?php _e('Activated','hotel'); ?></label>
                                <div class="pvalue">
                                    <select name="disable_pricing" style="width: 150px;">                                        
                                        <option value="1" <?php if($data['disable_pricing'] == '1') echo 'selected'; ?>><?php _e('Yes','hotel'); ?></option>
                                    	<option value="0"><?php _e('No','hotel'); ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="clear"></div>
                        </div>                        
                </div>
            </div>
        </div>
    </div>
        </div>
        <br><input type="submit" name="save_pricing" value="<?php _e('Save','hotel');?>" class="button-primary">
    </form>
</div>
<script type="text/javascript">

    jQuery(document).ready(function() {        
        jQuery.tranformPicker();
        jQuery('select[name=from_month], select[name=to_month]').change(function(){
            var current = jQuery(this),                
                id = current.attr('name'),
                value = current.val(),
                day_num = 0,
                target = jQuery('select[name=to_day]');
            switch( value )
            {
                case '2':
                    day_num = 29;
                    break;
                case '4':
                case '6':
                case '9':
                case '11':
                    day_num = 30;
                    break;
                default :
                    day_num = 31;
                    break;
            }
            if (id == 'from_month')
            {
                target = jQuery('select[name=from_day]');
            }
            days = '<option value="-1">Day</option>';
            for ( i = 1 ; i <= day_num ; i++ )
            {
                days += '<option value="' + i + '">' + i + '</option>';
            }
            target.html(days);
        });        
        var num_of_room_type = '<?php echo count($room_type_lists); ?>';
              
        for( var i=0; i < num_of_room_type; i++ )
        {
            if(jQuery('#check_room_type_' + i ).is(':checked'))
            {
                var room_id = jQuery('#check_room_type_' + i ).val();
                jQuery('#room_type_changed_' + room_id ).val(room_id);
                if(room_id > 0)
                {
                    change_price(room_id, '1');
                }
            }
        }
    });
    jQuery('input[class=check_room_type]').click(function(){
        var current = jQuery(this),
            value = current.val();
            
        if(current.is(':checked'))
        {
            jQuery('#room_type_changed_' + value ).val(value);
            jQuery('#table_change_pricing_' + value ).fadeOut(1000);
            change_price(value, '1');
            jQuery('#table_change_pricing_' + value ).fadeIn(1000);
        }
        else
        {
            change_price(value, '0');
            jQuery('#table_change_pricing_' + value ).fadeOut(1000);
        }
    });
    function change_price(roomtype_id, is_checked)
    {
        var price_input = '<?php echo $price_save; ?>';
        if(roomtype_id > 0)
        {
            jQuery.ajax({
                    type: 'post',
                    url: "<?php echo HOME_URL . '/?do_ajax=ajax_pricing_time'; ?>",
                    data: {
                            action: 'admin_pricing_time',
                            room_type_id: '' + roomtype_id,
                            ischecked: is_checked,
                            price: price_input
                        },
                        success: function(data)
                        {
                            jQuery('#table_change_pricing_' + roomtype_id ).html(data.message);
                        }
                   });
        }
    }
    jQuery('#room_type_checkall').click(function(){
        var num_room = '<?php echo count($room_type_lists); ?>';
        jQuery('.table_change_pricing').fadeOut(1000);
        for( var i=0; i < num_room; i++ )
        {
            var room_id = jQuery('#check_room_type_' + i ).val();
            jQuery('#check_room_type_' + i ).attr('checked', true);
            jQuery('#room_type_changed_' + room_id ).val(room_id);
            change_price(jQuery('#check_room_type_' + i ).val(), '1');
        }
        jQuery('.table_change_pricing').fadeIn(1000);
    });
    jQuery('#room_type_uncheckall').click(function(){
        var num_room = '<?php echo count($room_type_lists); ?>';
        for( var i=0; i < num_room; i++ )
        {
            jQuery('#check_room_type_' + i ).attr('checked', false);
            var room_id = jQuery('#check_room_type_' + i ).val();
            jQuery('#room_type_changed_' + room_id ).val('');
            change_price(jQuery('#check_room_type_' + i ).val(), '0');
        }
        jQuery('.table_change_pricing' ).fadeOut(1000);
    });
    jQuery.tranformPicker = function()
    {
        var value = jQuery('input[name=time]:checked').val(),
        weekDiv = jQuery('#weekly_picker'),
        monthDiv = jQuery('#monthly_picker'),
        yearDiv = jQuery('#yearly_picker');
        jQuery('.time-picker').hide().removeClass('selected');
        switch( value )
        {
            case 'weekly':                    
                weekDiv.show().addClass('selected');
                break;
            case 'monthly':                    
                monthDiv.show().addClass('selected');
                break;
            case 'yearly':                    
                yearDiv.show().addClass('selected');
                break;
        }
        /**
         *
         */
        jQuery('.daypicker').hide();
        
        jQuery('input.choose-picker').click(function(){
            var value = jQuery('input[name=time]:checked').val(),
            weekDiv = jQuery('#weekly_picker'),
            monthDiv = jQuery('#monthly_picker'),
            yearDiv = jQuery('#yearly_picker'),
            allDiv = jQuery('.time-picker'),
            selectDiv = jQuery('.time-picker.selected'),
            selectID = selectDiv.attr('id');
            if ( selectID != value + '_picker' )
            {
                selectDiv.removeClass('selected').fadeOut('fast', function(){
                    switch( value )
                    {
                        case 'weekly':                    
                            weekDiv.fadeIn().addClass('selected');
                            break;
                        case 'monthly':                    
                            monthDiv.fadeIn().addClass('selected');
                            break;
                        case 'yearly':                    
                            yearDiv.fadeIn().addClass('selected');
                            break;
                    }
                });                
            }
        });
        
        jQuery('.pickerblock').click(function(){
            var current = jQuery(this),
                name = current.attr('for');
                target = jQuery('.daypicker[name="'+ name +'"]');
            
            if ( target.is(':checked') )
            {
                target.removeAttr('checked');
                current.removeClass('checked');
            }
            else
            {
                target.attr('checked','checked');
                current.addClass('checked');
            }
        })        
    }
    jQuery(function($) {
        jQuery( "#date_start" ).datepicker({minDate: new Date() });
    });
    jQuery(function($) {
        jQuery( "#date_start_image" ).datepicker({minDate: new Date() });
    });
    function date_start()
    {
        jQuery( "#date_start" ).datepicker({minDate: new Date() });
        alert('Hello');
        return false;
    }    
    jQuery("#start_date_image").datepicker({
            buttonImage: '<?php echo TEMPLATE_URL . '/images/calendar.jpg'?>',
            buttonImageOnly: true,
            minDate: new Date(),
            showOn: 'both'
         });
     jQuery('#start_date_image').change(function(){
        var start_date = jQuery('#start_date_image').val();
        jQuery('#date_start').val(start_date);
     });
</script>
