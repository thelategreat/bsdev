$(document).ready(function() {
	$('#cal_week article').click(function() {
		var eventId = $(this).attr('event');
		var details = $('#details');
		var holder = $(this).parents('.divider');

		if ($(details).attr('eventId') == eventId) return; // Do nothing if we've already opened this
		// Get the details
		$.post('/calendar/details_json/', {eventId: eventId}, function(data) {
			if (data != null) {

				$(details).stop(true).animate({opacity: 'toggle', height: ['toggle', 'swing']}, 
						300, 'linear', function() {
					data.eventId = eventId;
					populateDetails(details, data);
					holder.after(details);
					details.fadeIn('fast', function() {
						var top = ($(details).offset().top);
						console.log(top);
						$('html, body').stop(true).animate({scrollTop:top}, {queue: false, duration: 800});	
					});
				});

			}	
		}, 'json');
	});

	$('#cal_month article').click(function() {
		var eventId = $(this).attr('event');
		var details = $('#details');
		var holder = $(this).parents('.container').parent();
		var article = $(this);

		var selected = $('.selected');
		if ($(details).attr('eventId') == eventId) return; // Do nothing if we've already opened this
		// Get the details
		$.post('/calendar/details_json/', {eventId: eventId}, function(data) {
			if (data != null) {
				if (selected.length > 0) {
					if ($(article).parent().has('#details').length == 1) {
						$(details).slideUp(200, function() {
							fadeInData(data);
						});
					} else {
						$(details).fadeOut(200, function() {
							$('.selected').stop(true).animate({width: ['12.5%', 'swing']}, 300, 'linear', function() {
								fadeInData(data);
							});
						});
					}
				} else {
					fadeInData(data);
				}

			}	
		}, 'json');

		function fadeInData(data) {
			$(holder).addClass('selected').stop(true).animate({width: ['24%', 'swing']}, 300, 'linear', function() {
				data.eventId = eventId;
				populateDetails(details, data);
				article.after(details);
				details.slideDown();
			});
		}
	});


});

function populateDetails(container, data) {
	$(container).attr('eventId', data.eventId);
	$(container).find('.title').html(data.title);
	$(container).find('.poster').attr('src', '/i/size/o/'+data.uuid+'/w/300');
	$(container).find('.director').html(data.director);
	$(container).find('.country').html(data.country);
	$(container).find('.year').html(data.year);
	$(container).find('.rating').html(data.rating);
	$(container).find('.running_time').html(data.running_time);
	$(container).find('.description').html(data.description);

}