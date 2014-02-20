<?php
/**
 * Used by the media tab on most everything
 */
?>

<?= $errors ?>

<p style="clear:both;">
<table class='media_table'>
<tr style='border-bottom: 1px solid #ccc'>
  <th width='15%'></th>
  <th width='20%'>File</th>
  <th width='15%'>Author</th>
  <th width='20%'>Date / Size</th>
  <th width='10%'>Order</th>
  <th width='10%'>Slot</th>
</tr>
<?php 
	$count = 0;
	foreach( $files as $file ) { ?> 
	<tr <?= ($count % 2) != 0 ? "class='odd'" : "" ?> data-id='<?=$file->map_id;?>' >
	 	<td>
			<img style='vertical-align:top' src="/i/size/o/<?=$file->thumbnail?>/w/70" />
		</td>
	  <td><h4><?=$file->caption?></h4><br>
			<a class='buttonlink' href="/admin/media/edit/<?=$file->url?>">Edit</a> 
			<a class='buttonlink' href="/admin/media/rmlink/<?=$file->url . '/' . $file->slot . $media_path ?>" onclick="return confirm('Really delete this?');">Delete</a>
		</td>
  	<td><?=$file->author?></td>
	  <td><small><?=$file->date; ?><br/><?=$file->size; ?></small></td>
		<td>
			<?php if( $count != 0 ) { ?>
			<a href="/admin/media/move/up/<?= $file->url . $slot . $media_path ?>">
				<img class="icon" src="/img/admin/go-up.png" />
			<a/> 
			<?php } else { echo "&nbsp;"; }?>
			- 
			<?php if( $count != count($files) - 1 ) { ?>
			<a href="/admin/media/move/down/<?= $file->url . '/' . $slot . $media_path ?>">
				<img class="icon" src="/img/admin/go-down.png" />
			</a>
			<?php } ?>
		</td>
		<td><select class='slot'>
				<option value='' <? if ($file->slot == '') echo 'selected'; ?>>- None -</option>
				<option value='general' <? if ($file->slot == 'general') echo 'selected'; ?>>Primary</option>
				<option value='secondary' <? if ($file->slot == 'secondary') echo 'selected'; ?>>Secondary</option>
			</select>
		</td>
	</tr>
<?php $count++; }?>
</table>
