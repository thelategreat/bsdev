$(function(){
  // Initialize slider
  $('#slider-1').liquidSlider({
  	 crossLinks:true,
	 continuous:false,
	 panelTitleSelector: "span.title", 
	 dynamicTabsAlign: "left", 
	 dynamicTabsPosition: "top",
	 mobileNavigation:false, 
	 dynamicArrows:true, 
	 slideEaseDuration: 1000, 
	 crossLinks:true, 
	 autoSlideControls: true, 
	 autoSlideStartText: '&#xe000;',
     autoSlideStopText: '&#xe001;',
	 autoSlideInterval: 7000, 
	 autoSlidePauseOnHover:false, 
	 autoSlideStopWhenClicked:false,
	});
});

$(function() {
    $('.start').addClass('cross-link active-thumb');
        $('ul.movers-row li a').click(function() {
        $('ul.movers-row li a').removeClass('cross-link active-thumb');
        $(this).addClass('cross-link active-thumb');             
   });
});