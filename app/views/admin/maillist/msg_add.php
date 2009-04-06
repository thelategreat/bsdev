<?=$error_msg?>

<form method="post">
<fieldset><legend>Add Newsletter</legend>
<table style="border: 0">
<tr>
  <td><label for="subject">Subject</label></td>
  <td><input name="subject" size="50" /></td>
</tr>
<tr>
  <td><label for="from">From</label></td>
  <td><input name="from" size="50" /></td>
</tr>
<tr>
  <td valign="top"><label for="text_fmt">Message</label></td>
  <td><textarea name="text_fmt" rows="15" cols="80"></textarea></td>
</tr>
<tr>
  <td valign="top"><label for="text_plain">Message (plain)</label></td>
  <td><textarea name="text_plain" class="mceNoEditor" rows="10" cols="80"></textarea></td>
</tr>
</table>
</fieldset>
<input type="submit" name="save" value="Save" />
<input type="submit" name="cancel" value="Cancel" />
</form>
