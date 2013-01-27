<h3>Import Subscribers</h3>

<?=$error_msg?>

<form enctype="multipart/form-data" action="/admin/maillist/subscrim" method="POST">
		<label for="List">Import to list</label><select name="list"><option value="0">None</option><?=$lists?></select><br/>
		<input type="file" name="userfile" />
		<input type="submit" name="import" value="Import" />
</form>

<p class="note">
	This importer expects a CSV file. Column one should be a valid
	email address and column 2 should contain a name to associate with the
	address. Any other columns are ignored.
</p>