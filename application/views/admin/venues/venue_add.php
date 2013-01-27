<h3>Add Venue</h3>
<?=$error_msg?>

<form method="post">
<fieldset><legend>Details</legend>
<table style="border:0">
<tr>
  <td><label for="venue">Venue</label></td>
  <td><input name="venue" size="50" /></td>
</tr>
<tr>
  <td><label for"location">Location</label></td>
  <td><?= $locations ?></td>
</tr>
</table>
</fieldset>
<input class="save-button" type="submit" name="save" value="Save" />
<input class="cancel-button" type="submit" name="cancel" value="Cancel" />
</form>
