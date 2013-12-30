<script type='text/javascript'>
$(document).ready(function() {
    $('#datatable').dataTable({
      'sDom': '<"H"rplf>t<"F"i>'
    });
  });
</script>
<div class=container>
  <header>Locations</header>
<br>

<a href="/admin/locations/add">
  <button>
    <i class="icon-plus icon"></i> Add Location 
  </button>
</a>
<br>

<table id='datatable'>
<thead>
  <th>Name</th>
  <th>Address</th>
  <th>City</th>
  <th>Postal</th>
</thead>
<tbody>
<? foreach( $data as $row ) { ?>
  <tr> 
    <td><a href="/admin/locations/edit/<?= $row->id ?>"><?= $row->name ?></td></a>
    <td><?= $row->address ?></td>
    <td><?= $row->city ?></td>
    <td><?= $row->postal ?></td>
  </tr>
<? } ?>
</tbody>
</table>
</div>