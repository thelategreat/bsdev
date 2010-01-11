<div style="float: right">
	<form id="search_form" method="post">
		<input id="query" name="q" value="<?foreach($stags as $tag){ echo $tag . " ";}?>" />
	</form>
</div>

<h3>Media Library</h3>

<?= $errors ?>

<table class="media_table">
	<tr>
		<th></th>
		<th>title/link</th>
		<th width="30%">description</th>
		<th>type</th>
		<th>tags</th>
	</tr>
<?php $count = 0; foreach( $items as $item ) { ?>
	<tr <?= ($count++ % 2 ) ? "class='odd'" : '' ?>>
		<td align="center">
			<a href="/admin/media/edit/<?= $item->uuid ?>" title="click to edit meta">
			<?php
				switch( $item->type ) {
					case 'link':
					  if( isset($item->thumbnail) && strlen($item->thumbnail)) {
							echo '<img src="' . $item->thumbnail . '" width="70" />';											
					  } else {
							echo '<img src="/media/logos/youtube.jpg" width="70" />';					
						}
						break;
					default:
						echo '<img src="/media/'. $item->uuid . '" width="70" />';					
			}
			?>
			</a>
			<p/>
			<span class="field_tip"><?= $item->uuid ?></span>
		</td>
		<td><?= $item->title ?><br/><em><?= $item->caption ?></em></td>
		<td><?= $item->description ?></td>
		<td><?= $item->type ?></td>
		<td><?= $item->tags ?></td>
	</tr>
<?php }?>
</table>

<div class="pager">
	<table>
		<tr>
			<td><a href="/admin/media/index/<?=$page-1?>">⇐ prev</a></td>
			<td align="right"><a href="/admin/media/index/<?=$page+1?>">next ⇒</a></td>
		</tr>
	</table>
</div>

<div id="upload_div" >
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
