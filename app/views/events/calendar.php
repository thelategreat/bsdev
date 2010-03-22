<div id="calendar_overflow"></div>

<ul id="weekday">
	<li class="cufon">Su</li>
	<li class="cufon">Mo</li>
	<li class="cufon">Tu</li>
	<li class="cufon">We</li>
	<li class="cufon">Th</li>
	<li class="cufon">Fr</li>
	<li class="cufon">Sa</li>
</ul>

<div id="calendar_container">

<ul class="calendar">
	
	<?php foreach( $calinfo as $event ) { ?>
		<li class="day">
			<?php if( $event["url"] != "" ) { ?>
				<a href="<?=$event["url"]?>" title="<?=$event["description"]?>">
			<?php } ?>
				<a href="<?=$event["day_url"]?>"><span class="number"><?=$event["day_number"]?></span></a>
				<img src="<?=$event["image"]?>" width="82" height="46" alt="" />
			<?php if( $event["url"] != "" ) { ?>
				</a>			
			<?php } ?>
		</li>		
	<?php } ?>
	
</ul><!-- /Calendar -->	

</div><!-- /Calendar container -->
	