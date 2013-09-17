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
  <th style="width: 40%" colspan="2">file</th>
  <th>tags</th>
  <th>author</th>
  <th>date/size</th>
  <th>order</th>
</tr>
<?php 
	$count = 0;
	foreach( $files as $file ) {  ?>
	<tr <?= ($count % 2) != 0 ? "class='odd'" : "" ?>>
	  <td width="10%">
			<img src="/i/size/o/<?=$file['thumbnail']?>/w/70" />
		</td>
	  <td><?=$file['caption']?>
		<p><small>
			<a href="/admin/media/edit/<?=$file['url']?>">Edit</a> | 
			<a href="/admin/media/rmlink/<?=$file['url'] . '/' . $slot . $media_path ?>" onclick="return confirm('Really delete this?');">Delete</a>
		</small></p>
		</td>
  	<td></td>
  	<td><?=$file['author']?></td>
	  <td><small><?=$file['date']?><br/><?=$file['size']?></small></td>
		<td>
			<?php if( $count != 0 ) { ?>
			<a href="/admin/media/move/up/<?= $file['url'] . '/' . $slot . $media_path ?>">
				<img class="icon" src="/img/admin/go-up.png" />
			<a/> 
			<?php } else { echo "&nbsp;"; }?>
			- 
			<?php if( $count != count($files) - 1 ) { ?>
			<a href="/admin/media/move/down/<?= $file['url'] . '/' . $slot . $media_path ?>">
				<img class="icon" src="/img/admin/go-down.png" /></td>
			</a>
			<?php } ?>
	</tr>
<?php $count++; }?>
</table>
