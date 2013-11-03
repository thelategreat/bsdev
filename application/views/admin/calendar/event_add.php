<? /*
<script type="text/javascript" language="javascript">
<?=file_get_contents(dirname(__FILE__) . '/js/event.js');?>
</script>
*/ ?>
<script type='text/javascript'>
	$(function() {

		initMCE(null);

		$('#fld_venue').autocomplete({ 
			source: function(request, response) {
                $.ajax({ url: "<?php echo site_url('admin/event/lookup_venue'); ?>",
	                data: { query: $("#fld_venue").val(),
	            			cat: 1},
	                dataType: "json",
	                type: "POST",
	                success: function(data){
	                    response(data);
	                }
	            });
			},
			select: function( event, ui ) {
				event.preventDefault();
				console.log (ui);
                if( ui.item.value != undefined ) {
                	$('#fld_venue').val( ui.item.label );
                	$('#fld_venue_id').val( ui.item.value );
                }
            }
		});
		$('.datepicker').datepicker({ dateFormat: 'yy-mm-dd' });
		$('#fld_start').change(function() {
			$('#fld_end').val($(this).val());
			$('#fld_end').css('background-color', 'yellow').animate({'background-color':'#fff'});
		});
		$('#btn_add_event').click(function() {
			tinyMCE.get("fld_body").save();	
			$.post('/admin/event/ajax_add_event', 
				$('#form').serialize(),
				function (data) {
					if (data.status == false) {
						alert(data.message);
					} else {
						$('#btn_back').click();	
					}
				},  'json');
		});
		$('#btn_delete_event').click(function() {

		$( "#dialog:ui-dialog" ).dialog( "destroy" );
			
				$( "#dialog-confirm" ).dialog({
					resizable: false,
					height:140,
					modal: true,
					buttons: {
						Cancel: function() {
							$( this ).dialog( "close" );
						},
						"Yes, delete": function() {
							$.post('/admin/event/ajax_remove_event', 
								$('#form').serialize(), 
								function(data) {
									if (data.status == false) {
										alert(data.message);
									} else {
										$('#btn_back').click();	
									}
								}, 'json')	
						}
					}
				});
		});

	});
</script>

<div class=container>
	<header>Add Event</header>

	<a href='/admin/calendar'>
		<button id='btn_back'>
			<i class="icon-chevron-left icon"></i> Calendar 
		</button>
	</a>
	<aside class=instruction>
		Enter the event information and assign a date.	
	</aside>

	<section>
		<div style="float:left">
		<form id='form'>
		<table class='form-table'>
			<tr><th>Category</th><td><?= $category_select ?></td>
			<tr><th>Audience</th><td><?= $audience_select ?></td>
			<tr><th>Title</th><td><input placeholder='Title' name=title id='fld_title' 
				<? if (isset($event)) { ?>
					value="<?=$event->title;?>"
				<? } ?>/>
			</td></tr>
			<tr><th>Description</th><td><textarea id='fld_body' name='body'>
				<? if (isset($event)) { ?>
					<?=$event->body;?>
				<? } ?>
			</textarea></td></tr>
			<tr><th>Start Date</th><td><input placeholder='Click for calendar' class="datepicker short" name="start" id="fld_start" 
				<? if (isset($event)) { ?>
					value="<?=date('Y-m-d', strtotime($event->start_time));?>"
				<? } ?>
				/></td></tr>
			<tr><th>Start Time</th><td><?=$start_time_widget?></td></tr>
			<tr><th>End Date</th><td><input placeholder='Click for calendar' class="datepicker short" name="end" id="fld_end" 
				<? if (isset($event)) { ?>
					value="<?=date('Y-m-d', strtotime($event->end_time));?>"
				<? } ?>
				/></td></tr>
			<tr><th>End Time</th><td><?=$end_time_widget?></td></tr>
			<tr><th>Venue</th><td><?= $venue_select ?></td></tr>
		</table>
		<? if (isset($event)) { ?>
			<input type='hidden' name='event_id' value='<?=$event->id;?>' />
		<? } ?>
		</form>
		</div>

		<div class="clear"></div>
		<? if (isset($event)) { ?>
			<button id="btn_add_event" class='iconbutton'>
					<i class="icon-save icon-2x"></i> Update Event
			</button>
			<button id="btn_delete_event" class='iconbutton'>
			<div>
				<i class="icon-remove-sign icon-2x"></i> Delete Event
			</div>
		</button>
		<? } else { ?>
			<button id="btn_add_event" class='iconbutton'>
					<i class="icon-save icon-2x"></i> Add Event
			</button>
		<? } ?>
	</section>

