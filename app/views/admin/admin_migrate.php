<h3>Migration</h3>

<p class="warn">
	Running the default migration could scramble this database. Make
	sure you have a clean backup before running this.
<p>

<form action="/admin/migrate/run" method="post">
<label for"key">DB Config</label>
<select name="key">
	<?php foreach( $keys as $key ): ?>
		<option><?=$key?></option>
	<?php endforeach; ?>
</select>
<input type="submit" name="run" value="Run" />
</form>
