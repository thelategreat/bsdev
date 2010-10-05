
<script type="text/javascript" language="javascript">
<?=file_get_contents(dirname(__FILE__) . '/js/event.js');?>
</script>

<h3>Add Event</h3>
	
<table>
	<tr><td>
	<fieldset><legend>Details</legend>
<form id="event_form" action="/admin/event/add" method="POST" onsubmit="return validate(this, event);">
<input type="hidden" name="id" id="fld_id" value="-1" />
<table>
	<tr>
		<td colspan="4">
			<table>
				<tr>
					<td><label for="venue">Venue</label></td>
					<td>
						<select name="venue" id="fld_venue" onchange="sel_venue();" >
						  <option>cinema</option>
						  <option>greenroom</option>
						  <option>ebar</option>
						  <option>bookstore</option>
						  <option>other</option>
						</select>
					</td>
					<td><label for="category">Category</label></td>
					<td>
						<select name="category" id="fld_category" onchange="sel_category();" >
						  <option>film</option>
						  <option>music</option>
						  <option>reading</option>
						  <option>poetry</option>
						  <option>lecture</option>
						</select>
					</td>
					<td><label for="audience">Audience</label></td>
					<td>
						<select name="audience" id="fld_audience" onchange="sel_audience();" >
						  <option value="general">general (all ages)</option>
						  <option value="children">children</option>
						  <option value="teen">young adult</option>
						  <option value="adult">adult</option>
						</select>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td><label for="title">Title</label></td>
		<td colspan="3">
			<input name="title" size="70" id="fld_title" onkeyup="lookup(this.value);" autocomplete="off" />
		</td>
	</tr>
	<tr>
		<td><label for="event_date_start">Start Date</label></td>
		<td><input class="date-pick" name="event_date_start" size="12" onblur="" id="fld_event_date_start" value="<?=date('Y-m-d')?>"/><span class="small">yyyy-mm-dd<span></td>
		<td><label for="event_time_start">Start Time</label></td>
		<td>
			<?=$start_time_widget?>
		</td>
	</tr>
	<tr>
		<td><label for="event_date_end">End Date</label></td>
		<td><input class="date-pick" name="event_date_end" size="12" onblur="" id="fld_event_date_end" value="" /><span class="small">yyyy-mm-dd<span></td>
		<td><label for="event_time_end">End Time</label></td>
		<td>
			<?=$end_time_widget?>
		</td>
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
<table>
	<tr>
		<td>
			<input style="background-color: #9F9" type="submit" name="add" value="Save" />
			<input style="background-color: #6d6" type="submit" name="addedit" value="Save &amp; Add Media" />
		</td>
		<td>
			<input type="submit" name="cancel" value="Cancel" />
		</td>
	</tr>
</table>
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
