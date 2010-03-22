<ul id="sidebar_home_nav">
	<li class="today( <?=($when == "today" ? "selected" : "")?>"><a class="cufon" href="/events/calendar/today"><?=$dates[0]?></a></li>
	<li class="tomorrow <?=($when == "tomorrow" ? "selected" : "")?>"><a class="cufon" href="/events/calendar/tomorrow"><?=$dates[1]?></a></li>
	<li class="calendar <?=(in_array($when, array("today","tomorrow")) ? "" : "selected")?>"><a class="cufon" href="/events/calendar/<?=$nextday?>"><?=$dates[2]?></a></li>		
</ul>
