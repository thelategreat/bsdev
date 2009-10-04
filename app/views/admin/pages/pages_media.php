<script type="text/javascript" src="/js/ajaxupload.js" ></script>

<script language="javascript" type="text/javascript">

function reload()
{
	var path = escape('/admin/pages/media/<?= $page->id ?>/' + $('#slot_select').val());
	$("#media_area").load( path );
}

$(function() {
	/* http://valums.com/ajax-upload/ */
	new AjaxUpload('upload_button', 
		{
			action: '/admin/pages/upload/<?= $page->id ?>',
			onComplete: function( file, response ) {
				reload();
			},
			onSubmit: function( file, extension ) {
				this.setData( {'slot': $('#slot_select').val()})
			}
		});	
		
		reload();	
});
</script>

<?=$tabs?>

<h3>Page: <?=$page->title?></h3>

Slot: <select id="slot_select" name="slot" onchange="reload()">
	<option>general</option>
<?php foreach( explode(",",$page->media) as $slot ) { ?>
			<option><?=$slot?></option>
<?php } ?>
</select><button onclick="reload()"><img width="16" src="/img/reload.png" /></button>

<hr/>
<div id="media_area" ></div>
<hr/>
<div id="upload_button"><img title="Upload" alt="Upload" src="/img/upload.png" /></div>