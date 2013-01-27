<script type="text/javascript">
$(function() {
	$("#send_on").datepicker({dateFormat: 'yy-mm-dd'});
});

function insert_template()
{
	var id = $('#template').val();
	$.post('/admin/maillist/ajxtmpl', {id: id}, function(data) {
		var data = eval(data);
		//alert(data[0].tmpl);
		//$('#text_fmt').tinymce().execCommand('mceInsertContent',false, data[0].tmpl);
		tinyMCE.get('text_fmt').execCommand('mceInsertContent',false, data[0].tmpl);
	});
}

</script>
<?=$error_msg?>
<?= validation_errors() ?>
<form method="post">
<fieldset><legend>Edit Newsletter</legend>
<table style="border:0">
<input type="hidden" name="id" value="<?=$msg->id?>" />
<tr>
  <td><label for="ml_list_id">List</label></td>
  <td><select name="ml_list_id"><?=$ml_lists?></select></td>
</tr>
<tr>
  <td><label for="subject">Subject</label></td>
  <td><input name="subject" size="50" value="<?=$msg->subject?>"/></td>
</tr>
<!--
<tr>
  <td><label for="from">From</label></td>
  <td><input name="from" size="50" value="<?=$msg->from?>"/></td>
</tr>
-->
<tr>
  <td valign="top"><label for="text_fmt">Message</label></td>
  <td><textarea name="text_fmt" id="text_fmt" rows="15" cols="80"><?=$msg->text_fmt?></textarea>
	<select name="template" id="template"><?=$template_list?></select> <button onclick="insert_template(); return false;">Insert</button>
	</td>
</tr>
<tr>
  <td valign="top"><label for="text_plain">Message (plain)</label></td>
  <td><textarea name="text_plain" id="text_plain" class="mceNoEditor" rows="10" cols="80"><?=$msg->text_plain?></textarea></td>
</tr>
<tr>
  <td valign="top"><label for="send_on">Scheduled for</label></td>
	<td><input name="send_on" id="send_on" value="<?=$send_on?>" /> at about 
		<select name="send_at">
			<?=$send_at?>
		</select>
	</td>
</tr>
</table>
</fieldset>
<input type="submit" name="save" value="Save" />
<input type="submit" name="cancel" value="Cancel" />
</form>
