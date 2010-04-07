<h3>About the System</h3>

<table>
	<?php foreach( $info as $section) { ?>
		<tr>
			<th colspan="2"><?=$section[0]?></th>
		</tr>
		<?php foreach( $section[1] as $item ) { ?>
			<tr>
				<td><?=$item[0]?></td>
				<td><?=$item[1]?></td>
			</tr>
		<?php } ?>
	<?php } ?>
</table>