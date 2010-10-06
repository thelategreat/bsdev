	
<div id="search_results">

<h3> Search Results</h3>	
	
<p>Query: <em>&quot;<?= $query_string ?>&quot;</em> returned <?= $results['count']?> results</p>
	
<table>
	<tr>
	  <th>Category</th>
	  <th>Event</th>
		<th>Time</th>
	  <th>Date</th>
		<th>Audience</th>
	</tr>
  <?php if( $results['count'] > 0 ) { 
		foreach( $results['results']->result() as $event ) { ?>
			<tr>
				<td>
					<?php if( file_exists('img/icons/black/' . $event->category . '.png')) { ?>
						<img class="icon" src="'/img/icons/black/<?=$event->category?>.png'" title="<?=$event->category?>" />
					<?php } else { ?>
						<?=$event->category?>
					<?php } ?>
				</td>
				<td><a href="/events/details/<?=$event->id?>"><?=$event->title?></a></td>
				<td><?=date('g:ia',strtotime($event->dt_start))?></td>
				<td><?=date('D M n, Y',strtotime($event->dt_start))?></td>
				<td><?=$event->audience?></td>
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