<div class="tabs">
<ul>
	<li><a href="#" class="selected" onclick="get_details();">Details</a></li>
	<li><a href="#" onclick="get_media();">Media</a></li>
</ul>
</div>

<form id="event_form" action="/admin/calendar/add_event" >
<input type="hidden" name="id" id="fld_id" value="-1" />
<table style="border: none;">
<tr>
	<td><label for="title">Title</label></td>
	<td colspan="3"><input name="title" size="50" id="fld_title" /></td>
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
  <td colspan="4">Description</td>
</tr>
<tr>
  <td colspan="4"><textarea name="body" rows="10" cols="70" id="fld_body"></textarea></td>
</tr>
</table>
</form>
<p/>
<span id='fld_event_date_startMsg'><p/></span>
<span id='fld_event_date_endMsg'><p/></span>
<button style="background-color: #9f9;" onclick="save_record()">Save</button>
<button onclick="$.modal.close()">Cancel</button>
