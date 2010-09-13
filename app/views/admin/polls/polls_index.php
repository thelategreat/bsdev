<div style="float: right">

</div>

<h3><a class="small" href="/admin/polls/add/" title="Add Poll"><img src="/img/admin/text_list_bullets.png" /></a> Polls</h3>

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
		<td></td>
	</tr>
	<?php $i++; } ?>
</table>