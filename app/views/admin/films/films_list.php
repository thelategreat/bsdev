
<div style="float: right">
	<a href="/admin/films/add/"><img src="/img/picture_add.png" /> Add Film</a>
</div>

<h3><img src="/img/picture.png" /> Films</h3>

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
		<td><a href="/admin/films/edit/<?=$row->id?>"><?=$row->title?></a></td>
		<td><?=$row->director?></td>
		<td><?=$row->year?></td>
		<td><?=$row->rating?></td>
	</tr>
<?php 
	$i++;
	endforeach; ?>
</table>