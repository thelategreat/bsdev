	
<div id="search_results">

<h3> Search Results</h3>	
	
	
<table class="search-results">
	<caption>Query: <em>&quot;<?= $query_string ?>&quot;</em> returned <?= $results['count']?> results</caption>
	<tr>
	  <th>Event</th>
		<th>Time</th>
	  <th>Date</th>
	  <th>Category</th>
		<th>Audience</th>
	</tr>
  <?php if( $results['count'] > 0 ) { 
		foreach( $results['results']->result() as $event ) { ?>
			<tr>
				<td><a href="/events/details/<?=$event->id?>"><?=$event->title?></a></td>
				<td><?=date('g:ia',strtotime($event->dt_start))?></td>
				<td><?=date('D M j, Y',strtotime($event->dt_start))?></td>
				<td>
					<?php if( file_exists('img/icons/black/' . $event->category_name . '.png')) { ?>
						<img class="icon" src="'/img/icons/black/<?=$event->category_name?>.png'" title="<?=$event->category_name?>" />
					<?php } else { ?>
						<?=$event->category_name?>
					<?php } ?>
				</td>
				<td><?=$event->audience_name?></td>
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