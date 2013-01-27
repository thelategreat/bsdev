<div style="float: right">
	<form method="post" action='/admin/vendors/index'>
		<input id="query" style="font-size: 0.8em;" name="q" value="<?= strlen($query) ? $query : 'search...'?>" size="20" onblur="if(this.value=='') this.value='search...';" onfocus="if(this.value == 'search...') this.value='';"/>
	</form>
</div>

<h3><a href="/admin/vendors/add"><img src="/img/admin/newspaper_add.png" title="Add Vendor"/></a> Vendors</h3>

<table class="article-list">
  <thead>
    <tr>
      <th width="40%">Vendor</th>
      <th>Type</th>
      <th>Last Trans</th>
      <th>Balance</th>
      <th>YTD</th>
      <th>Status</th>
    </tr>
  </thead>
<?php
 	$cnt = 0;
	foreach( $vendors->result() as $vendor ) { ?>
	<tr <?= ($cnt % 2) != 0 ? 'class="odd"' : ''?> >
	  <td><a href="/admin/vendors/edit/<?= $vendor->id ?>"><?= $vendor->name ?></a></td>
	  <td><small><?= $vendor->vtype ?></small></td>
	  <td><small><?  ?></small></td>
	  <td><small><?  ?></small></td>
	  <td><small><? ?></small></td>
	  <td><small><? ?></small></td>
</tr>
<?php $cnt++; } ?>
</table>

<?= $pager ?>

