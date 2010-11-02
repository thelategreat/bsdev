	
<div id="search_results">

<h3> Search Results</h3>	
	
	
<table class="search-results">
	<caption>Query: <em>&quot;<?= $query_string ?>&quot;</em> returned <?= $results['count']?> results</caption>
	<tr>
	  <th>Type</th>
		<th>Title</th>
	  <th>Date</th>
	  <th>Time</th>
	</tr>
  <?php if( $results['count'] > 0 ) { 
		foreach( $results['results']->result() as $event ) { ?>
			<tr>
				<td><?= $event->type ?></td>
				<td>
					<?php if( $event->type == 'event') { ?>
						<a href="/events/details/<?=$event->id?>"><?=$event->title?></a>
					<?php } elseif( $event->type == 'article') { ?>
						<a href="/article/view/<?=$event->id?>"><?=$event->title?></a>
					<?php } else { ?>
						<?=$event->title?>
					<?php } ?>
				</td>
				<td><?=date('g:ia',strtotime($event->created_on))?></td>
				<td><?=date('D M j, Y',strtotime($event->created_on))?></td>
			</tr>
  <?php }
	} ?>
	
</table>
</div>

<div class="pager">
	<table>
		<tr>
			<td>
				<?php if( $page > 1 ) { ?>
					<a href="/search/results/<?=$page - 1?>/<?= urlencode($query_string)?>" >⇐ prev</a>
				<?php } ?>
			</td>
			<td align="right">
				<?php if( $results['results']->num_rows() == $page_size ) { ?>
					<a href="/search/results/<?=$page + 1?>/<?= urlencode($query_string)?>">next ⇒</a>
				<?php } ?>
			</td>
		</tr>
	</table>
</div>