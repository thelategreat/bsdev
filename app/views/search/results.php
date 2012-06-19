
<div class="row">
  <div class="column grid_3">
  foo
  </div>
  <div class="column grid_9">

<div id="search_results">

<!--
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
-->

<table class="search-results">
	<caption>Query: <em>&quot;<?= $query_string ?>&quot;</em> returned <?= $results['count']?> results</caption>
	<tr>
	  <th>Type</th>
    <th>Title</th>
    <th>Author</th>
	  <th>Date</th>
	</tr>
  <?php if( $results['count'] > 0 ) {
    $count = 0; 
		foreach( $results['results']->result() as $item ) { ?>
      <tr class="<?= $count % 2 ? '' : 'odd'?>">
				<td><?= $item->type ?></td>
				<td>
					<?php if( $item->type == 'event') { ?>
						<a href="/events/details/<?=$item->id?>"><?=$item->title?></a>
					<?php } elseif( $item->type == 'article') { ?>
						<a href="/article/view/<?=$item->id?>"><?=$item->title?></a>
					<?php } elseif( $item->type == 'book') { ?>
						<a href="/product/view/<?=$item->id?>"><?=$item->title?></a>
					<?php } else { ?>
						<?=$item->title?>
					<?php } ?>
        </td>	
        <td>
          <?= $item->author ?>
        </td>
				<td class="small">
					<?php if( $item->dt_start !== NULL ) { ?>
						<?= fmt_date( $item->dt_start )?>
					<?php } ?>
				</td>
			</tr>
  <?php $count++; }
	} ?>
	
</table>
</div>

<?= $pagination ?>

  </div>
</div>

