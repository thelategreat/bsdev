<div class='widget-vertical tooltip' title='Schedule'>
	<? foreach ($events as $event) { ?>
		<article class='schedule-event ym-clearfix'>							
				<a href='/events/details/<?=$event['id'];?>'>
				<? if (isset($event['uuid'])) { ?>
					<div style='float:left'><img style='margin:5px' src='/i/size/o/<?=$event['uuid'];?>/w/50/h/50' /></div>
				<? } ?>								
					<h5><?=$event['title'];?></h5>
					<?=$event['dt_start'];?>
				</a>
		</article>
	<? } ?>
</div>
