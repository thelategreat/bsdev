<div style="float: right">
	<form id="search_form" method="post">
		<input id="q" name="q" value="<?foreach($stags as $tag){ echo $tag . " ";}?>" />
	</form>
</div>

<h3>Media Librarie</h3>

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
								echo '<img src="/img/icons/icon_video.jpg" width="70" />';					
							}
							break;
						default:
							if( file_exists('media/'. $item->uuid))
								echo '<img src="/media/'. $item->uuid . '" width="70" />';
							else
								echo '<img src="/img/image_warning.jpg" width="70" />';
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
			<td><a href="#" onclick="MediaBrowser.search_view(<?=$page-1?>); return false;">⇐ prev</a></td>
			<td align="right"><a href="#" onclick="MediaBrowser.search_view(<?=$page+1?>); return false;">next ⇒</a></td>
		</tr>
	</table>
</div>