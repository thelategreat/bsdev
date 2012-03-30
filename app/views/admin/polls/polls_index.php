<div style="float: right">

</div>

<h3><a class="small" href="/admin/polls/add/" title="Add Poll"><img src="/img/admin/text_list_bullets.png" /></a> Polls</h3>

<table>
	<tr>
		<th>title</th>
		<th>start date</th>
		<th>end date</th>
		<th/>
	</tr>
	<?php $i = 0;
	foreach( $polls->result() as $poll ) { ?>
	<tr <?= ($i % 2) ? 'class="odd"' : ''?>>
		<td><a href="/admin/polls/edit/<?=$poll->id?>"><?=$poll->question?></a></td>
    <td><?=date("M j, Y",strtotime($poll->poll_date))?></td>
		<td><?=date("M j, Y",strtotime($poll->poll_end_date))?></td>
    <td><a href="/admin/polls/view/<?=$poll->id?>">view</a></td>
	</tr>
	<?php $i++; } ?>
</table>
