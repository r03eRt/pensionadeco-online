<?php
include_once TEMPLATEPATH . '/admin_processing/admin_promotion_processing.php';
?>
<?php
$id = isset ($_GET['id']) ? $_GET['id'] : '';
if($id > 0)
{
    $promotions = get_option(BOOKING_PROMOTION);
    $promotion = array();
    foreach ($promotions as $key=>$pro)
    {
        if($pro['ID'] == $id)
        {
            $promotion = $pro;
        }
    }
    if(!empty ($promotion))
    {
?>
<div class="wrap">
    <?php the_support_panel();  ?>
    <br/>
    
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
        <form method="post" name="pricing" enctype="multipart/form-data" target="_self">
        <div class="heading">
            <h3 style="width: 100%"><?php echo __('Edit ','hotel') . '"' . $promotion['title'] . '"';?></h3>
            <div class="clear"></div>
        </div>
        <div class="item" style="padding: 0 0 0 20px; height:100%;">
            <div class="content" id="page">
                <div class="content submission" id="jobs" >
                    <div class="clear"></div><br>
                    <div class="pricing-option">
                        <div class="pricing-field">
                            <input type="radio" name="promotion" class="choose-picker" id="coupon" value="1" checked><span>&nbsp;<?php _e('Coupon','hotel');?></span>&nbsp;&nbsp;&nbsp;&nbsp;
                            <input type="radio" name="promotion" class="choose-picker" id="saleoff" value="0" <?php if(isset ($is_coupon) && $is_coupon== '0') echo 'checked';  elseif(isset ($promotion['code']) && $promotion['code']=='') echo 'checked';?>> <span>&nbsp;<?php _e('Sale off','hotel');?></span>&nbsp;&nbsp;&nbsp;&nbsp;
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="pricing-option" id="coupon_code">
                        <div class="pricing-field">
                            <label class="plabel" id="lcode"><?php _e('Coupon code','hotel'); ?></label>
                            <div class="pvalue">
                                <input type="text" class="check_room_type" name="pro_code" id="pro_code" value="<?php  if(isset ($data['code'])) echo $data['code']; elseif(isset ($promotion['code'])) echo $promotion['code'];?>" style="width: 100%"/>
                            </div>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="pricing-option">
                        <div class="pricing-field">
                            <label class="plabel"><?php _e('Title','hotel'); ?></label>
                            <div class="pvalue">
                                <input type="text" class="check_room_type" name="pro_title" id="pro_title" value="<?php  if(isset ($data['title'])) echo $data['title']; elseif(isset ($promotion['title'])) echo $promotion['title'];?>" style="width: 100%"/>
                            </div>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="pricing-option">
                        <div class="pricing-field">
                            <label class="plabel"><?php _e('Description','hotel'); ?></label>
                            <div class="pvalue">
                                <textarea cols="50"  rows="5" class="check_room_type" name="pro_description" id="pro_description" /><?php if(isset ($data['description'])) echo $data['description']; elseif(isset ($promotion['description'])) echo $promotion['description'];?></textarea>
                            </div>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="pricing-option">
                        <div class="pricing-field">
                            <label class="plabel"><?php _e('Promotion type','hotel'); ?></label>
                            <div class="pvalue">
                                <select name="promotion_type" style="width: 150px;">
                                    <option value="0"><?php _e('Select a promotion type... ','hotel'); ?></option>
                                    <option value="1" <?php if(isset ($data[promotion_type]) && $data[promotion_type] == '1') echo 'selected';  elseif(isset ($promotion['promotion_type']) && $promotion['promotion_type'] == '1') echo 'selected'; ?>><?php _e('Percent ','hotel'); ?> (%) </option>
                                    <option value="2" <?php if(isset ($data[promotion_type]) && $data[promotion_type] == '2') echo 'selected';  elseif(isset ($promotion['promotion_type']) && $promotion['promotion_type'] == '2') echo 'selected';?>><?php _e('Exact amount','hotel'); ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="pricing-option">
                        <div class="pricing-field">
                            <label class="plabel"><?php _e('Promotion amount','hotel'); ?></label>
                            <div class="pvalue">
                                <input type="text" class="check_room_type" name="pro_amount" id="pro_amount" value="<?php if(isset ($data['amount'])) echo $data['amount']; elseif(isset ($promotion['amount'])) echo $promotion['amount']; ?>"/>
                            </div>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="pricing-option">
                        <div class="pricing-field">
                            <label class="plabel"><?php _e('Usage','hotel'); ?></label>
                            <div class="pvalue">
                                <input type="text" class="check_room_type" name="pro_quanlity" id="pro_quanlity" value="<?php  if($data['quanlity']) echo $data['quanlity'];  elseif(isset ($promotion['quanlity'])) echo $promotion['quanlity'];?>"/>
                            </div>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="pricing-option">
                        <div class="pricing-field">
                            <label class="plabel"><?php _e('Start date','hotel'); ?></label>
                            <div class="pvalue">
                                <input type="text" class="check_room_type" name="start_date" id="start_date" value="<?php if(isset ($data['start_date'])) echo $data['start_date']; elseif(isset ($promotion['start_date'])) echo $promotion['start_date']; ?>" readonly="readonly"/>
                                <a href="" onclick="return false;"><input type="hidden" id="start_date_image" /></a>
                            </div>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="pricing-option">
                        <div class="pricing-field">
                            <label class="plabel"><?php _e('End date','hotel'); ?></label>
                            <div class="pvalue">
                                <input type="text" class="check_room_type" name="end_date" id="end_date" value="<?php if(isset ($promotion['end_date'])) echo $promotion['end_date']; elseif(isset ($data['end_date'])) echo $data['end_date'];?>" readonly="readonly"/>
                                <a href="" onclick="return false;"><input type="hidden" id="end_date_image" /></a>
                            </div>
                        </div>
                        <div class="clear"></div>
                    </div>

                        <div class="pricing-option">
                            <div class="pricing-field">
                                <label class="plabel"><?php _e('Activated','hotel'); ?></label>
                                <div class="pvalue">
                                    <select name="activated" style="width: 150px;">
                                        <option value="1" <?php if(isset ($data['activated']) && $data['activated'] == '1') echo 'selected'; elseif($promotion['activated'] == '1') echo 'selected'; ?>><?php _e('Yes','hotel'); ?></option>
                                        <option value="0" <?php if(isset ($data['activated']) && $data['activated'] == '0') echo 'selected'; elseif($promotion['activated'] == '0') echo 'selected'; ?>><?php _e('No','hotel'); ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="clear"></div>
                        </div>
                </div>
            </div>
        </div>
        <br><input type="submit" name="save_promotion" value="<?php _e('Update','hotel');?>" class="button-primary">
    </div>
    </form>
    </div>
</div>
<script type="text/javascript">
    
    jQuery(document).ready(function() {
        var saleoff = jQuery('#saleoff').val();        
        if(saleoff == '0')
            jQuery('#coupon_code').hide();
    });
    
    jQuery('#saleoff').click(function(){
        jQuery('#coupon_code').fadeIn(1000);
        jQuery('#coupon_code').fadeOut(1000);
    });
    jQuery('#coupon').click(function(){
        jQuery('#coupon_code').fadeOut(1000);
        jQuery('#coupon_code').fadeIn(1000);

    });
    jQuery(function() {
        jQuery( "#start_date" ).datepicker({minDate: new Date() });
    });
    jQuery(function() {
        jQuery( "#end_date" ).datepicker({minDate: new Date() });
    });
    jQuery("#start_date_image").datepicker({
            buttonImage: '<?php echo TEMPLATE_URL . '/images/calendar.jpg'?>',
            buttonImageOnly: true,
            minDate: new Date(),
            showOn: 'both'
         });
     jQuery('#start_date_image').change(function(){
        var start_date = jQuery('#start_date_image').val();
        jQuery('#start_date').val(start_date);
     });
     jQuery("#end_date_image").datepicker({
            buttonImage: '<?php echo TEMPLATE_URL . '/images/calendar.jpg'?>',
            buttonImageOnly: true,
            minDate: new Date(),
            showOn: 'both'
         });
     jQuery('#end_date_image').change(function(){
        var start_date = jQuery('#end_date_image').val();
        jQuery('#end_date').val(start_date);
     });
</script>
<?php
    }
}
?>