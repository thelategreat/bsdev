	
<ul id="search_results_headers">
  <li class="description cufon">Description</li>
  <li class="time cufon">Time</li>
	<li class="audience cufon">Audience</li>
	<li class="date cufon">Date</li>
</ul>

<div id="results_wrapper" style="min-height: 300px">
	<ul class="search_result_listings">

  <?php if( $results['count'] > 0 ) { 
		foreach( $results['results']->result() as $event ) { ?>
			<li><a href="/events/details/<?=$event->id?>">
				<span class="left"><span class="result_title"><?=$event->title?></span>
				<em class="result_description"><?=strip_tags((strlen($event->body) > 100 ? substr($event->body,0,100) . "..." : $event->body))?></em></span>
				<span class="result_time"><?=date('g:ia',strtotime($event->dt_start))?></span>
				<span class="result_audience"><?=$event->audience?></span>
				<span class="result_date"><?=date('D M n, Y',strtotime($event->dt_start))?></span></a>
			</li>
  <?php }
	} ?>
	<!--
	<li><a href="#">
		<span class="left"><span class="result_title">Burn After Reading</span><em class="result_description">Lorem ipsum dolor sit amet, consectetuer elit.</em></span>
		<span class="result_time">4pm</span>
		<span class="result_audience">All Ages</span>
		<span class="result_date">Mon, Jan 4, 2009</span></a>
	</li>
	
	<li><a href="#">
		<span class="left"><span class="result_title">Flight of the Conchords</span>
		<em class="result_description">Flew the Coop Tour with Toad and the Wet Sprocket.</em></span>
		<span class="result_time">6pm</span>
		<span class="result_audience">19+</span>
		<span class="result_date">Mon, Jan 15, 2009</span></a>
	</li>
	
	<li><a href="#">
		<span class="left"><span class="result_title">Sed eleifend euismod mauris</span>
		<em class="result_description">Donec ut pede. Sed luctus, augue eget aliquam porta nulla tellus.</em></span>
		<span class="result_time">8pm</span>
		<span class="result_audience">19+</span>
		<span class="result_date">Wed, Jan 18, 2009</span></a>
	</li>
	
	<li><a href="#">
		<span class="left"><span class="result_title">Weezer</span>
		<em class="result_description">Donec adipiscing accumsan libero. Sed eleifend euismod mauris.</em></span>
		<span class="result_time">9pm</span>
		<span class="result_audience">All Ages</span>
		<span class="result_date">Thu, Feb 12, 2009</span></a>
	</li>
	
	<li><a href="#">
		<span class="left"><span class="result_title">Some Other Event</span>
		<em class="result_description">Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Fusce orci neque.</em></span>
		<span class="result_time">9:30pm</span>
		<span class="result_audience">All Ages</span>
		<span class="result_date">Thu, Feb 12, 2009</span></a>
	</li>



	<li><a href="#">
		<span class="left"><span class="result_title">Burn After Reading</span><em class="result_description">Lorem ipsum dolor sit amet, consectetuer elit.</em></span>
		<span class="result_time">4pm</span>
		<span class="result_audience">All Ages</span>
		<span class="result_date">Mon, Jan 4, 2009</span></a>
	</li>
	
	<li><a href="#">
		<span class="left"><span class="result_title">Flight of the Conchords</span>
		<em class="result_description">Flew the Coop Tour with Toad and the Wet Sprocket.</em></span>
		<span class="result_time">6pm</span>
		<span class="result_audience">19+</span>
		<span class="result_date">Mon, Jan 15, 2009</span></a>
	</li>
	
	<li><a href="#">
		<span class="left"><span class="result_title">Sed eleifend euismod mauris</span>
		<em class="result_description">Donec ut pede. Sed luctus, augue eget aliquam porta nulla tellus.</em></span>
		<span class="result_time">8pm</span>
		<span class="result_audience">19+</span>
		<span class="result_date">Wed, Jan 18, 2009</span></a>
	</li>
	
	<li><a href="#">
		<span class="left"><span class="result_title">Weezer</span>
		<em class="result_description">Donec adipiscing accumsan libero. Sed eleifend euismod mauris.</em></span>
		<span class="result_time">9pm</span>
		<span class="result_audience">All Ages</span>
		<span class="result_date">Thu, Feb 12, 2009</span></a>
	</li>
	
	<li><a href="#">
		<span class="left"><span class="result_title">Some Other Event</span>
		<em class="result_description">Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Fusce orci neque.</em></span>
		<span class="result_time">9:30pm</span>
		<span class="result_audience">All Ages</span>
		<span class="result_date">Thu, Feb 12, 2009</span></a>
	</li>
-->
</ul> <!-- /Search Result Listings -->
</div>