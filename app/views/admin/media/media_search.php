<div style="float: right">
	<form id="search_form" method="post">
		<input id="q" name="q" value=" " />
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
						echo '<img src="/media/logos/youtube.jpg" width="70" />';					
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