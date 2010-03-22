<a class="previous_listings" href="/events/details">&laquo; Previous listings</a>
	<ul id="listings">
<?php foreach( $events->result() as $event ) { ?>
	<li><a href="/events/details/<?=$event->id?>">
		<span class="time"><?=date('h:i a',strtotime($event->dt_start))?></span>
		<span class="event"><?=$event->title?></span>
		<em class="description"><?=strlen(strip_tags($event->body)) > 100 ? substr(strip_tags($event->body),0,100) . "..." : strip_tags($event->body)?></em>
	</a></li>
<?php } ?>
</ul>
<!--
<ul id="listings">
	<li><a href="/events/details">
		<span class="time">4pm</span>
		<span class="event">Burn After Reading <span class="venue">@Cinema</span></span>
		<em class="description">Lorem ipsum dolor sit amet, consectetuer adipiscing elit.</em>
	</a></li>
	<li><a href="/events/details">
		<span class="time">6pm</span>
		<span class="event">Flight of the Conchords <span class="venue">@Ebar</span></span>
		<em class="description">Flew the Coop Tour with Toad and the Wet Sprocket.</em>
	</a></li>
	<li><a href="/events/details">
		<span class="time">8pm</span>
		<span class="event">Sed eleifend euismod <span class="venue">@Cinema</span></span>
		<em class="description">Donec adipiscing accumsan libero. Sed eleifend euismod mauris.</em>
	</a></li>
	<li><a href="/events/details">
		<span class="time">9:30pm</span>
		<span class="event">Weezer <span class="venue">@St.George's Church</span></span>
		<em class="description">Donec adipiscing accumsan libero. Sed eleifend euismod mauris.</em>
	</a></li>
	<li><a href="/events/details">
		<span class="time">10:30pm</span>
		<span class="event">Flight of the Conchords <span class="venue">@Ebar</span></span>
		<em class="description">Flew the Coop Tour with Toad and the Wet Sprocket.</em>
	</a></li>
	<li><a href="/events/details">
		<span class="time">4pm</span>
		<span class="event">Burn After Reading <span class="venue">@Cinema</span></span>
		<em class="description">Lorem ipsum dolor sit amet, consectetuer adipiscing elit.</em>
	</a></li>
	<li><a href="/events/details">
		<span class="time">6pm</span>
		<span class="event">Flight of the Conchords <span class="venue">@Ebar</span></span>
		<em class="description">Flew the Coop Tour with Toad and the Wet Sprocket.</em>
	</a></li>
	<li><a href="/events/details">
		<span class="time">8pm</span>
		<span class="event">Sed eleifend euismod <span class="venue">@Cinema</span></span>
		<em class="description">Donec adipiscing accumsan libero. Sed eleifend euismod mauris.</em>
	</a></li>
	<li><a href="/events/details">
		<span class="time">9:30pm</span>
		<span class="event">Weezer <span class="venue">@St.George's Church</span></span>
		<em class="description">Donec adipiscing accumsan libero. Sed eleifend euismod mauris.</em>
	</a></li>
	<li><a href="/events/details">
		<span class="time">10:30pm</span>
		<span class="event">Flight of the Conchords <span class="venue">@Ebar</span></span>
		<em class="description">Flew the Coop Tour with Toad and the Wet Sprocket.</em>
	</a></li>
	<li><a href="/events/details">
		<span class="time">4pm</span>
		<span class="event">Burn After Reading <span class="venue">@Cinema</span></span>
		<em class="description">Lorem ipsum dolor sit amet, consectetuer adipiscing elit.</em>
	</a></li>
	<li><a href="/events/details">
		<span class="time">6pm</span>
		<span class="event">Flight of the Conchords <span class="venue">@Ebar</span></span>
		<em class="description">Flew the Coop Tour with Toad and the Wet Sprocket.</em>
	</a></li>
	<li><a href="/events/details">
		<span class="time">8pm</span>
		<span class="event">Sed eleifend euismod <span class="venue">@Cinema</span></span>
		<em class="description">Donec adipiscing accumsan libero. Sed eleifend euismod mauris.</em>
	</a></li>
	<li><a href="/events/details">
		<span class="time">9:30pm</span>
		<span class="event">Weezer <span class="venue">@St.George's Church</span></span>
		<em class="description">Donec adipiscing accumsan libero. Sed eleifend euismod mauris.</em>
	</a></li>
	<li><a href="/events/details">
		<span class="time">10:30pm</span>
		<span class="event">Flight of the Conchords <span class="venue">@Ebar</span></span>
		<em class="description">Flew the Coop Tour with Toad and the Wet Sprocket.</em>
	</a></li>
</ul>
-->
<!-- /Listings -->

<a class="next_listings" href="#">Next listings &raquo;</a>
