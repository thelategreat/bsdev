<div style="float: right">
<a href="/admin/maillist/msgadd"><img src="/img/newspaper_add.png" /> New Message</a>
</div>

<h3><img src="/img/newspaper.png" /> Newsletters</h3>
<table>
<tr>
  <th>subject</th>
  <th>from</th>
  <th>date</th>
</tr>
<?php $i = 0;
  foreach( $msgs as $row ) { ?>
  <tr <?php if($i % 2 == 0) { echo 'class="odd"'; } ?> >
    <td><a href="/admin/maillist/msgedit/<?= $row->id ?>"><?= $row->subject ?></td></a>
    <td><?= $row->from ?></td>
    <td><?= $row->send_on ?></td>
  </tr>
<?php $i++; } ?>
</table>