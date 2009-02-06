<h2>Films</h2>
<a href="/admin/cinema/add">Add</a>
<table class="general">
	<tr>
		<th>Title</th>
		<th>Director</th>
		<th>Year</th>
		<th>Rating</th>
	</tr>
<?php
 	$i = 0;
	foreach( $films->result() as $row): ?>
	<tr <?= ($i % 2) ? 'class="odd"' : ''?>>
		<td><a href="/admin/cinema/edit/<?=$row->id?>"><?=$row->title?></a></td>
		<td><?=$row->director?></td>
		<td><?=$row->year?></td>
		<td><?=$row->rating?></td>
	</tr>
<?php 
	$i++;
	endforeach; ?>
</table>