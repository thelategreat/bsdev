$(function() {	

// Cufon font replacement
	Cufon.replace('.cufon', {
    	hover: true,
   		hoverables: { a: true }
 	});

 		
// Big feature slideshow
    $('ul#big_feature').cycle({
        fx:      'fade',
        timeout:  10000,
        pause:   'true',
        prev:    '#left_arrow',
        next:    '#right_arrow'
    });
    
    $('#stop').click(function() { 
	    $('ul#big_feature').cycle('pause'); 
	});
	
	$('#play').click(function() { 
	    $('ul#big_feature').cycle('resume'); 
	});
	
	
// Event media slider
	$('#event-media #photo_wrapper').cycle({
        fx:      'scrollHorz',
        timeout: 0,
        prev:    '#left_arrow',
        next:    '#right_arrow',
        easing: 'backinout'
    });
    
       
// Calendar slider
	$('#calendar_container').cycle({
        fx:      'scrollHorz',
        timeout: 0,
        prev:    '#left_arrow',
        next:    '#right_arrow',
    });    
    
    
// Search results slider
	$('#search-results #results_wrapper').cycle({
        fx:      'scrollHorz',
        timeout: 0,
        prev:    '#left_arrow',
        next:    '#right_arrow',
        easing: 'backinout'
    });    

    
// Address/category block in header
    $('ul#info_block').cycle({
        fx:      'fade',
        timeout:  30000
    });
		 
    	
// Makes calendar date change color when hovered over
	$('ul.calendar li.day.events ul.day_events, ul.calendar li.day.events a').mouseover(function(){
		$(this).parent().children().find("span.number").addClass("selected");
	});
	
	$('ul.calendar li.day.events ul.day_events, ul.calendar li.day.events a').mouseout(function(){
		$(this).parent().children().find("span.number").removeClass("selected");
	});
	
	
// Sidebar event listings slider
	$("#home #sidebar, #event #sidebar, #event-media #sidebar, #event-calendar #sidebar").jCarouselLite({
		btnNext: "a.next_listings",
    	btnPrev: "a.previous_listings",
    	vertical: true,
    	circular: false,
    	visible: 4,
    	scroll: 1
	});
	
	
// Show/hide buttons if at the first/last listing
	$('a.previous_listings, a.next_listings').click(function() {
		if($(this).hasClass('disabled')) {
			$(this).fadeOut("fast");
		} else {
			$("a.previous_listings:not('disabled'), a.next_listings:not('disabled')").fadeIn("fast");
		}
		return false;
	});

	
// Photo pop-ups on any page with classname fancybox
	$("a.fancybox").fancybox({
		'overlayShow': true,
		'overlayOpacity': .7,
		'zoomSpeedIn': 400,
		'zoomSpeedOut': 300
	}); 
	    
});



// Stylesheet switcher to test alternate colors - remove this after testing
// Must be commented out in order for IE 7 stylesheet to work
/*
$(document).ready(function() { 
    $("#stylesheet li a").click(function() { 
    	$("link").attr("href",$(this).attr('rel'));
    	$.cookie("css",$(this).attr('rel'), {expires: 365, path: '/'});
    	return false;
    });
    
    if($.cookie("css")) {
    	$("link").attr("href",$.cookie("css"));
    }
	
});
*/




