<ul>
<?php foreach( $ads as $ad ): ?>
	<li>
		<?php if( $ad->url ) { ?>
			<a href="<?=$ad->url?>" target="_blank">
		<?php } ?>
		<img title="<?= $ad->title ?>" src="/media/<?=$ad->uuid?>" height="130px" width="230px"/>
		<?php if( $ad->url ) { ?>
			</a>
		<?php } ?>
	</li>
<?php endforeach; ?>
</ul>
<!--
<ul>
	<li>
		<h3>
			Ad Blob 1
		</h3>
		<p>
			Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan.
		</p>
	</li>
	<li>
		<h3>
			Ad Blob 2
		</h3>
		<p>
			Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan.
		</p>
	</li>
	<li>
		<h3>
			Ad Blob 3
		</h3>
		<p>
			Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan.
		</p>
	</li>
</ul>
-->