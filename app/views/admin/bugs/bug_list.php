<div style="float: right">
	<form method="post">
		<input id="query" style="font-size: 0.8em;" name="q" value="<?=$query?>" size="15" onblur="if(this.value=='') this.value='search...';" onfocus="if(this.value == 'search...') this.value='';"/>
	</form>
</div>

<h3><a class="small" href="/admin/bugs/add"><img src="/img/admin/bug_add.png" title="Add an Issue"/></a> Issues</h3>

<table>
<tr>
  <th width="50%">Summary</th>
  <th>Date</th>
  <th>Submitter</th>
  <th>Assigned</th>
  <th>Status</th>
  <th>Type</th>
</tr>
<?php
 	$cnt = 0;
	foreach( $bugs->result() as $bug ) { ?>
	<tr <?= ($cnt % 2) != 0 ? 'class="odd"' : ''?> >
	  <td><a href="/admin/bugs/edit/<?= $bug->id ?>"><?= $bug->summary ?></a></td>
	  <td><small><?= date('Y-m-d',strtotime($bug->created_on)) ?></small></td>
	  <td><small><?= $bug->submitted_by ?></small></td>
	  <td><small><?= $bug->assigned_to ?></small></td>
	  <td><small><?= $bug->status ?></small></td>
	  <td><small><?= $bug->type ?></small></td>
</tr>
<?php $cnt++; } ?>
</table>
<table>
	<tr>
		<td><?=$prev_page?></td>
		<td align="right"><?=$next_page?></td>
	</tr>
</table>

<!-- pagination -->
<table>
	<tr>
		<td><?=$prev_page?></td>
		<td align="right"><?=$next_page?></td>
	</tr>
</table>