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
  <th style="width: 100%" colspan="2">Product</th>
  <th>Action</th>
</tr>
<?php
	$count = 0;
    foreach( $files as $file ) {
    ?>
	<tr <?= ($count % 2) != 0 ? "class='odd'" : "" ?>>
	  <td width="10%">
			<img src="<?=$file['thumbnail']?>" width="70" />
		</td>
        <td><?=$file['title']?> - <?=$file['contributor']?></td>
        <td><a href="#" class="remove book" data="<?= $file['id']?>">Remove</a></td>
	</tr>
<?php $count++; }?>
</table>
