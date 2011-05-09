<div style="float: right">
<img src="/img/icons/black/shop_cart.png" /> 
</div>
<div id="cart">
<h2>Shopping Cart</h2>

<hr/>

<? if( $cart->total_items() == 0 ) { ?>
	
<p>You have nothing in your shopping cart</p>


<? } else { ?>

<script type="text/javascript">
function remove_item( num ) {
  var elem = '' + num + '[qty]';
  $( 'input[name$="' + elem + '"]').val("0");
  return true;
}

function stopEnter( evt ) {
  var evt = (evt) ? evt : ((event) ? event : null);
  var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null );
  if((evt.keyCode == 13) && (node.type == "text")) {
    return false;
  }
}

document.onkeypress = stopEnter;

</script>

	<form action="/cart" id="cart-form" method="post">
	<table cellpadding="6" cellspacing="1" style="width:100%" border="0">

  <tr>
    <th/>
	  <th>QTY</th>
	  <th>Item Description</th>
	  <th style="text-align:right">Price</th>
	  <th style="text-align:right">Subtotal</th>
	</tr>
	
	<?
	$i = 1;
	foreach( $cart->contents() as $items ): ?>
		<?php echo form_hidden($i.'[rowid]', $items['rowid']); ?>
    <tr>
      <td><button class="small-button" onclick="return remove_item(<?=$i?>)" title="remove this item">delete</button></td>
        <td valign="top">
            <?php echo form_input(array('name' => $i.'[qty]', 'value' => $items['qty'], 'maxlength' => '3', 'size' => '3')); ?>
        </td>
			  <td>
        <?= '<a href="/product/view/' . $items['id'] . '">' . $items['name'] . '</a>' ?>

					<?php if ($cart->has_options($items['rowid']) == TRUE): ?>

						<p>
							<?php foreach ($cart->product_options($items['rowid']) as $option_name => $option_value): ?>

								<strong><?php echo $option_name; ?>:</strong> <?php echo $option_value; ?><br />

							<?php endforeach; ?>
						</p>

					<?php endif; ?>

			  </td>
			  <td style="text-align:right"><?php echo $cart->format_number($items['price']); ?></td>
			  <td style="text-align:right">$<?php echo $cart->format_number($items['subtotal']); ?></td>
			</tr>

		<?php $i++; ?>

		<?php endforeach; ?>

    <tr>
      <td colspan="5" style="border-top: 2px solid #999;">
    </tr>

    <tr>
		  <td colspan="3"> </td>
		  <td class="right"><strong>Subtotal</strong></td>
		  <td class="right"i style="color: #090">$<strong><?= $cart->format_number($cart->total()); ?></strong></td>
		</tr>
    <tr>
      <td colspan="5" style="border-top: 2px solid #999;">
    </tr>
    <tr>
      <td colspan="2">
        <?php echo form_submit('update', 'Update your Cart'); ?>
      </td>
      <td align="right" colspan="3">
        <?php echo form_submit('checkout', 'Checkout'); ?>
      </td>
    </tr>
		</table>
		
			
<? } ?>
</div> <!-- cart -->
