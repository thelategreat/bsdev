
<form class="general" action="/admin/bugs/add" method="post">
	<fieldset><legend>Add Issue</legend>
		<table style="border: 0">
		  <tr>
				<table style="margin-top: -10px;">
					<tr>
		    		<td><label for="summary">Summary</label></td>
						<td><input name="summary" size="60" value="<?=set_value('summary')?>"/></td>
					</tr>
					<tr>
						<td/>
						<td><?=form_error('summary')?></td>
					</tr>
					<tr>
		    		<td valign="top"><label for="description">Description</label></td>
						<td><textarea name="description" class="mceNoEditor" rows="10" cols="60"><?=set_value('description')?></textarea></td>
					</tr>
					<tr>
						<td/>
						<td><?=form_error('description')?></td>
					</tr>
			</tr>
			<tr>
    		<td><label for="type">Type</label></td>
				<td><select name="type">
							<option value="bug">Bug</option>
							<option value="feature">Feature Request</option>
						</select>
				</td>
			</tr>
			<tr>
				<td/>
				<td></td>
			</tr>
		</table>
	</fieldset>
	<input type="submit" style="background-color: #9f9;" name="save" value="Add" />
	<input type="submit" name="cancel" value="Cancel" />

</form>
