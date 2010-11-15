<?= $tabs ?>

<table style="width: 60%;">
	<tr>
		<th>date</th>
		<th>user</th>
		<th>issue</th>
		<th>activity</th>
	</tr>
<?php $count = 0;
	foreach( $activity->result() as $row ): ?>
	<tr <?= ($count % 2) != 0 ? 'class="odd"' : ''?>>
		<td style="width: 30%"><?=$row->activity_date ?></td>
		<td><?=$row->user ?></td>
		<td><a href="/admin/bugs/edit/<?=$row->bug_id?>"><?=$row->bug_id?></a></td>
		<td><?=$row->activity ?></td>
	</tr>
<?php $count++;
	endforeach; ?>
</table>