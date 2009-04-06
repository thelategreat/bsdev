<h3>Edit Subscriber</h3>
<?=$error_msg?>

<form method="post">
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
	<select name="subscriptions" multiple="multiple" style="width: 100%;">
	</select>
	</fieldset>
	</td>
</tr>
</table>
<input type="submit" name="save" value="Save" />
<input type="submit" name="cancel" value="Cancel" />
</form>
