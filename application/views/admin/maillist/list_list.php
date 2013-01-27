<div style="float: right; font-size: 0.9em">
</div>

<h3><a href="/admin/maillist/listadd" title="New List"><img src="/img/admin/text_list_bullets.png" /></a> Mailing Lists</h3>
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
    <td><?= $row->is_visible  ? '<img src="/img/admin/tick.png" />' : '<img src="/img/admin/cross.png" />'?></td>
    <td><?= $row->is_enabled  ? '<img src="/img/admin/tick.png" />' : '<img src="/img/admin/cross.png" />'?></td>
    <td><?= $row->is_open  ? '<img src="/img/admin/tick.png" />' : '<img src="/img/admin/cross.png" />'?></td>
  </tr>
<?php $i++; } ?>
</table>