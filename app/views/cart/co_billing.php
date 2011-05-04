
<h4>Billing Information</h4>

<p style="color:#f33;border-top: 3px solid #a22;border-bottom: 3px solid #a22;">
This site is currently in beta so please don't
put your real cc number in here. You can find valid
credit card test account numbers 
<a target="_blank" href="http://www.paypalobjects.com/en_US/vhelp/paypalmanager_help/credit_card_numbers.htm">here</a> 
if you want to help test stuff.
</p>


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


