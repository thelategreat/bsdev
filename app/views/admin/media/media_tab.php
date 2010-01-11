<script type="text/javascript" src="/js/ajaxupload.js" ></script>
<script type="text/javascript" src="/js/admin_mb.js" ></script>

<script language="javascript" type="text/javascript">

function reload()
{
	$('#slot_field').val($('#slot_select').val());
	$('#slot_field1').val($('#slot_select').val());
	$.post('/admin/media/browser', 
		{ path: '<?= $path ?>', 'slot': $('#slot_select').val() },
		function(data) {
			$('#media_area').html( data );
		}
	);			
}

$(function() {		
		reload();	
});
</script>

<?=$tabs?>

<h3><?=$title?></h3>

Slot: <select id="slot_select" name="slot" onchange="reload()">
	<option>general</option>
<?php foreach( explode(",", $slots) as $slot ) { 
	if( !empty($slot) && strlen(trim($slot)) ) { ?>
			<option><?=$slot?></option>
<?php } 
} ?>
</select><button onclick="reload()"><img width="16" src="/img/reload.png" /></button>
<button onclick="MediaBrowser.init({path: '<?=$path?>'});"><img src="/img/add.png" /></button>

<hr/>
<div id="media_area" ></div>
<hr/>
<div id="upload_div" >
	<table>
		<tr>
			<td>
	<form method="post" action="/admin/media" enctype="multipart/form-data" >
		<label for="userfile">File</label> <input type="file" name="userfile" />
		<input type="hidden" name="next" value="<?=$next?>" />
		<input type="hidden" name="path" value="<?=$path?>" />
		<input type="hidden" id="slot_field" name="slot" value="" />
		<input type="submit" name="upload" value="Upload" />
		</td>
		<td> - or - </td>
		<td>
		<label for="url">Link</label> <input type="text" size="50" name="url" />
		<input type="hidden" name="next" value="<?=$next?>" />
		<input type="hidden" name="path" value="<?=$path?>" />
		<input type="hidden" id="slot_field1" name="slot" value="" />
		<input type="submit" name="link" value="Save" />		
	</form>
	</td>
	</tr>
</table>
</div>


<div id="editModalDiv">
 <div id="modal_title">
	<span style="float: right">
		<img onclick="$.modal.close()" src="/img/close.png" title="Close" style="cursor: pointer;"/>
	</span>
    <h3>Media Browser</h3>
 </div>
 <div id="modal_content">
	<div id="popup_content">
	</div>
 </div>
</div>
