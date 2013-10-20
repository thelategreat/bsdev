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
		{ path: '<?= $path ?>' },
		function(data) {
			$('#media_area').html( data );
	
			$('#media_area .slot').on('change', function() {
				var slot = $(this).val();
				var map_id = $(this).parents('tr').data('id');

				$.post('/admin/media/update_media_map', 
					{slot: slot, map_id: map_id});
			});

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

	$('#upload-button').click(function() {
		$('#upload_div').toggle();
	});

	$('#search_submit').click(function(e) {
		e.preventDefault();
		$('#search_status').html('Searching...');

		$.post('/admin/media/search_json', {q:$('#search').val()}, function(data) {
			var area = $('#search_results .items');
			$(area).html('');
			$('#search_status').html('');

			if (data != null) {
				$.each(data, function(i, item) {
					area.append('<div class="result-image" data-media-id="'+item.id+'" data-uuid="'+item.uuid+'"><img src="/i/size/o/media--'+item.uuid+'/w/70"></div>');
				});
				$('.result-image').click(function() {
					$(this).toggleClass('selected');
				});
			}
		}, 'json');

		$('#search_results').show();
	});

	$("#add_media_button").click(function() {
		var selected = $('#search_results .selected');
		var i = 0;
		var errors = 0;
		$(selected).each(function() {
			i++;
			$.post('/admin/media/add_ajax', {path:'<?=$path;?>', uuid:$(this).data('uuid')}, function(data) {
				if (data.success == false) {
					errors++;
					alert(data.message);
				}	
				checkIsDone(selected.length, i, errors);	
			}, 'json');
		});
	});

	function checkIsDone(total, count, errors) {
		if (count == total && count > errors) {
			location.reload();
		}
	}

	$('#searchform').submit(function(e) {
		e.preventDefault();
	});

});

</script>

<div class=container>
	<header><?=$title;?></header>
	<nav>
		<?= $tabs ?>
	</nav>
	<br>

<br>
<div style='float:right'>
	<form id='searchform' method='post'>
		Search for media: <input type="text" id='search' name="search" /><button id="search_submit">Search</button><div id="search_status"></div><br/>
	</form>
</div>

<nav>
	<button type='submit' id='upload-button' class='iconbutton' name='upload' value='Upload'> 
		<i class='icon-upload icon-2x'></i> Upload 
	</button>
</nav>

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

<?php 
/*
<!--
<button onclick="reload()"><img width="16" src="/img/admin/reload.png" /></button>
-->
<button onclick="MediaBrowser.init({path: '<?=$path?>', width: 815, height: 600 });" title="search media library"><img src="/img/admin/image_link.png" /></button>
<!-- upload form -->
<button onclick="$('#upload_div').toggle();" title="add media">
	<img src="/img/admin/upload.png" width="16px"/>
</button>
*/
?>

<div id="upload_div" style="display: none;">
	<form method="post" action="/admin/media" enctype="multipart/form-data" onsubmit="return check_upload();" >
	<fieldset>
		<legend>Add Media</legend>
	<table class='form-table' 
		<tr>
			<th>
				Type
			</th>
			<td>
				<input type="radio" name="bogus" onclick="$('#file-input').toggle(); $('#link-input').toggle();" checked="checked"  /> Media
				<input type="radio" name="bogus" onclick="$('#file-input').toggle(); $('#link-input').toggle();" /> Link
			</td>
		<tr>
		<tr>
			<th>
				Title
			</th>
			<td>
				<input id="title-field" name="title" size="20" />
			</td>
		</tr>
		<tr id="file-input">
			<th>File</th>
			<td><input type="file" name="userfile" />
				<input type="hidden" name="next" value="<?=$next?>" />
				<input type="hidden" name="path" value="<?=$path?>" />
				<input type="hidden" id="slot_field" name="slot" value="" /><br/>
				<input type="submit" name="upload" value="Upload" />
			</td>
		</tr>
		<tr id="link-input" style='display:none'>
			<th>Link</th>
			<td><input type="text" size="30" name="url" />
				<input type="hidden" name="next" value="<?=$next?>" />
				<input type="hidden" name="path" value="<?=$path?>" />
				<input type="hidden" id="slot_field1" name="slot" value="" /><br/>
				<input type="submit" name="link" value="Save" />
			</td>
		</tr>
	</table>
	</fieldset>
	</form>
</div>
 

<div id="search_results">
	<header>Click to the select the items you wish to add then click submit.
		<button id='add_media_button' name='add_media' value='Add Media'> 
			<i class='icon-plus'></i> Submit 
		</button>
		<div style='clear:both'></div>
	</header>
	<div class='items'></div>
</div>
<div style='clear:both'></div>

<div id="media_area" ></div>


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

</div>
