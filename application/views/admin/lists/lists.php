<script type='text/javascript'>
	$(document).ready(function() {
		$('#lists').dataTable();
	});
</script>

<div class=container>
	<header>Lists</header>

	<nav>
		<a href="/admin/lists/add">
			<button id='btn_add'>
				<i class="icon-plus icon"></i> Add List
			</button>
		</a>
	</nav>
	<br>

	<section>
		<table id='lists' class="dataTable">
		  <thead>
		    <tr>
		      <th width="85%">List Name</th>
		      <th>Owner</th>
		    </tr>
		  </thead>
		<?php
		 	$cnt = 0;
			foreach( $lists->result() as $it) { ?>
			<tr <?= ($cnt % 2) != 0 ? 'class="odd"' : ''?> >
			  <td><a href="/admin/lists/edit/<?= $it->id ?>"><?= str_max_len($it->name, 40); ?></a></td>
		    <td>
		    	<?=$it->creator; ?>
		    </td>
		</tr>
		<?php $cnt++; } ?>
		</table>
	</section>
</div>