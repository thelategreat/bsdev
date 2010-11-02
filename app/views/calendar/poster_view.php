
<div style="float: right">
	<a href="<?=$prev_month_url?>" title="last month">
		<img src="/img/cal/arrow_left.png" style="width: 24px; margin-bottom: -5px"/></a>&nbsp;
	<a href="<?=$next_month_url?>" title="next month">
		<img src="/img/cal/arrow_right.png" style="width: 24px; margin-bottom: -5px"/></a>
</div>

<h2><?= date("F",mktime(0, 0, 0, $month, 1, $year))?> <?= $year ?></h2>

<?= $view_menu ?>

<table class="calendar-poster">
	<?php 
	$count = 0;
	foreach( $cal as $week ) {
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
			foreach( $day['events'] as $event ) { 
				if( $count % 3 == 0 ) {
					echo '<tr>';
				}
				?>
				<td valign="top">
					<span class="date"><?= $day['day']?> @ <?= $event['start']?> </span>
					<a href="/events/details/<?=$event['id']?>">
						<img src="<?=$event['media']?>" width="200px"/>
						<?=$event['title']?>
					</a>					
				</td>
				
		<?php
					if( $count > 0 && ($count + 1) % 3 == 0 ) {
						echo '</tr>';
					}
					$count++;
				}
			}
		}	?>
	<!--
	<tr>
		<td><img src="/img/junk/gallery_photo4.jpg" width="200px"/>Some Event</td>
		<td><img src="/img/junk/gallery_photo5.jpg" width="200px"/>Some Event</td>
		<td><img src="/img/junk/gallery_photo6.jpg" width="200px"/>Some Event</td>
	</tr>
	<tr>
		<td><img src="/img/junk/gallery_photo4.jpg" width="200px"/>Some Event</td>
		<td><img src="/img/junk/gallery_photo5.jpg" width="200px"/>Some Event</td>
		<td><img src="/img/junk/gallery_photo6.jpg" width="200px"/>Some Event</td>
	</tr>
	-->
</table>	