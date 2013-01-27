
<p/>

<fieldset><legend>Add Vendor</legend>
<?= form_open('/admin/vendors/add');?>
<table style="border: 0; width: auto;">
  <tr>
    <td><label>Name</label></td>
    <td colspan="3"><input name="name" size=50"><?=form_error('name')?></td>
  </tr>
  <tr>
    <td><label>Type</label></td>
    <td><?= form_dropdown('vtype',array('v'=>'Vendor','s'=>'Supplier'))?></td>
    <td><label>Status</label></td>
    <td><?= form_dropdown('status',array('a'=>'Active','i'=>'Inactive'))?></td>
  </tr>
  <tr>
    <td><label>Currency</label></td>
    <td><?= form_dropdown('currency',array('CAD'=>'CAD','USD'=>'USD'))?></td>
    <td><label>Commission</label></td>
    <td><input name="commision" size="4"/><?=form_error('commision')?></td>
  </tr>
  <tr>
    <td><label>Order Method</label></td>
    <td><?= form_dropdown('order_method',array('pho'=>'Phone','fax'=>'Fax','ema'=>'Email','x12'=>'X12','xml'=>'XML'))?></td>
   <td><label>CS Method</label></td>
    <td><?= form_dropdown('cs_method',array('pho'=>'Phone','fax'=>'Fax','ema'=>'Email','x12'=>'X12','xml'=>'XML'))?></td>
  </tr>
</table>
</fieldset>
<input class="save-button" name="save" type="submit" value="Save" />
<input class="cancel-button" name="cancel" type="submit" value="Cancel" />
</p>
</form>
