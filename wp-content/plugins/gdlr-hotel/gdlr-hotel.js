(function($){
	"use strict";

	$.fn.gdlr_datepicker_range = function(){
		$(this).datepicker({
			minDate: 0,
			dateFormat : 'yy-mm-dd',
			numberOfMonths: [1, 2],
			beforeShowDay: function(date) {
				var date1 = $.datepicker.parseDate('yy-mm-dd', $("#gdlr-check-in").val());
				var date2 = $.datepicker.parseDate('yy-mm-dd', $("#gdlr-check-out").val());
				return [true, date1 && ((date.getTime() == date1.getTime()) || (date2 && date >= date1 && date <= date2)) ? "dp-highlight" : ""];
			},
			onSelect: function(dateText, inst) {
				var date1 = $.datepicker.parseDate('yy-mm-dd', $("#gdlr-check-in").val());
				var date2 = $.datepicker.parseDate('yy-mm-dd', $("#gdlr-check-out").val());
				if (!date1 || date2) {
					$("#gdlr-check-in").val(dateText);
					$("#gdlr-check-out").val("");
				} else {
					$("#gdlr-check-out").val(dateText).trigger('change');
				}
			},
			closeText: objectL10n.closeText,
			currentText: objectL10n.currentText,
			monthNames: objectL10n.monthNames,
			monthNamesShort: objectL10n.monthNamesShort,
			dayNames: objectL10n.dayNames,
			dayNamesShort: objectL10n.dayNamesShort,
			dayNamesMin: objectL10n.dayNamesMin,
			firstDay: objectL10n.firstDay
		});	
	}
	
	$.fn.gdlr_datepicker = function(){
		$(this).datepicker({
			dateFormat : 'yy-mm-dd',
			minDate: 0,
			changeMonth: true,
			changeYear: true,
			closeText: objectL10n.closeText,
			currentText: objectL10n.currentText,
			monthNames: objectL10n.monthNames,
			monthNamesShort: objectL10n.monthNamesShort,
			dayNames: objectL10n.dayNames,
			dayNamesShort: objectL10n.dayNamesShort,
			dayNamesMin: objectL10n.dayNamesMin,
			firstDay: objectL10n.firstDay
		});
	}

	$.fn.gdlr_single_booking = function(){
			
		var resv_bar = $(this);	
		$(this).find('.gdlr-datepicker').gdlr_datepicker(); 
		
		// check in date and night num change
		$(this).on('change', '#gdlr-night, #gdlr-check-in', function(){
			var check_in = resv_bar.find('#gdlr-check-in');
			var check_out = resv_bar.find('#gdlr-check-out');
			var night_num = resv_bar.find('#gdlr-night');
			
			if( check_in.val() ){
				var check_out_date = check_in.datepicker('getDate', '+1d'); 
				check_out_date.setDate(check_out_date.getDate() + parseInt(night_num.val()));
				
				check_out.datepicker('setDate', check_out_date);
				
				var check_out_min = check_in.datepicker('getDate', '+1d'); 
				check_out_min.setDate(check_out_min.getDate() + 1);
				
				check_out.datepicker('option', 'minDate', check_out_min);
			}
		});
		
		// check out date change
		$(this).on('change', '#gdlr-check-out', function(){
			var check_in = resv_bar.find('#gdlr-check-in').datepicker('getDate');
			var check_out = $(this).datepicker('getDate');
			var date_diff = (check_out - check_in) / 86400000; // 1000/60/60/24
			
			if( check_in && date_diff > 0 ){
				var night_num = resv_bar.find('#gdlr-night');
				if( night_num.children('option[value="' + date_diff + '"]').length == 0 ){
					night_num.append('<option value="' + date_diff + '" >' + date_diff + '</option>')
				}
				$('#gdlr-night').val(date_diff);
			}
		});

		// amount change
		$(this).on('change', '#gdlr-room-number', function(){
			var amount = parseInt($(this).val());
			var resv_room = resv_bar.find('#gdlr-reservation-people-amount-wrapper');
			var room_diff = amount - resv_room.children().length;
			if( room_diff > 0 ){
				for( var i=0; i<room_diff; i++ ){
					var new_room = resv_room.children(':first-child').clone().hide();
					new_room.find('.gdlr-reservation-people-title span').html(resv_room.children().length + 1);
					new_room.appendTo(resv_room).slideDown(200);
				}
			}else if( room_diff < 0 ){
				resv_room.children().slice(room_diff).slideUp(200, function(){
					$(this).remove();
				});
			}
		});		
	}
	
	$.fn.gdlr_hotel_booking = function(){

		var area = {
			wrapper: $(this),
			resv_bar: $(this).find('#gdlr-reservation-bar'),
			room_form: $(this).find('#gdlr-reservation-bar-room-form'),
			date_form: $(this).find('#gdlr-reservation-bar-date-form'),
			summary_form: $(this).find('#gdlr-reservation-bar-summary-form'),
			
			proc_bar: $(this).find('#gdlr-booking-process-bar'),
			content_area: $(this).find('#gdlr-booking-content-inner'),
		};
		
		var resv_bar = {
			init: function(){
				
				// check in date and night num change
				area.resv_bar.on('change', '#gdlr-night, #gdlr-check-in', function(){
					var check_in = area.resv_bar.find('#gdlr-check-in');
					var check_out = area.resv_bar.find('#gdlr-check-out');
					var night_num = area.resv_bar.find('#gdlr-night');
					
					if( check_in.val() ){
						var check_out_date = check_in.datepicker('getDate', '+1d'); 
						check_out_date.setDate(check_out_date.getDate() + parseInt(night_num.val()));

						check_out.datepicker('setDate', check_out_date);
						
						var check_out_min = check_in.datepicker('getDate', '+1d'); 
						check_out_min.setDate(check_out_min.getDate() + 1);
						
						check_out.datepicker('option', 'minDate', check_out_min);
					}
				});
				
				// check out date change
				area.resv_bar.on('change', '#gdlr-check-out', function(){
					var check_in = area.resv_bar.find('#gdlr-check-in').datepicker('getDate');
					var check_out = $(this).datepicker('getDate');
					var date_diff = (check_out - check_in) / 86400000; // 1000/60/60/24
					
					if( check_in && date_diff > 0 ){
						var night_num = area.resv_bar.find('#gdlr-night');
						if( night_num.children('option[value="' + date_diff + '"]').length == 0 ){
							night_num.append('<option value="' + date_diff + '" >' + date_diff + '</option>')
						}
						$('#gdlr-night').val(date_diff);
					}
				});
				
				// amount change
				area.resv_bar.on('change', '#gdlr-room-number', function(){
					var amount = parseInt($(this).val());
					var resv_room = area.resv_bar.find('#gdlr-reservation-people-amount-wrapper');
					var room_diff = amount - resv_room.children().length;
					if( room_diff > 0 ){
						for( var i=0; i<room_diff; i++ ){
							var new_room = resv_room.children(':first-child').clone().hide();
							new_room.find('.gdlr-reservation-people-title span').html(resv_room.children().length + 1);
							new_room.appendTo(resv_room).slideDown(200);
						}
					}else if( room_diff < 0 ){
						resv_room.children().slice(room_diff).slideUp(200, function(){
							$(this).remove();
						});
					}
					
				});	

				// check availability button
				area.resv_bar.on('click', '#gdlr-reservation-bar-button', function(){
					main.change_state({ state: 2 });
					$(this).slideUp(200, function(){ $(this).remove(); })
					return false;
				});
				
				// query again when input change
				area.resv_bar.on('change', '#gdlr-check-in, #gdlr-night, #gdlr-check-out, #gdlr-room-number, #gdlr-hotel-branches, ' + 
					'select[name="gdlr-adult-number[]"], select[name="gdlr-children-number[]"]', function(){
					
					if( parseInt(area.proc_bar.attr('data-state')) > 1 ){
						area.room_form.slideUp(function(){
							$(this).html('').removeClass('gdlr-active');
							main.change_state({ state: 2 });
						});
						
					}
				});
			}	
		}
		
		var proc_bar = {
			get_state: function(){
				return area.proc_bar.attr('data-state');
			},
			
			set_state: function( state ){
				area.proc_bar.attr('data-state', state);
				area.proc_bar.children('[data-process="' + state + '"]').addClass('gdlr-active').siblings().removeClass('gdlr-active');
			}
		}
		
		var main = {
			init: function(){

				// init date picker
				area.wrapper.find('.gdlr-datepicker').gdlr_datepicker(); 
				area.wrapper.find("#gdlr-datepicker-range").gdlr_datepicker_range();
				
				// reservation bar event
				resv_bar.init();
				
				// room selection event
				this.room_select();
				
				// contact form event
				this.contact_submit();
			},
			
			room_select: function(){
				area.content_area.on('click', '.price-breakdown-close', function(){
					$(this).closest('.price-breakdown-wrapper').fadeOut(200);
					return false;
				});
				area.content_area.on('click', '.gdlr-price-break-down', function(){
					$(this).children('.price-breakdown-wrapper').fadeIn(200);
				});
				
				area.content_area.on('click', '.gdlr-room-selection',function(){
					area.room_form.find('.gdlr-active input').val($(this).attr('data-roomid'));
					main.change_state({ state: 2 });
					return false;
				});
				
				area.content_area.on('click', '.gdlr-pagination a', function(){
					main.change_state({ paged: $(this).attr('data-paged'), state: 2 });
					return false;
				});
				
				area.room_form.on('click', '.gdlr-reservation-change-room',function(){
					$(this).closest('.gdlr-reservation-room').find('input').val('');
					main.change_state({ state: 2 });
					return false;
				});
				
				area.content_area.on('click', '.gdlr-room-selection-next',function(){
					main.change_state({ state: 3 });
					return false;
				});
				
				// edit booking summary event
				area.summary_form.on('click', '#gdlr-edit-booking-button', function(){
					area.room_form.find('.gdlr-reservation-room:first-child input').val('');
					main.change_state({ state: 2 });
					return false;
				});
				
				area.summary_form.on('change', 'input[name="pay_deposit"]', function(){
					if($(this).val() == 'true'){
						area.summary_form.find('.gdlr-price-deposit-inner-wrapper').slideDown();
						area.summary_form.find('.gdlr-price-summary-grand-total').removeClass('gdlr-active');
						area.summary_form.find('input[name="pay_deposit"][value="true"]').closest('span').addClass('gdlr-active');
						area.summary_form.find('input[name="pay_deposit"][value="false"]').closest('span').removeClass('gdlr-active');
					}else{
						area.summary_form.find('.gdlr-price-deposit-inner-wrapper').slideUp();
						area.summary_form.find('.gdlr-price-summary-grand-total').addClass('gdlr-active');
						area.summary_form.find('input[name="pay_deposit"][value="true"]').closest('span').removeClass('gdlr-active');
						area.summary_form.find('input[name="pay_deposit"][value="false"]').closest('span').addClass('gdlr-active');
					}
					return false;
				});
				
			},
			
			contact_submit: function(){
				area.content_area.on('click', '.gdlr-booking-contact-submit', function(){
					if( !$(this).hasClass('gdlr-clicked') ){
						$(this).addClass('gdlr-clicked');
						area.content_area.find('.gdlr-error-message').slideUp();
						main.change_state({ state: 3, contact: $(this).closest('form'), 'contact_type': 'contact' });
					}
					return false; 
				});
				
				area.content_area.on('click', '.gdlr-booking-payment-submit', function(){
					if( !$(this).hasClass('gdlr-clicked') ){
						$(this).addClass('gdlr-clicked');
						area.content_area.find('.gdlr-error-message').slideUp();
						main.change_state({ state: 3, contact: $(this).closest('form'), 'contact_type': 'instant_payment' });
					}
					return false; 
				});
				
				// payment method selection
				area.content_area.on('click', '.gdlr-payment-method input[name="payment-method"]',function(){
					$(this).parent('label').addClass('gdlr-active').siblings().removeClass('gdlr-active');
				});
			},
			
			change_state: function( options ){
				area.content_area.animate({'opacity': 0.2});
				area.content_area.parent().addClass('gdlr-loading');
				
				var data_submit = { 
					'action': 'gdlr_hotel_booking', 
					'data': area.resv_bar.serialize(), 
					'state': options.state 
				};
				if( options.contact ) data_submit.contact = options.contact.serialize();
				if( options.contact_type ) data_submit.contact_type = options.contact_type;
				if( options.paged ) data_submit.paged = options.paged;

				$.ajax({
					type: 'POST',
					url: area.wrapper.attr('data-ajax'),
					data: data_submit,
					dataType: 'json',
					error: function( a, b, c ){ console.log(a, b, c); },
					success: function( data ){
						// console.log(data.data);
						
						if( data.state ){
							proc_bar.set_state(data.state);
							
							if( data.content ){
								var tmp_height = area.content_area.height();
								area.content_area.html(data.content);
								
								var new_height = area.content_area.height();
								
								area.content_area.parent().removeClass('gdlr-loading');
								area.content_area.height(tmp_height).animate({'opacity': 1, 'height': new_height}, function(){
									$(this).css('height', 'auto');
								});
							}
							if( data.summary_form ){
								if( !area.summary_form.hasClass('gdlr-active') ){
									area.summary_form.html(data.summary_form).slideDown().addClass('gdlr-active');
								}else{
									var tmp_height = area.summary_form.height();
									area.summary_form.html(data.summary_form);
									
									var new_height = area.summary_form.height();
									area.summary_form.height(tmp_height).animate({'height': new_height}, function(){
										$(this).css('height', 'auto');
									});
								}
							}
								
							if( data.state == 2 ){
								area.summary_form.slideUp(function(){ $(this).removeClass('gdlr-active'); });
								area.date_form.slideDown();
								
								if( data.room_form ){
									if( !area.room_form.hasClass('gdlr-active') ){
										area.room_form.html(data.room_form).slideDown().addClass('gdlr-active');
									}else{
										var tmp_height = area.room_form.height();
										area.room_form.html(data.room_form);
										
										var new_height = area.room_form.height();
										area.room_form.height(tmp_height).animate({'height': new_height}, function(){
											$(this).css('height', 'auto');
										});
									}
								}
							}else if( data.state == 3 ){
								area.room_form.slideUp(function(){ $(this).removeClass('gdlr-active'); });
								area.date_form.slideUp();

								// error message on form submit
								if( data.error_message ){
									area.content_area.find('.gdlr-button').removeClass('gdlr-clicked');
									area.content_area.find('.gdlr-error-message').html(data.error_message).slideDown();
									
									area.content_area.parent().removeClass('gdlr-loading');
									area.content_area.animate({'opacity': 1});
								}
								
								// for payment option
								if( data.payment && data.payment == 'paypal' ){
									var form_submit = area.content_area.find('form.gdlr-booking-contact-form');
									form_submit.attr('method', 'post');
									form_submit.attr('action', data.payment_url);
									form_submit.append(data.addition_part);
									form_submit.submit();
									
								}
							}
						} // data.state
					}
				});	
			}
		};
		
		main.init();
		
		return this;
	}
	
	$(document).ready(function(){
		
		// init the booking page
		$('#gdlr-single-booking-content').gdlr_hotel_booking();
		
		// init single room page
		$('body.single-room #gdlr-reservation-bar, #gdlr-hotel-availability').gdlr_single_booking();
		
		// room category hover
		$('.gdlr-room-category-item').on('mouseover', '.gdlr-room-category-thumbnail', function(){
			$(this).children('img').transition({ scale: 1.1, duration: 200, queue: false });
			$(this).children('.gdlr-room-category-thumbnail-overlay').animate({opacity: 0.6}, {duration: 150, queue: false});
			$(this).children('.gdlr-room-category-thumbnail-overlay-icon').animate({opacity: 1}, {duration: 150, queue: false});
		});
		$('.gdlr-room-category-item').on('mouseout', '.gdlr-room-category-thumbnail', function(){
			$(this).children('img').transition({ scale: 1, duration: 200, queue: false });
			$(this).children('.gdlr-room-category-thumbnail-overlay').animate({opacity: 0}, {duration: 150, queue: false});
			$(this).children('.gdlr-room-category-thumbnail-overlay-icon').animate({opacity: 0}, {duration: 150, queue: false});
		});
	});

})(jQuery);