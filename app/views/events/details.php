<?php if( $event ) { ?>

<script type="text/javascript">
function show_location( id )
{
	$.ajax({
		url: '/events/venue/' + id + '/ajax',
		success: function(data) {
			$('#location-dialog').html(data);
		  $('#location-dialog').jqmShow();
		}
	});
	return false;
}

$(document).ready(function(){
   $('#location-dialog').jqm();
});
</script>

<div class="row">
  <div class="column grid_3">
  foo
  </div>
  <div class="column grid_6">

<div class="event-detail">
	<?php if( $media->num_rows() > 0 ) { ?>
		<img class="event_photo" src="/media/<?=$media->row()->uuid?>" width="141" alt="This is a photo of the event" />
	<?php } ?>
 	<h2 class="event-date"><?=$event->title?></h2>
	<p class="event-time"><?=date('l F j, Y',strtotime($event->dt_start))?> <?=date('g:i a',strtotime($event->dt_start)) . ' to ' . date('g:i a',strtotime($event->dt_end))?></p>
	
	<p class="event-location">
		<?php if( $event->venue_ref != 0 ) { ?>
			<a href="#" onclick="return show_location(<?=$event->venue_ref?>);">@ <?=$event->venue?></a>
			<div id="location-dialog" class="dialogWindow"></div>
		<?php } else { ?>
			@ <?=$event->venue?>
		<?php } ?>
	</p>
	<address class="event-address"></address>

	<?php if( $extra ) { 
		echo '<table class="event-extra">';
		foreach( $extra as $k => $v ) {
		?>
		<tr><td><?= mk_label($k) ?></td><td><?= mk_linkable($v)?></td></tr>
	<?php } 
		echo '</table>';
	} else { ?>
		<p class="event-time">Audience: <?=$event->audience?></p>
	<?php } ?>

	<div class="event-description">
		<p><?=$event->body?></p>
	</div>		
	
	<?php if( $future && $future->num_rows() ) { ?>
		<h3>Also Running On...</h3>
		<table class="event-extra">
			<?php foreach( $future->result() as $row ) { ?>
			<tr>
				<td><?=date('l F j, Y',strtotime($row->dt_start))?></td><td><?=date('g:i a',strtotime($row->dt_start))?></td>
			</tr>
			<?php } ?>
		</table>
	<?php } ?>
			
</div>
		
<?php } else {  ?>

<h2 class="event_date">Hmmm... we could not find that event.</h2>
	
<?php } ?>

  </div>
  <div class="column grid_3">
  bar
  </div>
</div>
