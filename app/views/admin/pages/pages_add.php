<script type="text/javascript">
function change_type( sel )
{
	if($(sel).val() == 'page') {
		$('#body_label').html('Body');
		__textareaIfy(document.getElementById('page_body'));
	}
	else if($(sel).val() == 'link') {
		$('#body_label').html('URL');
		__inputIfy(document.getElementById('page_body'));
	}
}

function _size2cols(s) { return Math.round(parseInt(s)/1)+''; }
function __textareaIfy(element) {
   var textarea = $('<textarea>')
     .attr('cols', _size2cols(element.size||20))
     .attr('rows', '20')
     .val($(element).val());
   for (var i=0, len=element.attributes.length; i < len; i++) {
      if (element.attributes[i].nodeName != 'type') {
         textarea.attr(element.attributes[i].nodeName, element.attributes[i].nodeValue);
      }
   }
 
   textarea.insertBefore(element); 
   $(element).remove();
 
}
 
function _cols2size(s) { return Math.round(parseInt(s)*1)+''; }
function __inputIfy(element) {
   var input = $('<input>')
     .attr('size', _cols2size(element.cols||20))
     .val($(element).val());
   for (var i=0, len=element.attributes.length; i < len; i++) {
      if (!/cols|rows|size/.test(element.attributes[i].nodeName)) {
         input.attr(element.attributes[i].nodeName, element.attributes[i].nodeValue);
      }
   }
 
   input.insertBefore(element);
  
   $(element).remove();
}
 
</script>

<fieldset><legend>Add Page</legend>
<?= form_open('/admin/pages/add');?>
<table style="border: 0">
<tr>
<td><label>Parent</label></td>
<td><select name="parent_id"><option value="0">--Top Level--</option><?=$parent_select?></select></td>
</tr>
<tr>
<td><label>Type</label></td>
<td><?= $page_types ?></td>
</tr>
<tr>
<td><label>Title</label></td>
<td><input type="text" name="title" class="textbox" size="50" />
	<?=form_error('title')?>
</td>
</tr>
<tr>
<td><label id="body_label">Body</label></td>
<td><textarea name="body" id="page_body" rows="20" cols="60"></textarea>
	<?=form_error('body')?>
	</td>
</tr>
</table>
</fieldset>
<input class="button" name="save" type="submit" value="Submit" />
<input class="button" name="cancel" type="submit" value="Cancel" />
</p>
</form>
