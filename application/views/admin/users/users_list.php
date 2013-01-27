
<div style="float: right">
	<form method="post">
		<input id="query" style="font-size: 0.8em;" name="q" value="<?=$query?>" size="15" onblur="if(this.value=='') this.value='search...';" onfocus="if(this.value == 'search...') this.value='';"/>
	</form>
</div>

<h3><a class="small" href="/admin/users/add" title="New User"><img src="/img/admin/user_add.png" /></a> Site Users</h3>
<table>
<tr>
  <th>username</th>
  <th>name</th>
  <th>role</th>
  <th>email</th>
  <th>active</th>
  <th>last login</th>
</tr>
<?php $i = 0;
  foreach( $users as $row ) { ?>
  <tr <?php if($i % 2 == 0) { echo 'class="odd"'; } ?> >
    <td><a href="/admin/users/edit/<?= $row->id ?>"><?= $row->username ?></td></a>
    <td><?= $row->firstname ?> <?= $row->lastname ?></td>
    <td><?= $row->role ?></td>
    <td><?= $row->email ?></td>
    <td><?= $row->active ? '<img src="/img/admin/tick.png" />' : '<img src="/img/admin/cross.png" />'?></td>
    <td><?= $row->last_login[0] == '0' ? 'never' : date("M j, y @ g:ia",strtotime($row->last_login))?></td>
  </tr>
<?php $i++; } ?>
</table>