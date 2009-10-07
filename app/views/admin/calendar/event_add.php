<script type="text/javascript" language="javascript">
$(function() {
});

function validate( thisForm )
{
	if( $('#fld_title').val().length == 0 ) {
		alert("Title cannot be blank");
		return false;
	}
	if( $('#fld_event_date_start').val().length == 0 ) {
		alert("Start Date cannot be blank");
		return false;
	}
	if( $('#fld_event_date_end').val().length == 0 ) {
		alert("End Date cannot be blank");
		return false;
	}
	return true;
}

function lookup( inp )
{
	if( inp.length == 0 ) {
		$('#autoSuggestBox').hide();
	} else {
		$.post("/admin/event/lookup", {query: ""+inp+"", cat: ""+$('#fld_category').val()+"" },
			function( data ) {
				var ht = '';
				$(data).find('item').each(function(foo) {
					ht = ht + '<li onclick="fill(\'' + $(this).attr('name') + '\')">' + $(this).attr('name') + '</li>';
				});
				$('#autoSuggestBox').show();
				$('#autoSuggestList').html( ht );
			}, 'xml');
	}
}

function fill( thisValue )
{
	$('#fld_title').val(thisValue);
	$('#autoSuggestBox').hide();
}

</script>

<h3>Add Event</h3>
	
<table><tr><td>
	<fieldset><legend>Details</legend>
<form id="event_form" action="/admin/event/add" method="POST" onsubmit="return validate(this);">
<input type="hidden" name="id" id="fld_id" value="-1" />
<table style="border: none;">
	<tr>
		<td><label for="venue">Venue</label></td>
		<td>
			<select name="venue" id="fld_venue" >
			  <option>cinema</option>
			  <option>greenroom</option>
			  <option>ebar</option>
			  <option>bookstore</option>
			  <option>other</option>
			</select>
		</td>
		<td><label for="category">Category</label></td>
		<td>
			<select name="category" id="fld_category" >
			  <option>music</option>
			  <option>reading</option>
			  <option>poetry</option>
			  <option>lecture</option>
			  <option>film</option>
			</select>
		</td>
	</tr>
<tr>
	<td><label for="title">Title</label></td>
	<td colspan="3"><input name="title" size="50" id="fld_title" onkeyup="lookup(this.value);" autocomplete="off" />
	<br/><span class="form_error"><?=form_error('title')?></span>
	</td>
</tr>
<tr>
	<td><label for="event_date_start">Start Date</label></td>
	<td><input name="event_date_start" size="12" onblur="magicDate(this);" id="fld_event_date_start" /><span class="small">yyyy-mm-dd<span></td>
	<td><label for="event_time_start">Start Time</label></td>
	<td><input name="event_time_start" size="5" onblur="magicTime(this);" id="fld_event_time_start" /><span class="small">hh:mm</span></td>
</tr>
<tr>
	<td><label for="event_date_end">End Date</label></td>
	<td><input name="event_date_end" size="12" onblur="magicDate(this);" id="fld_event_date_end" /><span class="small">yyyy-mm-dd<span></td>
	<td><label for="event_time_end">End Time</label></td>
	<td><input name="event_time_end" size="5" onblur="magicTime(this);" id="fld_event_time_end"/><span class="small">hh:mm</span></td>
</tr>
<tr>
  <td colspan="4">Description</td>
</tr>
<tr>
  <td colspan="4"><textarea name="body" rows="10" cols="70" id="fld_body"></textarea></td>
</tr>
</table>
<p/>
<span id='fld_event_date_startMsg'><p/></span>
<span id='fld_event_date_endMsg'><p/></span>
</fieldset>
<input style="background-color: #9F9" type="submit" name="add" value="Add" />
<input type="submit" name="cancel" value="Cancel" />
</form>

</td>
<td valign="top" width="30%">
	<fieldset><legend>Lookup</legend>
		<div class="suggestBox" id="autoSuggestBox" style="display: none;">
			<div class="suggestList" id="autoSuggestList"></div>
		</div>
	</fieldset>
</td>
</tr>
</table>