<link rel="stylesheet" href="<? echo base_url('/js/fancybox/jquery.fancybox.css?v=2.1.5');?>" type="text/css" media="screen" />
<script type="text/javascript" src="<? echo base_url('/js/fancybox/jquery.fancybox.pack.js?v=2.1.5');?>"></script>

<script type='text/javascript'>
	$(document).ready(function() {
		$('#lists').dataTable({
      'sDom': '<"H"rplf>t<"F"i>'
    });
    $(".boxframe").fancybox({
        'width' : '75%',
        'height' : '75%',
        'autoScale' : false,
        'transitionIn' : 'none',
        'transitionOut' : 'none',
        'type' : 'iframe'
     });
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
		      <th>Preview</th>
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