<?=$tabs?>

<form method="post">
<fieldset><legend>Venue Details</legend>
<table style="border:0">
<input type="hidden" name="id" value="<?=$venue->id?>" />
<tr>
  <td><label for="name">Name</label></td>
  <td><input name="name" size="50" value="<?=$venue->name?>" /></td>
</tr>
<tr>
  <td><label for="address">Street Address</label></td>
  <td><input name="address" size="50" value="<?=$venue->address?>"/></td>
</tr>
<tr>
  <td><label for="city">City/Postal</label></td>
  <td><input name="city" size="30" value="<?=$venue->city?>" />/<input name="postal" size="7" value="<?=$venue->postal?>"/></td>
</tr>
<tr>
  <td valign="top"><label for="descrip">Description</label></td>
  <td><textarea name="descrip" rows="10" cols="50"><?=$venue->descrip?></textarea></td>
</tr>
<tr>
  <td valign="top"><label for="more_info">Extra Info</label></td>
  <td><textarea name="more_info" class="mceNoEditor" rows="10" cols="50"><?=$venue->more_info?></textarea></td>
</tr>
</table>
</fieldset>
<input class="save-button" type="submit" name="save" value="Save" />
<input class="cancel-button" type="submit" name="cancel" value="Cancel" />
</form>
