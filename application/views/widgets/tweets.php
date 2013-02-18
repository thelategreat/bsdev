<div id='twitter_feed' class='widget-vertical tooltip' title='Twitter Feed'>
	<ul class="twitter-feed">
	<?php foreach( $tweets as $tweet ) { ?>
		<li><em><?=date('Y-m-d', strtotime($tweet['pubDate']));?></em>
			<br/>
			<?=$tweet['title']?>
		</li>
	<?php } ?>
	</ul>
</div>