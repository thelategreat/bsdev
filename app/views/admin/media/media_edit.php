<h2>Edit Meta</h2>

<?php if( $item->type == "link") { 
	$url = $item->title;
	$purl = parse_url( $url );
	if( $purl['host'] == 'www.vimeo.com' || $purl['host'] == 'vimeo.com') {
		$url = 'http://vimeo.com/moogaloop.swf?clip_id=' . substr($purl['path'], 1 ) . '&amp;server=vimeo.com&amp;show_title=1&amp;show_byline=1&amp;show_portrait=0&amp;color=&amp;fullscreen=1';
	}
	?>
<object width="425" height="344">
	<param name="movie" value="<?= $url ?>"></param>
	<param name="allowFullScreen" value="false"></param>
	<param name="allowscriptaccess" value="always"></param>
	<embed src="<?= $url ?>" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="false" width="425" height="344"></embed>
</object>
<?php } else { ?>
<img id="theImage" src="/media/<?= $item->uuid ?>" />
<canvas id="theCanvas" style="border: 1px solid #000;"></canvas>
<?php } ?>

<?php echo validation_errors('<div class="error">', '</div>'); ?>

<form method="post" >
	<table>
		<tr>
			<td><label for="title"><?= $item->type == "link" ? "Link" : "Title" ?></label></td>
			<td><input name="title" size="50" value="<?= $item->title ?>" /></td>
		</tr>
		<tr>
			<td><label for="caption">Caption</label></td>
			<td><input name="caption" size="50" value="<?= $item->caption ?>" /></td>
		</tr>
		<tr>
			<td><label for="description">Description</label></td>
			<td><textarea name="description" class="mceNoEditor" rows="5" cols="60"><?= $item->description ?></textarea></td>
		</tr>
		<tr>
			<td><label for="tags">Tags</label></td>
			<td><input name="tags" size="50" value="<?= $item->tags ?>" /><br/>
				<span class="field_tip">tags are separated by spaces.<br/>if you need a two word tag, 
					use a hyphen to join them, like 'daniel-boone'</span>
			</td>
		</tr>
		<tr>
			<td><label for="license">License</label></td>
			<td>
				<select name="license">
					<option value="unknown">Unknown</option>
					<option value="weown">We own</option>
					<option value="open">Open</option>
				</select>
			</td>
		</tr>
	</table>
	<hr />
	<input style="background-color: #9f9" type="submit" name="save" value="Save" />
	<input type="submit" name="cancel" value="Cancel" />
	&nbsp;&nbsp;&nbsp;&nbsp;
	<input onclick="return confirm('Really delete this?');" style="background-color: #f99" type="submit" name="delete" value="Delete" />
</form>
<p/>
<script type="text/javascript">
function loadImage() {
	var cvs = $('#theCanvas')[0];
	if( !cvs ) return;
	var ctx = cvs.getContext('2d');
	var img = $('#theImage')[0];
	if( ctx && img ) {
		cvs.setAttribute('width', '300px' );
		cvs.setAttribute('height', '200px' );
		$(img).hide();
		ctx.drawImage(img, 0, 0, 300, 200 );		
	} else {
		alert('no image');
	}
	
}

$(document).ready(function() {
	loadImage();
});
</script>
