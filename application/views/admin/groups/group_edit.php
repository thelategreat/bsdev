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
		<td><label>Short URL</label></td>
		<td><input type="text" name="route" class="textbox" size="50" value="<?= set_value('route', $group->route)?>" />
			<?=form_error('route')?>
		</td>
	</tr>
	<tr>
		<td><label>Page Template</label></td>
		<td>
			<select name='template'>
				<? foreach ($templates as $it) { ?> 
				<option value='<?=$it->name;?>' <? if (isset($it->selected) && $it->selected === true) echo 'selected' ?>><?=$it->name;?></option>
				<? } ?>
			</select>
		</td>
	</tr>
	<tr id='orientation_select'>
		<td><label>Link Type</label></td>
		<td>
			<input type="radio" name="orientation" value="horizontal" <? if (!isset($group->orientation) || $group->orientation = 'horizontal') echo 'checked'; ?>>Horizontal
			<input type="radio" name="orientation" value="vertical" <? if (isset($group->orientation) && $group->orientation = 'vertical') echo 'checked'; ?>>Vertical<br>
		</td>
	</tr>
</table>
<table id='lp'>
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

<select style='display:none' id='dropdown'>
	<? foreach ($lists_dropdown as $key=>$val) { ?>
		<option value='<?=$key?>'><?=$val?></option> 
	<? } ?>			
</select>
</form>

<script type='text/javascript'>
$(function() {
	$('select[name="template"]').change(function() {
		loadAvailablePositions($(this).val());
	});

	$('select[name="parent_id"]').change(function() {
		updateOrientationVisibility();
	});

	loadAvailablePositions($('select[name="template"]').val());
	updateOrientationVisibility();
});

function loadAvailablePositions(templateName) {
	$.post('/admin/groups/get_template_positions', {template: templateName, id: <?=$group->id;?>}, function(data) {
		console.log(data);
		var options = $('#dropdown'); 
		var lp = $('#lp');
		$('#lp').empty();
		$.each(data.positions, function(index, value) {
			$(lp).append('<tr><td>'+value.name+'</td><td><select id="lists_'+value.id+'" name="lists['+value.id+']">'+$(options).html()+'</select></td></tr>');
			if (value.lists_id > 0) {
				// This is the selected list, should be selected in the dropdown
				$('#lists_'+value.id+ ' option[value="'+value.lists_id+'"]').prop('selected',true);
				console.log(value.lists_id);

			}
		});
	}, 'json').fail(function() {
		alert('ERROR: Unable to load template positions.');
	})
}

function updateOrientationVisibility() {
	var target = $('select[name="parent_id"]');
	if ($(target).val() == 1) {
		// top level items are the only ones eligible for vertical layout
		$('#orientation_select').show();
	} else {
		$('#orientation_select').hide();
	}
}
</script>
