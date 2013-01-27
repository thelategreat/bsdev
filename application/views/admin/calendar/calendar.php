
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

function get_details()
{
	$.get('/admin/calendar/new_event', function(data) {
		$('#modal_content').html( data );
		tinyMCE.init({
			mode : "textareas",
			editor_deselector : "mceNoEditor",
			theme : "advanced",
			theme_advanced_buttons1 : "mybutton,bold,italic,underline,separator,strikethrough,justifyleft,justifycenter,justifyright, justifyfull,bullist,numlist,undo,redo,link,unlink",
			theme_advanced_buttons2 : "",
			theme_advanced_buttons3 : "",
			theme_advanced_toolbar_location : "top",
			theme_advanced_toolbar_align : "left",
			theme_advanced_statusbar_location : "bottom",		
		});			
	});
	
}

function get_media()
{
	$.get('/admin/calendar/new_media', function(data) {
		$('#modal_content').html( data );
	});
	
}

function modal_open( dialog )
{
	var ediv = $('#editModalDiv');
  
	$.get('/admin/calendar/new_event', function(data) {
		$('#modal_content').html( data );
	});

	dialog.overlay.fadeIn('fast', function() {
		dialog.container.fadeIn('slow',function() {
			dialog.data.hide().slideDown('fast');
			tinyMCE.init({
				mode : "textareas",
				editor_deselector : "mceNoEditor",
				theme : "advanced",
				theme_advanced_buttons1 : "mybutton,bold,italic,underline,separator,strikethrough,justifyleft,justifycenter,justifyright, justifyfull,bullist,numlist,undo,redo,link,unlink",
				theme_advanced_buttons2 : "",
				theme_advanced_buttons3 : "",
				theme_advanced_toolbar_location : "top",
				theme_advanced_toolbar_align : "left",
				theme_advanced_statusbar_location : "bottom",		
			});			
		});	
	});	
}

function save_record()
{
	var query = $('#event_form').formSerialize();
	$.post('/admin/calendar/ajax_add_event', query, function(data) {
		data = eval("(" + data + ")");
		if( data.err == 1 ) {
			alert( data.msg + data.data );
		} else {
			$.modal.close();
			window.location.reload();
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

$(document).ready(function() {
	var weekdiv = $('#weekdiv');
	if( weekdiv ) {
		$('#weekdiv').scrollTop($('#weekdiv')[0].scrollHeight);
	}
});
</script>

<div class="leftCol">
	<table><tr>
	<td><h3><a href="/admin/event/add"><img src="/img/admin/calendar_add.png" style="cursor: pointer" title="New Event"/></a> Calendar</h3></td><td></td>
	</tr></table>
	<div class="scrollable" style="height: 500px">
	<ul class="event_list">
	<?php foreach( $events->result() as $row ) {
		echo '<li>';
		echo '<a href="/admin/event/edit/' . $row->id .'" title="Edit event" ><img class="icon" style="background-color: #88f" src="/img/icons/icon_'.$row->category.'.gif" /> ' . $row->title . '</a>';
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
 <div id="modal_tools" style="border-bottom: 1px solid #ddd;">
	<span style="float: right">
		<img onclick="$.modal.close()" src="/img/close.png" title="Close" style="cursor: pointer;"/>
	</span>
    <h3>Edit Event</h3>
 </div>
 <div id="modal_content">
 </div>
</div>
