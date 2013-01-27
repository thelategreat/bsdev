<div class="product-detail">
	
<img style="float: right; width: 200px; margin-left: 10px" src="<?=$item->image?>" title="<?=$item->ean?>"/>

<h2><?=$item->title?></h2>
<p/>
<table class="event-extra">
	<tr><td>EAN</td><td><?=$item->ean?></td></tr>
	<?php
	$contrib = explode('|',$item->contributor);
	$first = true;
	foreach( $contrib as $cont ) {
		echo "<tr><td>" . ($first ? "Contributors" : '') . "</td><td>$cont</td></tr>";
		$first = false;
	}
	?>
	<tr><td>Subject</td><td><?=$item->bisac_text?></td></tr>
	<tr><td>Publisher</td><td><?=$item->publisher?></td></tr>
	<tr><td>Pub. Date</td><td><?=$item->publishing_date?></td></tr>
	<tr><td>Format</td><td><?=$item->binding_text?></td></tr>
	<tr><td>Pages</td><td><?=$item->pages?></td></tr>
  <tr><td>Size</td><td><?=$item->size?></td></tr>
</table>

</div> <!-- product-detail -->

  <?php if( $item->bs_price ) { ?>
    <div class="big-price">
        <a style="float: left;" href="/cart/additem/<?=$item->id?>" title="Add to Cart"><img src="/img/icons/add_to_cart.png" /></a>
        <p>Bookshelf Price: <?='$' . money_format("%i",$item->bs_price) ?></p>
    </div>
  <?php } ?>


<?php

$oth = explode('|', $item->othertext_code_text);
$ot = explode('|', $item->othertext);
for( $i = 0; $i < count($ot); $i++ ) {
	echo '<h3>' . $oth[$i] . '</h3>';
	echo '<p>' . $ot[$i] . '</p>';
}



?>
