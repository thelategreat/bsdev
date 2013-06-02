
<p/>
<form action='/admin/products/edit/<?=$product->id?>' method="post">
<fieldset>
 <legend>Product Detail</legend>
  <table width='100%'>
    <tr>
    <td>
      <table>
      <tr>
        <td><label>EAN</label></td>
        <td><input name="ean" value="<?=$product->ean?>" readonly /><?=form_error('ean')?></td>
      </tr>
      <tr>
        <td><label>Type</label></td>
        <td><select name="prod_type"><option value="1">Book</option></select></td>
      </tr>
      <tr>
        <td><label>Title</label></td>
        <td colspan="3"><input name="title" size="60" value="<?=$product->title?>" /><?=form_error('title')?></td>
      </tr>
      <tr>
        <td><label>Record Status</label></td>
        <td><? echo form_dropdown('record_status', $options['record_statuses'], $product->record_status_id); ?></td>
      </tr>
      <tr>
        <td><label>Record Type</label></td>
        <td><? echo form_dropdown('record_type', $options['record_types'], $product->record_types_id); ?></td>
      </tr>
      <tr>
        <td><label>Record Source</label></td>
        <td><? echo form_dropdown('record_sources', $options['record_sources'], $product->record_source_id); ?></td>
      </tr>
      <tr>
        <td><label>Publisher</label></td>
        <td><input name='publisher_name' value='<?=$product->publisher_name ?>' /></td>
      </tr>
      <tr>
        <td><label>Publish Date</label></td>
        <td><input name='publish_date' value='<?=$product->publish_date ?>' /></td>
      </tr>
      <?
      if (count(explode('|', $product->contributor)) > 1) { 
          $contributors = explode('|', $product->contributor);
          foreach ($contributors as $contributor) {
            ?>
              <tr>
                <td><label>Contributor</label></td>
                <td><input name='contributor' value='<?=$contributor ?>' /></td>
              </tr>
            <?
          }
        } else {
          ?>
              <tr>
                <td><label>Contributor</label></td>
                <td><input name='contributor' value='<?=$product->contributor ?>' /></td>
              </tr>
          <?
        }
      ?>
      
      <tr>
        <td><label>Series</label></td>
        <td><input name='series_name' value='<?=$product->series_name ?>' /></td>
      </tr>
      <tr>
        <td><label>Format Code</label></td>
        <td><? echo form_dropdown('format_code', $options['format_codes'], $product->format_code_id); ?></td>
      </tr>
      <tr>
        <td><label>Category</label></td>
        <td><? echo form_dropdown('product_category', $options['product_category'], $product->category_id); ?></td>
      </tr>
      <tr>
        <td><label>Teaser</label></td>
        <td><input name='teaser' value='<?=$product->teaser ?>' /></td>
      </tr>
      <tr>
        <td><label>Description</label></td>
        <td><input name='description' value='<?=$product->description ?>' /></td>
      </tr>
      </table>  

    </td>

    <td>
      <table>
        <td><label>ONIX Bind Code</label></td>
          <td><input name='onix_bind_code' value='<?=$product->onix_bind_code ?>' readonly /></td>
        </tr>
        <tr>
          <td><label>Pages</label></td>
          <td><input name='pages' value='<?=$product->pages ?>' readonly/></td>
        </tr>
        <tr>
          <td><label>Size</label></td>
          <td><input name='size' value='<?=$product->size ?>' readonly/></td>
        </tr>
        <tr>
          <td><label>Audience</label></td>
          <td><input name='audience' value='<?=$product->audience ?>' readonly/></td>
        </tr>
        <tr>
          <td><label>Awards</label></td>
          <td><input name='awards' value='<?=$product->awards ?>' readonly/></td>
        </tr>
        <tr>
          <td><label>Timestamp</label></td>
          <td><input name='timestamp' value='<?=$product->timestamp ?>' readonly /></td>
        </tr>
        <tr>
          <td><label>Sortby</label></td>
          <td><input name='sortby' value='<?=$product->sortby ?>' /></td>
        </tr>
      </table>
    </td>

  </table>
</fieldset>


<fieldset>
  <legend>Pricing/Stock</legend>
  <table style="width: auto">
    
    
    
    
    <tr>
      <td><label>List Price</label></td>
      <td><input name='list_price' value='<?=$product->list_price ?>' readonly /></td>
    </tr>
    <tr>
      <td><label>Sell Price</label></td>
      <td><input name='sell_price' value='<?=$product->sell_price ?>' /></td>
    </tr>
    <tr>
      <td><label>BNC Peer OO</label></td>
      <td><input name='bnc_peer_oo' value='<?=$product->bnc_peer_oo ?>' readonly /></td>
    </tr>
    <tr>
      <td><label>BNC Peer OH</label></td>
      <td><input name='bnc_peer_oh' value='<?=$product->bnc_peer_oh ?>' readonly /></td>
    </tr>
    <tr>
      <td><label>BNC Peer ASP</label></td>
      <td><input name='bnc_peer_asp' value='<?=$product->bnc_peer_asp ?>' readonly /></td>
    </tr>
    <tr>
      <td><label>BNC Peer Stores</label></td>
      <td><input name='bnc_peer_stores' value='<?=$product->bnc_peer_stores ?>' readonly /></td>
    </tr>
    <tr>
      <td><label>BNC All ASP</label></td>
      <td><input name='bnc_all_asp' value='<?=$product->bnc_all_asp ?>' readonly /></td>
    </tr>
    <tr>
      <td><label>BS Sales 4 Week</label></td>
      <td><input name='bs_sales_4week' value='<?=$product->bs_sales_4week ?>' readonly /></td>
    </tr>
    <tr>
      <td><label>BNC Peer Sales 4 Week</label></td>
      <td><input name='bnc_peer_sales_4week' value='<?=$product->bnc_peer_sales_4week ?>' readonly /></td>
    </tr>
    <tr>
      <td><label>BNC All Sales 4 Week</label></td>
      <td><input name='bnc_all_sales_4week' value='<?=$product->bnc_all_sales_4week ?>' readonly/></td>
    </tr>
    <tr>
      <td><label>BNC All Sales Total</label></td>
      <td><input name='bnc_all_sales_total' value='<?=$product->bnc_all_sales_total ?>' readonly/></td>
    </tr>
    <tr>
      <td><label>Web Meta Edit</label></td>
      <td><input name='web_meta_edit' value='<?=$product->web_meta_edit ?>' readonly /></td>
    </tr>

  </table>
</fieldset>
<p/>
<input class="save-button" type="submit" name="save" value="Save" />
<input class="cancel-button" type="submit" name="cancel" value="Cancel" />
</form> 


