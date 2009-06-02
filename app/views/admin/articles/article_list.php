<div style="float: right">
<a href="/admin/articles/add"><img src="/img/add.png" /> Add Article</a>
</div>
<h3>Articles</h3>

<table>
<tr>
  <th width="50%">Title</th>
  <th>Author</th>
  <th>Category</th>
  <th>Last update</th>
  <th>Status</th>
  <th>Comments</th>
</tr>
<?php
 	$cnt = 0;
	foreach( $articles->result() as $article ) { ?>
	<tr <?= $cnt != 0 ? 'class="odd"' : ''?> >
	  <td><a href="/admin/articles/edit/<?= $article->id ?>"><?= $article->title ?></a></td>
	  <td><small><?= $article->author ?></small></td>
	  <td><small><?= $article->category ?></small></td>
	  <td><small><?= $article->updated_on ?></small></td>
		<td><small><?= $article->status ?></small></td>
		<td>-</td>
</tr>
<?php $cnt++; } ?>
</table>