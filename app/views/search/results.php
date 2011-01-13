	
<div id="search_results">


<a href="#" onclick="$('#advanced-search').toggle('slow');">advanced search</a>
<div id="advanced-search" style="display: none">
<form method="post">
	<fieldset>
		<legend>Advanced Search</legend>
		<label for="q">Find</label>	
		<input name="q" value="<?= $query_string ?>" size="50"/>
		<label for="type">Type</label>	
		<select name="type">
			<option value="all">all</option>
			<option value="events">events</option>
			<option value="articles">articles</option>
			<option value="books">books</option>
		</select>
		<input type="submit" name="search" value="Go" />
	</fieldset>
</form>
</div>

<table class="search-results">
	<caption>Query: <em>&quot;<?= $query_string ?>&quot;</em> returned <?= $results['count']?> results</caption>
	<tr>
	  <th>Type</th>
		<th>Title</th>
	  <th>Date</th>
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
					<?php } elseif( $event->type == 'book') { ?>
						<a href="/product/view/<?=$event->id?>"><?=$event->title?></a>
					<?php } else { ?>
						<?=$event->title?>
					<?php } ?>
				</td>				
				<td class="small">
					<?php if( $event->dt_start !== NULL ) { ?>
						<?= fmt_date( $event->dt_start )?>
					<?php } ?>
				</td>
			</tr>
  <?php }
	} ?>
	
</table>
</div>

<?= $pagination ?>
