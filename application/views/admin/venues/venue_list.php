<script type='text/javascript'>
	$(document).ready(function() {
		$('#venues').dataTable();
	});
</script>


<div class=container>
	<header>Venues</header>

	<br>

	<nav>
		<a href="/admin/venues/add/">
		<button id='btn_add'>
	    	<i class="icon-plus icon-2x"></i> Add Venue 
		</button>
		</a>
	</nav>
	<br>

	<table id='venues' class="dataTable">
	  <thead>
	    <tr>
	      <th width="35%">Name</th>
	      <th>Location</th>
	    </tr>
	  </thead>
	  <tbody>
	  	<?php 
	  	 foreach( $data as $row ) { ?>
		  <tr>
		    <td><a href="/admin/venues/edit/<?= $row->id ?>" title="Edit Venue"><?= $row->name ?></td></a>
		    <td><a href="/admin/locations/edit/<?= $row->locations_id ?>" title="Edit Location"><?= $row->location_name ?></a></td>
		  </tr>
		 <?php } ?>
	  </tbody>
	 </table>

 </div>
