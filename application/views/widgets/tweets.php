<?php if (count($tweets) > 0) { ?>
<ul>
<?php foreach( $tweets as $tweet ) { ?>
	<li><div class='title'><?=fmt_date($tweet->created_at, false);?></div>
		<div class='text'><?=$tweet->text;?></div>
	</li>
<? } ?>
</ul>
<?php } ?>