<ul id="<?=strtolower($title)?>" title="<?=$title?>" selected="true">
<?php foreach( $events as $k => $v ) { ?> 
  <li class="group"><?=$k?></li>
	<?php foreach( $v as $row ) { ?> 
		<li><?=$row?></li>
	<?php } ?>
<?php } ?>
</ul>