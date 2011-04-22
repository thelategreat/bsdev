
<?php
$items_count = 0;
$items_total = 0;
$ship_cost = "12.34";
$tax1 = 0.13;
$tax2 = 0.00;

foreach( $cart->contents() as $items ) {
  $items_count += $items['qty'];
  $items_total += $items['subtotal']; 
}

$sub_total = $items_total + $ship_cost;

?>

<h4>Review Your Order</h4>

<table cellpadding="5px" style="width: 100%; font-size: 0.9em; border: 1px solid #999;">
  <tr>
   <td valign="top" style="background-color: #eee; border-right: 1px solid #999;">
    <b>Shipping</b><br/>
<select name="shipping">
  <option value="cpr">Regular Parcel</option>
  <option value="xpr">Xpresspost</option>
  <option value="ppr">Priority</option>
</select>
    <pre id="ship-address">
<?=$order_info['shipto']?>
    </pre>
     <a href="/cart/shipto"><img src="/img/icons/page_edit.png" /> change</a>
   </td>
   <td valign="top">
     <b>Billing</b><br/>
<?=$order_info['ccno_disp']?>
     <pre id="bill-address">
<?=$order_info['billto']?>
     </pre>
     <a href="/cart/billing"><img src="/img/icons/page_edit.png" /> change</a>
   </td>
   <td valign="top" style="background-color: #eee; border-left: 1px solid #999;">
     <b>Summary</b>
      <table style="width: 100%; cell-padding: 0; cell-spacing: 0">
      <tr><td>Items (<?=$items_count?>)</td><td align="right"><?=$cart->format_number($items_total)?></td></tr>
      <tr><td>Shipping</td><td align="right"><?=$cart->format_number($ship_cost)?></td></tr>
        <tr><td/><td><hr/></td></tr>
        <tr><td>Sub Total</td><td align="right"><?=$cart->format_number($sub_total)?></td></tr>
        <tr><td>HST</td><td align="right"><?=$cart->format_number($sub_total * $tax1)?></td></tr>
        <tr><td>PST</td><td align="right"><?=$cart->format_number($sub_total * $tax2)?></td></tr>
        <tr><td colspan="2"><hr/></td></tr>
        <tr><td><b>Total</b></td><td align="right"><?=$cart->format_number($sub_total + ($sub_total * $tax1) + ($sub_total * $tax2))?></td></tr>
        <tr><td colspan="2"><hr/></td></tr>
        <tr><td colspan="2" align="right"><button>Place Your Order</button></td></tr>
      </table>
   </td>
  </tr>
</table>


<h4>Your Order Items</h4>
<table style="width:100%; font-size: 0.9em;">  
<?php
    $count = 0;
    foreach( $cart->contents() as $items ): ?>
      <tr class="<?=(($count % 2 != 0) ? "odd" : "")?>">
        <td valign="top"><?=$items['qty']?></td>
			  <td>
				<?= $items['name'] ?>
					<?php if ($cart->has_options($items['rowid']) == TRUE): ?>
						<p>
							<?php foreach ($cart->product_options($items['rowid']) as $option_name => $option_value): ?>

								<strong><?php echo $option_name; ?>:</strong> <?php echo $option_value; ?><br />

							<?php endforeach; ?>
						</p>
					<?php endif; ?>
			  </td>
			  <td style="text-align:right"><?= $cart->format_number($items['price']); ?></td>
			  <td style="text-align:right">$<?= $cart->format_number($items['subtotal']); ?></td>
			</tr>

<?php
     $count++; 
    endforeach; ?>
</table>
