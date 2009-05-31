
<div style="float: right">
<a href="#" onclick="$('#upload_div').toggle();"><img src="/img/go-up.png" /> upload</a>
</div>

<div id="upload_div" style="display: none;">
<form method="post" action="" enctype="multipart/form-data" />
<input type="hidden" name="media_path" value="<?=$media_path?>" />
<input type="file" name="userfile" />
<input type="submit" name="upload" value="Upload" />
</form>
</div>

<?= $errors ?>

<p style="clear:both;">
<table class='media_table'>
<tr>
  <th style="width: 40%" colspan="2">file</th>
  <th>tags</th>
  <th>author</th>
  <th>attached to</th>
  <th>date/size</th>
</tr>
<?php 
	$count = 0;
	foreach( $files as $file ) {  ?>
	<tr <?= ($count % 2) != 0 ? "class='odd'" : "" ?>>
	  <td width="10%"><img src="<?=$file['url']?>" width="50" /></td>
	  <td><?=$file['fname']?>
		<p><small>
			<a href="#">Edit</a> | 
			<a href="<?= $this->uri->uri_string()?>/rm/<?=$file['fname']?>" onclick="return confirm('Really delete this?');">Delete</a>
		</small></p>
		</td>
  	<td></td>
  	<td><?=$file['author']?></td>
	  <td><?=$file['attached_to']?></td>
	  <td><?=$file['date']?><br/><?=$file['size']?></td>
	</tr>
<?php $count++; }?>
</table>