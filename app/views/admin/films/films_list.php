
<div style="float: right">
	<form method="post">
		<input id="query" style="font-size: 0.8em;" name="q" value="<?=$query?>" size="15" onblur="if(this.value=='') this.value='search...';" onfocus="if(this.value == 'search...') this.value='';"/>
	</form>
</div>

<h3><a class="small" href="/admin/films/add/"><img src="/img/admin/picture_add.png" title="Add Film"/></a> Films</h3>

<table class="general">
	<tr>
		<th>title</th>
		<th>director</th>
		<th>year</th>
		<th>rating</th>
	</tr>
<?php
 	$i = 0;
	foreach( $films->result() as $row): ?>
	<tr <?= ($i % 2) ? 'class="odd"' : ''?>>
		<td><a href="/admin/films/edit/<?=$row->id?>"><?=$row->title?></a></td>
		<td><?=$row->director?></td>
		<td><?=$row->year?></td>
		<td><?=$row->rating?></td>
	</tr>
<?php 
	$i++;
	endforeach; ?>
</table>

<table class="pager">
	<tr>
		<td><?=$prev?></td>
		<td align="right"><?=$next?></td>
	</tr>
</table>