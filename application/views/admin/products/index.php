<script type='text/javascript'>
	$(document).ready(function() {
		$('#products').dataTable();
	});
</script>

<h3><a class="small" href="/admin/products/add/" title="Add Product"><img src="/img/admin/package_add.png" /></a> Products</h3>

<div style='border: 1px solid #ccc; margin-bottom: 20px; padding:10px'>
<h1>Products</h1>
<form method="post" action='/admin/products/index'>
<span class='notice'>Use * as a general wildcard in any query term. Use ? as a single-character wildcard.</span>
<table>
	<tr><td>Search EAN</td><td><input id="query_ean" name="q[ean]" value="<?= isset($q['ean']) > 0 ? $q['ean'] : ''?>" size="35" /></td></tr>
	<tr><td width='150px'>Search Contributor</td><td><input id="query_contributor" name="q[contributor]" value="<?= isset($q['contributor']) > 0 ? $q['contributor'] : ''?>" size="35" /></td></tr>
	<tr><td>Search Title</td><td><input id="query_title" name="q[title]" value="<?= isset($q['title']) > 0 ? $q['title'] : ''?>" size="35" /></td></tr>
	<tr><td>Search Publisher</td><td><input id="query_publisher" name="q[publisher]" value="<?= isset($q['publisher']) > 0 ? $q['publisher'] : ''?>" size="35" /></td></tr>
	<tr><td><input type='submit' id='submit' value='Search'></input></td><td></td></tr>
</table>
</form>
</div>

<table id='products'>
	<thead>
		<tr>
			<th>SKU</th>
			<th>Title</th>
			<th>Contributor</th>
			<th>Publisher</th>
		</tr>
	</thead>
	<?php $i = 0;
	if (isset($products) && $products != false) foreach( $products->result() as $product ) { ?>
		<tr <?= ($i % 2) ? 'class="odd"' : ''?>>
			<td><a href="/admin/products/edit/<?=$product->id?>"><?=$product->ean?></a></td>
			<td><?=str_max_len($product->title,90)?></td>
			<td><?=$product->contributor?></td>
			<td><?=$product->publisher?></td>
		</tr>
	<?php $i++; } ?>
</table>