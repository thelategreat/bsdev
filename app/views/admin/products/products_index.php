<div style="float: right">
	<form method="post">
		<input id="query" style="font-size: 0.8em;" name="q" value="<?=$query?>" size="15" onblur="if(this.value=='') this.value='search...';" onfocus="if(this.value == 'search...') this.value='';"/>
	</form>
</div>
<h3><a class="small" href="/admin/products/add/" title="Add Product"><img src="/img/admin/package_add.png" /></a> Products</h3>

<table>
	<tr>
		<th>sku</th>
		<th>title</th>
		<th>contributor</th>
	</tr>
	<?php $i = 0;
	foreach( $products->result() as $product ) { ?>
	<tr <?= ($i % 2) ? 'class="odd"' : ''?>>
		<td><a href="/admin/products/edit/<?=$product->id?>"><?=$product->ean?></a></td>
		<td><?=$product->title?></td>
		<td><?=$product->contributor?></td>
	</tr>
	<?php $i++; } ?>
</table>

<table class="pager">
  <tr>
    <td></td>
    <td align="right"><?=$prev_page?> | <?=$next_page?></td>
  </tr>
</table>

<hr/>


<?= form_open_multipart('/admin/products/import'); ?>
Import: <input type="file" name="userfile" size="30" />
<input type="submit" value="import" />
</form>