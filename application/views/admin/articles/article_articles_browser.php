<?php
/**
 * Used by the media tab on most everything
 */
?>
<script language="javascript" type="text/javascript">

$(function() {
});
</script>

<?= $errors ?>

<p style="clear:both;">
<table class='media_table'>
<tr>
  <th style="width: 100%" colspan="2">Articles</th>
  <th>Actions</th>
</tr>
<?php
	$count = 0;
    if ($files) foreach( $files as $file ) {
    ?>
	<tr <?= ($count % 2) != 0 ? "class='odd'" : "" ?>>
	  <td width="10%">
			<img src="<?=$file->image?>" width="70" />
		</td>
        <td><?=$file->title?> </td>
        <td><a href="#" class="remove article" data="<?= $file->id?>">Remove</a></td>      
	</tr>
<?php $count++; }?>
</table>
