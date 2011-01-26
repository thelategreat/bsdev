<div style="float: right">
	<form method="post">
		<input id="query" style="font-size: 0.8em;" name="q" value="<?=$query?>" size="15" onblur="if(this.value=='') this.value='search...';" onfocus="if(this.value == 'search...') this.value='';"/>
	</form>
</div>

<h3><a class="small" href="/admin/articles/add"><img src="/img/admin/newspaper_add.png" title="Add Article"/></a> Articles</h3>

<table>
  <thead>
    <tr>
      <th width="35%">Title</th>
      <th>Owner</th>
      <th>Author</th>
      <th>Category</th>
      <th>Group</th>
      <th>Publish</th>
      <th>Status</th>
    </tr>
  </thead>
<?php
 	$cnt = 0;
	foreach( $articles->result() as $article ) { ?>
	<tr <?= ($cnt % 2) != 0 ? 'class="odd"' : ''?> >
	  <td><a href="/admin/articles/edit/<?= $article->id ?>"><?= $article->title ?></a></td>
	  <td><small><?= $article->owner ?></small></td>
	  <td><small><?= $article->author ?></small></td>
	  <td><small><?= $article->category ?></small></td>
	  <td><small><?= $article->group_name ?></small></td>
	  <td><small><?= date('Y-m-d',strtotime($article->publish_on)) ?></small></td>
		<td><small><?= $article->status ?></small></td>
</tr>
<?php $cnt++; } ?>
</table>

<table class="pager">
  <tr>
    <td></td>
    <td align="right"><?=$prev_page?> | <?=$next_page?></td>
  </tr>
</table>
