jQuery(document).ready(function($){
	
	$('.date-pick, .datepicker').datepicker({ minDate: new Date() });
	
});

(function($){
	$.fn.eventCalendar = function(eventsList)
	{
		var container = $(this),
			containerID = container.attr('id'),
			tooltip = $('<div id="tooltip-wrapper" style="display: none">'),
			fade = true;
		$('body').prepend(tooltip);
		
		container.fullCalendar({
			header: { left : 'prev,next today' , center : 'title' , right: 'month,agendaWeek,agendaDay'},
			editable: false,
			eventMouseover: function(event, jsEvent, view){
				var url = '#booking_'+event.id;				
				tooltip = $('#tooltip-wrapper');
				tooltip.html( $(url).clone() );
				posX = jsEvent.pageX - ( tooltip.width() / 2);
				posY = jsEvent.pageY - ( tooltip.height() + 20);
				//alert( posX + ',' +posY  );
				tooltip.fadeIn().offset({ left: posX , top: posY });
				
				return false;
			},
			eventMouseout: function(event, jsEvent, view){
				//alert('test');
				$('#tooltip-wrapper').stop(true, true).fadeOut();
			},
			events: eventsList
		});
	}
})(jQuery);


//
//function displayBookingCalendar( divID , hightlights)
//{
//	$(divID).datepicker({
//		dateFormat: 'yy-mm-dd',
//		numberOfMonths: 2,
//		disabled: true,
//		beforeShowDay: function( date )
//		{
//			for ( i = 0; i < hightlights.length ; i++ )
//			{	
//				if ( date.getMonth() == (hightlights[i].month - 1) &&
//						date.getFullYear() == hightlights[i].year &&
//						date.getDate() ==  hightlights[i].day )
//				{
//					return [true,'ui-date-highlight', 'Click to see all reservations on this day'];
//				}				
//			}
//			return [true];
//		},
//		// search booking after select an
//		onSelect: function (dateText, inst)
//		{			
//			var container = $( divID ),
//				element = container.find( "a.ui-state-active" ),
//				isHighlight = element.parent().hasClass('ui-date-highlight'),
//				requestUrl = $('meta[name=url]').attr('content') + '/?action=ajax';
//				
//			do_search(dateText);			
//		}		
//	});
//	
//	function do_search(dateText)
//	{
//		var requestUrl = $('meta[name=url]').attr('content') + '/?action=ajax';
//		setTimeout(function(){
//			$.ajax({
//			url : requestUrl,
//			type: 'post',
//			data: ({
//					action_name : "ajax_get_bookings_by_date",
//					date: dateText
//					}),
//			success: function(data){
//				//var tooltip = $( '<div class="tooltip-wrapper">' ).html( data ),
//				//	container = $( divID ),
//				//	element = container.find( "a.ui-state-active" );
//				//
//				//$('.tooltip-wrapper').remove();
//				//$('body').prepend( tooltip );
//				//
//				//tooltip.offset( {top: element.offset().top + element.height(),
//				//					left: element.offset().left + element.width() } );
//				
//				$('.tooltip-wrapper').html(data);
//				
//				$(document).click(function(e){
//					var current = $('.tooltip-wrapper');
//					if ( !$.contains( $('.tooltip-wrapper').get()[0], e.target ) && !$(e.target).hasClass( 'tooltip-wrapper' ) )
//					{
//						current.fadeOut('slow', function(){ current.remove() } );
//					}
//				});				
//			},
//			beforeSend: function(){
//				var tooltip = $( '<div class="tooltip-wrapper">' ).html('loading...'),
//					container = $( divID ),
//					element = container.find( "a.ui-state-active" );
//				
//				$('.tooltip-wrapper').remove();
//				$('body').prepend( tooltip );
//				
//				tooltip.offset( {top: element.offset().top + element.height(),
//									left: element.offset().left + element.width() } );
//			}
//		});
//	}, 300);
//		
//	}
//}