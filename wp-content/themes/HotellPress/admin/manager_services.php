<?php $options = options::getInstance(); 
$message = '';
if(isset($_POST['applysubmitted'])){	
	
	$u_id = array();
	if(isset($_POST['cbkeys'])){
	$u_id= $_POST['cbkeys'];	
	}	
	
	if(isset($_POST['actionapplytop']) && $_POST['actionapplytop']=="delete" )
	{	
		if(count($u_id)>0){	
		for($i=0;$i<count($u_id);$i++){
			$options->deleteOption($u_id[$i]);		
		}		
		$message = __("Delete successful!",'hotel');
		
		}
		echo "<script language='javascript'>window.location = '".get_bloginfo('wpurl')."/wp-admin/admin.php?page=my-submenu-manager-option';</script>";
	}	
	
}
if (isset($_GET['delete-option']) && isset($_GET['id-option'])){
		$options->deleteOption($_GET['id-option']);
		$message = __("Delete successful!",'hotel');
		
}
if (isset($_GET['edit-option']) && isset($_GET['id-edit'])){
	$op_list = $options->getOptions();
	$list_op = $op_list[$_GET['id-edit']];
}
if (isset($_POST['submit_edit_option'])){
	if (function_exists('icl_register_string')) { 
   		$service = $_POST['name'];
   		icl_register_string('Services', md5($service), $service);
     }
	$options->updateoption($_GET['id-edit'], 'name', $_POST['name']);
	$options->updateoption($_GET['id-edit'], 'default_price', $_POST['default_price']);
	$message = __("Update successful!",'hotel');
	echo "<script language='javascript'>window.location = '".get_bloginfo('wpurl')."/wp-admin/admin.php?page=my-submenu-manager-option';</script>";
}

?>

<div id="icon-edit" class="icon32"><br></div>
	<h2 style="color:#464646;    font: italic 24px/35px Georgia; margin: 0; padding: 14px 15px 3px 0; text-shadow: 0 1px 0 #FFFFFF;" ><?php _e ('Manager Services', 'hotel');?></h2>
	<div class="atention">
		<strong><?php _e('Problems? Questions?','hotel');?></strong><?php _e(' Contact us at','hotel');?> <em><a href="http://www.dailywp.com/support/"><?php _e('Support','hotel'); ?></a></em>. 
	</div>
   
    <?php if(isset($message) && $message != '') { ?>
    <div id="message" class="updated below-h2"><p><?php echo $message; ?></p></div>
    <?php } ?>
  <br class="clear" />



