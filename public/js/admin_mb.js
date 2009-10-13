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

		/*
		 *
		 */
		reload : function() {
			$.post('/admin/media/browser', 
				{ path: _P.params.path },
				function(data) {
					$('#modal_content').html( data );
				}
			);			
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
		}
	}

}();
