
<script language="javascript" type="text/javascript">
$(function() {
});
</script>

<div style="float: right">
<div id="upload_button"><img src="/img/upload.png" /></div>
</div>
<h4><?=$media_path?></h4>

<?= $errors ?>

<p style="clear:both;">
<table class='media_table'>
<tr>
  <th style="width: 40%" colspan="2">file</th>
  <th>tags</th>
  <th>author</th>
  <th>date/size</th>
</tr>
<?php 
	$count = 0;
	foreach( $files as $file ) {  ?>
	<tr <?= ($count % 2) != 0 ? "class='odd'" : "" ?>>
	  <td width="10%"><img src="<?=$file['url']?>" width="50" /></td>
	  <td><?=$file['fname']?>
		<p><small>
			<a href="<?= $this->uri->uri_string()?>/edit/<?=$file['fname']?>">Edit</a> | 
			<a href="<?= $this->uri->uri_string()?>/rm/<?=$file['fname']?>" onclick="return confirm('Really delete this?');">Delete</a>
		</small></p>
		</td>
  	<td></td>
  	<td><?=$file['author']?></td>
	  <td><?=$file['date']?><br/><?=$file['size']?></td>
	</tr>
<?php $count++; }?>
</table>