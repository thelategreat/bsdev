
<?php foreach( $articles->result() as $article ) { ?>
	<div class="article">
		<span class="date">posted: <?=date('j M Y',strtotime($article->publish_on))?></span>
		<h3><?=$article->title?></h3>
		<p><?= strlen(trim($article->excerpt)) ? $article->excerpt : implode(' ', array_slice(explode( ' ', $article->body),0,100)) ?>
		</p>
		<a class="read-more" href="/article/view/<?=$article->id?>">...read more ➤</a>
	</div>
<?php } ?>
