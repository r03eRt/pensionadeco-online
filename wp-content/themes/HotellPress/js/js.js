/**
 * get the current date 
 */

jQuery(document).ready(function(){
	
	jQuery('a[name=modal]').click(function(){
		var box = jQuery('#search_box'),
			width = box.width(),
			height = box.height(),
			scrollTop = jQuery(document).scrollTop(),
			scrollLeft = jQuery(document).scrollLeft();
		
		leftPosition = (width / 2) * (-1);
		topPosition = (height / 2) * (-1);
		window_left = jQuery(window).width() / 2;
		window_top = jQuery(window).height() / 2;
		pos_top = scrollTop + window_top - (height / 2);
		pos_left = scrollLeft + window_left - (width / 2);
		//alert(box.offset().top);
		//alert(box.offset().top);
		//box.offset({left: screenWidth + leftPosition + scrollLeft, top: screenHeight + topPosition + scrollTop});
		console.log('window screen ( width:' +  window_left +  ' , height: ' + window_top +' )');
		console.log('document scroll ( left:' +  scrollLeft +  ' , top: ' + scrollTop +' )');
		console.log('box width( width:' +  width +  ' , top: ' + height +' )');
		console.log('total ( width:' +  pos_left +  ' , top: ' + pos_top +' )');
		box.fadeIn(1000);
		jQuery('#search_box').css('top', pos_top + 'px').css('left', pos_left + 'px');
		return false;
	});
	
	jQuery('#search_box .dialog-header a.close').click(function(){
		jQuery('#search_box').fadeOut(500);
		return false;
	});
	
	jQuery('.datepicker').datepicker();
	searchfrom_initialize();
	
	jQuery('.inline-field').each(function(){
		var current = jQuery(this),
			label = current.find('label'),
			input = current.find('input[type=text]'),
			value = input.val();
		
		label.show();
		
		input.focusin(function() {
			label.hide();
		});
		
		input.focusout(function(){
			if ( input.val() == '' )
				label.css('display', 'block');
		})
		
	});
});

function searchfrom_initialize()
{
	var popup_from = jQuery('.dialog-content').find('input[name=from]'),
		popup_to = jQuery('.dialog-content').find('input[name=to]');
	
	jQuery('.dialog-content').find('input[name=from], input[name=to]').datepicker('option', 'minDate', new Date() );
	popup_from.change(function(){
		//popup_to.datepicker('option' , 'minDate', jQuery(this).val() );
		var date = jQuery(this).datepicker('getDate'),
		y 			= date.getFullYear(),
		m 			= date.getMonth(),
		d			= date.getDate();
		//alert( popup_from.attr('id') );
		popup_to.datepicker('option' , 'minDate', new Date(y, m, d + 1) );
	});
	popup_to.change(function(){
		//popup_from.datepicker('option' , 'maxDate', jQuery(this).val() );
		var date = jQuery(this).datepicker('getDate'),
		y 			= date.getFullYear(),
		m 			= date.getMonth(),
		d			= date.getDate();
		//alert( popup_from.attr('id') );
		popup_from.datepicker('option' , 'maxDate', new Date(y, m, d - 1) );
	});
	
	jQuery('#start-date, #end-date').datepicker('option', 'minDate', new Date() );
	jQuery('#start-date').change(function(){
		var date = jQuery(this).datepicker('getDate'),
		y 			= date.getFullYear(),
		m 			= date.getMonth(),
		d			= date.getDate();
		jQuery('#end-date').datepicker('option' , 'minDate', new Date(y, m, d + 1) );
	});
	jQuery('#end-date').change(function(){
		var date = jQuery(this).datepicker('getDate'),
		y 			= date.getFullYear(),
		m 			= date.getMonth(),
		d			= date.getDate();
		jQuery('#start-date').datepicker('option' , 'maxDate', new Date(y, m, d - 1) );
	});
}


