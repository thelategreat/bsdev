<?php
/**
 * Used by the media tab on most everything
 */
?>

<?= $errors ?>

<p style="clear:both;">
<table class='media_table'>
<tr>
  <th style="width: 100%" colspan="2" align="left">Films</th>
  <th>Actions</th>
</tr>
<?php
	$count = 0;
    if ($files) foreach( $files as $file ) {
    ?>
	<tr <?= ($count % 2) != 0 ? "class='odd'" : "" ?>>
	  	<td width="10%">
	  		<? if (isset($file->media[0])) { ?>
				<img src="<?=imgCache('media/' . $file->media[0]->uuid, 100,100);?>" />
			<? } ?>
		</td>
        <td><?=$file->title?> </td>
        <td><a href="#" class="remove film" data="<?= $file->id?>">Remove</a></td>      
	</tr>
<?php $count++; }?>
</table>
