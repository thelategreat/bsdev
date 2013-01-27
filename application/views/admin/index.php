<h3>Dashboard</h3>


<dl class="table-display">
<?php foreach( $counts as $key => $value ): ?>
	<dt><?= $key ?></dt>
	<dd><?= $value ?></dd>
<?php endforeach; ?>
</dl>

<p style="clear: both"/>
<!--
<table>
	<tr>
		<th style="background-color: #333; color: #fff">Web Server</th>
		<th>Last Week</th>
		<th>This Week</th>
		<th>Last Month</th>
		<th>This Month</th>
	</td>
<?php foreach( $server as $key => $value ): ?>
	<tr>
		<th><?= $key ?></th>
		<td><?= $value ?></td>
		<td><?= $value ?></td>
		<td><?= $value ?></td>
		<td><?= $value ?></td>
	</tr>
<?php endforeach; ?>
</table>
-->