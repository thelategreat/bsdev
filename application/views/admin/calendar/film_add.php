
<script type="text/javascript" language="javascript">
<?=file_get_contents(dirname(__FILE__) . '/js/event.js');?>
</script>
<script type='text/javascript'>
	$(function() {
		$('#fld_title').autocomplete({ 
			source: function(request, response) {
                $.ajax({ url: "<?php echo site_url('admin/event/lookup_json'); ?>",
	                data: { query: $("#fld_title").val(),
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
                if( ui.item.value != undefined ) {
                	$('#fld_title').val( ui.item.label );
                	$('#fld_id').val( ui.item.value );

                	displayShowTimes( ui.item.value );
                }

            }
		});
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


		function displayShowTimes( $id ) {
			$.post('/admin/films/lookup_showtimes', {id: $id}, function(data) {
				console.log(data);

			}, 'json' );
		}
	});
</script>

<div class=container>
	<header>Add Event</header>

	<aside class=instruction>
		Choose the film, date and venue then click Add to create a new movie listing
	</aside>
	

</div>


<h3>Add Event</h3>
	
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
	*/ ?>
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
*/ ?>
</form>

</td>
</tr>
</table>
