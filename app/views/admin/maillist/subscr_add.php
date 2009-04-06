<h3>Add Subscriber</h3>
<?=$error_msg?>

<form method="post">
<fieldset><legend>Details</legend>
<table style="border: 0">
<tr>
  <td><label for="email">E-Mail</label></td>
  <td><input name="email" size="50" /></td>
</tr>
<tr>
  <td><label for="fullname">Full Name</label></td>
  <td><input name="fullname" size="50" /></td>
</tr>
<tr>
  <td><label for="pref_format">Preferred Format</label></td>
  <td>
	<select name="pref_format">
	  <option>HTML</option>
	  <option>Plain</option>
	</select>
  </td>
</tr>
<tr>
  <td><label for="status">Status</label></td>
  <td>
	<select name="status">
	  <option>active</option>
	  <option>inactive</option>
	  <option>banned</option>
	</select>
  </td>
</tr>
</table>
</fieldset>
<input type="submit" name="save" value="Save" />
<input type="submit" name="cancel" value="Cancel" />
</form>
