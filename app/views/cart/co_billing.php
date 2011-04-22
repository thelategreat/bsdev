
<h4>Billing Information</h4>


<form action="/cart/checkout" method="post">
<table>
<tr><td><label for="ccno">Credit Card</label></td><td><input name="ccno" value="<?=$order_info['ccno']?>"/></td></tr>
<tr><td>Name on Card</td><td><input name="ccname" value="<?=$order_info['ccname']?>"/></td></tr>
  <tr><td>Expiry</td><td><input name="ccexp" size="5" value="<?=$order_info['ccexp']?>"> mm/yy</td></tr>
</table>
<h5>Billing Address</h5>
<textarea name="billto" rows="5" cols="50">
<?= $order_info['billto'] ? $order_info['billto'] : $order_info['shipto'] ?>
</textarea>
<br/>
<input type="submit" name="save" value="Save" />


