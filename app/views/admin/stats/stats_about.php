<h3>System Info</h3>

<?= $msg ?>

<table>
	<?php foreach( $info as $section) { ?>
		<tr>
			<th colspan="2" style="padding-top: 5px;"><?=$section[0]?></th>
		</tr>
		<?php 
		$count = 0;
		foreach( $section[1] as $item ) { ?>
			<tr class="<?= ($count % 2) == 0 ? 'odd' : ''?>">
				<td><em><?=$item[0]?></em></td>
				<td><?=$item[1]?></td>
			</tr>
		<?php $count++; } ?>
	<?php } ?>
</table>