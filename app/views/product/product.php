

<table class="product-search-result">
<?php foreach( $products as $product ) { ?>
	<tr>
		<td class='center'><img height="100px" src="<?=$product->image?>" /></td>
		<td>
			<a href="#"><?= $product->title ?></a><br/>
			<?=$product->contributor?><br/>
			<?=$product->binding_text?><br/>
			<?= strlen(trim($product->bs_price)) > 0 ? '$' . money_format("%i",$product->bs_price) : '<pre>[' . $product->bs_price . ']??</pre>'?>
		</td>
		<td/>
<?php } ?>
</table>

<?= $pagination ?>