<script>
$(function()
{
	Date.format = "yyyy-mm-dd";
	$('.date-pick').datePicker({horizontalPosition: $.dpConst.POS_RIGHT });
	$('.date-pick').dpSetStartDate('2000-01-01');
});
</script>

<form class="general" action="/admin/articles/add" method="post">

  <!--
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
				<tr>
				<td>
					<input class="date-pick" name="publish_on" size="12" onblur="" id="fld_publish_on" value="<?=date('Y-m-d')?>"/>
					<br/><span class="small">yyyy-mm-dd<span>
				</td>
				</tr>
			</table>
			</td>
		</tr>
	  <tr>
			<td>
			<table>
				<tr><th>Display Priority</th></tr>
				<tr><td><?= $priority_select ?></td></tr>
			</table>
			</td>
		</tr>
	  <tr>
			<td>
			<table>
				<tr><th>Tags</th></tr>
				<tr><td><textarea name="tags" class="mceNoEditor" cols="20" rows="3"><?=set_value('tags')?></textarea></td></tr>
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
-->

<fieldset><legend>Add Essay</legend>
<table style="border: 0;">
  <tr>
    <td>
		<table style="margin-top: -10px;  width: auto;">
			<tr>
    		<td><label for="title_fld">Title</label></td>
				<td colspan="4"><input name="title" id="title_fld" size="60" value="<?=set_value('title')?>"/></td>
        <td><?=form_error('title')?></td>
        <td/>
			</tr>
			<tr>
    		<td><label for="author_fld">User</label></td>
				<td colspan="4"><input name="author" id="author_fld" size="60" value="<?=set_value('author')?>"/></td>
        <td><?=form_error('author')?></td>
			</tr>
      <tr>
        <td>Pub Date</td>
        <td>
          <input title="YYYY-MM-DD" class="date-pick" name="publish_on" size="12" onblur="" id="fld_publish_on" value="<?=date('Y-m-d')?>"/>
        </td>
        <td>Section</td>
        <td><?= $group_select ?></td>
      </tr>
		</table>
    </td>
  </tr>
  <tr>
    <th>Teaser</th>
  </tr>
  <tr>
    <td colspan="4">
      <textarea id="excerpt_fld" name="excerpt" class="mceNoEditor" rows="5" cols="60"><?=set_value('excerpt')?></textarea>
      <br/><?=form_error('excerpt')?>
    <td/>
  </tr>
  <tr>
    <th>Essay</th>
  </tr>
  <tr>
    <td colspan="4"><textarea name="body" id="body_fld" rows="15" cols="80"><?=set_value('body')?></textarea>
        <br/><?=form_error('body')?>
    </td>
  </tr>
  <tr>
    <td>
      <hr/>
      <input type="submit" class="save-button" name="save" value="Add" />
      <input type="submit" class="cancel-button" name="cancel" value="Cancel" />
    </td>
  </tr>
</table>
</fieldset>
</form>
