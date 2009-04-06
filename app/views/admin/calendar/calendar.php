<script type="text/javascript">

function edit_event( id )
{
	if( id != -1 ) {
		if( !get_event_data( id )) {
			return;
		}
	}
	
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

function get_event_data( eid )
{
	var ok = true;
	
	$.post('/admin/calendar/ajax_get_event', {id: eid }, function(data) {
		data = eval("(" + data + ")");
		if( data.err == 1 ) {
			alert( data.msg );
			ok =  false;
		} else {
			$('#fld_id').val(data.data['id']);
			$('#fld_title').val(data.data['title']);
			$('#fld_body').val(data.data['body']);
			var tmp = data.data['dt_start'].split(' ');
			$('#fld_event_date_start').val(tmp[0]);
			$('#fld_event_time_start').val(tmp[1].substr(0,5));
			tmp = data.data['dt_end'].split(' ');
			$('#fld_event_date_end').val(tmp[0]);
			$('#fld_event_time_end').val(tmp[1].substr(0,5));
		}
	});
	return ok;
}

function modal_open( dialog )
{
	var ediv = $('#editModalDiv');
	
	dialog.overlay.fadeIn('fast', function() {
		dialog.container.fadeIn('slow',function() {
			dialog.data.hide().slideDown('fast');
		});	
	});
}

function save_record()
{
	var query = $('#event_form').formSerialize();
	$.post('/admin/calendar/add_event', query, function(data) {
		data = eval("(" + data + ")");
		if( data.err == 1 ) {
			alert( data.msg + data.data );
		} else {
			$.modal.close();
		}
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
</script>

<div class="leftCol">
	<h3>Calendar <img src="/img/calendar_add.png" style="cursor: pointer" title="New Event" onclick="edit_event(-1);"/></h3>
	<div class="scrollable" style="height: 500px">
	<ul class="event_list">
	<?php foreach( $events->result() as $row ) {
		echo '<li>';
		echo '<a href="#" onclick="edit_event('.$row->id.')"><img class="icon" src="/img/icons/icon_'.$row->venue.'.gif" /> ' . $row->title . '</a>';
		echo '<br/><span class="event_date">' . date("M j, y @ g:ia",strtotime($row->dt_start)). '</span>';
		echo '</li>';
	} ?>
	</ul>
	</div>
</div>

<div class="rightCol">
<?=$tabs?>
<?=$cal?>
</div>

<div style="display: none; margin: 5px;" id="editModalDiv">
<h3>Event</h3>
<form id="event_form" action="/admin/calendar/add_event" >
<input type="hidden" name="id" id="fld_id" value="-1" />
<table style="border: none;">
<tr>
	<td><label for="title">Title</label></td>
	<td colspan="3"><input name="title" size="50" id="fld_title" /></td>
</tr>
<tr>
	<td><label for="event_date_start">Start Date</label></td>
	<td><input name="event_date_start" size="12" onblur="magicDate(this);" id="fld_event_date_start" /><span class="small">yyyy-mm-dd<span></td>
	<td><label for="event_time_start">Start Time</label></td>
	<td><input name="event_time_start" size="5" onblur="magicTime(this);" id="fld_event_time_start" /><span class="small">hh:mm</span></td>
</tr>
<tr>
	<td><label for="event_date_end">End Date</label></td>
	<td><input name="event_date_end" size="12" onblur="magicDate(this);" id="fld_event_date_end" /><span class="small">yyyy-mm-dd<span></td>
	<td><label for="event_time_end">End Time</label></td>
	<td><input name="event_time_end" size="5" onblur="magicTime(this);" id="fld_event_time_end"/><span class="small">hh:mm</span></td>
</tr>
<tr>
	<td><label for="venue">Venue</label></td>
	<td>
		<select name="venue" id="fld_venue" >
		  <option>cinema</option>
		  <option>greenroom</option>
		  <option>ebar</option>
		  <option>bookstore</option>
		  <option>other</option>
		</select>
	</td>
	<td><label for="category">Category</label></td>
	<td>
		<select name="category" id="fld_category" >
		  <option>music</option>
		  <option>reading</option>
		  <option>poetry</option>
		  <option>lecture</option>
		  <option>film</option>
		</select>
	</td>
</tr>
<tr>
  <td colspan="4">Description</td>
</tr>
<tr>
  <td colspan="4"><textarea name="body" rows="10" cols="70" id="fld_body"></textarea></td>
</tr>
</table>
</form>
<p/>
<button onclick="save_record()">Save</button>
<button onclick="$.modal.close()">Cancel</button>

</div>