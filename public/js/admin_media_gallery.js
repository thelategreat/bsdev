
var Gallery = function() 
{
	// everything is private
	var _P = {
		// defaults
		params: {
			width: 600,
			page_size: 18,
		},

		init : function( params ) {
	    _P.params = $.extend( true, _P.params, params );
			this.render(1, null);
		},
		
		recalc : function() {
		  //Get our elements for faster access and set overlay width
		  var div = $('div.gallery'),
		    ul = $('ul.gallery'),
		    ulPadding = 15;

    	div.css({
  		  overflow: 'hidden',
        width: _P.params['width'] + 'px'
      });

		  var divWidth = div.width();
		  
		  var lastLi = ul.find('li:last-child');
      
		  div.mousemove(function(e) {
		      var ulWidth = lastLi[0].offsetLeft + lastLi.outerWidth() + ulPadding;
		      var left = (e.pageX - div.offset().left) * (ulWidth - divWidth) / divWidth;
		      div.scrollLeft(Math.abs(left-ulPadding));
		  });				
		},
		
		render : function( page, query ) {
		  if( query == null ) {
		    query = '';
		  }
			$('#gallery-div').html("<img src='/img/ajax-loader.gif' />");
			$.post('/admin/media/gallery/' + page + '/' + _P.params.page_size, { page: page, page_size: _P.params.page_size, q: query },
				function( data ) {
					if( data.error ) {
						alert( 'Error: ' + data.error_msg );
						$('#gallery-div').html('');
						return;
					} else {
						var html = '';
						html += '<ul class="gallery">';
						for( var i = 0; i < data.items.length; i++ ) {
							html += '<a href="#" title="' + data.items[i].caption + '" onclick="Gallery.onclick(\'' + data.items[i].uuid + '\'); return false;">';
							if( data.items[i].type == 'link' ) {
								html += '<li><img src="' + data.items[i].thumbnail + '" /></li>';
							} else {
								html += '<li><img src="/media/' + data.items[i].uuid + '" /></li>';						
							}
							html += '</a>';
						}
						html += '</ul>';
						$('#gallery-div').html( html );	
						
						if( page > 1 ) {
							$('#prev-button').html('<a href="#" onclick="Gallery.render(' + (page - 1) + ');" title="prev page"><img class="arrow" src="/img/cal/arrow_left.png" width="24px"/></a>');
						} else {
							$('#prev-button').html('');
						}
						if( data.items.length != _P.params.page_size ) {
							$('#next-button').html('');
						} else {
							$('#next-button').html('<a href="#" onclick="Gallery.render(' + (page + 1) + ');" title="next page"><img class="arrow" src="/img/cal/arrow_right.png" width="24px"/></a>');
						}
						_P.recalc();
					}
				}, "json" );				
		},
		
		onclick : function( ) {
			// does diddly
		}
	};
	
	// the public bits here
	return {
		init : function( params ) {
			// grab anything that is a function and put it in here
			// also, remove it from params as those are hidden functions
			// and there is no way to get at them
		  for( var attr in params ) {
					if( typeof(params[attr]) == "function" ) {
						this[attr] = params[attr];
						delete params[attr];
					}
		  }
			_P.init( params );
		},
		render : function( page, query ) {
			_P.render( page, query );
		},
		onclick : function( ) {
			_P.onclick( )
		}
	}
	
}();

