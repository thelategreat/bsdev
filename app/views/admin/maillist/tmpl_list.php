<div style="float: right; font-size: 0.9em">
	<a href="/admin/maillist/tmpladd"><img src="/img/building_add.png" /> New Template</a>
</div>
<h3>Newsletter Templates</h3>

<table>
<?php foreach( $tmpls as $row ) { ?>
	<tr style="border-bottom: 1px solid #ddd;">
		<td><a href="/admin/maillist/tmpledit/<?=$row->id?>"><img src="/img/building_edit.png" /> <?=$row->name?></td>
		<td><a href="/admin/maillist/tmplrm/<?=$row->id?>" onclick="return confirm('Really delete this?');"><img src="/img/building_delete.png" /> remove</td>
	</tr>
<?php } ?>
</table>