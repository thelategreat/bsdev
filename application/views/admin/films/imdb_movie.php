<h4><?=$data->title?></h4>

<table>
	<tr class="odd">
		<td class="label">Directors</td>
		<td>
		<?php foreach( $directors->result() as $row ): ?>
			<?= $row->name?>, 
		<?php endforeach; ?>
		</td>
	</tr>
	<tr>
		<td class="label">Plot</td>
		<td>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<?php if( $plot ): ?>
				<?= str_replace("\n", "<p/>", $plot->plottext) ?>, 
			<?php endif; ?>
		</td>
	</tr>
</table>
