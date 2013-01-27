<?=$error_msg?>

<form method="post">
<fieldset><legend>Add Template</legend>
<table style="border: 0">
<tr>
  <td><label for="name">Name</label></td>
  <td><input name="name" size="50" /></td>
</tr>
<tr>
  <td valign="top"><label for="tmpl_text">Template</label></td>
  <td><textarea name="tmpl_text" rows="15" cols="80"></textarea></td>
</tr>
</table>
</fieldset>
<input type="submit" name="save" value="Save" />
<input type="submit" name="cancel" value="Cancel" />
</form>
