
<script type="text/javascript" language="javascript">
<?=file_get_contents(dirname(__FILE__) . '/js/event.js');?>
</script>

<h3>Add Event</h3>
	
<table>
	<tr><td valign="top">
	<fieldset><legend>Details</legend>
<form id="event_form" action="/admin/event/add" method="POST" onsubmit="return validate(this, event);">
<input type="hidden" name="id" id="fld_id" value="-1" />
<table>
	<tr>
		<td/>
		<td colspan="2">
			<table>
				<tr>
					<td><label for="category">Category</label></td>
					<td>
						<?= $category_select ?>
					</td>
					<td><label for="audience">Audience</label></td>
					<td>
						<?= $audience_select ?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td><label for="title">Title</label></td>
		<td colspan="3">
			<input name="title" size="70" id="fld_title" onkeyup="lookup(this.value);" autocomplete="off" />
			<input name="event_ref" type="hidden" id="fld_event_ref" />
		</td>
	</tr>
	<tr>
		<td><label for="event_date_start">Start Date</label></td>
		<td><input class="date-pick" name="event_date_start" size="12" onblur="" id="fld_event_date_start" value="<?=$start_date?>"/><span class="small">yyyy-mm-dd<span></td>
		<td><label for="event_time_start">Start Time</label></td>
		<td>
			<?=$start_time_widget?>
		</td>
	</tr>
	<tr>
		<td><label for="event_date_end">End Date</label></td>
		<td><input class="date-pick" name="event_date_end" size="12" onblur="" id="fld_event_date_end" value="<?=$start_date?>" /><span class="small">yyyy-mm-dd<span></td>
		<td><label for="event_time_end">End Time</label></td>
		<td>
			<?=$end_time_widget?>
		</td>
	</tr>
	<tr>
		<td><label for="venue">Venue</label></td>
		<td colspan="3">
			<input name="venue" size="50" id="fld_venue" onkeyup="lookup_venue(this.value);" autocomplete="off" />
			<input name="venue_ref" type="hidden" id="fld_venue_ref" />
		</td>
	</tr>
	<tr>
		<td valign="top">Description</td>
	  <td colspan="3"><textarea name="body" rows="10" cols="70" id="fld_body"></textarea></td>
	</tr>
</table>
<p/>
<span id='fld_event_date_startMsg'></span>
<span id='fld_event_date_endMsg'></span>
</fieldset>
<table>
	<tr>
		<td>
			<input style="background-color: #9F9" type="submit" name="add" value="Save" />
			<input style="background-color: #6d6" type="submit" name="addedit" value="Save &amp; add media" />
			<input style="background-color: #3d3" type="submit" name="addanother" value="Save &amp; add another" />
		</td>
		<td>
			<input type="submit" name="cancel" value="Cancel" onclick="cancelAction=true"/>
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
