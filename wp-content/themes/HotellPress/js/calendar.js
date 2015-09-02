//<![CDATA[
 
 
var lpindex = 1;
var liveprices = Array(['526|Bali (Crete)|Bali_Crete|Greece|Greece'],['402|Gullane|Gullane|United Kingdom|United_Kingdom'],['270|Bergen|Bergen|Norway|Norway'],['251|Bois-de-Villers|Bois_de_Villers|Belgium|Belgium'],['189|Waterford (Connecticut)|Waterford_Connecticut|United States|United_States'],['166|Riederalp|Riederalp|Switzerland|Switzerland'],['144|Bodrum|Bodrum|Turkey|Turkey'],['136|La Jolla|La_Jolla|United States|United_States'],['129|Solon|Solon|United States|United_States'],['125|Tok|Tok|United States|United_States']);
 
jQuery(document).ready(function($)
{
		
	// Get Current Date
 
	var d = new Date();
	var curr_date = d.getDate();
	var curr_month = d.getMonth()+1;
	var curr_year = d.getFullYear()+1;
	if(curr_month < 10) { curr_month = '0'+curr_month; }
	if(curr_date < 10) curr_date = '0'+curr_date;
	var currentDate = curr_year+'-'+curr_month+'-'+curr_date;
	
	
	// Date Picker
	Date.format = 'yyyy-mm-dd';
	$('.date-pick').datePicker({clickInput:true, displayClose: true,verticalOffset: 14,horizontalOffset: -25,endDate: currentDate});
	
	$('#start-date').bind(
		'dateSelected',
		function(e, selectedDates, $td, status) {
				var d=selectedDates;
				if(d) {
					d = new Date(d);
					$('#end-date').dpSetStartDate(d.addDays(1).asString());
					$('#end-date').dpSetDisplayedMonth(d.getMonth(),d.getYear());
				}
		}
	);
 
 
 
	$('#end-date').bind(
		'dateSelected',
		function(e, selectedDates)
		{
			var d = selectedDates;
			if (d) {
				d = new Date(d);
			}
		}
	);
	
	$('#cityname').autocomplete("/auto.php",
								{
									minChars: 3
								});
	$('#cityname').select().focus();
	$('#cityname').attr("autocomplete","off");
	$('#start-date').attr("autocomplete","off");
	$('#end-date').attr("autocomplete","off");
	
		$(document).everyTime("3s","rotatePrices",function(i) {
		rotateLivePrices();
	});
	
	// logReqTime('79.118.100.194','/','0.26323700 1278441568');
});
 
function rotateLivePrices() {
 
	if(lpindex == liveprices.length) {
		lpindex = 0;
	}
 
	lps = liveprices[lpindex];	
	price = lps.toString().split('|');
	
	$('.lpPrice').html(price[0]);	
	$('.lpCity').html('<a href="http://www.bookingadvisor.com/t/'+price[2]+'.htm">'+price[1]+'</a>, <a href="http://www.bookingadvisor.com/c/'+price[4]+'.htm">'+price[3]+'</a>');
	lpindex++;
}
 
function urlencode( str ) {
                                  
    var ret = str;
    
    ret = ret.toString();
    ret = encodeURIComponent(ret);
    ret = ret.replace(/%20/g, '+');
 
    return ret;
}
 
function logSearch(ip,action,ref) {
	var out = urlencode($('#cityname').val());
	var rurl = '/action.php?a=cl&ip='+ip+'&action='+action+'&ref='+urlencode(ref)+'&out='+out;
	jQuery.get(rurl);	
}
 
function logReqTime(ip,uri,stime) {
	var uri = urlencode(uri);
	var rurl = '/action.php?a=rt&ip='+ip+'&uri='+uri+'&st='+stime;
	jQuery.get(rurl);	
}
 
/* Modified To support Opera */
function BookmarkSite(title,url){
if (window.sidebar) // firefox
    window.sidebar.addPanel(title, url, "");
	else if(window.opera && window.print){ // opera
    var elem = document.createElement('a');
    elem.setAttribute('href',url);
    elem.setAttribute('title',title);
    elem.setAttribute('rel','sidebar');
    elem.click();
}
else if(document.all)// ie
    window.external.AddFavorite(url, title);
}
 
function submitSearchForm() {
	// validate
	var errormsg = "Oops, something went wrong:\n\n";
	var error = false;
	
	if($('#cityname').val() == 'Enter city name...') {
		alert("Oops, something went wrong..\n\n- You haven\'t typed any city name!\n\nPlease fix the errors above and try again.");
		return 0;
	}
	
	if($('#cityname').val().length < 4) {
		errormsg=errormsg+"- City Name Too Short\n";
		error = true;
	}
 
	if(($('#start-date').val().length < 10) || $('#start-date').val() == 'yyyy-mm-dd' ) {
		$('#start-date').dpDisplay();
		return false;
	//	errormsg=errormsg+"- Check-in Date Missing or Wrong Format\n";
	//	error = true;
	}	
	
	if(($('#end-date').val().length < 10) || $('#end-date').val() == 'yyyy-mm-dd') {
		$('#end-date').dpDisplay();
		return false;
	}		
	errormsg = errormsg + "\nPlease fix the errors above and try again.";
 
	if(!error)  { jQuery('#search_form').submit(); return true;}
	else
		alert(errormsg);	
}
//]]>