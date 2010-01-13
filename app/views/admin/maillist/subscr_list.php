<div style="float: right; font-size: 0.9em">
<a href="/admin/maillist/subscradd"><img src="/img/user_add.png" /> New Subscriber</a>
<a href="/admin/maillist/subscrim"><img src="/img/database_add.png" /> Import</a>
<a href="/admin/maillist/subscrex" onclick="return confirm('Export list?');"><img src="/img/database_save.png" /> Export</a>
<form style="display: inline" method="post"><input name="query" value="<?=$query?>" size="12"/></form>
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