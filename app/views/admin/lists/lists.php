<h3><a class="small" href="/admin/lists/add/" title="Add List"><img src="/img/admin/text_list_bullets.png" /></a> Lists</h3>

<table>
<tr>
  <th>List</th>
  <th>Owner</th>
</tr>
<?php foreach( $lists->result() as $list ) { ?>
<tr>
  <td><a href='/admin/lists/edit/<?=$list->id?>'><?= $list->name ?></a></td>
  <td><?= $list->creator ?></td>
</tr>
<?php } ?>


