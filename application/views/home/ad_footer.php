<ul>
<?php foreach( $ads as $ad ): ?>
	<li>
		<?php if( $ad->url ) { ?>
			<a href="/track/<?=$ad->id?>" target="_blank">
		<?php } ?>
		<img title="<?= $ad->title ?>" src="/media/<?=$ad->uuid?>" height="130px" width="230px"/>
		<?php if( $ad->url ) { ?>
			</a>
		<?php } ?>
	</li>
<?php endforeach; ?>
</ul>
