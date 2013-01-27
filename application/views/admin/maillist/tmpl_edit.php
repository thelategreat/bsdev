<?=$error_msg?>

<form method="post">
	<input type="hidden" name="id" value="<?=$tmpl->id?>">
<fieldset><legend>Add Template</legend>
<table style="border: 0">
<tr>
  <td><label for="name">Name</label></td>
  <td><input name="name" size="50" value="<?=$tmpl->name?>"/></td>
</tr>
<tr>
  <td valign="top"><label for="tmpl_text">Template</label></td>
  <td><textarea name="tmpl_text" rows="15" cols="80"><?=$tmpl->tmpl_text?></textarea></td>
</tr>
</table>
</fieldset>
<input type="submit" name="save" value="Save" />
<input type="submit" name="cancel" value="Cancel" />
</form>
