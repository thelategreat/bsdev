<script type="text/javascript" src="/js/ajaxupload.js" ></script>
<script type="text/javascript" src="/js/admin_mb.js" ></script>

<?= $tabs ?>

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
				<tr><th>Tags</th></tr>
				<tr>
					<td><textarea name="tags" class="mceNoEditor" cols="20" rows="5"><?=$article->tags?></textarea>
					</td>
				</tr>
			</table>
			</td>
		</tr>
	  <tr>
			<td>
				<hr/>
				<input type="submit" style="background-color: #9f9;" name="save" value="Update" />
				<input type="submit" name="cancel" value="Cancel" />
			</td>
		</tr>
	</table>
	</fieldset>
</div>

<fieldset><legend>Edit Article</legend>
<table style="border: 0">
  <tr>
    <td><input name="title" size="50" value="<?=$article->title?>"/>
		<br/><?=form_error('title')?>
    <td valign="top"></td>
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
		<table><tr><th>Excerpt</th><th>Media</th></tr>
			<tr>
				<td><textarea name="excerpt" class="mceNoEditor" rows="5" cols="60"><?=$article->excerpt?></textarea></td>
			  <td>
					<div id="media_preview">
						<a href="#" onclick="MediaBrowser.init({path: '/articles/<?=$article->id?>'});"><img src="/pubmedia/library/no_image.jpg" height="80" /></a>
						<br><small>no image assigned</small>
					</div>
				</td>
			</tr>
		</table>
		<td valign="top">
		</td>
  </tr>
</table>
</fieldset>
</form>

<div id="editModalDiv">
 <div id="modal_title">
	<span style="float: right">
		<img onclick="$.modal.close()" src="/img/close.png" title="Close" style="cursor: pointer;"/>
	</span>
    <h3>Media Browser</h3>
 </div>
 <div id="modal_content">
 </div>
</div>
