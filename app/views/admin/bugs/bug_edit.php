<style type="text/css">
div.comment 
{
	width: 70%;
	padding-bottom: 20px;
	border-bottom: 1px solid #ddd;
}

div.comment span.author {
	display: block;
	font-style: italic;
	font-size: 90%;
}

</style>
<form class="general" action="/admin/bugs/edit/<?=$bug->id?>" method="post">
	<fieldset><legend>Edit Issue</legend>
		<table style="border: 0">
		  <tr>
				<table style="margin-top: -10px;">
					<tr>
		    		<td><label for="summary">Summary</label></td>
						<td><input name="summary" size="60" value="<?= set_value('summary', $bug->summary) ?>"/></td>
					</tr>
					<tr>
						<td/>
						<td><?=form_error('summary')?></td>
					</tr>
					<tr>
		    		<td valign="top"><label for="description">Description</label></td>
						<td><textarea name="description" class="mceNoEditor" rows="10" cols="70"><?= set_value('description', $bug->description) ?></textarea></td>
					</tr>
					<tr>
						<td/>
						<td><?=form_error('description')?></td>
					</tr>
			</tr>
			<tr>
				<td/>
				<td>
					<table style="width: auto">
						<tr>
	    		<td><label for="type">Type</label></td>
					<td><select name="type">
								<option value="bug" <?=$bug->type == "bug" ? "selected" : ""?>>Bug</option>
								<option value="feature" <?=$bug->type == "feature" ? "selected" : ""?>>Feature Request</option>
							</select>
					</td>
	    		<td><label for="status">Status</label></td>
					<td><select name="status">
								<option value="new" <?=$bug->status == "new" ? "selected" : ""?>>new</option>
								<option value="open" <?=$bug->status == "open" ? "selected" : ""?>>open</option>
								<option value="fixed" <?=$bug->status == "fixed" ? "selected" : ""?>>fixed</option>
								<option value="wontfix" <?=$bug->status == "wontfix" ? "selected" : ""?>>wontfix</option>
								<option value="closed" <?=$bug->status == "closed" ? "selected" : ""?>>closed</option>
							</select>
					</td>
	    		<td><label for="assigned_to">Assign To</label></td>
					<td><?= $assigned_to_select ?></td>
				</tr>
				</table>
			</td>
			</tr>
		</table>
	</fieldset>
	
	<input type="submit" style="background-color: #9f9;" name="save" value="Update" />
	<input type="submit" name="cancel" value="Cancel" />

</form>

<hr/>

<h4><a href="#" onclick="$('#comment-form-div').toggle('slow');"><img src="/img/admin/comment_add.png" /> Comment</a></h4>

<div id="comment-form-div" style="display: none">
<form method="post" action="/admin/bugs/edit/<?=$bug->id?>">
	<textarea class="mceNoEditor" name="comment-text" rows="4" cols="50"></textarea><br/>
	<input type="submit" name="add-comment" value="Speak" />
</form>
</div>

<?php foreach( $comments->result() as $comment ): ?>
	<div class="comment">
		<span class="author"><?=$comment->submitted_by?> on <?= $comment->created_on ?></span>
		<?= $comment->comment ?>
	</div>
<?php endforeach; ?>

