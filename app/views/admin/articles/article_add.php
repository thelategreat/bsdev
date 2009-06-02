
<form class="general" action="/admin/articles/add" method="post">

<div style="float: right">
	<fieldset><legend>Meta</legend>
	<table style="border: 0">
	  <tr>
			<td>
			<table>
				<tr><th>Category</th></tr>
				<tr><td><?= $category_select ?></td></tr>
			</table>
			</td>
		</tr>
	  <tr>
			<td>
			<table>
				<tr><th>Tags</th></tr>
				<tr><td><textarea name="tags" class="mceNoEditor" cols="20" rows="5"></textarea></td></tr>
			</table>
			</td>
		</tr>
	  <tr>
			<td>
				<hr/>
				<input type="submit" style="background-color: #9f9;" name="save" value="Post Article" />
				<input type="submit" name="cancel" value="Cancel" />
			</td>
		</tr>
	</table>
	</fieldset>
</div>


<fieldset><legend>Add Article</legend>
<table style="border: 0">
  <tr>
    <td><input name="title" size="50" value="<?=set_value('title')?>"/>
		<br/><?=form_error('title')?></td>
  </tr>
  <tr>
    <td><textarea name="body" rows="15" cols="80"></textarea>
    <br/><?=form_error('body')?></td>
  </tr>
  <tr>
		<table style="width: 0"><tr><th>Excerpt</th><th>Media</th></tr>
			<tr>
				<td><textarea name="excerpt" class="mceNoEditor" rows="5" cols="60"></textarea></td>
				<td><a href="#" onclick="alert('image library here');"><img src="/pubmedia/library/no_image.jpg" height="80" /></a>
				<br><small>no image assigned</small>
			</tr>
		</table>
  <td/>
  </tr>
</table>
</fieldset>
</form>
