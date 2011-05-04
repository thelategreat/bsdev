
<p/>
<form action='/admin/products/edit/<?=$product->id?>' method="post">
<fieldset>
 <legend>Edit Product</legend>
  <table style="width: auto;">
    <tr>
      <td><label>EAN</label></td>
      <td><input name="ean" value="<?=$product->ean?>" /><?=form_error('ean')?></td>
      <td><label>Type</label></td>
      <td><select name="prod_type"><option value="1">Book</option></select></td>
    </tr>
    <tr>
      <td><label>Title</label></td>
      <td colspan="3"><input name="title" size="60" value="<?=$product->title?>" /><?=form_error('title')?></td>
    </tr>
  </table>
</fieldset>
<fieldset>
  <legend>Pricing/Stock</legend>
  <table style="width: auto">
    <tr>
      <td><label><b>Sale Price</b></label></td>
      <td><input name="bs_price" size="7" value="<?=$product->bs_price?>" /><?=form_error('bs_price')?></td>
      <td><label>Our Cost</label></td>
      <td><input name="bs_cost" size="7" value="<?=$product->bs_cost?>" readonly="readonly" /></td>
      <td><label>On Hand</label></td>
      <td><input name="bs_oh" size="4" value="<?=$product->bs_oh?>" readonly="readonly" /></td>
      <td><label>On Order</label></td>
      <td><input name="bs_oo" size="4" value="<?=$product->bs_oo?>" readonly="readonly" /></td>
    </tr>
  </table>
</fieldset>
<p/>
<input class="save-button" type="submit" name="save" value="Save" />
<input class="cancel-button" type="submit" name="cancel" value="Cancel" />
</form> 


