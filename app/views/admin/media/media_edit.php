<table>
	<tr>
		<td style="width: 80%;">
				<div style="background-color: #888; padding: 15px; text-align: center; border-bottom: 2px solid #333; border-right: 2px solid #333;">
<?php if( $item->type == "link") { 
	
	echo get_embed_object( $item->title );
	
} else { ?>
	<a style="float: right" href="#" id="toggle_size" onclick="toggle_size(); return false;" title="toggle view" style="font-size: .8em;">[+]</a><br/>

	<?php if( file_exists('media/'. $item->uuid)) { ?>
		<img id="theImage" src="/media/<?= $item->uuid ?>" />
	<?php } else { ?>
		<img id="theImage" src="/img/image_not_found.jpg" />
	<? } ?>
	<!-- <canvas id="theCanvas" style="border: 1px solid #000;"></canvas> -->
<?php } ?>
			</div>
			
<?php echo validation_errors('<div class="error">', '</div>'); ?>

<form method="post" >
	<h3>Media Info</h3>
	<hr />
	<table>
		<tr>
			<td><label for="caption">Caption</label></td>
			<td><input name="caption" size="50" value="<?= $item->caption ?>" /></td>
		</tr>
		<tr>
			<td><label for="tt_isbn">tt#/isbn</label></td>
			<td><input name="tt_isbn" size="17" value="<?= $item->tt_isbn ?>" /><br/>
				<span class="field_tip">tt1234567 or 13 digit ISBN</span>
			</td>
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
	<?php } ?>
	<?php if( $page && isset( $page )) { ?>
		<input type="hidden" name="page" value="<?=$page?>" />
	<?php } ?>
<p/>
		</td>
		<td valign="top" style="padding-left: 10px;">
			<div id="media-info">
			</div>
			<?php if( !file_exists('media/'. $item->uuid) && $item->type != "link") { 
				$msg = "<b>This media is missing!</b>";
				?>
				<p class="error small"><?= $msg ?></p>
				<h4>References</h4>
				<table style="margin-top: -10px;">
					<?php foreach( $used->result() as $row ) { 
						$tmp = explode('/', $row->path );
						array_splice( $tmp, 2, 0, 'edit');
						$path = implode('/', $tmp );
						?>
						<tr><td><a href="/admin<?=$path?>/media"><?=$path?></a></td></tr>
					<?php } ?>
				</table>
		 	  <input onclick="return confirm('Really delete this media and all references?');" style="background-color: #f99" type="submit" name="deleterefs" value="Delete All" />

				
			<?php } else if( $used->num_rows() != 0 ) { ?>
				<h4>References</h4>
				<table style="margin-top: -10px;">
					<?php foreach( $used->result() as $row ) { 
						$tmp = explode('/', $row->path );
						array_splice( $tmp, 2, 0, 'edit');
						$path = implode('/', $tmp );
						?>
						<tr><td><a href="/admin<?=$path?>/media"><?=$path?></a></td></tr>
					<?php } ?>
				</table>
			<?php } ?>
</form>
			
			<?php if( $item->type != "link") { ?>			
				<h4>Replace Image</h4>
				<form method="post" action="" enctype="multipart/form-data" >
					<input type="file" name="userfile" />
					<input type="submit" name="replace" value="Replace" />
				</form>
			<?php } ?>
		</td>
	</tr>
</table>

<script type="text/javascript">

function toggle_size()
{
	var cvs = $('#theCanvas')[0];
	if( !cvs ) return;
	var ctx = cvs.getContext('2d');
	var img = $('#theImage')[0];
	if( ctx && img ) {
		var width = parseInt($(cvs).attr('width'));
		var height = parseInt($(cvs).attr('height'));
		//alert( '' + img.width + ' ' + width );
		if( parseInt(img.width) == parseInt($(cvs).attr('width'))) {
			width = 200;
			height = 100;
			$('#toggle_size').html('[+]');
			$('#toggle_size').attr('title',"full size");
		} else {
			width = parseInt(img.width);
			height = parseInt(img.height);			
			$('#toggle_size').html('[-]');
			$('#toggle_size').attr('title',"smaller view");
		}
		cvs.setAttribute('width', '' + width + 'px' );
		cvs.setAttribute('height', '' + height + 'px' );
		ctx.drawImage(img, 0, 0, width, height );		
	}
}

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
			toggle_size();
		};
		img.src = img.src;
		
	} else {
		alert('no image');
	}	
}

$(document).ready(function() {
	//loadImage();
});
</script>
