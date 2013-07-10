<? /*
<script type="text/javascript" language="javascript">
<?=file_get_contents(dirname(__FILE__) . '/js/event.js');?>
</script>
*/ ?>
<script type='text/javascript'>
	$(function() {

		<? if (isset($film_id) && $film_id) { ?>
			displayShowTimes(<?=$film_id;?>);
		<? } ?>

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
                	$('#fld_event_ref').val( ui.item.value );

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
		$('#fld_event_date_start').change(function() {
			$('#fld_event_date_end').val($(this).val());
		});
		$('#btn_add_event').click(function() {
			var id = $('#fld_event_ref').val();
			var venue = $('#fld_venue').val();
			var start = $('#fld_event_date_start').val();
			var end   = $('#fld_event_date_end').val();
			var start_time_hour = $('#fld_event_time_start_hour').val();
			var start_time_min = $('#fld_event_time_start_min').val();
			var start_time_am_pm = $('#fld_event_time_start_am_pm').val();
			var end_time_hour = $('#fld_event_time_end_hour').val();
			var end_time_min = $('#fld_event_time_end_min').val();
			var end_time_am_pm = $('#fld_event_time_end_am_pm').val();

			$.post('/admin/films/ajax_add_showtime', 
				{id: id,
					venue: venue,
					start: start,
					end: end,
					start_time_hour: start_time_hour,
					start_time_min: start_time_min,
					start_time_am_pm: start_time_am_pm,
					end_time_hour: end_time_hour,
					end_time_min: end_time_min,
					end_time_am_pm: end_time_am_pm}, 
				function (data) {
					if (data.status == false) {
						alert(data.message);
					}
					displayShowTimes($('#fld_event_ref').val()); 
				}, 'json');
		});


		$('#container').delegate('a.delete', 'click', function(e) {
			e.preventDefault();
			$.post('/admin/films/remove_showtime', 
				{id: $(this).attr('value')},
				function(data) {
					console.log(data);
					displayShowTimes($('#fld_event_ref').val());
				});
		});



		function displayShowTimes( id ) {
			$.post('/admin/films/lookup_showtimes', {id: id}, function(data) {
				var table = $('table.results');
				$('table.results tbody').empty();
				$.each(data, function(index, val) {
					table.append('<tr><td>' + val.date+ '</td><td>' 
						+ val.start + '</td><td>' 
						+ val.end + '</td><td>'
						+ '<a class="delete" value="' + val.id + '">DEL</a></tr>');
				})
			}, 'json' );
		}


	});
</script>

<div class=container>
	<header>Add Film Showtime</header>

	<a href='/admin/calendar'>
		<button>
			<i class="icon-chevron-left icon"></i> Calendar 
		</button>
	</a>
	<aside class=instruction>
		Choose the film, date and venue then click Add to create a new movie listing
	</aside>

	<section>
		<div style="float:right;width:30%;padding: 5px">
			<h1>Show Times</h1>
			<table class='results'>
			<thead>
				<tr><td>Date</td><td>Start Time</td><td>End Time</td><td></td><tr>
			</thead>
			<tbody>
				
			</tbody>
		</table>
		</div>
		<div style="float:left">
		<table class='form-table'>
			<tr><th>Category</th><td><?= $category_select ?></td>
			<tr><th>Audience</th><td><?= $audience_select ?></td>
			<tr><th>Title</th><td><input  placeholder='Search...' name=title id='fld_title' value="<?=$title?>"/><input name='event_ref' type='hidden' id='fld_event_ref' value="<?=$film_id?>"/></td></tr>
			<tr><th>Start Date</th><td><input placeholder='Click for calendar' class="datepicker short" name="event_date_start" id="fld_event_date_start" /></td></tr>
			<tr><th>Start Time</th><td><?=$start_time_widget?></td></tr>
			<tr><th>End Date</th><td><input placeholder='Click for calendar' class="datepicker short" name="event_date_end" id="fld_event_date_end" /></td></tr>
			<tr><th>End Time</th><td><?=$end_time_widget?></td></tr>
			<tr><th>Venue</th><td><?= $venue_select ?></td></tr>
		</table>
		</div>

		<div class="clear"></div>
		<button id="btn_add_event" class='iconbutton'>
				<i class="icon-save icon-2x"></i> Add Showtime
		</button>
	</section>

</div>
