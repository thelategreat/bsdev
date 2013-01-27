
<fieldset><legend>Add Group</legend>
<?= form_open('/admin/groups/add');?>
<table style="border: 0">
	<tr>
		<td><label>Parent</label></td>
		<td><select name="parent_id"><option value="1">--Top Level--</option><?=$parent_select?></select></td>
	</tr>
	<tr>
		<td><label>Name</label></td>
		<td><input type="text" name="name" class="textbox" size="50" />
			<?=form_error('name')?>
		</td>
	</tr>
	<tr>
		<td><label>Active</label></td>
		<td><input type="checkbox" name="active" checked="checked" />
		</td>
	</tr>
</table>
</fieldset>
<input class="button" name="save" type="submit" value="Add" />
<input class="button" name="cancel" type="submit" value="Cancel" />
</p>
</form>
