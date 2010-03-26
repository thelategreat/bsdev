/*
 * some sugary jQuery extensions for field/form validation stuff
 */
(function($) {
	$.extend($.fn, {
		// is the field empty
		is_empty: function( ) {
			var obj = $(this);
			if( obj.val().length > 0 ) {
				return false;
			}
			return true;			
		},
		// is this a date
		is_date: function( options ) {
			var defaults = {
				format: 'yyyy-mm-dd'
			};
			var options = $.extend(defaults,options);
			var formats = {
				'yyyy-mm-dd' : new RegExp("\\d{4}-\\d{2}-\\d{2}")
			}
			var obj = $(this);
			if( formats[options.format].test(obj.val())) {
				return !/Invalid|NaN/.test(new Date(obj.val()));
			}
			return false;
		},
		// is this a number
		is_number: function()  {
			var obj = $(this);
			return  /^-?(?:\d+|\d{1,3}(?:,\d{3})+)(?:\.\d+)?$/.test(obj.val());
		},
		// valid email addy
		is_email: function() {
			var obj = $(this);
			return /^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i.test(obj.val());
		},
		// valid url
		is_url: function() {
			var obj = $(this);
			return /^(https?|ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(\#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i.test(obj.val());
		}
	});
})(jQuery);

var duration = null;

/*
 * Validation for event add/edit view
 */
function validate( thisForm, event )
{
	// look at this madness, no safari equivalent, grrrr
	// in safari event.target comes back as the form, not the button
	// Firefox || Opera || IE || unsupported
	var target =  event.explicitOriginalTarget || event.relatedTarget || document.activeElement || {};
	
	if (target.nodeType == 3) // defeat Safari bug
			target = target.parentNode;

  //alert(target);

	// server side will ignore this
	if( target.value == 'Cancel' )
		return true;
	
	if( $('#fld_title').is_empty() ) {
		alert("The event title cannot be blank");
		return false;
	}
	if( ! $('#fld_event_date_start').is_date() ) {
		alert("Start Date must be a valid date");
		return false;
	}
	if( ! $('#fld_event_date_end').is_date() ) {
		alert("End Date must be a valid date");
		return false;
	}
	// check the dates aren't stupid
	var st = new Date($('#fld_event_date_start').val());
	var en = new Date($('#fld_event_date_end').val());
	if( st > en ) {
		alert('Start date is after the end date.');
		return false;
	}
	// check time if single day event
	if( st.getTime() == en.getTime() ) {
		st = parseInt($('#fld_event_time_start_hour').val(), 10) * 60;
		st += parseInt($('#fld_event_time_start_min').val(), 10);
		if( $('#fld_event_time_start_am_pm').val() == 'pm') {
			st += 60 * 12;
		}
		
		en = parseInt($('#fld_event_time_end_hour').val(), 10) * 60;
		en += parseInt($('#fld_event_time_end_min').val());
		if( $('#fld_event_time_end_am_pm').val() == 'pm') {
			en += 60 * 12;
		}
		
		if( st > en ) {
			alert('Start time is after the end time');
			return false;
		}
	}
	return true;
}

function lookup( inp )
{
	if( inp.length == 0 ) {
		$('#autoSuggestBox').hide();
	} else {
		$.post("/admin/event/lookup", {query: ""+inp+"", cat: ""+$('#fld_category').val()+"" },
			function( data ) {
				var ht = '';
				$(data).find('item').each(function(foo) {
					ht = ht + '<li onclick="fill(\'' + $(this).attr('id') + '\',\''+$(this).attr('cat')+'\')">' + $(this).attr('name') + '</li>';
				});
				$('#autoSuggestBox').show();
				$('#autoSuggestList').html( ht );
			}, 'xml');
	}
}

function fill( id, cat )
{
	$.post("/admin/event/lookup", {id: ""+id+"", cat: ""+$('#fld_category').val()+"" },
		function( data ) {
			$(data).find('item').each(function() {
				var item = $(this);
				$('#fld_title').val(item.attr('title'));
				duration = item.attr('time');
				//$('#fld_body').val(item.find('description').text());
				tinyMCE.activeEditor.setContent(item.find('description').text());
			});
		}, 'xml' );
	$('#autoSuggestBox').hide();
}

// when user leaves the first date field 
function leave_first_date()
{
	if( $('#fld_event_date_end').val() == '' ) {
		$('#fld_event_date_end').val( $('#fld_event_date_start').val() );
	}
}
// changed category
function sel_category()
{
	duration = null;
}
// changed venue
function sel_venue()
{
}
// changed audience
function sel_audience()
{
}

function sel_event_time_start()
{
	if( duration ) {
		var min = parseInt(duration);
		var hour = parseInt(min / 60);
		var min = Math.round(parseInt(min % 60)/5)*5;
		var shour = parseInt($('#fld_event_time_start_hour').val());
		var smin = parseInt($('#fld_event_time_start_min').val());
		// TODO: this does not cross midnite properly
		if( smin + min > 60 ) {
			$('#fld_event_time_end_hour').val(''+(shour + hour + 1));			
			$('#fld_event_time_end_min').val(''+(smin + min - 60 ));			
		} else {
			$('#fld_event_time_end_hour').val(''+(shour + hour));			
			$('#fld_event_time_end_min').val(''+(smin + min));			
		}
	}
	$('#fld_event_time_end_am_pm').val($('#fld_event_time_start_am_pm').val());
}

$(function()
{
	Date.format = "yyyy-mm-dd";
	$('.date-pick').datePicker();
	$('#fld_event_date_start').blur( function() { leave_first_date(); });
});