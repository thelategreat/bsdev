/**
 This is the media browser 
*/
var MediaBrowser = function() 
{
	// everything is private
    var _P = {
       /*
        *
        */
        params: {
          width : 900,
          height : 500,
          path : null
        },

       /*
        *
        */
        init : function( params ) {
          for( var attr in params ) {
            _P.params[attr] = params[attr];
          }
      		$('#editModalDiv').modal({
      			overlayCss: {
      				backgroundColor: '#000', 
      				cursor: 'wait'
      			},
      			containerCss: {
      				height: _P.params['height'],
      				width: _P.params['width'],
      				backgroundColor: '#fff',
      				border: '3px solid #ccc',
      				overflow: 'auto'
      			},
      			onClose: _P.modal_close,
      			onOpen: _P.modal_open
      		});	
      		
        },

        browse : function ( pg ) {
            var query = "";
            if( !pg | pg < 1 ) {
                pg = 1;
            }
            if( $("#q").length ) {
                query = $("#q").val();
            }
      			$.post('/admin/media/search', { ajax: 1, q: query, pg: pg, path: _P.params['path'] },
      				function(data) {                
      					$('#popup_content').html( data );
      					$('#search_form').submit(function() {
      					   MediaBrowser.search_view(); 
      					   return false;
      					});
      	    });			
        },

    		/*
    		 *
    		 */
    		reload : function() {
    		    this.browse();
    		},
        
    		/*
    		 *
    		 */
    		modal_open : function( dialog ) {
    			var ediv = $('#editModalDiv');

    			_P.reload();

    			dialog.overlay.fadeIn('fast', function() {
    				dialog.container.fadeIn('slow',function() {
    					dialog.data.hide().slideDown('fast');
    				});	
    			});				
    		},

    		/*
    		 *
    		 */
    		modal_close : function( dialog ) {
    			dialog.data.fadeOut('slow',function() {
    				dialog.container.hide('slow', function() {
    					$.modal.close();
    				});
    			});			
    		}

    	};

  	// we expose the public bits here
  	return {
  		init : function( params ) {
  			_P.init( params );
  		},
  		reload : function() {
  			_P.reload();
  		},
  		media_view : function() {
  			_P.media_view();
  		},
  		search_view : function(pg) {
  			_P.search_view(pg);
  		}
  	}

}();

