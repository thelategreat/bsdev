
<div id="cart">
<h3>Shopping Cart</h3>

<? if( $cart->total_items() == 0 ) { ?>
	
<p>You have nothing in your shopping cart</p>

<p>You can add a add a test item  <a href="/cart/addfake">here</a></p>

<? } else { ?>
	
	<p>Changing the quantity to zero will remove the item</p>
	
	<?php echo form_open('/cart'); ?>
	<table cellpadding="6" cellspacing="1" style="width:100%" border="0">

	<tr>
	  <th>QTY</th>
	  <th>Item Description</th>
	  <th style="text-align:right">Price</th>
	  <th style="text-align:right">Sub-Total</th>
	</tr>
	
	<?
	$i = 1;
	foreach( $cart->contents() as $items ): ?>
		<?php echo form_hidden($i.'[rowid]', $items['rowid']); ?>
	  <tr>
			  <td valign="top"><?php echo form_input(array('name' => $i.'[qty]', 'value' => $items['qty'], 'maxlength' => '3', 'size' => '5')); ?></td>
			  <td>
				<?php echo $items['name']; ?>

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
		  <td colspan="2"> </td>
		  <td class="right dark"><strong>Total</strong></td>
		  <td class="right dark">$<?php echo $cart->format_number($cart->total()); ?></td>
		</tr>

		</table>
		
		<p><?php echo form_submit('', 'Update your Cart'); ?></p>
			
<? } ?>
</div> <!-- cart -->