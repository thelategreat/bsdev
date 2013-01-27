<div style="float: right">
  <h2>Database</h2>
</div>
  
<?= $tabs ?>
        
Tables: <select name="table">
<?php foreach( $tables as $table ): ?>
	<option><?=$table?></option>
<?php endforeach; ?>
</select>