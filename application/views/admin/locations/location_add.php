<div class=container>
  <header>Add Location</header>
<br>

<?=$error_msg?>

<form method="post">
<fieldset><legend>Details</legend>
<table style="border:0">
<tr>
  <td><label for="name">Name</label></td>
  <td><input name="name" size="50" /></td>
</tr>
<tr>
  <td><label for="address">Street Address</label></td>
  <td><input name="address" size="50" /></td>
</tr>
<tr>
  <td><label for="city">City/Postal</label></td>
  <td><input name="city" size="30" value="Guelph" />/<input name="postal" size="7" /></td>
</tr>
<tr>
  <td valign="top"><label for="descrip">Description</label></td>
  <td><textarea name="descrip" rows="10" cols="50"></textarea></td>
</tr>
<tr>
  <td valign="top"><label for="more_info">Extra Info</label></td>
  <td><textarea name="more_info" class="mceNoEditor" rows="10" cols="50"></textarea></td>
</tr>
</table>
</fieldset>
<input class="save-button" type="submit" name="save" value="Save" />
<input class="cancel-button" type="submit" name="cancel" value="Cancel" />
</form>
