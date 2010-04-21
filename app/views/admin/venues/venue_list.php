<div style="float: right">
</div>

<h3><a class="small" href="/admin/venues/add" title="Add Venue"><img src="/img/house_go.png" /></a> Venues</h3>
<table>
<tr>
  <th>name</th>
  <th>address</th>
  <th>city</th>
  <th>postal</th>
</tr>
<?php $i = 0;
  foreach( $venues as $row ) { ?>
  <tr <?php if($i % 2 == 0) { echo 'class="odd"'; } ?> >
    <td><a href="/admin/venues/edit/<?= $row->id ?>"><?= $row->name ?></td></a>
    <td><?= $row->address ?></td>
    <td><?= $row->city ?></td>
    <td><?= $row->postal ?></td>
  </tr>
<?php $i++; } ?>
</table>