<script type="text/javascript" src="/js/ajaxupload.js" ></script>

<script type="text/javascript" language="javascript">
<?=file_get_contents(dirname(__FILE__) . '/js/event.js');?>
</script>

<?=$tabs?>

<table><tr><td>
	<fieldset><legend>Event Details</legend>
<form id="event_form" action="/admin/event/edit/<?=$event->id?>" method="POST" onsubmit="return validate(this, event);">
<input type="hidden" name="id" id="fld_id" value="-1" />
<table style="border: none;">
<tr>
	<td colspan="4">
		<table>
			<tr>
				<td><label for="venue">Venue</label></td>
				<td>
					<select name="venue" id="fld_venue" >
					  <option <?=set_select('venue','cinema', ($event->venue == 'cinema'))?>>cinema</option>
					  <option <?=set_select('venue','greenroom', ($event->venue == 'greenroom'))?>>greenroom</option>
					  <option <?=set_select('venue','ebar', ($event->venue == 'ebar'))?>>ebar</option>
					  <option <?=set_select('venue','bookstore', ($event->venue == 'bookstore'))?>>bookstore</option>
					  <option <?=set_select('venue','other', ($event->venue == 'other'))?>>other</option>
					</select>
				</td>
				<td><label for="category">Category</label></td>
				<td>
					<select name="category" id="fld_category" >
					  <option <?=set_select('category','film', ($event->category == 'film'))?>>film</option>
					  <option <?=set_select('category','music', ($event->category == 'music'))?>>music</option>
					  <option <?=set_select('category','reading', ($event->category == 'reading'))?>>reading</option>
					  <option <?=set_select('category','poetry', ($event->category == 'poetry'))?>>poetry</option>
					  <option <?=set_select('category','lecture', ($event->category == 'lecture'))?>>lecture</option>
					</select>
				</td>
				<td><label for="audience">Audience</label></td>
				<td>
					<select name="audience" id="fld_audience" >
					  <option <?=set_select('audience','general', ($event->audience == 'general'))?>>general (all ages)</option>
					  <option <?=set_select('audience','children', ($event->audience == 'children'))?>>children</option>
					  <option <?=set_select('audience','teen', ($event->audience == 'teen'))?>>young adult</option>
					  <option <?=set_select('audience','adult', ($event->audience == 'adult'))?>>adult</option>
					</select>
				</td>
			</tr>
		</table>
	</td>
</tr>
<tr>
	<td><label for="title">Title</label></td>
	<td colspan="3">
		<input name="title" size="80" id="fld_title" value="<?=$event->title?>" onkeyup="lookup(this.value);" autocomplete="off"/>
	</td>
</tr>
<tr>
	<?php
	$dt_start = explode(" ", $event->dt_start);
	$dt_start[1] = explode(":",substr($dt_start[1], 0, -3));
	$dt_start_hour = $dt_start[1][0];
	$dt_start_min = $dt_start[1][1];
	$dt_start_am_pm = "am";
	if( $dt_start_hour > 12 ) {
		$dt_start_hour = $dt_start_hour - 12;
		$dt_start_am_pm = "pm";
	}
	//
	$dt_end = explode(" ", $event->dt_end);
	$dt_end[1] = explode(":",substr($dt_end[1], 0, -3));
	$dt_end_hour = $dt_end[1][0];
	$dt_end_min = $dt_end[1][1];
	$dt_end_am_pm = "am";
	if( $dt_end_hour > 12 ) {
		$dt_end_hour = $dt_end_hour - 12;
		$dt_end_am_pm = "pm";
	}
	?>
	<td><label for="event_date_start">Start Date</label></td>
	<td><input class="date-pick" name="event_date_start" size="12" onblur="" id="fld_event_date_start" value="<?=$dt_start[0]?>" autocomplete="off"/><span class="small" title="numeric date or english like: today, tomorrow, next monday, ...">yyyy-mm-dd<span></td>
	<td><label for="event_time_start">Start Time</label></td>
	<td>
		<!-- ><input name="event_time_start" size="5" onblur="" id="fld_event_time_start" value="<?=$dt_start[1]?>" autocomplete="off" /><span class="small">hh:mm</span> -->
		<select name="event_time_start_hour" id="fld_event_time_start_hour">
			<option <?= ($dt_start_hour == 1 ? "selected='selected'" : "")?>>1</option>
			<option <?= ($dt_start_hour == 2 ? "selected='selected'" : "")?>>2</option>
			<option <?=$dt_start_hour == 3 ? "selected='selected'" : ""?>>3</option>
			<option <?=$dt_start_hour == 4 ? "selected='selected'" : ""?>>4</option>
			<option <?=$dt_start_hour == 5 ? "selected='selected'" : ""?>>5</option>
			<option <?=$dt_start_hour == 6 ? "selected='selected'" : ""?>>6</option>
			<option <?=$dt_start_hour == 7 ? "selected='selected'" : ""?>>7</option>
			<option <?=$dt_start_hour == 8 ? "selected='selected'" : ""?>>8</option>
			<option <?=$dt_start_hour == 9 ? "selected='selected'" : ""?>>9</option>
			<option <?=$dt_start_hour == 10 ? "selected='selected'" : ""?>>10</option>
			<option <?=$dt_start_hour == 11 ? "selected='selected'" : ""?>>11</option>
			<option <?=$dt_start_hour == 12 ? "selected='selected'" : ""?>>12</option>
		</select>
		<select name="event_time_start_min" id="fld_event_time_start_min">
			<option <?=$dt_start_min == 0 ? "selected='selected'" : ""?>>00</option>
			<option <?=$dt_start_min == 15 ? "selected='selected'" : ""?>>15</option>
			<option <?=$dt_start_min == 30 ? "selected='selected'" : ""?>>30</option>
			<option <?=$dt_start_min == 45 ? "selected='selected'" : ""?>>45</option>
		</select>
		<select name="event_time_start_am_pm" id="fld_event_time_start_am_pm">
			<option <?=$dt_start_am_pm == "am" ? "selected='selected'" : ""?>>am</option>
			<option <?=$dt_start_am_pm == "pm" ? "selected='selected'" : ""?>>pm</option>
		</select>
	</td>
