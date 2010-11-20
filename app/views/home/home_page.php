<script type="text/javascript">
$(function(){
	$("#events-preview a[title]").tooltip({ position: "top center", opacity: 0.99, offset: [-60,10], effect: "slide"});
});
</script>

<?php foreach( $parents as $parent ) { ?>
	<a href="/home/section/<?=$parent['id']?>"><?= $parent['name'] ?></a>  ❖
<?php } ?>

<?php if( $events !== NULL ) { ?>
	<a style="float: right" href="/calendar"><span style="font-size: 90%;">...see the full calendar</span> <img src="/img/fancy_right.png" width="18px" style="margin-bottom: -5px"/></a>
	<h3 style="margin: 0; padding: 0; font-style: italic;">Coming up... </h3>
	<div id="events-preview">
		<ul>
		<?php foreach( $events->result() as $event ): ?>
			<li><a href="/events/details/<?=$event->id?>" title="<?=$event->title . '<br/>' . date('M d', strtotime($event->dt_start)) . ' @ '. date('g:m a',strtotime($event->dt_start))?>"><img src="/media/<?=$event->uuid?>" width="70px"\></a></li>
		<? endforeach; ?>
		</ul>
	</div>
<? } ?>

<?php 
	$count = 0;
	foreach( $articles as $article ): ?>
	<div class="article">
		<?php if( count($article->media) > 0 ) { $uuid = $article->media[0]['uuid']; ?>
			<!--<img src="/media/<?=$uuid?>" class="<?= $count % 2 > 0 ? 'img-preview-left' : 'img-preview-right'?>" />-->
      <img src="<?=$article->media[0]['thumbnail']?>" class="<?= $count % 2 > 0 ? 'img-preview-left' : 'img-preview-right'?> <?=$article->media[0]['type']?>"
              />
		<?php $count++; } ?>
		<h2><a href="/article/view/<?=$article->id?>"><?= $article->title ?></a></h2>
		<span class="date"><?=$article->author?>. - <?=date('j M Y',strtotime($article->publish_on))?> - <?=$article->group ?></span>
		<p><?= strlen(trim($article->excerpt)) ? $article->excerpt : implode(' ', array_slice(explode( ' ', $article->body),0,100) ) . '...' ?>
		<p class="read-more"><a href="/article/view/<?=$article->id?>"><em>read more...<img src="/img/big_feature_right_arrow.png" width="18px" style="margin-bottom: -4px"/></em></a><p>
	</div>
<?php 
endforeach; ?>

<?= $pagination ?>