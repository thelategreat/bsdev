
<div style="float: right">
	<a href="<?=$prev_month_url?>" title="last month">
		<img src="/img/cal/arrow_left.png" style="width: 24px; margin-bottom: -5px"/></a>&nbsp;
	<a href="<?=$next_month_url?>" title="next month">
		<img src="/img/cal/arrow_right.png" style="width: 24px; margin-bottom: -5px"/></a>
</div>

<h2><?= date("F",mktime(0, 0, 0, $month, 1, $year))?> <?= $year ?></h2>

<?= $view_menu ?>

<table class="calendar-month">
	<thead>
		<tr>
			<th>Sunday</th>
			<th>Monday</th>
			<th>Tuesday</th>
			<th>Wednesday</th>
			<th>Thursday</th>
			<th>Friday</th>
			<th>Saturday</th>
		</tr>
	</thead>
	<tbody>

		<?php foreach( $cal as $week ) {
			echo '<tr>';
			foreach( $week as $day ) { 
				// figure which month we are in and class day accordingly
				$class = '';
				$dt = explode( '/', $day['date'] );
				if( $dt[1] != $month ) {
					$class = 'class="other"';					
				}
				?>
				<td <?=$class?>><span class="day-num"><?= $day['num'] ?></span>
					<ul class="event-list">
					<?php foreach( $day['events'] as $event ) { ?>
						<li class="<?=$event['category']?>"><a href="/events/details/<?= $event['id']?>"><?= $event['title'] ?></a></li>
					<?php } ?>
					</ul>
				</td>
			<?php }
			echo '</tr>';
		}?>

	</tbody>
</table>
<!--
<table class="calendar-month">
	<thead>
		<tr>
			<th>Sunday</th>
			<th>Monday</th>
			<th>Tuesday</th>
			<th>Wednesday</th>
			<th>Thursday</th>
			<th>Friday</th>
			<th>Saturday</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td></td>
			<td></td>
			<td></td>
			<td><span class="day-num">1</span>Some Event</td>
			<td><span class="day-num">2</span></td>
			<td><span class="day-num">3</span></td>
			<td><span class="day-num">4</span></td>
		</tr>
		<tr>
			<td><span class="day-num">5</span></td>
			<td><span class="day-num">6</span></td>
			<td><span class="day-num">7</span></td>
			<td><span class="day-num">8</span>Some Event</td>
			<td><span class="day-num">9</span></td>
			<td><span class="day-num">10</span></td>
			<td><span class="day-num">11</span></td>
		</tr>
		<tr>
			<td><span class="day-num">12</span></td>
			<td><span class="day-num">13</span></td>
			<td><span class="day-num">14</span></td>
			<td><span class="day-num">15</span>Some Event</td>
			<td><span class="day-num">16</span></td>
			<td><span class="day-num">17</span></td>
			<td><span class="day-num">18</span></td>
		</tr>
		<tr>
			<td><span class="day-num">19</span></td>
			<td><span class="day-num">20</span></td>
			<td><span class="day-num">21</span></td>
			<td><span class="day-num">22</span>Some Event</td>
			<td><span class="day-num">23</span></td>
			<td><span class="day-num">24</span></td>
			<td><span class="day-num">25</span></td>
		</tr>
		<tr>
			<td><span class="day-num">26</span></td>
			<td><span class="day-num">27</span></td>
			<td><span class="day-num">28</span></td>
			<td><span class="day-num">29</span>Some Event</td>
			<td><span class="day-num">30</span></td>
			<td></td>
			<td></td>
		</tr>
	</tbody>
</table>

-->