<div style="float: right; font-size: 0.9em">

</div>

<h3><a href="/admin/maillist/msgadd" title="New Newsletter"><img src="/img/admin/newspaper_add.png" /></a> Newsletters</h3>
<table>
<tr>
  <th>subject</th>
  <th>from</th>
  <th>date</th>
  <th>status</th>
	<th>list</th>
</tr>
<?php $i = 0;
  foreach( $msgs as $row ) { ?>
  <tr <?php if($i % 2 == 0) { echo 'class="odd"'; } ?> >
    <td><a href="/admin/maillist/msgedit/<?= $row->id ?>"><?= $row->subject ?></td></a>
    <td><?= $row->from ?></td>
    <td><?= $row->send_on == '0000-00-00 00:00:00' ? "not scheduled" : $row->send_on ?></td>
		<td>
		<?php 
		if( $row->status == 0 or $row->status == '' ) {
			echo 'pending';
		} 
		else if( $row->status == 100 ) {
			echo 'complete';
		}
		else {
			echo "$row->status%";
		}
		?>		
		</td>
		<td><?=$row->name?></td>
  </tr>
<?php $i++; } ?>
</table>