<div id='col-left' style="float:left;"><div class='col-wrap'>
	<div class='form-wrap'>	
	<div id="error_services" class="error"></div>
	<h3><?php if(isset($_GET['edit-option'])){ _e('Edit Service','hotel'); }else{ _e('Add New Service','hotel');} ?></h3>
	
	<form method="post" action=""> 
	<div class="form-field form-required"  >
        <label for='name'><?php _e('Service name','hotel'); ?> :</label>
        <input type='text' name='name' id='name' value="<?php echo (isset($_GET['edit-option']) && isset($_GET['id-edit']))?$list_op['name']:''; ?>" style="width:200px;" />        
    	
    </div>   
    <div class="form-field form-required"  >
        <label for='description'><?php _e('Default price','hotel'); ?> :</label>      
       <input type='text' name='default_price'  id='default_price' value="<?php echo (isset($_GET['edit-option']) && isset($_GET['id-edit']))?$list_op['default_price']:'10'; ?>" style="width:100px;" onkeypress="return EnterNumber(event)"/>      
    	
    </div>       
    <?php if ((isset($_GET['edit-option']) && isset($_GET['id-edit']))){ ?>    
    <p class='submit'>    
    <input type="submit"   name="submit_edit_option" id="submit_edit_option" class="button-primary" value="<?php _e('Update Service','hotel');  ?>"></p>
	<?php }else { ?>
	 <p class='submit'>    
    <input type="button" onclick="submit_option_form()"  name="submit_option" id="submit_option" class="button-primary" value="<?php _e('Add Service','hotel'); ?>"></p>
	<?php } ?>
	</form>
	</div>
	</div>
	</div>
	
	
	<div id="col-right">		
      <div class="form-wrap">
          <form id="form_edit" action="" method="post"  >
          <div class="tablenav">
            <select name="actionapplytop" id="actionapplytop">
              <option value=""><?php _e('Bulk Actions','hotel'); ?></option>
              <option value="delete"><?php _e('Delete','hotel'); ?></option>
            </select>
            <input type="submit" class="button-secondary action"  id="applysubmitted" name="applysubmitted" value="<?php _e('Apply','hotel'); ?>"/>
          </div><br class="clear">
          <table class="widefat tag fixed" cellspacing="0">
            <thead class="content-types-list">
              <tr>
                <th style="" class="manage-column column-cb check-column" id="cb" scope="col"><input type="checkbox"></th>
                <th style="" class="manage-column column-name"  scope="col"><?php _e('Service name','hotel'); ?></th>                
                <th style="" class="manage-column column-description" id="categories" scope="col"><?php _e('Price','hotel'); ?></th>
              </tr>
            </thead>
            <tbody id="the-list">
           <?php 
           
          		$arr_options = $options->getOptions();          		
					if(isset($arr_options) && is_array($arr_options) && !empty($arr_options)){				
					foreach ($arr_options as $key => $list_options) { 
          	  ?>
            <tr id="content-type" class="">
                <th class="check-column" scope="row">
                 <input  type="checkbox" name="cbkeys[]" id="cbkeys[]" value="<?php echo $key;?>" style="width:10px; height:14px;" />
                </th>
                <td class="name column-name">
                  <strong>
                      <a class="row-title" title="Edit &ldquo;<?php echo $list_options['name']; ?>&rdquo;" href="<?php echo get_bloginfo('wpurl').'/wp-admin/admin.php?page=my-submenu-manager-option&edit-option=true&id-edit='.$key  ; ?>"><?php echo $list_options['name']; ?></a></strong><br />
                    <div class="row-actions">                     
                     
                    <span class="edit"><a href="<?php echo get_bloginfo('wpurl').'/wp-admin/admin.php?page=my-submenu-manager-option&edit-option=true&id-edit='.$key ; ?>"><?php _e('Edit','hotel'); ?></a> | </span>                    
                    
                    <span class="delete"><a href="javascript:del_option(<?php echo $key; ?>)"><?php _e('Delete','hotel'); ?></a></span>
                  </div>
                </td>
               
                <td class="manage-column column-description">
                    <?php echo $list_options['default_price']; ?>
                </td>
            </tr>
            <?php } } ?>
            </tbody>
            <tfoot>
              <tr>
                <th style="" class="manage-column column-cb check-column"  scope="col"><input type="checkbox"></th>
                <th style="" class="manage-column column-name" scope="col"><?php _e('Service name','hotel'); ?></th>                
                <th style="" class="manage-column column-description" scope="col"><?php _e('Price','hotel'); ?></th>
              </tr>
            </tfoot>
          </table>
        </form>
          <br class="clear">
          <br class="clear">          
      </div>
    </div>
   
    <script language="javascript">
    jQuery('#message').show();	
    jQuery('#error_services').hide();   
    function submit_option_form(){   
    	 jQuery('#error_services').hide();    	
		if (jQuery('#name').val() == ''){
			jQuery('#error_services').show();
			jQuery('#error_services').html('<p><?php _e('Enter name, please!','hotel'); ?></p>');
			jQuery('#name').focus();
		}else if (jQuery('#default_price').val() == '') {
			jQuery('#error_services').show();
			jQuery('#error_services').html('<p> <?php _e('Enter default, please!','hotel'); ?> </p>');
			jQuery('#default_price').focus();
		}else{
		
		save_action(jQuery('#name').val(),jQuery('#default_price').val());
		jQuery('#error_services').hide();
		
		}
	}

	function save_action(name, default_price)
	{
	
	    jQuery.ajax({          
	        type: 'post',
	        url: "<?php echo HOME_URL . '/?do_ajax=ajax_option_process'; ?>", 
	        data: {
	            action: 'ajax_option',
	            name: '' + name,
	            default_price: '' + default_price            		                    
	            },			                 
		        success: function(data){	   
		            	
	           		jQuery('#the-list').html(data.content);
	           		jQuery('#name').val('');
	           		jQuery('#default_price').val('10');	           		
	           		jQuery('#message').hide();	
		          }
	       });
	}		
	

    
function del_option(tagid)
{	
	if(confirm("<?php _e('You are about to permanently delete the selected items. Cancel to stop, Ok to delete.','hotel'); ?>"))
	{
		window.location = '<?php echo get_bloginfo('wpurl').'/wp-admin/admin.php?page=my-submenu-manager-option&delete-option=true&id-option='; ?>' + tagid ;
	}		
}

function EnterNumber(e)
{
	var keynum;
	var keychar;
	if(window.event) // IE
		{
		keynum = e.keyCode;
		}
	else if(e.which) // Netscape/Firefox/Opera
		{
		keynum = e.which;
		}
	if(keynum == 8)
		var numcheck = new RegExp("^[^a-z^A-Z]");
	else
		var numcheck = new RegExp("^[0-9+-_()]");
	keychar = String.fromCharCode(keynum);	
	return numcheck.test(keychar);
}

</script>