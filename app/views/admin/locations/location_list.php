<div style="float: right">
</div>

<h3><a class="small" href="/admin/locations/add" title="Add Location"><img src="/img/admin/house_go.png" /></a> Locations</h3>
<table>
<tr>
  <th>name</th>
  <th>address</th>
  <th>city</th>
  <th>postal</th>
</tr>
<?php $i = 0;
  foreach( $data as $row ) { ?>
  <tr <?php if($i % 2 == 0) { echo 'class="odd"'; } ?> >
    <td><a href="/admin/locations/edit/<?= $row->id ?>"><?= $row->name ?></td></a>
    <td><?= $row->address ?></td>
    <td><?= $row->city ?></td>
    <td><?= $row->postal ?></td>
  </tr>
<?php $i++; } ?>
</table>
