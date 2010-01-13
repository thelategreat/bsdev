<div style="float: right; font-size: 0.9em">
	<a href="/admin/maillist/tmpladd"><img src="/img/building_add.png" /> New Template</a>
</div>
<h3><img src="/img/building.png" /> Newsletter Templates</h3>

<table>
<?php foreach( $tmpls as $row ) { ?>
	<tr style="border-bottom: 1px solid #ddd;">
		<td><a href="/admin/maillist/tmpledit/<?=$row->id?>"> <?=$row->name?></a></td>
		<td><a href="/admin/maillist/tmplrm/<?=$row->id?>" onclick="return confirm('Really delete this?');"><img src="/img/building_delete.png" /> remove</a></td>
	</tr>
<?php } ?>
</table>