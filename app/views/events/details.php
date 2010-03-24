<?php if( $event ) { ?>
	
<div id="left_content">
 	<h2 class="event_date"><?=$event->title?></h2>
	<p class="event_time"><?=date('l F j, Y',strtotime($event->dt_start))?></p>
	<p class="event_time"><?=date('g:i a',strtotime($event->dt_start)) . ' to ' . date('g:i a',strtotime($event->dt_end))?></p>

	<p class="event_location">@ <?=$event->venue?></p>
	<address class="event_address">2nd floor, Bookshelf<br />
		41 Quebec St. Guelph
	</address>

	<div id="event_description">
		<p><?=$event->body?></p>
	</div>		
	
	<!--
	<div id="related_events">
		<h3>Related events that might interest you</h3>
		<ul id="related_events_list">	
			<li>			
				<h4 class="related_title"><a href="#">Burn After Reading</a> <span class="venue">@Cinema</span></h4>
				<p class="date">Monday, January 14th 6-8:30PM</p>
				<p class="description">Lorem ipsum dolor sit amet' consectetuer adipiscing elit.</p>
			</li>
		
			<li>
				<h4 class="related_title"><a href="#">Flight of the Conchords</a> <span class="venue">@Ebar</span></h4>
				<p class="description">Flew the Coop Tour with Toad and the Wet Sprocket</p>
			</li>
		
			<li>
				<h4 class="related_title"><a href="#">Sed eleifend euismod mauris</a> <span class="venue">@Cinema</span></h4>
				<p class="description">Donec ut pede. Sed luctus, augue eget aliquam porta nulla tellus.</p>
			</li>
		
			<li>
				<h4 class="related_title"><a href="#">Weezer</a> <span class="venue">@ St.George's Church</span></h4>
				<p class="description">Donec adipiscing accumsan libero. Sed eleifend euismod mauris.</p>
			</li>
		</ul>
	</div> -->
	<!-- /Related Events -->
</div><!-- /Left Content -->

<div id="right_content">
	<div id="more_info">
	
		<div id="share_this">
			<!-- Commented this out because it crashes IE7
			<script type="text/javascript">var addthis_pub="49f5f62a3237828b";</script>
			<a href="http://www.addthis.com/bookmark.php?v=20" onmouseover="return addthis_open(this, '', '[URL]', '[TITLE]')" onmouseout="addthis_close()" onclick="return addthis_sendto()"><img src="http://s7.addthis.com/static/btn/lg-share-en.gif" width="125" height="16" alt="Bookmark and Share"/></a><script type="text/javascript" src="http://s7.addthis.com/js/200/addthis_widget.js"></script>
			-->
		</div>
		
		<img class="event_photo" src="/i/events/event_photo.jpg" width="241" height="307" alt="This is a photo of the event" />
		<!--
		<div class="section">
			<h3>Tickets</h3>
			<p>Available at the Bookshelf checkout counter. $12 each. Student pricing available. Ask staff for details.</p>
		</div>
		
		<div class="section">						
			<h3>More Information</h3>
			<p>Presented By: <a href="#">Joe Blow</a></p>
			<p>Website: <a href="#">Official Tour Site</a></p>
		</div>
	
		<div class="section">
			<h3>Tags</h3>
			<ul id="tags">
				<li><a href="#">Guelph</a>,</li>
				<li><a href="#">Ahel Israel</a>,</li>
				<li><a href="#">Social Media</a>,</li>
				<li><a href="#">Social Networking</a>,</li>
				<li><a href="#">Twitter</a>,</li>
				<li><a href="#">Web 2.0 Guelph</a>,</li>
				<li><a href="#">Ahel Israel</a></li>			
			</ul>	
		</div>	
		-->		
	</div><!-- /More Info -->
</div><!-- /Right Content -->

<?php } else {  ?>
	<div id="left_content">
  	<h2 class="event_date">Hmmm... we could not find that event.</h2>
	</div>
	
<?php } ?>