<div style="float: right; font-size: 0.9em">
<a href="/admin/maillist/subscrim"><img src="/img/admin/database_add.png" /> Import</a>
<a href="/admin/maillist/subscrex" onclick="return confirm('Export list?');"><img src="/img/admin/database_save.png" /> Export</a>
<form style="display: inline" method="post"><input name="query" value="<?=$query?>" size="12"/></form>
</div>

<h3><a href="/admin/maillist/subscradd" title="New Subscriber"><img src="/img/admin/user_add.png" /></a> Mail List Subscribers</h3>
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
    <td><?= $row->status  == "active" ? '<img src="/img/admin/tick.png" />' : '<img src="/img/admin/cross.png" />'?></td>
  </tr>
<?php $i++; } ?>
</table>