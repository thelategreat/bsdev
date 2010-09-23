	
<div id="search_results">

<h3> Search Results</h3>	
	
<p>Query: <em>&quot;<?= $results['query'] ?>&quot;</em> returned <?= $results['count']?> results</p>
	
<table>
	<tr>
	  <th>Description</li>
	  <th>Time</li>
		<th>Audience</li>
		<th>Date</li>
	</tr>
  <?php if( $results['count'] > 0 ) { 
		foreach( $results['results']->result() as $event ) { ?>
			<tr>
				<td><a href="/events/details/<?=$event->id?>"><?=$event->title?></a></td>
				<td><?=strip_tags((strlen($event->body) > 100 ? substr($event->body,0,100) . "..." : $event->body))?></em></td>
				<td><?=date('g:ia',strtotime($event->dt_start))?></td>
				<td><?=$event->audience?></td>
				<td><?=date('D M n, Y',strtotime($event->dt_start))?></td>
			</tr>
  <?php }
	} ?>
	
</table>
</div>