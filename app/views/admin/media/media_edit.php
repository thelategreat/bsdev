<script type="text/javascript">

// foolin around
function bw_filter()
{
	var cvs = $('#theCanvas')[0];
	if( !cvs ) return;
	var ctx = cvs.getContext('2d');
	
	var imgd = ctx.getImageData(0, 0, cvs.width, cvs.height);
	var pix = imgd.data;
	for (var i = 0, n = pix.length; i < n; i += 4) {
		var grayscale = pix[i  ] * .3 + pix[i+1] * .59 + pix[i+2] * .11;
		pix[i  ] = grayscale; 	// red
		pix[i+1] = grayscale; 	// green
		pix[i+2] = grayscale; 	// blue
	// alpha
	}
	ctx.putImageData(imgd, 0, 0);
}

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
		var ratio = height / width;
		if( parseInt(img.width) == parseInt($(cvs).attr('width'))) {
			height = 400;
			width = 200;
			if( ratio < 0 ) {
				width = height * ratio;				
			} else {
				height = width * ratio;
			}
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
	// we hide the orig image and create a new one with the hidden image src
	// so that the onload trigger actually works when we set the src below
	var cimg = new Image();
	if( ctx && img ) {
		$(img).hide();
		//
		cimg.onload = function() {
			//alert( this.width + ' ' + this.height );			
			var width = this.width;
			var height = this.height;
			cvs.setAttribute('width', '' + width + 'px' );
			cvs.setAttribute('height', '' + height + 'px' );
			ctx.drawImage(img, 0, 0, width, height );		
			toggle_size();
		};
		cimg.src = img.src;
		
	} else {
		alert('no image');
	}	
}

$(document).ready(function() {
	loadImage();
});

</script>

<table>
	<tr>
	<td valign="top" style="padding-right: 20px; width: 40%">
		
<form method="post" action="/admin/media/edit/<?=$item->uuid?><?= $is_adding ? '/add' : ''?>">
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
		<tr style="display: none;">
			<td><label for="license">License</label></td>
			<td>
				<select name="license">
					<option value="unknown">Unknown</option>
					<option value="weown">We own</option>
					<option value="open">Open</option>
				</select>
			</td>
		</tr>
		<tr style="display: none;">
			<td><label for="title"><?= $item->type == "link" ? "Link" : "File" ?></label></td>
			<td><input name="title" size="50" value="<?= $item->title ?>" readonly="readonly"/></td>
		</tr>
	</table>
	<hr />
	<input style="background-color: #9f9" type="submit" name="save" value="Save" />
	<?php if( !$is_adding ) { ?>
		<input type="submit" name="cancel" value="Cancel" />
		&nbsp;&nbsp;&nbsp;&nbsp;
		<?php if( $used->num_rows() == 0 ) { ?>
	 	  <input onclick="return confirm('Really delete this?');" style="background-color: #f99" type="submit" name="delete" value="Delete" />
		<?php } 
	}
?>
	
	<?php if( $page && isset( $page )) { ?>
		<input type="hidden" name="page" value="<?=$page?>" />
	<?php } ?>
<p/>
		</td>
				<td valign="top" style="padding-left: 10px; display: none;">
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

			 	  	<input onclick="return confirm('Really delete this media and all references?');" style="background-color: #f99" type="submit" name="deleterefs" value="Delete All" />
					<?php } ?>

		</form>

					<?php if( $item->type != "link" && !$is_adding ) { ?>			
						<h4>Replace Image</h4>
						<form method="post" action="/admin//media/edit/<?=$item->uuid?>" enctype="multipart/form-data" >
							<input type="file" name="userfile" />
							<input type="submit" name="replace" value="Replace" />
						</form>
					<?php } ?>
				</td>
				
		<!-- P R E V I E W -->
		<td style="width: 40%; padding-right: 30px; padding-top: 50px;" valign="top">
				<div class="media-edit-preview">
		<?php if( $item->type == "link") { 

			echo get_embed_object( $item->title );

		} else { ?>
			<?php if( file_exists('media/'. $item->uuid)) { ?>
				<div style="float: right">
					<!--
					<ul>
						<li>
							<a href="#" id="toggle_size" onclick="toggle_size(); return false;" title="toggle view" style="font-size: .8em;">[+]</a><br/>
						</li>
						<li>
							<a href="#" id="bw_filter" onclick="bw_filter(); return false;" title="make b/w" style="font-size: .8em;">b/w</a><br/>
						</li>
					</ul>
					-->
				</div>
				<img id="theImage" src="/media/<?= $item->uuid ?>" />
				<canvas id="theCanvas" style="border: 1px solid #000;"></canvas>
			<?php } else { ?>
				<img id="theImage" src="/img/image_not_found.jpg" />
			<? } ?>
		<?php } ?>
			</div>

		<?php echo validation_errors('<div class="error">', '</div>'); ?>
		</td>
				
	</tr>
</table>


