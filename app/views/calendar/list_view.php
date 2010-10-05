
<h2><?= date("F",mktime(0, 0, 0, $month, 1, $year))?> <?= $year ?></h2>

<?= $view_menu ?>

<ul class="calendar-list">
	<?php foreach( $cal as $week ) {
		foreach( $week as $day ) {
			foreach( $day['events'] as $event ) { ?>
				<li><span class="date"><?= $day['date']?> @ <?= $event['start']?></span>
					   <p><a href="/events/details/<?=$event['id']?>"><?=$event['title']?></a></p>
				</li>								
	<?php
			}
		}
	}	
	?>
	<!--
	<li><span class="date">10 @ 8:00 p.m.</span>
		   <p>Some event</p></li>
	<li><span class="date">10 all day</span>	   
		<p>Some event</p></li>
	<li><span class="date">12 @ 4:45 p.m</span>
		 <p>Some event</p></li>
	-->
</ul>	