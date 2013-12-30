<fieldset><legend>Edit Group</legend>
<?= form_open('/admin/groups/edit/' . $group->id);?>
<table style="border: 0">
	<tr>
		<td><label>Parent</label></td>
		<td><select name="parent_id"><option value="1">--Top Level--</option><?=$parent_select?></select></td>
	</tr>
	<tr>
		<td><label>Name</label></td>
		<td><input type="text" name="name" class="textbox" size="50" value="<?= set_value('name', $group->name)?>" />
			<?=form_error('name')?>
		</td>
	</tr>
	<tr>
		<td><label>Active</label></td>
		<td><input type="checkbox" name="active"  <?= $group->active ? "checked='checked'" : '' ?>/>
		</td>
	</tr>
	<tr>
		<td><label>Page Template</label></td>
		<td>
			<select name='template_id'>
				<option value=''>Default Template</option>
				<? foreach ((array)$templates as $it) { ?> 
				<option value='<?=$it->id;?>'><?=$it->name;?></option>
				<? } ?>
			</select>
		</td>
	</tr>

	<? foreach ($list_positions as $l) { ?>
	<tr>
		<td><?=$l->name?></td><td>
			<select name='lists[<?=$l->id;?>]'>
				<? foreach ($lists_dropdown as $key=>$val) { ?>
					<option value='<?=$key?>' <? if (isset($group_lists[$l->id]) && $group_lists[$l->id] == $key) echo 'selected'; ?>><?=$val?></option> 
				<? } ?>			
			</select>
	</tr>
	<? } ?>
</table>
</fieldset>
<input class="button" name="save" type="submit" value="Save" />
<input class="button" name="cancel" type="submit" value="Cancel" />
</form>
