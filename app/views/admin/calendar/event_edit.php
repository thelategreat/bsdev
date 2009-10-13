<script type="text/javascript" src="/js/ajaxupload.js" ></script>

<script type="text/javascript">

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

</script>

<script type="text/javascript" language="javascript">
$(function() {
});

function lookup( inp )
{
	if( inp.length == 0 ) {
		$('#autoSuggestBox').hide();
	} else {
		$.post("/admin/event/lookup", {query: ""+inp+"", cat: ""+$('#fld_category').val()+"" },
			function( data ) {
				var ht = '';
				$(data).find('item').each(function(foo) {
					ht = ht + '<li onclick="fill(\'' + $(this).attr('name') + '\')">' + $(this).attr('name') + '</li>';
				});
				$('#autoSuggestBox').show();
				$('#autoSuggestList').html( ht );
			}, 'xml');
	}
}

function fill( thisValue )
{
	$('#fld_title').val(thisValue);
	$('#autoSuggestBox').hide();
}

</script>

<h3>Edit Event</h3>
	
<table><tr><td>
	<fieldset><legend>Details</legend>
<form id="event_form" action="/admin/event/edit/<?=$event->id?>" method="POST" >
<input type="hidden" name="id" id="fld_id" value="-1" />
<table style="border: none;">
<tr>
	<td><label for="venue">Venue</label></td>
	<td>
		<select name="venue" id="fld_venue" >
		  <option <?=set_select('venue','cinema', ($event->venue == 'cinema'))?>>cinema</option>
		  <option <?=set_select('venue','greenroom', ($event->venue == 'greenroom'))?>>greenroom</option>
		  <option <?=set_select('venue','ebar', ($event->venue == 'ebar'))?>>ebar</option>
		  <option <?=set_select('venue','bookstore', ($event->venue == 'bookstore'))?>>bookstore</option>
		  <option <?=set_select('venue','other', ($event->venue == 'other'))?>>other</option>
		</select>
	</td>
	<td><label for="category">Category</label></td>
	<td>
		<select name="category" id="fld_category" >
		  <option <?=set_select('category','music', ($event->category == 'music'))?>>music</option>
		  <option <?=set_select('category','reading', ($event->category == 'reading'))?>>reading</option>
		  <option <?=set_select('category','poetry', ($event->category == 'poetry'))?>>poetry</option>
		  <option <?=set_select('category','lecture', ($event->category == 'lecture'))?>>lecture</option>
		  <option <?=set_select('category','film', ($event->category == 'film'))?>>film</option>
		</select>
	</td>
</tr>
<tr>
	<td><label for="title">Title</label></td>
	<td colspan="3">
		<input name="title" size="80" id="fld_title" value="<?=$event->title?>" onkeyup="lookup(this.value);" autocomplete="off"/>
	</td>
</tr>
<tr>
	<?php
	$dt_start = explode(" ", $event->dt_start);
	$dt_start[1] = substr($dt_start[1], 0, -3);
	$dt_end = explode(" ", $event->dt_end);
	$dt_end[1] = substr($dt_end[1], 0, -3);
	?>
	<td><label for="event_date_start">Start Date</label></td>
	<td><input class="date-pick" name="event_date_start" size="12" onblur="magicDate(this);" id="fld_event_date_start" value="<?=$dt_start[0]?>" autocomplete="off"/><span class="small" title="numeric date or english like: today, tomorrow, next monday, ...">yyyy-mm-dd<span></td>
	<td><label for="event_time_start">Start Time</label></td>
	<td><input name="event_time_start" size="5" onblur="magicTime(this);" id="fld_event_time_start" value="<?=$dt_start[1]?>" autocomplete="off" /><span class="small">hh:mm</span></td>
</tr>
<tr>
	<td><label for="event_date_end">End Date</label></td>
	<td><input name="event_date_end" size="12" onblur="magicDate(this);" id="fld_event_date_end" value="<?=$dt_end[0]?>" autocomplete="off"/><span class="small">yyyy-mm-dd<span></td>
	<td><label for="event_time_end">End Time</label></td>
	<td><input name="event_time_end" size="5" onblur="magicTime(this);" id="fld_event_time_end" value="<?=$dt_end[1]?>" autocomplete="off"/><span class="small">hh:mm</span></td>
</tr>
<tr>
  <td colspan="4">Description</td>
</tr>
<tr>
  <td colspan="3"><textarea name="body" rows="10" cols="70" id="fld_body" ><?=$event->body?></textarea></td>
	<td valign="top" align="center">
		<fieldset><legend>Media</legend>
			<div id="media_preview">
				<a href="#" onclick="MediaBrowser.init({path: '/events/<?=$event->id?>'});"><img src="/pubmedia/library/no_image.jpg" height="80" /></a>
				<br><small>no image assigned</small>
			</div>
		</fieldset>
	</td>
</tr>
</table>
<p/>
<span id='fld_event_date_startMsg'><p/></span>
<span id='fld_event_date_endMsg'><p/></span>
</fieldset>
<table>
	<tr><td>
		<input style="background-color: #9F9" type="submit" name="update" value="Update" />
		<input type="submit" name="cancel" value="Cancel" />
	</td>
	<td align="right">
		<input style="background-color: #F99" type="submit" name="rm" value="Delete" onclick="return confirm('Really delete this event?');"/>
	</td>
</tr>
</table>
</form>

</td>
<td valign="top" width="30%">
	<fieldset><legend>Lookup</legend>
			<div class="suggestBox" id="autoSuggestBox" style="display: none;">
				<div class="suggestList" id="autoSuggestList"></div>
			</div>
	</fieldset>
</td>
</tr>
</table>

<!-- media editing -->
<div id="editModalDiv">
 <div id="modal_title">
		<span style="float: right">
			<img onclick="$.modal.close()" src="/img/close.png" title="Close" style="cursor: pointer;"/>
		</span>
    <h3>Media Browser</h3>
 </div>
 <div id="modal_content">
 </div>
</div>
