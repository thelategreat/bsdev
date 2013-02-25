<script type='text/javascript'>
	$(document).ready(function() {
		$('#articles').dataTable();
	});
</script>

<style>
table.pager {
  display: inline;
}
table.toolbar {
  display: inline;
}
</style>

<h3><a class="small" href="/admin/articles/add"><img src="/img/admin/newspaper_add.png" title="Add Essay"/></a> Essays</h3>

<table id='articles' class="dataTable">
  <thead>
    <tr>
      <th width="35%">Title</th>
      <th>User</th>
      <th>Category</th>
      <th>Section</th>
      <th>Date</th>
      <th>Status</th>
    </tr>
  </thead>
<?php
 	$cnt = 0;
	foreach( $articles->result() as $article ) { ?>
	<tr <?= ($cnt % 2) != 0 ? 'class="odd"' : ''?> >
	  <td><a href="/admin/articles/edit/<?= $article->id ?>"><?= str_max_len($article->title, 40); ?></a></td>
    <td>
      <small><?= $article->firstname ?> <?= $article->lastname ?> 
        <?php if(strlen($article->nickname)){?> [<i><?= $article->nickname ?></i>]<?php }?>
      </small>
    </td>
	  <td><small><?= $article->category ?></small></td>
	  <td><small><?= $article->group_name ?></small></td>
	  <td><small><?= date('Y-m-d',strtotime($article->publish_on)) ?></small></td>
		<td class="<?=strtolower($article->status)?>"><small><?= $article->status ?></small></td>
</tr>
<?php $cnt++; } ?>
</table>
