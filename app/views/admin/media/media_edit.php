<h2>Edit Meta</h2>

<?php if( $item->type == "link") { 
	
	echo get_embed_object( $item->title );
	
} else { ?>
	
	<img id="theImage" src="/media/<?= $item->uuid ?>" />
	<canvas id="theCanvas" style="border: 1px solid #000;"></canvas>

<?php } ?>

<?php echo validation_errors('<div class="error">', '</div>'); ?>

<form method="post" >
	<table>
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
		<tr>
			<td><label for="title"><?= $item->type == "link" ? "Link" : "File" ?></label></td>
			<td><input name="title" size="50" value="<?= $item->title ?>" readonly="readonly"/></td>
		</tr>
	</table>
	<hr />
	<input style="background-color: #9f9" type="submit" name="save" value="Save" />
	<input type="submit" name="cancel" value="Cancel" />
	&nbsp;&nbsp;&nbsp;&nbsp;
	<?php if( $used->num_rows() == 0 ) { ?>
 	  <input onclick="return confirm('Really delete this?');" style="background-color: #f99" type="submit" name="delete" value="Delete" />
	<?php } else { ?>
		<p class="info">this media is in use and can't be deleted</p>
	<?php } ?>
</form>
<p/>

<script type="text/javascript">

function loadImage() {
	var cvs = $('#theCanvas')[0];
	if( !cvs ) return;
	var ctx = cvs.getContext('2d');
	var img = $('#theImage')[0];
	if( ctx && img ) {
		$(img).hide();
		//
		img.onload = function() {
			//alert( this.width + ' ' + this.height );			
			var width = this.width;
			var height = this.height;
			cvs.setAttribute('width', '' + width + 'px' );
			cvs.setAttribute('height', '' + height + 'px' );
			ctx.drawImage(img, 0, 0, width, height );		
		};
		img.src = img.src;
		
	} else {
		alert('no image');
	}	
}

$(document).ready(function() {
	loadImage();
});
</script>
