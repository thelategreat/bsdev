<link href="<? echo base_url('/js/fullcalendar/fullcalendar.css');?>" rel="stylesheet" type="text/css"/>
<script type='text/javascript' src="<? echo base_url('/js/fullcalendar/fullcalendar.min.js');?>"></script>
<script type='text/javascript'>
	$(function() {

		$('#calendar').fullCalendar({
		
			editable: false,
			
			events: {
				url: "/admin/calendar/get_events",
				type: 'POST',
				error: function () {
					alert("There was an error fetching events.");
				},
			},
			timeFormat: 'h:mm {- h:mm}',
			agenda: {
				timeFormat: 'h:mm {- h:mm}'
			},			
			eventClick: function(calEvent, jsEvent, view) {
				if (calEvent.className == 'event') {
					window.location.href = '<? echo base_url("/admin/event/edit_event/");?>/' + calEvent.id;
				} else {
					window.location.href = '<? echo base_url("/admin/event/add_film/");?>/' + calEvent.id;
				}
		    },
			
			loading: function(bool) {
				if (bool) $('#loading').show();
				else $('#loading').hide();
			},

			header: {
				left: 'month,agendaWeek,agendaDay',
				center: 'title',
				right: 'prev,next today'
			}

			
		});

	});
</script>

<div class=container>
	<header>Events</header>

	<aside class=instruction>
		Choose the film, date and venue then click Add to create a new movie listing
	</aside>

	<nav>
		<a href="/admin/event/add_event">
			<button id='btn_back'>
				<i class="icon-calendar icon"></i> Add Event 
			</button>
		</a>
		<a href="/admin/event/add_film">
			<button id='btn_back'>
				<i class="icon-film icon"></i> Add Film 
			</button>
		</a>
	</nav>
	<br>

	<section>
		<div id='calendar'>
	</section>

</div>