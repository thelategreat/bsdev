<script type="text/javascript">
function postit( action, params ) {
  var form = document.createElement("form");
  form.setAttribute("method","post");
  form.setAttribute("action",action);
  for( var key in params ) {
    var hfld = document.createElement("input");
    hfld.setAttribute("type","hidden");
    hfld.setAttribute("name",key);
    hfld.setAttribute("value",params[key]);
    form.appendChild(hfld);
  }
  document.body.appendChild(form);
  form.submit();
}
</script>
<?php
$items_count = 0;
$items_total = 0;
/*
$ship_cost = "12.34";
$tax1 = 0.13;
$tax2 = 0.00;
*/

foreach( $cart->contents() as $items ) {
  $items_count += $items['qty'];
  $items_total += $items['subtotal']; 
}

$sub_total = $items_total + $order_info['ship_cost'];

?>

<div id="order">
<h4>Review Your Order</h4>

<table class="order-header">
  <tr>
   <td valign="top" style="border-bottom: 1px solid #999; width: 60%">
    <span class="sect-title">Shipping</span> <a href="/cart/shipto"><img src="/img/icons/page_edit.png" /> change</a>
<br/>
    <pre id="ship-address">
<?=$order_info['shipto']?>
    </pre>

Method: 

<select name="shipptype" onchange="postit('/cart/checkout',{'shiptype':this.options[this.selectedIndex].value})">
<?php 
foreach( $ship_options as $k => $v ):
  $opt = '<option value="' . $k . '"';
  if( $order_info['ship_via'] == $k ) {
    $opt .= ' selected="selected" ';
  }
  $opt .= '>' . $v . '</option>';
  echo $opt;
endforeach;
?>
</select>
  </td>
   <td rowspan="2" valign="top" style="background-color: #eee; border-left: 1px solid #999;">
     <span class="sect-title">Order Summary</span>
      <table style="width: 100%; cell-padding: 0; cell-spacing: 0">
      <tr><td>Items (<?=$items_count?>)</td><td align="right"><?=$cart->format_number($items_total)?></td></tr>
      <tr><td>Shipping</td><td align="right"><?=$cart->format_number($order_info['ship_cost'])?></td></tr>
        <tr><td/><td><hr/></td></tr>
        <tr><td><i>Sub Total</i></td><td align="right"><?=$cart->format_number($sub_total)?></td></tr>
        <tr><td>HST</td><td align="right"><?=$cart->format_number($sub_total * $order_info['tax1'])?></td></tr>
        <tr><td>PST</td><td align="right"><?=$cart->format_number($sub_total * $order_info['tax2'])?></td></tr>
        <tr><td colspan="2"><hr/></td></tr>
        <tr><td><b>Total</b></td><td align="right"><b>$<?=$cart->format_number($sub_total + ($sub_total * $order_info['tax1']) + ($sub_total * $order_info['tax2']))?></b></td></tr>
        <tr><td colspan="2"><hr/></td></tr>
        <tr>
          <td colspan="2" align="right">
            <form action="/cart/order" method="post">
              <button>Place Your Order</button>
            </form>
          </td>
        </tr>
      </table>
   </td>
   </tr>
   <tr>
    <td valign="top">
     <span class="sect-title">Billing</span> <a href="/cart/billing"><img src="/img/icons/page_edit.png" /> change</a><br/>
<?=$order_info['ccno_disp']?>
     <pre id="bill-address">
<?=$order_info['billto']?>
     </pre>
    </td>
 </tr>
</table>

<hr/>

<h4>Your Order Items</h4>
<table class="order-items" >  
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

</div> <!-- #order -->
