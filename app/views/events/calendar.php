<script type="text/javascript" src="/js/doodle-0.1.1.min.js"></script>

<script type="text/javascript">

function animate()
{
	setTimeout("animate()", 500);
}

$(document).ready(function(){
	for( var i = 0; i < 6*7; i++ ) {
		var cvs = $('#cvs_' + i )[0];
		var count = parseInt($(cvs).attr('count'));
		if( count ) {
			var ctx = cvs.getContext('2d');
			ctx.fillStyle = 'rgba('+Math.floor(255-42.5*count)+','+Math.floor(255-42.5*count)+',123, 0.3)';
			//ctx.fillRect(0,0,cvs.width,cvs.height);
			for( ; count > 0; count-- ) {
				var x = Math.floor(Math.random()*(cvs.width));
				var y = Math.floor(Math.random()*(cvs.height));
				var r = Math.max(10,Math.floor(Math.random()*(cvs.height/2))); 
				ctx.fillStyle = 'rgba('+Math.floor(Math.random()*255)+','+Math.floor(Math.random()*255)+','+Math.floor(Math.random()*255)+', 0.7)';
				
				var grad = ctx.createRadialGradient(x-5,y-5,0,x,y,r);//ctx.createLinearGradient( 0, 0, 0, 100 );
				grad.addColorStop(0, "rgba(255,255,255,0.9)");
				grad.addColorStop( 0.9, 'rgba('+Math.floor(Math.random()*255)+','+Math.floor(Math.random()*255)+','+Math.floor(Math.random()*255)+', 0.7)');
				ctx.fillStyle = grad;
				ctx.beginPath();
				ctx.arc(x,y,r,0,Math.PI*2,true);
				ctx.fill();
			}
		}
	}
	animate();
});
</script>

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
	<?php 
	$i = 0;
	foreach( $calinfo as $event ) { ?>
		<li class="day"><a href="<?=$event["day_url"]?>"><span class="number"><?=$event["day_number"]?></span></a>
			<canvas id="cvs_<?=$i?>" day_number="<?=$event["day_number"]?>" count="<?=isset($event["count"]) ? $event["count"] : "0" ?>" width="82" height="46"></canvas>
		</li>		
	<?php $i++; } ?>
	
</ul><!-- /Calendar -->	

</div><!-- /Calendar container -->
