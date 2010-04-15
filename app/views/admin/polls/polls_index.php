<div style="float: right">
	<a class="small" href="/admin/polls/add/"><img src="/img/picture_add.png" /> Add Poll</a>
</div>

<h3>Polls</h3>

<table>
	<tr>
		<th>title</th>
		<th>date</th>
		<th/>
	</tr>
	<?php $i = 0;
	foreach( $polls->result() as $poll ) { ?>
	<tr <?= ($i % 2) ? 'class="odd"' : ''?>>
		<td><a href="/admin/polls/edit/<?=$poll->id?>"><?=$poll->question?></a></td>
		<td><?=$poll->poll_date?></td>
		<td><a href="/admin/polls/rm/<?=$poll->id?>" title="delete poll" onclick="return confirm('Really delete?');"><img src="/img/cross.png"/></a></td>
	</tr>
	<?php $i++; } ?>
</table>