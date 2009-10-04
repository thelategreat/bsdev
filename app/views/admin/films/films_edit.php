<script type="text/javascript" src="/js/ajaxupload.js" ></script>

<script language="javascript" type="text/javascript">

var image_path = null;

function edit_image( id )
{
  image_path = id;
  /*
	if( id != -1 ) {
		if( !get_event_data( id )) {
			return;
		}
	}
	*/
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
		onClose: modal_close,
		onOpen: modal_open
	});	
}

function modal_open( dialog )
{
	var ediv = $('#editModalDiv');
  /*
	$.get('/admin/calendar/new_event', function(data) {
		$('#modal_content').html( data );
	});
  */
  $('#modal_content').html( '<img src="' + image_path + '" />' );
	dialog.overlay.fadeIn('fast', function() {
		dialog.container.fadeIn('slow',function() {
			dialog.data.hide().slideDown('fast');
		});	
	});	
  
}

function modal_close( dialog ) 
{	
	dialog.data.fadeOut('slow',function() {
		dialog.container.hide('slow', function() {
			$.modal.close();
		});
	});
}


var MediaBrowser = function() {
  var _P = {

    /*
     *
     */
    init : function( params ) {
      _P.params = params;
      _P.loadXml();
    },
    params: null,
    data: null,

    /*
     *
     */
    loadXml: function() {
      $.ajax({
        type: "POST",
        url : _P.params.xmlPath,
        dataType : "xml",
        success : function( data ) {
          _P.data = data;
          _P.max = _P.params.perView;
          _P.count = $("image", data).length;
          _P.preload();
          _P.browse();
        }
      });
    },
    first: 0,
    max: 0,
    count: 0,

    /*
     *
     */
    preload: function() {
      $("ul","#media").empty();
      $( "#media .prev").css("visibility", "hidden");
      $( "#media .next").css("visibility", "hidden");
      $("image", _P.data ).each( function( i ) {
        var caption = $.trim( $("caption", this).text() );
        var href = $.trim( $("source", this).text() );
        $( "ul", "#media").append([
          "<li><a href='#' onclick='edit_image(\"" + href + "\")'",
          "><img src='",
          href,
          "' width='100' height='100' alt='More info' />",
          "</a></li>"
          ].join( ""));
      });
      $( "#media .prev").click( function() {
        _P.browse( "prev" );
      });
      $( "#media .next").click( function() {
        _P.browse( "next" );
      });
    },

    /*
     *
     */
    browse: function( browse ) {
      if( browse == "prev" ) {
        if( _P.first == _P.count && (_P.count % _P.max > 0 )) {
          _P.first = _P.first - (( _P.count % _P.max ) + _P.max );
        } else {
          _P.first = _P.first - ( _P.max * 2 );
        }
      }
      var range = _P.first + _P.max;
      var start = 1;
      if( range > _P.max ) {
        start = (( range - _P.max ) + 1 );
      }
      if( _P.first == 0 ) {
        $( "#media .prev").css("visibility", "hidden");
      } else {
        $( "#media .prev").css("visibility", "visible");        
      }
      if( range < _P.count ) {
        $( "#media .next").css("visibility", "visible");
      } else if (range >= _P.count ) {
        range = _P.count;
        $( "#media .next").css("visibility", "hidden");        
      }        
      $( "image", _P.data ).each( function( i ) {
        if( i >= _P.first && i < range ) {
          $( "#media li:eq(" + i + ")").fadeIn( "slow" );
        } else {
          $( "#media li:eq(" + i + ")").css( "display", "none");          
        }
      });      
      _P.first = range;
      $( "#media .showing" ).html([
        "Viewing <strong>",
        start,
        " - ",
        range,
        "</strong> of <strong>",
        _P.count,
        "</strong>"
        ].join(""));
    },
    
    /*
     *
     */
    tooltip : {
      show: function( e, $o ) {},
      hide: function( e, $o ) {},
      getMouse: function( v, e ) {},
      getViewport: function() {}
    }
  };
  
  return {
    init : function( params ) {
      _P.init( params );
    },
		reload : function() {
			_P.data = null;
			_P.first = 0;
			_P.loadXml();
		}
  }
}();


