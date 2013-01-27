<?php
/*
Generic media tab for system

pass in the following minimum:

$page_data = array(
'tabs' => $this->tabs->gen_tabs($this->page_tabs, $cur_tab, '/admin/venues/edit/' . $venue->id),
'title' => "The Tab Title",											// the page title
'path' => '/pages/' . $page->id,								// the page in the db for this
'next' => "/admin/pages/edit/$page->id/media",  // the web path to this tab
);

optionally you can pass in:

$page_data['slot'] = 'general';									// the current page slot
$page_data['slots] = 'front,back';              // any extra slots required. comma sep

- slots are simply sub categories. the default is 'general' They can be used as
  bin on the page, layouts oriented stuff

*/
?>

<script type="text/javascript" src="/js/ajaxupload.js" ></script>
<script type="text/javascript" src="/js/admin_mb.js" ></script>
<script type="text/javascript" src="/js/admin_media_gallery.js" ></script>

<script language="javascript" type="text/javascript">

function reload()
{
	var slot = 'general';
	if( $('#slot_select')) {
		slot = $('#slot_select').val();
	}	
	$.post('/admin/media/browser', 
		{ path: '<?= $path ?>', 'slot': slot },
		function(data) {
			$('#media_area').html( data );
		}
	);			
}

function check_upload()
{
	var blank=/^\s*$/;
	if( blank.test($('#title-field').val()) ) {
		alert("Please fill in the title field.");
		$('#title-field').focus();
		return false;
	}
	return true;
}

$(function() {		
		reload();	
});
</script>

<?=$tabs?>

<h3><?=$title?></h3>

<?php if( !isset($slot)) { $slot = 'general'; ?>
  <input type="hidden" id="slot_select" name="slot" value="general" />
<?php } else { ?>
Slot: <select id="slot_select" name="slot" onchange="reload()">
	<option <?=$slot == 'general' ? 'selected' : ''?>>general</option>
<?php foreach( explode(",", $slots) as $s ) { 
	if( !empty($s) && strlen(trim($s)) ) { ?>
			<option <?=$slot == $s ? 'selected' : ''?>><?=$s?></option>
<?php } 
  }
} ?>
</select>
<!--
<button onclick="reload()"><img width="16" src="/img/admin/reload.png" /></button>
-->
<button onclick="MediaBrowser.init({path: '<?=$path?>', width: 815, height: 300 });" title="search media library"><img src="/img/admin/image_link.png" /></button>
<!-- upload form -->
<button onclick="$('#upload_div').toggle('slow');" title="add media"><img src="/img/admin/upload.png" width="16px"/></button>
<div id="upload_div" style="display: none;">
	<form method="post" action="/admin/media" enctype="multipart/form-data" onsubmit="return check_upload();" >
	<fieldset>
		<legend>Add Media</legend>
	<table style="width: auto">
		<tr>
			<td>
					<label for"type">Type</label>
			</td>
			<td>
				<input type="radio" name="bogus" onclick="$('#file-input').toggle(); $('#link-input').toggle();" checked="checked"  /> Media
				<input type="radio" name="bogus" onclick="$('#file-input').toggle(); $('#link-input').toggle();" /> Link
			</td>
		<tr>
		<tr>
			<td>
					<label for"title">Title</label>
			</td>
			<td>
					<input id="title-field" name="title" size="20" />
			</td>
		</tr>
			<td colspan="2">
				<div id="file-input">
					<label for="userfile">File</label>&nbsp;&nbsp;&nbsp;&nbsp;<input type="file" name="userfile" />
					<input type="hidden" name="next" value="<?=$next?>" />
					<input type="hidden" name="path" value="<?=$path?>" />
					<input type="hidden" id="slot_field" name="slot" value="" /><br/>
					<input type="submit" name="upload" value="Upload" />
				</div>
				<div id="link-input" style="display: none;">
					<label for="url">Link</label> <input type="text" size="30" name="url" />&nbsp;&nbsp;
					<input type="hidden" name="next" value="<?=$next?>" />
					<input type="hidden" name="path" value="<?=$path?>" />
					<input type="hidden" id="slot_field1" name="slot" value="" /><br/>
					<input type="submit" name="link" value="Save" />		
				</div>
			</td>
		</tr>
	</table>
	</fieldset>
	</form>
</div>

<hr/>
<div id="media_area" ></div>
<hr/>


<div id="editModalDiv">
 <div id="modal_title">
	<span style="float: right">
		<img onclick="$.modal.close()" src="/img/admin/close.png" title="Close" style="cursor: pointer;"/>
	</span>
    <h3>Media Browser</h3>
 </div>
 <div id="modal_content">
	<div id="popup_content">
	</div>
 </div>
</div>
