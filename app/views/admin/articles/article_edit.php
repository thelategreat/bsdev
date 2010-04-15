<script type="text/javascript" src="/js/ajaxupload.js" ></script>
<script type="text/javascript" src="/js/admin_mb.js" ></script>

<script>
$(function()
{
	Date.format = "yyyy-mm-dd";
	$('.date-pick').datePicker({horizontalPosition: $.dpConst.POS_RIGHT });
	$('.date-pick').dpSetStartDate('2000-01-01');
});
</script>
<!--
<?= $tabs ?>
-->
<form class="general" action="/admin/articles/edit/<?=$article->id?>" method="post">

<div style="float: right">
	<fieldset><legend>Meta</legend>
	<table style="border: 0">
	  <tr>
			<td>
			<table>
				<tr><th>Status</th></tr>
				<tr><td>
					<?= $status_select ?>
				</td></tr>
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
					<input class="date-pick" name="publish_on" size="12" onblur="" id="fld_publish_on" value="<?=date('Y-m-d',strtotime($article->publish_on))?>"/>
					<br/><span class="small">yyyy-mm-dd<span>
				</td></tr>
			</table>
			</td>
		</tr>
	  <tr>
			<td>
			<table>
				<tr><th>Tags</th></tr>
				<tr>
					<td><textarea name="tags" class="mceNoEditor" cols="20" rows="3"><?=$article->tags?></textarea>
					</td>
				</tr>
			</table>
			</td>
		</tr>
	  <tr>
			<td>
				<hr/>
				<table>
					<tr>
						<td>
							<input type="submit" style="background-color: #9f9;" name="save" value="Update" />
							<input type="submit" name="cancel" value="Cancel" />
						</td>
						<td align="right">
							| <input style="background-color: #faa" type="submit" name="rm" value="Delete" onclick="return confirm('Realy delete this article?');" />
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	</fieldset>
</div>

<fieldset><legend>Edit Article</legend>
<table style="border: 0">
  <tr>
		<table style="margin-top: -10px;">
			<tr>
    		<td><label for="title">Title</label></td>
				<td><input name="title" size="60" value="<?=$article->title?>"/></td>
			</tr>
			<tr>
				<td/>
				<td><?=form_error('title')?></td>
			</tr>
			<tr>
    		<td><label for="author">Author</label></td>
				<td><input name="author" size="60" value="<?=$article->author?>"/></td>
			</tr>
			<tr>
				<td/>
				<td><?=form_error('author')?></td>
			</tr>
		</table>
  </tr>
  <tr>
    <td/>
  </tr>
  <tr>
    <td><textarea name="body" rows="15" cols="80"><?=$article->body?></textarea>
    <br/><?=form_error('body')?></td>
    <td valign="top">
		</td>		
  </tr>
  <tr>
    <td>
		<table><tr><th>Excerpt</th></tr>
			<tr>
				<td><textarea name="excerpt" class="mceNoEditor" rows="5" cols="80"><?=$article->excerpt?></textarea></td>
			</tr>
		</table>
		<td valign="top">
		</td>
  </tr>
</table>
</fieldset>
</form>
