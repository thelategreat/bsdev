
<script type="text/javascript" src="/js/admin_media_gallery.js" ></script>

<script type="text/javascript">

$(document).ready(function() {
	Gallery.init({
		width: 850,
		onclick : function( uuid ) {
			$.post('/admin/media/edit/' + uuid, { ajax: "1" },
				function( data ) {
					$('#work-area').html( data );
					$('#work-area').show();
			});				
		}
	});
	//gallery( 1 );
});

function search_handler()
{
	Gallery.render( 1, $('#q').val());
	return false;
}

</script>

<div style="width: 850px">
	
<div style="float: right">
	<form id="search_form" method="post" onsubmit="return search_handler();">
		<?php
		$query = '';
		foreach($stags as $tag) { 
			$query .= $tag . " ";
		}
		if( strlen(trim($query)) == 0 ) {
			$query = 'search...';
		}
		?>
		<input id="q" name="q" value="<?=$query?>" size="15" onblur="if(this.value=='') this.value='search...';" onfocus="if(this.value == 'search...') this.value='';"/>
	</form>
</div>

<h3>Media Library</h3>

<?= $errors ?>
<!--
<a href="#" onclick="$('#upload-div').toggle('slow');"><img src="/img/admin/upload.png" width="24px"/></a>
<div id="upload-div" style="display: none;">
	<table>
		<tr>
			<td>
	<form method="post" action="" enctype="multipart/form-data" >
		<label for="userfile">File</label> <input type="file" name="userfile" />
		<input type="submit" name="upload" value="Upload" />
		</td>
		<td> - or - </td>
		<td>
		<label for="url">Link</label> <input type="text" size="50" name="url" />
		<input type="submit" name="link" value="Save" />		
	</form>
	</td>
	</tr>
</table>
</div>
-->
<!-- upload form -->
<button onclick="$('#upload_div').toggle('slow');" title="upload media"><img src="/img/admin/upload.png" width="16px"/></button>
<div id="upload_div" style="display: none;">
	<form method="post" action="" enctype="multipart/form-data" >
		<fieldset>
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
			<td colspan="2">
				<div id="file-input">
					<label for="userfile">File</label>&nbsp;&nbsp;&nbsp;&nbsp;<input type="file" name="userfile" />
					<input type="submit" name="upload" value="Upload" />
				</div>
				<div id="link-input" style="display: none;">
					<label for="url">Link</label> <input type="text" size="30" name="url" />&nbsp;&nbsp;
					<input type="submit" name="link" value="Save" />		
				</div>
			</td>
		</tr>
	</table>
	</fieldset>
	</form>
</div>

<p/>

<div class="gallery" id="gallery-div"></div>

<div style="float: right" id="next-button">
	<a href='#'><img src="/img/cal/arrow_right.png" width="20px"/></a>
</div>
<div style="float: left" id="prev-button">
	<a href='#'><img src="/img/cal/arrow_left.png" width="20px"/></a>
</div>

<div id="work-area" style="display: none" ></div>

</div>