$(function() {
  var mb = MediaBrowser.init({
    xmlPath : "/admin/cinema/media/<?= $film->id ?>",
    imgPath : "images",
    perView : 4
  });
	/* http://valums.com/ajax-upload/ */
  new AjaxUpload('upload_button', 
		{
			action: '/admin/cinema/upload/<?= $film->id ?>',
			onComplete: function( file, response ) {
				MediaBrowser.reload();
			}
		});
});

</script>

<h3>Edit Film</h3>

<?= form_open('admin/cinema/edit/' . $film->id, array('class'=>'general')); ?>
<table style="border: 0">
	<tr><td valign="top">
		<fieldset><legend>Details</legend>
		<table style="border: 0">
			<tr>
				<td><label for="ttno">tt#</label><br/>
				<input name="ttno" type="text" value="<?=set_value('ttno', $film->ttno )?>"/></td>
				<td class="form_error"><?=form_error('ttno')?></td>
			</tr>
			<tr>
				<td><label for="title">title</label><br/>
				<input name="title" type="text" size="40" class="required" value="<?=set_value('title', $film->title )?>"/></td>
				<td class="form_error"><?=form_error('title')?></td>
			</tr>
			<tr>
				<td><label for="director">director</label><br/>
				<input name="director" type="text" class="required" value="<?=set_value('director', $film->director )?>"/></td>
				<td class="form_error"><?=form_error('director')?></td>
			</tr>
			<tr>
				<td><label for="country">country</label><br/>
				<input name="country" type="text" value="<?=set_value('country', $film->country )?>"/></td>
				<td class="form_error"><?=form_error('country')?></td>
			</tr>
			<tr>
				<td>
					<table style="border: 0">
						<tr>
							<td><label for="year">year</label><br/>
							<input name="year" type="text" size="4" value="<?=set_value('year', $film->year )?>"/></td>
							<td class="form_error"><?=form_error('year')?></td>
							<td><label for="running_time">running time</label><br/>
							<input name="running_time" type="text" size="4" value="<?=set_value('running_time', $film->running_time)?>"/></td>
							<td class="form_error"><?=form_error('running_time')?></td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td><label for="rating">rating</label><br/>
				<input name="rating" type="text" value="<?=set_value('rating', $film->rating)?>"/></td>
				<td class="form_error"><?=form_error('rating')?></td>
			</tr>
			<tr>
				<td><label for="link" onclick="do_link_lookup()">link</label><br/>
				<input name="link" id="link" type="text" size="50" value="<?=set_value('link', $film->imdb_link)?>"/></td>
				<td class="form_error"><?=form_error('link')?></td>
			</tr>
		</table>
		</fieldset>
	</td>
	<td valign="top">
	  <table>
	    <tr>
	      <td>
      		<fieldset><legend>Description</legend>
      		<textarea name="description" cols="60" rows="15"><?=set_value('description',$film->description)?></textarea>
      		</fieldset>
    		</td>
    	</tr>
    	<tr>
    	  <td valign="top">
      		<fieldset style=""><legend>Media</legend>
      		  <!-- media browser thing -->
      		  <div id="media">
      		    <div class="overclear buttons">
      		      <a href="#" class="prev"><img src="/img/16-arrow-left.png" title="Previous" alt="Previous"/></a>
      		      <div class="showing"><!-- showing --></div>
      		      <a href="#" class="next"><img src="/img/16-arrow-right.png" title="Next" alt="Next"/></a>
      		    </div>
      		    <div class="overclear inner">
      		      <ul class="overclear">
      		      </ul>
      		    </div>
      		    <div class="overclear bottom">  
      		      <div id="upload_button"><img src="/img/upload.png" /></div>
      		    </div>
      		  </div>
      	  </fieldset>
    	  </td>
    	</tr>
		</table>
	</td>
	</tr>
</table>
<br/>
<input type="submit" name="update" value="Update" />
<input type="submit" name="cancel" value="Cancel" />
</form>

<div style="display: none; margin: 5px;" id="editModalDiv">
 <div id="modal_tools" style="border-bottom: 1px solid #ddd;">
	<span style="float: right">
		<img onclick="$.modal.close()" src="/img/close.png" title="Close" style="cursor: pointer;"/>
	</span>
    <h3>Edit Image</h3>
 </div>
 <div id="modal_content">
 </div>
</div>
