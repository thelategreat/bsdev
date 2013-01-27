
<p/>

<?= form_open("/admin/vendors/edit/" . $vendor->id, array('class'=>'general') );?>
<fieldset><legend>Edit Vendor</legend>
<table style="border: 0; width: auto">
  <tr>
    <td><label>Name</label></td>
    <td colspan="3"><input name="name" size="50" value="<?=$vendor->name?>"><?=form_error('name')?></td>
  </tr>
  <tr>
    <td><label>Type</label></td>
    <td><?= form_dropdown('vtype',array('v'=>'Vendor','s'=>'Supplier'),$vendor->vtype)?></td>
    <td><label>Status</label></td>
    <td><?= form_dropdown('status',array('a'=>'Active','i'=>'Inactive'),$vendor->status)?></td>
  </tr>
  <tr>
    <td><label>Currency</label></td>
    <td><?= form_dropdown('currency',array('CAD'=>'CAD','USD'=>'USD'),$vendor->currency)?></td>
    <td><label>Commission</label></td>
    <td><input name="commision" size="4" value="<?=$vendor->commision?>"/></td>
  </tr>
  <tr>
    <td><label>Order Method</label></td>
    <td><?= form_dropdown('order_method',array('pho'=>'Phone','fax'=>'Fax','ema'=>'Email','x12'=>'X12','xml'=>'XML'),$vendor->order_method)?></td>
   <td><label>CS Method</label></td>
    <td><?= form_dropdown('cs_method',array('pho'=>'Phone','fax'=>'Fax','ema'=>'Email','x12'=>'X12','xml'=>'XML'),$vendor->cs_method)?></td>
  </tr>
</table>
</fieldset>
<input class="save-button" name="save" type="submit" value="Save" />
<input class="cancel-button" name="cancel" type="submit" value="Cancel" />
</p>
</form>
