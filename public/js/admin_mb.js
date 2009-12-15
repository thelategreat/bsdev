/*
 * media browser
 */
var MediaBrowser = function() 
{
	// everything is private
    var _P = {
       /*
        *
        */
        params: null,

       /*
        *
        */
        init : function( params ) {
            _P.params = params;

    		$('#editModalDiv').modal({
    			overlayCss: {
    				backgroundColor: '#000', 
    				cursor: 'wait'
    			},
    			containerCss: {
    				height: 500,
    				width: 600,
    				backgroundColor: '#fff',
    				border: '3px solid #ccc',
    				overflow: 'auto'
    			},
    			onClose: _P.modal_close,
    			onOpen: _P.modal_open
    		});	

        },

        search_view : function () {
            var query = "";
            if( $("#q").length ) {
                query = $("#q").val();
            }
			$.post('/admin/media/search', 
				{ ajax: 1, q: query, pg: 1 },
				function(data) {
					$('#popup_content').html( data );
					$('#search_form').submit(function() {
					   MediaBrowser.search_view(); 
					   return false;
					});
				}
			);			
        },

        media_view : function () {
            this.reload();
        },

        
        add : function() {
            alert('adding');
        },


		/*
		 *
		 */
		reload : function() {
		    this.search_view();
		    /*
			$.post('/admin/media/browser', 
				{ path: _P.params.path },
				function(data) {
					$('#modal_content').html( data );
				}
			);
			*/			
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

                    $('.media_table a').each(function(i){
                        var href = $(this).attr('href');
                        $(this).attr('href', '');
                        $(this).bind('click', function() {
                           items = href.split('/');
                   			$.post('/admin/media/add', 
                   				{ path: _P.params['path'], 
                   				 'uuid' : items[items.length-1], 
                   				 'slot' : $('#slot_select').val() },
                   				function(data) {
                   				    //alert(data);
                				} );
                				return true;
                        });
                    });
                    
					/* http://valums.com/ajax-upload/ */
					if( typeof AjaxUpload != "undefined" ) {
    				    new AjaxUpload('upload_button', 
    						{
    							action: '/admin/media/upload/',
    							data: {
    								path: _P.params.path
    							},
    							onComplete: function( file, response ) {
    								_P.reload();
    							}
    						});
					}
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
		search_view : function() {
			_P.search_view();
		}
	}

}();
