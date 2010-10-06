
<h2><?= date("F",mktime(0, 0, 0, $month, 1, $year))?> <?= $year ?></h2>

<?= $view_menu ?>

<ul class="calendar-list">
	<?php foreach( $cal as $week ) {
		foreach( $week as $day ) {
			switch( $day['day']) {
				case 1:
				$day['day'] = '1<sup>st</sup>';
				break;
				case 2:
				$day['day'] = '2<sup>nd</sup>';
				break;
				case 3:
				$day['day'] = '3<sup>rd</sup>';
				break;
				default:
				$day['day'] = $day['day'] . '<sup>th</sup>';
			}
			foreach( $day['events'] as $event ) { ?>
				<li><span class="day"><?= $day['day']?></span><span class="date">@ <?= $event['start']?></span>
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