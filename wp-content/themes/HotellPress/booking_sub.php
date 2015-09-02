<script type="text/javascript">
function check_policy()
{
	if(document.getElementById('check_agree').checked == true)
		document.form_fill_info.submit();	
}
function fill_data(act)
{	
	if(document.getElementById('check_agree').checked == true)
	{
		var title	= document.getElementById('s_title').value;
		var email	= document.getElementById('email').value;
		var phone	= document.getElementById('phone').value;
		var f_name	= document.getElementById('first_name').value;
		var l_name	= document.getElementById('last_name').value;
		var country	= document.getElementById('country').value;
		var state	= document.getElementById('state').value;
		var street	= document.getElementById('street').value;
		var agree_purchase = $('input[name=purchase_agree][value=1]').is(":checked");
		
		
		var error 	= '';
		if(street == ''){
			document.getElementById('street_err').innerHTML = '<?php _e('Street is not blank !','hotel'); ?>';
			error = 1;
		}else		
			document.getElementById('street_err').innerHTML = '';		
		if(phone == ''){
			document.getElementById('phone_err').innerHTML = '<?php _e('Phone is not blank !','hotel'); ?>';
			error = 1;
		}else
			document.getElementById('phone_err').innerHTML = '';
		if(l_name == ''){
			document.getElementById('last_err').innerHTML = '<?php _e('Last name is not blank !','hotel'); ?>';
			error = 1;
		}else
			document.getElementById('last_err').innerHTML = '';		
		if(f_name == ''){
			document.getElementById('first_err').innerHTML = '<?php _e('First name is not blank !','hotel'); ?>';
			error = 1;
		}else
			document.getElementById('first_err').innerHTML = '';		
		if(email == '' || validateEmail(email) == false){			
			document.getElementById('email_err').innerHTML = '<?php _e('Email is not blank or invalid !','hotel'); ?>';
			error = 1;
		}else
			document.getElementById('email_err').innerHTML = '';
		if(state == ''){			
			document.getElementById('state_err').innerHTML = '<?php _e('State is not blank !','hotel'); ?>';
			error = 1;
		}else
			document.getElementById('state_err').innerHTML = '';
		if(error == '')
		{
			if (window.XMLHttpRequest)
			{// code for IE7+, Firefox, Chrome, Opera, Safari
				xmlhttp=new XMLHttpRequest();
			}
			else
			{// code for IE6, IE5
				xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange=function()
			{
				if (xmlhttp.readyState==4 && xmlhttp.status==200)
				{					
					var res = xmlhttp.responseText;						
					if(res != 'error')
					{						
						document.getElementById('fill_info').style.display	= 'none';
						document.getElementById('list_info').style.display	= '';	
						document.getElementById('show_full_name').innerHTML	= title + f_name + ' ' + l_name;
						document.getElementById('show_email').innerHTML		= email;
						document.getElementById('show_phone').innerHTML		= phone;
						document.getElementById('show_country').innerHTML	= country;
						document.getElementById('show_state').innerHTML		= state;
						document.getElementById('show_street').innerHTML	= street;
						
						//For paypal fields						
						<?php 
						if($paid == 0 || get_option('tgt_permit_payment') == 0) 
						{
						?>
							document.getElementById('u_id_free').value	= res;
							document.getElementById('show_payment_parent').innerHTML	= '';
						<?php
						}else  
						{
						?>					
							if(agree_purchase == true)
							{								
								document.getElementById('show_payment').innerHTML	= '<?php _e('Purchase online','hotel'); ?>';
								if(document.getElementById('payment_service').value == 'paypal')
								{									
									//document.getElementById('paypal_return').value	= '<?php echo HOME_URL; ?>/?action=payment_success&room_type=<?php echo $room_type->ID ; ?>&num_rooms=<?php echo $num_rooms; ?>&date_in=<?php echo $date_in; ?>&date_out=<?php echo $date_out; ?>&total_price=<?php echo $paid.' '.$currency; ?>&u_id='+res;
									//document.getElementById('paypal_cancel_return').value	= '<?php echo HOME_URL; ?>/?action=payment_false&ero=Ero&u_id='+res;
									document.getElementById('payment_type').value	= 'paypal';
									document.getElementById('payment_page').value	= 'https://www.paypal.com/cgi-bin/webscr';
									document.getElementById('u_id').value	= res;
									document.getElementById('paypal_item_number').value	= res;
									document.getElementById('paypal_invoice').value	= 'Transaction '+res;
								}else if(document.getElementById('payment_service').value == '2checkout')
								{
									document.getElementById('payment_type').value	= '2checkout';
									document.getElementById('payment_page').value	= '';
									document.getElementById('u_id').value	= res;
								}
							}else
							{
								document.getElementById('show_payment').innerHTML	= '<?php _e('Cash','hotel'); ?>';
								document.getElementById('paypal_info').innerHTML = '';
								document.getElementById('paypal_info').innerHTML = '<form method="POST" name="submit_payment" id="submit_payment" action="<?php echo tgt_free_payment_link (); ?>"> <input type="hidden" name="payment_method" id="payment_method" value="cash">'
																					+ '<input type="hidden" name="room_type" id="room_type" value="<?php echo $room_type->ID; ?>">'
																					+ '<input type="hidden" name="num_rooms" id="num_rooms" value="<?php echo $num_rooms; ?>">'
																					+ '<input type="hidden" name="date_in" id="date_in" value="<?php echo $date_in; ?>">'
																					+ '<input type="hidden" name="date_out" id="date_out" value="<?php echo $date_out; ?>">'
																					+ '<input type="hidden" name="total_price" id="total_price" value="<?php echo $paid.' '.$currency; ?>">'
																					+ '<input type="hidden" name="u_id" id="u_id">'
																					+ '<input type="hidden" name="promotion" id="promotion" value="<?php echo $promotion_price[1]; ?>" />'
																					<?php
																					/*
																					 * Services information which user selected
																					 */							
																					if(isset($_POST['go_booking_page']) && !empty($_POST['go_booking_page']))
																					{
																						if(isset($_POST['services']) && !empty($_POST['services']))
																						{
																							$default_services = get_post_meta($_POST['roomtype'],META_ROOMTYPE_SERVICES,true);
																							foreach($_POST['services'] as $k => $v)
																							{
																								if($default_services[$v]['name'] != '')
																								{
																					?>
																									+ '<input type="hidden" name="service_name[<?php echo $default_services[$v]['name']; ?>]" value="<?php echo $currencysymbol.$default_services[$v]['price'] ?>" />'									
																					<?php											
																								}
																							}
																						}
																					}
																					?>
																					+ '<div style="clear:left;"></div><div id="search" style="margin-top:15px; float:right;">'
																					+ '<div id="search_left"></div><div id="search_center"><input type="submit" name="submit_continue" value="<?php _e('Confirm & Pay','hotel');?>" id="butt_save" class="png" /></div><div id="search_right"></div></div></form>';
								document.getElementById('u_id').value	= res;																
							}
						<?php
						}
						?>
					}else if(res =='error')
					{
						alert('<?php _e('Booking has a problem, please try again'); ?>');
					}
				}
			}			
			var queryString = "?action="+ act +"&title="+ title +"&email="+ email +"&phone="+ phone +"&f_name="+ f_name +"&l_name="+ l_name +"&country="+ country +"&state="+ state +"&street="+ street + "&room_type=<?php echo $room_type->ID;  ?>&date_in=<?php echo $date_in; ?>&date_out=<?php echo $date_out; ?>&num_rooms=<?php echo $num_rooms;?>&agree_purchase="+agree_purchase+"&paid=<?php echo $paid; ?>";	
			
			xmlhttp.open("GET", "<?php echo HOME_URL . "/wp-content/themes/" . FOLDER_STR . "/ajax/ajax_for_booking.php"?>"+queryString, true);
			xmlhttp.send(); 
		}
	}else
		alert('<?php _e('You should check the agreement with using booking online service!','hotel'); ?>');
}

</script>