</div>
<div id="dialog-confirm" title="Empty the recycle bin?" style='display:none'>
	<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>Really delete this event?</p>
</div>

<? /*
<table>
	<tr><td valign="top">
	<fieldset><legend>Details</legend>
<form id="event_form" action="/admin/event/add" method="POST" onsubmit="return validate(this, event);">
<input type="hidden" name="id" id="fld_id" value="-1" />


<table>
	<tr>
		<td><label for="category">Category</label></td>
		<td>
			<?= $category_select ?>
		</td>
	</tr>
	<tr>
		<td><label for="audience">Audience</label></td>
		<td>
			<?= $audience_select ?>
		</td>
	</tr>
	<tr>
		<td><label for="title">Title</label></td>
		<td colspan="3">
			<input name="title" size="70" id="fld_title" autocomplete="off" />
			<input name="event_ref" type="hidden" id="fld_event_ref" />
		</td>
	</tr>
	<tr>
		<td><label for="event_date_start">Start Date</label></td>
		<td><input class="datepicker" name="event_date_start" size="12" onblur="" id="fld_event_date_start" value="<?=$start_date?>"/><span class="small">yyyy-mm-dd<span></td>
		<td><label for="event_time_start">Start Time</label></td>
		<td>
			<?=$start_time_widget?>
		</td>
	</tr>
	<tr>
		<td><label for="event_date_end">End Date</label></td>
		<td><input class="datepicker" name="event_date_end" size="12" onblur="" id="fld_event_date_end" value="<?=$start_date?>" /><span class="small">yyyy-mm-dd<span></td>
		<td><label for="event_time_end">End Time</label></td>
		<td>
			<?=$end_time_widget?>
		</td>
	</tr>
	<tr>
		<td><label for="venue">Venue</label></td>
		<td colspan="3">
			<input name="venue" size="50" id="fld_venue" autocomplete="off" />
			<input name="venue_ref" type="hidden" id="fld_venue_id" />
		</td>
	</tr>
	<? /*
	<tr>
		<td valign="top">Description</td>
	  <td colspan="3"><textarea name="body" rows="10" cols="70" id="fld_body"></textarea></td>
	</tr>
	
</table>
<p/>
<? /*
<span id='fld_event_date_startMsg'></span>
<span id='fld_event_date_endMsg'></span>
</fieldset>
<table class="button-bar">
	<tr>
		<td>
			<input class="save-button" type="submit" name="add" value="Save" />
			<input class="save-button1" type="submit" name="addedit" value="Save &amp; add media" />
			<input class="save-button2" type="submit" name="addanother" value="Save &amp; add another" />
		</td>
		<td align="right">
			<input class="cancel-button" type="submit" name="cancel" value="Cancel" onclick="cancelAction=true"/>
		</td>
	</tr>
</table>

</form>

</td>
</tr>
</table>
*/ ?>


<? 
/*



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
<table class="button-bar">
	<tr>
		<td>
			<!--
			<input style="background-color: #9F9" type="submit" name="add" value="Save" />
			<input style="background-color: #6d6" type="submit" name="addedit" value="Save &amp; add media" />
			<input style="background-color: #3d3" type="submit" name="addanother" value="Save &amp; add another" />
			-->
			<input class="save-button" type="submit" name="add" value="Save" />
			<input class="save-button1" type="submit" name="addedit" value="Save &amp; add media" />
			<input class="save-button2" type="submit" name="addanother" value="Save &amp; add another" />
		</td>
		<td align="right">
			<input class="cancel-button" type="submit" name="cancel" value="Cancel" onclick="cancelAction=true"/>
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
*/ ?>