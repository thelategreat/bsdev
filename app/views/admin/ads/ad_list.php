<div style="float: right">
	<form method="post">
		<input id="query" style="font-size: 0.8em;" name="q" value="<?=$query?>" size="15" onblur="if(this.value=='') this.value='search...';" onfocus="if(this.value == 'search...') this.value='';"/>
	</form>
</div>

<h3><a class="small" href="/admin/ads/add"><img src="/img/admin/newspaper_add.png" title="Add Advert"/></a> Ads</h3>

<table>
<tr>
  <th width="50%">Title/Client</th>
  <th>Start Date</th>
  <th>End Date</th>
  <th>Url</th>
  <th>Clicks</th>
  <th>Owner</th>
</tr>
<?php
 	$cnt = 0;
	foreach( $ads->result() as $ad ) { ?>
	<tr <?= ($cnt % 2) != 0 ? 'class="odd"' : ''?> >
	  <td><a href="/admin/ads/edit/<?= $ad->id ?>"><?= $ad->title ?></a></td>
	  <td><small><?= date('Y-m-d',strtotime($ad->start_date)) ?></small></td>
		<td><small><?= date('Y-m-d',strtotime($ad->end_date)) ?></small></td>
	  <td><small><?= $ad->url ?></small></td>
	  <td><small><?= $ad->clicks ?></small></td>
	  <td><small><?= $ad->owner ?></small></td>
</tr>
<?php $cnt++; } ?>
</table>
<table>
	<tr>
		<td><?=$prev_page?></td>
		<td align="right"><?=$next_page?></td>
	</tr>
</table>