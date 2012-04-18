<div style="float: right">
</div>

<h3><a class="small" href="/admin/venues/add" title="Add Venue"><img src="/img/admin/house_go.png" /></a> Venues</h3>
<table>
<tr>
  <th>name</th>
  <th>location</th>
</tr>
<?php $i = 0;
  foreach( $data as $row ) { ?>
  <tr <?php if($i % 2 == 0) { echo 'class="odd"'; } ?> >
    <td><a href="/admin/venues/edit/<?= $row->id ?>"><?= $row->venue ?></td></a>
    <td><?= $row->location ?></td>
  </tr>
<?php $i++; } ?>
</table>
