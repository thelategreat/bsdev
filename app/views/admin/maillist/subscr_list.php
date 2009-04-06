<div style="float: right">
<a href="/admin/maillist/subscradd"><img src="/img/user_add.png" /> New Subscriber</a>
</div>

<h3><img src="/img/group.png" /> Mail List Subscribers</h3>
<table>
<tr>
  <th>email</th>
  <th>fullname</th>
  <th>active</th>
</tr>
<?php $i = 0;
  foreach( $users as $row ) { ?>
  <tr <?php if($i % 2 == 0) { echo 'class="odd"'; } ?> >
    <td><a href="/admin/maillist/subscredit/<?= $row->id ?>"><?= $row->email ?></td></a>
    <td><?= $row->fullname ?></td>
    <td><?= $row->status  == "active" ? '<img src="/img/tick.png" />' : '<img src="/img/cross.png" />'?></td>
  </tr>
<?php $i++; } ?>
</table>