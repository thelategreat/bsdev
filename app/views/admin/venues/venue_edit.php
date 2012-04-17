<?=$tabs?>

<form method="post">
<fieldset><legend>Venue Details</legend>
<table style="border:0">
<input type="hidden" name="id" value="<?=$data->id?>" />
<tr>
  <td><label for="venue">Venue</label></td>
  <td><input name="venue" size="50" value="<?=$data->venue?>" /></td>
</tr>
</table>
</fieldset>
<input class="save-button" type="submit" name="save" value="Save" />
<input class="cancel-button" type="submit" name="cancel" value="Cancel" />
</form>
