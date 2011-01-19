

<table class="product-search-result">
<?php foreach( $products as $product ) { ?>
	<tr>
		<td class='center'><img height="100px" src="<?=$product->image?>" /></td>
		<td>
			<a href="/product/view/<?= $product->id ?>"><?= $product->title ?></a><br/>
			<div style="font-size: .9em">
			<span style="font-weight: bold;">EAN</span> <span style="font-style: italic; font-size: .9em"><?=$product->ean?></span><br/>
			<span style="font-weight: bold;">By</span> <?=str_replace( '|', ' &amp; ', $product->contributor) ?><br/>
			<span style="font-weight: bold;">Format</span> <?=$product->binding_text?><br/>
			<span style="font-weight: bold;">Pub. Date</span> <?=$product->publishing_date?><br/>
			<span style="font-weight: bold;">List Price</span> <?= strlen(trim($product->bs_price)) > 0 ? '$' . money_format("%i",$product->bs_price) : '<pre>$[' . $product->bs_price . ']??</pre>'?>
			</div>
		</td>
		<td/>
<?php } ?>
</table>

<?= $pagination ?>