<script type='text/javascript'>
$(document).ready(function() {
	$('#films').dataTable({
      'sDom': '<"H"rplf>t<"F"i>'
    });
	});
</script>


<div class=container>
	<header>Films</header>

	<nav>
		<a href="/admin/films/add/">
			<button id='btn_add'>
				<i class="icon-plus icon"></i> Add Film 
			</button>
		</a>
	</nav>
	<br>

	<section>
		<table id='films'>
			<thead>
				<td>title</td>
				<td>director</td>
				<td>year</td>
				<td>rating</td>
			</thead>
			<tbody>
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
			</tbody>
		</table>
		
	</section>

</div>

<? 
/*
<style>
table.pager {
  display: inline;
}
table.toolbar {
  display: inline;
}
</style>

<table class="toolbar">
<tr>
  <td>
    <h3><a class="small" href="/admin/films/add/"><img src="/img/admin/picture_add.png" title="Add Film"/></a> Films</h3>
  </td>
  <td><?= $pager ?></td>
  <td>
 	<form method="post" style="display: inline;">
		<input id="query" style="font-size: 0.8em;" name="q" value="<?=$query?>" size="15" onblur="if(this.value=='') this.value='search...';" onfocus="if(this.value == 'search...') this.value='';"/>
  </form>
  </td>
</tr>
</table>

<!--
<div class="toolbar">
	<span class="toolbar-title">Films</span>
	<button><img src="/img/admin/picture_add.png" /></button>
	<span class="spacer"> </span>
	<button><img src="/img/admin/32-arrow-right.png" /></button>
	<button><img src="/img/admin/32-arrow-left.png" /></button>
	<form method="post" style="display: inline">
		<input id="query" style="font-size: 0.8em;" name="q" value="<?=$query?>" size="15" onblur="if(this.value=='') this.value='search...';" onfocus="if(this.value == 'search...') this.value='';"/>
	</form>
</div>
-->
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
*/ ?>
