<script type="text/javascript">
function add_subscription()
{
	!$('#available_lists option:selected').appendTo('#subscription_list');
	$('#subscription_list option').removeAttr("selected");	
	return false;	
}

function remove_subscription()
{
	!$('#subscription_list option:selected').appendTo('#available_lists');
	return false;
}

function can_submit()
{
	// select all before we submit
	$('#subscription_list option').attr("selected","selected");	
	return true;
}

</script>

<h3>Edit Subscriber</h3>
<?=$error_msg?>

<form method="post" onsubmit="return can_submit();">
<table style="border: 0">
<tr>
	<td>
	<fieldset><legend>Details</legend>
	<table style="border: 0">
		<input type="hidden" name="id" value="<?=$subscr->id?>"
		<tr>
		  <td><label for="email">E-Mail</label></td>
		  <td><input name="email" size="50" value="<?=$subscr->email?>" /></td>
		</tr>
		<tr>
		  <td><label for="fullname">Full Name</label></td>
		  <td><input name="fullname" size="50" value="<?=$subscr->fullname?>"/></td>
		</tr>
		<tr>
		  <td><label for="pref_format">Preferred Format</label></td>
		  <td>
			<select name="pref_format">
			  <option <?=($subscr->pref_format == "HTML" ? "selected" : "")?>>HTML</option>
			  <option <?=($subscr->pref_format == "Plain" ? "selected" : "")?>>Plain</option>
			</select>
		  </td>
		</tr>
		<tr>
		  <td><label for="status">Status</label></td>
		  <td>
			<select name="status">
			  <option <?=($subscr->status == "active" ? "selected" : "")?>>active</option>
			  <option <?=($subscr->status == "inactive" ? "selected" : "")?>>inactive</option>
			  <option <?=($subscr->status == "banned" ? "selected" : "")?>>banned</option>
			</select>
		  </td>
		</tr>
	</table>
	</fieldset>
	</td>
	<td valign="top">
		<fieldset><legend>Subscriptions</legend>
			<select name="subscriptions[]" id="subscription_list" multiple="multiple" style="width: 100%;">
				<?php foreach($subs as $k => $v) { ?>
					<option value="<?=$k?>"><?=$v?></option>
				<?php } ?>
			</select>
			<select name="lists" id="available_lists">
				<?php foreach($lists as $k => $v) { ?>
					<option value="<?=$k?>"><?=$v?></option>
				<?php } ?>
			</select>
			<button onclick="return add_subscription();">+</button>
			<button style="background-color: #e99;" onclick="return remove_subscription();">-</button>
	</fieldset>
	</td>
</tr>
</table>
<input type="submit" name="save" value="Save" />
<input type="submit" name="cancel" value="Cancel" />
</form>
