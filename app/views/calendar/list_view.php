<div class="row">
  <div class="column grid_3">
  foo
  </div>
  <div class="column grid_9">

<h2><span style="padding: 2px; margin: 2px;">
	<a href="<?=$prev_month_url?>" title="last month">
		<img src="/img/cal/arrow_left.png" style="width: 24px; margin-bottom: -5px"/></a>&nbsp;
	<a href="<?=$next_month_url?>" title="next month">
		<img src="/img/cal/arrow_right.png" style="width: 24px; margin-bottom: -5px"/></a>
</span> | 
<?= date("F",mktime(0, 0, 0, $month, 1, $year))?> <?= $year ?>
</h2>


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
				<li class="<?=$event['category']?>">
					<?php if( file_exists('img/icons/black/' . $event['category'] . '.png')) { ?>
						<img class="icon" style="float: right" src="'/img/icons/black/<?=$event['category']?>.png'" title="<?=$event['category']?>" />
					<?php }?>
						 <span class="day"><?= $day['day']?></span><span class="date">@ <?= $event['start']?></span>
					   <p><a href="/events/details/<?=$event['id']?>"><?=$event['title']?></a></p>
						 <div style="font-size: .85em; text-align: right"><?=$event['rating']?></div>
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

  </div>
</div>

