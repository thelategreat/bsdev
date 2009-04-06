
<div style="float: right">
<a href="/admin/users/add"><img src="/img/user_add.png" /> New User</a>
</div>

<h3><img src="/img/group.png" /> Site Users</h3>
<table>
<tr>
  <th>username</th>
  <th>role</th>
  <th>email</th>
  <th>active</th>
  <th>last login</th>
</tr>
<?php $i = 0;
  foreach( $users as $row ) { ?>
  <tr <?php if($i % 2 == 0) { echo 'class="odd"'; } ?> >
    <td><a href="/admin/users/edit/<?= $row->id ?>"><?= $row->username ?></td></a>
    <td><?= $row->role ?></td>
    <td><?= $row->email ?></td>
    <td><?= $row->active ? '<img src="/img/tick.png" />' : '<img src="/img/cross.png" />'?></td>
    <td><?= $row->last_login[0] == '0' ? 'never' : date("M j, y @ g:ia",strtotime($row->last_login))?></td>
  </tr>
<?php $i++; } ?>
</table>