<ul id="<?=strtolower($title)?>" title="<?=$title?>" selected="true">
<?php foreach( $events as $k => $v ) { ?> 
  <li class="group"><?=$k?></li>
	<?php foreach( $v as $event ) { ?> 
		<li><a href="#"><?=$event->title?></a></li>
	<?php } ?>
<?php } ?>
</ul>