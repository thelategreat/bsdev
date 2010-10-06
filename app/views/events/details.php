<?php if( $event ) { ?>
	
<div class="event-detail">
	<?php if( $media->num_rows() > 0 ) { ?>
		<img class="event_photo" src="/media/<?=$media->row()->uuid?>" width="141" alt="This is a photo of the event" />
	<?php } ?>
 	<h2 class="event-date"><?=$event->title?></h2>
	<p class="event-time"><?=date('l F j, Y',strtotime($event->dt_start))?></p>
	<p class="event-time"><?=date('g:i a',strtotime($event->dt_start)) . ' to ' . date('g:i a',strtotime($event->dt_end))?></p>

	<p class="event-location">@ <?=$event->venue?></p>
	<address class="event-address"></address>

	<div class="event-description">
		<p><?=$event->body?></p>
	</div>		
			
</div>
		
<?php } else {  ?>

<h2 class="event_date">Hmmm... we could not find that event.</h2>
	
<?php } ?>