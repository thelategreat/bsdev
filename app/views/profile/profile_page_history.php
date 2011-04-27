<div style="float: right; background-color: #000; color: #fff; padding: 10px; border-radius: 5px;">
	<a style="color: #fff;" href="/profile/logout">logout</a>
</div>

<h2>Your Bookshelf</h2>

<p>
Hi <?=$firstname?>! This is your profile page.
</p>

<hr/>
<?=$error?>

<div class="tabs">
<ul>
 <li><a href="/profile/page/">Identity</a></li>
 <li><a href="/profile/page/social">Social</a></li>
 <li><a href="/profile/page/subscribe">Subscriptions</a></li>
 <li><a href="/profile/page/purchase">Purchasing</a></li>
 <li><a class="selected" href="/profile/page/history">History</a></li>
</ul>
</div>

<table class="generic">
  <tr>
    <th>Order#</th>
    <th>Date</th>
    <th>Status</th>
  </tr>
<?php foreach( $orders->result() as $row ): ?>
  <tr>
    <td><?= $row->order_no ?></td>
    <td><?= date('Y-m-d',strtotime($row->order_dt)) ?></td>
    <td><?= $row->state ?></td>
  </tr>
<?php endforeach; ?>
</table>
