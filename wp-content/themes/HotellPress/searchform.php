<form id="search_form" method="post" action="<?php echo tgt_get_page_link('search') ?>"> 
<div class="dialog-content">	
	<p class="reservation-error" style="display: hidden; color: #993300"></p>
	<input type="hidden" name="s" value="CheckRooms"></input>
	
	<div class="dialog-row wide">
		<label for=""><?php _e('From:', 'hotel') ?></label> <br />
		<input type="text" class="datepicker" name="from" value="<?php echo date('m/d/Y');?>"/>
	</div>
	<div class="dialog-row wide">
		<label for=""><?php _e('To:', 'hotel') ?></label> <br />
		<input type="text" class="datepicker" name="to" value="<?php echo date('m/d/Y', strtotime('tomorrow'));?>"/>
	</div>
	<div class="dialog-row wide">
		<label for=""><?php _e('Adults:', 'hotel') ?></label> <br />
		<select name="num_adults" class="select-search-form">
			<?php
				$max_ppl = get_option('tgt_max_people_per_booking') ? get_option('tgt_max_people_per_booking') : 8;
				for ( $i = 1; $i <= $max_ppl; $i++ )
				{
					echo '<option value="' . $i . '">' . $i . '</option>';
				}
			?>
		</select>
	</div>
	
	<div class="dialog-row wide">
		<label for=""><?php _e('Rooms:', 'hotel') ?></label> <br />
		<select name="num_rooms" class="select-search-form">			
			<?php
				$max_rooms = get_option('tgt_max_rooms_per_booking') ? get_option('tgt_max_rooms_per_booking') : 8;
				for ( $i = 1; $i <= $max_rooms; $i++ )
				{
					echo '<option value="' . $i . '">' . $i . '</option>';
				}
			?>
		</select>
	</div>
	
	<?php			
	if(get_option('tgt_room_fields',true) != '')
	{
		$fields = get_option('tgt_room_fields',true);				
		if(is_array($fields) && !empty($fields))
		{
			foreach($fields as $k=>$v)
			{
				$option = '';
				if($v['can_search'] == '1' && $v['activated'] == '1')
				{
					if($v['field_type'] == 'textbox')
					{								
	?>
						<div class="dialog-row clear">
						
							<label><?php
							 if (function_exists('icl_register_string')) {
								    echo icl_t('Additional fields',md5($v['field_name']), $v['field_name']);
  							}else {
  									echo $v['field_name'];
  							}
							//echo $v['field_name']; 
							?>
							:</label><br/>
							<input style="margin-right:10px; margin-bottom:10px;width:100px;" type="text" name="<?php echo $k; ?>"/> 
						
						</div>
	<?php
					}elseif($v['field_type'] == 'textarea')
					{
	?>
						<div class="dialog-row clear">
						
							<label><?php echo $v['field_name']; ?>:</label><br/>
							<textarea style="width:150px;" cols="4" name="<?php echo $k; ?>"></textarea> 
						
						</div>
	<?php
					}elseif($v['field_type'] == 'checkbox')
					{
	?>
						<div class="dialog-row clear">
						<input style="float:left; margin-right:10px; margin-bottom:10px;" type="<?php echo $v['field_type']; ?>" value="yes" name="<?php echo $k; ?>"/> 
						<label><?php echo $v['field_name']; ?></label>
						</div>
	<?php
					}elseif($v['field_type'] == 'combobox')
					{
						$option = explode(',',$v['field_options']);
	?>
					<div class="dialog-row clear">					
						<label><?php echo $v['field_name']; ?>:</label>&nbsp;
						<select style="width: 100px; margin-right:10px; margin-bottom:10px;-moz-border-radius:5px 5px 5px 5px;background:url('../images/select-bg.png') no-repeat scroll 0 0 transparent;border:1px solid #C3C3C3;" name="<?php echo $k; ?>">
						<option value=""><?php _e('Select','hotel'); ?></option>
						<?php
						for($i=0;$i<count($option);$i++)
						{
						?>
							<option value="<?php echo $option[$i]; ?>"><?php echo $option[$i]; ?></option>
						<?php
						}
						?>
						</select>					
					</div>
	<?php
					}
				}
			}
		}
	}
	?>
	

	<div class="clear"></div>
</div>

<div class="dialog-footer">
	<div class="button" style="margin-top:15px; float:left; margin-left:0;">  
		<div class="button_left"></div>        
			<div class="button_center">
				<input id="searchsubmit" name="search" type="submit" class="button" value="<?php _e ('Check Availability', 'hotel');?>"/>
			</div>
		<div class="button_right"></div>
	</div>
	<div class="clear"></div>
</div>
</form>
					
			