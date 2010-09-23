<script type="text/javascript" src="/js/coda.js"></script>
<script type="text/javascript">
$(function () {
	$('.date_has_event').each(function () {
		// options
		var distance = 10;
		var time = 250;
		var hideDelay = 500;
 
		var hideDelayTimer = null;
 
		// tracker
		var beingShown = false;
		var shown = false;
 
		var trigger = $(this);
		var popup = $('.events ul', this).css('opacity', 0);
 
		// set the mouseover and mouseout on both element
		$([trigger.get(0), popup.get(0)]).mouseover(function () {
			// stops the hide event if we move from the trigger to the popup element
			if (hideDelayTimer) clearTimeout(hideDelayTimer);
 
			// don't trigger the animation again if we're being shown, or already visible
			if (beingShown || shown) {
				return;
			} else {
				beingShown = true;
 
				// reset position of popup box
				popup.css({
					bottom: 20,
					left: -76,
					display: 'block' // brings the popup back in to view
				})
 
				// (we're using chaining on the popup) now animate it's opacity and position
				.animate({
					bottom: '+=' + distance + 'px',
					opacity: 1
				}, time, 'swing', function() {
					// once the animation is complete, set the tracker variables
					beingShown = false;
					shown = true;
				});
			}
		}).mouseout(function () {
			// reset the timer if we get fired again - avoids double animations
			if (hideDelayTimer) clearTimeout(hideDelayTimer);
 
			// store the timer so that it can be cleared in the mouseover if required
			hideDelayTimer = setTimeout(function () {
				hideDelayTimer = null;
				popup.animate({
					bottom: '-=' + distance + 'px',
					opacity: 0
				}, time, 'swing', function () {
					// once the animate is complete, set the tracker variables
					shown = false;
					// hide the popup entirely after the effect (opacity alone doesn't do the job)
					popup.css('display', 'none');
				});
			}, hideDelay);
		});
	});
});
</script>

<div id="full-calendar">
<h3>Calendar: Sep 9, 2010</h3>

<table cellspacing="0">
	<thead>
		<tr>
			<th>Mon</th><th>Tue</th><th>Wed</th>
			<th>Thu</th><th>Fri</th><th>Sat</th>
			<th>Sun</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td class="padding" colspan="3"></td>
			<td> 1</td>
			<td> 2</td>
			<td> 3</td>
			<td> 4</td>
		</tr>
		<tr>
			<td> 5</td>
			<td> 6</td>
			<td> 7</td>
			<td> 8</td>
			<td class="today"> 9</td>
			<td>10</td>
			<td>11</td>
		</tr>
		<tr>
			<td>12</td>
			<td class="date_has_event">
				13
				<div class="events">
					<ul>
						<li>
							<span class="title">Event 1</span>
							<span class="desc">Lorem ipsum dolor sit amet, consectetu adipisicing elit.</span>
						</li>
						<li>
							<span class="title">Event 2</span>
							<span class="desc">Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</span>
						</li>
						<li>
							<span class="title">Event 3</span>
							<span class="desc">Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</span>
						</li>
					</ul>
				</div>				
			</td>
			<td>14</td>
			<td>15</td>
			<td>16</td>
			<td>17</td>
			<td>18</td>
		</tr>
		<tr>
			<td>19</td>
			<td>20</td>
			<td>21</td>
			<td class="date_has_event">
				22
				<div class="events">
					<ul>
						<li>
							<span class="title">Event 1</span>
							<span class="desc">Lorem ipsum dolor sit amet, consectetu adipisicing elit.</span>
						</li>
						<li>
							<span class="title">Event 2</span>
							<span class="desc">Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</span>
						</li>
					</ul>
				</div>				
			</td>
			<td>23</td>
			<td>24</td>
			<td>25</td>
		</tr>	
		<tr>
			<td>26</td>
			<td>27</td>
			<td>28</td>
			<td>29</td>
			<td>30</td>
			<td>31</td>
			<td class="padding"></td>
		</tr>
	</tbody>
	<tfoot>
		<th>Mon</th><th>Tue</th><th>Wed</th>
		<th>Thu</th><th>Fri</th><th>Sat</th>
		<th>Sun</th>
	</tfoot>
</table>
</div>