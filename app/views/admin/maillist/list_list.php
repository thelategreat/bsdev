<div style="float: right; font-size: 0.9em">
<a href="/admin/maillist/listadd"><img src="/img/add.png" /> New List</a>
</div>

<h3><img src="/img/text_list_bullets.png" /> Mailing Lists</h3>
<table>
<tr>
  <th>name</th>
  <th>description</th>
  <th>visible</th>
  <th>enabled</th>
  <th>open</th>
</tr>
<?php $i = 0;
  foreach( $lists as $row ) { ?>
  <tr <?php if($i % 2 == 0) { echo 'class="odd"'; } ?> >
    <td><a href="/admin/maillist/listedit/<?= $row->id ?>"><?= $row->name ?></td></a>
    <td><?= $row->descrip ?></td>
    <td><?= $row->is_visible  ? '<img src="/img/tick.png" />' : '<img src="/img/cross.png" />'?></td>
    <td><?= $row->is_enabled  ? '<img src="/img/tick.png" />' : '<img src="/img/cross.png" />'?></td>
    <td><?= $row->is_open  ? '<img src="/img/tick.png" />' : '<img src="/img/cross.png" />'?></td>
  </tr>
<?php $i++; } ?>
</table>