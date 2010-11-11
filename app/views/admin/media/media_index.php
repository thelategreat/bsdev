
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


</script>

<div style="width: 850px">
	
<div style="float: right">
	<form id="search_form" method="post">
		<?php
		$query = '';
		foreach($stags as $tag) { 
			$query .= $tag . " ";
		}
		if( strlen(trim($query)) == 0 ) {
			$query = 'search...';
		}
		?>
		<input id="query" name="q" value="<?=$query?>" size="15" onblur="if(this.value=='') this.value='search...';" onfocus="if(this.value == 'search...') this.value='';"/>
	</form>
</div>

<h3>Media Library</h3>

<?= $errors ?>

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

<div class="gallery" id="gallery-div"></div>

<div style="float: right" id="next-button">
	<a href='#'><img src="/img/cal/arrow_right.png" width="20px"/></a>
</div>
<div style="float: left" id="prev-button">
	<a href='#'><img src="/img/cal/arrow_left.png" width="20px"/></a>
</div>

<div id="work-area" style="display: none" ></div>

</div>