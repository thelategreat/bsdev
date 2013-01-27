
<h4>Shipping Information</h4>

<form action="/cart/checkout" method="post">
<textarea name="shipto" rows="5" cols="50">
<?=$order_info['shipto'] ? $order_info['shipto'] : ""?>
</textarea>
<br/>
<input type="submit" name="save" value="Save" />


