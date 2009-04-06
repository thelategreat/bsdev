<h3>Edit Mail List</h3>
<?=$error_msg?>

<form method="post">
<fieldset><legend>Details</legend>
<table style="border: 0">
<input type="hidden" name="id" value="<?=$list->id?>"
<tr>
  <td><label for="name">List Name</label></td>
  <td><input name="name" size="50" value="<?=$list->name?>"/></td>
</tr>
<tr>
  <td valign="top"><label for="descrip">Description</label></td>
  <td><textarea name="descrip" rows="10" cols="50"><?=$list->descrip?></textarea></td>
</tr>
</table>
</fieldset>
<input type="submit" name="save" value="Save" />
<input type="submit" name="cancel" value="Cancel" />
</form>
