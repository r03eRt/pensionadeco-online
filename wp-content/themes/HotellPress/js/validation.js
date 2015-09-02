/**
 * Desc: library for validation such as : phone number, email  ...
 */

/**
 * function name : validateEmail(str)
 * desc: user for email validation
 * param: email string
 * return: true if input is a valid email, unless it will return false 
 */
function validateEmail(str) {

		var at="@";
		var dot=".";
		var lat=str.indexOf(at);
		var lstr=str.length;
		var ldot=str.indexOf(dot);
		if (str.indexOf(at)==-1){
		   return false;
		}

		if (str.indexOf(at)==-1 || str.indexOf(at)==0 || str.indexOf(at)==lstr){
		   return false;
		}

		if (str.indexOf(dot)==-1 || str.indexOf(dot)==0 || str.indexOf(dot)==lstr){
		    return false;
		}

		 if (str.indexOf(at,(lat+1))!=-1){
		    return false;
		 }

		 if (str.substring(lat-1,lat)==dot || str.substring(lat+1,lat+2)==dot){
		    return false;
		 }

		 if (str.indexOf(dot,(lat+2))==-1){
		    return false;
		 }
		
		 if (str.indexOf(" ")!=-1){
		    return false;
		 }

 		 return true;				
}

/**
 * VALIDATE PHONE NUMBER
 */


/**
 * function name : validatePhone(strPhone)
 * desc: user for email validation
 * param: phone number or string
 * return: true if input is a valid phone number, unless it will return false 
 */
function validatePhone(phoneNumber){
	if (phoneNumber.match(/^[0-9\-\ \(\)\+]+[0-9\-\ \(\)\+]$/)){
		return true;
	}
	return false;
}


function checkInput()
{
	var count = 0;			
	if(jQuery('input[name=customer_name]').val() == "")
	{
		jQuery('#name_error').html(customer_name_empty);
		count = count+1;
	}
	else 
	{
		jQuery('#name_error').html('');					
	}
	var email = jQuery('input[name=email]').val();
	if(email == "")
	{
		jQuery('#email_error').html(email_empty);										
		count = count+1;
	}
	else
	{
		if(!validateEmail(email))
		{
			jQuery('#email_error').html(email_invalid);
			count = count+1;
		}						
		else
		{
			jQuery('#email_error').html('');
		}						
	}
										
	var phoneNum = jQuery('input[name=phone]').val();
	if(phoneNum == "")
	{
		jQuery('#phone_error').html(phone_empty);
		count = count+1;	
	}
	else
	{
		if(!validatePhone(phoneNum))
		{
			jQuery('#phone_error').html(phone_invalid);
			count = count+1;	
		}
		else
			jQuery('#phone_error').html('');
	}
					
	var comment = jQuery('textarea[name=comments]').val();
	
	if(comment == "" || comment== null)
	{
		jQuery('#comment_error').html(message_empty);
		count = count+1;
	}		
	else
	{
		jQuery('#comment_error').html('');
	}				
					
	if (count > 0)
	{
		jQuery('#send_mail_error').html('');
		return false;
	}
	else
	{						
		return true;
	}
}			


				