<div style="float: right">
	<form method="post" action='/admin/ads/index'>
		<input id="query" style="font-size: 0.8em;" name="q" value="<?= strlen($query) > 0 ? $query : 'search...' ?>" size="15" onblur="if(this.value=='') this.value='search...';" onfocus="if(this.value == 'search...') this.value='';"/>
	</form>
</div>

<h3><a class="small" href="/admin/ads/add"><img src="/img/admin/newspaper_add.png" title="Add Advert"/></a> Ads</h3>

<table class='ads-list'>
  <thead>
    <tr>
      <th></th>
      <th width="30%">Title/Client</th>
      <th>Start/End</th>
      <th>Url</th>
      <th style="text-align: center;">Clicks</th>
      <th>Owner</th>
    </tr>
  </thead>
  <tbody>
<?php
 	$cnt = 0;
	foreach( $ads->result() as $ad ) { ?>
	<tr <?= ($cnt % 2) != 0 ? 'class="odd"' : ''?> style="height: 60px">
		<?php if( file_exists("media/$ad->uuid")) { ?>
			<td><img src="/media/<?=$ad->uuid?>" width="60px" /></td>
		<?php } else { ?>
			<td class="error">missing</td>
		<?php } ?>
	  <td><a href="/admin/ads/edit/<?= $ad->id ?>"><?= $ad->title ?></a></td>
	  <td <?= strtotime($ad->end_date) <= time() ? "class='expired'" : ""?>>
			<small><?= date('Y-m-d',strtotime($ad->start_date)) ?>/<?= date('Y-m-d',strtotime($ad->end_date)) ?></small>
		</td>
	  <td style="overflow: hidden; white-space: nowrap;"><small><?= str_max_len($ad->url, 30) ?></small></td>
	  <td align="center"><small><?= $ad->clicks ?></small></td>
	  <td><small><?= $ad->owner ?></small></td>
  </tr>
<?php $cnt++; } ?>
  </tbody>
</table>

<?= $pager ?>