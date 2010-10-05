
<h2><?= date("F",mktime(0, 0, 0, $month, 1, $year))?> <?= $year ?></h2>

<?= $view_menu ?>

<table class="calendar-poster">
	<?php 
	$count = 0;
	foreach( $cal as $week ) {
		foreach( $week as $day ) {
			foreach( $day['events'] as $event ) { 
				if( $count % 3 == 0 ) {
					echo '<tr>';
				}
				?>
				<td valign="top">
					<span class="date"><?= $day['date']?> @ <?= $event['start']?></span>
					<img src="/img/junk/gallery_photo4.jpg" width="200px"/>
					<a href="/events/details/<?=$event['id']?>"><?=$event['title']?></a>					
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