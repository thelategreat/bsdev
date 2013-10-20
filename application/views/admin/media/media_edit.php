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

function show_refs(uuid)
{
	// refs-info
	// media-edit-preview
	$('#refs-info').toggle('slow');
	return false;
}

$(document).ready(function() {
	loadImage();

	$('#delete-button').click(function(e) {
		e.preventDefault();
		var numRefs = $('#references_table').find('tr').length;

		if (numRefs > 0) {
			alert("This media can't be deleted since it's being used. Pleased see the references table below and delete the media from the referring articles.");

			return;
		}

		if (confirm('Really delete this media?')) {
			// Delete it, not referenced
			$.getJSON('/admin/media/delete/<?=$item->uuid?>', function(data) {
				if (data.success == false) {
					alert(data.message);
				} else {
					location.href = "<?=$redirect?>";
				}
			});
		}
	});
});

</script>


<div class=container>
	<header>Media</header>
</div>

<form class="general" action="/admin/media/edit/<?=$item->uuid?><?= $is_adding ? '/add' : ''?>" 
		method="post" enctype="multipart/form-data" >

<?php echo validation_errors('<div class="error">', '</div>'); ?>

<div class="media-edit-preview" id="media-edit-preview">
	<?php if( $item->type == "link") { 
		echo get_embed_object( $item->title );
	} else { ?>
		<?php if( file_exists('media/'. $item->uuid)) { ?>
			<img id="theImage" src="/media/<?= $item->uuid ?>" />
			<canvas id="theCanvas"></canvas>
		<?php } else { ?>
			<img id="theImage" src="/img/image_not_found.jpg" />
		<? } ?>
<?php } ?>
</div>
<section id="meta" style="float:left; width:40%">
	<table class='form-table'>
		<tr><th>Caption</th><td><input type='text' name="caption" size="50" value="<?= $item->caption ?>" /></td>
		<tr><th>TT# / ISBN</th><td><input type='text' name="tt_isbn" size="17" value="<?= $item->tt_isbn ?>" /></td>
		<tr><th>Description</th><td><textarea name="description" class="mceNoEditor" rows="5" cols="40"><?= $item->description ?></textarea></td>
		<tr><th>Tags</th><td><input type='text' name="tags" size="50" value="<?=$item->tags ?>" /></td></tr>
		<?php if( $item->type != "link" && !$is_adding ) { ?>			
		<tr><th>Replacement Image</th>
			<td>
				<input type="file" name="userfile" />
			</td>
		</tr>
	<?php } ?>
	</table>
</section>
<div style='clear:both' />

<section id='references' style="border:1px solid #aaa;padding:5px">
	<h3>This media is referenced in the following places:</h3>
	<table>
		<thead>
			<tr style='border-bottom: 1px solid black'><td width='50%'>Name</td><td width='10%'>Type</td><td>Link</td></tr>
		</thead>
	</table>
	<div style='height: 120px;overflow-y:scroll'>
	<table id='references_table'>
		<tbody>
			<? foreach ($used as $it) { ?>
				<tr>
					<td width='50%'><?= $it->title; ?></td>
					<td width='10%'><?= $it->type; ?></td>
					<td><a href="<?=$it->edit_url;?>"><?=$it->edit_url;?></a></td></tr>
			<? } ?>
		</tbody>
	</table>
	</div>
</section>
<nav>
	<? if ($this->session->userdata('sourcepage')) { ?>
	<a href="<?= base_url($this->session->userdata('sourcepage')); ?>">
	<button type='button' id='back-button' class='iconbutton'>
			<i class='icon-angle-left icon-2x'></i> Back 
	</button>
	</a>
	<? } ?>
	<button type='submit' id="save-button" class='iconbutton' name='save' value="Save">
		<i class="icon-save icon-2x"></i> Save 
	</button>
	<button type='submit' id='delete-button' class='iconbutton' name='rm' value='Delete'>
		<i class='icon-trash icon-2x'></i> Delete
	</button>
</nav>



<? /* **** THE STUFF BELOW THIS IS THE OLD PAGE *****  ?>
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
		<?php if( count($used) == 0 ) { ?>
	 	  <input onclick="return confirm('Really delete this?');" style="background-color: #f99" type="submit" name="delete" value="Delete" />
		<?php
		}
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
							<?php foreach( count($used) as $row ) { 
								$tmp = explode('/', $row->path );
								array_splice( $tmp, 2, 0, 'edit');
								$path = implode('/', $tmp );
								?>
								<tr><td><a href="/admin<?=$path?>/media"><?=$path?></a></td></tr>
							<?php } ?>
						</table>

			 	  	<input onclick="return confirm('Really delete this media and all references?');" style="background-color: #f99" type="submit" name="deleterefs" value="Delete All" />

					<?php } else if( count($used) != 0 ) { ?>
						<h4>References</h4>
						<table style="margin-top: -10px;">
							<?php foreach( $used as $row ) { 
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
			<td style="width: 40%; padding-right: 30px; padding-top: 20px;" valign="top">
				<a href="#" onclick="return show_refs('<?=$item->uuid?>');"><img src="/img/admin/info.png" width="16px"/></a>
				<div id="refs-info" style="display: none;">
					<h4>References</h4>
					<table style="margin-top: -10px;">
						<?php
						foreach( $used as $row ) { 
							$tmp = explode('/', $row->path );
							array_splice( $tmp, 2, 0, 'edit');
							$path = implode('/', $tmp );
							?>
							<tr><td><a href="/admin<?=$path?>/media"><?=$path?></a></td></tr>
			<?php } ?>
						</table>
				</div>
				<div class="media-edit-preview" id="media-edit-preview">
					<?php if( $item->type == "link") { 
						echo get_embed_object( $item->title );
					} else { ?>
						<?php if( file_exists('media/'. $item->uuid)) { ?>
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

<? */ ?>