</tr>
<tr>
	<td><label for="event_date_end">End Date</label></td>
	<td><input class="date-pick" name="event_date_end" size="12" onblur="" id="fld_event_date_end" value="<?=$dt_end[0]?>" autocomplete="off"/><span class="small">yyyy-mm-dd<span></td>
	<td><label for="event_time_end">End Time</label></td>
	<td>
		<!-- <input name="event_time_end" size="5" onblur="" id="fld_event_time_end" value="<?=$dt_end[1]?>" autocomplete="off"/><span class="small">hh:mm</span>-->
		<select name="event_time_end_hour" id="fld_event_time_end_hour">
			<option <?= ($dt_end_hour == 1 ? "selected='selected'" : "")?>>1</option>
			<option <?= ($dt_end_hour == 2 ? "selected='selected'" : "")?>>2</option>
			<option <?=$dt_end_hour == 3 ? "selected='selected'" : ""?>>3</option>
			<option <?=$dt_end_hour == 4 ? "selected='selected'" : ""?>>4</option>
			<option <?=$dt_end_hour == 5 ? "selected='selected'" : ""?>>5</option>
			<option <?=$dt_end_hour == 6 ? "selected='selected'" : ""?>>6</option>
			<option <?=$dt_end_hour == 7 ? "selected='selected'" : ""?>>7</option>
			<option <?=$dt_end_hour == 8 ? "selected='selected'" : ""?>>8</option>
			<option <?=$dt_end_hour == 9 ? "selected='selected'" : ""?>>9</option>
			<option <?=$dt_end_hour == 10 ? "selected='selected'" : ""?>>10</option>
			<option <?=$dt_end_hour == 11 ? "selected='selected'" : ""?>>11</option>
			<option <?=$dt_end_hour == 12 ? "selected='selected'" : ""?>>12</option>
		</select>
		<select name="event_time_end_min" id="fld_event_time_end_min">
			<option <?=$dt_end_min == 0 ? "selected='selected'" : ""?>>00</option>
			<option <?=$dt_end_min == 15 ? "selected='selected'" : ""?>>15</option>
			<option <?=$dt_end_min == 30 ? "selected='selected'" : ""?>>30</option>
			<option <?=$dt_end_min == 45 ? "selected='selected'" : ""?>>45</option>
		</select>
		<select name="event_time_end_am_pm" id="fld_event_time_end_am_pm">
			<option <?=$dt_end_am_pm == "am" ? "selected='selected'" : ""?>>am</option>
			<option <?=$dt_end_am_pm == "pm" ? "selected='selected'" : ""?>>pm</option>
		</select>
	</td>
</tr>
<tr>
  <td colspan="4">Description</td>
</tr>
<tr>
  <td colspan="4"><textarea name="body" rows="10" cols="70" id="fld_body" ><?=$event->body?></textarea></td>
  <!--
	<td valign="top" align="center">
		<fieldset><legend>Media</legend>
			<div id="media_preview">
				<a href="#" onclick="MediaBrowser.init({path: '/events/<?=$event->id?>'});"><img src="/pubmedia/library/no_image.jpg" height="80" /></a>
				<br><small>no image assigned</small>
			</div>
		</fieldset>
	</td>
  -->
</tr>
</table>
<p/>
<span id='fld_event_date_startMsg'><p/></span>
<span id='fld_event_date_endMsg'><p/></span>
</fieldset>
<table>
	<tr><td>
		<input style="background-color: #9F9" type="submit" name="update" value="Update" />
		<input type="submit" name="cancel" value="Cancel" />
	</td>
	<td align="right">
		<input style="background-color: #F99" type="submit" name="rm" value="Delete" onclick="return confirm('Really delete this event?');"/>
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

<!-- media editing 
<div id="editModalDiv">
 <div id="modal_title">
		<span style="float: right">
			<img onclick="$.modal.close()" src="/img/close.png" title="Close" style="cursor: pointer;"/>
		</span>
    <h3>Media Browser</h3>
 </div>
 <div id="modal_content">
 </div>
</div>
-->
