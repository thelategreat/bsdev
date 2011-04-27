<div style="float: right">
	<form method="post" action='/admin/orders/index'>
		<input id="query" style="font-size: 0.8em;" name="q" value="<?= strlen($query) ? $query : 'search...'?>" size="20" onblur="if(this.value=='') this.value='search...';" onfocus="if(this.value == 'search...') this.value='';"/>
	</form>
</div>

<h3><img src="/img/admin/newspaper_add.png" title="Add Article"/>Orders</h3>

<table class="article-list">
  <thead>
    <tr>
      <th>Order#</th>
      <th>Date</th>
      <th>Customer</th>
      <th>Amount</th>
      <th>Status</th>
    </tr>
  </thead>
<?php
 	$cnt = 0;
	foreach( $orders->result() as $order ) { ?>
	<tr <?= ($cnt % 2) != 0 ? 'class="odd"' : ''?> >
	  <td><a href="/admin/orders/edit/<?= $order->id ?>"><?= $order->order_no ?></a></td>
	  <td><small><?= date('Y-m-d',strtotime($order->order_dt)) ?></small></td>
	  <td><small><?  ?></small></td>
	  <td><small><?  ?></small></td>
	  <td><small><?= $order->state ?></small></td>
</tr>
<?php $cnt++; } ?>
</table>

<?= $pager ?>

