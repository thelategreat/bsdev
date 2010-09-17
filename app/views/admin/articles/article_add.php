<script>
$(function()
{
	Date.format = "yyyy-mm-dd";
	$('.date-pick').datePicker({horizontalPosition: $.dpConst.POS_RIGHT });
	$('.date-pick').dpSetStartDate('2000-01-01');
});
</script>

<form class="general" action="/admin/articles/add" method="post">

<div style="float: right">
	<fieldset><legend>Meta</legend>
	<table style="border: 0">
	  <tr>
			<td>
			<table>
				<tr><th>Status</th></tr>
				<tr><td>
					Draft
				</td></tr>
			</table>
			</td>
		</tr>
	  <tr>
			<td>
			<table>
				<tr><th>Group</th></tr>
				<tr><td><?= $group_select ?></td></tr>
			</table>
			</td>
		</tr>
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
				<tr><th>Pub Date</th></tr>
				<tr><td>
					<input class="date-pick" name="publish_on" size="12" onblur="" id="fld_publish_on" value="<?=date('Y-m-d')?>"/>
					<br/><span class="small">yyyy-mm-dd<span>
				</td></tr>
			</table>
			</td>
		</tr>
	  <tr>
			<td>
			<table>
				<tr><th>Tags</th></tr>
				<tr><td><textarea name="tags" class="mceNoEditor" cols="20" rows="3"></textarea></td></tr>
			</table>
			</td>
		</tr>
	  <tr>
			<td>
				<hr/>
				<input type="submit" style="background-color: #9f9;" name="save" value="Add" />
				<input type="submit" name="cancel" value="Cancel" />
			</td>
		</tr>
	</table>
	</fieldset>
</div>


<fieldset><legend>Add Article</legend>
<table style="border: 0">
  <tr>
		<table style="margin-top: -10px;">
			<tr>
    		<td><label for="title">Title</label></td>
				<td><input name="title" size="60" value="<?=set_value('title')?>"/></td>
			</tr>
			<tr>
				<td/>
				<td><?=form_error('title')?></td>
			</tr>
			<tr>
    		<td><label for="author">Author</label></td>
				<td><input name="author" size="60" value="<?=set_value('author')?>"/></td>
			</tr>
			<tr>
				<td/>
				<td><?=form_error('author')?></td>
			</tr>
		</table>
  </tr>
  <tr>
    <td><textarea name="body" rows="15" cols="80"></textarea>
    <br/><?=form_error('body')?></td>
  </tr>
  <tr>
		<table style="width: 0"><tr><th>Excerpt</th></tr>
			<tr>
				<td><textarea name="excerpt" class="mceNoEditor" rows="5" cols="60"></textarea></td>
			</tr>
		</table>
  <td/>
  </tr>
</table>
</fieldset>
</form>
